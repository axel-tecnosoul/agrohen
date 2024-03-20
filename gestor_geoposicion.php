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
    <title>MYLA - Gestor Geoposicion</title>
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
    <!-- Loader ends-->
    <?php
        include_once('./views/main_header.php');
      ?>
      <!-- Page Header Ends                              -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="page-sidebar">
           <?php
            include_once('./views/slideBar.php');
          ?>
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-header">
              <div class="row">
                <div class="col">
                  <div class="page-header-left">
                    <h3>Gestor Geoposicion</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Gestor Geoposicion</li>
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
                  <div class="card-body">
                    <div class="row">
                      <div class="col-3">
                        <label>Proveedor</label>
                        <select id="proveedor" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-3">
                        <label>Almacen:</label>
                        <select id="almacen" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-2 align-self-end">
                        <button class="btn btn-success btnBuscar">Buscar</button>
                      </div>
                      <div class="col-2 align-self-end">
                        <button id="btnExportar" type="button" class="btn btn-outline-primary" data-toggle="modal"><i class="fa fa-file-excel-o"></i> Exportar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="row">
              <!-- Ajax Generated content for a column start-->
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="d-inline">Calendario de Manenimiento</h5>
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
                      <h5>Vehiculos</h5>
                      <button id="btnNuevoVehiculo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nuevo vehiculo</button>
                    </div><?php
                    //var_dump($_SESSION);?>
                    <div class="card-body">
                      <div class="table-responsive" id="contTablaListas" >
                        <table class="table table-hover" id="tablaVehiculos">
                          <thead class="text-center">
                            <tr>
                              <th class="text-center">#ID</th>
                              <th>N° de movil</th>
                              <th>Patente</th>
                              <th>Marca</th>
                              <th>Modelo</th>
                              <th>Año</th>
                              <th>Tecnico asignado</th>
                              <th>KM actuales</th>
                              <th>Estado</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tfoot class="text-center">
                            <tr>
                              <th class="text-center">#ID</th>
                              <th>N° de movil</th>
                              <th>Patente</th>
                              <th>Marca</th>
                              <th>Modelo</th>
                              <th>Año</th>
                              <th>Tecnico asignado</th>
                              <th>KM actuales</th>
                              <th>Estado</th>
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
            <span id="id_vehiculo" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formVehiculos">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*N° de Movil:</label>
                    <input type="text" class="form-control" id="numero_movil" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Patente:</label>
                    <input type="text" class="form-control" id="patente" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Marca</label>
                    <select class="form-control" id="marca" required>
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Modelo:</label>
                    <input type="text" class="form-control" id="modelo" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Año:</label>
                    <input type="text" class="form-control" id="anio" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Codigo motor:</label>
                    <input type="text" class="form-control" id="codigo_motor" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Codigo chasis</label>
                    <input type="text" class="form-control" id="codigo_chasis" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Tecnico asignado</label>
                    <select class="form-control" id="tecnico_asignado" required>
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Fecha adquirido:</label>
                    <input type="date" class="form-control" id="fecha_adquirido" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*KM adquirido:</label>
                    <input class="form-control" type="number" id="km_adquirido" require>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">*N° cedula verde:</label>
                    <input type="text" class="form-control" id="nro_cedula_verde" required>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="" class="col-form-label">Vencimiento cedula verde:</label>
                    <input type="date" class="form-control" id="vencimiento_cedula_verde" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="" class="col-form-label">*KM actuales:</label>
                    <input class="form-control" type="number" id="km_actuales" require>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="" class="col-form-label">Proximo service general:</label>
                    <input type="date" class="form-control" id="proximo_service_general">
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="" class="col-form-label">Fecha de baja:</label>
                    <input type="date" class="form-control" id="fecha_baja">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Vencimiento seguro:</label>
                    <input type="date" class="form-control" id="vencimiento_seguro" required>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Vencimiento GNC:</label>
                    <input type="date" class="form-control" id="vencimiento_gnc" required>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Vencimiento VTV:</label>
                    <input type="date" class="form-control" id="proximo_vencimiento_vtv" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="" class="col-form-label">Comentarios:</label>
                    <textarea id="comentarios" class="form-control"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 mb-2"><button id="btnAddAdjuntos" class="btn btn-secondary"> Agregar adjuntos</button></div>
                <div class="col-lg-12 d-none" id="masAdjuntos">
                  <div id="dropMasArchivos"></div>
                </div>
              </div>
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
    <div class="modal fade" id="verDetalleVehiculo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detalle vehiculo ID <span id='id_detalle_vehiculo'></span></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body"></div>
        </div>
      </div>
    </div>
    <!-- FIN MODAL ver detalle empresa y linea de tiempo-->

    <!--Modal para añadir tarea de mantenimiento-->
    <div class="modal fade" id="modalAddMantenimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabelMantenimiento"></h5>
            <span id="id_vehiculo_mantenimiento" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formTareaMantenimiento">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Detalle:</label>
                    <input type="text" class="form-control" id="detalle" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Costo estimado</label>
                    <input type="text" class="form-control" id="costo_estimado" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Fecha:</label>
                    <input type="date" class="form-control" id="fecha_mantenimiento" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Hora</label>
                    <input type="time" class="form-control" id="hora_mantenimiento" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="" class="col-form-label">Comentarios:</label>
                    <textarea id="comentarios_tarea_mantenimiento" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" id="btnGuardarTareaMantenimiento" class="btn btn-dark">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL para añadir tarea de mantenimiento-->

    <!--Modal con opciones para tareas de mantenimiento-->
    <div class="modal fade" id="modalOpcionesTareas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">¿Qué desea hacer con la tarea?</h5>
            <span id="id_tarea_mantenimiento" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <button type="button" data-dismiss="modal" class="btn btn-success" id="btnEditarTarea"><i class='fa fa-edit'></i> Editar</button>
            <button type="button" data-dismiss="modal" class="btn btn-dark"  data-toggle="modal" data-target="#modalMarcarTareaCompleta"><i class="fa fa-check"></i> Completar</button>
            <button type="button" data-dismiss="modal" class="btn btn-danger" id="btnBorrarTarea"><i class="fa fa-trash-o"></i> Borrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL con opciones para tareas de mantenimiento-->

    <!--Modal para marcar tarea completa-->
    <div class="modal fade" id="modalMarcarTareaCompleta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <h5 class="modal-title">Marcar tarea completa</h5> -->
            <h5 class="modal-title">¿Marcar tarea completa?</h5>
            <!-- <span id="id_tarea_mantenimiento_completar" class="d-none"></span> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formTareaMantenimiento">
            <div class="modal-body">
              <!-- <div class="row">
                <div class="col-lg-12">¿Esta seguro que desea marcar como completa esta tarea?</div>
              </div> -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="" class="col-form-label">Adjuntar foto:</label>
                    <input class="form-control" type="file" id="adjunto_mantenimiento_vehicular" accept="image/*" data-original-title="" title="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="" class="col-form-label">Comentarios:</label>
                    <textarea class="form-control" id="comentarios_mantenimiento_vehicular"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="button" id="btnMarcarTareaMantenimientoRealizada" class="btn btn-dark">Completar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL para marcar tarea completa-->

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
        tablaVehiculos= $('#tablaVehiculos').DataTable({
          "ajax": {
              "url" : "./models/administrar_vehiculos.php?accion=traerVehiculos",
              "dataSrc": "",
            },
          "columns":[
            {"data": "id_vehiculo"},
            {"data": "numero_movil"},
            {"data": "patente"},
            {"data": "marca"},
            {"data": "modelo"},
            {"data": "anio"},
            {"data": "tecnico"},
            //{"data": "km_adquirido_mostrar"},
            {"data": "km_actuales_mostrar"},
            {"data": "estado"},
            //{"data": "fecha_alta"},
            {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button><button class='btn btn-primary btnAddMantenimiento' title='Añadir tarea de mantenimiento'><i class='fa fa-wrench'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
          ],
          "language":  idiomaEsp,
          dom: '<"mr-2 d-inline"l>Bfrtip',
          buttons: [
            {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel-o"></i>',
              titleAttr: 'Excel',
              title:     "Vehiculos",
              className: 'btn-success',
              exportOptions: {
                columns: ':not(:last-child)',
                format: {
                  body: function ( data, row, column, node ) {
                    // Eliminamos $ y reemplazamos , por . para que excel tome correctamente los decimales
                    //return column === 7 ? data.replace( /[$.]/g, '' ).replace( /[,]/g, '.' ) : data;
                    // Cambiamos el punto por una coma para que excel lo tomo como un separador de miles
                    return column === 7 ? data.replace( /[.]/g, ',' ) : data;
                  }
                }
              }
            },
            {
              extend:    'pdfHtml5',
              text:      '<i class="fa fa-file-pdf-o"></i>',
              title:     "Vehiculos",
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
              if(b!=1 && b!=10){
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
          events: "./models/administrar_vehiculos.php?accion=traerTareasMantenimientoVehiculos",
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
        datosIniciales.append('accion', 'traerDatosIniciales');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_vehiculos.php",
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

            /*Identifico el select de tecnicos*/
            $selectTecnicos= document.getElementById("tecnico_asignado");
            /*Genero los options del select de tecnicos*/
            respuestaJson.tecnicos.forEach((tecnico)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(tecnico.nombre_completo);
                $option.appendChild(optionText);
                $option.setAttribute("value", tecnico.id_tecnico);
                $selectTecnicos.appendChild($option);
            });

            /*Identifico el select de marcas*/
            $selectMarcas= document.getElementById("marca");
            /*Genero los options del select de marcas*/
            respuestaJson.marcas.forEach((marca)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(marca.marca);
                $option.appendChild(optionText);
                $option.setAttribute("value", marca.id_marca);
                $selectMarcas.appendChild($option);
            });

            /*Ingreso datos en totalizadores*/
            /*console.log(respuestaJson.totalizadores[0].totItem);
            document.getElementById("totItems").innerText=respuestaJson.totalizadores[0].totItem;
            document.getElementById("cantDisp").innerText=respuestaJson.totalizadores[0].cantDisp;
            document.getElementById("cantReserv").innerText=respuestaJson.totalizadores[0].cantReserv;
            document.getElementById("totValor").innerText=respuestaJson.totalizadores[0].valoracion;*/
          }
        });
      }

      $('#formVehiculos').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        
        let datosEnviar = new FormData();
        datosEnviar.append("patente", $.trim($('#patente').val()));
        //datosEnviar.append("fecha_alta", $.trim($('#fecha_alta').val()));
        datosEnviar.append("marca", $.trim($('#marca').val()));
        datosEnviar.append("modelo", $.trim($('#modelo').val()));
        datosEnviar.append("anio", $.trim($('#anio').val()));
        datosEnviar.append("codigo_motor", $.trim($('#codigo_motor').val()));
        datosEnviar.append("codigo_chasis", $.trim($('#codigo_chasis').val()));
        datosEnviar.append("nro_cedula_verde", $.trim($('#nro_cedula_verde').val()));
        datosEnviar.append("fecha_adquirido", $.trim($('#fecha_adquirido').val()));
        datosEnviar.append("fecha_baja", $.trim($('#fecha_baja').val()));
        datosEnviar.append("tecnico", $.trim($('#tecnico_asignado').val()));
        datosEnviar.append("comentarios", $.trim($('#comentarios').val()));
        datosEnviar.append("proximo_service_general", $.trim($('#proximo_service_general').val()));
        datosEnviar.append("km_adquirido", $.trim($('#km_adquirido').val()));
        datosEnviar.append("proximo_vencimiento_vtv", $.trim($('#proximo_vencimiento_vtv').val()));
        datosEnviar.append("km_actuales", $.trim($('#km_actuales').val()));
        datosEnviar.append("numero_movil", $.trim($('#numero_movil').val()));
        datosEnviar.append("vencimiento_cedula_verde", $.trim($('#vencimiento_cedula_verde').val()));
        datosEnviar.append("vencimiento_seguro", $.trim($('#vencimiento_seguro').val()));
        datosEnviar.append("vencimiento_gnc", $.trim($('#vencimiento_gnc').val()));
        
        datosEnviar.append("id_vehiculo", $.trim($('#id_vehiculo').html()));
        datosEnviar.append("accion", accion);
        //console.log(accion);

        let cantArchivos = 0;
        //arrayFiles se llena en dropDocumentosVehiculo.html
        if(typeof arrayFiles !== 'undefined'){
          for(let i = 0; i < arrayFiles.length; i++) {
            datosEnviar.append('file'+i, arrayFiles[i]);
            //datosEnviar.append('comentario'+i, arrayFiles[i]);
            cantArchivos++;
          };
        }else{
          let arrayFiles = "";
        }
        datosEnviar.append('cantAdjuntos', cantArchivos);

        $.ajax({
          data: datosEnviar,
          url: "models/administrar_vehiculos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            console.log(data);
            if(data==""){
              tablaVehiculos.ajax.reload(null, false);

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

      /*$(document).on("click", "#btnGuardar", function(){
        let id_proveedor = $('#proveedor').val();
        let id_almacen = $('#almacen').val();
        let totalEnviar = parseFloat($('.total').text());
        //let id_orden = parseInt($(".id_ordenEdit").text());
        let id_orden= parseInt($(".id_ordenEditar").text());
        let comentarios = $("#comentarios").val();
        if (isNaN(totalEnviar)) {
          totalEnviar = 0;
        }

        if (id_almacen == 0 || id_proveedor ==0 || totalEnviar ==0) {
          swal({
            icon: 'error',
            title: 'Debe ingresar todos los datos marcados con asterisco (*)'
          });
        }else{
          const itemsOrdenArray = [];
          for(let[key, value] of itemsOrden){
            if (value.cantidad > 0) {
              itemsOrdenArray.push({
                id: key,
                valor: value.cantidad,
                precio: value.precio
              });
            }else{
              if (window.accion == "UpdateOrden") {
                  itemsOrdenArray.push({
                    id: key,
                    valor: value.cantidad,
                    precio: value.precio
                });
              }
            }
          }

          items = JSON.stringify(itemsOrdenArray);
          $.ajax({
            url: "models/administrar_ordenes.php",
            type: "POST",
            datatype:"json",
            data:  {accion: accion,items:items, total:totalEnviar, proveedor: id_proveedor, almacen: id_almacen, id_orden: id_orden, comentarios: comentarios},
            success: function(data) {
              $('#modalConciliar').modal('hide');
              tablaVehiculos.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });

            }
          })
        }

      })*/

      $("#btnNuevoVehiculo").click(function(){
          accion = "addVehiculo"
          $("#formVehiculos").trigger("reset");
          let modal=$('#modalCRUD');
          modal.find(".modal-header").css( "background-color", "#17a2b8");
          modal.find(".modal-header").css( "color", "white" );
          modal.find(".modal-title").text("Nuevo vehiculo");
          modal.modal('show');
      });

      $(document).on('click', '#btnAddAdjuntos', function(e){
        e.preventDefault();
        $rowMasAdjuntos = document.getElementById("masAdjuntos");
        $rowMasAdjuntos.classList.toggle("d-none");
        $.ajax({
            url: "dropDocumentosVehiculo.html",
            type: "POST",
            datatype:"json",    
            data:  {},    
            success: function(response) {
              $('#dropMasArchivos').html(response);
            }
          });
      });

      $(document).on("click", ".btnEditar", function(){
        fila = $(this).closest("tr");
        let id_vehiculo = fila.find('td:eq(0)').text();

        $("#formVehiculos").trigger("reset");
        let modal=$('#modalCRUD');
        modal.find(".modal-header").css( "background-color", "#22af47");
        modal.find(".modal-header").css( "color", "white" );
        modal.find(".modal-title").text("Editar Vehiculo ID "+id_vehiculo);
        modal.modal('show');

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerVechiculoUpdate');
        datosUpdate.append('id_vehiculo', id_vehiculo);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_vehiculos.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#procesando').modal('show');
          },
          success: function(datosProcesados){

            let datosInput = JSON.parse(datosProcesados);
            /*console.log(datosInput);
            console.log(datosInput[0]);*/
            let datos_vehiculo=datosInput[0];

            //$("#fecha_alta").val(datosInput.datos_vehiculo.fecha_alta);
            $("#patente").val(datos_vehiculo.patente);
            $("#marca").val(datos_vehiculo.id_marca);
            $("#modelo").val(datos_vehiculo.modelo);
            $("#anio").val(datos_vehiculo.anio);
            $("#codigo_motor").val(datos_vehiculo.codigo_motor);
            $("#codigo_chasis").val(datos_vehiculo.codigo_chasis);
            $("#nro_cedula_verde").val(datos_vehiculo.nro_cedula_verde);
            $("#fecha_adquirido").val(datos_vehiculo.fecha_adquirido);
            $("#fecha_baja").val(datos_vehiculo.fecha_baja);
            $("#tecnico_asignado").val(datos_vehiculo.id_tecnico_asignado);
            $("#comentarios").val(datos_vehiculo.comentarios);
            $("#proximo_service_general").val(datos_vehiculo.proximo_service_general);
            $("#km_adquirido").val(datos_vehiculo.km_adquirido);
            $('#proximo_vencimiento_vtv').val(datos_vehiculo.proximo_vencimiento_vtv);
            $('#km_actuales').val(datos_vehiculo.km_actuales);
            $('#numero_movil').val(datos_vehiculo.numero_movil);
            $('#vencimiento_cedula_verde').val(datos_vehiculo.vencimiento_cedula_verde);
            $('#vencimiento_seguro').val(datos_vehiculo.vencimiento_seguro);
            $('#vencimiento_gnc').val(datos_vehiculo.vencimiento_gnc);

            $('#id_vehiculo').html(id_vehiculo);
            
            accion = "updateVehiculo";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $(document).on("click", ".btnVer", function(){
        let fila = $(this);           
        let id_vehiculo = parseInt($(this).closest('tr').find('td:eq(0)').text()); 
        let accion = "traerDetalleVehiculo";

        $("#id_detalle_vehiculo").html(id_vehiculo);
        
        $.ajax({
          url: "models/administrar_vehiculos.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, id_vehiculo:id_vehiculo},
          success: function(response) {
            //console.log(response);
            let datos = JSON.parse(response);

            $(".modal-header").css( "background-color", "#ffc107");
            $(".modal-header").css( "color", "white" );

            verDetalleVehiculo=$("#verDetalleVehiculo");
            verDetalleVehiculo.modal("show");

            /*let $divAdjuntos = document.getElementById('adjuntos');
            $divAdjuntos.innerHTML="";*/
            let vehiculo=datos.datos_vehiculo;
            let documentos=datos.documentos_vehiculo;
            $detalleVehiculo = `<div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                     <label class="col-form-label font-weight-bold">N° de Movil:</label><br>${vehiculo.numero_movil}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                     <label class="col-form-label font-weight-bold">Patente:</label><br>${vehiculo.patente}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                     <label class="col-form-label font-weight-bold">Marca:</label><br>${vehiculo.marca}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                     <label class="col-form-label font-weight-bold">Modelo:</label><br>${vehiculo.modelo}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Año:</label><br>${vehiculo.anio}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Codigo motor:</label><br>${vehiculo.codigo_motor}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Codigo chasis:</label><br>${vehiculo.codigo_chasis}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Tecnico asignado:</label><br>${vehiculo.tecnico}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Fecha adquirido:</label><br>${vehiculo.fecha_adquirido_mostrar}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">KM adquirido:</label><br>${vehiculo.km_adquirido_mostrar}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">N° cedula verde:</label><br>${vehiculo.nro_cedula_verde}
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Vencimiento cedula verde:</label><br>${vehiculo.vencimiento_cedula_verde_mostrar}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">KM actuales:</label><br>${vehiculo.km_actuales_mostrar}
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Proximo service general:</label><br>${vehiculo.proximo_service_general_mostrar}
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Fecha de baja:</label><br>${vehiculo.fecha_baja_mostrar}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Vencimiento seguro:</label><br>${vehiculo.vencimiento_seguro_mostrar}
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Vencimiento GNC:</label><br>${vehiculo.vencimiento_gnc_mostrar}
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Proximo vencimiento VTV:</label><br>${vehiculo.proximo_vencimiento_vtv_mostrar}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Comentarios:</label><br>${vehiculo.comentarios}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="table-responsive tablaItems">
                    <table class="table table-hover" id="tablaAdjuntosDetalle">
                      <thead class="text-center">
                        <tr>
                          <th>#ID</th>
                          <th>Documento</th>
                          <th>Usuario</th>
                          <th>Fecha</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>`
                        if(documentos.length>0){
                          documentos.forEach((documento)=>{
                            $detalleVehiculo+= `<tr>
                                  <td>${documento.id_documento}</td>
                                  <td><a href="./views/documentos_vehiculo/adj_${id_vehiculo}_${documento.documento}" target="_blank" >${documento.documento}</a></td>
                                  <td>${documento.usuario}</td>
                                  <td>${documento.fecha_hora_alta}</td>
                                  <td><button class='btn btn-danger btnBorrarAdjunto'><i class='fa fa-trash-o'></i></button></td>
                              </tr>`;
                          })
                        }else{
                          $detalleVehiculo+= `<tr>
                              <td colspan="5" class="text-center">No se han encontrado documntos</td>
                          </tr>`;
                        }
                        $detalleVehiculo+= `</tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header">
                      <h5>Historial tareas de mantenimiento</h5>
                    </div>
                    <div class="card-body">
                      <div class="timeline-small">`;

                        datos.mantenimiento_vehiculo.forEach((tarea)=>{
                          //console.log(tarea);
                          /*$detalleVehiculo+= `<div class="media">
                              <div class="timeline-round m-r-30 timeline-line-1 bg-primary text-center"><i class="fa fa-2x fa-wrench mt-2"></i></div>
                              <div class="media-body">
                                <h6>${tarea.usuario} <span class="pull-right f-14">${tarea.fecha_hora}</span></h6>
                                <p>Costo estimado: ${tarea.costo_estimado}</p>
                                <p>Detalle: ${tarea.detalle}</p>
                                <p>Realizado: ${tarea.realizado}</p>
                              </div>
                            </div>`;*/
                            $detalleVehiculo+= `<div class="media">
                              <div class="timeline-round m-r-30 timeline-line-1 bg-primary text-center"><i class="fa fa-2x fa-wrench mt-2"></i></div>
                              <div class="media-body">
                                <h6>${tarea.usuario} <span class="pull-right f-14">${tarea.fecha_mostrar}</span></h6>
                                <div class="row">
                                  <div class="col-lg-6">
                                    <p>Costo estimado: ${tarea.costo_estimado_mostrar}</p>
                                    <p>Detalle: ${tarea.detalle}</p>
                                    <p>Comentarios (al agendar): ${tarea.comentarios}</p>
                                  </div>
                                  <div class="col-lg-6">
                                    <p>Realizado: ${tarea.realizado_mostrar}</p>`
                                    //if(tarea.realizado==1){
                                      tarea.adjuntosMantenimientoVehiculos.forEach((adjunto)=>{
                                        $detalleVehiculo+= `
                                        <p>Adjunto: <a href="./views/adjuntos_mantenimiento_vehicular/${tarea.id_tarea_mantenimiento}_${adjunto.adjunto}" target="_blank" >${adjunto.adjunto}</a></p>
                                        <p>Comentario (al completar): ${adjunto.comentario}</p>
                                        <p>Fecha y hora: ${adjunto.fecha_hora_alta}</p>
                                        `;
                                      })
                                    //}
                                    $detalleVehiculo+= `</div>
                                </div>
                              </div>
                            </div>`;
                        });

            $detalleVehiculo += `</div>
                    </div>
                  </div>
                </div>
              </div>`;

            verDetalleVehiculo.find(".modal-body").html($detalleVehiculo);
          }
        });
      });

      $(document).on("click", ".btnBorrarAdjunto", function(){
        fila = $(this).closest("tr");
        let id_documento = fila.find('td:eq(0)').text();
        let nombre_adjunto = fila.find('td:eq(1)').text()
        let accion = "borrarDocumento";

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este archivo, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            //accion = "borrarDocumento";
            $.ajax({
              url: "models/administrar_vehiculos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion: accion, id_documento: id_documento, nombre_adjunto: nombre_adjunto},    
              success: function(data) {
                fila.remove();
                swal({
                  icon: 'success',
                  title: 'Documento eliminado exitosamente'
                });
              }
            }) 
          } else {
            swal("El docmento no se eliminó!");
          }
        })
      });

      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);           
        id_vehiculo = parseInt($(this).closest('tr').find('td:eq(0)').text());       

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este vehiculo, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarVehiculo";
            $.ajax({
              url: "models/administrar_vehiculos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_vehiculo:id_vehiculo},    
              success: function() {
                tablaVehiculos.row(fila.parents('tr')).remove().draw();                  
              }
            }); 
          } else {
            swal("El registro no se eliminó!");
          }
        })
      });

      $(document).on("click", ".btnAddMantenimiento", function(e){
          accion = "addTareaMantenimiento";
          fila = $(this).closest("tr");
          let id_vehiculo = fila.find('td:eq(0)').text();
          $("#formTareaMantenimiento").trigger("reset");
          $("#id_vehiculo_mantenimiento").html(id_vehiculo);
          $(".modal-header").css( "background-color", "#17a2b8");
          $(".modal-header").css( "color", "white" );
          $(".modal-title").text("Nueva tarea de mantenimiento");
          $('#modalAddMantenimiento').modal('show');
      });

      $(document).on("click", "#btnEditarTarea", function(e){
        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar tarea de mantenimiento");
        $("#formTareaMantenimiento").trigger("reset");
        $('#modalAddMantenimiento').modal('show');

        let datosGet = new FormData();
        datosGet.append('accion', 'traerTareaMantenimientoUpdate');
        datosGet.append('id_tarea_mantenimiento', $("#id_tarea_mantenimiento").html());
        $.ajax({
          data: datosGet,
          url: './models/administrar_vehiculos.php',
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

            //$("#fecha_alta").val(datosInput.datos_vehiculo.fecha_alta);
            console.log(datosInput.detalle);
            console.log($("#detalle"));

            $("#detalle").val(datosInput[0].detalle);
            $("#costo_estimado").val(datosInput[0].costo_estimado);
            $("#fecha_mantenimiento").val(datosInput[0].fecha);
            $("#hora_mantenimiento").val(datosInput[0].hora);
            $("#comentarios_tarea_mantenimiento").val(datosInput[0].comentarios);
            
            $('#id_vehiculo').html(id_vehiculo);
            
            accion = "updateTareaMantenimiento";
          }
        });

        calendar.refetchEvents();
      })

      $('#formTareaMantenimiento').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        let datosEnviar = new FormData();
        
        datosEnviar.append("id_tarea_mantenimiento", $.trim($('#id_tarea_mantenimiento').html()))
        datosEnviar.append("detalle", $.trim($('#detalle').val()))
        datosEnviar.append("costo_estimado", $.trim($('#costo_estimado').val()));
        datosEnviar.append("fecha_mantenimiento", $.trim($('#fecha_mantenimiento').val()));
        datosEnviar.append("hora_mantenimiento", $.trim($('#hora_mantenimiento').val()));
        datosEnviar.append("comentarios", $.trim($('#comentarios_tarea_mantenimiento').val()));
        datosEnviar.append("id_vehiculo", $.trim($("#id_vehiculo_mantenimiento").html()));
        datosEnviar.append("accion", accion);
        //console.log(accion);

        $.ajax({
          data: datosEnviar,
          url: "models/administrar_vehiculos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            tablaVehiculos.ajax.reload(null, false);
            calendar.refetchEvents();
          }
        });

        $('#modalAddMantenimiento').modal('hide'); 
        
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
        
      });

      $(document).on("click", "#btnMarcarTareaMantenimientoRealizada", function(e){
          let datosUpdate = new FormData();
          datosUpdate.append('accion', 'marcarTareaMantenimientoRealizada');
          datosUpdate.append('comentarios_mantenimiento_vehicular', $("#comentarios_mantenimiento_vehicular").val());
          datosUpdate.append('id_tarea_mantenimiento', $("#id_tarea_mantenimiento").html());

          let adjunto = document.getElementById("adjunto_mantenimiento_vehicular");
          if (adjunto.files.length > 0) {
            datosUpdate.append("file", adjunto.files[0]);
          }else{
            datosUpdate.append("file", "");
          }

          $.ajax({
            data: datosUpdate,
            url: './models/administrar_vehiculos.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(datosProcesados){
              swal({
                icon: 'success',
                title: 'Tarea de mantenimiento marcada como completa correctamente'
              });
              calendar.refetchEvents();
            }
          });

          $('#modalMarcarTareaCompleta').modal('hide');

      });

      $(document).on("click", "#btnBorrarTarea", function(){
        id_tarea_mantenimiento = $("#id_tarea_mantenimiento").html();

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminada esta tarea, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarTarea";
            $.ajax({
              url: "models/administrar_vehiculos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_tarea_mantenimiento:id_tarea_mantenimiento},
              success: function() {
                calendar.refetchEvents();
              }
            }); 
          } /*else {
            swal("El registro no se eliminó!");
          }*/
        })
      });

    </script>
  </body>
</html>