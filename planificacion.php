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
    <title>MYLA - Planificacion</title>
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
                  <h3>Planificacion</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Planificacion</li>
                  </ol>
                  <span class="d-none" id="id_empresa"><?=$_SESSION['rowUsers']['id_empresa']?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <!-- Ajax Generated content for a column start-->
          <div class="row">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="d-inline">Calendario de Ordenes de Trabajo</h5>
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
                  <h5>Grilla de presupuestos aprobados</h5>
                  <!-- <button id="btnNuevoPresupuesto" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nuevo presupuesto</button> -->
                </div><?php
                //var_dump($_SESSION["rowUsers"]["id_empresa"]);?>
                <div class="card-body">
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
            <span id="id_presupuesto_ot" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formOrdenTrabajo" >
            <div class="modal-body">
              <!--Accordion wrapper-->
              <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                <!-- Accordion card datos del presupuesto -->
                <!-- <div class="card border-secondary">
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    <div class="card-header" role="tab" id="heading1">
                      <h6 class="mb-0">Datos del pedido <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
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
                </div> -->
                <!-- Fin Accordion card -->
                <!-- Accordion card datos generales de la OT-->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
                    <div class="card-header" role="tab" id="heading2">
                      <h6 class="mb-0">Datos generales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapse2" class="collapse show" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Centro de costos:</label>
                            <select class="form-control" name="id_centro_costos_ot" id="id_centro_costos_ot" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="fecha" class="col-form-label">*Fecha:</label>
                            <input type="date" class="form-control" name="fecha" id="fecha" required min="<?=date("Y-m-d")?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Desde:</label>
                            <input type="time" class="form-control" name="hora_desde" id="hora_desde" required value="<?=$desde=date("H:i")?>">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Hasta:</label>
                            <input type="time" class="form-control" name="hora_hasta" id="hora_hasta" required value="<?=date("H:i",strtotime($desde."+8 hours"))?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card tareas-->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapse3" aria-expanded="true" aria-controls="collapse3">
                    <div class="card-header" role="tab" id="heading3">
                      <h6 class="mb-0">Tareas <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapse3" class="collapse" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row d-none">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Cliente:</label>
                            <select class="form-control" id="id_cliente">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Ubicacion:</label>
                            <select class="form-control" id="id_ubicacion">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="table-responsive" id="contTablaTareas">  
                        <table class="table table-hover" id="tablaTareas">
                          <thead class="text-center">
                            <tr>
                              <th class="text-center">Seleccione</th>
                              <th>Rubro</th>
                              <th>Titulo</th>
                              <th>Descripcion</th>
                              <th>Cantidad</th>
                              <th>UM</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tfoot class="text-center">
                            <tr>
                              <th class="text-center">Seleccione</th>
                              <th>Rubro</th>
                              <th>Titulo</th>
                              <th>Descripcion</th>
                              <th>Cantidad</th>
                              <th>UM</th>
                              <th>Total</th>
                            </tr>
                          </tfoot>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Fin Accordion card -->
                <!-- Accordion card técnicos-->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapse5" aria-expanded="false" aria-controls="collapse5">
                    <div class="card-header" role="tab" id="headingTwo3">
                      <h6 class="mb-0">Técnicos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapse5" class="collapse" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-12"><h5>Resumen de cargos:</h5></div>
                        <div class="col-12" id="resumenCargos"></div>
                      </div>
                      <div class="table-responsive tablaTecnicos">
                        <table class="table table-hover" id="tablaTecnicos">
                          <thead class="text-center">
                            <tr>
                              <th>Seleccione</th>
                              <th>#ID</th>
                              <th>Tecnico</th>
                              <th>Cargo</th>
                              <th>Vehiculo</th>
                              <th>Agenda</th>
                            </tr>
                          </thead>
                          <!-- <tfoot class="text-center">
                            <tr>
                              <th>Seleccione</th>
                              <th>#ID</th>
                              <th>Tecnico</th>
                              <th>Vehiculo</th>
                            </tr>
                          </tfoot> -->
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

    <!--Modal para ver detalle de OT asignadas previamente-->
    <div class="modal fade" id="modalDetalleOTAsignadas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="nombre_tecnico"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <div class="card border-secondary"> -->
              <!-- Card header -->
              <!-- <div class="card-header" role="tab" id="heading2">
                <h6 class="mb-0 text-white">Ordenes de trabajo asignadas para el <span id="fecha_ot_asignadas"></span></h6>
              </div> -->
              <!-- Card body -->
              <!-- <div class="card-body border-secondary"> -->
                <label for="fecha_ot_asignadas">Fecha: </label><span id="fecha_ot_asignadas"></span>
                <table id="tablaOTAsignadas" class="table">
                  <thead>
                    <th>ID OT</th>
                    <th>Cliente</th>
                    <th>Hora desde</th>
                    <th>Hora hasta</th>
                  </thead>
                  <tbody></tbody>
                </table>
              <!-- </div> -->
            <!-- </div> -->
          </div>
          <!-- <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- FINAL MODAL para ver detalle de OT asignadas previamente-->

    <!--Modal para generar ordenes de compra-->
    <div class="modal fade" id="modalOrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <!-- <span id="id_presupuesto_oc" class="d-none"></span> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formOrdenCompra" >
            <div class="modal-body">
              <div class="row d-none">
                <div class="col-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Centro de costos:</label>
                    <select class="form-control" id="id_centro_costos_oc">
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Almacen:</label>
                    <select class="form-control" id="almacen">
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">Almacen: <span id="nombre_almacen"></span></div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive" style="">
                    <table class="table table-hover table-sm" id="tablaItems">
                      <thead class="text-center">
                        <tr>
                          <th>Imagen</th>
                          <th>Item</th>
                          <th>UM</th>
                          <th>Proveedor</th>
                          <th>Tipo</th>
                          <th>Categoría</th>
                          <th>Precio unit.</th>
                          <th>Stock</th>
                          <th>Necesario</th>
                          <th>Pedir</th>
                          <!-- <th>Acciones</th> -->
                        </tr>
                      </thead>
                      <tfoot class="text-center">
                        <tr>
                          <th>Imagen</th>
                          <th>Item</th>
                          <th>UM</th>
                          <th>Proveedor</th>
                          <th>Tipo</th>
                          <th>Categoría</th>
                          <th>Precio unit.</th>
                          <th>Stock</th>
                          <th>Necesario</th>
                          <th>Pedir</th>
                          <!-- <th>Acciones</th> -->
                        </tr>
                      </tfoot>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-dark">Generar OC</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL para generar ordenes de compra-->

    <?php
    //include_once("assets/presupuestos/modal_crear_editar.php")?>

    <!--Modal para ver detalle-->
    <div class="modal fade" id="modalVerDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelVerDetalle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabelVerDetalle"></h5>
            <span id="id_presupuesto_detalle" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!--Accordion wrapper-->
            <div class="accordion md-accordion" id="accordionVerDetalle" role="tablist" aria-multiselectable="true">
              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseOne1VerDetalle" aria-expanded="true" aria-controls="collapseOne1VerDetalle">
                  <div class="card-header" role="tab" id="headingOne1VerDetalle">
                    <h6 class="mb-0">Datos del pedido <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseOne1VerDetalle" class="collapse show" role="tabpanel" aria-labelledby="headingOne1VerDetalle" data-parent="#accordionVerDetalle">
                  <div class="card-body border-secondary">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Cliente: </label><span id="lblClienteVerDetalle"></span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Ubicacion: </label><span id="lblUbicacionVerDetalle"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Contacto: </label><span id="lblContactoVerDetalle"></span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Prioridad: </label><span id="lblPrioridadVerDetalle"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Descripcion: </label><span id="lblDescripcionVerDetalle"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Fecha: </label><span id="lblFechaVerDetalle"></span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Número: </label><span id="lblNumeroVerDetalle"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Caducidad: </label><span id="lblCaducidadVerDetalle"></span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Tipo: </label><span id="lblTipoVerDetalle"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="" class="col-form-label font-weight-bold">Comentarios: </label><span id="lblComentariosVerDetalle"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN Accordion card -->
              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseTwo2VerDetalle" aria-expanded="false" aria-controls="collapseTwo2VerDetalle">
                  <div class="card-header" role="tab" id="headingTwo2VerDetalle">
                    <h6 class="mb-0">Materiales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseTwo2VerDetalle" class="collapse" role="tabpanel" aria-labelledby="headingTwo2VerDetalle" data-parent="#accordionVerDetalle">
                  <div class="card-body border-secondary">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaItemsVerDetalle">
                        <thead class="text-center">
                          <tr>
                            <th>Item</th>
                            <th>Imagen</th>
                            <th>UM</th>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Precio unit.</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                    <div class="row">
                      <div class="col-12 mt-3">
                        <span class="float-right">
                          <label class="font-weight-bold h4">Total:</label>
                          <span class="subtotalMaterialesFormateado h4">$ 0,00</span>
                          <span class="subtotalMateriales d-none"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN Accordion card -->
              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseThree3VerDetalle" aria-expanded="false" aria-controls="collapseThree3VerDetalle">
                  <div class="card-header" role="tab" id="headingThree3VerDetalle">
                    <h6 class="mb-0">Cargos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseThree3VerDetalle" class="collapse" role="tabpanel" aria-labelledby="headingThree3VerDetalle" data-parent="#accordionVerDetalle">
                  <div class="card-body border-secondary">
                    <div class="table-responsive tablaCargos">
                      <table class="table table-hover" id="tablaCargosVerDetall">
                        <thead class="text-center">
                          <tr>
                            <th>#ID</th>
                            <th>Cargo</th>
                            <th>$/hs</th>
                            <th>Jornadas</th>
                            <th>Hs/Jornada</th>
                            <th>Subtotal</th>
                          </tr>
                        </thead>
                        <tfoot class="text-center">
                          <tr>
                            <th>#ID</th>
                            <th>Cargo</th>
                            <th>$/hs</th>
                            <th>Jornadas</th>
                            <th>Hs/Jornada</th>
                            <th>Subtotal</th>
                          </tr>
                        </tfoot>
                        <tbody></tbody>
                      </table>
                    </div>
                    <div class="row">
                      <div class="col-12 mt-3">
                        <span class="float-right">
                          <label class="font-weight-bold h4">Total:</label>
                          <span class="subtotalCargosFormateado h4">$ 0,00</span>
                          <span class="subtotalCargos d-none"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN Accordion card -->
              <!-- Accordion card -->
              <div class="card border-secondary">
                <!-- Card header -->
                <a class="collapsed" data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseFour4VerDetalle" aria-expanded="false" aria-controls="collapseFour4VerDetalle">
                  <div class="card-header" role="tab" id="headingFour4VerDetalle">
                    <h6 class="mb-0">Totales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                  </div>
                </a>
                <!-- Card body -->
                <div id="collapseFour4VerDetalle" class="collapse" role="tabpanel" aria-labelledby="headingFour4VerDetalle" data-parent="#accordionVerDetalle">
                  <div class="card-body border-secondary">
                    <div class="row">
                      <div class="col-4 mt-3 d-flex align-items-center">
                        <label class="font-weight-bold w-100 text-right">Total materiales y cargos:</label>
                      </div>
                      <div class="col-3 mt-3 align-self-center">
                        <span class="subtotalCargosFormateado pull-right">$ 0,00</span>
                        <span class="subtotalCargos d-none"></span>
                      </div>
                      <div class="col-2 mt-3"></div>
                    </div>
                    <div class="row">
                      <div class="col-4 mt-3 d-flex align-items-center">
                        <label class="font-weight-bold w-100 text-right">Gastos generales:</label>
                      </div>
                      <div class="col-3 mt-3 align-self-center">
                        <span class="subtotalGastosGeneralesFormateado pull-right">$ 0,00</span>
                        <span class="subtotalGastosGenerales d-none"></span>
                      </div>
                      <div class="col-2 mt-3">
                        <label for="" class="col-form-label" id="lblPorcentajeGastosGenerales"></label>%
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4 mt-3 d-flex align-items-center">
                        <label class="font-weight-bold w-100 text-right">Movilidad:</label>
                      </div>
                      <div class="col-3 mt-3 align-self-center">
                        <span class="subtotalMovilidadFormateado pull-right">$ 0,00</span>
                        <span class="subtotalMovilidad d-none"></span>
                      </div>
                      <div class="col-2 mt-3">
                        <label for="" class="col-form-label" id="lblPorcentajeMovilidad"></label>%
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4 mt-3 d-flex align-items-center">
                        <label class="font-weight-bold w-100 text-right">Rentabilidad:</label>
                      </div>
                      <div class="col-3 mt-3 align-self-center">
                        <span class="subtotalRentabilidadFormateado pull-right">$ 0,00</span>
                        <span class="subtotalRentabilidad d-none"></span>
                      </div>
                      <div class="col-2 mt-3">
                        <div class="input-group">
                          <label for="" class="col-form-label" id="lblPorcentajeRentabilidad"></label>%
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN Accordion card -->
            </div>
            <!-- Accordion wrapper -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL para ver detalle-->

    <!--Modal con opciones para tareas de mantenimiento-->
    <div class="modal fade" id="modalOpcionesCalendario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <span id="id_tarea_mantenimiento" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <button type="button" data-dismiss="modal" class="btn btn-success" id="btnCrearOT"><i class='fa fa-edit'></i> Crear Orden de Trabajo</button>
            <!-- <button type="button" data-dismiss="modal" class="btn btn-dark"  data-toggle="modal" data-target="#modalMarcarTareaCompleta"><i class="fa fa-check"></i> Completar</button>
            <button type="button" data-dismiss="modal" class="btn btn-danger" id="btnBorrarTarea"><i class="fa fa-trash-o"></i> Borrar</button> -->
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
    <script src="assets/js/datatable/datatable-extension/dataTables.rowGroup.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/custom.js"></script>

    <script src="assets/js/select2/select2.full.min.js"></script>
    <script src="assets/js/select2/select2-custom.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>

    <script src="assets/js/fullCalendar/main.min.js"></script>
    <script src="assets/js/fullCalendar/locales/es.js"></script>
    <!-- <script src="assets/presupuestos/modal_crear_editar.js"></script>
    <script src="assets/tareas/form_crear_editar.js"></script> -->
    
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">
      var accion = "";
      var tablaTareas=$("#tablaTareas");
      var tablaTecnicos= $('#tablaTecnicos');
      var tablaMaterialesOT=$("#tablaMaterialesOT");
      var tablaPresupuestos=$('#tablaPresupuestos')

      var materialesOT=[]
      var cargosOT=[]
      var cargosTareasSeleccionadas=[]
      /*var tablaItems=$('#tablaItems');
      var tablaCargos=$('#tablaCargos');
      var tablaTecnicos=$('#tablaTecnicos');*/

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
        
        let id_estado=$("#id_estado").html();
        let id_estado_pedido="3,4,5"
        tablaPresupuestos.DataTable({
          "ajax": {
              //"url" : "./models/administrar_presupuestos.php?accion=traerPresupuestosAprobados",
              "url" : "./models/administrar_presupuestos.php?accion=traerPresupuestos&id_estado_pedido="+id_estado_pedido,
              "dataSrc": "",
            },
          "stateSave": true,
          "columns":[
            {"data": "id_pedido"},
            {"data": "pedido.cliente"},
            {"data": "pedido.direccion"},
            {"data": "pedido.contacto_cliente"},
            {"data": "total_precio_mostrar"},
            {"data": "estado"},
            {render: function(data, type, row, meta) {
              return ()=>{
                //si la orden esta finalizada no se puede editar
                
                let btnVer="<button class='btn btn-warning btnVer' title='Ver detalle'><i class='fa fa-eye'></i></button>";
                let btnOrdenCompra="";
                let btnOrdenTrabajo="";
                if(row.id_estado_pedido==4){
                  //btnBorrar=btnEditar="";
                  btnOrdenCompra=`<button class='btn btn-primary btnOrdenCompra' data-id-almacen='${row.pedido.id_almacen}' data-almacen='${row.pedido.almacen}' data-id-centro-costos='${row.pedido.id_centro_costos}' title='Crear Ordenes de Compra'><i class='fa fa-shopping-cart'></i></button>`;
                }else if(row.id_estado_pedido==30){//CAMBIAR EL 30 POR EL ESTADO QUE CORRESPONDA
                  btnOrdenTrabajo="<button class='btn btn-primary btnOrdenTrabajo' title='Crear Orden de Trabajo'><i class='fa fa-briefcase'></i></button>";
                }
                btnOrdenTrabajo="<button class='btn btn-primary btnOrdenTrabajo' title='Crear Orden de Trabajo'><i class='fa fa-briefcase'></i></button>";//ELIMINAR -> SOLO PARA PRUEBAS
                
                if(!row.total_precio){
                  btnOrdenCompra=btnOrdenTrabajo=""
                }

                let buttons=btnOrdenCompra+btnOrdenTrabajo+btnVer;
                return `
                <div class='text-center'>
                  <span class="d-none" id="cliente_pedido_${row.id_pedido}">${row.pedido.id_cliente}</span>
                  <div class='btn-group'>${buttons}</div>
                </div>`;
              };
            }},
          ],
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

        calendar = new FullCalendar.Calendar(document.getElementById('cal-agenda-view'), {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            //right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          //events: eventosJSON,
          events: "./models/administrar_presupuestos.php?accion=traerOrdenesTrabajo",
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
            console.log(event);
            //if(event.extendedProps.realizado==0){
                //$('#modalOpcionesCalendario').modal('show');
                var modalOpcionesCalendario=$('#modalOpcionesCalendario');
                modalOpcionesCalendario.modal('show');
                //modalOpcionesCalendario.find("#id_tarea_mantenimiento").html(event.id);
            //}
          },
          dateClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            console.log(info);
            var today  = new Date();
            if(info.date>=today){
              var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

              var modalOpcionesCalendario=$('#modalOpcionesCalendario');
              modalOpcionesCalendario.find(".modal-title").html(info.date.toLocaleDateString("es-AR",options))
              modalOpcionesCalendario.modal('show');
              
              let fecha=document.getElementById("fecha");
              console.log(fecha);
              fecha.value="2023-01-01";
              fecha.min=today.toISOString().split('T')[0];
              
              $(fecha).on("change",function(){
                console.log(this.value);
                //this.value="2023-01-01"
              })
              fecha.value=info.dateStr.toString()
              
              //modalOpcionesCalendario.find("#id_tarea_mantenimiento").html(event.id);
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

        $('#modalDetalleOTAsignadas').on('hidden.bs.modal', function (e) {
          $("#modalOrdenTrabajo").css("z-index","1050")
        });
      });

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
                $option.setAttribute("value", centro_costos.id_centro_costo);
                $selectCentroCostos.appendChild($option);
            });
            $selectCentroCostos= document.getElementById("id_centro_costos_oc");
            //Genero los options del select de vehiculos
            respuestaJson.centroCostos.forEach((centro_costos)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(centro_costos.nombre);
                $option.appendChild(optionText);
                $option.setAttribute("value", centro_costos.id_centro_costo);
                $selectCentroCostos.appendChild($option);
            });

            //Identifico el select de almacenes
            $selectClientes= document.getElementById("almacen");
            //Genero los options del select de almacenes
            respuestaJson.almacenes.forEach((almacen)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(almacen.almacen);
                $option.appendChild(optionText);
                $option.setAttribute("value", almacen.id_almacen);
                $selectClientes.appendChild($option);
            });

            //Identifico el select de clientes
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

      /*$(document).on("change", "#id_cliente", function(){
        getUbicacionesCliente();
        getTareasSinProgramar();
      });*/

      /*$(document).on("change", "#id_ubicacion,#fecha", function(){
        getTareasSinProgramar();
      });*/

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

      function getTareasSinProgramar(id_pedido,aTareas){
        let fecha=document.getElementById("fecha").value;
        /*if(id_ubicacion==undefined){
          id_ubicacion=document.getElementById("id_ubicacion").value;
        }*/
        //console.log(aTareas);
        let id_estado=1;// pendiente
        //materialesOT=[]
        cargosOT=[]
        tablaTareas.dataTable().fnDestroy();
        tablaTareas.DataTable({
          "ajax": {
            "url" : "./models/administrar_presupuestos.php?accion=traerTareasCargosSinProgramar&id_pedido="+id_pedido,
            "dataSrc": "",
          },
          rowId: 'id_tarea',
          "columns":[
            //{"data": "id_item"},
            //{"defaultContent" : "<input class='form-control select' type='checkbox'>"},
            {render: function(data, type, row, meta) {
              return ()=>{
                if(type=="type"){
                  cargosOT.push({
                    "id_tarea":row.id_tarea,
                    "cargos":row.cargos
                  })
                }
                //checkeamos las filas si queremos editar
                let checked="";
                if(aTareas!=undefined){
                  if(aTareas.includes(row.id_mantenimiento_preventivo)){
                    checked="checked";
                  }
                }
                return `<input class='form-control select' type='checkbox' ${checked}>`;//
              };
            }},
            /*{"data": "elemento.descripcion"},
            {"data": "asunto"},
            {"data": "detalle"},
            {"data": "cantidad_mostrar"},
            {"data": "unidad_medida"},*/

            //{"data": "id_tarea"},
            {"data": "rubro","className":"d-none"},
            {"data": "asunto"},
            {"data": "detalle"},
            {"data": "cantidad_mostrar"},
            {"data": "unidad_medida"},
            {render: function(data, type, row, meta) {
              return ()=>{
                //RENDER se ejecuta muchas veces, por lo que hago el calculo solo cuando se muestra
                if(type=="display"){
                  //mapTotalTarea.set(row.id_tarea,row.total_tarea)
                  return new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(row.total_tarea)
                }else{
                  return row.total_tarea;
                }
              };
            }},
          ],
          rowGroup: {
            endRender: function ( rows, group ) {
              var sum = rows.data().pluck("total_tarea").reduce( function (a, b) {
                //return a + b.replace(/[^\d]/g, '')*1;
                //return a + b.replace(/[$.]/g, '')*1;
                return parseFloat(a) + parseFloat(b);
              }, 0);
              return 'Total: '+$.fn.dataTable.render.number('.',',',2,'$').display( sum );
            },
            dataSrc: "rubro"
          },
          columnDefs: [
            {target: 1,visible: false},
            {targets: ["_all"], className: "clickeable"},
          ],
          "language": idiomaEsp,
          initComplete: function(){
            console.log(cargosOT);
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
              selectDeselectRow(celda=this)
            });

            //si hay filas checkeadas las mostramos como seleccionadas
            tablaTareas.find("input[type='checkbox']:checked").each(function(){
              var t=$(this).closest("tr");
              selectRow(t);
            })
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

      /*$(document).on("click", "#btnActualizarPresupuesto", function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        $('#modalPresupuesto').modal('hide');
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
      });*/

      $('#formOrdenTrabajo').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
        let id_pedido=$('#id_presupuesto_ot').html();
        let id_cliente=$('#cliente_pedido_'+id_pedido).html();

        let tareas= {};
        //let tareas= [];
        tablaTareas.DataTable().rows(function ( idx, data, node ) {
          if($(node).hasClass("selected")){
            //let id_tarea=data.id_tarea
            //let id_tarea=data
            tareas[idx]={data};
            //tareas.push(id_tarea)
          }
        });
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

        datosEnviar.append("id_centro_costos", $.trim($('#id_centro_costos_ot').val()));
        datosEnviar.append("fecha", $.trim($('#fecha').val()));
        datosEnviar.append("hora_desde", $.trim($('#hora_desde').val()));
        datosEnviar.append("hora_hasta", $.trim($('#hora_hasta').val()));
        datosEnviar.append("tipoTareas", "correctivo");
        datosEnviar.append("id_pedido", id_pedido);
        datosEnviar.append("id_cliente", id_cliente);

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
              tablaPresupuestos.DataTable().ajax.reload(null, false);

              //$('#modalCRUD').modal('hide');
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
        });
      });

      $(document).on("click", ".btnOrdenCompra", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();

        let idAlmacen=this.dataset.idAlmacen
        let almacen=this.dataset.almacen
        let idCentroCostos=this.dataset.idCentroCostos

        $("#id_centro_costos_oc").val(idAlmacen)
        $("#almacen").val(idCentroCostos)
        $("#nombre_almacen").html(almacen)

        $("#formOrdenCompra").trigger("reset");
        let modal=$('#modalOrdenCompra');
        modal.find(".modal-header").css( "background-color", "#17a2b8");
        modal.find(".modal-header").css( "color", "white" );
        modal.find(".modal-title").html("Generar Ordenes de Compra para el presupuesto ID <span id='id_pedido_oc'>"+id_pedido+"</span>");
        modal.modal('show');

        getItems(id_pedido)
      });

      function getItems(id_pedido){
        //console.log(tablaItems);
        let tablaItems=$("#tablaItems")
        tablaItems.dataTable().fnDestroy();
        tablaItems.DataTable({
          "ajax": {
              //"url" : "./models/administrar_stock.php?accion=traerItems",
              "url" : "./models/administrar_presupuestos.php?accion=traerStockMaterialesPresupuesto&id_pedido="+id_pedido,
              "dataSrc": "",
            },
          "columns":[
            //{"data": "id_item"},
            {render: function(data, type, row, meta) {
              return ()=>{
                let $img = " ";
                if (row.imagen !=""){
                  $img=`<img src="./views/img_items/${row.imagen}" class="img-thumbnail">`;
                }
                return $img;
              };
            }},
            {"data": "item"},
            {"data": "unidad_medida"},
            {"data": "proveedor"},
            {"data": "tipo"},
            {"data": "categoria"},
            {"data": "precio_unitario_mostrar"},
            {"data": "cantidad_disponible_mostrar"},
            //{"data": "cantidad_estimada_mostrar"},
            {render: function(data, type, row, meta) {
              return ()=>{
                let class_text="text-danger"
                console.log(row.cantidad_disponible);
                console.log(row.cantidad_estimada);
                if(parseFloat(row.cantidad_disponible)>=parseFloat(row.cantidad_estimada)){
                  class_text="text-success"
                }
                return `<span class="${class_text}">${row.cantidad_estimada_mostrar}</span>`
              }
            }},
            {"data": "pedir_mostrar"},
            /*{render: function(data, type, row, meta) {
              return ()=>{
                let accion
                if(row.cantidad_disponible>=row.cantidad_estimada){
                  accion="Reservar"
                }else{
                  accion="Pedir"
                }
                return `<div class='btn btn-sm btn-success btn${accion}' data-id-item='${row.id_item}' data-id-proveedor='${row.id_proveedor}'>${accion}</div>`;
              };
            }},*/
          ],
          columnDefs: [
            { className: 'text-right', targets: [6] },
            { className: 'text-center', targets: [7,8,9] },
          ],
          //"order": [[ 8, "desc" ]],
          "language":  idiomaEsp,
          initComplete: function(){
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b>1 && b<5){
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

      $("#formOrdenCompra").submit(function(e){
        e.preventDefault()

        let id_pedido=$("#id_pedido_oc").html()
        $.ajax({
          url: "models/administrar_presupuestos.php",
          type: "POST",
          datatype:"json",
          data:  {accion: "generarOrdenesCompra", id_pedido: id_pedido},
          success: function(data) {
            console.log(data);
            if(data>0){
              $('#modalOrdenCompra').modal('hide');
              swal({
                icon: 'success',
                title: 'Ordenes de compra generadas exitosamente'
              });
            }
          }
        })
      })

      $(document).on("click", ".btnOrdenTrabajo", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();
        $("#formOrdenTrabajo").trigger("reset");

        abrirModalCrearOT(id_pedido)

      })

      $(document).on("click", "#btnCrearOT", function(){
        //abrirModalCrearOT()
      });

      function abrirModalCrearOT(id_pedido){

        accion = "addOrdenTrabajo"
        let modal=$('#modalOrdenTrabajo');
        modal.find(".modal-header").css( "background-color", "#17a2b8");
        modal.find(".modal-header").css( "color", "white" );
        //modal.find$(".modal-title").text("Nueva Orden de Trabajo para el presupuesto ID "+id_presupuesto);
        modal.find(".modal-title").text("Nueva Orden de Trabajo Pedido ID "+id_pedido);
        modal.modal('show');

        $('#tablaTareas').DataTable().clear().draw();
        $("#tablaTareas").dataTable().fnDestroy();
        $("#tablaTareas").dataTable({"language":  idiomaEsp});

        //$("#fecha").val(fecha).attr("min",new Date());
        getTareasSinProgramar(id_pedido)
        getTecnicos([])
        $('#id_presupuesto_ot').html(id_pedido);
      }

      function getStockItems(materiales){
        let data=[]
        let btnReservar="<button class='btn btn-success btnReservar'>Reservar</button>"
        let btnPedir="<button class='btn btn-success btnPedir'>Pedir</button>"
        let hayStockSuficienteParaLaOT=1;
        /*materiales.forEach(material => {
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
        });*/
        //console.log(tablaItems);
        tablaMaterialesOT.dataTable().fnDestroy();
        tablaMaterialesOT.DataTable({
          "ajax": {
            "url" : "./models/administrar_stock.php?accion=traerItems",
            "dataSrc": "",
          },
          "columns":[
            {"data": "id_item","class":"d-none"},
            {
              render: function(data, type, row, meta) {
                return ()=>{
                  let $img = " ";
                  if (row.imagen !=""){
                    $img=`<img src="./views/img_items/${row.imagen}" class="img-thumbnail">`;
                  }
                  return $img;
                };
              }
            },
            {"data": "item"},
            {"data": "proveedor"},
            {"data": "almacen"},
            {"data": "cantDisp"},
            {render: function(data, type, row, meta) {
              return ()=>{
                return `<label data-id-item="${row.id_item}" data-id-proveedor="${row.id_proveedor}" class="necesario"></label>`;
              };
            }},
            {render: function(data, type, row, meta) {
              return ()=>{
                if(hayStockSuficienteParaLaOT==1){
                  accion=btnReservar;
                }else{
                  accion="";
                  if(row.accion=="pedir"){
                    accion=btnPedir;
                  }
                }
                return accion
              };
            }}
          ],
          "language":  idiomaEsp,
          initComplete: function(){
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b<6 && b>2){
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

      $(document).on("click", ".btnEditar", function(){
        fila = $(this).closest("tr");
        let id_presupuesto = fila.find('td:eq(0)').text();

        editarPresupuesto(id_presupuesto)

      });

      function selectDeselectRow(celda){
        if($(celda).hasClass("clickeable")){
          if($(celda).parent().hasClass('selected')){
            deselectRow(celda);
          }else{
            selectRow(celda);
          }
        }
      }

      function selectRow(celda){
        let row=$(celda).parent()
        let table=row.parents("table");
        if(table.attr("id")=="tablaTareas"){
          //obtenemos el id de la fila que es el id de la tarea
          let id_tarea=row[0].id;
          //obtenemos los cargos de la tarea
          let cargos = cargosOT.find( cargo => cargo.id_tarea === id_tarea );

          //agregamos los cargos de la tarea a una nuevo array de tareas elegidas
          cargosTareasSeleccionadas.push(cargos)

          getTecnicos(cargosTareasSeleccionadas)
        }
        if(table.attr("id")=="tablaTecnicos"){
          console.log(row[0].childNodes);
        }
        row.addClass('selected');
        row.find(".select").prop("checked",true);
      }

      function deselectRow(celda){
        let row=$(celda).parent()
        let table=row.parents("table");
        if(table.attr("id")=="tablaTareas"){
          //obtenemos el id de la fila que es el id de la tarea
          let id_tarea=row[0].id;
          //obtenemos los cargos de la tarea
          let cargos = cargosOT.find( cargo => cargo.id_tarea === id_tarea );

          //generamos una copia del array de tareas elegidas sin la tarea deseleccionada
          cargosTareasSeleccionadas = cargosTareasSeleccionadas.filter(function(cargo) {
            return cargo.id_tarea !== id_tarea; 
          });

          getTecnicos(cargosTareasSeleccionadas)
        }
        if(table.attr("id")=="tablaTecnicos"){
          
        }

        row.removeClass('selected');
        row.find(".select").prop("checked",false);
      }

      function agruparCargosSumandoCantidadPersonas(cargosTareasSeleccionadas){

        //generamos un nuevo array sin las tareas
        let cargos=[];
        cargosTareasSeleccionadas.forEach((cargosTareas)=>{
          //cargos=[ ...cargos, ...cargosTareas.cargos]
          cargos=cargos.concat(cargosTareas.cargos)
        })
        /*Yo lo resolvería con un Array.reduce de la siguiente manera:

        1) Primero inicializaría el valor que el reduce va a devolver en un arreglo vacío
        2) Luego comprobaría si el elemento ya existe en el acumulador.
          2.1) Si no existe, simplemente devuelvo un nuevo arreglo, con los elementos previos que hayan en el arreglo y el valor actual de la iteración
          2.2) Si existe, itero el acumulador con map para devolver un nuevo arreglo, vuelvo y compruebo que el objeto exista, si existe le sumo la cantidad actual de la iteración del map con la cantidad actual de la iteración del reduce, si no existe simplemente devuelvo el valor actual de la iteración dentro del map.*/

        //return cargosTareasSeleccionadasSinDuplicados = cargos.reduce((acumulador, valorActual) => {
        return cargos.reduce((acumulador, valorActual) => {
          const elementoYaExiste = acumulador.find(elemento => elemento.id_cargo === valorActual.id_cargo);
          if (elementoYaExiste) {
            return acumulador.map((elemento) => {
              if (elemento.id_cargo === valorActual.id_cargo) {
                return {
                  ...elemento,//"..." es un operador de propagacion incluido en ECMAScript 6
                  cantidad_personas: parseInt(elemento.cantidad_personas) + parseInt(valorActual.cantidad_personas),
                  horas_jornada: parseInt(elemento.horas_jornada) + parseInt(valorActual.horas_jornada)
                }
              }
              return elemento;
            });
          }

          return [...acumulador, valorActual];
        }, []);

      }

      function getTecnicos(cargosTareasSeleccionadas){
        let cargosAgrupados=agruparCargosSumandoCantidadPersonas(cargosTareasSeleccionadas)

        resumenCargos="";
        cargosAgrupados.forEach((cargos)=>{
          //cargos=[ ...cargos, ...cargosTareas.cargos]
          //cargos=cargos.concat(cargosTareas.cargos)
          /*resumenCargos+=`
            <div id="resumen${cargos.id_cargo}">
              ${cargos.cargo}: <span class="cant_cargo">${cargos.cantidad_personas}</span>
              (<span class="total_horas_por_jornada">${cargos.horas_por_jornada}</span> Hs. totales)
            </div>
          `;*/
          resumenCargos+=`
            <div id="resumen${cargos.id_cargo}">
              ${cargos.cargo}: <span class="total_horas_por_jornada">${cargos.horas_jornada}</span> Hs. totales
            </div>
          `;
        })
        $("#resumenCargos").html(resumenCargos);

        let cargos = cargosAgrupados.map(function(a) {return a.id_cargo;});
        cargos=cargos.toString();

        tablaTecnicos.dataTable().fnDestroy();
        tablaTecnicos.DataTable({
          "ajax": {
            "url" : "./models/administrar_tecnicos.php?accion=traerTecnicos&cargos="+cargos,
            "dataSrc": "",
          },
          "columns":[
            {"defaultContent" : "<input class='form-control select' type='checkbox'>"},
            {"data": "id_tecnico"},
            {"data": "nombre_completo"},
            {"data": "cargo.cargo"},
            {render: function(data, type, row, meta) {
              return ()=>{
                //genero los select vacíos para luego obtener los vehiculos. Guardo en el atributo data el id_vehiculo de cada tecnico 
                let id="vehiculo"+row.id_tecnico;
                return `<div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <select class="select_vehiculos" id="${id}" data-id-vehiculo="${row.id_vehiculo}">
                      <option value="0">- Sin vehiculo -</option>
                    </select>
                  </div>
                  <button class='btn btn-sm btn-secondary sinVehiculo' type="button" data-id-select-vehiculo='#${id}' title='Sin vehiculo'><i class="fa fa-ban" aria-hidden="true"></i></button>
                  </div>`;
              };
            }},
            {render: function(data, type, row, meta) {
              return ()=>{
                //genero un boton para ver el detalle de los horarios agendados 
                return `<button class='btn btn-sm btn-warning horariosOcupados' type="button" data-id-tecnico='${row.id_tecnico}' title='Ver horarios ocupados'><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button>`;
              };
            }}
          ],
          columnDefs: [
            {targets: [0,1,2,3], class: "clickeable"},
            {targets: [5], class: "text-center"},
          ],
          "language":  idiomaEsp,
          initComplete: function(){
            $('[title]').tooltip();
            
            this.find("tbody tr td").on( 'click', function () {
              selectDeselectRow(celda=this)
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
        });
      }

      $(document).on("click", ".sinVehiculo", function(){
        $(this.dataset.idSelectVehiculo).val(0);
      });

      $(document).on("click", ".horariosOcupados", function(){
        //console.log($(this).parents("tr").find("td:eq(2)").text());
        let nombre_tecnico=$(this).parents("tr").find("td:eq(2)").text()
        let id_tecnico=this.dataset.idTecnico
        $("#modalOrdenTrabajo").css("z-index","1040")
        let modalDetalleOTAsignadas=$("#modalDetalleOTAsignadas")
        $("#nombre_tecnico").html(nombre_tecnico);
        modalDetalleOTAsignadas.modal("show")

        $tabla = document.getElementById("tablaOTAsignadas");
        $bodyTablaOTAsignadas = $tabla.querySelector("tbody");
        $bodyTablaOTAsignadas.innerHTML="";

        let fecha=$("#fecha").val()
        if(fecha==""){
          $bodyTablaOTAsignadas.innerHTML=`<tr>
            <td colspan="4" class="text-center">Seleccione una fecha</td>
          </tr>`;
        }else{
          e=fecha.split("-")
          //let fecha_mostrar  = new Date(fecha);
          fecha_mostrar=e[2]+"-"+e[1]+"-"+e[0]
          /*const options = { year: 'numeric', month: 'long', day: 'numeric' };
          fecha_mostrar=fecha_mostrar.toLocaleDateString("es-ES",options);*/
          $("#fecha_ot_asignadas").html(fecha_mostrar)

          let datosUpdate = new FormData();
          datosUpdate.append('accion', 'traerAgendaTecnicos');
          datosUpdate.append('id_tecnico', id_tecnico);
          datosUpdate.append('fecha', fecha);
          $.ajax({
            data: datosUpdate,
            url: './models/administrar_orden_trabajo.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            success: function(datosProcesados){
              //console.log(datosProcesados);
              let datosInput = JSON.parse(datosProcesados);
              console.log(datosInput);

              if(datosInput.length>0){
                datosInput.forEach((ots)=>{
                  console.log(ots);
                  $tr=`<tr>
                        <td>${ots.id_orden_trabajo}</td>
                        <td>${ots.cliente}</td>
                        <td>${ots.hora_desde_mostrar}</td>
                        <td>${ots.hora_hasta_mostrar}</td>
                    </tr>`;
                  $bodyTablaOTAsignadas.innerHTML +=$tr;
                })
              }else{
                $bodyTablaOTAsignadas.innerHTML=`<tr>
                  <td colspan="4" class="text-center">No se han encontrado registros</td>
                </tr>`;
              }
            }
          });
        }
      });

      $(document).on("click", ".btnVer", function(){
        fila = $(this).closest("tr");
        let id_presupuesto = fila.find('td:eq(0)').text();

        let modal=$('#modalVerDetalle');
        modal.find(".modal-header").css( "background-color", "#ffc107").css( "color", "white" );
        modal.find(".modal-title").text("Ver presupuesto N° "+id_presupuesto);
        modal.modal('show');

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
            /*let dmp=datosInput.datos_mantenimiento_preventivo;
            console.log(dmp);*/

            $("#lblClienteVerDetalle").html(datosInput.cliente);
            $("#lblUbicacionVerDetalle").html(datosInput.direccion);
            $("#lblElementoVerDetalle").html(datosInput.descripcion_activo);
            $("#lblAsuntoVerDetalle").html(datosInput.asunto);
            $("#lblDetalleVerDetalle").html(datosInput.detalle);
            $("#lblPrioridadVerDetalle").html(datosInput.prioridad);
            //$("#lblContacto").html(datosInput.contacto_cliente);
            //$("#lblid_vehiculo_asignado").html(datosInput.id_vehiculo_asignado);
            //$("#lblcosto_movilidad_estimado").html(datosInput.costo_movilidad_estimado);
            // $("#lblFecha").html(datosInput.fecha_mostrar);
            // $("#lblHoraDesde").html(datosInput.hora_desde_mostrar);
            // $("#lblHoraHasta").html(datosInput.hora_hasta_mostrar);

            $tabla = document.getElementById("tablaItemsVerDetalle");
            $bodyTablaItems = $tabla.querySelector("tbody");
            $bodyTablaItems.innerHTML="";

            if(datosInput.materiales.length>0){
              let totalItems=0;
              datosInput.materiales.forEach((items)=>{
                info_items=items.item
                console.log(items);
                let img = " ";
                if (info_items.imagen !=""){
                  let dir="./views/img_items/"+info_items.imagen
                  img=`<a href="${dir}" target="_blank" ><img src="${dir}" class="img-thumbnail"></a>`;
                }
                let subtotal=parseFloat(items.cantidad)*parseFloat(info_items.precio_unitario_sin_formato);
                totalItems=totalItems+subtotal
                subtotal=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotal);
                $tr=`<tr>
                      <td>${info_items.item}</td>
                      <td>${img}</td>
                      <td>${info_items.unidad_medida}</td>
                      <td>${info_items.proveedor}</td>
                      <td>${info_items.tipo}</td>
                      <td>${info_items.categoria}</td>
                      <td class="pull-right">${info_items.precio_unitario}</td>
                      <td>${items.cantidad}</td>
                      <td class="pull-right">${subtotal}</td>
                  </tr>`;
                $bodyTablaItems.innerHTML +=$tr;
              })
              //$(".subtotalMateriales")
              totalItems=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(totalItems);
              $(".subtotalMaterialesFormateado").html(totalItems);
            }else{
              $bodyTablaItems.innerHTML=`<tr>
                      <td colspan="5" class="text-center">No se han encontrado registros</td>
                  </tr>`;
            }

            $('#id_presupuesto').html(id_presupuesto);
            
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
        let id_presupuesto = parseInt($(this).closest('tr').find('td:eq(0)').text());
        $("#id_tarea_mantenimiento_borrar").html(id_presupuesto);

        $("#modalOpcionesEliminarTareas").modal("show");

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