<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}
$db = getPdoConnection();
$estadoValues = getEnumValues($db, 'vueltas', 'estado');
$formError = '';
$id = $_GET['id'] ?? ($_POST['id'] ?? '');

$choferes = $db->query("SELECT id, nombre FROM choferes WHERE activo = 1 ORDER BY nombre")->fetchAll();
$camiones = $db->query("SELECT id, patente FROM camiones WHERE activo = 1 ORDER BY patente")->fetchAll();

function fetchVuelta(PDO $db, $id) {
  $stmt = $db->prepare("SELECT * FROM vueltas WHERE id = ?");
  $stmt->execute([$id]);
  return $stmt->fetch();
}

function fetchAnticipos(PDO $db, $id_vuelta) {
  $stmt = $db->prepare("SELECT * FROM vueltas_anticipos WHERE id_vuelta = ? ORDER BY fecha");
  $stmt->execute([$id_vuelta]);
  return $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'save_vuelta') {
    $id_camion = $_POST['id_camion'];
    $id_chofer = $_POST['id_chofer'];
    $fecha_salida = $_POST['fecha_salida'];
    $km_salida = (int)$_POST['km_salida'];
    $observaciones = trim($_POST['observaciones'] ?? '');
    if (!$id_camion || !$id_chofer || !$fecha_salida || $km_salida < 0) {
      $formError = 'Datos de vuelta incompletos.';
    } else {
      if ($id) {
        $stmt = $db->prepare("UPDATE vueltas SET id_camion = ?, id_chofer = ?, fecha_salida = ?, km_salida = ?, observaciones = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$id_camion, $id_chofer, $fecha_salida, $km_salida, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario'], $id]);
      } else {
        $stmt = $db->prepare("INSERT INTO vueltas (id_camion, id_chofer, fecha_salida, km_salida, observaciones, estado, id_usuario) VALUES (?, ?, ?, ?, ?, 'abierta', ?)");
        $stmt->execute([$id_camion, $id_chofer, $fecha_salida, $km_salida, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario']]);
        $id = $db->lastInsertId();
      }
      header("Location: vueltas_abm.php?id=".$id);
      exit;
    }
  } elseif ($action === 'save_anticipo' && $id) {
    $fecha = $_POST['fecha'];
    $forma_pago = $_POST['forma_pago'];
    $importe = (float)$_POST['importe'];
    $observaciones = trim($_POST['observaciones'] ?? '');
    $anticipo_id = $_POST['anticipo_id'] ?? '';
    if ($importe < 0) {
      $formError = 'No se permiten importes negativos.';
    } elseif (!in_array($forma_pago, getEnumValues($db, 'vueltas_anticipos', 'forma_pago'), true)) {
      $formError = 'Forma de pago inválida.';
    } else {
      if ($anticipo_id) {
        $stmt = $db->prepare("UPDATE vueltas_anticipos SET fecha = ?, forma_pago = ?, importe = ?, observaciones = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$fecha, $forma_pago, $importe, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario'], $anticipo_id]);
      } else {
        $stmt = $db->prepare("INSERT INTO vueltas_anticipos (id_vuelta, fecha, forma_pago, importe, observaciones, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $fecha, $forma_pago, $importe, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario']]);
      }
      header("Location: vueltas_abm.php?id=".$id);
      exit;
    }
  } elseif ($action === 'anular_anticipo' && $id) {
    $anticipo_id = $_POST['anticipo_id'];
    $stmt = $db->prepare("UPDATE vueltas_anticipos SET anulado = 1 WHERE id = ?");
    $stmt->execute([$anticipo_id]);
    header("Location: vueltas_abm.php?id=".$id);
    exit;
  }
}

$vuelta = $id ? fetchVuelta($db, $id) : null;
$anticipos = $id ? fetchAnticipos($db, $id) : [];
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <?php include('./views/head_tables.php');?>
  </head>
  <body>
    <div class="loader-wrapper"><div class="loader bg-white"><div class="whirly-loader"></div></div></div>
    <?php include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid">
          <div class="page-header py-3"><h3><?= $id ? 'Editar vuelta' : 'Nueva vuelta'?></h3></div>
        </div>
        <div class="container-fluid">
          <?php if ($formError):?><div class="alert alert-danger"><?=$formError?></div><?php endif;?>
          <div class="card mb-3">
            <div class="card-header"><h5>Datos de vuelta</h5></div>
            <div class="card-body">
              <form method="post">
                <input type="hidden" name="action" value="save_vuelta">
                <input type="hidden" name="id" value="<?=$id?>">
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>Camión</label>
                    <select name="id_camion" class="form-control" required>
                      <option value="">Seleccione</option>
                      <?php foreach($camiones as $c):?>
                        <option value="<?=$c['id']?>" <?=($vuelta['id_camion'] ?? '')==$c['id']?'selected':''?>><?=htmlspecialchars($c['patente'])?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Chofer</label>
                    <select name="id_chofer" class="form-control" required>
                      <option value="">Seleccione</option>
                      <?php foreach($choferes as $c):?>
                        <option value="<?=$c['id']?>" <?=($vuelta['id_chofer'] ?? '')==$c['id']?'selected':''?>><?=htmlspecialchars($c['nombre'])?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Fecha salida</label>
                    <input type="date" name="fecha_salida" class="form-control" required value="<?=$vuelta['fecha_salida'] ?? ''?>">
                  </div>
                  <div class="form-group col-md-3">
                    <label>KM salida</label>
                    <input type="number" name="km_salida" class="form-control" required value="<?=$vuelta['km_salida'] ?? ''?>">
                  </div>
                </div>
                <div class="form-group">
                  <label>Observaciones</label>
                  <textarea name="observaciones" class="form-control" rows="2"><?=$vuelta['observaciones'] ?? ''?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Guardar vuelta</button>
                <?php if ($id):?><a class="btn btn-secondary" href="vueltas_detalle.php?id=<?=$id?>">Ver detalle</a><?php endif;?>
              </form>
            </div>
          </div>
          <?php if ($id):?>
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Anticipos</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive mb-3">
                  <table class="table table-bordered">
                    <thead class="text-center"><tr><th>Fecha</th><th>Forma pago</th><th>Importe</th><th>Observaciones</th><th>Anulado</th><th>Acciones</th></tr></thead>
                    <tbody>
                      <?php foreach($anticipos as $ant):?>
                        <tr>
                          <form method="post" class="form-inline">
                            <input type="hidden" name="action" value="save_anticipo">
                            <input type="hidden" name="id" value="<?=$id?>">
                            <input type="hidden" name="anticipo_id" value="<?=$ant['id']?>">
                            <td><input type="date" name="fecha" class="form-control" value="<?=$ant['fecha']?>" required></td>
                            <td>
                              <select name="forma_pago" class="form-control">
                                <?php foreach(getEnumValues($db, 'vueltas_anticipos', 'forma_pago') as $fp):?>
                                  <option value="<?=$fp?>" <?=$fp==$ant['forma_pago']?'selected':''?>><?=$fp?></option>
                                <?php endforeach;?>
                              </select>
                            </td>
                            <td><input type="number" step="0.01" name="importe" class="form-control" value="<?=$ant['importe']?>" required></td>
                            <td><input type="text" name="observaciones" class="form-control" value="<?=htmlspecialchars($ant['observaciones'])?>"></td>
                            <td><?=$ant['anulado']?'Sí':'No'?></td>
                            <td>
                              <button class="btn btn-sm btn-primary" type="submit">Actualizar</button>
                          </form>
                          <form method="post" class="d-inline" onsubmit="return confirm('Anular anticipo?');">
                            <input type="hidden" name="action" value="anular_anticipo">
                            <input type="hidden" name="id" value="<?=$id?>">
                            <input type="hidden" name="anticipo_id" value="<?=$ant['id']?>">
                            <button class="btn btn-sm btn-danger" type="submit">Anular</button>
                          </form>
                            </td>
                        </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
                <h6>Nuevo anticipo</h6>
                <form method="post" class="form-row">
                  <input type="hidden" name="action" value="save_anticipo">
                  <input type="hidden" name="id" value="<?=$id?>">
                  <div class="form-group col-md-3"><input type="date" name="fecha" class="form-control" required></div>
                  <div class="form-group col-md-3">
                    <select name="forma_pago" class="form-control">
                      <?php foreach(getEnumValues($db, 'vueltas_anticipos', 'forma_pago') as $fp):?>
                        <option value="<?=$fp?>"><?=$fp?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                  <div class="form-group col-md-3"><input type="number" step="0.01" min="0" name="importe" class="form-control" placeholder="Importe" required></div>
                  <div class="form-group col-md-3"><input type="text" name="observaciones" class="form-control" placeholder="Observaciones"></div>
                  <div class="form-group col-md-12"><button class="btn btn-success" type="submit">Agregar</button></div>
                </form>
              </div>
            </div>
          <?php endif;?>
        </div>
      </div>
      <footer class="footer"><div class="container-fluid"></div></footer>
    </div>
  </body>
</html>
