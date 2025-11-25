<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}
$db = getPdoConnection();
$id = $_GET['id'] ?? ($_POST['id'] ?? '');
$id_vuelta = $_GET['id_vuelta'] ?? ($_POST['id_vuelta'] ?? '');

if (!$id_vuelta) { header('Location: vueltas.php'); exit; }

$vueltaStmt = $db->prepare("SELECT * FROM vueltas WHERE id = ?");
$vueltaStmt->execute([$id_vuelta]);
$vuelta = $vueltaStmt->fetch();
if (!$vuelta || $vuelta['estado']==='liquidada') { header('Location: vueltas_detalle.php?id='.$id_vuelta); exit; }

$destinos = $db->query("SELECT id, nombre AS nombre_destino FROM destinos ORDER BY nombre")->fetchAll();
$estadoViaje = getEnumValues($db, 'viajes', 'estado');
$formasPago = getEnumValues($db, 'viajes_cobros', 'forma_pago');
$tiposGasto = getEnumValues($db, 'viajes_gastos', 'tipo');
$error = '';

function fetchViaje(PDO $db, $id) {
  $stmt = $db->prepare("SELECT * FROM viajes WHERE id = ?");
  $stmt->execute([$id]);
  return $stmt->fetch();
}

function fetchCobros(PDO $db, $id_viaje) {
  $stmt = $db->prepare("SELECT * FROM viajes_cobros WHERE id_viaje = ?");
  $stmt->execute([$id_viaje]);
  return $stmt->fetchAll();
}

function fetchGastos(PDO $db, $id_viaje) {
  $stmt = $db->prepare("SELECT * FROM viajes_gastos WHERE id_viaje = ?");
  $stmt->execute([$id_viaje]);
  return $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'save_viaje') {
    $id_destino = $_POST['id_destino'];
    $fecha = $_POST['fecha'];
    $origen = $_POST['origen'];
    $destino_txt = $_POST['destino'];
    $flete_total = (float)$_POST['flete_total'];
    $observaciones = $_POST['observaciones'] ?? null;
    $estado = $_POST['estado'];

    if ($flete_total <= 0) {
      $error = 'El flete total debe ser mayor a cero.';
    } elseif ($fecha < $vuelta['fecha_salida'] || ($vuelta['fecha_cierre'] && $fecha > $vuelta['fecha_cierre'])) {
      $error = 'La fecha del viaje debe estar dentro del rango de la vuelta.';
    } elseif (!in_array($estado, $estadoViaje, true)) {
      $error = 'Estado inválido';
    } else {
      if ($id) {
        $stmt = $db->prepare("UPDATE viajes SET id_destino = ?, fecha = ?, origen = ?, destino = ?, flete_total = ?, observaciones = ?, estado = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$id_destino, $fecha, $origen ?: null, $destino_txt ?: null, $flete_total, $observaciones ?: null, $estado, $_SESSION['rowUsers']['id_usuario'], $id]);
      } else {
        $stmt = $db->prepare("INSERT INTO viajes (id_vuelta, id_destino, fecha, origen, destino, flete_total, observaciones, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_vuelta, $id_destino, $fecha, $origen ?: null, $destino_txt ?: null, $flete_total, $observaciones ?: null, $estado, $_SESSION['rowUsers']['id_usuario']]);
        $id = $db->lastInsertId();
      }
      header('Location: viajes_abm.php?id='.$id.'&id_vuelta='.$id_vuelta);
      exit;
    }
  } elseif ($action === 'save_cobro' && $id) {
    $cobro_id = $_POST['cobro_id'] ?? '';
    $fecha = $_POST['fecha'];
    $forma_pago = $_POST['forma_pago'];
    $importe = (float)$_POST['importe'];
    $referencia = $_POST['referencia'] ?? null;
    if ($importe < 0) { $error = 'Importe inválido'; }
    elseif (!in_array($forma_pago, $formasPago, true)) { $error = 'Forma de pago inválida'; }
    else {
      if ($cobro_id) {
        $stmt = $db->prepare("UPDATE viajes_cobros SET fecha = ?, forma_pago = ?, importe = ?, referencia = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$fecha, $forma_pago, $importe, $referencia ?: null, $_SESSION['rowUsers']['id_usuario'], $cobro_id]);
      } else {
        $stmt = $db->prepare("INSERT INTO viajes_cobros (id_viaje, fecha, forma_pago, importe, referencia, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $fecha, $forma_pago, $importe, $referencia ?: null, $_SESSION['rowUsers']['id_usuario']]);
      }
      header('Location: viajes_abm.php?id='.$id.'&id_vuelta='.$id_vuelta);
      exit;
    }
  } elseif ($action === 'anular_cobro' && $id) {
    $stmt = $db->prepare("UPDATE viajes_cobros SET anulado = 1 WHERE id = ?");
    $stmt->execute([$_POST['cobro_id']]);
    header('Location: viajes_abm.php?id='.$id.'&id_vuelta='.$id_vuelta);
    exit;
  } elseif ($action === 'save_gasto' && $id) {
    $gasto_id = $_POST['gasto_id'] ?? '';
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];
    $importe = (float)$_POST['importe'];
    $detalle = $_POST['detalle'] ?? null;
    if ($importe < 0) { $error = 'Importe inválido'; }
    elseif (!in_array($tipo, $tiposGasto, true)) { $error = 'Tipo inválido'; }
    else {
      if ($gasto_id) {
        $stmt = $db->prepare("UPDATE viajes_gastos SET fecha = ?, tipo = ?, importe = ?, detalle = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$fecha, $tipo, $importe, $detalle ?: null, $_SESSION['rowUsers']['id_usuario'], $gasto_id]);
      } else {
        $stmt = $db->prepare("INSERT INTO viajes_gastos (id_viaje, fecha, tipo, importe, detalle, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $fecha, $tipo, $importe, $detalle ?: null, $_SESSION['rowUsers']['id_usuario']]);
      }
      header('Location: viajes_abm.php?id='.$id.'&id_vuelta='.$id_vuelta);
      exit;
    }
  } elseif ($action === 'anular_gasto' && $id) {
    $stmt = $db->prepare("UPDATE viajes_gastos SET anulado = 1 WHERE id = ?");
    $stmt->execute([$_POST['gasto_id']]);
    header('Location: viajes_abm.php?id='.$id.'&id_vuelta='.$id_vuelta);
    exit;
  }
}

$viaje = $id ? fetchViaje($db, $id) : null;
$cobros = $id ? fetchCobros($db, $id) : [];
$gastos = $id ? fetchGastos($db, $id) : [];
?>
<!DOCTYPE html>
<html lang="es">
  <head><?php include('./views/head_tables.php');?></head>
  <body>
    <div class="loader-wrapper"><div class="loader bg-white"><div class="whirly-loader"></div></div></div>
    <?php include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid"><div class="page-header py-3"><h3><?= $id ? 'Editar viaje' : 'Nuevo viaje'?></h3></div></div>
        <div class="container-fluid">
          <?php if ($error):?><div class="alert alert-danger"><?=$error?></div><?php endif;?>
          <div class="card mb-3"><div class="card-header"><h5>Datos del viaje</h5></div><div class="card-body">
            <form method="post">
              <input type="hidden" name="action" value="save_viaje">
              <input type="hidden" name="id" value="<?=$id?>">
              <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label>Destino</label>
                  <select name="id_destino" class="form-control" required>
                    <?php foreach($destinos as $d):?>
                      <option value="<?=$d['id']?>" <?=($viaje['id_destino'] ?? '')==$d['id']?'selected':''?>><?=htmlspecialchars($d['nombre_destino'])?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="form-group col-md-3"><label>Fecha</label><input type="date" name="fecha" class="form-control" required value="<?=$viaje['fecha'] ?? ''?>"></div>
                <div class="form-group col-md-3"><label>Origen</label><input type="text" name="origen" class="form-control" value="<?=$viaje['origen'] ?? ''?>"></div>
                <div class="form-group col-md-3"><label>Destino</label><input type="text" name="destino" class="form-control" value="<?=$viaje['destino'] ?? ''?>"></div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3"><label>Flete total</label><input type="number" step="0.01" min="0.01" name="flete_total" class="form-control" required value="<?=$viaje['flete_total'] ?? ''?>"></div>
                <div class="form-group col-md-3">
                  <label>Estado</label>
                  <select name="estado" class="form-control">
                    <?php foreach($estadoViaje as $est):?><option value="<?=$est?>" <?=($viaje['estado'] ?? '')==$est?'selected':''?>><?=$est?></option><?php endforeach;?>
                  </select>
                </div>
                <div class="form-group col-md-6"><label>Observaciones</label><input type="text" name="observaciones" class="form-control" value="<?=$viaje['observaciones'] ?? ''?>"></div>
              </div>
              <button class="btn btn-primary" type="submit">Guardar</button>
              <a class="btn btn-secondary" href="vueltas_detalle.php?id=<?=$id_vuelta?>">Volver</a>
            </form>
          </div></div>
          <?php if ($id):?>
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header"><h5>Cobros</h5></div>
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead><tr><th>Fecha</th><th>Forma pago</th><th>Importe</th><th>Referencia</th><th>Anulado</th><th>Acciones</th></tr></thead>
                      <tbody>
                        <?php foreach($cobros as $c):?>
                          <tr>
                            <form method="post">
                              <input type="hidden" name="action" value="save_cobro">
                              <input type="hidden" name="id" value="<?=$id?>">
                              <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                              <input type="hidden" name="cobro_id" value="<?=$c['id']?>">
                              <td><input type="date" name="fecha" class="form-control" value="<?=$c['fecha']?>" required></td>
                              <td><select name="forma_pago" class="form-control"><?php foreach($formasPago as $fp):?><option value="<?=$fp?>" <?=$fp==$c['forma_pago']?'selected':''?>><?=$fp?></option><?php endforeach;?></select></td>
                              <td><input type="number" step="0.01" min="0" name="importe" class="form-control" value="<?=$c['importe']?>"></td>
                              <td><input type="text" name="referencia" class="form-control" value="<?=htmlspecialchars($c['referencia'])?>"></td>
                              <td><?=$c['anulado']?'Sí':'No'?></td>
                              <td>
                                <button class="btn btn-sm btn-primary" type="submit">Actualizar</button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('Anular cobro?');">
                              <input type="hidden" name="action" value="anular_cobro">
                              <input type="hidden" name="id" value="<?=$id?>">
                              <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                              <input type="hidden" name="cobro_id" value="<?=$c['id']?>">
                              <button class="btn btn-sm btn-danger" type="submit">Anular</button>
                            </form>
                              </td>
                          </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    <h6>Nuevo cobro</h6>
                    <form method="post" class="form-row">
                      <input type="hidden" name="action" value="save_cobro">
                      <input type="hidden" name="id" value="<?=$id?>">
                      <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                      <div class="form-group col-md-4"><input type="date" name="fecha" class="form-control" required></div>
                      <div class="form-group col-md-4"><select name="forma_pago" class="form-control"><?php foreach($formasPago as $fp):?><option value="<?=$fp?>"><?=$fp?></option><?php endforeach;?></select></div>
                      <div class="form-group col-md-4"><input type="number" step="0.01" min="0" name="importe" class="form-control" placeholder="Importe" required></div>
                      <div class="form-group col-md-12"><input type="text" name="referencia" class="form-control" placeholder="Referencia"></div>
                      <div class="form-group col-md-12"><button class="btn btn-success" type="submit">Agregar</button></div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header"><h5>Gastos</h5></div>
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead><tr><th>Fecha</th><th>Tipo</th><th>Importe</th><th>Detalle</th><th>Anulado</th><th>Acciones</th></tr></thead>
                      <tbody>
                        <?php foreach($gastos as $g):?>
                          <tr>
                            <form method="post">
                              <input type="hidden" name="action" value="save_gasto">
                              <input type="hidden" name="id" value="<?=$id?>">
                              <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                              <input type="hidden" name="gasto_id" value="<?=$g['id']?>">
                              <td><input type="date" name="fecha" class="form-control" value="<?=$g['fecha']?>" required></td>
                              <td><select name="tipo" class="form-control"><?php foreach($tiposGasto as $tg):?><option value="<?=$tg?>" <?=$tg==$g['tipo']?'selected':''?>><?=$tg?></option><?php endforeach;?></select></td>
                              <td><input type="number" step="0.01" min="0" name="importe" class="form-control" value="<?=$g['importe']?>"></td>
                              <td><input type="text" name="detalle" class="form-control" value="<?=htmlspecialchars($g['detalle'])?>"></td>
                              <td><?=$g['anulado']?'Sí':'No'?></td>
                              <td>
                                <button class="btn btn-sm btn-primary" type="submit">Actualizar</button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('Anular gasto?');">
                              <input type="hidden" name="action" value="anular_gasto">
                              <input type="hidden" name="id" value="<?=$id?>">
                              <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                              <input type="hidden" name="gasto_id" value="<?=$g['id']?>">
                              <button class="btn btn-sm btn-danger" type="submit">Anular</button>
                            </form>
                              </td>
                          </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    <h6>Nuevo gasto</h6>
                    <form method="post" class="form-row">
                      <input type="hidden" name="action" value="save_gasto">
                      <input type="hidden" name="id" value="<?=$id?>">
                      <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                      <div class="form-group col-md-4"><input type="date" name="fecha" class="form-control" required></div>
                      <div class="form-group col-md-4"><select name="tipo" class="form-control"><?php foreach($tiposGasto as $tg):?><option value="<?=$tg?>"><?=$tg?></option><?php endforeach;?></select></div>
                      <div class="form-group col-md-4"><input type="number" step="0.01" min="0" name="importe" class="form-control" placeholder="Importe" required></div>
                      <div class="form-group col-md-12"><input type="text" name="detalle" class="form-control" placeholder="Detalle"></div>
                      <div class="form-group col-md-12"><button class="btn btn-success" type="submit">Agregar</button></div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php endif;?>
        </div>
      </div>
      <footer class="footer"><div class="container-fluid"></div></footer>
    </div>

    <!-- latest jquery-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Funciones -->
    <script src="assets/js/funciones.js"></script>
    <!-- Bootstrap js-->
    <script src="assets/js/bootstrap/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.js"></script>
    <!-- feather icon js-->
    <script src="assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- Sidebar jquery-->
    <script src="assets/js/sidebar-menu.js"></script>
    <script src="assets/js/config.js"></script>
    <!-- Plugins JS start-->
    <!--<script src="assets/js/datatable/datatables/datatable.custom.js"></script>-->
    <script src="assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/jszip.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.autoFill.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.select.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.print.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.scroller.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/custom.js"></script>
    <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <script src="assets/js/select2/select2.full.min.js"></script>
    <script src="assets/js/select2/select2-custom.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    
  </body>
</html>
