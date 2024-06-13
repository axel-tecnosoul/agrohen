<?php 
session_start();
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
$hoy = date('Y-m-d');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
}?>
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
                <div class="card-header">
                  <h5>Administrar Cargas</h5>
                    <button id="btnNuevo" type="button" class="btn btn-warning mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>
                </div>
                <div class="card-body">
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
                          <th>Monto</th>
                          <!-- <th>Datos adicionales del chofer</th> -->
                          <th class="text-center">Acciones</th>
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
                          <th style="text-align:right">Total Monto</th>
                          <th class="text-center">Acciones</th>
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
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_carga" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="fecha_carga" class="col-form-label">Fecha de carga:</label>
                    <input type="date" class="form-control" id="fecha_carga" value="<?=$hoy?>" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="id_origen" class="col-form-label">Origen</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_origen" required></select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="id_chofer" class="col-form-label">Chofer:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_chofer" required></select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="datos_adicionales_chofer" class="col-form-label">Datos adicionales del chofer:</label>
                    <input type="text" class="form-control" id="datos_adicionales_chofer">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="id_proveedor_default" class="col-form-label">Proveedor por defecto:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_proveedor_default"></select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    
                  </div>
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
                    <label class="col-form-label font-weight-bold">Fecha Carga:</label>
                    <span id="lbl_fecha_carga"></span>
                  </div>
                </div>
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
                <div class="col-lg-3">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Datos adicionales:</label>
                    <span id="lbl_datos_adicionales_chofer"></span>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label class="col-form-label font-weight-bold">Proveedor:</label>
                    <span id="lbl_proveedor"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <table id="tableProductosVer" class="table table-striped">
                    <thead>
                      <th style="width: 30%">Producto</th>
                      <th style="width: 40%">Cantidad de bultos</th>
                      <th style="width: 15%">Kg Total</th>
                      <th style="width: 15%">Monto Total</th>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <th style="text-align:right">Totales</th>
                      <th id="total_bultos_ver">0</th>
                      <th class="text-right" id="total_kilos_ver">0</th>
                      <th class="text-right" id="total_monto_ver">0</th>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="btnImprimirConPrecio" class="btn btn-success">Imprimir con precio</button>
              <button type="submit" id="btnImprimirSinPrecio" class="btn btn-dark">Imprimir sin precio</button>
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
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
      $(document).ready(function(){
        tablaCargas= $('#tablaCargas').DataTable({
          "ajax": {
            "url" : "./models/administrar_cargas.php?accion=traerCargas",
            "dataSrc": "",
          },
          "responsive": true,
          "columns":[
            {"data": "id_carga"},
            {"data": "fecha_formatted"},
            {"data": "origen"},
            //{"data": "chofer"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  let datos_adicionales_chofer=""
                  if(full.datos_adicionales_chofer!=""){
                    datos_adicionales_chofer=" ("+full.datos_adicionales_chofer+")"
                  }
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
            {render: function(data, type, full, meta) {
              return formatCurrency(full.total_monto);
            }},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  $buttonsGroup="<div class='text-center'><div class='btn-group'>";
                  $btnEliminar=''
                  $btnEditar=''
                  $btnGestionarCarga=''
                  //$btnDespachar=''
                  $btnVer=''
                  if(full.despachado=="No"){
                    $btnEditar=`<button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button>`
                    $btnEliminar=`<button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button>`
                    //$btnDespachar=`<button class='btn btn-primary btnDespachar'><i class='fa fa-truck'></i></button>`
                    }
                  $btnVer=`<button class='btn btn-primary btnVer'><i class='fa fa-eye'></i></button>`

                  $btnGestionarCarga=`<button class='btn btn-warning btnGestionar'><i class='fa fa-cogs'></i></button>`
                  
                  $buttonsGroupEnd=`</div></div>`

                  $btnComplete = $buttonsGroup+$btnEliminar+$btnEditar+$btnGestionarCarga+$btnVer+$buttonsGroupEnd
                  
                  return $btnComplete;
                };
              }
            },
            {"data": "despachado"},
            {"data": "usuario"},
          ],
          "columnDefs": [
            {
              "targets": [4,5],
              "className": 'text-right'
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
              $(api.column(6).footer()).html(formatCurrency(suma_monto));
              //$("#total_bultos").html(suma_bultos);
              //$("#total_kilos").html(suma_kilos);
            }
          },
          initComplete: function(settings, json){
            $('[title]').tooltip();
          }
        });
        
        cargarDatosComponentes();
      
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
            //Genero los options del select choferes
            respuestaJson.choferes.forEach((chofer)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(chofer.chofer);
              $option.appendChild(optionText);
              $option.setAttribute("value", chofer.id_chofer);
              $selectChofer.appendChild($option);
            })
            $($selectChofer).select2()

            /*Identifico el select de origenes*/
            $selectOrigen = document.getElementById("id_origen");
            //Genero los options del select origenes
            respuestaJson.origenes.forEach((origen)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(origen.origen);
              $option.appendChild(optionText);
              $option.setAttribute("value", origen.id_origen);
              $selectOrigen.appendChild($option);
            })
            $($selectOrigen).select2()

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

        $.ajax({
          url: "models/administrar_cargas.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, fecha_carga:fecha_carga, id_origen:id_origen, id_chofer:id_chofer, datos_adicionales_chofer:datos_adicionales_chofer, id_proveedor_default:id_proveedor_default, id_carga:id_carga},
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
            console.log(datosInput);
            $("#fecha_carga").val(datosInput.fecha);
            $("#id_origen").val(datosInput.id_origen).change();
            $("#id_chofer").val(datosInput.id_chofer).change();
            $('#datos_adicionales_chofer').val(datosInput.datos_adicionales_chofer)
            $('#id_proveedor_default').val(datosInput.id_proveedor).change()
            $('#id_carga').html(id_carga)

            accion = "updateCarga";
          }
        });

        $('#modalCRUD').modal('show');
      });

      // $(document).on("click", ".btnVer", function(){
      //   $(".modal-header").css( "background-color", "#007bff");
      //   $(".modal-header").css( "color", "white" );
      //   $(".modal-title").text("Ver Carga");
      //   $('#modalCRUDadmin').modal('show');
      //   fila = $(this).closest("tr");
      //   let id_carga = fila.find('td:eq(0)').text();

      //   let datosVer = new FormData();
      //   datosVer.append('accion', 'getDatosCarga');
      //   datosVer.append('id_carga', id_carga);
      //   $.ajax({
      //     data: datosVer,
      //     url: './models/administrar_cargas.php',
      //     method: "post",
      //     cache: false,
      //     contentType: false,
      //     processData: false,
      //     beforeSed: function(){
      //       //$('#procesando').modal('show');
      //     },
      //     success: function(response){
      //       let datosInput = JSON.parse(response);
      //       console.log(datosInput);
      //       $("#fecha_carga").html(datosInput.fecha);
      //       $("#id_origen").html(datosInput.id_origen);
      //       $("#id_chofer").html(datosInput.id_chofer);
      //       $('#datos_adicionales_chofer').html(datosInput.datos_adicionales_chofer)
      //       $('#id_proveedor_default').html(datosInput.id_proveedor)
      //       $('#id_carga').html(id_carga)

      //       accion = "verCarga";
      //     }
      //   });

      //   $('#modalCRUD').modal('show');
      // });

      // $(document).on("click", ".btnVer", function() {
      //   $(".modal-header").css("background-color", "#007bff");
      //   $(".modal-header").css("color", "white");
      //   $(".modal-title").text("Ver Carga ID: ");
      //   $('#modalCRUDadminVer').modal('show');
      //   fila = $(this).closest("tr");
      //   let id_carga = fila.find('td:eq(0)').text();

      //   let datosVer = new FormData();
      //   datosVer.append('accion', 'traerDatosVerDetalleCarga');
      //   datosVer.append('id_carga', id_carga);
      //   $.ajax({
      //       data: datosVer,
      //       url: './models/administrar_cargas.php',
      //       method: "post",
      //       cache: false,
      //       contentType: false,
      //       processData: false,
      //       success: function(response) {
      //         try {
      //           let datosInput = JSON.parse(response);
      //           console.log(datosInput);
      //           if (!datosInput || !datosInput.productos) {
      //               console.error('Respuesta JSON no válida:', response);
      //               alert('Error al obtener los datos de la carga. Por favor, inténtelo de nuevo.');
      //               return;
      //           }
      //           $(".modal-title").html("Ver Carga ID: " + datosInput.id_carga);
      //           $("#lbl_fecha_carga").html(datosInput.fecha_formatted);
      //           $("#lbl_id_carga").html(datosInput.id_carga);
      //           $("#lbl_origen").html(datosInput.origen);
      //           $("#lbl_proveedor").html(datosInput.proveedor);
      //           $("#lbl_chofer").html(datosInput.chofer);
      //           $('#lbl_datos_adicionales_chofer').html(datosInput.datos_adicionales_chofer)
      //           $('#lbl_proveedor_default').html(datosInput.proveedor)
      //           $('#id_carga').html(id_carga)

      //           accion = "verCarga";

      //           // Identifico la tabla de productos
      //           var tbody = document.querySelector('#tableProductosVer tbody');
      //           tbody.innerHTML = "";
      //           let cantidad_bulto_total = 0
      //           let total_kilos = 0
      //           let total_monto = 0
      //           datosInput.productos.forEach((producto) => {
      //             // Sumar cantidad de bultos
      //             cantidad_bulto_total += parseFloat(producto.total_bultos);
      //             //console.log("cantidad_bulto_total = " + cantidad_bulto_total);

      //             // Sumar kilos
      //             total_kilos += parseFloat(producto.total_kilos);
      //             //console.log("total_kilos = " + total_kilos);

      //             // Sumar monto
      //             total_monto += parseFloat(producto.total_monto)
      //             //console.log("total_monto = " + total_monto)

      //             let contenidoFila = `
      //               <td class="align-middle">
      //                   <input type='text' readonly tabindex="-1"class="form-control producto" value='${producto.producto}'>
      //               </td>
      //               <td class="align-middle">
      //                   <div class="input-group">
      //                       <input type="text" readonly tabindex="-1" class="form-control cantidad_bultos" value="${producto.total_bultos}" placeholder="Deje en blanco si no desea cargar este producto al producto">
      //                   </div>
      //               </td>
      //               <td class="align-middle">
      //                 <input type="text" readonly tabindex="-1" class="form-control text-right subtotal_kilos_formatted" value='${producto.total_kilos}'>
      //               </td>
      //               <td class="align-middle">
      //                 <input type="text" readonly tabindex="-1" class="form-control text-right subtotal_monto_formatted" value='${producto.total_monto}'>
      //             </td>`;

      //             // Crear una nueva fila
      //             var newRow = document.createElement('tr');
      //             // Insertar el contenido HTML en la nueva fila
      //             newRow.innerHTML = contenidoFila;
      //             // Agregar la fila al tbody
      //             tbody.appendChild(newRow);
      //           });

      //           $('#total_bultos_ver').text(cantidad_bulto_total.toFixed(2));
      //           $('#total_kilos_ver').text(total_kilos.toFixed(2));
      //           $('#total_monto_ver').text(total_monto.toFixed(2));

      //           // Eliminar el botón de despachar si existe
      //           $("#btnDespachar").remove();

      //           // Agregar botón de despachar si no ha sido despachada
      //           if (datosInput.despacho === 0) {
      //             // Si existe el boton que no lo cree de forma infinita
      //             if ($(".modal-footer #btnDespachar").length === 0) {
      //               $(".modal-footer").append('<button type="submit" id="btnDespachar" class="btn btn-primary">Despachar</button>');
      //             }
      //           }else{
      //             //Si 
      //             $("#btnDespachar").remove();
      //           }

      //         } catch (e) {
      //           console.error('Error al analizar JSON:', e, 'Respuesta:', response);
      //           alert('Error al procesar los datos de la carga. Por favor, inténtelo de nuevo.');
      //         }
      //       },
      //       error: function(jqXHR, textStatus, errorThrown) {
      //           console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
      //           alert('Error al obtener los datos de la carga. Por favor, inténtelo de nuevo.');
      //       }
      //   });

      //   $('#modalCRUD').modal('show');
      // });

      // Función para cargar datos y mostrar modal
      function cargarDatosYMostrarModal(id_carga) {
        return new Promise(function(resolve, reject) {
          // Realizar la solicitud AJAX para obtener datos
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
              try {
                let datosInput = JSON.parse(response);
                console.log(datosInput);
                if (!datosInput || !datosInput.productos) {
                  console.error('Respuesta JSON no válida:', response);
                  reject('Error al obtener los datos de la carga.');
                  return;
                }
                // Actualizar elementos en el modal con los datos obtenidos
                $(".modal-header").css("background-color", "#007bff");
                $(".modal-header").css("color", "white");
                $(".modal-title").text("Ver Carga ID: ");
                $(".modal-title").html("Ver Carga ID: " + datosInput.id_carga);
                $("#lbl_fecha_carga").html(datosInput.fecha_formatted);
                $("#lbl_origen").html(datosInput.origen);
                $("#lbl_proveedor").html(datosInput.proveedor);
                $("#lbl_chofer").html(datosInput.chofer);
                $('#lbl_datos_adicionales_chofer').html(datosInput.datos_adicionales_chofer);
                $('#lbl_proveedor_default').html(datosInput.proveedor);
                $('#id_carga').html(id_carga);

                accion = "verCarga";

                // Identificar la tabla de productos
                var tbody = document.querySelector('#tableProductosVer tbody');
                tbody.innerHTML = "";
                let cantidad_bulto_total = 0;
                let total_monto = 0;
                let total_kilos = 0;
                datosInput.productos.forEach((producto) => {
                  // Sumar cantidad de bultos
                  cantidad_bulto_total += parseFloat(producto.total_bultos);
                    
                  // Sumar kilos
                  total_kilos += parseFloat(producto.total_kilos);

                  // Sumar monto
                  total_monto += parseFloat(producto.total_monto);

                  let contenidoFila = `
                      <td class="align-middle">
                        <input type='text' readonly tabindex="-1" class="form-control producto" value='${producto.producto}'>
                      </td>
                      <td class="align-middle">
                          <div class="input-group">
                              <input type="text" readonly tabindex="-1" class="form-control cantidad_bultos" value="${producto.total_bultos}" placeholder="Deje en blanco si no desea cargar este producto al producto">
                          </div>
                      </td>
                      <td class="align-middle">
                          <input type="text" readonly tabindex="-1" class="form-control text-right subtotal_kilos_formatted" value='${producto.total_kilos}'>
                      </td>
                      <td class="align-middle">
                          <input type="text" readonly tabindex="-1" class="form-control text-right subtotal_monto_formatted" value='${producto.total_monto}'>
                      </td>`;

                    // Crear una nueva fila
                    var newRow = document.createElement('tr');
                    // Insertar el contenido HTML en la nueva fila
                    newRow.innerHTML = contenidoFila;
                    // Agregar la fila al tbody
                    tbody.appendChild(newRow);
                });

                $('#total_bultos_ver').text(cantidad_bulto_total.toFixed(2));
                $('#total_kilos_ver').text(total_kilos.toFixed(2));
                $('#total_monto_ver').text(total_monto.toFixed(2));

                // Eliminar el botón de despachar si existe
                $("#btnDespachar").remove();

                // Agregar botón de despachar si no ha sido despachada
                if (datosInput.despacho === 0) {
                  // Si no existe el botón, agregarlo
                  if ($(".modal-footer #btnDespachar").length === 0) {
                    $(".modal-footer").append('<button type="submit" id="btnDespachar" class="btn btn-primary">Despachar</button>');
                  }
                } else {
                  // Si la carga fue despachada, eliminar el botón
                  $("#btnDespachar").remove();
                }

                // Mostrar el modal después de actualizar
                $('#modalCRUDadminVer').modal('show');

                // Resolver la Promesa después de cargar y mostrar el modal
                resolve();
              } catch (e) {
                console.error('Error al analizar JSON:', e, 'Respuesta:', response);
                reject('Error al procesar los datos de la carga en cargarDatosYMostrarModal');
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.error('Error en la solicitud AJAX 1:', textStatus, errorThrown);
              alert('Error al obtener los datos de la carga. Por favor, inténtelo de nuevo.');
              reject('Error al obtener los datos de la carga.');
            }
          });
        });
      }

      // Evento click para el botón .btnVer
      $(document).on("click", ".btnVer", function() {
        // Configurar el modal y cargar los datos al hacer clic en .btnVer
        $(".modal-header").css("background-color", "#007bff");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Ver Carga ID: ");
        $('#modalCRUDadminVer').modal('show');
        var fila = $(this).closest("tr");
        var id_carga = fila.find('td:eq(0)').text();
        console.log('id carga: ' + id_carga);
        // Llamar a la función para cargar datos y mostrar modal
        cargarDatosYMostrarModal(id_carga)
          .then(function() {
            // Configurar evento click para el botón #btnDespachar después de cargar y mostrar modal
            $(document).on("click", "#btnDespachar", function(event) {
              event.preventDefault();
              console.log(id_carga);

              // Realizar confirmación y AJAX para despachar carga
              swal({
                title: "¿Estás seguro?",
                text: "Una vez despachada esta carga, no podrás modificarla.",
                icon: "info",
                buttons: true,
                dangerMode: true,
              })
                .then((willDelete) => {
                  if (willDelete) {
                    let accion = "despacharCarga";
                    console.log("accion: " + accion + " ," + "id_carga: " + id_carga );
                    $.ajax({
                      url: "models/administrar_cargas.php",
                      type: "POST",
                      dataType: "json",
                      data: { accion: "despacharCarga", id_carga: id_carga },
                      success: function(response) {
                        console.log("respuesta: " + response.success);
                        if (response.success) {
                          // Actualizar tabla de cargas u otras acciones necesarias
                          tablaCargas.ajax.reload(null, false);
                          swal({
                            icon: 'success',
                            title: 'Carga despachada correctamente'
                          });
                          setTimeout(() => {
                            location.reload(); // Recargar la página después de cierto tiempo
                          }, 3000);
                        } else {
                          // Mostrar mensaje de error si la carga no pudo ser despachada
                          swal({
                            icon: 'error',
                            title: 'Error al despachar la carga',
                            text: response.message
                          });
                        }
                      },
                      error: function(jqXHR, textStatus, errorThrown) {
                          console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                          alert('Error al despachar la carga. Por favor, inténtelo de nuevo.');
                      }
                  });

                  } else {
                    swal("La carga no se despachó.");
                  }
                });
              });
            })
          .catch(function(error) {
          console.error('Error:', error);
          alert('Error al cargar los datos de la carga. Por favor, inténtelo de nuevo.');
        });
      });


      $(document).on("click", ".btnGestionar", function(){
        fila = $(this).closest("tr");
        let id_carga = fila.find('td:eq(0)').text();
        window.location.href="cargas_abm.php?id="+id_carga
      });

      // $(document).on("click", "#btnDespachar", function(event){
      //   event.preventDefault();
      //   let id_carga =  $("#lbl_id_carga").html();
      //   console.log(id_carga);    
      //   swal({
      //     title: "Estas seguro?",
      //     text: "Una vez despachada esta carga, no podras modificarla",
      //     icon: "info",
      //     buttons: true,
      //     dangerMode: true,
      //   })
      //   .then((willDelete) => {
      //     if (willDelete) {
      //       accion = "despacharCarga";
      //       $.ajax({
      //         url: "models/administrar_cargas.php",
      //         type: "POST",
      //         datatype:"json",
      //         data:  {accion:accion, id_carga:id_carga},
      //         success: function() {
      //           //tablaCargas.row(fila.parents('tr')).remove().draw();
      //           tablaCargas.ajax.reload(null, false);
      //           swal({
      //             icon: 'success',
      //             title: 'Carga despachada correctamente'
      //           })
      //           setTimeout(() => {
      //             location.reload();
      //           }, 3000);
                
      //         }
      //       });
      //     } else {
      //       swal("La carga no se despachó!");
      //     }
      //   })
      // });

      //Imprimir con o sin precio
      $(document).on("click", "#btnImprimirConPrecio", function(event){
        event.preventDefault();
        let id_carga =  $('#id_carga').html();
        let cp = 1;

        datosEnviar = JSON.stringify(id_carga);

        const url = "./imprimirCarga.php?id_carga=" + id_carga + "&cp=" + cp;
        win = window.open(url, '_blank', 'toolbar=no,status=no, menubar=no');
        // Cambiar el foco al nuevo tab (punto opcional)
        win.focus();
      })

      $(document).on("click", "#btnImprimirSinPrecio", function(event){
        event.preventDefault();
        let id_carga =  $('#id_carga').html();
        let cp = 0;

        datosEnviar = JSON.stringify(id_carga);

        const url = "./imprimirCarga.php?id_carga=" + id_carga + "&cp=" + cp;
        win = window.open(url, '_blank', 'toolbar=no,status=no, menubar=no');
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