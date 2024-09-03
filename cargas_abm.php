<?php 
session_start();
include_once('models/conexion.php');
include_once("models/administrar_cargas.php");
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
}
$id=0;
if(isset($_GET["id"])){
  $id=$_GET["id"];
}

$cargas = new cargas();

$datosCarga=$cargas->getDatosCarga($id);
$datosCarga=json_decode($datosCarga,true);
//var_dump($datosCarga);
$mostrarMotivo=0;
$anchoColMotivo=0;
if($datosCarga["despachado"]=="Si"){
  $mostrarMotivo=1;
  $anchoColMotivo=0;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include('./views/head_tables.php');?>
    <style>
      /* Estilo para que el borde del select2 sea igual al de los inputs form-control */
      .select2-container .select2-selection--single,
      .select2-container .select2-selection--multiple {
          border: 1px solid #ced4da !important; /* Ajusta el color y el grosor del borde según tus necesidades */
      }

      /* Estilo para resaltar el borde cuando el select2 tiene foco */
      .select2-container .select2-selection--single:focus,
      .select2-container .select2-selection--multiple:focus {
          border-color: #80bdff !important; /* Puedes ajustar el color de resaltado del borde al tener foco */
          box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important; /* Puedes ajustar el estilo de sombra al tener foco según tus necesidades */
      }

      /* Agrega este estilo a tu archivo CSS o en un <style> en tu HTML */
      .select2-container--default .select2-dropdown,
      .select2-container--default .select2-results__option,
      .select2-container--default .select2-results__option {
        /*min-width: 300px; /* Ajusta este valor según sea necesario */
        min-width: max-content !important;
      }

      .select2-container--default .select2-dropdown {
        width: auto; /* Permitir que el ancho sea ajustable */
        min-width: 300px; /* Ajusta este valor según sea necesario */
      }

      .select2-container--default .select2-results__option[aria-disabled=true] {
        display: none;
      }

      #tableDepositos tbody td{
        padding: 0.3rem;
      }

      .modal-content {
        display: flex;
        flex-direction: column;
        max-height: 90vh;
      }

      .modal-header, .modal-footer {
        flex-shrink: 0;
      }

      .modal-body {
        overflow-y: auto;
        flex-grow: 1;
        padding: 20px;
      }

      .select2-container {
        z-index: 2000 !important; /* Ajusta según sea necesario */
      }

      .required{
        border: 1px solid red;
      }
    </style>
  </head>
  <body>
    <!-- Loader starts-->
    <!-- <div class="loader-wrapper">
      <div class="loader bg-white">
        <div class="whirly-loader"> </div>
      </div>
    </div> -->
    <!-- Loader ends--><?php
    include_once('./views/main_header.php');?>
    <!-- Page Header Ends-->
    <!-- Page Body Start-->
    <div class="page-body-wrapper">
      <!-- Page Sidebar Start-->
      <div class="page-sidebar"><?php
        include_once('./views/slideBar.php');?>
      </div>
      <!-- Page Sidebar Ends-->
      <div class="page-body">
        <div class="container-fluid">
          <div class="page-header py-3">
            <div class="row">
              <div class="col">
                <div class="page-header-left">
                  <h3>Cargas</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="cargas.php">Cargas</a></li>
                    <li class="breadcrumb-item active">Gestionar carga</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <div class="row">
            <!-- Ajax Generated content for a column start-->
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header py-3">
                  <h5 style="display: inline-block;vertical-align: middle;">Gestionar Carga N° <?=$_GET["id"]?></h5><?php
                  if($datosCarga["despacho"]==0){?>
                    <button id="btnNuevo" type="button" class="btn btn-warning ml-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar producto</button><?php
                  }?>
                  <span id="id_perfil" class="d-none"><?=$_SESSION["rowUsers"]["id_perfil"]?></span>
                </div>
                <div class="card-body py-1">

                  <!-- Acordion de Bootstrap -->
                  <div id="accordion" class="mb-1">
                    <div class="card border-top-1 border-end-1 border-bottom-1 border-dark">
                      <div class="btn btn-dark text-left" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <span>Detalles de la Carga</span>
                        <!-- <span class="bi bi-caret-right"></span> -->
                        <span class="accicon"><i class="fa fa-angle-right float-right rotate-icon"></i></span>
                      </div>

                      <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body p-2">
                          <div class="row">
                            <div class="col-sm-2 text-right"><strong>Fecha:</strong></div>
                            <div class="col-sm-2" id="fecha_carga"><?=$datosCarga["fecha_formatted"]?></div>
                            <div class="col-sm-3 text-right"><strong>Chofer:</strong></div>
                            <div class="col-sm-5" id="chofer_carga"><?=$datosCarga["chofer"]?></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2 text-right"><strong>Origen:</strong></div>
                            <div class="col-sm-2" id="origen_carga"><?=$datosCarga["origen"]?></div>
                            <div class="col-sm-3 text-right"><strong>Datos adicionales chofer:</strong></div>
                            <div class="col-sm-5" id="datos_adicionales_chofer_carga"><?=$datosCarga["datos_adicionales_chofer"]?></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2 text-right"><strong>Usuario:</strong></div>
                            <div class="col-sm-2" id="usuario"><?=$datosCarga["usuario"]?></div>
                            <div class="col-sm-3 text-right"><strong>Despachado:</strong></div>
                            <div class="col-sm-5" id="fecha_hora_despacho"><?php
                            echo $datosCarga["despachado"];
                            if($datosCarga["fecha_hora_despacho"]!=""){
                              echo " (".$datosCarga["fecha_hora_despacho"].")";
                            }?></div>
                          </div>
                          <input type="hidden" id="id_carga" value="<?=$id?>">
                          <input type="hidden" id="id_chofer" value="<?=$datosCarga["id_chofer"]?>">
                          <input type="hidden" id="id_origen" value="<?=$datosCarga["id_origen"]?>">
                          <input type="hidden" id="id_proveedor_default" value="<?=$datosCarga["id_proveedor"]?>">
                          <input type="hidden" id="despachado" value="<?=$datosCarga["despachado"]?>">
                          <input type="hidden" id="confirmada" value="<?=$datosCarga["confirmada"]?>">
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin del acordeón -->

                  <div class="dt-ext table-responsive">
                    <table class="table table-hover display" id="tablaProductosCarga">
                      <thead class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Producto</th>
                          <!-- <th>Proveedor</th> -->
                          <th style="text-align:right">Kg x bulto</th>
                          <th style="text-align:right">Precio</th>
                          <th style="text-align:right">Total bultos</th>
                          <th style="text-align:right">Total kilos</th>
                          <th style="text-align:right">Total Monto</th>
                          <th class="text-center">Acciones</th>
                          <th class="none">Usuario:</th>
                          <th class="none">Fecha y hora de alta:</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th style="text-align:right" colspan="4">Totales</th>
                          <th style="text-align:right">Total bultos</th>
                          <th style="text-align:right">Total kilos</th>
                          <th style="text-align:right">Total Monto</th>
                          <th class="text-center"></th>
                          <th class="none"></th>
                          <th class="none"></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- Ajax Generated content for a column end-->
          </div>
        </div>
        <!-- Container-fluid Ends-->
      </div>
      <!-- footer start-->
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6 footer-copyright">
              <p class="mb-0"></p>
            </div>
            <div class="col-md-6">
              <p class="pull-right mb-0"></p>
            </div>
          </div>
        </div>
      </footer>
    </div>

    <!--Modal para CRUD admin-->
    <div class="modal fade" id="modalCRUD" tabindex="-1000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_carga_producto" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin" style="display: contents;">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="id_familia" class="col-form-label">Familia:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_familia" required></select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="id_producto" class="col-form-label">Producto:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_producto" required>
                      <option>Seleccione una familia</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="kg_x_bulto" class="col-form-label">Kg x bulto:</label>
                    <input type="number" class="form-control" id="kg_x_bulto" step="0.1" min="0" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="precio_general" class="col-form-label">Precio:</label>
                    <input type="number" class="form-control" id="precio_general" step="1" min="0" required>
                  </div>
                </div>
              </div><?php
              if($mostrarMotivo==1){?>
                <div class="row" id="motivo_group">
                  <div class="col-lg-12">
                    <div class="form-group row align-items-center">
                      <label for="motivo_cambio_producto" class="col-form-label col-sm-4 col-md-3">Motivo de la modificación:</label>
                      <div class="col-sm-8 col-md-9">
                        <input type="text" class="form-control" id="motivo_cambio_producto">
                        <input type="hidden" class="form-control" id="id_producto_aux">
                        <input type="hidden" class="form-control" id="kg_x_bulto_aux">
                        <input type="hidden" class="form-control" id="precio_general_aux">
                      </div>
                    </div>
                  </div>
                </div><?php
              }?>
              <div class="row">
                <div class="col-lg-12">
                  <table id="tableDepositos" class="table table-striped">
                    <thead>
                      <th style="width: 20%">Deposito</th>
                      <th style="width: 15%">Bultos</th>
                      <th style="width: 15%">Kg Total</th>
                      <th style="width: 15%">Precio</th>
                      <th style="width: 15%">Monto Total</th><?php
                      if($mostrarMotivo==1){?>
                        <th style="width: 20%">Motivo</th><?php
                      }?>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <th style="text-align:right">Totales</th>
                      <th class="text-right" id="total_bultos">0</th>
                      <th class="text-right" id="total_kilos">0</th>
                      <th class="text-right"></th>
                      <th class="text-right" id="total_monto">0</th><?php
                      if($mostrarMotivo==1){?>
                        <th style="width: 20%"></th><?php
                      }?>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" id="btnGuardarCargarOtro" class="btn btn-success">Guardar y cargar otro</button>
              <button type="submit" id="btnGuardarCerrar" class="btn btn-dark">Guardar y cerrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--Modal para ver la info de un producto-->
    <div class="modal fade" id="modalVer" tabindex="-1000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mr-4" id="exampleModalLabel">Ver carga de producto</h5>
            <span id="historial" class="d-none"><select id="select_historial"></select></span>
            <span id="id_carga_producto_ver" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin" style="display: contents;">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Familia:</label>
                    <span id="lbl_familia"></span>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="lbl_producto" class="col-form-label font-weight-bold">Nombre:</label>
                    <span id="lbl_producto"></span>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="lbl_kg" class="col-form-label font-weight-bold">Kg x bulto:</label>
                    <span id="lbl_kg"></span>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="lbl_precio_general" class="col-form-label font-weight-bold">Precio:</label>
                    <span id="lbl_precio_general"></span>
                  </div>
                </div>
                <!-- <div class="col-lg-5">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Proveedor:</label>
                    <span id="lbl_proveedor"></span>
                  </div>
                </div> -->
              </div>
              <div class="row">
                 <div class="col-lg-7">
                  <div class="form-group">
                    <label for="lbl_motivo_cambio_producto" class="col-form-label font-weight-bold">Motivo de la modificacion en el producto:</label>
                    <span id="lbl_motivo_cambio_producto"></span>
                  </div>
                </div> 
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="lbl_usuario" class="col-form-label font-weight-bold">Usuario:</label>
                    <span id="lbl_usuario"></span>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="lbl_fecha_hora_alta" class="col-form-label font-weight-bold">Fecha y hora carga:</label>
                    <span id="lbl_fecha_hora_alta"></span>
                  </div>
                </div>
                <div class="col-lg-5 d-none">
                  <div class="form-group">
                    <label for="lbl_fecha_hora_ultima_modificacion" class="col-form-label font-weight-bold">Ultima modificacion:</label>
                    <span id="lbl_fecha_hora_ultima_modificacion"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <table id="tableDepositosVer" class="table table-striped">
                    <thead>
                      <th class="text-center" style="width: 20%">Deposito</th>
                      <th class="text-center" style="width: 12%">Bultos</th>
                      <th class="text-center" style="width: 13%">Kg Total</th>
                      <th class="text-center" style="width: 13%">Precio</th>
                      <th class="text-center" style="width: 15%">Monto Total</th><?php
                      if($mostrarMotivo==1){?>
                        <th class="text-center" style="width: 27%">Motivo</th><?php
                      }?>

                      <!-- <th style="width: 30%">Deposito</th>
                      <th style="width: 40%">Cantidad de bultos</th>
                      <th style="width: 15%">Kg Total</th>
                      <th style="width: 15%">Monto Total</th> -->
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <!-- <th style="text-align:right">Totales</th>
                      <th id="total_bultos">0</th>
                      <th class="text-right" id="total_kilos">0</th>
                      <th class="text-right" id="total_monto">0</th> -->

                      <th style="text-align:right">Totales</th>
                      <th class="text-right" id="total_bultos_ver">0</th>
                      <th class="text-right" id="total_kilos_ver">0</th>
                      <th class="text-right"></th>
                      <th class="text-right" id="total_monto_ver">0</th><?php
                      if($mostrarMotivo==1){?>
                        <th style="width: 20%"></th><?php
                      }?>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modalNuevoProducto" tabindex="-100000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:2000">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#f39c12;color:white">
            <h5 class="modal-title" id="exampleModalLabel">Alta rapida de productos</h5>
            <span id="id_producto" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formProducto">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Familia:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_familia_nuevo_producto" required></select>
                  </div>
                </div>  
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Presentacion:</label>
                    <select class="form-control" style="width: 100%;" id="id_presentacion" required></select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Unidad de Medida:</label>
                    <select class="form-control" style="width: 100%;" id="id_unidad_medida" required></select>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" id="btnGuardarProducto" class="btn btn-dark">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
 
    <!-- latest jquery-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
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
    <script src="assets/js/funciones.js"></script>
    <!-- Plugin used-->
    <script type="text/javascript">
      var accion
      var id_perfil=$("#id_perfil").html()
      var id_carga=$("#id_carga").val();
      var despachado = $("#despachado").val()
      var confirmada = $("#confirmada").val()
      var bandera_buscar_producto=true;

      // Mensaje que se mostrará cuando no se encuentren resultados
      var select2NoResultText = "No hay resultados. Presione ENTER para agregar";

      // Configuración para cada select en un objeto
      var configuraciones = {
          presentacion: {
              selectId: 'id_presentacion',
              administrar: 'presentaciones',
              accion: 'addpresentacion',
              tabla: 'productos',
              campo: 'id_presentacion'
          },
          unidadMedida: {
              selectId: 'id_unidad_medida',
              administrar: 'unidad_medida',
              accion: 'addUnidad_medida',
              tabla: 'unidades_medida',
              campo: 'id_unidad_medida'
          }
      };

      var aProductos = []

      //getDatosCarga();

      $(document).ready(function(){
        console.log("document ready");

        cargarDatosComponentes();
        cargarDatosComponentesNuevoProducto()

        tablaProductosCarga= $('#tablaProductosCarga').DataTable({
          "ajax": {
            "url" : "./models/administrar_cargas.php?accion=traerProductosCarga&id_carga="+id_carga,
            "dataSrc": "",
          },
          "responsive": true,
          "columns":[
            {"data": "id_carga_producto"},
            {render: function(data, type, full, meta) {
              return full.familia+" "+full.producto+" ("+full.presentacion+" - "+full.unidad_medida+")";
            }},
            //{"data": "proveedor"},
            {"data": "kg_x_bulto"},
            //{"data": "total_kilos"},
            {render: function(data, type, full, meta) {
              return formatCurrency(full.precio_general);
            }},
            {render: function(data, type, full, meta) {
              return formatNumber2Decimal(full.total_bultos);
            }},
            {render: function(data, type, full, meta) {
              return formatNumber2Decimal(full.total_kilos);
            }},
            //{"data": "total_monto"},
            {render: function(data, type, full, meta) {
              return formatCurrency(full.total_monto);
            }},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  $buttonsGroup="<div class='text-center'><div class='btn-group'>";

                  $btnVer=`<button class='btn btn-primary btnVer' title="Ver"><i class='fa fa-eye'></i></button>`
                  $btnEditar=`<button class='btn btn-success btnEditar' title="Editar"><i class='fa fa-edit'></i></button>`
                  $btnEliminar=`<button class='btn btn-danger btnBorrar' title="Eliminar"><i class='fa fa-trash-o'></i></button>`
                  
                  if(despachado=="Si"){
                    $btnEliminar=''
                  }
                  if(confirmada==1){
                    $btnEditar=''
                  }

                  if(id_perfil==2){
                    $btnEditar=''
                    $btnEliminar=''
                  }
                  
                  $buttonsGroupEnd=`</div></div>`

                  $btnComplete = $buttonsGroup+$btnEliminar+$btnEditar+$btnVer+$buttonsGroupEnd
                  
                  return $btnComplete;
                };
              }
            },
            {"data": "usuario"},
            {"data": "fecha_hora_alta"},
          ],
          "columnDefs": [
            {
              "targets": [3,4,5],
              "className": 'text-right'
            }
          ],
          "language": idiomaEsp,
          drawCallback: function(settings) {
            if(settings.json){
              let suma_bultos=0;
              let suma_kilos=0;
              let suma_monto=0;
              settings.json.forEach(row => {
                suma_bultos+=parseFloat(row.total_bultos)
                suma_kilos+=parseFloat(row.total_kilos)
                suma_monto+=parseFloat(row.total_monto)
              });
              // Update footer
              var api = this.api();
              $(api.column(4).footer()).html(formatNumber2Decimal(suma_bultos));
              $(api.column(5).footer()).html(formatNumber2Decimal(suma_kilos));
              $(api.column(6).footer()).html(formatCurrency(suma_monto));
              //$("#total_bultos").html(suma_bultos);
              //$("#total_kilos").html(suma_kilos);
            }
          },
          initComplete: function(settings, json){
            $('[title]').tooltip();
          }
        });

        $("#id_familia").on("change", function(e){
          let id_familia=this.value
          let id_producto=undefined
          getProductosByFamilia(id_familia,id_producto)
        })

        $("#btnNuevo").click(function(){
          var $boton = $(this);
          $("#formAdmin").trigger("reset");
          let modal=$('#modalCRUD')
          modal.find(".modal-header").css("background-color", "#17a2b8");
          modal.find(".modal-header").css("color", "white" );
          modal.find(".modal-title").text("Alta de productos para la carga "+<?=$id?>);
          modal.modal('show');
          modal.on('shown.bs.modal', function (e) {
            document.getElementById("id_familia").focus();
          })
          
          $("#btnGuardarCargarOtro").removeClass("d-none")
          $("#btnGuardarCerrar").text("Guardar y cerrar")

          //let id_proveedor_default=$("#id_proveedor_default").val()
          //console.log(id_proveedor_default);
          //$("#id_proveedor").val(id_proveedor_default).change();
          $('#id_familia').val("").change();
          //$('#id_producto').val("").change();

          //$("#motivo_group").addClass("d-none");
          $('#id_familia').attr("disabled",false)
          $('#kg_x_bulto').attr("disabled",false)
          //$('#id_proveedor').attr("disabled",false)

          calcularTotales()

          accion = "addProductoCarga";
        });

        $('#formAdmin').submit(function(e){
          e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
          var $boton = $(this).find(':submit');
          //throw new Error("my error message");

          let cargarOtro=false;
          if ($('#btnGuardarCargarOtro').is(':focus')) {// Verificar si se presionó el botón "btnGuardarCargarOtro"
            cargarOtro=true;
            console.log("Se presionó el botón btnGuardarCargarOtro");
          }else if ($('#btnGuardarCerrar').is(':focus')) {// Verificar si se presionó el botón "btnGuardarCerrar"
            console.log("Se presionó el botón btnGuardarCerrar");
          }

          let id_carga_producto = $.trim($('#id_carga_producto').html());
          let id_carga = $.trim($('#id_carga').val());
          let id_producto = $.trim($('#id_producto').val());
          console.log(id_producto);
          let id_proveedor = $.trim($('#id_proveedor_default').val());
          let kg_x_bulto = $.trim($('#kg_x_bulto').val());
          let precio_general = $.trim($('#precio_general').val());
          let motivo_cambio_producto = $.trim($('#motivo_cambio_producto').val());
          mostrarSpinner($boton);

          let datosDepositos = []; // Array para almacenar los datos de las filas seleccionadas

          let tableDepositosRows = $("#tableDepositos tbody tr");
          tableDepositosRows.each(function(){
            let fila=$(this);
            let cantidad_bultos=fila.find(".cantidad_bultos").val()
            if(cantidad_bultos>0){
              
              let id_deposito=fila.find(".id_deposito").val()
              let id_producto_destino=fila.find(".id_producto_destino").val()
              let tipo_aumento_extra=fila.find(".tipo_aumento_extra").val()
              let valor_extra=fila.find(".valor_extra").val()
              let subtotal_kilos=fila.find(".subtotal_kilos").val()
              let precio_destino=fila.find(".precio_destino").val()
              let motivo_cambio_deposito=fila.find(".motivo_cambio_deposito").val()
              //console.log(motivo_cambio_deposito);

              datosDepositos.push({
                id_deposito: id_deposito,
                id_producto_destino: id_producto_destino,
                tipo_aumento_extra: tipo_aumento_extra,
                valor_extra: valor_extra,
                cantidad_bultos: cantidad_bultos,
                subtotal_kilos: subtotal_kilos,
                precio_destino: precio_destino,
                motivo_cambio_deposito: motivo_cambio_deposito,
              });
            }
          })

          if (datosDepositos.length==0) {
            swal({
              icon: 'error',
              title: 'Ingrese la cantidad de bultos para al menos un deposito'
            });
            restaurarBoton($boton);
            return false;
          }

          $("#btnGuardarCargarOtro").addClass("disabled")
          $("#btnGuardarCerrar").addClass("disabled")

          $.ajax({
            url: "models/administrar_cargas.php",
            type: "POST",
            datatype:"json",
            data:  {accion:accion, id_carga_producto:id_carga_producto, id_carga:id_carga, id_producto:id_producto, id_proveedor:id_proveedor, kg_x_bulto:kg_x_bulto, precio_general:precio_general, motivo_cambio_producto:motivo_cambio_producto, datosDepositos:datosDepositos},
            success: function(data) {
              //console.log(data);
              data = JSON.parse(data);
              //console.log(data);
              if(data.ok==1){
                tablaProductosCarga.ajax.reload(null, false);
                
                swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente',
                  timer: 1000, // 1 segundo
                  buttons: false // Esconde el botón de OK
                }).then(function() {
                  // La función dentro del then se ejecutará después de que el alert se cierre automáticamente
                  if (cargarOtro) {
                    $("#btnNuevo").click();
                    //let id_proveedor_default = $("#id_proveedor_default").val();
                    //$('#id_proveedor').val(id_proveedor_default).change();
                  }
                });
                restaurarBoton($boton);

                $('#modalCRUD').modal('hide');

              }else{
                swal({
                  icon: 'error',
                  title: 'El registro no se insertó!'
                });
                restaurarBoton($boton);
              }

              $("#btnGuardarCargarOtro").removeClass("disabled")
              $("#btnGuardarCerrar").removeClass("disabled")
            }
          });
        });

        $(document).on("click", ".btnEditar", function(){
          $boton = $(this);
          bandera_buscar_producto=false
          let modalCrud=$('#modalCRUD');
          modalCrud.find(".modal-header").css( "background-color", "#22af47");
          modalCrud.find(".modal-header").css( "color", "white" );
          modalCrud.find(".modal-title").text("Editar carga de producto");
          //modalCrud.modal('show');
          $("#formAdmin").trigger("reset");
          fila = $(this).closest("tr");
          let id_carga_producto = fila.find('td:eq(0)').text();

          $("#btnGuardarCargarOtro").addClass("d-none")
          $("#btnGuardarCerrar").text("Guardar")

          $("#id_carga_producto").html(id_carga_producto);
          let datosUpdate = new FormData();
          datosUpdate.append('accion', 'traerProductoDestinosCarga');
          datosUpdate.append('id_carga_producto', id_carga_producto);
          mostrarSpinner($boton);
          $.ajax({
            data: datosUpdate,
            url: './models/administrar_cargas.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            /*beforeSed: function(){
              //$('#procesando').modal('show');
            },*/
            success: function(response){
              accion = "updateProductoCarga";
              let datosInput = JSON.parse(response);
              //console.log(datosInput);

              $('#id_familia').val(datosInput.id_familia).change();
              $('#id_producto_aux').val(datosInput.id_producto);
              //$('#id_producto').val(datosInput.id_producto).change();
              //console.log(datosInput.kg_x_bulto);
              $('#kg_x_bulto').val(datosInput.kg_x_bulto);
              $('#kg_x_bulto_aux').val(datosInput.kg_x_bulto);
              console.log($('#kg_x_bulto'));
              //$('').val(datosInput.kg_x_bulto);
              $('#precio_general, #precio_general_aux').val(datosInput.precio_general);
              //$('').val(datosInput.precio_general);
              //$('#id_proveedor').val(datosInput.id_proveedor).change();

              /*$("#motivo_group").addClass("d-none");
              if(despachado=="Si"){
                //$('#id_familia').attr("disabled",true)
                //$('#kg_x_bulto').attr("disabled",true)
                //$('#id_proveedor').attr("disabled",true)
                $("#motivo_group").removeClass("d-none");
              }*/

              bandera_buscar_producto=true
              getProductosByFamilia(datosInput.id_familia,datosInput.id_producto)

              let destinos=datosInput["destinos"];
              //console.log(destinos);
              let tableDepositosRows = $("#tableDepositos tbody tr");
              tableDepositosRows.each(function(){
                let fila=$(this);
                //console.log("fila",fila);
                let id_deposito=fila.find(".id_deposito").val()

                //buscamos a ver si hay datos del destino
                //let data=destinos.find(({ id_destino }) => id_destino === id_deposito);
                let data = null;
                for (let key in destinos) {
                  if (destinos.hasOwnProperty(key)) {
                    let destino = destinos[key];
                    if (destino.id_destino === id_deposito) {
                      data = destino;
                      break; // Detiene el bucle una vez que se encuentra el destino
                    }
                  }
                }

                /*console.log(id_deposito);
                console.log(data);*/

                precio_destino=datosInput.precio_general;
                id_producto_destino=cantidad_bultos=cantidad_bultos_aux=subtotal_kilos=subtotal_monto=""

                if(data!=undefined){
                  //console.log("data",data);
                  id_producto_destino=data.id_producto_destino;
                  cantidad_bultos=data.cantidad_bultos;
                  cantidad_bultos_aux=data.cantidad_bultos;
                  subtotal_kilos=data.subtotal;
                  precio_destino=data.precio_destino;
                  subtotal_monto=data.monto;
                }

                fila.find(".id_producto_destino").val(id_producto_destino)
                fila.find(".cantidad_bultos").val(cantidad_bultos)
                fila.find(".cantidad_bultos_aux").val(cantidad_bultos)
                fila.find(".subtotal_kilos").val(subtotal_kilos)
                fila.find(".precio_destino").val(precio_destino)
                fila.find(".precio_destino_aux").val(precio_destino)
                fila.find(".subtotal_monto").val(subtotal_monto)
              })
              calcularTotales()
              restaurarBoton($boton);
            }
          });

          modalCrud.modal('show');
        });

        $(document).on("click", ".btnVer", function(){
          $(".modal-header").css( "background-color", "#007bff");
          $(".modal-header").css( "color", "white" );
          //$(".modal-title").text("Ver carga de producto");
          $('#modalVer').modal('show');
          fila = $(this).closest("tr");
          let id_carga_producto = fila.find('td:eq(0)').text();

          $("#id_carga_producto_ver").html(id_carga_producto);

          getDatosVerCargaProducto(id_carga_producto,buscar="actual")

          //$('#modalCRUD').modal('show');
        });

        $(document).on("change", "#select_historial", function(){
          let id_carga_producto=this.value
          let buscar="auditoria"
          if(id_carga_producto=="actual"){
            buscar=id_carga_producto
            id_carga_producto=$("#id_carga_producto_ver").html();
          }

          getDatosVerCargaProducto(id_carga_producto,buscar)
        })

        $('#modalVer').on('hidden.bs.modal', function (event) {
          $("#historial").addClass("d-none")
        })

        //Borrar
        $(document).on("click", ".btnBorrar", function(){
          fila = $(this);
          let id_carga_producto = parseInt($(this).closest('tr').find('td:eq(0)').text());
          let id_carga = $.trim($('#id_carga').val());
          swal({
            title: "Estas seguro?",
            text: "El producto se eliminará para todos los depositos",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              accion = "eliminarProductoCarga";
              $.ajax({
                url: "models/administrar_cargas.php",
                type: "POST",
                datatype:"json",
                data: {accion:accion, id_carga_producto:id_carga_producto, id_carga:id_carga},
                success: function() {
                  //tablaProductosCarga.row(fila.parents('tr')).remove().draw();
                  tablaProductosCarga.ajax.reload(null, false);
                  swal({
                    icon: 'success',
                    title: 'Producto eliminado correctamente'
                  })
                }
              }); 
            } else {
              swal("El registro no se eliminó!");
            }
          })
        });

        $(document).on("input", "#precio_general", function(){
          $("#tableDepositos .precio_destino").val(this.value);
        })

        $(document).on("input", "#kg_x_bulto, #precio_general, .cantidad_bultos, .precio_destino", function(){
          toggleSolicitarMotivo();
          calcularTotales();
        })

        $(document).on("change", "#id_producto", toggleSolicitarMotivo)

        // Función para rotar el ícono cuando se abre el acordeón
        $('#collapseOne').on('show.bs.collapse', function () {
          $('#headingOne .fa').addClass('fa-angle-down').removeClass('fa-angle-right');
        });

        // Función para revertir la rotación del ícono cuando se cierra el acordeón
        $('#collapseOne').on('hide.bs.collapse', function () {
          $('#headingOne .fa').removeClass('fa-angle-down').addClass('fa-angle-right');
        });

        // Detectamos los tipeos en la búsqueda de productos para permitir dar de alta uno nuevo
        $('#id_producto').on('select2:open', function() {
            let searchField = $('.select2-search__field');
            let noResultsShown = false;

            searchField.on('keydown', function(e) {
                if ($('.select2-results__option').text() == select2NoResultText) {
                    noResultsShown = true;
                }
                if (e.key === 'Enter' && noResultsShown) {
                    let searchTerm = $(this).val();
                    $("#id_producto").select2("close");
                    let modalNuevoProducto = $("#modalNuevoProducto");

                    console.log(searchTerm);
                    console.log(modalNuevoProducto.find("#nombre"));

                    modalNuevoProducto.modal("show");
                    modalNuevoProducto.find("#nombre").val(searchTerm);

                    let id_familia = $("#id_familia").val();

                    let id_familia_nuevo_producto = modalNuevoProducto.find("#id_familia_nuevo_producto");
                    id_familia_nuevo_producto.val(id_familia).change().prop('disabled', true);
                    $('#id_presentacion').val("").change();
                    $('#id_unidad_medida').val("").change();

                    function inicializarSelect(config) {
                        agregarOpcionSelect(
                            config.selectId,
                            config.administrar,
                            config.accion,
                            select2NoResultText,
                            config.tabla,
                            config.campo
                        );
                    }

                    // Inicializar los selects dentro del modal utilizando la configuración
                    inicializarSelect(configuraciones.presentacion);
                    inicializarSelect(configuraciones.unidadMedida);

                    noResultsShown = false; // Resetear el flag después de mostrar el modal
                }
            });
        });


        var originalZIndex;
        var originalModalOverflow;

        // Manejar la apertura del segundo modal
        $('#modalNuevoProducto').on('show.bs.modal', function() {
          // Guardar el valor original del z-index
          originalZIndex = $(".modal-backdrop").css("z-index");
          // Ajustar el z-index para oscurecer el primer modal
          $(".modal-backdrop").css("z-index", "1050");

          // Guardar y desactivar el scroll del modal de fondo
          originalOverflow = $('#modalCRUD').css('overflow');
          $('#modalCRUD').css('overflow', 'hidden');

        });

        $('#modalNuevoProducto').on('shown.bs.modal', function (e) {
          //document.getElementById("id_familia").focus();
          $("#nombre").focus();
        })

        // Manejar el cierre del segundo modal
        $('#modalNuevoProducto').on('hidden.bs.modal', function() {
          // Restaurar el valor original del z-index
          $(".modal-backdrop").css("z-index", originalZIndex);

          // Restaurar el scroll del modal de fondo
          $('#modalCRUD').css('overflow', originalOverflow);

          $("body").addClass("modal-open")

        });

        $('#formProducto').submit(function(e){
          e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
          //let id_producto = $.trim($('#id_producto').html());
          let id_familia_nuevo_producto = $.trim($('#id_familia_nuevo_producto').val());
          let nombre = $.trim($('#nombre').val());
          let id_presentacion = $.trim($('#id_presentacion').val());
          let id_unidad_medida = $.trim($('#id_unidad_medida').val());

          $("#btnGuardarProducto").addClass("disabled")

          $.ajax({
            url: "models/administrar_producto.php",
            type: "POST",
            datatype:"json",
            data:  {accion: "addProducto", id_producto: 0, nombre: nombre, id_presentacion: id_presentacion, id_unidad_medida: id_unidad_medida, id_familia: id_familia_nuevo_producto},
            success: function(data) {
              respuestaJson = JSON.parse(data);
              if(respuestaJson.ok=="1"){
                console.log(respuestaJson.id_producto)
                let select_id_producto=$('#id_producto');
                // Agregar el nuevo option al select subyacente
                select_id_producto.append('<option value="'+respuestaJson.id_producto+'">'+nombre+'</option>');

                // Disparar el evento de cambio en el select2 para que se actualice con el nuevo option
                select_id_producto.trigger('change');

                // Seleccionar el nuevo option en el select2
                select_id_producto.val(respuestaJson.id_producto).change();

                select_id_producto.focus();

                $('#modalNuevoProducto').modal("hide")
              }else{
                swal({
                  icon: 'error',
                  title: 'El registro no se insertó!'
                });
              }
              $("#btnGuardarProducto").removeClass("disabled")
            }
          });
        });

        // Manejar el evento select2:select para restablecer el foco en todos los select2
        $('.js-example-basic-single').on('select2:select', function (e) {
          $(this).next('.select2-container').find('.select2-selection').focus(); // Restablecer el foco en el elemento select2
        });

        //si es un campo requerido por algun cambio que haya realizado el usuario, se valida cuando ingresa algo
        $(document).on("input","#motivo_cambio_producto",function(){
          //let t=$(this);
          if(this.required){
            //t.removeClass("is-invalid")
            requerirMotivoCambioProducto(false)
          }else{
            requerirMotivoCambioProducto(true)
            //t.addClass("is-invalid")
          }
        })

        //si es un campo requerido por algun cambio que haya realizado el usuario, se valida cuando ingresa algo
        $(document).on("input",".motivo_cambio_deposito",function(){
          //let t=$(this);
          if(this.value.length>0 && this.required){
            //t.removeClass("is-invalid")
            requerirMotivoCambioDeposito(this,false)
          }else{
            //t.addClass("is-invalid")
            requerirMotivoCambioDeposito(this,true)
          }
        })

      });

      idiomaEsp = {
        "autoFill": {
          "cancel": "Cancelar",
          "fill": "Llenar las celdas con <i>%d<i><\/i><\/i>",
          "fillHorizontal": "Llenar las celdas horizontalmente",
          "fillVertical": "Llenar las celdas verticalmente"
        },
        "decimal": ",",
        "emptyTable": "No hay datos disponibles en la Tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
        "infoFiltered": "Filtrado de _MAX_ entradas totales",
        "infoThousands": ".",
        "lengthMenu": "Mostrar _MENU_ entradas",
        "loadingRecords": "Cargando...",
        "paginate": {
          "first": "Primera",
          "last": "Ultima",
          "next": "Siguiente",
          "previous": "Anterior"
        },
        "processing": "Procesando...",
        "search": "Busqueda:",
        "searchBuilder": {
          "add": "Agregar condición",
          "button": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
          },
          "clearAll": "Quitar todo",
          "condition": "Condición",
          "conditions": {
            "date": {
              "after": "Luego",
              "before": "Luego",
              "between": "Entre",
              "empty": "Vacio",
              "equals": "Igual"
            }
          },
          "data": "Datos",
          "deleteTitle": "Borrar regla de filtrado",
          "leftTitle": "Criterio de alargado",
          "logicAnd": "Y",
          "logicOr": "O",
          "rightTitle": "Criterio de endentado",
          "title": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
          },
          "value": "Valor"
        },
        "searchPlaceholder": "Ingrese caracteres de busqueda",
        "thousands": ".",
        "zeroRecords": "No se encontraron registros que coincidan con la búsqueda",
        "datetime": {
          "previous": "Anterior",
          "next": "Siguiente",
          "hours": "Hora",
          "minutes": "Minuto",
          "seconds": "Segundo"
        },
        "editor": {
          "close": "Cerrar",
          "create": {
            "button": "Nuevo",
            "title": "Crear nueva entrada",
            "submit": "Crear"
          },
          "edit": {
            "button": "Editar",
            "title": "Editar entrada",
            "submit": "Actualizar"
          },
          "remove": {
            "button": "Borrar",
            "title": "Borrar",
            "submit": "Borrar",
            "confirm": {
              "_": "Está seguro que desea borrar %d filas?",
              "1": "Está seguro que desea borrar 1 fila?"
            }
          },
          "multi": {
            "title": "Múltiples valores",
            "info": "La selección contiene diferentes valores para esta entrada. Para editarla y establecer todos los items al mismo valor, clickear o tocar aquí, de otra manera conservarán sus valores individuales.",
            "restore": "Deshacer cambios",
            "noMulti": "Esta entrada se puede editar individualmente, pero no como parte de un grupo."
          }
        }
      }

      function getDatosVerCargaProducto(id_carga_producto,buscar){
        //$("#id_carga_producto").html(id_carga_producto);
        let modalBody=$("#modalVer").find(".modal-body")
        if(buscar=="auditoria"){
          modalBody.css("background-color","beige")
        }else{
          modalBody.css("background-color","white")
        }
        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerProductoDestinosCarga');
        datosUpdate.append('id_carga_producto', id_carga_producto);
        datosUpdate.append('buscar', buscar);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_cargas.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#procesando').modal('show');
          },
          success: function(response){
            //accion = "updateProductoCarga";
            let datosInput = JSON.parse(response);
            //console.log("Datos Input: ", datosInput);

            //console.log(datosInput.historial.length);
            if(buscar=="actual" && datosInput.historial.length>0){
              $("#historial").removeClass("d-none")
              var selectHistorial = document.getElementById("select_historial");
              selectHistorial.innerHTML="";

              //Genero los options del select familias
              $option = document.createElement("option");
              let optionText = document.createTextNode("Actual");
              $option.appendChild(optionText);
              $option.setAttribute("value", "actual");
              //$option.setAttribute("selected", true);
              selectHistorial.appendChild($option);

              let cant=datosInput.historial.length;
              datosInput.historial.forEach((registro)=>{
                $option = document.createElement("option");
                //let text=registro.id_auditoria_producto_destino+" - "+registro.usuario+" ("+registro.fecha_hora+")"
                let text=cant+" - "+registro.usuario+" ("+registro.fecha_hora+")";
                cant--
                let optionText = document.createTextNode(text);
                $option.appendChild(optionText);
                $option.setAttribute("value", registro.id_auditoria_producto_destino);
                selectHistorial.appendChild($option);
              })
              $(selectHistorial).select2()
            }

            $('#lbl_familia').html(datosInput.familia);
            let presentacion=datosInput.presentacion
            let unidad_medida=datosInput.unidad_medida
            let text=datosInput.producto;
            if(presentacion!=undefined && unidad_medida!=undefined){
              text+=" x "+presentacion+" - "+unidad_medida;
            }
            $('#lbl_producto').html(text);
            $('#lbl_proveedor').html(datosInput.proveedor);
            //$('#id_producto').val(datosInput.id_producto).change();
            $('#lbl_kg').html(formatNumber2Decimal(datosInput.kg_x_bulto));
            let precio_general=formatCurrency(datosInput.precio_general);
            /*if(datosInput.motivo_cambio_producto.length>0){
              precio_general+=" (<span style='font-style: italic;'>"+datosInput.motivo_cambio_producto+"</span>)";
            }*/
            $('#lbl_precio_general').html(precio_general);
            $('#lbl_motivo_cambio_producto').html(datosInput.motivo_cambio_producto);
            //$('#id_proveedor').val(datosInput.id_proveedor).change();
            $('#lbl_usuario').html(datosInput.usuario)
            $('#lbl_fecha_hora_alta').html(datosInput.fecha_hora_alta)
            $('#lbl_fecha_hora_ultima_modificacion').html(datosInput.fecha_hora_ultima_modificacion)
            //$('#lbl_fecha_hora_ultima_modificacion').html(datosInput.fecha_hora_ultima_modificacion)
            //let destinos=datosInput["destinos"];
            //console.log("Destinos: " + destinos);

            // Identifico la tabla de destinos
            var tbody = document.querySelector('#tableDepositosVer tbody');
            tbody.innerHTML="";
            let cantidad_bulto_total = 0
            let total_kilos = 0
            let total_monto = 0
            //datosInput.destinos.forEach((destino) => {
            for (let key in datosInput.destinos) {
              let destino = datosInput.destinos[key];
              //console.log(destino)
              // Plantilla para cada fila
              let tipo_aumento_extra=destino.tipo_aumento_extra
              let valor_extra=destino.valor_extra
              let lbl_tipo_aumento_extra=""
              if(tipo_aumento_extra>0){
                //lbl_tipo_aumento_extra=" (+"+tipo_aumento_extra+"%)"
              }
              
              cantidad_bulto_total += parseFloat(destino.cantidad_bultos);
              let subtotal_kilos=destino.subtotal_kilos;
              if(subtotal_kilos==null){
                subtotal_kilos=0;
              }
              console.log(subtotal_kilos);
              total_kilos += parseFloat(subtotal_kilos);
              let monto=destino.monto;
              if(monto==null){
                monto=0;
              }
              console.log(monto);
              total_monto += parseFloat(monto)

              let contenidoFila = `
                <td class="align-middle">${destino.destino}</td>
                <td style="text-align: right;">${formatNumber2Decimal(destino.cantidad_bultos)}</td>
                <td style="text-align: right;">${formatNumber2Decimal(destino.subtotal_kilos)}</td>
                <td style="text-align: right;">${formatCurrency(destino.precio_destino)}</td>
                <td style="text-align: right;">${formatCurrency(destino.monto)}</td>`;

              //console.log(despachado);
              if(despachado=="Si"){
                contenidoFila+= `<td>${destino.motivo_cambio_deposito}</td>`;
              }
              // Crear una nueva fila
              var newRow = document.createElement('tr');
              // Insertar el contenido HTML en la nueva fila
              newRow.innerHTML = contenidoFila;
              // Agregar la fila al tbody
              tbody.appendChild(newRow);
            };

            $('#total_bultos_ver').text(formatNumber2Decimal(cantidad_bulto_total));
            $('#total_kilos_ver').text(formatNumber2Decimal(total_kilos));
            $('#total_monto_ver').text(formatCurrency(total_monto));


          }
        });
      }

      function getProductosByFamilia(id_familia_selected,id_producto){

        /*Identifico el select de productos*/
        $selectProducto = document.getElementById("id_producto");
        $selectProducto.innerHTML="";
        //Genero los options del select productos
        
        aProductos.forEach((producto)=>{
          let id_familia=producto.id_familia
          
          if(id_familia_selected==id_familia){
            
            $option = document.createElement("option");

            let presentacion=producto.presentacion
            let unidad_medida=producto.unidad_medida

            let text=producto.producto
            if(presentacion!=undefined && unidad_medida!=undefined){
              text+=" x "+presentacion+" - "+unidad_medida;
            }
            if(producto.ultimo_precio && despachado=="No"){
              text+=" ($"+producto.ultimo_precio+" | "+producto.ultimo_kg_x_bulto+" Kgs.)"
            }

            let optionText = document.createTextNode(text);
            $option.appendChild(optionText);
            $option.setAttribute("value", producto.id_producto);
            $option.setAttribute("data-idFamilia", id_familia);

            if(id_producto>0 && producto.id_producto==id_producto){
              $option.setAttribute("selected", true);
            }
            $selectProducto.appendChild($option);
          }
        })
        //$($selectProducto).select2()
        if(id_familia_selected>0){
          //$('#id_producto option[value=""]').text("Seleccione...")
          $('#id_producto').select2({
            language: {
              noResults: function() {
                return select2NoResultText;
              }
            },
            placeholder: "Seleccione..."
          });
        }else{
          $('#id_producto').select2({
            placeholder: "Seleccione una familia..."
          })
        }

        if(id_producto==undefined){
          id_producto=0;
        }
        $("#id_producto").val(id_producto).change();

      }

      function getProductosByFamilia2(id_familia,id_producto){
        if(bandera_buscar_producto){
          //console.log("BUSCAMOS LOS PRODUCTOS");
          let datosIniciales = new FormData();
          datosIniciales.append('accion', 'getProductosByFamilia');
          datosIniciales.append('id_familia', id_familia);
          $.ajax({
            data: datosIniciales,
            url: "./models/administrar_cargas.php",
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#addProdLocal').modal('hide');
            },
            success: function(respuesta){
              /*Convierto en json la respuesta del servidor*/
              productosByFamilia = JSON.parse(respuesta);

              /*Identifico el select de familias*/
              $selectProducto = document.getElementById("id_producto");
              $selectProducto.innerHTML="";
              //Genero los options del select familias
              productosByFamilia.forEach((producto)=>{
                $option = document.createElement("option");
                let presentacion=producto.presentacion
                let unidad_medida=producto.unidad_medida
                let text=producto.producto
                if(presentacion!=undefined && unidad_medida!=undefined){
                  text+=" x "+presentacion+" - "+unidad_medida;
                }
                if(producto.ultimo_precio && despachado=="No"){
                  text+=" ($"+producto.ultimo_precio+" | "+producto.ultimo_kg_x_bulto+" Kgs.)"
                }
                let optionText = document.createTextNode(text);
                $option.appendChild(optionText);
                
                $option.setAttribute("value", producto.id_producto);
                if(id_producto==producto.id_producto){
                  $option.setAttribute("selected", "selected");
                }
                $selectProducto.appendChild($option);
              })

              $($selectProducto).select2({
                language: {
                  noResults: function() {
                    return select2NoResultText;
                  }
                }
              })
              
              if(despachado=="Si"){
                //$selectProducto.disabled=true
              }
              //console.log("PRODUCTOS CARGADOS");
              if(id_producto>0){
                $("#id_producto").val(id_producto).change();
                //console.log("PRODUCTO ASIGNADO");
              }
            }
          });
        }
      }

      function haCambiadoProducto(){
        let id_producto = $("#id_producto").val()
        if(isNaN(id_producto) || !id_producto){
          id_producto=0;
        }
        //console.log("id_producto",id_producto);
        let id_producto_aux = $("#id_producto_aux").val()
        if(isNaN(id_producto_aux) || !id_producto_aux){
          id_producto_aux=0;
        }
        //console.log("id_producto_aux",id_producto_aux);
        //console.log("");
        if(id_producto_aux!=undefined && id_producto_aux!=id_producto && id_producto!="Seleccione una familia"){
          return true;
        }else{
          return false;
        }
      }

      function haCambiadoKgPorBulto(){
        let kg_x_bulto = $("#kg_x_bulto").val()
        //console.log("kg_x_bulto",kg_x_bulto);
        kg_x_bulto = parseFloat(kg_x_bulto)
        //console.log("kg_x_bulto",kg_x_bulto);
        if(isNaN(kg_x_bulto)){
          kg_x_bulto=0;
        }
        //console.log("kg_x_bulto",kg_x_bulto);
        let kg_x_bulto_aux = parseFloat($("#kg_x_bulto_aux").val())
        if(isNaN(kg_x_bulto_aux)){
          kg_x_bulto_aux=0;
        }
        //console.log("kg_x_bulto_aux",kg_x_bulto_aux);
        //console.log("");

        if(kg_x_bulto_aux!=undefined && kg_x_bulto_aux!=kg_x_bulto){
          return true;
        }else{
          return false;
        }
      }

      function haCambiadoPrecioGeneral(){
        let precio_general = $("#precio_general").val()
        //console.log("precio_general",precio_general);
        precio_general = parseFloat(precio_general)
        //console.log("precio_general",precio_general);
        if(isNaN(precio_general)){
          precio_general=0;
        }
        //console.log("precio_general",precio_general);
        let precio_general_aux = parseFloat($("#precio_general_aux").val())
        if(isNaN(precio_general_aux)){
          precio_general_aux=0;
        }
        //console.log("precio_general_aux",precio_general_aux);
        //console.log("");

        if(precio_general_aux!=undefined && precio_general_aux!=precio_general){
          return true;
        }else{
          return false;
        }
      }

      function haCambiadoCantidadBultos(fila){
        let cantidad_bultos = parseFloat(fila.find(".cantidad_bultos").val())
        if(isNaN(cantidad_bultos)){
          cantidad_bultos=0;
        }
        //console.log("cantidad_bultos",cantidad_bultos);
        let cantidad_bultos_aux = parseFloat(fila.find(".cantidad_bultos_aux").val())
        if(isNaN(cantidad_bultos_aux)){
          cantidad_bultos_aux=0;
        }
        //console.log("cantidad_bultos_aux",cantidad_bultos_aux);

        if(cantidad_bultos_aux!=undefined && cantidad_bultos_aux!=cantidad_bultos){
          return true;
        }else{
          return false;
        }
      }

      function haCambiadoPrecioDestino(fila){
        let precio_destino = parseFloat(fila.find(".precio_destino").val())
        if(isNaN(precio_destino)){
          precio_destino=0;
        }
        //console.log("precio_destino",precio_destino);
        let precio_destino_aux = parseFloat(fila.find(".precio_destino_aux").val())
        if(isNaN(precio_destino_aux)){
          precio_destino_aux=0;
        }
        //console.log("precio_destino_aux",precio_destino_aux);

        if(precio_destino_aux!=undefined && precio_destino_aux!=precio_destino){
          return true;
        }else{
          return false;
        }
      }

      function requerirMotivoCambioProducto(siNo){
        //console.log("siNo",siNo);
        let motivo_cambio_producto=$("#motivo_cambio_producto")
        if(motivo_cambio_producto.length>0){
          let valor_motivo_cambio_producto=motivo_cambio_producto.val()
          if(siNo && valor_motivo_cambio_producto.length<=0){
            motivo_cambio_producto.attr("required",true).addClass("is-invalid")
          }else{
            motivo_cambio_producto.attr("required",false).removeClass("is-invalid")
          }
        }
      }

      function requerirMotivoCambioDeposito(elemento,siNo){
        let motivo_cambio_deposito=$(elemento)
        if(motivo_cambio_deposito.length>0){
          let valor_motivo_cambio_deposito=motivo_cambio_deposito.val()
          if(siNo && valor_motivo_cambio_deposito.length<=0){
            motivo_cambio_deposito.attr("required",true).addClass("is-invalid")
          }else{
            motivo_cambio_deposito.attr("required",false).removeClass("is-invalid")
          }
        }
      }

      function toggleSolicitarMotivo(){
        //console.log("entramos a toggleSolicitarMotivo");

        let precio_general=$("#precio_general").val();

        let varHaCambiadoProducto=haCambiadoProducto()
        let varHaCambiadoKgPorBulto=haCambiadoKgPorBulto()
        let varHaCambiadoPrecioGeneral=haCambiadoPrecioGeneral()

        //console.log("haCambiadoProducto",varHaCambiadoProducto)
        //console.log("haCambiadoKgPorBulto",varHaCambiadoKgPorBulto)
        //console.log("haCambiadoPrecioGeneral",varHaCambiadoPrecioGeneral);
        if(varHaCambiadoProducto || varHaCambiadoKgPorBulto || varHaCambiadoPrecioGeneral){
          requerirMotivoCambioProducto(true)
          //$("#motivo_cambio_producto").css("border","1px solid red")
        }else{
          requerirMotivoCambioProducto(false)
          //$("#motivo_cambio_producto").css("border","1px solid black")
        }

        let tableDepositosRows = $("#tableDepositos tbody tr");
        //console.log(kg_x_bulto);
        tableDepositosRows.each(function(){
          let fila=$(this);

          let cantidad_bultos = fila.find(".cantidad_bultos").val()
          let precio_destino = fila.find(".precio_destino").val()

          let varHaCambiadoCantidadBultos=haCambiadoCantidadBultos(fila);
          //console.log(varHaCambiadoCantidadBultos);
          let varHaCambiadoPrecioDestino=haCambiadoPrecioDestino(fila);

          requerirMotivoCambioEnDeposito=false;
          if(cantidad_bultos>0 && precio_destino>0){
            
            if(varHaCambiadoCantidadBultos || varHaCambiadoPrecioDestino){
              if(precio_destino==precio_general){
                if(varHaCambiadoCantidadBultos){
                  requerirMotivoCambioEnDeposito=true;
                  //console.log("1.3");
                }else{
                  requerirMotivoCambioEnDeposito=false;
                  //console.log("1.1");
                }
              }else{
                requerirMotivoCambioEnDeposito=true;
                //console.log("1.2");
              }
            }
            
            if(haCambiadoPrecioGeneral() && varHaCambiadoCantidadBultos && !varHaCambiadoPrecioDestino){
              requerirMotivoCambioEnDeposito=true;
              //console.log("2");
            }
            
            if(haCambiadoPrecioGeneral() && !varHaCambiadoCantidadBultos && !varHaCambiadoPrecioDestino){
              requerirMotivoCambioEnDeposito=false;
              //console.log("3");
            }

            if(!haCambiadoPrecioGeneral() && !varHaCambiadoCantidadBultos && varHaCambiadoPrecioDestino){
              requerirMotivoCambioEnDeposito=true;
              //console.log("5");
            }

          }else{
            requerirMotivoCambioEnDeposito=false;
            //console.log("4");
          }

          let elemento=fila.find(".motivo_cambio_deposito")
          requerirMotivoCambioDeposito(elemento,requerirMotivoCambioEnDeposito)
        })
      }

      function calcularTotales(){
        let kg_x_bulto = $("#kg_x_bulto").val();
        //let precio_general = $("#precio_general").val();

        let tableDepositosRows = $("#tableDepositos tbody tr");
        let sumaBultos=0;
        let sumaKilos=0;
        let sumaMonto=0;
        //console.log(kg_x_bulto);
        tableDepositosRows.each(function(){
          let fila=$(this);
          
          let cantidad_bultos=fila.find(".cantidad_bultos").val()
          if(isNaN(cantidad_bultos) || cantidad_bultos==""){
            cantidad_bultos=0;
          }
          sumaBultos+=parseFloat(cantidad_bultos);

          let precio_destino=fila.find(".precio_destino").val()
          if(isNaN(precio_destino) || precio_destino==""){
            precio_destino=0;
          }

          let subtotal_kilos=cantidad_bultos*kg_x_bulto;
          let subtotal_monto=cantidad_bultos*precio_destino;
          //console.log(subtotal_monto);
        
          sumaKilos+=subtotal_kilos;
          sumaMonto+=subtotal_monto;
        
          if(subtotal_kilos==0){
            subtotal_kilos="";
          }

          if(subtotal_monto==0){
            subtotal_monto="";
          }

          let subtotal_kilos_mostrar="";
          if(subtotal_kilos>0){
            subtotal_kilos_mostrar=formatNumber2Decimal(subtotal_kilos)
          }
          fila.find(".subtotal_kilos").val(subtotal_kilos)
          fila.find(".subtotal_kilos_formatted").val(subtotal_kilos_mostrar)
          
          let subtotal_monto_mostrar=""
          if(subtotal_monto>0){
            subtotal_monto_mostrar=formatCurrency(subtotal_monto)
          }
          fila.find(".subtotal_monto").val(subtotal_monto)
          fila.find(".subtotal_monto_formatted").val(subtotal_monto_mostrar)

        })

        $("#total_bultos").html(formatNumber2Decimal(sumaBultos));
        $("#total_kilos").html(formatNumber2Decimal(sumaKilos));
        $("#total_monto").html(formatCurrency(sumaMonto));
      }

      function cargarDatosComponentes(){
        let id_carga=$("#id_carga").val();
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesCargas');
        datosIniciales.append('id_carga', id_carga);
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_cargas.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#addProdLocal').modal('hide');
          },
          success: function(respuesta){
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);

            /*Identifico el select de familias*/
            $selectFamilia = document.getElementById("id_familia");
            //Genero los options del select familias
            respuestaJson.familias.forEach((familia)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(familia.familia);
              $option.appendChild(optionText);
              $option.setAttribute("value", familia.id_familia);
              $selectFamilia.appendChild($option);
            })
            $($selectFamilia).select2()

            /*Identifico el select de productos*/
            //$selectProducto = document.getElementById("id_producto");
            //Genero los options del select productos
            respuestaJson.productos.forEach((producto)=>{
              aProductos.push(producto)
              /*$option = document.createElement("option");

              let id_producto=producto.id_producto
              let presentacion=producto.presentacion
              let unidad_medida=producto.unidad_medida

              let text=producto.producto
              if(presentacion!=undefined && unidad_medida!=undefined){
                text+=" x "+presentacion+" - "+unidad_medida;
              }
              if(producto.ultimo_precio && despachado=="No"){
                text+=" ($"+producto.ultimo_precio+" | "+producto.ultimo_kg_x_bulto+" Kgs.)"
              }*/

              //console.log(id_producto);
              /*if(id_producto==""){
                //console.log("id_producto no es numerico");
                id_producto=0;
              }*/
              //console.log(id_producto);

              /*let optionText = document.createTextNode(text);
              $option.appendChild(optionText);
              $option.setAttribute("value", id_producto);
              $option.setAttribute("data-idFamilia", producto.id_familia);
              $selectProducto.appendChild($option);*/
            })
            //$($selectProducto).select2()

            /*Identifico el select de proveedores*/
            /*$selectProveedor = document.getElementById("id_proveedor");
            //Genero los options del select proveedores
            respuestaJson.proveedores.forEach((proveedor)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(proveedor.proveedor);
              $option.appendChild(optionText);
              $option.setAttribute("value", proveedor.id_proveedor);
              $selectProveedor.appendChild($option);
            })
            $($selectProveedor).select2()*/

            // Identifico la tabla de destinos
            var tbody = document.querySelector('#tableDepositos tbody');
            tbody.innerHTML="";
            respuestaJson.destinos.forEach((destino) => {
              if (respuestaJson.destinos_preseleccionados.length==0 || respuestaJson.destinos_preseleccionados.includes(destino.id_destino)){
                // Plantilla para cada fila
                let tipo_aumento_extra=destino.tipo_aumento_extra
                let valor_extra=destino.valor_extra
                let lbl_aumento_extra=""
                if(tipo_aumento_extra==null){
                  tipo_aumento_extra="";
                  //lbl_aumento_extra=" (+"+tipo_aumento_extra+"%)"
                }
                //console.log(destino)
                // title="Deje en blanco si no desea cargar este producto al destino"
                let contenidoFila = `
                  <td class="align-middle">
                    <input type='hidden' class="tipo_aumento_extra" value='${tipo_aumento_extra}'>
                    <input type='hidden' class="valor_extra" value='${valor_extra}'>
                    <input type='hidden' class="id_producto_destino" value=''>
                    <input type='hidden' class="id_deposito" value='${destino.id_destino}'>${destino.destino+lbl_aumento_extra}
                  </td>
                  <td class="align-middle">
                    <div class="input-group">
                      <input type="number" class="form-control text-right cantidad_bultos" value="">
                    </div>
                  </td>
                  <td class="align-middle">
                    <input type="hidden" name="subtotal_kilos" class="subtotal_kilos">
                    <input type="text" readonly tabindex="-1" class="form-control text-right subtotal_kilos_formatted">
                  </td>
                  <td class="align-middle">
                    <div class="input-group">
                      <input type="number" class="form-control text-right precio_destino" value="">
                    </div>
                  </td>
                  <td class="align-middle">
                    <input type="hidden" name="subtotal_monto" class="subtotal_monto">
                    <input type="text" disabled tabindex="-1" class="form-control text-right subtotal_monto_formatted">
                  </td>`;
                  if(despachado=="Si"){
                    contenidoFila+=`
                    <td class="align-middle">
                      <input type="hidden" name="cantidad_bultos_aux" class="cantidad_bultos_aux">
                      <input type="hidden" name="precio_destino_aux" class="precio_destino_aux">
                      <input type="text" class="form-control motivo_cambio_deposito">
                    </td>`
                  }
                // Crear una nueva fila
                var newRow = document.createElement('tr');
                // Insertar el contenido HTML en la nueva fila
                newRow.innerHTML = contenidoFila;
                // Agregar la fila al tbody
                tbody.appendChild(newRow);
              }
            });

          }
        });
      }

      function cargarDatosComponentesNuevoProducto(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesProducto');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_producto.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#addProdLocal').modal('hide');
          },
          success: function(respuesta){
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            /*Identifico el select de perfiles*/
            $selectFamilia = document.getElementById("id_familia_nuevo_producto");
            /*Genero los options del select usuarios*/
            respuestaJson.familias.forEach((familia)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(familia.familia);
              $option.appendChild(optionText);
              $option.setAttribute("value", familia.id_familia);
              $selectFamilia.appendChild($option);
            })

            $($selectFamilia).select2({dropdownParent: $('#modalNuevoProducto')})

            /*Identifico el select de presentacion*/
            $selectPresentacion = document.getElementById("id_presentacion");
            /*Genero los options del select Presentacion*/
            respuestaJson.presentacion.forEach((presentacion)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(presentacion.presentacion);
              $option.appendChild(optionText);
              $option.setAttribute("value", presentacion.id_presentacion);
              $selectPresentacion.appendChild($option);
            })

            //$($selectPresentacion).select2({dropdownParent: $('#modalNuevoProducto')})
            $($selectPresentacion).select2({
              language: {
                noResults: function() {
                  return select2NoResultText;
                }
              },
              dropdownParent: $('#modalNuevoProducto')
            })

            /*Identifico el select de unidades de medidas*/
            $selectUnidadMedida = document.getElementById("id_unidad_medida");
            /*Genero los options del select unidades_medidas*/
            respuestaJson.unidades_medidas.forEach((unidad_medida)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(unidad_medida.unidad_medida);
              $option.appendChild(optionText);
              $option.setAttribute("value", unidad_medida.id_unidad_medida);
              $selectUnidadMedida.appendChild($option);
            })

            $($selectUnidadMedida).select2({dropdownParent: $('#modalNuevoProducto')})

          }
        });
      }

    </script>
  </body>
</html>