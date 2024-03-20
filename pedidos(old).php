<?php
session_start();
include_once('./../conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hoy=date("Y/m/d");
$ahora=date("Y-m-d\TH:i");
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
    <title>MYLA - Pedidos</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/datatable-extension.css">
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
        max-height: 76vh;
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
                  <h3>Pedidos</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Pedidos</li>
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
                  <h5>Grilla de pedidos</h5>
                  <button id="btnNuevoPedido" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nuevo pedido</button>
                </div><?php
                //var_dump($_SESSION["rowUsers"]["id_empresa"]);?>
                <div class="card-body">
                  <div class="table-responsive" id="contTablaListas" >
                    <table class="table table-hover" id="tablaPedidos">
                      <thead class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Cliente</th>
                          <th>Direccion</th>
                          <th>Contacto</th>
                          <!-- <th>Descripcion</th> -->
                          <th>Prioridad</th>
                          <th>Fecha</th>
                          <th>Numero</th>
                          <th>Caducidad</th>
                          <th>Tipo</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tfoot class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Cliente</th>
                          <th>Direccion</th>
                          <th>Contacto</th>
                          <!-- <th>Descripcion</th> -->
                          <th>Prioridad</th>
                          <th>Fecha</th>
                          <th>Numero</th>
                          <th>Caducidad</th>
                          <th>Tipo</th>
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

    <!--Modal para CRUD-->
    <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_pedido" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formPedido" >
            <div class="modal-body">
              <!--Accordion wrapper-->
              <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                <!-- Accordion card datos principales-->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                    <div class="card-header" role="tab" id="headingOne1">
                      <h6 class="mb-0">Datos principales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseOne1" class="collapse show" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="cliente" class="col-form-label">*Cliente:</label>
                            <select name="cliente" class="form-control" id="cliente">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="ubicacion" class="col-form-label">*Ubicacion</label>
                            <select name="ubicacion" class="form-control" id="ubicacion" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="id_contacto_cliente" class="col-form-label">*Contacto:</label>
                            <select name="id_contacto_cliente" class="form-control" id="id_contacto_cliente" required>
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="numero" class="col-form-label">*Número ticket del cliente:</label>
                            <input type="text" name="numero" class="form-control" id="numero" value="" required>
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
                      <h6 class="mb-0">Datos de la avería <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseTwo2" class="collapse" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Asunto:</label>
                            <input type="text" class="form-control" id="asunto" value="" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Descripcion:</label>
                            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <button id="btnAddAdjuntos" class="btn btn-secondary"> Agregar adjuntos</button>
                        <div class="col-lg-12 d-none" id="masAdjuntos">
                          <div id="dropMasArchivos"></div>
                        </div>
                      </div>
                      <div class="row border p-2 mt-2 ml-2 mr-2" id="adjuntos"></div>
                    </div>
                  </div>
                </div>
                <!-- Accordion card -->
                <!-- Accordion card -->
                <div class="card border-secondary">
                  <!-- Card header -->
                  <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseThree4" aria-expanded="false" aria-controls="collapseThree4">
                    <div class="card-header" role="tab" id="headingThree4">
                      <h6 class="mb-0">Datos del pedido <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                    </div>
                  </a>
                  <!-- Card body -->
                  <div id="collapseThree4" class="collapse" role="tabpanel">
                    <div class="card-body border-secondary">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Fecha:</label>
                            <input type="datetime-local" class="form-control" id="fecha" value="<?=$ahora?>" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Prioridad:</label>
                            <select class="form-control" id="prioridad">
                              <option value="">Seleccione</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Caducidad:</label>
                            <input type="datetime-local" class="form-control" id="caducidad" value="<?=$ahora?>" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Tipo:</label>
                            <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" name="tipo" id="tipo_cotizar" value="1" required>
                              <label class="custom-control-label" for="tipo_cotizar">Cotizar</label>
                            </div>
                            <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input" name="tipo" id="tipo_ot_directa" value="2" required> 
                              <label class="custom-control-label" for="tipo_ot_directa">OT directa</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="" class="col-form-label">*Comentarios:</label>
                            <textarea name="comentarios" id="comentarios" class="form-control"></textarea>
                          </div>
                        </div>
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

    <?php
    include_once("assets/presupuestos/modal_crear_editar.php")?>

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
    <script src="assets/tareas/form_crear_editar.js"></script>
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

        /*idiomaEspItems= Object.assign({},idiomaEsp);
        idiomaEspItems.zeroRecords="No se encontraron registros que coincidan con la búsqueda<br><button type='button' class='btn btn-success' id='btnAgregarNuevoItem'>Agregar</button>";*/
        
        //debugger;
        tablaPedidos.DataTable({
          "ajax": {
              "url" : "./models/administrar_pedidos.php?accion=traerPedidos&id_estado=1",
              "dataSrc": "",
            },
          "stateSave": true,
          "columns":[
            {"data": "id_pedido"},
            {"data": "cliente"},
            {"data": "direccion"},
            {"data": "contacto_cliente"},
            //{"data": "descripcion"},
            //{"data": "prioridad"},
            {render: function(data, type, row, meta) {
              return ()=>{
                let class_prior="prior_"
                if(row.id_prioridad==1) class_prior+="pendiente"
                if(row.id_prioridad==2) class_prior+="normal"
                if(row.id_prioridad==3) class_prior+="media"
                if(row.id_prioridad==4) class_prior+="alta"
                if(row.id_prioridad==5) class_prior+="critica"
                return `<span class="badge ${class_prior}">${row.prioridad}</span>`;
              };
            }},
            {"data": "fecha_mostrar"},
            {"data": "nro_ticket"},
            {"data": "fecha_caducidad_mostrar"},
            {"data": "tipo"},
            //{"data": "vehiculo"},
            // {"data": "costo_movilidad_estimado_mostrar"},
            {render: function(data, type, row, meta) {
              return ()=>{
                //si la orden esta finalizada no se puede editar
                
                let btnEditar="<button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button>";
                //let btnVer="<button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button>";
                let btnVer="";
                let btnBorrar="<button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button>";
                let btnCotizar="<button class='btn btn-primary btnCotizar'><i class='fa fa-usd'></i></button>";

                if(row.id_estado_pedido>1){
                  btnBorrar=btnEditar="";
                }
                if(row.id_estado_pedido>=2){
                  btnCotizar="";
                }
                let buttons=btnCotizar+btnEditar+btnVer+btnBorrar;
                return `
                <div class='text-center'>
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
              title:     "Pedidos",
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
              title:     "Pedidos",
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
                //column.data().unique().sort().each(function(d,j){
                column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ){
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

        $('#modalCRUD').on('hidden.bs.modal', function (e) {
          document.getElementById('dropMasArchivos').innerHTML="";
          document.getElementById('masAdjuntos').classList.toggle("d-none");
        });

        $('#modalNuevaTarea').on('hidden.bs.modal', function (e) {
          $("#modalPresupuesto").modal("show");
        });

      });

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesPedidos');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_pedidos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(respuesta){
            //console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            //console.log(respuestaJson);

            //Identifico el select de prioridades
            $selectPrioridades= document.getElementById("prioridad");
            //Genero los options del select de prioridades
            respuestaJson.prioridades.forEach((prioridad)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(prioridad.prioridad_con_horas);
                $option.appendChild(optionText);
                $option.setAttribute("value", prioridad.id_prioridad);
                $option.setAttribute("data-horas", prioridad.horas_caducidad);
                $selectPrioridades.appendChild($option);
            });

            //Identifico el select de clientes y genero los options del select de clientes
            $selectClientes= document.getElementById("cliente");
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

      $(document).on('click', '#btnAddAdjuntos', function(e){
        e.preventDefault();
        $rowMasAdjuntos = document.getElementById("masAdjuntos");
        $rowMasAdjuntos.classList.toggle("d-none");
        $.ajax({
            url: "dropAdjuntosMantenimientoPreventivo.html",
            type: "POST",
            datatype:"json",
            data:{},
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
            $option.setAttribute("value", "");
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

      $(document).on("change", "#prioridad", function(){
        //console.log(this)
        //let horas_caducidad=this.dataset.horas
        let horas_caducidad = $('option:selected',this).data("horas");
        let caducidad
        if(horas_caducidad!=null){
          //console.log(horas_caducidad);
          let fecha=$("#fecha").val()
          caducidad=agregarHorasAFecha(fecha,horas_caducidad)
          //console.log(caducidad);
        }else{
          caducidad=""
        }
        $("#caducidad").val(caducidad)
      });

      function agregarHorasAFecha(fecha,horas){
        fecha = new Date(fecha);
        fecha.setHours(fecha.getHours()+horas);
        
        let anio=fecha.getFullYear();
        let mes=(parseInt(fecha.getMonth())+1).toString().padStart(2,0);
        let dia=fecha.getDate().toString().padStart(2,0);
        let hora=fecha.getHours().toString().padStart(2,0);
        let minutos=fecha.getMinutes().toString().padStart(2,0);
        let nueva_fecha=anio+"-"+mes+"-"+dia+"T"+hora+":"+minutos
        
        return nueva_fecha
      }

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
            $option.setAttribute("value", "");
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

      $('#formPedido').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        
        let datosEnviar = new FormData();
        
        datosEnviar.append("cliente", $.trim($('#cliente').val()));
        datosEnviar.append("ubicacion", $.trim($('#ubicacion').val()));
        datosEnviar.append("id_contacto_cliente", $.trim($('#id_contacto_cliente').val()));
        datosEnviar.append("numero", $.trim($('#numero').val()));
        datosEnviar.append("asunto", $.trim($('#asunto').val()));
        datosEnviar.append("descripcion", $.trim($('#descripcion').val()));
        datosEnviar.append("fecha", $.trim($('#fecha').val()));
        datosEnviar.append("prioridad", $.trim($('#prioridad').val()));
        datosEnviar.append("caducidad", $.trim($('#caducidad').val()));
        datosEnviar.append("tipo", $.trim($('input[name="tipo"]:checked').val()));
        datosEnviar.append("comentarios", $.trim($('#comentarios').val()));
        
        datosEnviar.append("id_pedido", $.trim($('#id_pedido').html()));
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
          url: "models/administrar_pedidos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            if(data==""){
              tablaPedidos.DataTable().ajax.reload(null, false);
              //calendar.refetchEvents();

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

      $("#btnNuevoPedido").click(function(){
        accion = "addPedido"
        $("#formPedido").trigger("reset");
        let modal=$('#modalCRUD');
        modal.find(".modal-header").css( "background-color", "#17a2b8");
        modal.find(".modal-header").css( "color", "white" );
        modal.find(".modal-title").text("Nuevo pedido");
        modal.modal('show');
        
        $selectContactosCliente= document.getElementById("id_contacto_cliente");
        $selectContactosCliente.innerHTML = "";
        $option = document.createElement("option");
        let optionTextB = document.createTextNode("Sin resultados");
        $option.appendChild(optionTextB);
        $selectContactosCliente.appendChild($option);

      });

      $(document).on("click", ".btnEditar", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();

        let modal=$('#modalCRUD');
        modal.find(".modal-header").css( "background-color", "#22af47");
        modal.find(".modal-header").css( "color", "white" );
        modal.find(".modal-title").text("Editar pedido ID "+id_pedido);
        $("#formPedido").trigger("reset");
        modal.modal('show');

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerPedidos');
        datosUpdate.append('id_pedido', id_pedido);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_pedidos.php',
          //url: './models/administrar_pedidos.php?accion=traerPedidos&id_pedido='+id_pedido,
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(datosProcesados){
            //console.log(datosProcesados);
            let datosInput = JSON.parse(datosProcesados);
            datosInput=datosInput[0]
            //console.log(datosInput);
            //let dmp=datosInput.datos_mantenimiento_preventivo;
            //console.log(dmp);

            $("#cliente").val(datosInput.id_cliente);
            getUbicacionesCliente(datosInput.id_cliente,datosInput.id_direccion_cliente);
            getContactosUbicacion(datosInput.id_direccion_cliente,datosInput.id_contacto_cliente);

            $("#asunto").val(datosInput.asunto);
            $("#descripcion").val(datosInput.descripcion);
            $("#prioridad").val(datosInput.id_prioridad);
            
            $("input[type='radio'][value='"+datosInput.id_tipo_pedido+"']").attr("checked",true);
            $("#fecha").val(datosInput.fecha);
            $("#numero").val(datosInput.nro_ticket);
            $("#caducidad").val(datosInput.fecha_caducidad);
            $("#comentarios").val(datosInput.comentarios)
            
            $('#id_pedido').html(id_pedido);

            $.ajax({
              url: './models/administrar_pedidos.php',
              type: "POST",
              datatype:"json",
              data:  {accion: "traerAdjuntosPedidos", id_pedido: id_pedido},
              success: function(datosProcesados){
                let datosInput = JSON.parse(datosProcesados);
                if(datosInput.length > 0){
                  let url_adjuntos = "./views/adjuntosPedidos/";
                  let $fragment = document.createDocumentFragment();
                  let $divAdjuntos = document.getElementById('adjuntos');

                  $divAdjuntos.innerHTML="";

                  datosInput.forEach((adjuntos)=>{
                    let extension = adjuntos.archivo.split(".");
                    if(extension[1] == 'jpg' || extension[1] == 'jpeg' || extension[1] == 'png' || extension[1] == 'gif'){
                      $divImagen = `
                        <div class="col-lg-4 mb-2">
                          <div class="col-lg-12">
                            <div class="text-center">
                              <img src="./views/adjuntosPedidos/adj_${id_pedido}_${adjuntos.archivo}" class="img-thumbnail w-50"></br>
                              ${adjuntos.archivo}
                            </div>
                          </div> 
                          <div class="col-lg-12 text-center mt-1">
                            <a class='btn btn-outline-danger btnBorrarAdjunto text-danger' data-id="${adjuntos.id_adjunto}" data-name="${adjuntos.archivo}"><i class='fa fa-trash-o'></i></a>
                          </div>
                        </div>
                      `;
                    }else{
                      $divImagen = `
                        <div class="col-lg-4 mb-2">
                          <div class="col-lg-12">
                            <div class="text-center">
                              <img src="./assets/images/imgAdjuntos.jpg" class="img-thumbnail w-50" id=""></br>
                              ${adjuntos.archivo}
                            </div>
                          </div> 
                          <div class="col-lg-12 text-center mt-1">
                            <a class='btn btn-outline-danger btnBorrarAdjunto text-danger' data-id="${adjuntos.id_adjunto}" data-name="${adjuntos.archivo}"><i class='fa fa-trash-o'></i></a>
                          </div>
                        </div>
                      `;
                    }
                    $divAdjuntos.innerHTML+=$divImagen
                  });
                }else{
                  $('#imgUpdate').attr("src", "");
                  $('#imgUpdate').addClass("d-none");
                  $('.btnBorrarFoto').addClass("d-none");
                }
              }
            })
            
            accion = "updatePedido";
          }
        });

      });

      $(document).on("click", ".btnCotizar", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();
        console.log(id_pedido);
        crearPresupuesto(id_pedido)

      });

      $(document).on("click", ".btnVer", function(){
        fila = $(this).closest("tr");
        let id_pedido = fila.find('td:eq(0)').text();

        $(".modal-title").text("Ver tarea de mantenimiento preventivo N° "+id_pedido);
        $('#modalVerDetalle').modal('show');

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerDetalleMantenimientoPreventivo');
        datosUpdate.append('id_pedido', id_pedido);
        $.ajax({
          url: './models/administrar_pedidos.php',
          //url: './models/administrar_pedidos.php?accion=traerPedidos&id_pedido='+id_pedido,
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
                      <td><a href="./views/mantenimiento_preventivo/adj_${id_pedido}_${adjunto.archivo}" target="_blank" >${adjunto.archivo}</a></td>
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

            $('#id_pedido').html(id_pedido);
            
            accion = "updateMantenimientoPreventivo";
          }
        });

      });

      $(document).on("click", ".btnBorrarAdjunto", function(){
        let elem=$(this).parent().parent();
        let id_adjunto = this.dataset.id;
        let nombre_adjunto = this.dataset.name
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
              url: "models/administrar_pedidos.php",
              type: "POST",
              datatype:"json",
              data:  {accion: accion, id_adjunto: id_adjunto, nombre_adjunto: nombre_adjunto},
              success: function(data) {
                if(!data){
                  elem.remove();
                  swal({
                    icon: 'success',
                    title: 'Archivo eliminado exitosamente'
                  });
                }
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
          text: "Una vez eliminada este pedido no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarPedido";
            $.ajax({
              url: "models/administrar_pedidos.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_pedido:id_pedido},
              success: function() {
                //tablaPedidos.row(fila.parents('tr')).remove().draw();
                swal({
                  icon: 'success',
                  title: 'Pedido eliminado correctamente'
                });
                tablaPedidos.DataTable().ajax.reload(null, false);
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