<?php 
session_start();
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
$hoy = date('Y-m-d');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
}
$id_perfil=$_SESSION["rowUsers"]["id_perfil"]?>
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

      .tablaDetalleCarga-container {
        position: relative;
        overflow: auto;
        max-height: 400px; /* Ajusta esto según tus necesidades */
      }

      .tablaDetalleCarga thead th {
        position: sticky;
        top: 0;
        background: burlywood;
        z-index: 10;
      }

      .tablaDetalleCarga tbody td,
      .tablaDetalleCarga tfoot td,
      .tablaDetalleCarga thead th {
        white-space: nowrap;
        border: solid 1px black;
      }

      .tablaDetalleCarga .fixed-column {
        position: sticky;
        left: 0;
        background: burlywood;
        z-index: 20;
      }

      .tablaDetalleCarga .fixed-column-2 {
        position: sticky;
        left: 282px; /* Ajusta esto según el ancho de tus columnas */
        background: burlywood;
        z-index: 20;
      }

      .tablaDetalleCarga .fixed-column-3 {
        position: sticky;
        left: 282px; /* Ajusta esto según el ancho de tus columnas */
        background: burlywood;
        z-index: 20;
      }

      .tablaDetalleCarga .fixed-column-4 {
        position: sticky;
        left: 282px; /* Ajusta esto según el ancho de tus columnas */
        background: burlywood;
        z-index: 20;
      }

      .tablaDetalleCarga .fixed-column-header {
        position: sticky;
        top: 0;
        background: burlywood;
        z-index: 30; /* Más alto para asegurar que está sobre las celdas fijas */
      }

      .tablaDetalleCarga .destino-start {
        background-color: #d0cece;
      }

      .tablaDetalleCarga .destino-end {
        background-color: #d0cece;
      }

      .tablaDetalleCarga tfoot {
        background: burlywood;
      }

      #destinos_default {
        display: flex;
        flex-wrap: wrap;
      }

      #destinos_default .custom-control.custom-switch {
        width: 33%; /* Asegura que cada switch ocupe el 50% del ancho del contenedor */
        padding: 5px 2.5rem; /* Espaciado interno */
        box-sizing: border-box; /* Incluye padding y border en el ancho total */
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
          <div class="page-header py-3">
            <div class="row">
              <div class="col">
                <div class="page-header-left">
                  <h3>Cargas</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Cargas</li>
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
                  <h5 style="display: inline-block;vertical-align: middle;">Administrar Cargas</h5>
                  <button id="btnNuevo" type="button" class="btn btn-warning ml-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>
                  <span id="id_perfil" class="d-none"><?=$_SESSION["rowUsers"]["id_perfil"]?></span>
                </div>
                <div class="card-body py-1">
                <input type="hidden" id="id_deposito_usuario" value="<?=$_SESSION["rowUsers"]["id_deposito"]?>">
                  <table id="tableFiltros" style="" class="table table-borderless mb-3">
                    <tr>
                      <td width="8%" class="text-right p-1">Desde: </td>
                      <td width="15%" class="p-1 inputDate" style="">
                        <input type="date" id="desde" value="<?=date("Y-m-d",strtotime(date("Y-m-d")." -1 year"))?>" class="form-control form-control-sm w-auto filtraTabla">
                      </td>
                      <td width="8%" rowspan="2" style="vertical-align: middle;" class="text-right p-1">Estado:</td>
                      <td width="15%" rowspan="2" style="vertical-align: middle;" class="p-1">
                        <select id="estado" class="form-control js-example-basic-single filtraTabla" style="width: 100%;">
                          <option value="p">Pendiente</option>
                          <option value="d">Despachado</option>
                          <option value="c">Confirmado</option>
                        </select>
                      </td>
                      <td width="8%" rowspan="2" style="vertical-align: middle;" class="text-right p-1">Origen:</td>
                      <td width="19%" rowspan="2" style="vertical-align: middle;" class="p-1">
                        <select id="filtro_id_origen" class="form-control js-example-basic-single filtraTabla" style="width: 100%;"></select>
                      </td>
                      <td width="8%" rowspan="2" style="vertical-align: middle;" class="text-right p-1">Chofer:</td>
                      <td width="19%" rowspan="2" style="vertical-align: middle;" class="p-1">
                        <select id="filtro_id_chofer" class="form-control js-example-basic-single filtraTabla" style="width: 100%;"></select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-right p-1">Hasta: </td>
                      <td class="p-1 inputDate"><input type="date" id="hasta" value="<?=date("Y-m-d")?>" class="form-control form-control-sm w-auto filtraTabla"></td>
                    </tr>
                  </table>
                  <div class="dt-ext table-responsive">
                    <table class="table table-hover display" id="tablaCargas">
                      <thead class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Fecha</th>
                          <th>Origen</th>
                          <th>Chofer</th>
                          <th>Bultos</th>
                          <th>Kilos</th>
                          <!-- <th>Monto</th> -->
                          <th>Estado</th>
                          <!-- <th>Datos adicionales del chofer</th> -->
                          <th class="text-center">Acciones</th>
                          <th class="none">Datos adicionales del chofer:</th>
                          <th class="none">Despachado:</th>
                          <th class="none">Usuario:</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th style="text-align:right" colspan="4">Totales</th>
                          <th style="text-align:right">Total bultos</th>
                          <th style="text-align:right">Total kilos</th>
                          <!-- <th style="text-align:right">Total Monto</th> -->
                          <th style="text-align:right"></th>
                          <th class="text-center">Acciones</th>
                          <th class="none"></th>
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
    <div class="modal fade" id="modalCRUDadmin" tabindex="-1000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_carga" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="fecha_carga" class="col-form-label">Fecha de carga:</label>
                    <input type="date" class="form-control" id="fecha_carga" value="<?=$hoy?>" required>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="id_origen" class="col-form-label">Origen</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_origen" required></select>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="id_proveedor_default" class="col-form-label">Proveedor por defecto:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_proveedor_default"></select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="id_chofer" class="col-form-label">Chofer:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_chofer" required></select>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="form-group">
                    <label for="datos_adicionales_chofer" class="col-form-label">Datos adicionales del chofer:</label>
                    <input type="text" class="form-control" id="datos_adicionales_chofer">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label class="col-form-label">Destinos preseleccionados:</label>
                  <div class="form-group" id="destinos_default"></div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar y continuar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--Modal para CRUD admin-->
    <div class="modal fade" id="modalCRUDadminVer" tabindex="-1000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="max-width: 1200px;margin: 1rem auto;">
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
                    <label class="col-form-label font-weight-bold">Fecha Carga:</label>
                    <span id="lbl_fecha_carga"></span>
                    <input type="hidden" id="lbl_id_carga">
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Despachado:</label>
                    <span id="lbl_despachado"></span>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Confirmado:</label>
                    <span id="lbl_confirmado"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Origen:</label>
                    <span id="lbl_origen"></span>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Chofer:</label>
                    <span id="lbl_chofer"></span>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Datos adicionales:</label>
                    <span id="lbl_datos_adicionales_chofer"></span>
                  </div>
                </div>
                <!-- <div class="col-lg-2">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Proveedor:</label>
                    <span id="lbl_proveedor"></span>
                  </div>
                </div> -->
              </div>
              <div class="row">
                <div class="col-lg-12 table-container tablaDetalleCarga" id="tableProductosVer" style="overflow-x: auto; overflow-y: auto; max-height: 50vh;padding-left: 0;">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-warning btnImprimirOC" data-con-precio="1">Imprimir OC con precio</button>
              <button type="submit" class="btn btn-dark btnImprimirOC" data-con-precio="0">Imprimir OC sin precio</button>
              <button type="button" id="btnDespachar" class="btn btn-primary">Despachar</button>
              <button type="button" id="btnConfirmar" class="btn btn-primary d-none">Confirmar</button>
              <button type="button" id="btnExportar" class="btn btn-success">Exportar</button>
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
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
      var tablaCargas= $('#tablaCargas')
      var desde_aux=""
      var hasta_aux=""
      var id_origen_aux=""
      var id_chofer_aux=""
      var estado_aux=""
      $(document).ready(function(){
        getCargas()
        cargarDatosComponentes();
        $(document).on("change",".filtraTabla",getCargas)
      });

      function getCargas(){
        let desde=$("#desde").val()
        let hasta=$("#hasta").val()

        if(desde>hasta){
          alert("La fecha desde no puede ser mayor a la fecha hasta")
        }else{
          let id_origen=$("#filtro_id_origen").val()
          let id_chofer=$("#filtro_id_chofer").val()
          let estado=$("#estado").val()
          
          let hayCambioEnLosDatos=0;
          if(desde_aux!==desde || hasta_aux!==hasta || id_origen_aux!==id_origen || id_chofer_aux!==id_chofer || estado_aux!==estado){
            hayCambioEnLosDatos=1;
          }

          if(hayCambioEnLosDatos==1){
            tablaCargas.DataTable().destroy();
            tablaCargas.DataTable({
              "ajax": {
                "url" : "./models/administrar_cargas.php?accion=traerCargas&desde="+desde+"&hasta="+hasta+"&id_origen="+id_origen+"&id_chofer="+id_chofer+"&estado="+estado,
                "dataSrc": "",
              },
              "responsive": true,
              "order": [[0, "desc"]],
              "columns":[
                {"data": "id_carga"},
                {"data": "fecha_formatted"},
                {"data": "origen"},
                //{"data": "chofer"},
                {
                  render: function(data, type, full, meta) {
                    return ()=>{
                      let datos_adicionales_chofer=""
                      /*if(full.datos_adicionales_chofer!=""){
                        datos_adicionales_chofer=" ("+full.datos_adicionales_chofer+")"
                      }*/
                      return full.chofer+datos_adicionales_chofer;
                    };
                  }
                },
                //{"data": "datos_adicionales_chofer"},
                //{"data": "total_kilos"},
                {render: function(data, type, full, meta) {
                  return formatNumber2Decimal(full.total_bultos);
                }},
                {render: function(data, type, full, meta) {
                  return formatNumber2Decimal(full.total_kilos);
                }},
                //{"data": "total_monto"},
                /*{render: function(data, type, full, meta) {
                  return formatCurrency(full.total_monto);
                }},*/
                {render: function(data, type, full, meta) {
                  //console.log(full);
                  return full.estado;
                }},
                {
                  render: function(data, type, full, meta) {
                    return ()=>{
                      $buttonsGroup="<div class='text-center'><div class='btn-group'>";
                      
                      $btnEditar=`<button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button>`
                      $btnEliminar=`<button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button>`
                      
                      $btnGestionarCarga=''
                      
                      //console.log(full);
                      let despachado=0;
                      if(full.despachado=="Si"){
                        despachado=1;
                        $btnEliminar=''
                        $btnEditar=''
                        //$btnDespachar=`<button class='btn btn-primary btnDespachar'><i class='fa fa-truck'></i></button>`
                      }

                      if(id_perfil==2){
                        $btnEliminar=''
                        $btnEditar=''
                      }

                      $btnVer=`<button class='btn btn-primary btnVer' data-despachado='${despachado}'><i class='fa fa-eye'></i></button>`

                      if(full.total_bultos=="0.00" && full.total_kilos=="0.00" && full.total_monto=="0.00"){
                        $btnVer=''
                      }

                      $btnGestionarCarga=`<button class='btn btn-warning btnGestionar'><i class='fa fa-cogs'></i></button>`
                      
                      $buttonsGroupEnd=`</div></div>`

                      $btnComplete = $buttonsGroup+$btnEliminar+$btnEditar+$btnGestionarCarga+$btnVer+$buttonsGroupEnd
                      
                      return $btnComplete;
                    };
                  }
                },
                {"data": "datos_adicionales_chofer"},
                {"data": "despachado"},
                {"data": "usuario"},
              ],
              "columnDefs": [
                {
                  "targets": [4,5,6],
                  "className": 'text-right'
                },{
                  "targets": [8,7],
                  "className": 'px-0'
                },{
                  "targets": [1],
                  "className": 'text-nowrap'
                }
              ],
              "language":  idiomaEsp,
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
                  //$(api.column(6).footer()).html(formatCurrency(suma_monto));
                  //$("#total_bultos").html(suma_bultos);
                  //$("#total_kilos").html(suma_kilos);
                }
              },
              initComplete: function(settings, json){
                $('[title]').tooltip();
              }
            });

            desde_aux=desde;
            hasta_aux=hasta;
            id_origen_aux=id_origen;
            id_chofer_aux=id_chofer;
            estado_aux=estado;
          }
        }
      }

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

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesCargas');
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

            /*Identifico el select de choferes*/
            $selectChofer = document.getElementById("id_chofer");
            $selectFiltroChofer = document.getElementById("filtro_id_chofer");
            //Genero los options del select choferes
            respuestaJson.choferes.forEach((chofer)=>{
              $option1 = document.createElement("option");
              let optionText = document.createTextNode(chofer.chofer);
              $option1.appendChild(optionText);
              $option1.setAttribute("value", chofer.id_chofer);

              const $option2 = $option1.cloneNode(true); // Clona el nodo de la opción completa
              $selectFiltroChofer.appendChild($option2);
              $selectChofer.appendChild($option1);
            })
            $($selectChofer).select2()
            $($selectFiltroChofer).select2()

            let destinos_default = document.getElementById("destinos_default");
            destinos_default.innerHTML=""
            let destinos_check="";
            respuestaJson.destinos.forEach((destino)=>{
              destinos_check+=`
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input destinos_check" value="${destino.id_destino}" id="destino-${destino.id_destino}">
                  <label class="custom-control-label" for="destino-${destino.id_destino}">${destino.destino}</label>
                </div>
              `
            })
            destinos_default.innerHTML=destinos_check;
              

            /*Identifico el select de origenes*/
            $selectOrigen = document.getElementById("id_origen");
            $selectFiltroOrigen = document.getElementById("filtro_id_origen");
            //Genero los options del select origenes
            respuestaJson.origenes.forEach((origen)=>{
              $option1 = document.createElement("option");
              let optionText = document.createTextNode(origen.origen);
              $option1.appendChild(optionText);
              $option1.setAttribute("value", origen.id_origen);

              const $option2 = $option1.cloneNode(true); // Clona el nodo de la opción completa
              $selectFiltroOrigen.appendChild($option2);
              $selectOrigen.appendChild($option1);
            })
            $($selectOrigen).select2()
            $($selectFiltroOrigen).select2()

            /*Identifico el select de proveedores*/
            $selectProveedor = document.getElementById("id_proveedor_default");
            //Genero los options del select proveedores
            respuestaJson.proveedores.forEach((proveedor)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(proveedor.proveedor);
              $option.appendChild(optionText);
              $option.setAttribute("value", proveedor.id_proveedor);
              $selectProveedor.appendChild($option);
            })
            $($selectProveedor).select2()

          }
        });
      }

      $("#btnNuevo").click(function(){
        $("#formAdmin").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Alta de carga");
        let modal=$('#modalCRUDadmin')
        modal.modal('show');
        modal.on('shown.bs.modal', function (e) {
          document.getElementById("fecha_carga").focus();
        })
        accion = "addCarga";
      });

      $('#formAdmin').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
        let id_carga = $.trim($('#id_carga').html());
        let fecha_carga = $.trim($('#fecha_carga').val());
        let id_origen = $.trim($('#id_origen').val());
        let id_chofer = $.trim($('#id_chofer').val());
        let datos_adicionales_chofer = $.trim($('#datos_adicionales_chofer').val());
        let id_proveedor_default = $.trim($('#id_proveedor_default').val());
        let datosDepositos = []

        let destinos_check=$(".destinos_check:checked").each(function(){
          let id_deposito=this.value;
          datosDepositos.push(id_deposito);
        })

        $.ajax({
          url: "models/administrar_cargas.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, fecha_carga:fecha_carga, id_origen:id_origen, id_chofer:id_chofer, datos_adicionales_chofer:datos_adicionales_chofer, id_proveedor_default:id_proveedor_default, id_carga:id_carga, datosDepositos:datosDepositos},
          success: function(respuesta) {
            respuestaJson = JSON.parse(respuesta);
            if(respuestaJson.ok=="1"){
              //tablaCargas.ajax.reload(null, false);
              window.location.href="cargas_abm.php?id="+respuestaJson.id_carga;
            }else{
              swal({
                icon: 'error',
                title: 'El registro no se insertó!'
              });
            }
          }
        });

        /*$('#modalCRUDadmin').modal('hide');
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });*/
      });

      $(document).on("click", ".btnEditar", function(){
        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar Carga");
        $('#modalCRUDadmin').modal('show');
        fila = $(this).closest("tr");
        let id_carga = fila.find('td:eq(0)').text();

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'getDatosCarga');
        datosUpdate.append('id_carga', id_carga);
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
            let datosInput = JSON.parse(response);
            //console.log(datosInput);
            $("#fecha_carga").val(datosInput.fecha);
            $("#id_origen").val(datosInput.id_origen).change();
            $("#id_chofer").val(datosInput.id_chofer).change();
            $('#datos_adicionales_chofer').val(datosInput.datos_adicionales_chofer)
            $('#id_proveedor_default').val(datosInput.id_proveedor).change()
            $('#id_carga').html(id_carga)

            /*let destinos_check=$(".destinos_check").each(function(){
              let id_deposito=this.value;
              console.log(datosInput.destinos_preseleccionados);
              //total_bultos
              if (datosInput.destinos_preseleccionados.includes(id_deposito)){
                this.checked=true;
              }
            })*/

            let destinos_check = $(".destinos_check").each(function() {
            let id_deposito = this.value;

            // Buscar el destino en el array de destinos preseleccionados
            let destino = datosInput.destinos_preseleccionados.find(dest => dest.id_destino == id_deposito);

            if (destino) {
              // Marcar la casilla si el destino está en la lista
              this.checked = true;

              // Deshabilitar la casilla si total_bultos es mayor a 0
              if (parseFloat(destino.total_bultos) > 0) {
                this.disabled = true;
              }
            }
          });

            accion = "updateCarga";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $(document).on("click", ".btnVer", function() {
        $(".modal-header").css("background-color", "#007bff");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Ver Carga ID: ");
        $('#modalCRUDadminVer').modal('show');
        fila = $(this).closest("tr");
        let id_carga = fila.find('td:eq(0)').text();

        let despachado=$(this).data("despachado");

        if(despachado==1){
          $("#btnDespachar").addClass("disbaled d-none")
          $("#btnConfirmar").removeClass("disbaled d-none")
        }else{
          $("#btnDespachar").removeClass("disbaled d-none")
          $("#btnConfirmar").addClass("disbaled d-none")
        }

        if(id_perfil==2){
          $("#btnImprimirConPrecio").addClass("disbaled d-none")
          $("#btnImprimirSinPrecio").addClass("disbaled d-none")
        }else{
          $("#btnImprimirConPrecio").removeClass("disbaled d-none")
          $("#btnImprimirSinPrecio").removeClass("disbaled d-none")
        }

        let datosVer = new FormData();
        datosVer.append('accion', 'traerDatosVerDetalleCarga');
        datosVer.append('id_carga', id_carga);
        $.ajax({
          data: datosVer,
          url: './models/administrar_cargas.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(response) {
            //console.log(response);
            response=response.split("%%")
            
            let datosInput = JSON.parse(response[1]);
            console.log(datosInput);
            $(".modal-title").html("Ver Carga ID: " + datosInput.id_carga);
            $("#lbl_fecha_carga").html(datosInput.fecha_formatted);
            let despachado;
            if(datosInput.despacho==1){
              despachado="Si"
            }else{
              despachado="No"
            }
            if(datosInput.fecha_hora_despacho!=""){
              despachado+=" ("+datosInput.fecha_hora_despacho+")"
            }
            let confirmado;
            if(datosInput.confirmada==1){
              confirmado="Si"
            }else{
              confirmado="No"
            }
            if(datosInput.fecha_hora_confirmacion!=""){
              confirmado+=" ("+datosInput.fecha_hora_confirmacion+")"
            }
            $("#lbl_despachado").html(despachado);
            $("#lbl_confirmado").html(confirmado);
            $("#lbl_id_carga").val(datosInput.id_carga);
            $("#lbl_origen").html(datosInput.origen);
            $("#lbl_proveedor").html(datosInput.proveedor);
            $("#lbl_chofer").html(datosInput.chofer);
            $('#lbl_datos_adicionales_chofer').html(datosInput.datos_adicionales_chofer)
            //$('#lbl_proveedor_default').html(datosInput.proveedor)
            $('#id_carga').html(id_carga)

            accion = "verCarga";

            let tableProductosVer=$("#tableProductosVer")
            tableProductosVer.html(response[0]);

            let ancho1=parseFloat(tableProductosVer.find("th.fixed-column").css("width"))
            let ancho2=parseFloat(tableProductosVer.find("th.fixed-column-2").css("width"))
            let ancho3=parseFloat(tableProductosVer.find("th.fixed-column-3").css("width"))

            tableProductosVer.find(".fixed-column-2").css("left",ancho1)
            tableProductosVer.find(".fixed-column-3").css("left",ancho1+ancho2)
            tableProductosVer.find(".fixed-column-4").css("left",ancho1+ancho2+ancho3)
            //alert(ancho1)
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
            alert('Error al obtener los datos de la carga. Por favor, inténtelo de nuevo.');
          }
        });

        $('#modalCRUD').modal('show');
      });
  
      $(document).on("click", "#btnDespachar", function(event){
        event.preventDefault();
        let id_carga = $("#lbl_id_carga").val();
        //console.log("id_carga: " + id_carga);
        swal({
          title: "Estas seguro?",
          text: "Una vez despachada esta carga, no podras modificarla",
          icon: "info",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "despacharCarga";
            $.ajax({
              url: "models/administrar_cargas.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_carga:id_carga},
              success: function() {
                //tablaCargas.row(fila.parents('tr')).remove().draw();
                //tablaCargas.ajax.reload(null, false);
                swal({
                  icon: 'success',
                  title: 'Carga despachada correctamente'
                }).then(() => {
                  tablaCargas.ajax.reload(null, false);
                  $('#modalCRUDadminVer').modal('hide');
                  //$("#btnDespachar").addClass("invisible");
                });
                // setTimeout(() => {
                //   location.reload();
                // }, 3000);
              }
            });
          } else {
            swal("La carga no se despachó!");
          }
        })
      });

      $(document).on("click", "#btnConfirmar", function(event){
        event.preventDefault();
        let id_carga = $("#lbl_id_carga").val();
        //console.log("id_carga: " + id_carga);
        swal({
          title: "Estas seguro?",
          text: "Una vez confirmada esta carga, no se podrá modificar",
          icon: "info",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "confirmarCarga";
            $.ajax({
              url: "models/administrar_cargas.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_carga:id_carga},
              success: function() {
                swal({
                  icon: 'success',
                  title: 'Carga confirmada correctamente'
                }).then(() => {
                  $('#modalCRUDadminVer').modal('hide');
                  getCargas()
                  //tablaCargas.ajax.reload(null, false);
                });
                // setTimeout(() => {
                //   location.reload();
                // }, 3000);
              }
            });
          } else {
            swal("La carga no se confirmó!");
          }
        })
      });

      $(document).on("click", ".btnGestionar", function(){
        fila = $(this).closest("tr");
        let id_carga = fila.find('td:eq(0)').text();
        window.location.href="cargas_abm.php?id="+id_carga
      });

      $(document).on('click', '#btnExportar', function() {
        let id_carga = $('#lbl_id_carga').val();
        console.log('id_carga: '+id_carga);
        exportarExcel(id_carga);
      });

      function exportarExcel(id_carga) {
        let datosExportar = new FormData();
        datosExportar.append('accion', 'exportar_excel');
        datosExportar.append('id_carga', id_carga);
        console.log(datosExportar);

        $.ajax({
          url: './models/administrar_cargas.php',
          method: 'POST',
          data: datosExportar,
          cache: false,
          contentType: false,
          processData: false,
          xhrFields: {
              responseType: 'blob'
          },
          success: function(response) {
            // Asegurarse que la respuesta es un Blob
            if (response instanceof Blob) {
              let a = document.createElement('a');
              let url = window.URL.createObjectURL(response);

              // Obtener la fecha y hora actual
              let now = new Date();
              let year = now.getFullYear();
              let month = String(now.getMonth() + 1).padStart(2, '0'); // Meses desde 0 a 11
              let day = String(now.getDate()).padStart(2, '0');
              let hours = String(now.getHours()).padStart(2, '0');
              let minutes = String(now.getMinutes()).padStart(2, '0');
              let seconds = String(now.getSeconds()).padStart(2, '0');

              // Formato deseado: "YYYY-MM-DD_HH-MM-SS"
              let formattedDate = `${year}-${month}-${day}_${hours}-${minutes}-${seconds}`;
              
              // Nombre del archivo con fecha y hora
              let filename = `Carga ID ${id_carga}_${formattedDate}.xlsx`;
              
              a.href = url;
              //a.download = 'Carga ID '+id_carga+'.xlsx';
              a.download = filename;
              document.body.append(a);
              a.click();
              //a.remove();
              window.URL.revokeObjectURL(url);
            } else {
              console.error('La respuesta no es un Blob:', response);
              alert('Error al exportar los datos. La respuesta no es un archivo válido.');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            // Manejo de errores detallado
            let errorMessage = 'Error al exportar los datos. Por favor, inténtelo de nuevo.';
            if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                errorMessage = jqXHR.responseJSON.error;
            }
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
            alert(errorMessage);
          }
        });
      }

      //Imprimir con o sin precio
      $(document).on("click", ".btnImprimirOC", function(event){
        event.preventDefault();
        let id_carga =  $('#id_carga').html();
        
        let cp = this.dataset.conPrecio;

        datosEnviar = JSON.stringify(id_carga);

        let destinos=[]
        $(".check_destino:checked").each(function(){
          //console.log(this);
          destinos.push(this.value)
        })
        str_destinos=destinos.join(',');

        win = window.open("./imprimirCarga.php?id_carga="+id_carga+"&cp="+cp+"&destinos="+str_destinos);
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
      })

      //Borrar
      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);
        id_carga = parseInt($(this).closest('tr').find('td:eq(0)').text());       
        swal({
          title: "Estas seguro?",
          text: "Se eliminaran todos los productos y destinos asignados. Una vez eliminada esta carga, no volveras a verla",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarCarga";
            $.ajax({
              url: "models/administrar_cargas.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_carga:id_carga},
              success: function() {
                //tablaCargas.row(fila.parents('tr')).remove().draw();
                tablaCargas.ajax.reload(null, false);
                swal({
                  icon: 'success',
                  title: 'Carga eliminada correctamente'
                })
              }
            }); 
          } else {
            swal("La carga no se eliminó!");
          }
        })
      });
    </script>
  </body>
</html>