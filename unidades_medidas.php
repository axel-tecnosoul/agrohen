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
                  <h3>Unidades de medidas</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Unidades de Medidas</li>
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
                  <h5 style="display: inline-block;vertical-align: middle;">Administrar Unidad Medida</h5>
                    <button id="btnNuevo" type="button" class="btn btn-warning ml-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>
                </div>
                <div class="card-body py-1">
                  <div class="table-responsive">
                    <table class="table table-hover display" id="tablaUnidad_medida">
                      <thead class="text-center">
                        <tr>
                          <th class="text-center">#ID</th>
                          <th>Nombre</th>
                          <th>Estado</th>
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
    <div class="modal fade" id="modalCRUDadmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_unidad_medida" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" required>
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
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">
      var accion
      $(document).ready(function(){
        tablaUnidad_medida = $('#tablaUnidad_medida').DataTable({
          "ajax": {
            "url" : "./models/administrar_unidad_medida.php?accion=traerUnidad_medida",
            "dataSrc": "",
          },
          "columns":[
            {"data": "id_unidad_medida"},
            {"data": "nombre"},
            {
              render: function(data, type, full, meta) {
                const estados = {
                    0: "Inactivo",
                    1: "Activo",
                  }
                return ()=>{
                  $options="";
                  for(key in estados){
                    if(full.activo == key){
                      $options+=`<option selected value="${full.estado}">${estados[key]}</option>`
                    }else{
                      $options+=`<option value="${key}">${estados[key]}</option>`;
                      }
                  }
                  $selectInit = `<select class="estado">`;
                  $selectEnd = "</select>";
                  $selectComplete = $selectInit + $options+$selectEnd

                  return $selectComplete;
                };
              }
            },
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

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosIniciales');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_unidad_medida.php",
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

            /*Genero los options del select usuarios*/
            // respuestaJson.usuarios.forEach((usuario)=>{
            //   $option = document.createElement("option");
            //   let optionText = document.createTextNode(usuario.usuario);
            //   $option.appendChild(optionText);
            //   $option.setAttribute("value", usuario.id_usuario);
            // })

          }
        });
      }

      $("#btnNuevo").click(function(){
        var $boton = $(this);
        $("#formAdmin").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Alta Unidad de Medida");
        let modal=$('#modalCRUDadmin')
        modal.modal('show');
        modal.on('shown.bs.modal', function (e) {
          document.getElementById("nombre").focus();
        })
        accion = "addUnidad_medida";
      });

      $('#formAdmin').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
        var $boton = $(this).find(':submit');
        let id_unidad_medida = $.trim($('#id_unidad_medida').html());
        let nombre = $.trim($('#nombre').val());
        mostrarSpinner($boton);

        $.ajax({
          url: "models/administrar_unidad_medida.php",
          type: "POST",
          datatype:"json",
          data:  {accion: accion, id_unidad_medida: id_unidad_medida, nombre: nombre},
          success: function(data) {
            respuestaJson = JSON.parse(data);
            if(respuestaJson.ok=="1"){
              tablaUnidad_medida.ajax.reload(null, false);
            }else{
              swal({
                icon: 'error',
                title: 'El registro no se insertó!'
              });
            }
            restaurarBoton($boton);
          }
        });
        $('#modalCRUDadmin').modal('hide');
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
        restaurarBoton($boton);
      });

      $(document).on("click", ".btnEditar", function(){
        $boton = $(this);
        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar Unidad de Medida");
        $('#modalCRUDadmin').modal('show');
        fila = $(this).closest("tr");
        let id_unidad_medida = fila.find('td:eq(0)').text();

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerUnidad_medidaUpdate');
        datosUpdate.append('id_unidad_medida', id_unidad_medida);
        mostrarSpinner($boton);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_unidad_medida.php',
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
            $('#id_unidad_medida').html(datosInput.id_unidad_medida);
            $("#nombre").val(datosInput.nombre);
            //$('#usuario').val(datosInput.usuario)
            //$('#id_usuario').html(datosInput.id_usuario)

            accion = "updateUnidad_medida";
            restaurarBoton($boton);
          }
        });

        $('#modalCRUD').modal('show');
      });

      //Borrar
      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);
        id_unidad_medida = parseInt($(this).closest('tr').find('td:eq(0)').text());       
        swal({
          title: "Estas seguro?",
          text: "Una vez eliminada esta unidad de medida, no volveras a verla",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarUnidad_medida";
            $.ajax({
              url: "models/administrar_unidad_medida.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_unidad_medida:id_unidad_medida},
              success: function(response) {
                response = JSON.parse(response);
                if(response=="1"){
                  tablaUnidad_medida.ajax.reload(null, false);
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

      $(document).on("change", ".estado", function(){
        fila = $(this);
        nuevoEstado = $(this).val();
        id_unidad_medida = parseInt($(this).closest('tr').find('td:eq(0)').text());
        accion = "cambiarEstado";
        $.ajax({
          url: "models/administrar_unidad_medida.php",
          type: "POST",
          datatype:"json",
          data:  {accion: accion, id_unidad_medida: id_unidad_medida, estado: nuevoEstado},    
          success: function(data) {
            $('#modalCRUD').modal('hide');
            tablaUnidad_medida.ajax.reload(null, false);
            swal({
              icon: 'success',
              title: 'Estado cambiado exitosamente'
            });
          }
        })
      })
    </script>
  </body>
</html>