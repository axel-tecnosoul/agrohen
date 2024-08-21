<?php 
session_start();
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
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
          <div class="page-header py-3">
            <div class="row">
              <div class="col">
                <div class="page-header-left">
                  <h3>Productos</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Productos</li>
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
                  <h5 style="display: inline-block;vertical-align: middle;">Administrar Productos</h5>
                    <button id="btnNuevo" type="button" class="btn btn-warning ml-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>
                </div>
                <div class="card-body py-1">
                  <div class="table-responsive">
                    <table class="table table-hover display" id="tablaProducto">
                      <thead class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Familia</th>
                          <th>Nombre</th>
                          <th>Presentacion</th>
                          <th>Unidad de Medida</th>
                          <th>Ultimo Precio</th>
                          <th>Ultimo KG x Bulto</th>
                          <!-- <th>Estado</th> -->
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
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
    <div class="modal fade" id="modalCRUDadmin" tabindex="-100000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_producto" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Familia:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_familia" required></select>
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
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_presentacion" required>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Unidad de Medida:</label>
                    <select class="form-control js-example-basic-single" style="width: 100%;" id="id_unidad_medida" required></select>
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
 
    <!-- latest jquery-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!--Funciones-->
    <script src="assets/js/funciones.js"></script>
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
    <!--<script src="assets/js/datatable/datatables/datatable.custom.js"></script>-->
    <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <script src="assets/js/select2/select2.full.min.js"></script>
    <script src="assets/js/select2/select2-custom.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">
      var accion
      var select2ProductoNoResultText="No hay resultados. Presione ENTER para agregar"
      $(document).ready(function(){
        tablaProducto = $('#tablaProducto').DataTable({
          "ajax": {
            "url" : "./models/administrar_producto.php?accion=traerProducto",
            "dataSrc": "",
          },
          "columns":[
            {"data": "id_producto"},
            {"data": "familia"},
            {"data": "nombre"},
            {"data": "presentacion"},
            {"data": "unidad_medida"},
            {"data": "ultimo_precio"},
            {"data": "ultimo_kg_x_bulto"},
            {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
          ],
          "language":  idiomaEsp
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

      //detectamos los tipeos en la busqueda de presentaciones para permitir dar de alta uno nuevo
      $('#id_presentacion').on('select2:open', function() {
        let searchField = $('.select2-search__field');
        let noResultsShown = false;

        searchField.on('keydown', function(e) {
          if ($('.select2-results__option').text()==select2ProductoNoResultText){
            noResultsShown = true;
          }
          if (e.key === 'Enter' && noResultsShown) {
            let searchTerm = $(this).val();
            //Alta de Presentacion
            console.log("Apretaste Enter!")
          }
        });
      });

      function cargarDatosComponentes(){
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
            $selectFamilia = document.getElementById("id_familia");
            /*Genero los options del select usuarios*/
            respuestaJson.familias.forEach((familia)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(familia.familia);
              $option.appendChild(optionText);
              $option.setAttribute("value", familia.id_familia);
              $selectFamilia.appendChild($option);
            })

            $($selectFamilia).select2()

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

            //$($selectPresentacion).select2()
            $($selectPresentacion).select2({
              language: {
                noResults: function() {
                  return select2ProductoNoResultText;
                }
              }
            })
            console.log($selectPresentacion);

            /*Identifico el select de perfiles*/
            $selectUnidadMedida = document.getElementById("id_unidad_medida");
            /*Genero los options del select usuarios*/
            respuestaJson.unidades_medidas.forEach((unidad_medida)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(unidad_medida.unidad_medida);
              $option.appendChild(optionText);
              $option.setAttribute("value", unidad_medida.id_unidad_medida);
              $selectUnidadMedida.appendChild($option);
            })

            $($selectUnidadMedida).select2()

          }
        });
      }

      $("#btnNuevo").click(function(){
        var $boton = $(this);
        $("#formAdmin").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Alta de Producto");
        let modal=$('#modalCRUDadmin')
        modal.modal('show');
        modal.on('shown.bs.modal', function (e) {
          document.getElementById("nombre").focus();
        })
        $('#id_presentacion').val("").change();
        $('#id_unidad_medida').val("").change();
        $('#id_familia').val("").change();
        accion = "addProducto";
      });

      $('#formAdmin').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
        var $boton = $(this).find(':submit');
        let id_producto = $.trim($('#id_producto').html());
        let id_familia = $.trim($('#id_familia').val());
        let nombre = $.trim($('#nombre').val());
        let id_presentacion = $.trim($('#id_presentacion').val());
        let id_unidad_medida = $.trim($('#id_unidad_medida').val());
        mostrarSpinner($boton);

        $.ajax({
          url: "models/administrar_producto.php",
          type: "POST",
          datatype:"json",
          data:  {accion: accion, id_producto: id_producto, nombre: nombre, id_presentacion: id_presentacion, id_unidad_medida: id_unidad_medida, id_familia: id_familia},
          success: function(data) {
            respuestaJson = JSON.parse(data);
            if(respuestaJson.ok=="1"){
            //if(data=="1"){
              tablaProducto.ajax.reload(null, false);
              $('#modalCRUDadmin').modal('hide');
              swal({
                icon: 'success',
                title: 'Accion realizada correctamente'
              });
            }else{
              swal({
                icon: 'error',
                title: 'El registro no se insertó!'
              });
            }
            restaurarBoton($boton);
          },
          error: function() {
              restaurarBoton($boton);
              swal({
                  icon: 'error',
                  title: 'Error al realizar la operación'
              });
          }
        });
      });

      $(document).on("click", ".btnEditar", function(){
        $boton = $(this);
        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar Producto");
        $('#modalCRUDadmin').modal('show');
        fila = $(this).closest("tr");
        let id_producto = fila.find('td:eq(0)').text();

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerProductoUpdate');
        datosUpdate.append('id_producto', id_producto);
        mostrarSpinner($boton);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_producto.php',
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
            $('#id_producto').html(datosInput.id_producto);
            $("#nombre").val(datosInput.nombre);
            $("#id_presentacion").val(datosInput.id_presentacion).change();
            $("#id_unidad_medida").val(datosInput.id_unidad_medida).change();
            $("#id_familia").val(datosInput.id_familia).change();
            //$('#usuario').val(datosInput.usuario)
            //$('#id_usuario').html(datosInput.id_usuario)

            accion = "updateProducto";
            restaurarBoton($boton);
          }
        });

        $('#modalCRUD').modal('show');
      });

      //Borrar
      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);
        id_producto = parseInt($(this).closest('tr').find('td:eq(0)').text());       
        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este producto, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarProducto";
            $.ajax({
              url: "models/administrar_producto.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_producto:id_producto},
              success: function(response) {
                response = JSON.parse(response);
                if(response=="1"){
                  tablaProducto.ajax.reload(null, false);
                  swal({
                    icon: 'success',
                    title: 'Accion realizada correctamente'
                  });
                }else{
                  swal({
                    icon: 'error',
                    title: response
                  });
                }
              }
            }); 
          } else {
            swal("El registro no se eliminó!");
          }
        })
      });

      // $(document).on("change", ".estado", function(){
      //   fila = $(this);
      //   nuevoEstado = $(this).val();
      //   id_producto = parseInt($(this).closest('tr').find('td:eq(0)').text());
      //   accion = "cambiarEstado";
      //   $.ajax({
      //     url: "models/administrar_producto.php",
      //     type: "POST",
      //     datatype:"json",
      //     data:  {accion: accion, id_producto: id_producto, estado: nuevoEstado},    
      //     success: function(data) {
      //       $('#modalCRUD').modal('hide');
      //       tablaproducto.ajax.reload(null, false);
      //       swal({
      //         icon: 'success',
      //         title: 'Estado cambiado exitosamente'
      //       });
      //     }
      //   })
      // })
    </script>
  </body>
</html>