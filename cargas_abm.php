<?php 
session_start();
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
}
$id=0;
if(isset($_GET["id"])){
  $id=$_GET["id"];
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

      #modalCRUDadmin .modal-body {
        max-height: 90%; /* Establece el alto máximo */
        overflow-y: auto; /* Agrega un scroll vertical cuando el contenido supere el alto máximo */
      }

      #tableDepositos tbody td{
        padding: 00.3rem;
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
                  <h5>Gestionar Carga N° <?=$_GET["id"]?></h5>
                  <button id="btnNuevo" type="button" class="btn btn-warning mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar producto</button>
                </div>

                <div class="card-body pt-0">
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
                            <div class="col-sm-2" id="fecha_carga"></div>
                            <div class="col-sm-3 text-right"><strong>Chofer:</strong></div>
                            <div class="col-sm-5" id="chofer_carga"></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2 text-right"><strong>Origen:</strong></div>
                            <div class="col-sm-2" id="origen_carga"></div>
                            <div class="col-sm-3 text-right"><strong>Datos adicionales chofer:</strong></div>
                            <div class="col-sm-5" id="datos_adicionales_chofer_carga"></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2 text-right"><strong>Usuario:</strong></div>
                            <div class="col-sm-2" id="usuario"></div>
                            <div class="col-sm-3 text-right"><strong>Fecha y hora despacho:</strong></div>
                            <div class="col-sm-5" id="fecha_hora_despacho"></div>
                          </div>
                          <input type="hidden" id="id_carga" value="<?=$id?>">
                          <input type="hidden" id="id_chofer">
                          <input type="hidden" id="id_origen">
                          <input type="hidden" id="id_proveedor_default">
                          <input type="hidden" id="despachado">
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
                          <th>Proveedor</th>
                          <th style="text-align:right">Kg x bulto</th>
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
      <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_carga_producto" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin">
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
                    <label for="id_producto" class="col-form-label">Nombre:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_producto" required>
                      <option>Seleccione una familia</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label for="id_proveedor" class="col-form-label">Proveedor:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_proveedor" required></select>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label for="kg_x_bulto" class="col-form-label">Kg x bulto:</label>
                    <input type="number" class="form-control" id="kg_x_bulto" step="0.1" min="0" required>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="form-group">
                    <label for="kg_x_bulto" class="col-form-label">Precio:</label>
                    <input type="number" class="form-control" id="precio" step="0.1" min="0" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <table id="tableDepositos" class="table table-striped">
                    <thead>
                      <th style="width: 30%">Deposito</th>
                      <th style="width: 40%">Cantidad de bultos</th>
                      <th style="width: 15%">Kg Total</th>
                      <th style="width: 15%">Monto Total</th>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                      <th style="text-align:right">Totales</th>
                      <th id="total_bultos">0</th>
                      <th class="text-right" id="total_kilos">0</th>
                      <th class="text-right" id="total_monto">0</th>
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
      var id_carga=$("#id_carga").val();
      var bandera_buscar_producto=true;

      cargarDatosComponentes();
      getDatosCarga();

      $(document).ready(function(){

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
            {"data": "proveedor"},
            {"data": "kg_x_bulto"},
            //{"data": "total_kilos"},
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
                  if(full.despachado=="No"){
                    $btnEditar=`<button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button>`
                    $btnEliminar=`<button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button>`
                  }else{
                    
                  }
                  
                  $buttonsGroupEnd=`</div></div>`

                  $btnComplete = $buttonsGroup+$btnEliminar+$btnEditar+$buttonsGroupEnd
                  
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
              let suma_kilos=0;
              let suma_monto=0;
              settings.json.forEach(row => {
                suma_kilos+=parseFloat(row.total_kilos)
                suma_monto+=parseFloat(row.total_monto)
              });
              // Update footer
              var api = this.api();
              $(api.column(4).footer()).html(formatNumber2Decimal(suma_kilos));
              $(api.column(5).footer()).html(formatCurrency(suma_monto));
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
          $("#formAdmin").trigger("reset");
          $(".modal-header").css("background-color", "#17a2b8");
          $(".modal-header").css("color", "white" );
          $(".modal-title").text("Alta de productos para la carga "+<?=$id?>);
          let modal=$('#modalCRUDadmin')
          modal.modal('show');
          modal.on('shown.bs.modal', function (e) {
            document.getElementById("id_familia").focus();
          })
          $("#id_proveedor").val($("#id_proveedor_default").val()).change();
          $('#id_familia').val("").change();
          $('#id_producto').val("").change();
          accion = "addProductoCarga";
        });

        $('#formAdmin').submit(function(e){
          e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página

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
          let id_proveedor = $.trim($('#id_proveedor').val());
          let kg_x_bulto = $.trim($('#kg_x_bulto').val());
          let precio = $.trim($('#precio').val());

          let datosDepositos = []; // Array para almacenar los datos de las filas seleccionadas

          let tableDepositosRows = $("#tableDepositos tbody tr");
          tableDepositosRows.each(function(){
            let fila=$(this);
            
            let id_producto_destino=fila.find(".id_producto_destino").val()
            let porcentaje_extra=fila.find(".porcentaje_extra").val()
            let cantidad_bultos=fila.find(".cantidad_bultos").val()
            let id_deposito=fila.find(".id_deposito").val()
            let subtotal_kilos=fila.find(".subtotal_kilos").val()
            console.log(id_producto_destino);

            datosDepositos.push({
              id_producto_destino: id_producto_destino,
              porcentaje_extra: porcentaje_extra,
              cantidad_bultos: cantidad_bultos,
              id_deposito: id_deposito,
              subtotal_kilos: subtotal_kilos
            });

          })

          if (datosDepositos.length==0) {
            swal({
              icon: 'error',
              title: 'Ingrese la cantidad de bultos para al menos un deposito'
            });
            return false;
          }

          $.ajax({
            url: "models/administrar_cargas.php",
            type: "POST",
            datatype:"json",
            data:  {accion:accion, id_carga_producto:id_carga_producto, id_carga:id_carga, id_producto:id_producto, id_proveedor:id_proveedor, kg_x_bulto:kg_x_bulto, precio:precio, datosDepositos:datosDepositos},
            success: function(data) {
              //console.log(data);
              data = JSON.parse(data);
              //console.log(data);
              if(data.ok==1){
                tablaProductosCarga.ajax.reload(null, false);
                
                swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                }).then(function() {
                  // Esta función se ejecutará después de que el usuario presione el botón "OK"
                  console.log('El usuario presionó OK');
                  // Puedes agregar aquí la lógica que deseas ejecutar después de presionar OK
                  if (cargarOtro) {
                    $("#btnNuevo").click()
                    $('#id_proveedor').val(id_proveedor).change();
                  }
                });

                /*if (cargarOtro) {
                  $(this).reset();
                  $('#id_proveedor').val(id_proveedor).change();
                }else{*/
                  $('#modalCRUDadmin').modal('hide');
                //}
              }else{
                swal({
                  icon: 'error',
                  title: 'El registro no se insertó!'
                });
              }
            }
          });
        });

        $(document).on("click", ".btnEditar", function(){
          bandera_buscar_producto=false
          $(".modal-header").css( "background-color", "#22af47");
          $(".modal-header").css( "color", "white" );
          $(".modal-title").text("Editar carga de producto");
          $('#modalCRUDadmin').modal('show');
          $("#formAdmin").trigger("reset");
          fila = $(this).closest("tr");
          let id_carga_producto = fila.find('td:eq(0)').text();

          $("#id_carga_producto").html(id_carga_producto);
          let datosUpdate = new FormData();
          datosUpdate.append('accion', 'traerProductoDestinosCarga');
          datosUpdate.append('id_carga_producto', id_carga_producto);
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
              accion = "updateProductoCarga";
              let datosInput = JSON.parse(response);
              //console.log(datosInput);

              $('#id_familia').val(datosInput.id_familia).change();
              //$('#id_producto').val(datosInput.id_producto).change();
              bandera_buscar_producto=true
              getProductosByFamilia(datosInput.id_familia,datosInput.id_producto)
              $('#kg_x_bulto').val(datosInput.kg_x_bulto);
              $('#precio').val(datosInput.precio);
              $('#id_proveedor').val(datosInput.id_proveedor).change();

              let destinos=datosInput["destinos"];
              //console.log(destinos);
              let tableDepositosRows = $("#tableDepositos tbody tr");
              tableDepositosRows.each(function(){
                let fila=$(this);
                //console.log("fila",fila);
                let id_deposito=fila.find(".id_deposito").val()

                let data=destinos.find(({ id_destino }) => id_destino === id_deposito);
                /*console.log(id_deposito);
                console.log(data);*/
                if(data!=undefined){
                  //console.log("data",data);
                  fila.find(".id_producto_destino").val(data.id_producto_destino)
                  fila.find(".cantidad_bultos").val(data.cantidad_bultos)
                  fila.find(".subtotal_kilos").val(data.subtotal)
                  fila.find(".subtotal_monto").val(data.subtotal)
                }
              })
              calcularTotales()
            }
          });

          $('#modalCRUD').modal('show');
        });

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

        $(document).on("input", "#kg_x_bulto, #precio, .cantidad_bultos", function(){
          calcularTotales()
        })

        // Función para rotar el ícono cuando se abre el acordeón
        $('#collapseOne').on('show.bs.collapse', function () {
          $('#headingOne .fa').addClass('fa-angle-down').removeClass('fa-angle-right');
        });

        // Función para revertir la rotación del ícono cuando se cierra el acordeón
        $('#collapseOne').on('hide.bs.collapse', function () {
          $('#headingOne .fa').removeClass('fa-angle-down').addClass('fa-angle-right');
        });

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

      function getProductosByFamilia(id_familia,id_producto){
        if(bandera_buscar_producto){
          console.log("BUSCAMOS LOS PRODUCTOS");
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
                let optionText = document.createTextNode(producto.producto);
                $option.appendChild(optionText);
                $option.setAttribute("value", producto.id_producto);
                if(id_producto==producto.id_producto){
                  $option.setAttribute("selected", "selected");
                }
                $selectProducto.appendChild($option);
              })
              $($selectProducto).select2()
              console.log("PRODUCTOS CARGADOS");
              if(id_producto>0){
                $("#id_producto").val(id_producto).change();
                console.log("PRODUCTO ASIGNADO");
              }
            }
          });
        }
      }

      function calcularTotales(){
        let kg_x_bulto = $("#kg_x_bulto").val();
        let precio = $("#precio").val();
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

          let subtotal_kilos=cantidad_bultos*kg_x_bulto;
          let subtotal_monto=cantidad_bultos*precio;
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
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosIniciales');
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

            /*Identifico el select de proveedores*/
            $selectProveedor = document.getElementById("id_proveedor");
            //Genero los options del select proveedores
            respuestaJson.proveedores.forEach((proveedor)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(proveedor.proveedor);
              $option.appendChild(optionText);
              $option.setAttribute("value", proveedor.id_proveedor);
              $selectProveedor.appendChild($option);
            })
            $($selectProveedor).select2()

            // Identifico la tabla de destinos
            var tbody = document.querySelector('#tableDepositos tbody');
            tbody.innerHTML="";
            respuestaJson.destinos.forEach((destino) => {
                // Plantilla para cada fila
              let porcentaje_extra=destino.porcentaje_extra
              let lbl_porcentaje_extra=""
              if(porcentaje_extra>0){
                //lbl_porcentaje_extra=" (+"+porcentaje_extra+"%)"
              }
              let contenidoFila = `
                <td class="align-middle">
                  <input type='hidden' class="porcentaje_extra" value='${porcentaje_extra}'>
                  <input type='hidden' class="id_producto_destino" value=''>
                  <input type='hidden' class="id_deposito" value='${destino.id_destino}'>${destino.destino+lbl_porcentaje_extra}
                </td>
                <td class="align-middle">
                  <div class="input-group">
                    <input type="number" class="form-control cantidad_bultos" value="" placeholder="Deje en blanco si no desea cargar este producto al destino">
                  </div>
                </td>
                <td class="align-middle">
                  <input type="hidden" name="subtotal_kilos" class="subtotal_kilos">
                  <input type="text" readonly tabindex="-1" class="form-control text-right subtotal_kilos_formatted">
                </td>
                <td class="align-middle">
                  <input type="hidden" name="subtotal_monto" class="subtotal_monto">
                  <input type="text" disabled tabindex="-1" class="form-control text-right subtotal_monto_formatted">
                </td>`;
              // Crear una nueva fila
              var newRow = document.createElement('tr');
              // Insertar el contenido HTML en la nueva fila
              newRow.innerHTML = contenidoFila;
              // Agregar la fila al tbody
              tbody.appendChild(newRow);
            });

          }
        });
      }

      function getDatosCarga(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'getDatosCarga');
        datosIniciales.append('id_carga', '<?=$id?>');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_cargas.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(respuesta){
            /*Convierto en json la respuesta del servidor*/
            data = JSON.parse(respuesta);
            console.log(data);
            $("#fecha_carga").html(data.fecha_formatted)
            $("#origen_carga").html(data.origen)
            $("#chofer_carga").html(data.chofer)
            $("#datos_adicionales_chofer_carga").html(data.datos_adicionales_chofer)
            $("#fecha_hora_despacho").html(data.fecha_hora_despacho)
            $("#usuario").html(data.usuario)

            $("#id_chofer").val(data.id_chofer)
            $("#id_origen").val(data.id_origen)
            $("#id_proveedor_default").val(data.id_proveedor)
            $("#despachado").val(data.despachado)
          }
        });
      }
    </script>
  </body>
</html>