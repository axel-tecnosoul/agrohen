<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}

$db = getPdoConnection();
$id_vuelta = $_GET['id_vuelta'] ?? $_GET['id'] ?? null;
$id_liquidacion = $_GET['id_liquidacion'] ?? null;
$error = '';

function fetchVuelta(PDO $db, int $id_vuelta): ?array {
  $stmt = $db->prepare("SELECT v.*, c.patente, c.descripcion AS camion_descripcion, ch.nombre AS chofer_nombre FROM vueltas v LEFT JOIN camiones c ON c.id = v.id_camion LEFT JOIN choferes ch ON ch.id = v.id_chofer WHERE v.id = ?");
  $stmt->execute([$id_vuelta]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function fetchLiquidacionById(PDO $db, int $id): ?array {
  $stmt = $db->prepare("SELECT * FROM liquidaciones_choferes WHERE id = ?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function fetchLiquidacionByVuelta(PDO $db, int $id_vuelta): ?array {
  $stmt = $db->prepare("SELECT * FROM liquidaciones_choferes WHERE id_vuelta = ? AND (anulado = 0 OR anulado IS NULL)");
  $stmt->execute([$id_vuelta]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function obtenerTotales(PDO $db, int $id_vuelta, float $entregado): array {
  $total_fleteStmt = $db->prepare("SELECT IFNULL(SUM(flete_total),0) AS total FROM viajes WHERE id_vuelta = ? AND anulado = 0");
  $total_fleteStmt->execute([$id_vuelta]);
  $total_flete = (float)$total_fleteStmt->fetch()['total'];
  $honorarios = $total_flete * 0.15;

  $anticiposStmt = $db->prepare("SELECT IFNULL(SUM(importe),0) AS total FROM vueltas_anticipos WHERE id_vuelta = ? AND anulado = 0");
  $anticiposStmt->execute([$id_vuelta]);
  $anticipos = (float)$anticiposStmt->fetch()['total'];

  $cobrosStmt = $db->prepare("SELECT IFNULL(SUM(vc.importe),0) AS total FROM viajes_cobros vc JOIN viajes v ON v.id = vc.id_viaje WHERE v.id_vuelta = ? AND vc.anulado = 0");
  $cobrosStmt->execute([$id_vuelta]);
  $cobros_chofer = (float)$cobrosStmt->fetch()['total'];

  $gastosStmt = $db->prepare("SELECT IFNULL(SUM(vg.importe),0) AS total FROM viajes_gastos vg JOIN viajes v ON v.id = vg.id_viaje WHERE v.id_vuelta = ? AND vg.anulado = 0");
  $gastosStmt->execute([$id_vuelta]);
  $gastos = (float)$gastosStmt->fetch()['total'];

  $saldo_caja_chofer = $anticipos + $cobros_chofer - $gastos - $entregado;
  $monto_a_pagar = $honorarios - $saldo_caja_chofer;
  $resultado = $monto_a_pagar > 0 ? 'a_pagar' : ($monto_a_pagar < 0 ? 'a_cobrar' : 'saldo_cero');

  return [
    'total_flete' => $total_flete,
    'honorarios' => $honorarios,
    'anticipos' => $anticipos,
    'cobros_chofer' => $cobros_chofer,
    'gastos' => $gastos,
    'saldo_caja_chofer' => $saldo_caja_chofer,
    'monto_a_pagar' => $monto_a_pagar,
    'resultado' => $resultado,
    'entregado_a_empresa_en_efectivo' => $entregado,
  ];
}

function obtenerViajes(PDO $db, int $id_vuelta): array {
  $sql = "SELECT
      v.id,
      v.fecha,
      v.origen,
      v.destino,
      v.flete_total,
      COALESCE((
        SELECT SUM(vc.importe)
        FROM viajes_cobros vc
        WHERE vc.id_viaje = v.id
          AND vc.anulado = 0
      ), 0) AS total_cobros,
      COALESCE((
        SELECT SUM(vg.importe)
        FROM viajes_gastos vg
        WHERE vg.id_viaje = v.id
          AND vg.anulado = 0
      ), 0) AS total_gastos
    FROM viajes v
    WHERE v.id_vuelta = ?
      AND v.anulado = 0
    ORDER BY v.fecha";
  $stmt = $db->prepare($sql);
  $stmt->execute([$id_vuelta]);
  return $stmt->fetchAll();
}

function obtenerPagos(PDO $db, int $id_liquidacion): array {
  $stmt = $db->prepare("SELECT * FROM liquidaciones_choferes_pagos WHERE id_liquidacion = ? ORDER BY fecha DESC, id DESC");
  $stmt->execute([$id_liquidacion]);
  return $stmt->fetchAll();
}

function formatearNumero(float $valor): string {
  return number_format($valor, 2, ',', '.');
}

$liquidacion = null;
if ($id_liquidacion) {
  $liquidacion = fetchLiquidacionById($db, (int)$id_liquidacion);
  if ($liquidacion) {
    $id_vuelta = (int)$liquidacion['id_vuelta'];
  }
}

if ($id_vuelta && !$liquidacion) {
  $liquidacion = fetchLiquidacionByVuelta($db, (int)$id_vuelta);
  if ($liquidacion) {
    $id_liquidacion = $liquidacion['id'];
  }
}

if (!$id_vuelta) {
  header('Location: liquidaciones.php');
  exit;
}

$vuelta = fetchVuelta($db, (int)$id_vuelta);
if (!$vuelta) {
  header('Location: liquidaciones.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'generar') {
    if (!$vuelta['km_cierre']) {
      $error = 'La vuelta debe tener km de cierre';
    } elseif ($vuelta['estado'] === 'liquidada') {
      $error = 'La vuelta ya está liquidada';
    } elseif (!empty($vuelta['anulado'])) {
      $error = 'La vuelta está anulada';
    } else {
      $entregado = (float)($_POST['entregado_a_empresa_en_efectivo'] ?? 0);
      $db->beginTransaction();
      $totales = obtenerTotales($db, (int)$id_vuelta, $entregado);
      $stmt = $db->prepare("INSERT INTO liquidaciones_choferes (id_vuelta, fecha, honorarios, anticipos, cobros_chofer, gastos, saldo_caja_chofer, entregado_a_empresa_en_efectivo, monto_a_pagar, resultado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([
        $id_vuelta,
        date('Y-m-d'),
        $totales['honorarios'],
        $totales['anticipos'],
        $totales['cobros_chofer'],
        $totales['gastos'],
        $totales['saldo_caja_chofer'],
        $entregado,
        $totales['monto_a_pagar'],
        $totales['resultado'],
        $_SESSION['rowUsers']['id_usuario'],
      ]);
      $db->prepare("UPDATE vueltas SET estado = 'liquidada' WHERE id = ?")->execute([$id_vuelta]);
      $db->commit();
      header('Location: liquidaciones_detalle.php?id_liquidacion=' . $db->lastInsertId());
      exit;
    }
  } elseif ($action === 'guardar_pago_liquidacion' || $action === 'pago') {
    $liq_id = (int)($_POST['id_liquidacion'] ?? 0);
    $fecha = $_POST['fecha'] ?? '';
    $forma_pago = $_POST['forma_pago'] ?? '';
    $importe = (float)($_POST['importe'] ?? 0);
    $observaciones = $_POST['observaciones'] ?? null;
    if (!$liq_id) {
      $error = 'Liquidación inexistente';
    } elseif ($importe < 0) {
      $error = 'Importe inválido';
    } elseif (!in_array($forma_pago, getEnumValues($db, 'liquidaciones_choferes_pagos', 'forma_pago'), true)) {
      $error = 'Forma de pago inválida';
    } else {
      $stmt = $db->prepare("INSERT INTO liquidaciones_choferes_pagos (id_liquidacion, fecha, forma_pago, importe, observaciones, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$liq_id, $fecha, $forma_pago, $importe, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario']]);
      header('Location: liquidaciones_detalle.php?id_liquidacion=' . $liq_id);
      exit;
    }
  } elseif ($action === 'anular_pago') {
    $pago_id = (int)($_POST['pago_id'] ?? 0);
    $liq_id = (int)($_POST['id_liquidacion'] ?? 0);
    if ($pago_id && $liq_id) {
      $db->prepare("UPDATE liquidaciones_choferes_pagos SET anulado = 1 WHERE id = ?")->execute([$pago_id]);
      header('Location: liquidaciones_detalle.php?id_liquidacion=' . $liq_id);
      exit;
    }
  }
}

$entregadoActual = $liquidacion ? (float)$liquidacion['entregado_a_empresa_en_efectivo'] : 0;
$baseTotales = obtenerTotales($db, (int)$id_vuelta, $entregadoActual);
$totales = $liquidacion ? [
  'total_flete' => $baseTotales['total_flete'],
  'honorarios' => (float)$liquidacion['honorarios'],
  'anticipos' => (float)$liquidacion['anticipos'],
  'cobros_chofer' => (float)$liquidacion['cobros_chofer'],
  'gastos' => (float)$liquidacion['gastos'],
  'saldo_caja_chofer' => (float)$liquidacion['saldo_caja_chofer'],
  'monto_a_pagar' => (float)$liquidacion['monto_a_pagar'],
  'resultado' => $liquidacion['resultado'],
  'entregado_a_empresa_en_efectivo' => $entregadoActual,
] : $baseTotales;

$viajes = obtenerViajes($db, (int)$id_vuelta);
$pagos = $id_liquidacion ? obtenerPagos($db, (int)$id_liquidacion) : [];
$total_pagado = 0;
foreach ($pagos as $pago) {
  if (empty($pago['anulado'])) {
    $total_pagado += (float)$pago['importe'];
  }
}
$saldo_pendiente = $totales['monto_a_pagar'] - $total_pagado;
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <?php include('./views/head_tables.php');?>
  </head>
  <body>
    <div class="loader-wrapper">
      <div class="loader bg-white"><div class="whirly-loader"></div></div>
    </div><?php
    $mainHeaderTitle = "Liquidaciones choferes";
    include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid pt-3">
          <?php if ($error): ?>
            <div class="alert alert-danger" role="alert"><?=$error?></div>
          <?php endif; ?>

          <div class="card mb-3">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Liquidación del chofer</h5>
              <span class="badge badge-<?=(($vuelta['estado'] === 'liquidada') ? 'success' : 'primary')?> text-uppercase"><?=$vuelta['estado']?></span>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 border-right">
                  <h6 class="mb-3">Resumen de la vuelta</h6>
                  <dl class="row mb-0">
                    <dt class="col-sm-5">Camión</dt>
                    <dd class="col-sm-7"><?=htmlspecialchars(trim(($vuelta['patente'] ?? '') . ' ' . ($vuelta['camion_descripcion'] ?? '')))?></dd>
                    <dt class="col-sm-5">Chofer</dt>
                    <dd class="col-sm-7"><?=htmlspecialchars($vuelta['chofer_nombre'] ?? '');?></dd>
                    <dt class="col-sm-5">Fecha salida / cierre</dt>
                    <dd class="col-sm-7"><?=htmlspecialchars($vuelta['fecha'] ?? '')?> / <?=htmlspecialchars($vuelta['fecha_cierre'] ?? '')?></dd>
                    <dt class="col-sm-5">KM salida / cierre</dt>
                    <dd class="col-sm-7"><?=htmlspecialchars($vuelta['km_salida'] ?? '')?> / <?=htmlspecialchars($vuelta['km_cierre'] ?? '')?></dd>
                    <dt class="col-sm-5">Estado</dt>
                    <dd class="col-sm-7 text-capitalize"><?=htmlspecialchars($vuelta['estado'])?></dd>
                    <dt class="col-sm-5">Observaciones</dt>
                    <dd class="col-sm-7"><?=nl2br(htmlspecialchars($vuelta['observaciones'] ?? ''))?></dd>
                  </dl>
                </div>
                <div class="col-md-6">
                  <h6 class="mb-3">Resumen de liquidación</h6>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Total flete</span><strong>$<?=formatearNumero($totales['total_flete'])?></strong></div>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Honorarios (15%)</span><strong>$<?=formatearNumero($totales['honorarios'])?></strong></div>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Anticipos</span><strong>$<?=formatearNumero($totales['anticipos'])?></strong></div>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Cobros del chofer (en viaje)</span><strong>$<?=formatearNumero($totales['cobros_chofer'])?></strong></div>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Gastos (peajes, gasoil, otros)</span><strong>$<?=formatearNumero($totales['gastos'])?></strong></div>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Saldo caja chofer</span><strong>$<?=formatearNumero($totales['saldo_caja_chofer'])?></strong></div>
                  <div class="d-flex justify-content-between py-1 border-bottom"><span>Entregado a la empresa en efectivo</span><strong>$<?=formatearNumero($totales['entregado_a_empresa_en_efectivo'])?></strong></div>
                  <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="font-weight-bold">Monto a pagar al chofer</span>
                    <span class="font-weight-bold h5 mb-0">$<?=formatearNumero($totales['monto_a_pagar'])?></span>
                  </div>
                  <div class="d-flex justify-content-between py-1">
                    <span>Resultado</span>
                    <span class="text-uppercase font-weight-bold"><?php if ($totales['monto_a_pagar'] > 0): ?>A pagar al chofer<?php elseif ($totales['monto_a_pagar'] < 0): ?>El chofer debe devolver<?php else: ?>Sin saldo pendiente<?php endif; ?></span>
                  </div>
                </div>
              </div>
              <?php if (!$liquidacion): ?>
                <hr>
                <form method="post" class="row align-items-end">
                  <input type="hidden" name="action" value="generar">
                  <div class="form-group col-md-4">
                    <label for="entregado_a_empresa_en_efectivo">Entregado a la empresa en efectivo</label>
                    <input type="number" step="0.01" min="0" name="entregado_a_empresa_en_efectivo" id="entregado_a_empresa_en_efectivo" class="form-control" value="0" required>
                  </div>
                  <div class="form-group col-md-12">
                    <button class="btn btn-primary" type="submit">Confirmar liquidación</button>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Viajes de la vuelta</h5>
              <a class="btn btn-warning btn-sm" href="viajes.php?id_vuelta=<?=$id_vuelta?>"><i class="fa fa-external-link"></i> Ver/editar viajes</a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Origen</th>
                      <th>Destino</th>
                      <th class="text-right">Flete total</th>
                      <th class="text-right">Cobros</th>
                      <th class="text-right">Gastos</th>
                      <th class="text-right">Neto viaje</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($viajes) === 0): ?>
                      <tr><td colspan="7" class="text-center">No hay viajes cargados en esta vuelta.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($viajes as $viaje): ?>
                      <?php $neto = ($viaje['total_cobros'] ?? 0) - ($viaje['total_gastos'] ?? 0); ?>
                      <tr>
                        <td><?=htmlspecialchars($viaje['fecha'])?></td>
                        <td><?=htmlspecialchars($viaje['origen'])?></td>
                        <td><?=htmlspecialchars($viaje['destino'])?></td>
                        <td class="text-right">$<?=formatearNumero((float)$viaje['flete_total'])?></td>
                        <td class="text-right">$<?=formatearNumero((float)$viaje['total_cobros'])?></td>
                        <td class="text-right">$<?=formatearNumero((float)$viaje['total_gastos'])?></td>
                        <td class="text-right font-weight-bold">$<?=formatearNumero($neto)?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <?php if ($liquidacion): ?>
            <div class="card mb-3">
              <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pagos registrados</h5>
                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#modalPagoLiquidacion"><i class="fa fa-plus-square"></i> Agregar pago</button>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-sm mb-0">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Forma de pago</th>
                        <th class="text-right">Importe</th>
                        <th>Observaciones</th>
                        <th class="text-center">Anulado</th>
                        <th class="text-center">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($pagos) === 0): ?>
                        <tr><td colspan="6" class="text-center">No hay pagos registrados.</td></tr>
                      <?php endif; ?>
                      <?php foreach ($pagos as $p): ?>
                        <tr>
                          <td><?=htmlspecialchars($p['fecha'])?></td>
                          <td><?=htmlspecialchars($p['forma_pago'])?></td>
                          <td class="text-right">$<?=formatearNumero((float)$p['importe'])?></td>
                          <td><?=htmlspecialchars($p['observaciones'])?></td>
                          <td class="text-center"><?=$p['anulado'] ? 'Sí' : 'No'?></td>
                          <td class="text-center">
                            <?php if (empty($p['anulado'])): ?>
                              <form method="post" class="d-inline" onsubmit="return confirm('¿Anular pago?');">
                                <input type="hidden" name="action" value="anular_pago">
                                <input type="hidden" name="id_liquidacion" value="<?=$id_liquidacion?>">
                                <input type="hidden" name="pago_id" value="<?=$p['id']?>">
                                <button class="btn btn-sm btn-danger" type="submit">Anular</button>
                              </form>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6">
                    <div class="d-flex justify-content-between"><span>Total pagado</span><strong>$<?=formatearNumero($total_pagado)?></strong></div>
                    <div class="d-flex justify-content-between"><span>Saldo pendiente</span><strong>$<?=formatearNumero($saldo_pendiente)?></strong></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid"></div>
      </footer>
    </div>

    <?php if ($liquidacion): ?>
      <div class="modal fade" id="modalPagoLiquidacion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar pago de liquidación</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post">
              <div class="modal-body">
                <input type="hidden" name="action" value="guardar_pago_liquidacion">
                <input type="hidden" name="id_liquidacion" value="<?=$id_liquidacion?>">
                <div class="form-group">
                  <label for="fecha_pago">Fecha</label>
                  <input type="date" id="fecha_pago" name="fecha" class="form-control" value="<?=date('Y-m-d')?>" required>
                </div>
                <div class="form-group">
                  <label for="forma_pago">Forma de pago</label>
                  <select id="forma_pago" name="forma_pago" class="form-control" required>
                    <?php foreach (getEnumValues($db, 'liquidaciones_choferes_pagos', 'forma_pago') as $fp): ?>
                      <option value="<?=$fp?>"><?=$fp?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="importe_pago">Importe</label>
                  <input type="number" step="0.01" min="0" id="importe_pago" name="importe" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="observaciones_pago">Observaciones</label>
                  <input type="text" id="observaciones_pago" name="observaciones" class="form-control" placeholder="Opcional">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar pago</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>

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
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
  </body>
</html>
