<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}
$db = getPdoConnection();
$estadoValues = getEnumValues($db, 'vueltas', 'estado');

$choferes = $db->query("SELECT id, nombre FROM choferes WHERE activo = 1 ORDER BY nombre")->fetchAll();
$camiones = $db->query("SELECT id, patente FROM camiones WHERE activo = 1 ORDER BY patente")->fetchAll();

$filters = [
  'id_chofer' => $_GET['id_chofer'] ?? '',
  'id_camion' => $_GET['id_camion'] ?? '',
  'estado' => $_GET['estado'] ?? '',
  'desde' => $_GET['desde'] ?? '',
  'hasta' => $_GET['hasta'] ?? '',
];

$where = ['v.anulado = 0'];
$params = [];
if ($filters['id_chofer']) { $where[] = 'v.id_chofer = ?'; $params[] = $filters['id_chofer']; }
if ($filters['id_camion']) { $where[] = 'v.id_camion = ?'; $params[] = $filters['id_camion']; }
if ($filters['estado']) { $where[] = 'v.estado = ?'; $params[] = $filters['estado']; }
if ($filters['desde']) { $where[] = 'v.fecha_salida >= ?'; $params[] = $filters['desde']; }
if ($filters['hasta']) { $where[] = 'v.fecha_salida <= ?'; $params[] = $filters['hasta']; }

$sql = "SELECT v.*, c.patente, ch.nombre AS chofer_nombre FROM vueltas v JOIN camiones c ON c.id = v.id_camion JOIN choferes ch ON ch.id = v.id_chofer WHERE " . implode(' AND ', $where) . " ORDER BY v.fecha_salida DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$vueltas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <?php include('./views/head_tables.php');?>
  </head>
  <body>
    <div class="loader-wrapper">
      <div class="loader bg-white"><div class="whirly-loader"></div></div>
    </div>
    <?php include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid">
          <div class="page-header py-3"><div class="row"><div class="col"><h3>Vueltas</h3></div></div></div>
        </div>
        <div class="container-fluid">
          <div class="card mb-3">
            <div class="card-header"><h5>Filtros</h5></div>
            <div class="card-body">
              <form method="get" class="form-row">
                <div class="form-group col-md-3">
                  <label>Chofer</label>
                  <select name="id_chofer" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach($choferes as $ch):?>
                      <option value="<?=$ch['id']?>" <?=$filters['id_chofer']==$ch['id']?'selected':''?>><?=htmlspecialchars($ch['nombre'])?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label>Camión</label>
                  <select name="id_camion" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach($camiones as $cm):?>
                      <option value="<?=$cm['id']?>" <?=$filters['id_camion']==$cm['id']?'selected':''?>><?=htmlspecialchars($cm['patente'])?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label>Estado</label>
                  <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach($estadoValues as $estado):?>
                      <option value="<?=$estado?>" <?=$filters['estado']==$estado?'selected':''?>><?=ucfirst($estado)?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label>Desde</label>
                  <input type="date" name="desde" class="form-control" value="<?=$filters['desde']?>">
                </div>
                <div class="form-group col-md-2">
                  <label>Hasta</label>
                  <input type="date" name="hasta" class="form-control" value="<?=$filters['hasta']?>">
                </div>
                <div class="form-group col-md-12 text-right">
                  <button class="btn btn-primary" type="submit">Filtrar</button>
                  <a href="vueltas_abm.php" class="btn btn-success">Nueva vuelta</a>
                </div>
              </form>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h5>Listado</h5></div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="text-center"><tr><th>Fecha salida</th><th>KM salida</th><th>Fecha cierre</th><th>KM cierre</th><th>Chofer</th><th>Camión</th><th>Estado</th><th>Acciones</th></tr></thead>
                  <tbody>
                    <?php foreach($vueltas as $v):?>
                      <tr>
                        <td><?=htmlspecialchars($v['fecha_salida'])?></td>
                        <td><?=htmlspecialchars($v['km_salida'])?></td>
                        <td><?=htmlspecialchars($v['fecha_cierre'])?></td>
                        <td><?=htmlspecialchars($v['km_cierre'])?></td>
                        <td><?=htmlspecialchars($v['chofer_nombre'])?></td>
                        <td><?=htmlspecialchars($v['patente'])?></td>
                        <td><?=htmlspecialchars($v['estado'])?></td>
                        <td>
                          <a class="btn btn-sm btn-info" href="vueltas_abm.php?id=<?=$v['id']?>">Editar / Ver</a>
                          <a class="btn btn-sm btn-secondary" href="vueltas_detalle.php?id=<?=$v['id']}">Detalle</a>
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
      <footer class="footer"><div class="container-fluid"></div></footer>
    </div>
  </body>
</html>
