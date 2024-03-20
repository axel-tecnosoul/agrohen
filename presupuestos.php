<?php
session_start();
include_once('./../conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
    header("location:./models/redireccionar.php");
}?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="endless admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, endless admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <!--<link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">-->
    <title>MYLA - Presupuestos</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/flag-icon.css">
     <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="assets/css/datatables.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/feather-icon.css">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="assets/css/sweetalert2.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/light-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fullCalendar/main.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select2.css">
    <style type="text/css">
      input[type=number]::-webkit-inner-spin-button,
      input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
        margin: 0;
        }
      input[type=number] { -moz-appearance:textfield; }
      .fa-angle-down {
        transition: all 0.3s ease;
      }
      a.collapsed .card-header .fa-angle-down {
        transform: rotate(180deg);
      }
      .modal .card-header{
        background-color: #2f3c4e;
      }
      .modal a .card-header h6{
        color: #f6f7fb;
      }

      .modal-dialog{
        overflow-y: initial !important;
        margin-top: 1vh;
      }
      .modal-body{
        max-height: 75vh;
        overflow-y: auto;
      }
      .modal-lg, .modal-xl {
        max-width: 1000px;
      }
      #tablaTotales{
        width: 80vw;
      }
      .lblTotales{
        width: 20vw;
      }
      .montoTotales{
        width: 20vw;
      }
      .porcentajeTotales{
        width: 10vw;
      }
      .dtrg-group{
        background-color: lightgrey !important;
      }
    </style>
  </head>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="loader bg-white">
        <div class="whirly-loader"> </div>
      </div>
    </div>
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
          <div class="page-header">
            <div class="row">
              <div class="col">
                <div class="page-header-left">
                  <h3><?=$titulo?></h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active"><?=$titulo?></li>
                  </ol>
                  <span class="d-none" id="id_empresa"><?=$_SESSION['rowUsers']['id_empresa']?></span>
                  <span class="d-none" id="id_estado_presupuesto"><?=implode(",",$id_estado)?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <!-- Ajax Generated content for a column start-->
          <!-- <div class="row">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="d-inline">Calendario de tareas</h5>
                </div>
                <div class="card-body">
                  <div id="cal-agenda-view"></div>
                </div>
              </div>
            </div>
          </div> -->

          <div class="row">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header">
                  <h5>Grilla de presupuestos</h5>
                  <!-- <button id="btnNuevoPresupuesto" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nuevo presupuesto</button> -->
                </div><?php
                //var_dump($_SESSION["rowUsers"]["id_empresa"]);?>
                <div class="card-body">
                  <span id="minimoAprobacion" class="d-none"><?=$_SESSION['rowUsers']['monto_aprobacion_minimo'];?></span>
                  <span id="maximoAprobacion" class="d-none"><?=$_SESSION['rowUsers']['monto_aprobacion_maximo'];?></span>
                  <div class="table-responsive" id="contTablaListas" >
                    <table class="table table-hover" id="tablaPresupuestos">
                      <thead class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Cliente</th>
                          <th>Direccion</th>
                          <th>Contacto</th>
                          <th>Total</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tfoot class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Cliente</th>
                          <th>Direccion</th>
                          <th>Contacto</th>
                          <th>Total</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </tfoot>
                      <tbody></tbody>
                    </table>
                  </div>
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

    <!--Modal para nueva Orden de trabajo-->
    <div class="modal fade" id="modalOrdenTrabajo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_presupuesto" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formOrdenTrabajo" >
            <div class="modal-body">
              <!--Accordion wrapper-->
              <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    <div class="card-header" role="tab" id="heading1">
                      <h6 class="mb-0">Datos del pedido <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapse1" class="collapse show" role="tabpanel" aria-labelledby="heading1" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label font-weight-bold">Cliente: </label><span id="lblClienteOT"></span>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label font-weight-bold">Ubicacion: </label><span id="lblUbicacionOT"></span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label font-weight-bold">Contacto: </label><span id="lblContactoOT"></span>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label font-weight-bold">Prioridad: </label><span id="lblPrioridadOT"></span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="" class="col-form-label font-weight-bold">Descripcion: </label><span id="lblDescripcionOT"></span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="" class="col-form-label font-weight-bold">Comentarios: </label><span id="lblComentariosOT"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
                    <div class="card-header" role="tab" id="heading2">
                      <h6 class="mb-0">Datos generales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapse2" class="collapse show" role="tabpanel" aria-labelledby="heading2" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Centro de costos:</label>
                            <select class="form-control" id="id_centro_costos_ot" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Fecha:</label>
                            <input type="date" class="form-control" id="fecha" required value="<?=date("Y-m-d",strtotime("+1 days"))?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Desde:</label>
                            <input type="time" class="form-control" id="hora_desde" required value="<?=$desde=date("H:i")?>">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Hasta:</label>
                            <input type="time" class="form-control" id="hora_hasta" required value="<?=date("H:i",strtotime($desde."+8 hours"))?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                    <div class="card-header" role="tab" id="heading3">
                      <h6 class="mb-0">Materiales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapse3" class="collapse" role="tabpanel" aria-labelledby="heading3" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="table-responsive tablaMaterialesOT">
                        <table class="table table-hover" id="tablaMaterialesOT">
                        <thead class="text-center">
                            <tr>
                              <th>Imagen</th>
                              <th>Item</th>
                              <th>Proveedor</th>
                              <th>Almacen</th>
                              <th>Disponible</th>
                              <!-- <th>Precio unit.</th> -->
                              <!-- <th>UM</th> -->
                              <th>Cantidad necesaria</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tfoot class="text-center">
                            <tr>
                              <th>Imagen</th>
                              <th>Item</th>
                              <th>Proveedor</th>
                              <th>Almacen</th>
                              <th>Disponible</th>
                              <!-- <th>Precio unit.</th> -->
                              <!-- <th>UM</th> -->
                              <th>Cantidad necesaria</th>
                              <th>Acciones</th>
                            </tr>
                          </tfoot>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo3" aria-expanded="false" aria-controls="collapseTwo3">
                    <div class="card-header" role="tab" id="headingTwo3">
                      <h6 class="mb-0">Técnicos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseTwo3" class="collapse" role="tabpanel" aria-labelledby="headingTwo3" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="table-responsive tablaTecnicos">
                        <table class="table table-hover" id="tablaTecnicos">
                          <thead class="text-center">
                            <tr>
                              <th>Seleccione</th>
                              <th>#ID</th>
                              <th>Tecnico</th>
                              <th>Vehiculo</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th>Seleccione</th>
                              <th>#ID</th>
                              <th>Tecnico</th>
                              <th>Vehiculo</th>
                            </tr>
                          </tfoot>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
              </div>
              <!-- Accordion wrapper -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-dark">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL para nueva Orden de trabajo-->

    <?php
    include_once("assets/presupuestos/modal_crear_editar.php");
    include_once("assets/presupuestos/modal_ver_detalle.php")?>

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
    <!-- <script src="assets/js/datatable/datatables/jquery.dataTables.min.js"></script> -->
    <script src="assets/js/chart/chartjs/chart.min.js"></script>
    <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>

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
    <!-- <script src="assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js"></script> -->
    <script src="assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.scroller.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.rowGroup.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/custom.js"></script>

    <script src="assets/js/select2/select2.full.min.js"></script>
    <script src="assets/js/select2/select2-custom.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>

    <script src="assets/js/fullCalendar/main.min.js"></script>
    <script src="assets/js/fullCalendar/locales/es.js"></script>
    <script src="assets/presupuestos/modal_crear_editar.js"></script>
    <script src="assets/presupuestos/modal_ver_detalle.js"></script>
    <script src="assets/tareas/form_crear_editar.js"></script>
    
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">
      var accion = "";
      /*var tablaItems=$('#tablaItems');
      var tablaCargos=$('#tablaCargos');
      var tablaTecnicos=$('#tablaTecnicos');
      var tablaMaterialesOT=$("#tablaMaterialesOT");*/

      $(document).ready(function(){
        //cargarDatosComponentes();
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
        //debugger;
        let id_estado_presupuesto=$("#id_estado_presupuesto").html();
        console.log(id_estado_presupuesto);
        //tablaPresupuestos=$('#tablaPresupuestos').DataTable({
        tablaPresupuestos.DataTable({
          "ajax": {
            "url" : "./models/administrar_presupuestos.php?accion=traerPresupuestos&id_estado_pedido="+id_estado_presupuesto,
            "dataSrc": "",
          },
          "stateSave": true,
          "columns":[
            {"data": "id_pedido"},
            {"data": "pedido.cliente"},
            {"data": "pedido.direccion"},
            {"data": "pedido.contacto_cliente"},
            {"data": "total_precio_mostrar"},
            //{"data": "estado"},
            {render: function(data, type, row, meta) {
              return ()=>{
                if(type=="display"){
                  let id_estado=row.id_estado_pedido
                  const estados = {
                    1: "Creado",
                    2: "Borrador",
                    3: "Enviado al cliente",
                    4: "Aceptada",
                    5: "Cancelado",
                    6: "Finalizada"
                  }
                  $options="";
                  for(key in estados){
                    if(id_estado == key){
                      $options+=`<option selected value="${id_estado}">${estados[key]}</option>`
                    }else{
                      switch(id_estado){
                        case "1":
                          if(key == 5){
                            $options+=`<option value="${key}">${estados[key]}</option>`
                          }
                        break
                        case "2":
                          if(key == 3 || key == 5){
                            $options+=`<option value="${key}">${estados[key]}</option>`
                          }
                        break;
                        case "4":
                          if(key == 5){
                            $options+=`<option value="${key}">${estados[key]}</option>`
                          }
                        break;
                      }
                    }
                  }
                  $span = `<span class="d-none">${row.estado}</span>`;
                  $selectInit = `<select class="estado_pedido">`;//
                  $selectEnd = "</select>";
                  $selectComplete = $selectInit + $options+$selectEnd
                  if(row.id_estado_pedido==2){
                    return $span + $selectComplete;
                  }else{
                    return `<span>${row.estado}</span>`
                  }
                }else{
                  return row.estado
                }
              };
            }},
            {render: function(data, type, row, meta) {
              return ()=>{
                //si la orden esta finalizada no se puede editar
                
                let btnEditar="<button class='btn btn-success btnEditar' title='Editar'><i class='fa fa-edit'></i></button>";
                let btnVer="<button class='btn btn-warning btnVer' title='Ver detalle'><i class='fa fa-eye'></i></button>";
                let btnBorrar="<button class='btn btn-danger btnBorrar' title='Borrar'><i class='fa fa-trash-o'></i></button>";
                let btnOrdenTrabajo="<button class='btn btn-primary btnOrdenTrabajo' title='Crear Orden de Trabajo'><i class='fa fa-briefcase'></i></button>";
                btnOrdenTrabajo="";
                if(row.id_estado_pedido==2){
                  
                }
                if(row.id_estado_pedido>2){
                  btnBorrar=""
                  if(row.total_precio>0){
                    btnEditar=""
                  }
                }
                let buttons=btnOrdenTrabajo+btnEditar+btnVer+btnBorrar;
                return `
                <div class='text-center'>
                  <div class='btn-group'>${buttons}</div>
                </div>`;
              };
            }},
          ],
          /*order: [[2, 'asc']],
          rowGroup: {
              endRender: function ( rows, group ) {
                var sum = rows.data().pluck("total_precio").reduce( function (a, b) {
                  //return a + b.replace(/[^\d]/g, '')*1;
                  //return a + b.replace(/[$.]/g, '')*1;
                  return parseFloat(a) + parseFloat(b);
                }, 0);
                console.log(sum);
                return 'Total: '+$.fn.dataTable.render.number('.',',',2,'$').display( sum );
              },
              dataSrc: "pedido.cliente"
          },*/
          "language":  idiomaEsp,
          dom: '<"mr-2 d-inline"l>Bfrtip',
          buttons: [
            {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel-o"></i>',
              titleAttr: 'Excel',
              title:     "Presupuestos",
              className: 'btn-success',
              exportOptions: {
                columns: ':not(:last-child)',
                /*format: {
                  body: function ( data, row, column, node ) {
                    // Strip $ from salary column to make it numeric
                    return column === 7 ? data.replace( /[$.]/g, '' ).replace( /[,]/g, '.' ) : data;
                  }
                }*/
              }
            },
            {
              extend:    'pdfHtml5',
              text:      '<i class="fa fa-file-pdf-o"></i>',
              title:     "Presupuestos",
              titleAttr: 'PDF',
              download: 'open',
              className: 'btn-danger',
              exportOptions: {
                columns: ':not(:last-child)',
              }
            }
          ],
          initComplete: function(){
            $('[title]').tooltip();
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b!=1 && b!=7){
                var column=this;
                var name=$(column.footer()).text();
                var select=$("<select id='filtro"+name+"' class='form-control form-control-sm filtrosTrato'><option value=''>Todos</option></select>")
                  .appendTo($(column.footer()).empty())
                  .on("change",function(){
                    var val=$.fn.dataTable.util.escapeRegex(
                      $(this).val()
                    );
                    column.search(val ? '^'+val+'$':'',true,false).draw();
                  });
                //column.data().unique().sort().each(function(d,j){
                column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ){
                  let val
                  //console.log(name);
                  if(name=="Estado"){
                    let span=$(d);
                    val=span[0].innerHTML
                  }else{
                    val=$("<div/>").html(d).text();
                  }
                  if(column.search()==='^'+val+'$'){
                    select.append("<option value='"+val+"' selected='selected'>"+val+"</option>");
                  }else{
                    select.append("<option value='"+val+"'>"+val+"</option>");
                  }
                })
              }
              b++;
            })
          }
        });

        /*tablaTecnicos.DataTable({
          "ajax": {
            "url" : "./models/administrar_tecnicos.php?accion=traerTecnicos",
            "dataSrc": "",
          },
          "columns":[
            {"defaultContent" : "<input class='form-control select' type='checkbox'>"},
            {"data": "id_tecnico"},
            {"data": "nombre_completo"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  //genero los select vacíos para luego obtener los vehiculos. Guardo en el atributo data el id_vehiculo de cada tecnico 
                  let id="vehiculo"+full.id_tecnico;
                  return `<div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <select class="select_vehiculos" id="`+id+`" data-id-vehiculo="`+full.id_vehiculo+`">
                        <option value="0">- Sin vehiculo -</option>
                      </select>
                    </div>
                    <button class='btn btn-sm btn-secondary sinVehiculo' type="button" data-id-select-vehiculo='#`+id+`' title='Sin vehiculo'><i class="fa fa-ban" aria-hidden="true"></i></button>
                    </div>`;
                };
              }
            }
          ],
          "language":  idiomaEsp,
          initComplete: function(){
            this.find("tbody tr td").on( 'click', function () {
              var t=$(this).parent();
              if(t.hasClass('selected')){
                deselectRow(t);
              }else{
                selectRow(t);
              }
            });
            //cuando se complta el datatable busco los vehiculos y los cargamos al select de cada tecnico
            $.ajax({
              url: "./models/administrar_vehiculos.php?accion=traerVehiculos",
              method: "get",
              cache: false,
              contentType: false,
              processData: false,
              success: function(respuesta){

                //Identifico los select de vehiculos de la tabla de tecnicos
                $selectVehiculos= document.getElementsByClassName("select_vehiculos");

                for (let select_vehiculo_tecnico of $selectVehiculos) {
                  respuestaJson = JSON.parse(respuesta);
                  //console.log(respuestaJson);
                  let idVehiculoAsignado=select_vehiculo_tecnico.dataset.idVehiculo;

                  //Genero los options del select de clientes
                  respuestaJson.forEach((vehiculo)=>{
                    $option = document.createElement("option");
                    let optionText = document.createTextNode(vehiculo.vehiculo);
                    $option.appendChild(optionText);
                    $option.setAttribute("value", vehiculo.id_vehiculo);
                    if(idVehiculoAsignado==vehiculo.id_vehiculo){
                      $option.setAttribute("selected","selected");
                    }
                    select_vehiculo_tecnico.appendChild($option);
                  });
                }
              }
            });
          }
        });*/

        /*$('#modalOrdenTrabajo').on('hidden.bs.modal', function (e) {
          document.getElementById('dropMasArchivos').innerHTML="";
          document.getElementById('masAdjuntos').classList.toggle("d-none");
        });*/
      });

      $(document).on("change", ".estado_pedido", function(){
        fila = $(this);
        nuevoEstado = $(this).val();
        id_pedido = parseInt($(this).closest('tr').find('td:eq(0)').text());
        totalFormateado = $(this).closest('tr').find('td:eq(4)').text();
        
        total = totalFormateado.replace("$", "");

        arrayValor = total.split(",");
        total = parseFloat(arrayValor[0].replace(".","")+"."+arrayValor[1])
        console.log(total);
        
        accion = "cambiarEstadoPedido";

        let minimoAprobacion = parseFloat(document.getElementById("minimoAprobacion").innerText);
        let maximoAprobacion = parseFloat(document.getElementById("maximoAprobacion").innerText);

        console.log(minimoAprobacion);
        console.log(maximoAprobacion);

        if(maximoAprobacion < total){
          swal({
            icon: 'error',
            title: 'Orden excede el límite máximo de aprobación'
          });
          tablaPresupuestos.DataTable().ajax.reload(null, false);
        }else if(minimoAprobacion > total){
          swal({
            icon: 'error',
            title: 'No alcanza el monto mínimo de aprobación'
          });
          tablaPresupuestos.DataTable().ajax.reload(null, false);
        }else{
          $.ajax({
            url: "models/administrar_presupuestos.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion, id_pedido: id_pedido, id_estado: nuevoEstado},    
            success: function(data) {
              if(data==""){
                //$('#modalCRUD').modal('hide');
                tablaPresupuestos.DataTable().ajax.reload(null, false);
                swal({
                  icon: 'success',
                  title: 'Estado cambiado exitosamente'
                });
                //cargarGrafico();
              }
            }
          })
        }
      })

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesPresupuestos');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_presupuestos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
              //$('#addProdLocal').modal('hide');
          },
          success: function(respuesta){
            //console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);

            //Identifico el select de vehiculos
            $selectCentroCostos= document.getElementById("id_centro_costos_ot");
            //Genero los options del select de vehiculos
            respuestaJson.centroCostos.forEach((centro_costos)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(centro_costos.nombre);
                $option.appendChild(optionText);
                $option.setAttribute("value", centro_costos.id_centro_costos);
                $selectCentroCostos.appendChild($option);
            });

            //Identifico el select de clientes
            /*$selectClientes= document.getElementById("cliente");
            //Genero los options del select de clientes
            respuestaJson.clientes.forEach((cliente)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(cliente.razon_social);
                $option.appendChild(optionText);
                $option.setAttribute("value", cliente.id_cliente);
                $selectClientes.appendChild($option);
            });*/

          }
        });
      }

      $(document).on("click", "#btnNuevoAdjunto", function(){
        $("#inputFile").removeClass("d-none");
      });

      $(document).on('click', '#btnAddAdjuntos', function(e){
        e.preventDefault();
        $rowMasAdjuntos = document.getElementById("masAdjuntos");
        $rowMasAdjuntos.classList.toggle("d-none");
        $.ajax({
            url: "dropAdjuntosMantenimientoPreventivo.html",
            type: "POST",
            datatype:"json",    
            data:  {},    
            success: function(response) {
              $('#dropMasArchivos').html(response);
            }
          });
      });

      /*$(document).on("change", "#cliente", function(){
        getUbicacionesCliente();
      });

      function getUbicacionesCliente(id_cliente,id_ubicacion_cliente){
        let datosIniciales = new FormData();
        if(id_cliente==undefined){
          id_cliente=document.getElementById("cliente").value;
        }
        datosIniciales.append('id_cliente', id_cliente);
        datosIniciales.append('accion', 'traerDirecciones');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_clientes.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(respuesta){
            //console.log(respuesta);
            //Convierto en json la respuesta del servidor
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);
            let listaUbicaciones=respuestaJson;

            //Identifico el select de direcciones
            $selectUbicacionesCliente= document.getElementById("ubicacion");
            $selectUbicacionesCliente.innerHTML = "";
            //Genero los options del select de direcciones
            $option = document.createElement("option");
            let texto="Sin resultados";
            if(listaUbicaciones.length>0){
              texto="Seleccione una ubicacion";
            }
            let optionText = document.createTextNode(texto);
            $option.appendChild(optionText);
            $selectUbicacionesCliente.appendChild($option);
            
            listaUbicaciones.forEach((ubicaciones_cliente)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(ubicaciones_cliente.direccion);
                $option.appendChild(optionText);
                $option.setAttribute("value", ubicaciones_cliente.id_direccion);
                if(id_ubicacion_cliente==ubicaciones_cliente.id_direccion){
                  $option.setAttribute("selected", true);
                }
                $selectUbicacionesCliente.appendChild($option);
            });

          }
        });
      }

      $(document).on("change", "#ubicacion", function(){
        getContactosUbicacion();
      });

      function getContactosUbicacion(id_ubicacion,id_contacto_cliente){
        let datosIniciales = new FormData();
        if(id_ubicacion==undefined){
          id_ubicacion=document.getElementById("ubicacion").value;
        }
        datosIniciales.append('id_ubicacion', id_ubicacion);
        datosIniciales.append('accion', 'traerContactos');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_clientes.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(respuesta){
            //console.log(respuesta);
            //Convierto en json la respuesta del servidor
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);
            let listaContactos=respuestaJson.contactos;

            //Identifico el select de direcciones
            $selectContactosCliente= document.getElementById("id_contacto_cliente");
            $selectContactosCliente.innerHTML = "";
            //Genero los options del select de direcciones
            $option = document.createElement("option");
            let texto="Sin resultados";
            if(listaContactos.length>0){
              texto="Seleccione un contacto";
            }
            let optionText = document.createTextNode(texto);
            $option.appendChild(optionText);
            $selectContactosCliente.appendChild($option);
            
            listaContactos.forEach((contacto_cliente)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(contacto_cliente.nombre_completo);
                $option.appendChild(optionText);
                $option.setAttribute("value", contacto_cliente.id_contacto);
                if(id_contacto_cliente==contacto_cliente.id_contacto){
                  $option.setAttribute("selected", true);
                }
                $selectContactosCliente.appendChild($option);
            });

          }
        });
      }*/

      $(document).on("click", "#btnActualizarPresupuesto", function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        $('#modalPresupuesto').modal('hide');
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
        /*let datosEnviar = new FormData();
        datosEnviar.append("id_elemento_cliente", $.trim($('#id_elemento_cliente').val()));
        //datosEnviar.append("fecha_alta", $.trim($('#fecha_alta').val()));
        datosEnviar.append("asunto", $.trim($('#asunto').val()));
        datosEnviar.append("detalle", $.trim($('#detalle').val()));
        datosEnviar.append("id_contacto_cliente", $.trim($('#id_contacto_cliente').val()));
        datosEnviar.append("id_almacen", $.trim($('#id_almacen').val()));
        //datosEnviar.append("costo_movilidad_estimado", $.trim($('#costo_movilidad_estimado').val()));
        datosEnviar.append("fecha", $.trim($('#fecha').val()));
        datosEnviar.append("hora_desde", $.trim($('#hora_desde').val()));
        datosEnviar.append("hora_hasta", $.trim($('#hora_hasta').val()));

        datosEnviar.append("prioridad", $.trim($('#prioridad').val()));

        datosEnviar.append("frecuencia_cantidad", $.trim($('#frecuencia_cantidad').val()));
        datosEnviar.append("frecuencia_repeticion", $.trim($('#frecuencia_repeticion').val()));
        datosEnviar.append("frecuencia_stop", $.trim($('#frecuencia_stop').val()));

        //let items= new Array();
        let items= {};

        $(".items").each(function(){
          let cantidad_item=this.value;
          if(cantidad_item!="" && cantidad_item!=0){
            items[this.id]={
              "cantidad":cantidad_item,
              "proveedor":$("#proveedor-"+this.id).val()
            };
          }
        });
        datosEnviar.append("itemsJSON", JSON.stringify(items));
        
        datosEnviar.append("id_presupuesto", $.trim($('#id_presupuesto').html()));
        datosEnviar.append("accion", accion);
        //console.log(accion);

        let cantArchivos = 0;
        if(typeof arrayFiles !== 'undefined'){
          for(let i = 0; i < arrayFiles.length; i++) {
            datosEnviar.append('file'+i, arrayFiles[i]);
            cantArchivos++;
          };
        }else{
          let arrayFiles = "";
        }
        datosEnviar.append('cantAdjuntos', cantArchivos);

        $.ajax({
          data: datosEnviar,
          url: "models/administrar_presupuestos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            if(data==""){
              tablaPresupuestos.ajax.reload(null, false);
              calendar.refetchEvents();

              $('#modalOrdenTrabajo').modal('hide');
          
              swal({
                icon: 'success',
                title: 'Accion realizada correctamente'
              });
            }else{
              swal({
                title: 'Ha ocurrido un error!'
              });
            }
          }
        });*/
      });

      $('#formOrdenTrabajo').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        $('#modalOrdenTrabajo').modal('hide');
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
        /*let datosEnviar = new FormData();
        datosEnviar.append("id_elemento_cliente", $.trim($('#id_elemento_cliente').val()));
        //datosEnviar.append("fecha_alta", $.trim($('#fecha_alta').val()));
        datosEnviar.append("asunto", $.trim($('#asunto').val()));
        datosEnviar.append("detalle", $.trim($('#detalle').val()));
        datosEnviar.append("id_contacto_cliente", $.trim($('#id_contacto_cliente').val()));
        datosEnviar.append("id_almacen", $.trim($('#id_almacen').val()));
        //datosEnviar.append("costo_movilidad_estimado", $.trim($('#costo_movilidad_estimado').val()));
        datosEnviar.append("fecha", $.trim($('#fecha').val()));
        datosEnviar.append("hora_desde", $.trim($('#hora_desde').val()));
        datosEnviar.append("hora_hasta", $.trim($('#hora_hasta').val()));

        datosEnviar.append("prioridad", $.trim($('#prioridad').val()));

        datosEnviar.append("frecuencia_cantidad", $.trim($('#frecuencia_cantidad').val()));
        datosEnviar.append("frecuencia_repeticion", $.trim($('#frecuencia_repeticion').val()));
        datosEnviar.append("frecuencia_stop", $.trim($('#frecuencia_stop').val()));

        //let items= new Array();
        let items= {};

        $(".items").each(function(){
          let cantidad_item=this.value;
          if(cantidad_item!="" && cantidad_item!=0){
            items[this.id]={
              "cantidad":cantidad_item,
              "proveedor":$("#proveedor-"+this.id).val()
            };
          }
        });
        datosEnviar.append("itemsJSON", JSON.stringify(items));
        
        datosEnviar.append("id_presupuesto", $.trim($('#id_presupuesto').html()));
        datosEnviar.append("accion", accion);
        //console.log(accion);

        let cantArchivos = 0;
        if(typeof arrayFiles !== 'undefined'){
          for(let i = 0; i < arrayFiles.length; i++) {
            datosEnviar.append('file'+i, arrayFiles[i]);
            cantArchivos++;
          };
        }else{
          let arrayFiles = "";
        }
        datosEnviar.append('cantAdjuntos', cantArchivos);

        $.ajax({
          data: datosEnviar,
          url: "models/administrar_presupuestos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            if(data==""){
              tablaPresupuestos.ajax.reload(null, false);
              calendar.refetchEvents();

              $('#modalOrdenTrabajo').modal('hide'); 
          
              swal({
                icon: 'success',
                title: 'Accion realizada correctamente'
              });
            }else{
              swal({
                title: 'Ha ocurrido un error!'
              });
            }
          }
        });*/
      });

      $(document).on("click", ".btnOrdenTrabajo", function(){
        fila = $(this).closest("tr");
        let id_presupuesto = fila.find('td:eq(0)').text();

        accion = "addOrdenTrabajo"
        $("#formOrdenTrabajo").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Nueva Orden de Trabajo para el presupuesto ID "+id_presupuesto);
        $('#modalOrdenTrabajo').modal('show');
        
        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerPresupuestos');
        datosUpdate.append('id_presupuesto', id_presupuesto);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_presupuestos.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(datosProcesados){
            //console.log(datosProcesados);
            let datosInput = JSON.parse(datosProcesados);
            console.log(datosInput);
            //let dmp=datosInput.datos_mantenimiento_preventivo;
            //console.log(dmp);

            $("#lblClienteOT").html(datosInput.cliente);
            $("#lblUbicacionOT").html(datosInput.direccion);
            $("#lblContactoOT").html(datosInput.contacto_cliente);
            $("#lblPrioridadOT").html(datosInput.prioridad);
            $("#lblDescripcionOT").html(datosInput.descripcion);
            /*$("#lblFecha").html(datosInput.fecha_mostrar);
            $("#lblNumero").html(datosInput.numero);
            $("#lblCaducidad").html(datosInput.caducidad_mostrar);
            $("#lblTipo").html(datosInput.tipo);*/
            $("#lblComentariosOT").html(datosInput.comentarios);

            getStockItems(datosInput.materiales);
            
            $('#id_presupuesto').html(id_presupuesto);
            
          }
        });

      });

      function getStockItems(materiales){
        let data=[]
        let btnReservar="<button class='btn btn-success btnReservar'>Reservar</button>"
        let btnPedir="<button class='btn btn-success btnPedir'>Pedir</button>"
        let hayStockSuficienteParaLaOT=1;
        materiales.forEach(material => {
          material.stock.forEach(stock => {
            let accion="reservar"
            if(stock.cantidad_disponible<material.cantidad){//si no hay stock suficiente
              accion="pedir"
              hayStockSuficienteParaLaOT=0
            }
            let fila={
              "id_item":material.item.id_item,
              "item":material.item.item,
              "imagen":material.item.imagen,
              "id_proveedor":stock.id_proveedor,
              "proveedor":stock.proveedor,
              "id_almacen":stock.id_almacen,
              "almacen":stock.almacen,
              "disponible":stock.cantidad_disponible,
              //"precio":stock.precio,
              "necesario":material.cantidad,
              "accion":accion,
              "item-proveedor":material.item.item+"-"+stock.proveedor
            }
            data.push(fila);
          })
        });
        //console.log(tablaItems);
        tablaMaterialesOT.dataTable().fnDestroy();
        tablaMaterialesOT.DataTable({
          "data":data,
          "columns":[
            {"data": "item-proveedor","class":"d-none"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  let $img = " ";
                  if (full.imagen !=""){
                    $img=`<img src="./views/img_items/${full.imagen}" class="img-thumbnail">`;
                  }
                  return $img;
                };
              }
            },
            {"data": "item","class":"item"},
            {"data": "proveedor","class":"proveedor"},
            {"data": "almacen"},
            {"data": "disponible"},
            {"data": "necesario"},
            //{"data": "accion"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  if(hayStockSuficienteParaLaOT==1){
                    accion=btnReservar;
                  }else{
                    accion="";
                    if(full.accion=="pedir"){
                      accion=btnPedir;
                    }
                  }
                  return accion
                };
              }
            }
          ],
          "language":  idiomaEsp,
          initComplete: function(){
            //detectamos cuando presiona Enter para agregar el producto
            let searchBox=$(tablaItems.DataTable().table().container()).find("div.dataTables_filter input");
            $(document).on("keyup", searchBox, function(e){
              if(e.keyCode == 13){
                document.querySelector(".btnAgregarNuevoProducto").click();
              }
            });
            
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b<6 && b!=1){
                var column=this;
                var name=$(column.header()).text();
                var select=$("<select id='filtro"+name.replace(/ /g, "")+"' class='form-control form-control-sm filtrosTrato'><option value=''>Todos</option></select>")
                  .appendTo($(column.footer()).empty())
                  .on("change",function(){
                    var val=$.fn.dataTable.util.escapeRegex(
                      $(this).val()
                    );
                    column.search(val ? '^'+val+'$':'',true,false).draw();
                  });
                column.data().unique().sort().each(function(d,j){
                  var val=$("<div/>").html(d).text();
                  if(column.search()==='^'+val+'$'){
                    select.append("<option value='"+val+"' selected='selected'>"+val+"</option>");
                  }else{
                    select.append("<option value='"+val+"'>"+val+"</option>");
                  }
                })
              }
              b++;
            })
          }
        });
        //MergeGridCells();
      }

      /*function MergeGridCells() {
        var dimension_cells = new Array();
        var dimension_col = null;
        var columnCount = $("#tablaMaterialesOT tr:first th").length;
        for (dimension_col = 0; dimension_col < columnCount; dimension_col++) {
            // first_instance holds the first instance of identical td
            var first_instance = null;
            var rowspan = 1;
            // iterate through rows
            $("#tablaMaterialesOT").find('tr').each(function () {

                // find the td of the correct column (determined by the dimension_col set above)
                var dimension_td = $(this).find('td:nth-child(' + dimension_col + ')');

                if (first_instance == null) {
                    // must be the first row
                    first_instance = dimension_td;
                } else if (dimension_td.text() == first_instance.text()) {
                    // the current td is identical to the previous
                    // remove the current td
                    dimension_td.remove();
                    ++rowspan;
                    // increment the rowspan attribute of the first instance
                    first_instance.attr('rowspan', rowspan);
                } else {
                    // this cell is different from the last
                    first_instance = dimension_td;
                    rowspan = 1;
                }
            });
        }
    }*/

      $(document).on("click", ".btnEditar", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();

        editarPresupuesto(id_pedido)

      });

      function selectRow(t){
        t.addClass('selected');
        var inputCantidadEntregar=t.find("input[type='number']");
        t.find(".select").prop("checked",true);
        
        inputCantidadEntregar.val(inputCantidadEntregar.data("faltantes"));
        inputCantidadEntregar.attr("disabled",true);
      }

      function deselectRow(t){
        t.removeClass('selected');
        var inputCantidadEntregar=t.find("input[type='number']");
        t.find(".select").prop("checked",false);
        
        inputCantidadEntregar.val("");
        inputCantidadEntregar.attr("disabled",false);
      }

      $(document).on("click", ".sinVehiculo", function(){
        $(this.dataset.idSelectVehiculo).val(0);
      });

      $(document).on("click", ".btnVer", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();

        verDetallePresupuesto(id_pedido);

      });

      $(document).on("click", ".btnBorrarAdjunto", function(){
        fila = $(this).closest("tr");
        let id_adjunto = fila.find('td:eq(0)').text();
        let nombre_adjunto = fila.find('td:eq(1)').text()
        let accion = "borrarAdjunto";

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este archivo, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            //accion = "borrarAdjunto";
            $.ajax({
              url: "models/administrar_presupuestos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion: accion, id_adjunto: id_adjunto, nombre_adjunto: nombre_adjunto},    
              success: function(data) {
                fila.remove();
                swal({
                  icon: 'success',
                  title: 'Archivo eliminado exitosamente'
                });
              }
            }) 
          } else {
            swal("El registro no se eliminó!");
          }
        })
      });

      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);
        let id_pedido = parseInt($(this).closest('tr').find('td:eq(0)').text());

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este presupuesto no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarPresupuesto";
            $.ajax({
              url: "models/administrar_presupuestos.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_pedido:id_pedido},
              success: function() {
                //tablaPresupuestos.row(fila.parents('tr')).remove().draw();
                swal({
                  icon: 'success',
                  title: 'Presupuesto eliminado correctamente'
                });
                tablaPresupuestos.DataTable().ajax.reload(null, false);
              }
            }); 
          } else {
            swal("El registro no se eliminó!");
          }
        })

      });

      $(document).on("click", "#btnBorrarSeleccionado", function(){
        let id_presupuesto=$("#id_tarea_mantenimiento_borrar").html();
        swal({
          title: "Estas seguro?",
          text: "Una vez eliminada esta tarea no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarMantenimientoPreventivoIndividual";
            $.ajax({
              url: "models/administrar_presupuestos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_presupuesto:id_presupuesto},    
              success: function() {
                tablaPresupuestos.row(fila.parents('tr')).remove().draw();                  
              }
            }); 
          } else {
            swal("El registro no se eliminó!");
          }
        })
      });

      $(document).on("click", "#btnBorrarPendientes", function(){
        let id_presupuesto=$("#id_tarea_mantenimiento_borrar").html();
        swal({
          title: "Estas seguro?",
          text: "Una vez eliminadas estas tareas no volveras a verlas",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarMantenimientoPreventivoPendientes";
            $.ajax({
              url: "models/administrar_presupuestos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_presupuesto:id_presupuesto},    
              success: function() {
                tablaPresupuestos.ajax.reload(null, false);
              }
            }); 
          } else {
            swal("El registro no se eliminó!");
          }
        })
      });

    </script>
  </body>
</html>