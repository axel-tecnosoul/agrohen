<?php
  session_start();
  include_once('./../conexion.php');
  date_default_timezone_set("America/Buenos_Aires");
  $hora = date('Hi');
  if (!isset($_SESSION['rowUsers']['id_usuario'])) {
      header("location:./models/redireccionar.php");
  }
?>
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
    <title>MYLA - Ordenes de trabajo</title>
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
      .modalAccordionDark .card-header{
        background-color: #2f3c4e;
      }
      .modalAccordionDark a .card-header h6{
        color: #f6f7fb;
      }
      .select:focus{
        /*border: 0px;*/
        -webkit-box-shadow: none;
        box-shadow: none;
      }
      .modal-dialog{
        overflow-y: initial !important
      }
      .modal-body{
        max-height: 75vh;
        overflow-y: auto;
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
      <!-- Page Header Ends                              -->
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
                    <h3>Ordenes de trabajo</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Ordenes de trabajo</li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
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
                    <h5>Grilla de Ordenes de trabajo</h5>
                    <button id="btnNuevoOrdenTrabajo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nueva Orden de Trabajo</button>
                  </div><?php
                  //var_dump($_SESSION["rowUsers"]["id_empresa"]);?>
                  <div class="card-body">
                    <div class="table-responsive" id="contTablaListas" >
                      <table class="table table-hover" id="tablaOrdenesTrabajo">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Cliente</th>
                            <th>Elemento</th>
                            <th>Ubicacion</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tfoot class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Cliente</th>
                            <th>Elemento</th>
                            <th>Ubicacion</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Acciones</th>
                          </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Ajax Generated content for a column end-->
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
    </div>

    <!--Modal para CRUD-->
    <div class="modal fade modalAccordionDark" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_orden_trabajo" class="d-none"></span>
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
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                    <div class="card-header" role="tab" id="headingOne1">
                      <h6 class="mb-0">Datos generales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne1" class="collapse show" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Centro de costos:</label>
                            <select class="form-control" id="id_centro_costos" required>
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
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2">
                    <div class="card-header" role="tab" id="headingOne2">
                      <h6 class="mb-0">Tareas de mantenimiento preventivo pendientes <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne2" class="collapse show" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Cliente:</label>
                            <select class="form-control" id="id_cliente" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Ubicacion:</label>
                            <select class="form-control" id="id_ubicacion" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="table-responsive tablaTareas">
                        <table class="table table-hover" id="tablaTareas">
                          <thead class="text-center">
                            <tr>
                              <th>Seleccione</th>
                              <th>Asunto</th>
                              <th>Elemento</th>
                              <th>Detalle</th>
                              <th>Fecha</th>
                              <th>Desde</th>
                              <th>Hasta</th>
                            </tr>
                          </thead>
                          <tfoot class="text-center">
                            <tr>
                              <th>Seleccione</th>
                              <th>Asunto</th>
                              <th>Elemento</th>
                              <th>Detalle</th>
                              <th>Fecha</th>
                              <th>Desde</th>
                              <th>Hasta</th>
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
                  <div id="collapseTwo3" class="collapse" role="tabpanel">
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
              <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL CRUD-->

    <!-- MODAL ver detalle empresa y linea de tiempo -->
    <div class="modal fade modalAccordionDark" id="verDetalleOrdenTrabajo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detalle Orden de Trabajo <span id="idOrdenTrabajoverDetalle"></span></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <!--Accordion wrapper-->
            <div class="accordion md-accordion" id="accordionExDetalle" role="tablist" aria-multiselectable="true">
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionExDetalle" href="#collapseOne1Detalle" aria-expanded="true" aria-controls="collapseOne1Detalle">
                    <div class="card-header" role="tab" id="headingOne1">
                      <h6 class="mb-0">Datos generales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne1Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordionExDetalle">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">Centro de costo: <span id="lblCentroCosto"></span></label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">Estado: <span id="lblEstado"></span></label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="" class="col-form-label">Fecha: <span id="lblFecha"></span></label>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="" class="col-form-label">Desde: <span id="lblHoraDesde"></span></label>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="" class="col-form-label">Hasta: <span id="lblHoraHasta"></span></label>
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
                  <a data-toggle="collapse" data-parent="#accordionExDetalle" href="#collapseOne2Detalle" aria-expanded="true" aria-controls="collapseOne2Detalle">
                    <div class="card-header" role="tab" id="headingOne2">
                      <h6 class="mb-0">Datos del cliente <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne2Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingOne2" data-parent="#accordionExDetalle">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">Cliente: <span id="lblCliente"></span></label>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">Ubicacion: <span id="lblUbicacion"></span></label>
                          </div>
                        </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-hover" id="tableContactosDetalle">
                          <thead class="text-center">
                            <tr>
                              <th>Contacto</th>
                              <th>Whatsapp</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionExDetalle" href="#collapseOne3Detalle" aria-expanded="true" aria-controls="collapseOne3Detalle">
                    <div class="card-header" role="tab" id="headingOne3">
                      <h6 class="mb-0">Tareas de mantenimiento preventivo <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne3Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingOne3" data-parent="#accordionExDetalle">
                    <div class="card-body border-secondary">
                      <div class="table-responsive">
                        <table class="table table-hover" id="tableTareasDetalle">
                          <thead class="text-center">
                            <tr>
                              <th>#ID</th>
                              <th>Asunto</th>
                              <th>Elemento</th>
                              <th>Detalle</th>
                              <th>Fecha</th>
                              <th>Desde</th>
                              <th>Hasta</th>
                            </tr>
                          </thead>
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
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionExDetalle" href="#collapseTwo4Detalle" aria-expanded="false" aria-controls="collapseTwo4Detalle">
                    <div class="card-header" role="tab" id="headingTwo4">
                      <h6 class="mb-0">Técnicos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseTwo4Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingTwo4" data-parent="#accordionExDetalle">
                    <div class="card-body border-secondary">
                      <div class="table-responsive">
                        <table class="table table-hover" id="tableTecnicosDetalle">
                          <thead class="text-center">
                            <tr>
                              <th>Tecnico</th>
                              <th>Vehiculo</th>
                              <th>Inicio</th>
                              <th>Fin</th>
                              <th>Geoposicion</th>
                            </tr>
                          </thead>
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
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionExDetalle" href="#collapseTwo5Detalle" aria-expanded="false" aria-controls="collapseTwo5Detalle">
                    <div class="card-header" role="tab" id="headingTwo5">
                      <h6 class="mb-0">Materiales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseTwo5Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingTwo5" data-parent="#accordionExDetalle">
                    <div class="card-body border-secondary">
                      <div class="table-responsive">
                        <table class="table table-hover" id="tableMaterialesDetalle">
                          <thead class="text-center">
                            <tr>
                              <th>Material</th>
                              <th>Cantidad Estimada</th>
                              <th>Cargado vehiculo</th>
                              <th>Cantidad utilizada</th>
                              <th>Aprobado cliente</th>
                            </tr>
                          </thead>
                          <tbody class="text-center"></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionExDetalle" href="#collapseTwo6Detalle" aria-expanded="false" aria-controls="collapseTwo6Detalle">
                    <div class="card-header" role="tab" id="headingTwo6">
                      <h6 class="mb-0">Adjuntos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseTwo6Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingTwo6" data-parent="#accordionExDetalle">
                    <div class="card-body border-secondary">
                      <div class="table-responsive">
                        <table class="table table-hover" id="tableAdjuntosDetalle">
                          <thead class="text-center">
                            <tr>
                            <th>#ID</th>
                            <th>Archivo</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Comentarios</th>
                            </tr>
                          </thead>
                          <tbody class="text-center"></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
              </div>
              <!-- Accordion wrapper -->
          </div>
        </div>
      </div>
    </div>
    <!-- FIN MODAL ver detalle empresa y linea de tiempo-->

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
    <script src="assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/chart/chartjs/chart.min.js"></script>
    <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>

    <script src="assets/js/fullCalendar/main.min.js"></script>
    <script src="assets/js/fullCalendar/locales/es.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->

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
    <script src="assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.scroller.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/custom.js"></script>
    <!-- Plugin used-->
    <script type="text/javascript">

      var accion = "";
      tablaTareas=$("#tablaTareas");
      tablaTecnicos= $('#tablaTecnicos');

      $(document).ready(function(){
        cargarDatosComponentes();
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
        tablaOrdenesTrabajo= $('#tablaOrdenesTrabajo').DataTable({
          "ajax": {
            "url" : "./models/administrar_orden_trabajo.php?accion=traerOrdenesTrabajoPreventiva",
            "dataSrc": "",
          },
          "stateSave": true,
          "columns":[
            {"data": "id_orden_trabajo"},
            {"data": "elemento.cliente"},
            {"data": "elemento.descripcion"},
            //{"data": "asunto"},
            {"data": "elemento.direccion"},
            {"data": "estado"},
            {"data": "fecha_mostrar"},
            {"data": "hora_desde_mostrar"},
            {"data": "hora_hasta_mostrar"},
            //{"data": "vehiculo"},
            // {"data": "costo_movilidad_estimado_mostrar"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  //si la orden esta finalizada no se puede editar
                  
                  let btnEditar="<button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button>";
                  let btnVer="<button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button>";
                  let btnBorrar="<button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button>";

                  if(full.id_estado==3){
                    btnBorrar=btnEditar="";
                  }
                  let buttons=btnEditar+btnVer+btnBorrar;
                  return `
                  <div class='text-center'>
                    <div class='btn-group'>${buttons}</div>
                  </div>`;
                };
              }
            },
          ],
          "language":  idiomaEsp,
          dom: '<"mr-2 d-inline"l>Bfrtip',
          buttons: [
            {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel-o"></i>',
              titleAttr: 'Excel',
              title:     "Ordenes de trabajo",
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
              title:     "Ordenes de trabajo",
              titleAttr: 'PDF',
              download: 'open',
              className: 'btn-danger',
              exportOptions: {
                columns: ':not(:last-child)',
              }
            }
          ],
          initComplete: function(){
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b!=1 && b!=9){
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

        tablaTecnicos.DataTable({
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

                /*Identifico los select de vehiculos de la tabla de tecnicos*/
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
        });

        $('#modalCRUD').on('hidden.bs.modal', function (e) {
          /*document.getElementById('dropMasArchivos').innerHTML="";
          document.getElementById('masAdjuntos').classList.toggle("d-none");*/
        });
      });

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesOrdenesTrabajo');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_orden_trabajo.php",
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

            /*Identifico el select de centro de costos*/
            $selectCentroCostos= document.getElementById("id_centro_costos");
            //Genero los options del select de centro de costos
            respuestaJson.centro_costos.forEach((centro_costo)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(centro_costo.nombre);
                $option.appendChild(optionText);
                $option.setAttribute("value", centro_costo.id_centro_costo);
                $selectCentroCostos.appendChild($option);
            });

            /*Identifico el select de clientes*/
            $selectClientes= document.getElementById("id_cliente");
            //Genero los options del select de clientes
            respuestaJson.clientes.forEach((cliente)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(cliente.razon_social);
                $option.appendChild(optionText);
                $option.setAttribute("value", cliente.id_cliente);
                $selectClientes.appendChild($option);
            });

          }
        });
      }

      $(document).on("click", ".sinVehiculo", function(){
        //console.log(this.dataset.idSelectVehiculo);
        //console.log($(this.dataset.idSelectVehiculo))
        $(this.dataset.idSelectVehiculo).val(0);
      });

      $(document).on("change", "#id_cliente", function(){
        getUbicacionesCliente();
        getTareasMantenimientoPreventivoPendientes();
      });

      function getUbicacionesCliente(id_cliente,id_ubicacion_cliente){
        let datosIniciales = new FormData();
        if(id_cliente==undefined){
          id_cliente=document.getElementById("id_cliente").value;
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
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);
            let listaUbicaciones=respuestaJson;

            /*Identifico el select de direcciones*/
            $selectUbicacionesCliente= document.getElementById("id_ubicacion");
            $selectUbicacionesCliente.innerHTML = "";
            /*Genero los options del select de direcciones*/
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

      $(document).on("change", "#id_ubicacion,#fecha", function(){
        getTareasMantenimientoPreventivoPendientes();
      });

      function getTareasMantenimientoPreventivoPendientes(id_ubicacion,aTareas){
        let fecha=document.getElementById("fecha").value;
        if(id_ubicacion==undefined){
          id_ubicacion=document.getElementById("id_ubicacion").value;
        }
        //console.log(aTareas);
        let id_estado=1;// pendiente
        tablaTareas.dataTable().fnDestroy();
        if(id_ubicacion>0){
          tablaTareas.DataTable({
            "ajax": {
              "url" : "./models/administrar_mantenimieno_preventivo.php?accion=traerMantenimientoPreventivo&id_ubicacion="+id_ubicacion+"&fecha="+fecha+"&id_estado="+id_estado,
              "dataSrc": "",
            },
            "columns":[
              //{"data": "id_item"},
              //{"defaultContent" : "<input class='form-control select' type='checkbox'>"},
              {
                render: function(data, type, full, meta) {
                  return ()=>{
                    //checkeamos las filas si queremos editar
                    let checked="";
                    if(aTareas!=undefined){
                      if(aTareas.includes(full.id_mantenimiento_preventivo)){
                        checked="checked";
                      }
                    }
                    return `<input class='form-control select' type='checkbox' ${checked}>`;//
                  };
                }
              },
              {"data": "asunto"},
              {"data": "descripcion_activo"},
              {"data": "detalle"},
              {"data": "fecha_mostrar"},
              {"data": "hora_desde_mostrar"},
              {"data": "hora_hasta_mostrar"},
            ],
            "language": idiomaEsp,
            initComplete: function(){
              var b=1;
              var c=0;
              this.api().columns.adjust().draw();//Columns sin parentesis
              this.api().columns().every(function(){//Columns() con parentesis
                if(b>1 && b<4){
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
              
              tablaTareas.find("tbody tr td").on( 'click', function () {
                var t=$(this).parent();
                if(t.hasClass('selected')){
                  deselectRow(t);
                }else{
                  selectRow(t);
                }
              });

              //si hay filas checkeadas las mostramos como seleccionadas
              tablaTareas.find("input[type='checkbox']:checked").each(function(){
                var t=$(this).closest("tr");
                selectRow(t);
              })
            }
          });
        }else{
          tablaTareas.DataTable({"language": idiomaEsp,});
        }
      }

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

      $('#formOrdenTrabajo').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   

        let tareas= {};
        //let tareas= [];
        tablaTareas.DataTable().rows(function ( idx, data, node ) {
          if($(node).hasClass("selected")){
            tareas[idx]={data};
            /*let id_tarea=data.id_tarea
            tareas.push(id_tarea)*/
          }
        });
        //console.log(tareas)
        tareasJson=JSON.stringify(tareas);
        //console.log(tareasJson)

        let tecnicos= {};
        tablaTecnicos.DataTable().rows(function ( idx, data, node ) {
          let fila=$(node);
          if(fila.hasClass("selected")){
            //console.log(fila.find(".select_vehiculos").val());
            data.id_vehiculo=fila.find(".select_vehiculos").val();
            tecnicos[idx]={data};
          }
        });
        //console.log(tecnicos)
        tecnicosJson=JSON.stringify(tecnicos);
        //console.log(tecnicosJson)
        
        let datosEnviar = new FormData();
        datosEnviar.append("tareas", tareasJson);
        datosEnviar.append("tecnicos", tecnicosJson);

        datosEnviar.append("id_centro_costos", $.trim($('#id_centro_costos').val()));
        datosEnviar.append("fecha", $.trim($('#fecha').val()));
        datosEnviar.append("hora_desde", $.trim($('#hora_desde').val()));
        datosEnviar.append("hora_hasta", $.trim($('#hora_hasta').val()));
        datosEnviar.append("id_cliente", $.trim($('#id_cliente').val()));

        datosEnviar.append("id_orden_trabajo", $.trim($('#id_orden_trabajo').html()));
        datosEnviar.append("accion", accion);
        //console.log(accion);

        $.ajax({
          data: datosEnviar,
          url: "models/administrar_orden_trabajo.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            if(data==""){
              tablaOrdenesTrabajo.ajax.reload(null, false);

              $('#modalCRUD').modal('hide'); 
          
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
        });
      });

      $("#btnNuevoOrdenTrabajo").click(function(){
          accion = "addOrdenTrabajo"
          $("#formOrdenTrabajo").trigger("reset");
          $(".modal-header").css( "background-color", "#17a2b8");
          $(".modal-header").css( "color", "white" );
          $(".modal-title").text("Nueva orden de trabajo");
          $('#tablaTareas').DataTable().clear().draw();
          $("#tablaTareas").dataTable().fnDestroy();
          $("#tablaTareas").dataTable({"language":  idiomaEsp});
          $('#modalCRUD').modal('show');

          //tablaTareas.ajax.reload(null, false);
      });

      $(document).on("click", ".btnVer", function(){
        let fila = $(this);           
        let id_orden_trabajo = parseInt($(this).closest('tr').find('td:eq(0)').text()); 
        let accion = "traerDetalleOrdenTrabajo";
        
        $.ajax({
          url: "models/administrar_orden_trabajo.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, id_orden_trabajo:id_orden_trabajo},
          success: function(response) {
            console.log(response);
            let datos = JSON.parse(response);
            //console.log(datos);
            let dot=datos.detalle_orden_trabajo;
            $(".modal-header").css( "background-color", "#ffc107");
            $(".modal-header").css( "color", "white" );

            verDetalleOrdenTrabajo=$("#verDetalleOrdenTrabajo");
            verDetalleOrdenTrabajo.modal("show");
            $("#idOrdenTrabajoverDetalle").html(" N°"+id_orden_trabajo)

            /*let $divAdjuntos = document.getElementById('adjuntos');
            $divAdjuntos.innerHTML="";*/
            $("#lblCentroCosto").html(dot.nombre_centro_costo);
            $("#lblFecha").html(dot.fecha_mostrar);
            $("#lblHoraDesde").html(dot.hora_desde_mostrar);
            $("#lblHoraHasta").html(dot.hora_hasta_mostrar);
            $("#lblEstado").html(dot.estado);

            $("#lblCliente").html(dot.cliente);
            $("#lblUbicacion").html(dot.direccion);

            $tabla = document.getElementById("tableContactosDetalle");
            $bodyTablaContactos = $tabla.querySelector("tbody");
            $bodyTablaContactos.innerHTML="";

            if(datos.contactos_orden_trabajo.length>0){
              datos.contactos_orden_trabajo.forEach((contactos)=>{
                  $tr=`<tr>
                        <td>${contactos.nombre_completo}</td>
                        <td>
                          <a href="https://wa.me/${contactos.telefono}?text=" target="_blank" class="item">
                            <i class="fa fa-whatsapp" aria-hidden="true"></i> ${contactos.telefono}
                          </a>
                        </td>
                    </tr>`;
                  $bodyTablaContactos.innerHTML +=$tr;
              })
            }else{
              $bodyTablaContactos.innerHTML=`<tr>
                      <td colspan="6" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

            $tabla = document.getElementById("tableTareasDetalle");
            $bodyTablaTareas = $tabla.querySelector("tbody");
            $bodyTablaTareas.innerHTML="";

            if(datos.tareas_orden_trabajo.length>0){
              datos.tareas_orden_trabajo.forEach((tareas)=>{
                  $tr=`<tr>
                        <td>${tareas.id_mantenimiento_preventivo}</td>
                        <td>${tareas.asunto}</td>
                        <td>${tareas.descripcion_activo}</td>
                        <td>${tareas.detalle}</td>
                        <td>${tareas.fecha_mostrar}</td>
                        <td>${tareas.hora_desde_mostrar}</td>
                        <td>${tareas.hora_hasta_mostrar}</td>
                    </tr>`;
                  $bodyTablaTareas.innerHTML +=$tr;
              })
            }else{
              $bodyTablaTareas.innerHTML=`<tr>
                      <td colspan="6" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

            $tabla = document.getElementById("tableTecnicosDetalle");
            $bodyTablaTecnicos = $tabla.querySelector("tbody");
            $bodyTablaTecnicos.innerHTML="";

            console.log("tecnicos_orden_trabajo");
            console.log(datos.tecnicos_orden_trabajo);
            if(datos.tecnicos_orden_trabajo.length>0){
              datos.tecnicos_orden_trabajo.forEach((tecnicos)=>{
                  $tr=`<tr>
                        <td>${tecnicos.tecnico}</td>
                        <td>${tecnicos.vehiculo}</td>
                        <td>${tecnicos.fecha_hora_inicio_trabajo}</td>
                        <td>${tecnicos.fecha_hora_fin_trabajo}</td>
                        <td>${tecnicos.geoposicion_inicio_trabajo}</td>
                    </tr>`;
                  $bodyTablaTecnicos.innerHTML +=$tr;
              })
            }else{
              $bodyTablaTecnicos.innerHTML=`<tr>
                      <td colspan="5" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

            $tabla = document.getElementById("tableMaterialesDetalle");
            $bodyTablaMateriales = $tabla.querySelector("tbody");
            $bodyTablaMateriales.innerHTML="";

            console.log("materiales_orden_trabajo");
            console.log(datos.materiales_orden_trabajo);
            if(datos.materiales_orden_trabajo.length>0){
              datos.materiales_orden_trabajo.forEach((materiales)=>{
                  $tr=`<tr>
                        <td>${materiales.item}</td>
                        <td>${materiales.cantidad_reservada}</td>
                        <td>${materiales.cargado_vehiculo_mostrar}</td>
                        <td>${materiales.cantidad_utilizada}</td>
                        <td>${materiales.aprobado_cliente}</td>
                    </tr>`;
                  $bodyTablaMateriales.innerHTML +=$tr;
              })
            }else{
              $bodyTablaMateriales.innerHTML=`<tr>
                      <td colspan="5" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

            $tabla = document.getElementById("tableAdjuntosDetalle");
            $bodyTablaAdjuntos = $tabla.querySelector("tbody");
            $bodyTablaAdjuntos.innerHTML="";

            console.log("adjuntos_orden_trabajo");
            console.log(datos.adjuntos_orden_trabajo);
            if(datos.adjuntos_orden_trabajo.length>0){
              datos.adjuntos_orden_trabajo.forEach((adjunto)=>{
                  $tr=`<tr>
                        <td>${adjunto.id_adjunto}</td>
                        <td><a href="./views/adjuntosOT/${adjunto.archivo}" target="_blank" >${adjunto.archivo}</a></td>
                        <td>${adjunto.email}</td>
                        <td>${adjunto.comentarios}</td>
                        <td>${adjunto.fecha}</td>
                    </tr>`;
                  $bodyTablaAdjuntos.innerHTML +=$tr;
              })
            }else{
              $bodyTablaAdjuntos.innerHTML=`<tr>
                      <td colspan="5" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

          }
        });
      });

      $(document).on("click", ".btnEditar", function(){
        fila = $(this).closest("tr");
        let id_orden_trabajo = fila.find('td:eq(0)').text();
        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar orden de trabajo N°"+id_orden_trabajo);
        $("#formOrdenTrabajo").trigger("reset");
        $('#modalCRUD').modal('show');

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerDetalleOrdenTrabajo');
        datosUpdate.append('id_orden_trabajo', id_orden_trabajo);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_orden_trabajo.php',
          //url: './models/administrar_orden_trabajo.php?accion=traerOrdenTrabajo&id_orden_trabajo='+id_orden_trabajo,
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#procesando').modal('show');
          },
          success: function(datosProcesados){
            //console.log(datosProcesados);
            let datosInput = JSON.parse(datosProcesados);
            let dot=datosInput.detalle_orden_trabajo;
            //console.log(dot);

            $("#id_centro_costos").val(dot.id_centro_costos);
            $("#fecha").val(dot.fecha);
            $("#hora_desde").val(dot.hora_desde);
            $("#hora_hasta").val(dot.hora_hasta);
            $("#id_cliente").val(dot.id_cliente);
            getUbicacionesCliente(dot.id_cliente,dot.id_direccion_cliente)

            let aTareas=[];
            datosInput.tareas_orden_trabajo.forEach((tareas)=>{
              aTareas.push(tareas.id_mantenimiento_preventivo);
            })
            //console.log(aTareas);

            getTareasMantenimientoPreventivoPendientes(dot.id_direccion_cliente,aTareas);

            let aTecnicos=[];
            datosInput.tecnicos_orden_trabajo.forEach((tecnicos)=>{
              aTecnicos.push(tecnicos.id_tecnico);
            })
            
            tablaTecnicos.find("tbody tr").each(function(){
              let fila=$(this);
              deselectRow(fila);
              let tecnicoFila=fila.find('td:eq(1)').text();

              datosInput.tecnicos_orden_trabajo.forEach((tecnicos)=>{
                if(tecnicos.id_tecnico==tecnicoFila){
                  /*fila.addClass("selected");
                  fila.find("input[type='checkbox']").attr("checked","checked");*/
                  selectRow(fila);
                  fila.find(".select_vehiculos").val(tecnicos.id_vehiculo)
                }
              })
            })
            
            $('#id_orden_trabajo').html(id_orden_trabajo);
            
            accion = "updateOrdenTrabajo";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);           
        id_orden_trabajo = parseInt($(this).closest('tr').find('td:eq(0)').text());       
        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado esta orden, no volveras a verla",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarOrdenTrabajo";
            $.ajax({
              url: "models/administrar_orden_trabajo.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_orden_trabajo:id_orden_trabajo},    
              success: function() {
                tablaOrdenesTrabajo.row(fila.parents('tr')).remove().draw();                  
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