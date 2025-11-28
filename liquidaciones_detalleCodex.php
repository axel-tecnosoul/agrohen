<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) { header("location:./models/redireccionar.php"); exit; }
$db = getPdoConnection();
$id_vuelta = $_GET['id_vuelta'] ?? null;
$id_liquidacion = $_GET['id_liquidacion'] ?? null;
$liquidacion = null;
$error = '';

function fetchVuelta(PDO $db, $id_vuelta) {
  $stmt = $db->prepare("SELECT v.*, c.patente, ch.nombre AS chofer_nombre FROM vueltas v JOIN camiones c ON c.id = v.id_camion JOIN choferes ch ON ch.id = v.id_chofer WHERE v.id = ?");
  $stmt->execute([$id_vuelta]);
  return $stmt->fetch();
}

if ($id_liquidacion) {
  $liqStmt = $db->prepare("SELECT * FROM liquidaciones_choferes WHERE id = ?");
  $liqStmt->execute([$id_liquidacion]);
  $liquidacion = $liqStmt->fetch();
  $id_vuelta = $liquidacion['id_vuelta'];
}

$vuelta = $id_vuelta ? fetchVuelta($db, $id_vuelta) : null;
if (!$vuelta) { header('Location: liquidaciones.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'generar') {
    if (!$vuelta['km_cierre']) { $error = 'La vuelta debe tener km de cierre'; }
    elseif ($vuelta['estado'] === 'liquidada') { $error = 'La vuelta ya está liquidada'; }
    elseif ($vuelta['anulado']) { $error = 'La vuelta está anulada'; }
    else {
      $entregado = (float)$_POST['entregado_a_empresa_en_efectivo'];
      $db->beginTransaction();
      $totales = obtenerTotales($db, $id_vuelta, $entregado);
      $stmt = $db->prepare("INSERT INTO liquidaciones_choferes (id_vuelta, fecha, honorarios, anticipos, cobros_chofer, gastos, saldo_caja_chofer, entregado_a_empresa_en_efectivo, monto_a_pagar, resultado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$id_vuelta, date('Y-m-d'), $totales['honorarios'], $totales['anticipos'], $totales['cobros_chofer'], $totales['gastos'], $totales['saldo_caja_chofer'], $entregado, $totales['monto_a_pagar'], $totales['resultado'], $_SESSION['rowUsers']['id_usuario']]);
      $db->prepare("UPDATE vueltas SET estado = 'liquidada' WHERE id = ?")->execute([$id_vuelta]);
      $db->commit();
      header('Location: liquidaciones_detalle.php?id_liquidacion='.$db->lastInsertId());
      exit;
    }
  } elseif ($action === 'pago') {
    $liq_id = $_POST['id_liquidacion'];
    $fecha = $_POST['fecha'];
    $forma_pago = $_POST['forma_pago'];
    $importe = (float)$_POST['importe'];
    $observaciones = $_POST['observaciones'] ?? null;
    if ($importe < 0) { $error = 'Importe inválido'; }
    elseif (!in_array($forma_pago, getEnumValues($db, 'liquidaciones_choferes_pagos', 'forma_pago'), true)) { $error = 'Forma de pago inválida'; }
    else {
      $stmt = $db->prepare("INSERT INTO liquidaciones_choferes_pagos (id_liquidacion, fecha, forma_pago, importe, observaciones, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$liq_id, $fecha, $forma_pago, $importe, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario']]);
      header('Location: liquidaciones_detalle.php?id_liquidacion='.$liq_id);
      exit;
    }
  } elseif ($action === 'anular_pago') {
    $pago_id = $_POST['pago_id'];
    $liq_id = $_POST['id_liquidacion'];
    $db->prepare("UPDATE liquidaciones_choferes_pagos SET anulado = 1 WHERE id = ?")->execute([$pago_id]);
    header('Location: liquidaciones_detalle.php?id_liquidacion='.$liq_id);
    exit;
  }
}

function obtenerTotales(PDO $db, $id_vuelta, $entregado) {
  $total_flete = $db->prepare("SELECT IFNULL(SUM(flete_total),0) AS total FROM viajes WHERE id_vuelta = ? AND anulado = 0");
  $total_flete->execute([$id_vuelta]);
  $total_flete = (float)$total_flete->fetch()['total'];
  $honorarios = $total_flete * 0.15;

  $anticipos = $db->prepare("SELECT IFNULL(SUM(importe),0) AS total FROM vueltas_anticipos WHERE id_vuelta = ? AND anulado = 0");
  $anticipos->execute([$id_vuelta]);
  $anticipos = (float)$anticipos->fetch()['total'];

  $cobros_chofer = $db->prepare("SELECT IFNULL(SUM(vc.importe),0) AS total FROM viajes_cobros vc JOIN viajes v ON v.id = vc.id_viaje WHERE v.id_vuelta = ? AND vc.anulado = 0");
  $cobros_chofer->execute([$id_vuelta]);
  $cobros_chofer = (float)$cobros_chofer->fetch()['total'];

  $gastos = $db->prepare("SELECT IFNULL(SUM(vg.importe),0) AS total FROM viajes_gastos vg JOIN viajes v ON v.id = vg.id_viaje WHERE v.id_vuelta = ? AND vg.anulado = 0");
  $gastos->execute([$id_vuelta]);
  $gastos = (float)$gastos->fetch()['total'];

  $saldo_caja_chofer = $anticipos + $cobros_chofer - $gastos - $entregado;
  $monto_a_pagar = $honorarios - $saldo_caja_chofer;
  $resultado = $monto_a_pagar > 0 ? 'a_pagar' : 'a_cobrar';

  return [
    'total_flete' => $total_flete,
    'honorarios' => $honorarios,
    'anticipos' => $anticipos,
    'cobros_chofer' => $cobros_chofer,
    'gastos' => $gastos,
    'saldo_caja_chofer' => $saldo_caja_chofer,
    'monto_a_pagar' => $monto_a_pagar,
    'resultado' => $resultado,
  ];
}

$totales = obtenerTotales($db, $id_vuelta, $liquidacion ? ($liquidacion['entregado_a_empresa_en_efectivo'] ?? 0) : 0);

$pagos = [];
if ($id_liquidacion) {
  $pagosStmt = $db->prepare("SELECT * FROM liquidaciones_choferes_pagos WHERE id_liquidacion = ?");
  $pagosStmt->execute([$id_liquidacion]);
  $pagos = $pagosStmt->fetchAll();
}
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
        <div class="container-fluid"><div class="page-header py-3"><h3>Liquidación</h3></div></div>
        <div class="container-fluid">
          <?php if ($error):?><div class="alert alert-danger"><?=$error?></div><?php endif;?>
          <div class="card mb-3"><div class="card-header"><h5>Datos de vuelta</h5></div><div class="card-body">
            <div class="row">
              <div class="col-md-3"><strong>Vuelta:</strong> <?=$vuelta['id']?></div>
              <div class="col-md-3"><strong>Chofer:</strong> <?=htmlspecialchars($vuelta['chofer_nombre'])?></div>
              <div class="col-md-3"><strong>Camión:</strong> <?=htmlspecialchars($vuelta['patente'])?></div>
              <div class="col-md-3"><strong>Estado:</strong> <?=$vuelta['estado']?></div>
            </div>
          </div></div>
          <div class="card mb-3"><div class="card-header"><h5>Totales</h5></div><div class="card-body">
            <div class="row">
              <div class="col-md-3">Total flete: $<?=number_format($totales['total_flete'],2,',','.')?></div>
              <div class="col-md-3">Honorarios: $<?=number_format($totales['honorarios'],2,',','.')?></div>
              <div class="col-md-3">Anticipos: $<?=number_format($totales['anticipos'],2,',','.')?></div>
              <div class="col-md-3">Cobros chofer: $<?=number_format($totales['cobros_chofer'],2,',','.')?></div>
              <div class="col-md-3">Gastos: $<?=number_format($totales['gastos'],2,',','.')?></div>
              <div class="col-md-3">Saldo caja chofer: $<?=number_format($totales['saldo_caja_chofer'],2,',','.')?></div>
              <div class="col-md-3">Monto a pagar: $<?=number_format($totales['monto_a_pagar'],2,',','.')?></div>
              <div class="col-md-3">Resultado: <?=$totales['resultado']?></div>
            </div>
          </div></div>
          <?php if (!$id_liquidacion):?>
            <div class="card mb-3"><div class="card-header"><h5>Generar liquidación</h5></div><div class="card-body">
              <form method="post">
                <input type="hidden" name="action" value="generar">
                <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                <div class="form-row">
                  <div class="form-group col-md-4"><label>Entregado a empresa en efectivo</label><input type="number" step="0.01" min="0" name="entregado_a_empresa_en_efectivo" class="form-control" value="0"></div>
                </div>
                <button class="btn btn-primary" type="submit">Generar Liquidación</button>
              </form>
            </div></div>
          <?php else:?>
            <div class="card"><div class="card-header"><h5>Pagos registrados</h5></div><div class="card-body">
              <table class="table table-bordered"><thead><tr><th>Fecha</th><th>Forma pago</th><th>Importe</th><th>Observaciones</th><th>Anulado</th><th>Acciones</th></tr></thead><tbody>
                <?php foreach($pagos as $p):?>
                  <tr>
                    <td><?=$p['fecha']?></td>
                    <td><?=$p['forma_pago']?></td>
                    <td>$<?=number_format($p['importe'],2,',','.')?></td>
                    <td><?=htmlspecialchars($p['observaciones'])?></td>
                    <td><?=$p['anulado']?'Sí':'No'?></td>
                    <td>
                      <form method="post" class="d-inline" onsubmit="return confirm('Anular pago?');">
                        <input type="hidden" name="action" value="anular_pago">
                        <input type="hidden" name="id_liquidacion" value="<?=$id_liquidacion?>">
                        <input type="hidden" name="pago_id" value="<?=$p['id']?>">
                        <button class="btn btn-sm btn-danger" type="submit">Anular</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach;?>
              </tbody></table>
              <h6>Registrar pago</h6>
              <form method="post" class="form-row">
                <input type="hidden" name="action" value="pago">
                <input type="hidden" name="id_liquidacion" value="<?=$id_liquidacion?>">
                <div class="form-group col-md-3"><input type="date" name="fecha" class="form-control" required></div>
                <div class="form-group col-md-3"><select name="forma_pago" class="form-control"><?php foreach(getEnumValues($db,'liquidaciones_choferes_pagos','forma_pago') as $fp):?><option value="<?=$fp?>"><?=$fp?></option><?php endforeach;?></select></div>
                <div class="form-group col-md-3"><input type="number" step="0.01" min="0" name="importe" class="form-control" placeholder="Importe" required></div>
                <div class="form-group col-md-3"><input type="text" name="observaciones" class="form-control" placeholder="Observaciones"></div>
                <div class="form-group col-md-12"><button class="btn btn-success" type="submit">Agregar pago</button></div>
              </form>
            </div></div>
          <?php endif;?>
        </div>
      </div>
      <footer class="footer"><div class="container-fluid"></div></footer>
    </div>
  </body>
</html>
