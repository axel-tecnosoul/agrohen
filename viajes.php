<?php
session_start();
include_once('models/pdo_transport.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}

$db = getPdoConnection();
$id_vuelta = isset($_GET['id_vuelta']) ? (int)$_GET['id_vuelta'] : 0;
if (!$id_vuelta) {
  header('Location: vueltas.php');
  exit;
}

$estadoViaje = getEnumValues($db, 'viajes', 'estado');
$formasPagoCobro = getEnumValues($db, 'viajes_cobros', 'forma_pago');
$tiposGasto = getEnumValues($db, 'viajes_gastos', 'tipo');

$accion = $_POST['accion'] ?? '';
if ($accion) {
  $post_id_vuelta = (int)($_POST['id_vuelta'] ?? $id_vuelta);
  if ($accion === 'guardar_viaje') {
    $id_viaje = (int)($_POST['id_viaje'] ?? 0);
    $fecha = $_POST['fecha'] ?? '';
    $origen = trim($_POST['origen'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $flete_total = (float)($_POST['flete_total'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    $observaciones = trim($_POST['observaciones'] ?? '');
    if ($flete_total >= 0 && in_array($estado, $estadoViaje, true)) {
      if ($id_viaje) {
        $stmt = $db->prepare("UPDATE viajes SET fecha = ?, origen = ?, destino = ?, flete_total = ?, estado = ?, observaciones = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$fecha, $origen ?: null, $destino ?: null, $flete_total, $estado, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario'], $id_viaje]);
      } else {
        $stmt = $db->prepare("INSERT INTO viajes (id_vuelta, fecha, origen, destino, flete_total, estado, observaciones, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$post_id_vuelta, $fecha, $origen ?: null, $destino ?: null, $flete_total, $estado, $observaciones ?: null, $_SESSION['rowUsers']['id_usuario']]);
      }
    }
    header('Location: viajes.php?id_vuelta='.$post_id_vuelta);
    exit;
  } elseif ($accion === 'anular_viaje') {
    $id_viaje = (int)($_POST['id_viaje'] ?? 0);
    $id_vuelta = $post_id_vuelta;
    if ($id_viaje && $id_vuelta) {
      $stmt = $db->prepare("UPDATE viajes SET anulado = 1 WHERE id = ?");
      $stmt->execute([$id_viaje]);
    }
    header("Location: viajes.php?id_vuelta=".$id_vuelta);
    exit;
  } elseif ($accion === 'guardar_cobro') {
    $id_cobro = (int)($_POST['id_cobro'] ?? 0);
    $id_viaje = (int)($_POST['id_viaje'] ?? 0);
    $fecha = $_POST['fecha'] ?? '';
    $forma_pago = $_POST['forma_pago'] ?? '';
    $importe = (float)($_POST['importe'] ?? 0);
    $referencia = trim($_POST['referencia'] ?? '');
    if ($id_viaje && in_array($forma_pago, $formasPagoCobro, true)) {
      if ($id_cobro) {
        $stmt = $db->prepare("UPDATE viajes_cobros SET fecha = ?, forma_pago = ?, importe = ?, referencia = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$fecha, $forma_pago, $importe, $referencia ?: null, $_SESSION['rowUsers']['id_usuario'], $id_cobro]);
      } else {
        $stmt = $db->prepare("INSERT INTO viajes_cobros (id_viaje, fecha, forma_pago, importe, referencia, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_viaje, $fecha, $forma_pago, $importe, $referencia ?: null, $_SESSION['rowUsers']['id_usuario']]);
      }
    }
    header('Location: viajes.php?id_vuelta='.$post_id_vuelta);
    exit;
  } elseif ($accion === 'eliminar_cobro') {
    $id_cobro = (int)($_POST['id_cobro'] ?? 0);
    if ($id_cobro) {
      $stmt = $db->prepare("UPDATE viajes_cobros SET anulado = 1 WHERE id = ?");
      $stmt->execute([$id_cobro]);
    }
    header('Location: viajes.php?id_vuelta='.$post_id_vuelta);
    exit;
  } elseif ($accion === 'guardar_gasto') {
    $id_gasto = (int)($_POST['id_gasto'] ?? 0);
    $id_viaje = (int)($_POST['id_viaje'] ?? 0);
    $fecha = $_POST['fecha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $importe = (float)($_POST['importe'] ?? 0);
    $detalle = trim($_POST['detalle'] ?? '');
    if ($id_viaje && in_array($tipo, $tiposGasto, true)) {
      if ($id_gasto) {
        $stmt = $db->prepare("UPDATE viajes_gastos SET fecha = ?, tipo = ?, importe = ?, detalle = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([$fecha, $tipo, $importe, $detalle ?: null, $_SESSION['rowUsers']['id_usuario'], $id_gasto]);
      } else {
        $stmt = $db->prepare("INSERT INTO viajes_gastos (id_viaje, fecha, tipo, importe, detalle, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_viaje, $fecha, $tipo, $importe, $detalle ?: null, $_SESSION['rowUsers']['id_usuario']]);
      }
    }
    header('Location: viajes.php?id_vuelta='.$post_id_vuelta);
    exit;
  } elseif ($accion === 'eliminar_gasto') {
    $id_gasto = (int)($_POST['id_gasto'] ?? 0);
    if ($id_gasto) {
      $stmt = $db->prepare("UPDATE viajes_gastos SET anulado = 1 WHERE id = ?");
      $stmt->execute([$id_gasto]);
    }
    header('Location: viajes.php?id_vuelta='.$post_id_vuelta);
    exit;
  }
}

function obtenerVuelta(PDO $db, int $id_vuelta): ?array {
  $sql = "SELECT v.*, c.patente, c.descripcion AS camion_descripcion, ch.nombre AS chofer_nombre FROM vueltas v JOIN camiones c ON c.id = v.id_camion JOIN choferes ch ON ch.id = v.id_chofer WHERE v.id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$id_vuelta]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function obtenerAnticipos(PDO $db, int $id_vuelta): array {
  $stmt = $db->prepare("SELECT fecha, forma_pago, importe, observaciones, anulado FROM vueltas_anticipos WHERE id_vuelta = ? ORDER BY fecha");
  $stmt->execute([$id_vuelta]);
  return $stmt->fetchAll();
}

function obtenerViajes(PDO $db, int $id_vuelta): array {
  $sql = "SELECT 
      v.id,
      v.fecha,
      v.origen,
      v.destino,
      v.flete_total,
      v.estado,
      v.observaciones,
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

function obtenerCobros(PDO $db, int $id_viaje): array {
  $stmt = $db->prepare("SELECT id, fecha, forma_pago, importe, referencia, anulado FROM viajes_cobros WHERE id_viaje = ? ORDER BY fecha");
  $stmt->execute([$id_viaje]);
  return $stmt->fetchAll();
}

function obtenerGastos(PDO $db, int $id_viaje): array {
  $stmt = $db->prepare("SELECT id, fecha, tipo, importe, detalle, anulado FROM viajes_gastos WHERE id_viaje = ? ORDER BY fecha");
  $stmt->execute([$id_viaje]);
  return $stmt->fetchAll();
}

$vuelta = obtenerVuelta($db, $id_vuelta);
if (!$vuelta) { header('Location: vueltas.php'); exit; }
$anticipos = obtenerAnticipos($db, $id_vuelta);
$viajes = obtenerViajes($db, $id_vuelta);
$cobrosPorViaje = [];
$gastosPorViaje = [];
foreach ($viajes as $viaje) {
  $cobrosPorViaje[$viaje['id']] = obtenerCobros($db, (int)$viaje['id']);
  $gastosPorViaje[$viaje['id']] = obtenerGastos($db, (int)$viaje['id']);
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <?php include('./views/head_tables.php');?>
  </head>
  <body>
    <div class="loader-wrapper"><div class="loader bg-white"><div class="whirly-loader"></div></div></div>
    <?php
    $mainHeaderTitle = 'Viajes de la vuelta N° '.$id_vuelta;
    include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid pt-3">
          <div class="card mb-3">
            <div class="card-header"><h5 class="mb-0">Vuelta y anticipos</h5></div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <h6 class="mb-3">Datos de la vuelta</h6>
                  <table class="table table-sm table-borderless mb-0">
                    <tbody>
                      <tr>
                        <th class="pl-0" scope="row">Camión</th>
                        <td><?=htmlspecialchars($vuelta['patente'].' '.$vuelta['camion_descripcion'])?></td>
                      </tr>
                      <tr>
                        <th class="pl-0" scope="row">Chofer</th>
                        <td><?=htmlspecialchars($vuelta['chofer_nombre'])?></td>
                      </tr>
                      <tr>
                        <th class="pl-0" scope="row">Fecha salida</th>
                        <td><?=$vuelta['fecha_salida']?></td>
                      </tr>
                      <tr>
                        <th class="pl-0" scope="row">KM salida</th>
                        <td><?=$vuelta['km_salida']?></td>
                      </tr>
                      <tr>
                        <th class="pl-0" scope="row">Estado</th>
                        <td><?=$vuelta['estado']?></td>
                      </tr>
                      <tr>
                        <th class="pl-0" scope="row">Observaciones</th>
                        <td><?=htmlspecialchars($vuelta['observaciones'])?></td>
                      </tr>
                    </tbody>
                  </table>
                  <small class="text-muted">Los anticipos se editan desde la pantalla de Vueltas.</small>
                </div>
                <div class="col-md-4">
                  <h6 class="mb-3">Anticipos de la vuelta</h6>
                  <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Forma</th>
                          <th class="text-right">Importe</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($anticipos as $a):?>
                          <tr>
                            <td><?=$a['fecha']?></td>
                            <td><?=$a['forma_pago']?> <?=$a['anulado'] ? '<span class="text-danger">(Anulado)</span>' : ''?></td>
                            <td class="text-right">$<?=number_format($a['importe'], 2, ',', '.')?></td>
                          </tr>
                        <?php endforeach;?>
                        <?php if (!$anticipos):?>
                          <tr><td colspan="3" class="text-center">Sin anticipos cargados</td></tr>
                        <?php endif;?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Viajes de esta vuelta</h5>
              <button type="button" class="btn btn-warning" id="btnAgregarViaje">Agregar viaje</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="tablaViajes">
                  <thead class="text-center">
                    <tr>
                      <th>Fecha</th>
                      <th>Origen</th>
                      <th>Destino</th>
                      <th>Flete total</th>
                      <th class="text-right">Cobros</th>
                      <th class="text-right">Gastos</th>
                      <th class="text-right">Neto viaje</th>
                      <th>Estado</th>
                      <th class="text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($viajes as $v):?>
                      <tr>
                        <td><?=$v['fecha']?></td>
                        <td><?=htmlspecialchars($v['origen'])?></td>
                        <td><?=htmlspecialchars($v['destino'])?></td>
                        <td>$<?=number_format($v['flete_total'], 2, ',', '.')?></td>
                        <td class="text-right">$<?=number_format($v['total_cobros'], 2, ',', '.')?></td>
                        <td class="text-right">$<?=number_format($v['total_gastos'], 2, ',', '.')?></td>
                        <td class="text-right">
                          <?php
                            $neto_viaje = $v['total_cobros'] - $v['total_gastos'];
                            echo '$'.number_format($neto_viaje, 2, ',', '.');
                          ?>
                        </td>
                        <td><?=$v['estado']?></td>
                        <td class="text-center">
                          <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-info btnCobros" data-id="<?=$v['id']?>">Cobros</button>
                            <button type="button" class="btn btn-secondary btnGastos" data-id="<?=$v['id']?>">Gastos</button>
                            <button type="button" class="btn btn-primary btnEditarViaje" data-id="<?=$v['id']?>" data-fecha="<?=$v['fecha']?>" data-origen="<?=htmlspecialchars($v['origen'])?>" data-destino="<?=htmlspecialchars($v['destino'])?>" data-flete="<?=$v['flete_total']?>" data-estado="<?=$v['estado']?>" data-observaciones="<?=htmlspecialchars($v['observaciones'])?>">Editar</button>
                            <form method="post" class="d-inline" onsubmit="return confirm('¿Seguro que desea anular este viaje?');">
                              <input type="hidden" name="accion" value="anular_viaje">
                              <input type="hidden" name="id_viaje" value="<?=$v['id']?>">
                              <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                              <button type="submit" class="btn btn-danger">Anular</button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 footer-copyright"><p class="mb-0"></p></div>
              <div class="col-md-6"><p class="pull-right mb-0"></p></div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <div class="modal fade" id="modalViaje" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Viaje</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form method="post" id="formViaje">
            <input type="hidden" name="accion" value="guardar_viaje">
            <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
            <input type="hidden" name="id_viaje" id="id_viaje">
            <div class="modal-body">
              <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="fecha" id="fecha_viaje" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Origen</label>
                <input type="text" name="origen" id="origen" class="form-control">
              </div>
              <div class="form-group">
                <label>Destino</label>
                <input type="text" name="destino" id="destino" class="form-control">
              </div>
              <div class="form-group">
                <label>Flete total</label>
                <input type="number" step="0.01" min="0" name="flete_total" id="flete_total" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Estado</label>
                <select name="estado" id="estado_viaje" class="form-control">
                  <?php foreach($estadoViaje as $estado):?>
                    <option value="<?=$estado?>"><?=$estado?></option>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCobros" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cobros del viaje</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="tablaCobros">
                <thead class="text-center"><tr><th>Fecha</th><th>Forma de pago</th><th>Importe</th><th>Referencia</th><th>Anulado</th><th>Acciones</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
            <button type="button" class="btn btn-warning btn-sm mb-3" id="btnMostrarCobro">Agregar cobro</button>
            <div id="formCobroWrapper" class="card d-none">
              <div class="card-body">
                <form method="post" id="formCobro">
                  <input type="hidden" name="accion" value="guardar_cobro">
                  <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                  <input type="hidden" name="id_viaje" id="id_viaje_cobro">
                  <input type="hidden" name="id_cobro" id="id_cobro">
                  <div class="form-row">
                    <div class="form-group col-md-3"><label>Fecha</label><input type="date" name="fecha" id="fecha_cobro" class="form-control" required></div>
                    <div class="form-group col-md-3">
                      <label>Forma de pago</label>
                      <select name="forma_pago" id="forma_pago" class="form-control">
                        <?php foreach($formasPagoCobro as $fp):?>
                          <option value="<?=$fp?>"><?=$fp?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                    <div class="form-group col-md-3"><label>Importe</label><input type="number" step="0.01" min="0" name="importe" id="importe_cobro" class="form-control" required></div>
                    <div class="form-group col-md-3"><label>Referencia</label><input type="text" name="referencia" id="referencia" class="form-control"></div>
                  </div>
                  <div class="text-right"><button type="submit" class="btn btn-primary btn-sm">Guardar cobro</button></div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalGastos" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Gastos del viaje</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="tablaGastos">
                <thead class="text-center"><tr><th>Fecha</th><th>Tipo</th><th>Importe</th><th>Detalle</th><th>Anulado</th><th>Acciones</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
            <button type="button" class="btn btn-warning btn-sm mb-3" id="btnMostrarGasto">Agregar gasto</button>
            <div id="formGastoWrapper" class="card d-none">
              <div class="card-body">
                <form method="post" id="formGasto">
                  <input type="hidden" name="accion" value="guardar_gasto">
                  <input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">
                  <input type="hidden" name="id_viaje" id="id_viaje_gasto">
                  <input type="hidden" name="id_gasto" id="id_gasto">
                  <div class="form-row">
                    <div class="form-group col-md-3"><label>Fecha</label><input type="date" name="fecha" id="fecha_gasto" class="form-control" required></div>
                    <div class="form-group col-md-3">
                      <label>Tipo</label>
                      <select name="tipo" id="tipo_gasto" class="form-control">
                        <?php foreach($tiposGasto as $tg):?>
                          <option value="<?=$tg?>"><?=$tg?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                    <div class="form-group col-md-3"><label>Importe</label><input type="number" step="0.01" min="0" name="importe" id="importe_gasto" class="form-control" required></div>
                    <div class="form-group col-md-3"><label>Detalle</label><input type="text" name="detalle" id="detalle_gasto" class="form-control"></div>
                  </div>
                  <div class="text-right"><button type="submit" class="btn btn-primary btn-sm">Guardar gasto</button></div>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
        </div>
      </div>
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
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <script>
      $(function() {
        $('#tablaViajes').DataTable({
          language: { url: 'assets/js/datatable/spanish.json' },
          ordering: false
        });

        var cobrosData = <?php echo json_encode($cobrosPorViaje);?>;
        var gastosData = <?php echo json_encode($gastosPorViaje);?>;

        function limpiarFormViaje() {
          $('#id_viaje').val('');
          $('#fecha_viaje').val('');
          $('#origen').val('');
          $('#destino').val('');
          $('#flete_total').val('');
          $('#estado_viaje').val($('#estado_viaje option:first').val());
          $('#observaciones').val('');
        }

        $('#btnAgregarViaje').on('click', function() {
          limpiarFormViaje();
          $('#modalViaje').modal('show');
        });

        $('.btnEditarViaje').on('click', function() {
          limpiarFormViaje();
          $('#id_viaje').val($(this).data('id'));
          $('#fecha_viaje').val($(this).data('fecha'));
          $('#origen').val($(this).data('origen'));
          $('#destino').val($(this).data('destino'));
          $('#flete_total').val($(this).data('flete'));
          $('#estado_viaje').val($(this).data('estado'));
          $('#observaciones').val($(this).data('observaciones'));
          $('#modalViaje').modal('show');
        });

        function renderCobros(id_viaje) {
          var tbody = $('#tablaCobros tbody');
          tbody.empty();
          var items = cobrosData[id_viaje] || [];
          items.forEach(function(c) {
            var fila = $('<tr>');
            fila.append('<td>' + (c.fecha || '') + '</td>');
            fila.append('<td>' + (c.forma_pago || '') + '</td>');
            fila.append('<td>$' + parseFloat(c.importe || 0).toFixed(2) + '</td>');
            fila.append('<td>' + (c.referencia || '') + '</td>');
            fila.append('<td>' + (parseInt(c.anulado,10) ? 'Sí' : 'No') + '</td>');
            var acciones = $('<td class="text-center">');
            var btnEditar = $('<button type="button" class="btn btn-sm btn-primary mr-1">Editar</button>').on('click', function() {
              $('#formCobroWrapper').removeClass('d-none');
              $('#id_cobro').val(c.id);
              $('#id_viaje_cobro').val(id_viaje);
              $('#fecha_cobro').val(c.fecha);
              $('#forma_pago').val(c.forma_pago);
              $('#importe_cobro').val(c.importe);
              $('#referencia').val(c.referencia);
            });
            acciones.append(btnEditar);
            var formEliminar = $('<form method="post" class="d-inline ml-1" onsubmit="return confirm(\'¿Eliminar cobro?\');"></form>');
            formEliminar.append('<input type="hidden" name="accion" value="eliminar_cobro">');
            formEliminar.append('<input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">');
            formEliminar.append('<input type="hidden" name="id_cobro" value="' + c.id + '">');
            formEliminar.append('<button type="submit" class="btn btn-sm btn-danger">Eliminar</button>');
            acciones.append(formEliminar);
            fila.append(acciones);
            tbody.append(fila);
          });
        }

        function renderGastos(id_viaje) {
          var tbody = $('#tablaGastos tbody');
          tbody.empty();
          var items = gastosData[id_viaje] || [];
          items.forEach(function(g) {
            var fila = $('<tr>');
            fila.append('<td>' + (g.fecha || '') + '</td>');
            fila.append('<td>' + (g.tipo || '') + '</td>');
            fila.append('<td>$' + parseFloat(g.importe || 0).toFixed(2) + '</td>');
            fila.append('<td>' + (g.detalle || '') + '</td>');
            fila.append('<td>' + (parseInt(g.anulado,10) ? 'Sí' : 'No') + '</td>');
            var acciones = $('<td class="text-center">');
            var btnEditar = $('<button type="button" class="btn btn-sm btn-primary mr-1">Editar</button>').on('click', function() {
              $('#formGastoWrapper').removeClass('d-none');
              $('#id_gasto').val(g.id);
              $('#id_viaje_gasto').val(id_viaje);
              $('#fecha_gasto').val(g.fecha);
              $('#tipo_gasto').val(g.tipo);
              $('#importe_gasto').val(g.importe);
              $('#detalle_gasto').val(g.detalle);
            });
            acciones.append(btnEditar);
            var formEliminar = $('<form method="post" class="d-inline ml-1" onsubmit="return confirm(\'¿Eliminar gasto?\');"></form>');
            formEliminar.append('<input type="hidden" name="accion" value="eliminar_gasto">');
            formEliminar.append('<input type="hidden" name="id_vuelta" value="<?=$id_vuelta?>">');
            formEliminar.append('<input type="hidden" name="id_gasto" value="' + g.id + '">');
            formEliminar.append('<button type="submit" class="btn btn-sm btn-danger">Eliminar</button>');
            acciones.append(formEliminar);
            fila.append(acciones);
            tbody.append(fila);
          });
        }

        $('.btnCobros').on('click', function() {
          var id_viaje = $(this).data('id');
          $('#id_viaje_cobro').val(id_viaje);
          $('#formCobro')[0].reset();
          $('#id_cobro').val('');
          $('#formCobroWrapper').addClass('d-none');
          renderCobros(id_viaje);
          $('#modalCobros').modal('show');
        });

        $('.btnGastos').on('click', function() {
          var id_viaje = $(this).data('id');
          $('#id_viaje_gasto').val(id_viaje);
          $('#formGasto')[0].reset();
          $('#id_gasto').val('');
          $('#formGastoWrapper').addClass('d-none');
          renderGastos(id_viaje);
          $('#modalGastos').modal('show');
        });

        $('#btnMostrarCobro').on('click', function() {
          $('#formCobro')[0].reset();
          $('#id_cobro').val('');
          $('#formCobroWrapper').removeClass('d-none');
        });

        $('#btnMostrarGasto').on('click', function() {
          $('#formGasto')[0].reset();
          $('#id_gasto').val('');
          $('#formGastoWrapper').removeClass('d-none');
        });
      });
    </script>
  </body>
</html>
