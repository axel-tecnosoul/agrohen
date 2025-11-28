<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) { header("location:./models/redireccionar.php"); exit; }
$db = getPdoConnection();

$choferes = $db->query("SELECT id, nombre FROM choferes WHERE activo = 1 ORDER BY nombre")->fetchAll();
$resultados = getEnumValues($db, 'liquidaciones_choferes', 'resultado');
$filters = [
  'id_chofer' => $_GET['id_chofer'] ?? '',
  'fecha' => $_GET['fecha'] ?? '',
  'resultado' => $_GET['resultado'] ?? '',
];
$where = ['l.anulado = 0'];
$params = [];
if ($filters['id_chofer']) { $where[] = 'v.id_chofer = ?'; $params[] = $filters['id_chofer']; }
if ($filters['fecha']) { $where[] = 'l.fecha = ?'; $params[] = $filters['fecha']; }
if ($filters['resultado']) { $where[] = 'l.resultado = ?'; $params[] = $filters['resultado']; }
$sql = "SELECT l.*, v.id AS id_vuelta, ch.nombre AS chofer_nombre FROM liquidaciones_choferes l JOIN vueltas v ON v.id = l.id_vuelta JOIN choferes ch ON ch.id = v.id_chofer WHERE " . implode(' AND ', $where) . " ORDER BY l.fecha DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$liquidaciones = $stmt->fetchAll();
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
        <div class="container-fluid"><div class="page-header py-3"><h3>Liquidaciones de choferes</h3></div></div>
        <div class="container-fluid">
          <div class="card mb-3"><div class="card-header"><h5>Filtros</h5></div><div class="card-body">
            <form method="get" class="form-row">
              <div class="form-group col-md-4"><label>Chofer</label><select name="id_chofer" class="form-control"><option value="">Todos</option><?php foreach($choferes as $c):?><option value="<?=$c['id']?>" <?=$filters['id_chofer']==$c['id']?'selected':''?>><?=htmlspecialchars($c['nombre'])?></option><?php endforeach;?></select></div>
              <div class="form-group col-md-3"><label>Fecha</label><input type="date" name="fecha" class="form-control" value="<?=$filters['fecha']?>"></div>
              <div class="form-group col-md-3"><label>Resultado</label><select name="resultado" class="form-control"><option value="">Todos</option><?php foreach($resultados as $r):?><option value="<?=$r?>" <?=$filters['resultado']==$r?'selected':''?>><?=$r?></option><?php endforeach;?></select></div>
              <div class="form-group col-md-2 align-self-end"><button class="btn btn-primary" type="submit">Filtrar</button></div>
            </form>
          </div></div>
          <div class="card"><div class="card-header"><h5>Listado</h5></div><div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="text-center"><tr><th>Vuelta</th><th>Chofer</th><th>Honorarios</th><th>Anticipos</th><th>Cobros chofer</th><th>Gastos</th><th>Saldo caja chofer</th><th>Monto a pagar</th><th>Resultado</th><th>Fecha</th><th>Acciones</th></tr></thead>
                <tbody>
                  <?php foreach($liquidaciones as $l):?>
                    <tr>
                      <td><?=$l['id_vuelta']?></td>
                      <td><?=htmlspecialchars($l['chofer_nombre'])?></td>
                      <td>$<?=number_format($l['honorarios'],2,',','.')?></td>
                      <td>$<?=number_format($l['anticipos'],2,',','.')?></td>
                      <td>$<?=number_format($l['cobros_chofer'],2,',','.')?></td>
                      <td>$<?=number_format($l['gastos'],2,',','.')?></td>
                      <td>$<?=number_format($l['saldo_caja_chofer'],2,',','.')?></td>
                      <td>$<?=number_format($l['monto_a_pagar'],2,',','.')?></td>
                      <td><?=$l['resultado']?></td>
                      <td><?=$l['fecha']?></td>
                      <td><a class="btn btn-sm btn-info" href="liquidaciones_detalle.php?id_liquidacion=<?=$l['id']?>">Ver detalle</a></td>
                    </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div></div>
        </div>
      </div>
      <footer class="footer"><div class="container-fluid"></div></footer>
    </div>
  </body>
</html>
