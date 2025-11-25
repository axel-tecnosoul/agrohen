<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}
$db = getPdoConnection();
$id = $_GET['id'] ?? ($_POST['id'] ?? '');
if (!$id) { header('Location: vueltas.php'); exit; }

function fetchVueltaCompleta(PDO $db, $id) {
  $sql = "SELECT v.*, c.patente, ch.nombre AS chofer_nombre FROM vueltas v JOIN camiones c ON c.id = v.id_camion JOIN choferes ch ON ch.id = v.id_chofer WHERE v.id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$id]);
  return $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'cerrar') {
    $km_cierre = (int)$_POST['km_cierre'];
    $fecha_cierre = $_POST['fecha_cierre'];
    $vuelta = fetchVueltaCompleta($db, $id);
    if ($km_cierre < $vuelta['km_salida']) {
      $error = 'El KM de cierre debe ser mayor o igual al de salida';
    } else {
      $stmt = $db->prepare("UPDATE vueltas SET km_cierre = ?, fecha_cierre = ?, estado = 'cerrada', fecha_hora_ultima_modificacion = NOW() WHERE id = ? AND estado = 'abierta'");
      $stmt->execute([$km_cierre, $fecha_cierre, $id]);
    }
  } elseif ($action === 'anular') {
    $stmt = $db->prepare("UPDATE vueltas SET anulado = 1 WHERE id = ? AND estado <> 'liquidada'");
    $stmt->execute([$id]);
  }
  header("Location: vueltas_detalle.php?id=".$id);
  exit;
}

$vuelta = fetchVueltaCompleta($db, $id);
if (!$vuelta) { header('Location: vueltas.php'); exit; }

$anticipos = $db->prepare("SELECT SUM(importe) AS total FROM vueltas_anticipos WHERE id_vuelta = ? AND anulado = 0");
$anticipos->execute([$id]);
$total_anticipos = $anticipos->fetch()['total'] ?? 0;

$viajesStmt = $db->prepare("SELECT v.*, d.destino AS destino_nombre, IFNULL((SELECT SUM(importe) FROM viajes_cobros vc WHERE vc.id_viaje = v.id AND vc.anulado = 0),0) AS cobros_totales, IFNULL((SELECT SUM(importe) FROM viajes_gastos vg WHERE vg.id_viaje = v.id AND vg.anulado = 0),0) AS gastos_totales FROM viajes v JOIN destinos d ON d.id = v.id_destino WHERE v.id_vuelta = ? AND v.anulado = 0 ORDER BY v.fecha");
$viajesStmt->execute([$id]);
$viajes = $viajesStmt->fetchAll();

$totalesStmt = $db->prepare("SELECT IFNULL(SUM(flete_total),0) AS total_fletes FROM viajes WHERE id_vuelta = ? AND anulado = 0");
$totalesStmt->execute([$id]);
$total_fletes = $totalesStmt->fetch()['total_fletes'] ?? 0;
$total_cobros = array_sum(array_column($viajes, 'cobros_totales'));
$total_gastos = array_sum(array_column($viajes, 'gastos_totales'));
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
        <div class="container-fluid">
          <div class="page-header py-3"><h3>Detalle de vuelta</h3></div>
        </div>
        <div class="container-fluid">
          <div class="card mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3"><strong>Camión:</strong> <?=htmlspecialchars($vuelta['patente'])?></div>
                <div class="col-md-3"><strong>Chofer:</strong> <?=htmlspecialchars($vuelta['chofer_nombre'])?></div>
                <div class="col-md-3"><strong>Fecha salida:</strong> <?=$vuelta['fecha_salida']?></div>
                <div class="col-md-3"><strong>KM salida:</strong> <?=$vuelta['km_salida']?></div>
                <div class="col-md-3"><strong>Fecha cierre:</strong> <?=$vuelta['fecha_cierre']?></div>
                <div class="col-md-3"><strong>KM cierre:</strong> <?=$vuelta['km_cierre']?></div>
                <div class="col-md-3"><strong>Estado:</strong> <?=$vuelta['estado']?></div>
                <div class="col-md-3"><strong>Anticipos totales:</strong> $<?=number_format($total_anticipos,2,',','.')?></div>
                <div class="col-md-3"><strong>Total fletes:</strong> $<?=number_format($total_fletes,2,',','.')?></div>
                <div class="col-md-3"><strong>Total cobros:</strong> $<?=number_format($total_cobros,2,',','.')?></div>
                <div class="col-md-3"><strong>Total gastos:</strong> $<?=number_format($total_gastos,2,',','.')?></div>
              </div>
              <div class="mt-3">
                <a class="btn btn-success" href="viajes_abm.php?id_vuelta=<?=$id?>">Agregar viaje</a>
                <?php if (!$vuelta['km_cierre']):?>
                  <button class="btn btn-primary" data-toggle="modal" data-target="#modalCerrar">Cerrar vuelta</button>
                <?php endif;?>
                <?php if ($vuelta['estado']==='cerrada'):?>
                  <a class="btn btn-info" href="liquidaciones_detalle.php?id_vuelta=<?=$id}">Ir a Liquidación</a>
                <?php endif;?>
                <?php if ($vuelta['estado']!=='liquidada'):?>
                  <form method="post" class="d-inline" onsubmit="return confirm('Marcar como anulada?');">
                    <input type="hidden" name="action" value="anular">
                    <input type="hidden" name="id" value="<?=$id?>">
                    <button class="btn btn-danger" type="submit">Marcar anulado</button>
                  </form>
                <?php endif;?>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h5>Viajes</h5></div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="text-center"><tr><th>Fecha</th><th>Destino</th><th>Flete total</th><th>Cobros</th><th>Gastos</th><th>Estado</th><th>Acciones</th></tr></thead>
                  <tbody>
                    <?php foreach($viajes as $vi):?>
                      <tr>
                        <td><?=$vi['fecha']?></td>
                        <td><?=htmlspecialchars($vi['destino_nombre'] ?: $vi['destino'])?></td>
                        <td>$<?=number_format($vi['flete_total'],2,',','.')?></td>
                        <td>$<?=number_format($vi['cobros_totales'],2,',','.')?></td>
                        <td>$<?=number_format($vi['gastos_totales'],2,',','.')?></td>
                        <td><?=$vi['estado']?></td>
                        <td>
                          <a class="btn btn-sm btn-primary" href="viajes_abm.php?id=<?=$vi['id']?>&id_vuelta=<?=$id?>">Editar</a>
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
    <div class="modal fade" id="modalCerrar" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form method="post">
            <div class="modal-header"><h5 class="modal-title">Cerrar vuelta</h5></div>
            <div class="modal-body">
              <input type="hidden" name="action" value="cerrar">
              <input type="hidden" name="id" value="<?=$id?>">
              <div class="form-group"><label>Fecha cierre</label><input type="date" name="fecha_cierre" class="form-control" required></div>
              <div class="form-group"><label>KM cierre</label><input type="number" name="km_cierre" class="form-control" required></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Cerrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
