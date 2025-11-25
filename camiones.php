<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}
$db = getPdoConnection();
$message = '';

function fetchCamion(PDO $db, $id)
{
  $stmt = $db->prepare("SELECT * FROM camiones WHERE id = ?");
  $stmt->execute([$id]);
  return $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'save') {
    $id = $_POST['id'] ?? '';
    $patente = trim($_POST['patente'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $km_actual = $_POST['km_actual'] !== '' ? (int)$_POST['km_actual'] : null;
    $activo = isset($_POST['activo']) ? 1 : 0;

    if ($patente === '') {
      $message = 'La patente es obligatoria.';
    } else {
      $stmt = $db->prepare("SELECT id FROM camiones WHERE patente = ? AND id <> IFNULL(?, 0)");
      $stmt->execute([$patente, $id ?: 0]);
      if ($stmt->fetch()) {
        $message = 'Ya existe un camión con la misma patente.';
      } else {
        if ($id) {
          $stmt = $db->prepare("UPDATE camiones SET patente = ?, descripcion = ?, km_actual = ?, activo = ?, id_usuario = ? WHERE id = ?");
          $stmt->execute([$patente, $descripcion ?: null, $km_actual, $activo, $_SESSION['rowUsers']['id_usuario'], $id]);
          $message = 'Camión actualizado.';
        } else {
          $stmt = $db->prepare("INSERT INTO camiones (patente, descripcion, km_actual, activo, id_usuario) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$patente, $descripcion ?: null, $km_actual, $activo, $_SESSION['rowUsers']['id_usuario']]);
          $message = 'Camión creado.';
        }
      }
    }
  } elseif ($action === 'toggle') {
    $id = $_POST['id'] ?? '';
    if ($id) {
      $camion = fetchCamion($db, $id);
      if ($camion) {
        $nuevo = $camion['activo'] ? 0 : 1;
        $stmt = $db->prepare("UPDATE camiones SET activo = ?, fecha_hora_ultima_modificacion = NOW() WHERE id = ?");
        $stmt->execute([$nuevo, $id]);
        $message = 'Estado actualizado.';
      }
    }
  } elseif ($action === 'edit') {
    $id_edit = $_POST['id'];
    $camion_edit = fetchCamion($db, $id_edit);
  }
}

$stmt = $db->query("SELECT * FROM camiones ORDER BY patente");
$camiones = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <?php include('./views/head_tables.php');?>
  </head>
  <body>
    <div class="loader-wrapper">
      <div class="loader bg-white">
        <div class="whirly-loader"></div>
      </div>
    </div>
    <?php include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid">
          <div class="page-header py-3">
            <div class="row">
              <div class="col"><div class="page-header-left"><h3>Camiones</h3></div></div>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <?php if ($message):?>
            <div class="alert alert-info"><?=$message?></div>
          <?php endif;?>
          <div class="row">
            <div class="col-lg-4">
              <div class="card">
                <div class="card-header"><h5><?=isset($camion_edit)?'Editar Camión':'Nuevo Camión'?></h5></div>
                <div class="card-body">
                  <form method="post">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?=isset($camion_edit)?$camion_edit['id']:''?>">
                    <div class="form-group">
                      <label>Patente</label>
                      <input type="text" name="patente" class="form-control" required value="<?=isset($camion_edit)?htmlspecialchars($camion_edit['patente']):''?>">
                    </div>
                    <div class="form-group">
                      <label>Descripción</label>
                      <input type="text" name="descripcion" class="form-control" value="<?=isset($camion_edit)?htmlspecialchars($camion_edit['descripcion']):''?>">
                    </div>
                    <div class="form-group">
                      <label>KM actual</label>
                      <input type="number" name="km_actual" class="form-control" value="<?=isset($camion_edit)&&$camion_edit['km_actual']!==null?$camion_edit['km_actual']:''?>">
                    </div>
                    <div class="form-group form-check">
                      <input type="checkbox" name="activo" class="form-check-input" id="activo" <?=isset($camion_edit)?($camion_edit['activo']?'checked':''):'checked'?>>
                      <label class="form-check-label" for="activo">Activo</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="card">
                <div class="card-header"><h5>Listado</h5></div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead class="text-center"><tr><th>Patente</th><th>Descripción</th><th>KM actual</th><th>Activo</th><th>Acciones</th></tr></thead>
                      <tbody>
                        <?php foreach($camiones as $camion):?>
                          <tr>
                            <td><?=htmlspecialchars($camion['patente'])?></td>
                            <td><?=htmlspecialchars($camion['descripcion'])?></td>
                            <td><?=htmlspecialchars($camion['km_actual'])?></td>
                            <td><?=$camion['activo']?'Sí':'No'?></td>
                            <td>
                              <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?=$camion['id']?>">
                                <button class="btn btn-sm btn-info" type="submit">Editar</button>
                              </form>
                              <form method="post" class="d-inline" onsubmit="return confirm('Cambiar estado?');">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?=$camion['id']?>">
                                <button class="btn btn-sm btn-warning" type="submit"><?=$camion['activo']?'Desactivar':'Activar'?></button>
                              </form>
                            </td>
                          </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer"><div class="container-fluid"></div></footer>
    </div>
  </body>
</html>
