<?php
session_start();
include_once('models/conexion.php');
if (!isset($_SESSION['rowUsers']['id_usuario'])) {
  header("location:./models/redireccionar.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <?php include('./views/head_tables.php');?>
  </head>
  <body>
    <div class="loader-wrapper">
      <div class="loader bg-white">
        <div class="whirly-loader"></div>
      </div>
    </div>
    <?php include('./views/main_header.php');?>
    <div class="page-body-wrapper">
      <div class="page-sidebar"><?php include('./views/slideBar.php');?></div>
      <div class="page-body">
        <div class="container-fluid">
          <div class="page-header py-3">
            <div class="row">
              <div class="col">
                <div class="page-header-left">
                  <h3>Vueltas</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Transporte</li>
                    <li class="breadcrumb-item active">Vueltas</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header py-3">
                  <h5 style="display: inline-block;vertical-align: middle;">Administrar vueltas</h5>
                  <button id="btnNuevaVuelta" type="button" class="btn btn-success ml-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Nueva vuelta</button>
                </div>
                <div class="card-body py-1">
                  <table id="tableFiltrosVueltas" class="table table-borderless mb-3">
                    <tr>
                      <td width="10%" class="text-right p-1">Chofer:</td>
                      <td width="20%" class="p-1">
                        <select id="filtro_chofer" class="form-control" style="width: 100%;">
                          <option value="">Todos</option>
                          <option value="1">Juan Pérez</option>
                          <option value="2">Carlos Gómez</option>
                        </select>
                      </td>
                      <td width="10%" class="text-right p-1">Camión:</td>
                      <td width="20%" class="p-1">
                        <select id="filtro_camion" class="form-control" style="width: 100%;">
                          <option value="">Todos</option>
                          <option value="ABC123">ABC123</option>
                          <option value="DEF456">DEF456</option>
                        </select>
                      </td>
                      <td width="10%" class="text-right p-1">Estado:</td>
                      <td width="20%" class="p-1">
                        <select id="filtro_estado" class="form-control" style="width: 100%;">
                          <option value="">Todos</option>
                          <option value="abierta">Abierta</option>
                          <option value="cerrada">Cerrada</option>
                          <option value="liquidada">Liquidada</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-right p-1">Desde:</td>
                      <td class="p-1">
                        <input type="date" id="filtro_desde" class="form-control">
                      </td>
                      <td class="text-right p-1">Hasta:</td>
                      <td class="p-1">
                        <input type="date" id="filtro_hasta" class="form-control">
                      </td>
                    </tr>
                  </table>
                  <div class="dt-ext table-responsive">
                    <table class="table table-hover display" id="tablaVueltas">
                      <thead class="text-center">
                        <tr>
                          <th>Fecha salida</th>
                          <th>KM salida</th>
                          <th>Fecha cierre</th>
                          <th>KM cierre</th>
                          <th>Chofer</th>
                          <th>Camión</th>
                          <th>Estado</th>
                          <th class="text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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

    <div class="modal fade" id="modalCRUDvuelta" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tituloModalVuelta"></h5>
            <span id="id_vuelta" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formVuelta">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="id_camion" class="col-form-label">Camión:</label>
                    <select id="id_camion" class="form-control" required>
                      <option value="">Seleccione</option>
                      <option value="1">ABC123</option>
                      <option value="2">DEF456</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="id_chofer" class="col-form-label">Chofer:</label>
                    <select id="id_chofer" class="form-control" required>
                      <option value="">Seleccione</option>
                      <option value="1">Juan Pérez</option>
                      <option value="2">Carlos Gómez</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="fecha_salida" class="col-form-label">Fecha salida:</label>
                    <input type="date" id="fecha_salida" class="form-control" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="km_salida" class="col-form-label">KM salida:</label>
                    <input type="number" id="km_salida" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="observaciones" class="col-form-label">Observaciones:</label>
                    <textarea id="observaciones" class="form-control" rows="2"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
              <button type="submit" id="btnGuardarVuelta" class="btn btn-primary">Guardar y continuar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- latest jquery-->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Funciones -->
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
    <script type="text/javascript">
      var tablaVueltas
      var accionVuelta
      var idiomaEsp

      $(document).ready(function(){
        inicializarIdioma()
        inicializarTablaVueltas()

        $(document).on('click', '#btnNuevaVuelta', function(){
          limpiarFormularioVuelta()
          $('#tituloModalVuelta').html('Alta de vuelta')
          accionVuelta = 'addVuelta'
          $('#modalCRUDvuelta').modal('show')
        })

        $('#tablaVueltas tbody').on('click', '.btnEditarVuelta', function(){
          var data = tablaVueltas.row($(this).parents('tr')).data()
          console.log('Editar vuelta', data.id_vuelta)
          alert('Editar vuelta: ' + data.id_vuelta)
        })

        /*$('#tablaVueltas tbody').on('click', '.btnDetalleVuelta', function(){
          var data = tablaVueltas.row($(this).parents('tr')).data()
          console.log('Detalle vuelta', data.id_vuelta)
          alert('Detalle vuelta: ' + data.id_vuelta)
        })*/

        $('#formVuelta').on('submit', function(e){
          e.preventDefault()
          var datos = {
            id_vuelta: $('#id_vuelta').html(),
            id_camion: $('#id_camion').val(),
            id_chofer: $('#id_chofer').val(),
            fecha_salida: $('#fecha_salida').val(),
            km_salida: $('#km_salida').val(),
            observaciones: $('#observaciones').val()
          }
          console.log('Datos de vuelta', datos)
          alert('Datos de vuelta: ' + JSON.stringify(datos))
          // TODO: enviar datos por AJAX a models/administrar_vueltas.php
        })
      })

      function inicializarIdioma(){
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
          "zeroRecords": "No se encontraron resultados",
          "aria": {
            "sortAscending": ": activar para ordenar la columna de manera ascendente",
            "sortDescending": ": activar para ordenar la columna de manera descendente"
          }
        }
      }

      function inicializarTablaVueltas(){
        tablaVueltas = $('#tablaVueltas').DataTable({
          "ajax": {
            "url" : "assets/mock/vueltas_demo.json",
            "dataSrc": ""
          },
          "responsive": true,
          "columns":[
            {"data": "fecha_salida"},
            {"data": "km_salida"},
            {"data": "fecha_cierre"},
            {"data": "km_cierre"},
            {"data": "chofer"},
            {"data": "camion"},
            {"data": "estado"},
            {
              render: function(data, type, full, meta) {
                return "<div class='text-center'><div class='btn-group'>" +
                  "<button class='btn btn-success btn-sm btnEditarVuelta' title='Editar'><i class='fa fa-edit'></i> Editar</button>" +
                  "<a href='viajes.php?id_vuelta="+full.id_vuelta+"' class='btn btn-info btn-sm btnDetalleVuelta ml-1' title='Ver vuelta'><i class='fa fa-info-circle'></i> Ver</a>" +
                  "</div></div>"
              }
            }
          ],
          "language": idiomaEsp
        })
      }

      function limpiarFormularioVuelta(){
        $('#formVuelta')[0].reset()
        $('#id_vuelta').html('')
      }
    </script>
  </body>
</html>
