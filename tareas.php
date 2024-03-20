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
    <title>MYLA - Tareas de Mantenimiento Preventivo</title>
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
      .modal .card-header{
        background-color: #2f3c4e;
      }
      .modal a .card-header h6{
        color: #f6f7fb;
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
                    <h3>Tareas de Mantenimiento Preventivo</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Tareas de Mantenimiento Preventivo</li>
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
              </div>

              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <h5>Grilla de tareas</h5>
                      <button id="btnNuevoMantenimientoPreventivo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nuevo tarea</button>
                    </div><?php
                    //var_dump($_SESSION["rowUsers"]["id_empresa"]);?>
                    <div class="card-body">
                      <div class="table-responsive" id="contTablaListas" >
                        <table class="table table-hover" id="tablaTareas">
                          <thead class="text-center">
                            <tr>
                              <th class="text-center">#ID</th>
                              <th>Cliente</th>
                              <th>Elemento</th>
                              <th>Asunto</th>
                              <th>Detalle</th>
                              <th>Fecha</th>
                              <th>Hs desde</th>
                              <th>Hs hasta</th>
                              <th>Estado</th>
                              <th>Contacto</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tfoot class="text-center">
                            <tr>
                              <th class="text-center">#ID</th>
                              <th>Cliente</th>
                              <th>Elemento</th>
                              <th>Asunto</th>
                              <th>Detalle</th>
                              <th>Fecha</th>
                              <th>Hs desde</th>
                              <th>Hs hasta</th>
                              <th>Estado</th>
                              <th>Contacto</th>
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
    <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_mantenimiento_preventivo" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formMantenimientoPreventivo" >
            <div class="modal-body">
              <!--Accordion wrapper-->
              <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                    <div class="card-header" role="tab" id="headingOne1">
                      <h6 class="mb-0">Datos principales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne1" class="collapse show" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Cliente:</label>
                            <select class="form-control" id="cliente">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Ubicacion</label>
                            <select class="form-control" id="ubicacion" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Contacto:</label>
                            <select class="form-control" id="id_contacto_cliente" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Elemento</label>
                            <select class="form-control" id="id_elemento_cliente" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">
                    <div class="card-header" role="tab" id="headingTwo2">
                      <h6 class="mb-0">Datos de la tarea <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseTwo2" class="collapse" role="tabpanel" aria-labelledby="headingTwo2" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Asunto:</label>
                            <input type="text" class="form-control" id="asunto" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Detalle:</label>
                            <input type="text" class="form-control" id="detalle" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Prioridad:</label>
                            <select class="form-control" id="prioridad">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <!-- <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="" class="col-form-label">Adjuntos:</label>
                            <input type="file" class="form-control" id="adjuntos" multiple>
                          </div>
                        </div>
                      </div> -->
                      <div class="row">
                        <div class="col-lg-4 mb-2"><button id="btnAddAdjuntos" class="btn btn-secondary"> Agregar adjuntos</button></div>
                        <div class="col-lg-12 d-none" id="masAdjuntos">
                          <div id="dropMasArchivos"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary" id="cardFechaHoraEjecucion">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree4" aria-expanded="false" aria-controls="collapseThree4">
                    <div class="card-header" role="tab" id="headingThree4">
                      <h6 class="mb-0">Fecha y hora de ejecucion <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseThree4" class="collapse" role="tabpanel" aria-labelledby="headingThree4" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Fecha:</label>
                            <input type="date" class="form-control" id="fecha" value="<?=date("Y-m-d")?>" required>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Hora desde:</label>
                            <input type="time" class="form-control" id="hora_desde" value="<?=$horaDesde=date("H:i")?>" required>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Hora Hasta:</label>
                            <input type="time" class="form-control" id="hora_hasta" value="<?=date("H:i",strtotime($horaDesde."+1 hours"))?>" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-2">
                          <div class="form-group">
                            <label for="" class="col-form-label">Repetir cada:</label>
                          </div>
                        </div>
                        <div class="col-lg-2">
                          <div class="form-group">
                            <input type="number" step="1" class="form-control" id="frecuencia_cantidad" placeholder="cantidad">
                          </div>
                        </div>
                        <div class="col-lg-2">
                          <div class="form-group">
                            <select id="frecuencia_repeticion" class="form-control">
                              <option value="days">Días</option>
                              <option value="week">Semanas</option>
                              <option value="month">Meses</option>
                              <option value="year">Años</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-2">
                          <div class="form-group">
                          <label for="" class="col-form-label">Hasta el:</label>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <input type="date" class="form-control" id="frecuencia_stop" placeholder="cantidad">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree5" aria-expanded="false" aria-controls="collapseThree5">
                    <div class="card-header" role="tab" id="headingThree5">
                    
                      <h6 class="mb-0">Materiales previstos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseThree5" class="collapse" role="tabpanel" aria-labelledby="headingThree5" data-parent="#accordionEx">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Almacenes:</label>
                            <select class="form-control" id="id_almacen">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="table-responsive tablaItems">
                        <table class="table table-hover" id="tablaItems">
                          <thead class="text-center">
                            <tr>
                              <!-- <th>#ID</th> -->
                              <th>Item</th>
                              <th>Imagen</th>
                              <th>UM</th>
                              <th>Proveedor</th>
                              <th>Tipo</th>
                              <th>Categoría</th>
                              <th>Cantidad estimada</th>
                            </tr>
                          </thead>
                          <tfoot class="text-center">
                            <tr>
                              <!-- <th>#ID</th> -->
                              <th>Item</th>
                              <th>Imagen</th>
                              <th>UM</th>
                              <th>Proveedor</th>
                              <th>Tipo</th>
                              <th>Categoría</th>
                              <th>Cantidad estimada</th>
                            </tr>
                          </tfoot>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Accordion card -->
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

    <!--Modal para Ver Detalle-->
    <div class="modal fade" id="modalVerDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelDetalle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabelDetalle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!--Accordion wrapper-->
            <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1Detalle" aria-expanded="true" aria-controls="collapseOne1Detalle">
                  <div class="card-header" role="tab" id="headingOne1Detalle">
                    <h6 class="mb-0">Datos principales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseOne1Detalle" class="collapse show" role="tabpanel" aria-labelledby="headingOne1Detalle" data-parent="#accordionEx">
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
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label">Contacto: <span id="lblContacto"></span></label>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label">Elemento: <span id="lblElemento"></span></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Accordion card -->

              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2Detalle" aria-expanded="false" aria-controls="collapseTwo2Detalle">
                  <div class="card-header" role="tab" id="headingTwo2Detalle">
                    <h6 class="mb-0">Datos de la tarea <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseTwo2Detalle" class="collapse" role="tabpanel" aria-labelledby="headingTwo2Detalle" data-parent="#accordionEx">
                  <div class="card-body border-secondary">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label">Asunto: <span id="lblAsunto"></span></label>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label">Detalle: <span id="lblDetalle"></span></label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label">Prioridad: <span id="lblPrioridad"></span></label>
                        </div>
                      </div>
                    </div>
                    <div class="table-responsive tablaItems">
                      <table class="table table-hover" id="tablaAdjuntosDetalle">
                        <thead class="text-center">
                          <tr>
                            <th>#ID</th>
                            <th>Archivo</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Accordion card -->
              
              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree4Detalle" aria-expanded="false" aria-controls="collapseThree4Detalle">
                  <div class="card-header" role="tab" id="headingThree4Detalle">
                    <h6 class="mb-0">Fecha y hora de ejecucion<i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseThree4Detalle" class="collapse" role="tabpanel" aria-labelledby="headingThree4Detalle" data-parent="#accordionEx">
                  <div class="card-body border-secondary">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="" class="col-form-label">Fecha: <span id="lblFecha"></span></label>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="" class="col-form-label">Hora desde: <span id="lblHoraDesde"></span></label>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label for="" class="col-form-label">Hora Hasta: <span id="lblHoraHasta"></span></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Accordion card -->

              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree5Detalle" aria-expanded="false" aria-controls="collapseThree5Detalle">
                  <div class="card-header" role="tab" id="headingThree5Detalle">
                    <h6 class="mb-0">Materiales previstos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseThree5Detalle" class="collapse" role="tabpanel" aria-labelledby="headingThree5Detalle" data-parent="#accordionEx">
                  <div class="card-body border-secondary">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label">Almacen: <span id="lblAlmacen"></span></label>
                        </div>
                      </div>
                    </div>
                    <div class="table-responsive tablaItems">
                      <table class="table table-hover" id="tablaItemsDetalle">
                        <thead class="text-center">
                          <tr>
                            <!-- <th>#ID</th> -->
                            <th>Item</th>
                            <th>UM</th>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Cantidad estimada</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Accordion card -->
            </div>
            <!-- Accordion wrapper -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL Ver Detalle-->

    <!--Modal con opciones para tareas de mantenimiento-->
    <div class="modal fade" id="modalOpcionesEliminarTareas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">¿Qué tareas desea eliminar?</h5>
            <span id="id_tarea_mantenimiento_borrar" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <button type="button" data-dismiss="modal" class="btn btn-dark" id="btnBorrarSeleccionado"><i class="fa fa-check"></i> Eliminar solo esta</button>
            <button type="button" data-dismiss="modal" class="btn btn-danger" id="btnBorrarPendientes"><i class="fa fa-trash-o"></i> Esta y sus repeticiones pendientes</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL con opciones para tareas de mantenimiento-->

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
    <script src="assets/js/datatable/datatable-extension/custom.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>

    <script src="assets/js/fullCalendar/main.min.js"></script>
    <script src="assets/js/fullCalendar/locales/es.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">

      var accion = "";
      tablaItems=$('#tablaItems');
      $(document).ready(function(){
        //armarCalendario();
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
        tablaTareas= $('#tablaTareas').DataTable({
          "ajax": {
              "url" : "./models/administrar_mantenimieno_preventivo.php?accion=traerMantenimientoPreventivo",
              "dataSrc": "",
            },
          "stateSave": true,
          "columns":[
            {"data": "id_mantenimiento_preventivo"},
            {"data": "cliente"},
            {"data": "descripcion_activo"},
            {"data": "asunto"},
            {"data": "detalle"},
            {"data": "fecha_mostrar"},
            {"data": "hora_desde_mostrar"},
            {"data": "hora_hasta_mostrar"},
            {"data": "estado"},
            {"data": "contacto_cliente"},
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
              title:     "Tareas de mantenimiento preventivo",
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
              title:     "Tareas de mantenimiento preventivo",
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
              if(b!=1 && b!=11){
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

        //var calendarEl = document.getElementById('cal-agenda-view');
        calendar = new FullCalendar.Calendar(document.getElementById('cal-agenda-view'), {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            //right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          //events: eventosJSON,
          events: "./models/administrar_mantenimieno_preventivo.php?accion=traerMantenimientoPreventivoCalendario",
          locale: "es",
          //defaultDate: '2016-06-12',
          //defaultView: 'agendaWeek',
          //editable: true,
          selectable: true,
          weekNumbers: true,
          navLinks: true, // can click day/week names to navigate views
          selectable: true,
          nowIndicator: true,
          dayMaxEvents: true, // allow "more" link when too many events
          //selectHelper: true,
          //droppable: true,
          //eventLimit: true,
          eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
            //second: '2-digit',
            //meridiem: false
          },
          eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            var event=info.event;
            if(event.extendedProps.realizado==0){
                //$('#modalOpcionesTareas').modal('show');
                var modalOpcionesTareas=$('#modalOpcionesTareas');
                modalOpcionesTareas.modal('show');
                modalOpcionesTareas.find("#id_tarea_mantenimiento").html(event.id);

                /*var modalMarcarTareaCompleta=$('#modalMarcarTareaCompleta');
                modalMarcarTareaCompleta.modal('show');
                modalMarcarTareaCompleta.find("#id_tarea_mantenimiento").html(event.id);*/
                /*if (event.url) {
                  window.open(event.url, "_blank");
                  return false;
                }*/
            }
          },
          eventDidMount: function(info) {
            el=info.el;
            event=info.event;
            $(el).popover({
              title: "#"+event.id+" - "+event.title,
              content: event.extendedProps.description,
              trigger: 'hover',
              placement: 'top',
              container: 'body',
              html: true
            });
          },
        });
        calendar.render();

        $('#modalCRUD').on('hidden.bs.modal', function (e) {
          document.getElementById('dropMasArchivos').innerHTML="";
          document.getElementById('masAdjuntos').classList.toggle("d-none");
        });
      });

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesMantenimientoPreventivo');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_mantenimieno_preventivo.php",
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

            /*Identifico el select de vehiculos*/
            $selectAlmacenes= document.getElementById("id_almacen");
            //Genero los options del select de vehiculos
            respuestaJson.almacenes.forEach((almacen)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(almacen.almacen);
                $option.appendChild(optionText);
                $option.setAttribute("value", almacen.id_almacen);
                $selectAlmacenes.appendChild($option);
            });

            //Identifico el select de vehiculos
            $selectPrioridades= document.getElementById("prioridad");
            //Genero los options del select de vehiculos
            respuestaJson.prioridades.forEach((prioridad)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(prioridad.prioridad);
                $option.appendChild(optionText);
                $option.setAttribute("value", prioridad.id_prioridad);
                $selectPrioridades.appendChild($option);
            });

            /*Identifico el select de clientes*/
            $selectClientes= document.getElementById("cliente");
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

      $(document).on("click", "#btnNuevoAdjunto", function(){
        $("#inputFile").removeClass("d-none");
      });

      $(document).on("change", "#id_almacen", function(){
        let id_almacen=this.value;
        getItems(id_almacen);
      });

      function getItems(id_almacen,aItems){
        tablaItems.dataTable().fnDestroy();
        tablaItems.DataTable({
          "ajax": {
              "url" : "./models/administrar_stock.php?accion=traerItems&id_almacen="+id_almacen,
              "dataSrc": "",
            },
          "columns":[
            //{"data": "id_item"},
            {"data": "item"},
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
            {"data": "unidad_medida"},
            {"data": "proveedor"},
            {"data": "tipo"},
            {"data": "categoria"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  let cantidad_estimada="";
                  if(aItems!=undefined){
                    aItems.forEach((items)=>{
                      if(full.id_item==items.id_item && full.id_proveedor==items.id_proveedor){
                        cantidad_estimada=items.cantidad_estimada;
                      }
                    })
                  }
                  return `
                  <input type='hidden' id='proveedor-item-`+full.id_item+`' value=`+full.id_proveedor+`>
                  <input type='number' class='form-control items' id='item-`+full.id_item+`' value='${cantidad_estimada}' placeholder='Cantidad estimada'>`;//
                };
              }
            },
          ],
          "language":  idiomaEsp,
          initComplete: function(){
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b!=7 && b!=2){
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

      $(document).on("change", "#cliente", function(){
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
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);
            let listaUbicaciones=respuestaJson;

            /*Identifico el select de direcciones*/
            $selectUbicacionesCliente= document.getElementById("ubicacion");
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

      $(document).on("change", "#ubicacion", function(){
        getContactosUbicacion();
        getElementosUbicacion();
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
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);
            let listaContactos=respuestaJson.contactos;

            /*Identifico el select de direcciones*/
            $selectContactosCliente= document.getElementById("id_contacto_cliente");
            $selectContactosCliente.innerHTML = "";
            /*Genero los options del select de direcciones*/
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
      }

      function getElementosUbicacion(id_ubicacion,id_elemento_cliente){
        let datosIniciales = new FormData();
        if(id_ubicacion==undefined){
          id_ubicacion=document.getElementById("ubicacion").value;
        }
        datosIniciales.append('id_ubicacion', id_ubicacion);
        datosIniciales.append('accion', 'traerElementosCliente');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_elementos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(respuesta){
            //console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);

            /*Identifico el select de direcciones*/
            $selectElementosCliente= document.getElementById("id_elemento_cliente");
            $selectElementosCliente.innerHTML = "";
            /*Genero los options del select de direcciones*/
            $option = document.createElement("option");
            let texto="Sin resultados";
            if(respuestaJson.length>0){
              texto="Seleccione un elemento";
            }
            let optionText = document.createTextNode(texto);
            $option.appendChild(optionText);
            $selectElementosCliente.appendChild($option);
            
            respuestaJson.forEach((elemento)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(elemento.descripcion);
                $option.appendChild(optionText);
                $option.setAttribute("value", elemento.id_elemento);
                if(id_elemento_cliente==elemento.id_elemento){
                  $option.setAttribute("selected", true);
                }
                $selectElementosCliente.appendChild($option);
            });

          }
        });
      }

      $('#formMantenimientoPreventivo').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        
        let datosEnviar = new FormData();
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
        
        datosEnviar.append("id_mantenimiento_preventivo", $.trim($('#id_mantenimiento_preventivo').html()));
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
          url: "models/administrar_mantenimieno_preventivo.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            if(data==""){
              tablaTareas.ajax.reload(null, false);
              calendar.refetchEvents();

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

      $("#btnNuevoMantenimientoPreventivo").click(function(){
        accion = "addMantenimientoPreventivo"
        $("#formMantenimientoPreventivo").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Nueva tarea de mantenimiento preventivo");
        $('#tablaItems').DataTable().clear().draw();
        $("#tablaItems").dataTable().fnDestroy();
        $("#tablaItems").dataTable({"language":  idiomaEsp});
        $('#modalCRUD').modal('show');
        $("#cardFechaHoraEjecucion").removeClass("d-none");
        $selectElementosCliente= document.getElementById("id_elemento_cliente");
        $selectElementosCliente.innerHTML = "";
        $option = document.createElement("option");
        let optionTextA = document.createTextNode("Sin resultados");
        $option.appendChild(optionTextA);
        $selectElementosCliente.appendChild($option);
        
        $selectContactosCliente= document.getElementById("id_contacto_cliente");
        $selectContactosCliente.innerHTML = "";
        $option = document.createElement("option");
        let optionTextB = document.createTextNode("Sin resultados");
        $option.appendChild(optionTextB);
        $selectContactosCliente.appendChild($option);

        //tablaItems.ajax.reload(null, false);
      });

      $(document).on("click", ".btnEditar", function(){
        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar tarea de mantenimiento preventivo");
        $("#formMantenimientoPreventivo").trigger("reset");
        $('#modalCRUD').modal('show');
        $("#cardFechaHoraEjecucion").addClass("d-none");
        fila = $(this).closest("tr");
        let id_mantenimiento_preventivo = fila.find('td:eq(0)').text();

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerDetalleMantenimientoPreventivo');
        datosUpdate.append('id_mantenimiento_preventivo', id_mantenimiento_preventivo);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_mantenimieno_preventivo.php',
          //url: './models/administrar_mantenimieno_preventivo.php?accion=traerMantenimientoPreventivo&id_mantenimiento_preventivo='+id_mantenimiento_preventivo,
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#procesando').modal('show');
          },
          success: function(datosProcesados){
            console.log(datosProcesados);
            let datosInput = JSON.parse(datosProcesados);
            console.log(datosInput);
            let dmp=datosInput.datos_mantenimiento_preventivo;
            console.log(dmp);

            $("#cliente").val(dmp.id_cliente);
            $("#ubicacion").val(dmp.id_direccion_cliente);
            $("#id_elemento_cliente").val(dmp.id_activo_cliente);
            $("#asunto").val(dmp.asunto);
            $("#detalle").val(dmp.detalle);
            $("#id_contacto_cliente").val(dmp.id_contacto_cliente);
            //$("#id_vehiculo_asignado").val(dmp.id_vehiculo_asignado);
            //$("#costo_movilidad_estimado").val(dmp.costo_movilidad_estimado);
            $("#fecha").val(dmp.fecha);
            $("#hora_desde").val(dmp.hora_desde);
            $("#hora_hasta").val(dmp.hora_hasta);
            $("#prioridad").val(dmp.id_prioridad)

            getUbicacionesCliente(dmp.id_cliente,dmp.id_direccion_cliente);
            getContactosUbicacion(dmp.id_cliente,dmp.id_contacto_cliente);
            getElementosUbicacion(dmp.id_cliente,dmp.id_activo_cliente);

            let mmp=datosInput.materiales_mantenimiento_preventivo;
            //console.log(mmp);

            if(mmp.length>0){
              let aItems=[];
              mmp.forEach((materiales)=>{
                let objMateriales = {
                  "id_item":materiales.id_item,
                  "id_proveedor":materiales.id_proveedor,
                  "cantidad_estimada":materiales.cantidad_estimada,
                }
                aItems.push(objMateriales);
              })
              //console.log(aItems);
              let id_almacen=mmp[0].id_almacen
              $("#id_almacen").val(id_almacen);
              getItems(id_almacen,aItems);
            }
            
            $('#id_mantenimiento_preventivo').html(id_mantenimiento_preventivo);
            
            accion = "updateMantenimientoPreventivo";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $(document).on("click", ".btnVer", function(){
        fila = $(this).closest("tr");
        let id_mantenimiento_preventivo = fila.find('td:eq(0)').text();

        $(".modal-title").text("Ver tarea de mantenimiento preventivo N° "+id_mantenimiento_preventivo);
        $('#modalVerDetalle').modal('show');

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerDetalleMantenimientoPreventivo');
        datosUpdate.append('id_mantenimiento_preventivo', id_mantenimiento_preventivo);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_mantenimieno_preventivo.php',
          //url: './models/administrar_mantenimieno_preventivo.php?accion=traerMantenimientoPreventivo&id_mantenimiento_preventivo='+id_mantenimiento_preventivo,
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
            console.log(datosInput);
            let dmp=datosInput.datos_mantenimiento_preventivo;
            console.log(dmp);

            $("#lblCliente").html(dmp.cliente);
            $("#lblUbicacion").html(dmp.direccion);
            $("#lblElemento").html(dmp.descripcion_activo);
            $("#lblAsunto").html(dmp.asunto);
            $("#lblDetalle").html(dmp.detalle);
            $("#lblPrioridad").html(dmp.prioridad);

            $tabla = document.getElementById("tablaAdjuntosDetalle");
            $bodyTablaAdjuntos = $tabla.querySelector("tbody");
            $bodyTablaAdjuntos.innerHTML="";

            let amp=datosInput.adjuntos_mantenimiento_preventivo;
            //console.log(amp);

            if(amp.length>0){
              amp.forEach((adjunto)=>{
                //console.log(adjunto);
                $tr=`<tr>
                      <td>${adjunto.id_adjunto}</td>
                      <td><a href="./views/mantenimiento_preventivo/adj_${id_mantenimiento_preventivo}_${adjunto.archivo}" target="_blank" >${adjunto.archivo}</a></td>
                      <td>${adjunto.usuario}</td>
                      <td>${adjunto.fecha_hora}</td>
                      <td><button class='btn btn-danger btnBorrarAdjunto'><i class='fa fa-trash-o'></i></button></td>
                  </tr>`;
                $bodyTablaAdjuntos.innerHTML +=$tr;
              })
            }else{
              $bodyTablaAdjuntos.innerHTML=`<tr>
                      <td colspan="5" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

            $("#lblContacto").html(dmp.contacto_cliente);
            //$("#lblid_vehiculo_asignado").html(dmp.id_vehiculo_asignado);
            //$("#lblcosto_movilidad_estimado").html(dmp.costo_movilidad_estimado);
            $("#lblFecha").html(dmp.fecha_mostrar);
            $("#lblHoraDesde").html(dmp.hora_desde_mostrar);
            $("#lblHoraHasta").html(dmp.hora_hasta_mostrar);

            let mmp=datosInput.materiales_mantenimiento_preventivo;
            //console.log(mmp);

            $tabla = document.getElementById("tablaItemsDetalle");
            $bodyTablaItems = $tabla.querySelector("tbody");
            $bodyTablaItems.innerHTML="";

            if(mmp.length>0){
              //console.log(aItems);
              let almacen=mmp[0].almacen
              $("#lblAlmacen").html(almacen);

              mmp.forEach((materiales)=>{
                //console.log(materiales);
                $tr=`<tr>
                      <td>${materiales.item}</td>
                      <td>${materiales.unidad_medida}</td>
                      <td>${materiales.proveedor}</td>
                      <td>${materiales.tipo}</td>
                      <td>${materiales.categoria}</td>
                      <td class="text-center">${materiales.cantidad_estimada}</td>
                  </tr>`;
                $bodyTablaItems.innerHTML +=$tr;
              })
            }else{
              $bodyTablaItems.innerHTML=`<tr>
                      <td colspan="6" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }
            
            $('#id_mantenimiento_preventivo').html(id_mantenimiento_preventivo);
            
            accion = "updateMantenimientoPreventivo";
          }
        });

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
              url: "models/administrar_mantenimieno_preventivo.php",
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
        let id_mantenimiento_preventivo = parseInt($(this).closest('tr').find('td:eq(0)').text());
        $("#id_tarea_mantenimiento_borrar").html(id_mantenimiento_preventivo);

        $("#modalOpcionesEliminarTareas").modal("show");

      });

      $(document).on("click", "#btnBorrarSeleccionado", function(){
        let id_mantenimiento_preventivo=$("#id_tarea_mantenimiento_borrar").html();
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
              url: "models/administrar_mantenimieno_preventivo.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_mantenimiento_preventivo:id_mantenimiento_preventivo},    
              success: function() {
                tablaTareas.row(fila.parents('tr')).remove().draw();                  
              }
            }); 
          } else {
            swal("El registro no se eliminó!");
          }
        })
      });

      $(document).on("click", "#btnBorrarPendientes", function(){
        let id_mantenimiento_preventivo=$("#id_tarea_mantenimiento_borrar").html();
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
              url: "models/administrar_mantenimieno_preventivo.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_mantenimiento_preventivo:id_mantenimiento_preventivo},    
              success: function() {
                tablaTareas.ajax.reload(null, false);
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