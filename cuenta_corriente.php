<?php 
session_start();
include_once('models/conexion.php');
date_default_timezone_set("America/Buenos_Aires");
$hora = date('Hi');
$ahora = date('Y-m-d H:i');
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
      .inputDate{
        max-width: 150px;
      }
      #tableFiltros td{
        /*width: auto;*/
      }
      tr.child {
        background-color: beige !important;
      }
      table.dataTable tbody tr {
        background-color: inherit;
      }
      table.child td{
        padding: .25rem !important;
      }
      .detalle-producto {
        display: flex;
        align-items: center;
      }
      .simbolo {
        min-width: 1ch;
        text-align: left;
      }
      .precio {
        text-align: right;
        margin-right: 20px;
        width: 110px;
        text-wrap: nowrap;
      }
      .descripcion {
        flex: 1;
        text-align: left;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
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
                  <h3>Cuenta Corriente</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Cuenta Corriente</li>
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
                  <h5 style="display: inline-block;vertical-align: middle;">Cuenta Corriente</h5><?php
                  if($id_perfil==1){?>
                    <button id="btnNuevo" type="button" class="btn btn-warning ml-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar Movimiento</button><?php
                  }?>
                  <button id="btnImprimir" type="button" class="btn btn-primary ml-2 " data-toggle="modal"><i class="fa fa-plus-square"></i> Imprimir Movimiento</button>
                  <button id="btnExportar" type="button" class="btn btn-secondary ml-2 " data-toggle="modal"><i class="fa fa-plus-square"></i> Exportar Movimiento</button>
                  <span id="id_perfil" class="d-none"><?=$id_perfil?></span>
                </div>
                <div class="card-body py-1">

                  <input type="hidden" id="id_deposito_usuario" value="<?=$_SESSION["rowUsers"]["id_deposito"]?>">
                  <table id="tableFiltros" style="" class="table table-borderless mb-3">
                    <tr id="row1">
                      <td width="10%" class="text-right p-1">Desde: </td>
                      <td width="10%" class="p-1 inputDate" style="">
                        <input type="date" id="desde" value="<?=date("Y-m-d",strtotime(date("Y-m-d")." -1 year"))?>" class="form-control form-control-sm w-auto filtraTabla">
                      </td><?php
                      if($id_perfil==1){?>
                        <td width="10%" rowspan="2" style="vertical-align: middle;" class="text-right p-1">Cuenta:</td>
                        <td width="30%" rowspan="2" style="vertical-align: middle;" class="p-1">
                          <select id="cuenta" class="form-control form-control-sm filtraTabla selectpicker" data-style="multiselect"></select>
                        </td>
                        <td width="10%" rowspan="2" style="vertical-align: middle;" class="depositoCells invisible text-right p-1">Deposito:</td>
                        <td width="30%" rowspan="2" style="vertical-align: middle;" class="depositoCells invisible p-1">
                          <select id="id_deposito" class="form-control form-control-sm filtraTabla selectpicker" data-style="multiselect" data-selected-text-format="count > 1" multiple></select>
                        </td><?php
                      }?>
                    </tr>
                    <tr id="row2">
                      <td class="text-right p-1">Hasta: </td>
                      <td class="p-1 inputDate"><input type="date" id="hasta" value="<?=date("Y-m-d")?>" class="form-control form-control-sm w-auto filtraTabla"></td>
                    </tr>
                  </table>

                  <div class="dt-ext table-responsive">
                    <table class="table table-hover display" id="tablaCtaCte">
                      <thead class="text-center">
                        <tr>
                          <th>Fecha y hora</th>
                          <th>Descripcion</th>
                          <th>Debe</th>
                          <th>Haber</th>
                          <th>Saldo</th>
                          <!-- <th class="none">Origen: </th>
                          <th class="none">Chofer: </th>
                          <th class="none"></th> -->
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot>
                        <tr>
                          <th style="text-align:right" colspan="2">Totales</th>
                          <th style="text-align:right">Total debe</th>
                          <th style="text-align:right">Total haber</th>
                          <th style="text-align:right"></th>
                          <!-- <th style="text-align:right" colspan="3"></th> -->
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

    <!--Modal para CRUD-->
    <div class="modal fade" id="modalCRUDadmin" tabindex="-1000000000000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <span id="id_movimiento" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="formAdmin">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="fecha_hora" class="col-form-label">Fecha:</label>
                    <input type="hidden" id="lbl_id_cuenta">
                    <input type="datetime-local" class="form-control" id="fecha_hora" value="<?=$ahora?>" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="cuenta_registrar" class="col-form-label">Cuenta:</label>
                    <select class="form-control" style="width: 100%;" id="cuenta_registrar" required></select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="tipo_movimiento" class="col-form-label">Tipo de movimiento</label>
                    <select class="form-control" style="width: 100%;" id="tipo_movimiento" required>
                      <option value="">Seleccione...</option>
                      <option value="debe">Debe</option>
                      <option value="haber">Haber</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="monto" class="col-form-label">Monto:</label>
                    <input type="number" class="form-control" id="monto" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                <div class="form-group">
                    <label for="descripcion" class="col-form-label">Descripcion:</label>
                    <input type="text" class="form-control" id="descripcion" required>
                  </div>
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
      var allOptionsSelectDestino
      var tablaCtaCte=$('#tablaCtaCte')
      var desde_aux=""
      var hasta_aux=""
      var id_aux=0
      var id_deposito_aux=""
      $(document).ready(function(){
        
        cargarDatosComponentes();

        getCtacte()

        $("#cuenta").on("change",filtrarDepositos)

        $(".filtraTabla").on("change",getCtacte)
      
      });

      /*$(document).on('mouseenter','.select2-selection__choice',function () {
        console.log(this);
        $(this).removeAttr('data-original-title');
      });*/

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

      function filtrarDepositos(){
        var selectElement = document.getElementById("cuenta");
        var selectedIndex = selectElement.selectedIndex;
        var selectedOption = selectElement.options[selectedIndex];
        if(selectedOption.dataset.tipo=="responsable"){
          $(".depositoCells").removeClass("invisible")

          // Obtén el valor del id_responsable del select #cuenta
          var id_responsable = selectedOption.dataset.id;

          /*Identifico el select de destinos*/
          $selectDestino = document.getElementById("id_deposito");
          $selectDestino.innerHTML="";
          //console.log(allOptionsSelectDestino);
          //Genero los options del select destinos
          allOptionsSelectDestino.forEach((destino)=>{
            //console.log(destino);
            if(destino.id_responsable==id_responsable){
              $option = document.createElement("option");
              let optionText = document.createTextNode(destino.destino);
              $option.appendChild(optionText);
              $option.setAttribute("value", destino.id_destino);
              $selectDestino.appendChild($option);
            }
          })
          $($selectDestino).select2({
            placeholder: "Seleccione...",
            //allowClear: true
          })

        }else{
          $(".depositoCells").addClass("invisible")
        }

        $("#id_deposito").val([]).change();
      }

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesCtaCte');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_cta_cte.php",
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
            selectTipoMovimiento = document.getElementById("tipo_movimiento");
            selectCuenta = document.getElementById("cuenta");
            $selectCuentaRegistrarNuevoMovimiento = document.getElementById("cuenta_registrar");
            
            //Genero los options del select choferes
            if(selectCuenta.type=="select-one"){
              respuestaJson.cuentas.forEach((cuenta)=>{
                
                let $option1 = document.createElement("option");
                let optionText = document.createTextNode(cuenta.cuenta);
                $option1.appendChild(optionText);
                let valor="";
                if(cuenta.id!=""){
                  valor=cuenta.tipo+"-"+cuenta.id;
                  $option1.setAttribute("data-id", cuenta.id);
                  $option1.setAttribute("data-tipo", cuenta.tipo);
                  $option1.setAttribute("data-tipo_aumento_extra", cuenta.tipo_aumento_extra);
                  $option1.setAttribute("data-valor_extra", cuenta.valor_extra);
                }
                $option1.setAttribute("value", valor);
                selectCuenta.appendChild($option1);

                // Crear opción para el select de nuevo movimiento
                const $option2 = $option1.cloneNode(true); // Clona el nodo de la opción completa
                $selectCuentaRegistrarNuevoMovimiento.appendChild($option2);
              })
              $(selectCuenta).select2()
              $($selectCuentaRegistrarNuevoMovimiento).select2()
              $(selectTipoMovimiento).select2()
            }

            /*Identifico el select de destinos*/
            $selectDestino = document.getElementById("id_deposito");
            
            //Genero los options del select destinos
            allOptionsSelectDestino=respuestaJson.destinos;
            allOptionsSelectDestino.forEach((destino)=>{
              /*$option = document.createElement("option");
              let optionText = document.createTextNode(destino.destino);
              $option.appendChild(optionText);
              $option.setAttribute("value", destino.id_destino);
              $option.setAttribute("data-id_responsable", destino.id_responsable);
              $selectDestino.appendChild($option);
              $selectCuentaRegistrarNuevoMovimiento.appendChild($option);*/

              const $option = document.createElement("option");
              const optionText1 = document.createTextNode(destino.destino);
              $option.appendChild(optionText1);
              $option.setAttribute("value", destino.id_destino);
              $option.setAttribute("data-id_responsable", destino.id_responsable);
              $selectDestino.appendChild($option);
            })
            
            $($selectDestino).select2({
              placeholder: "Seleccione...",
              //allowClear: true
            })
            /*allOptionsSelectDestino = $($selectDestino).find('option');
            console.log(allOptionsSelectDestino);*/

          }
        });
      }

      function getCtacte(force){
        let desde=$("#desde").val()
        let hasta=$("#hasta").val()

        if(desde>hasta){
          alert("La fecha desde no puede ser mayor a la fecha hasta")
        }else{
          var $selectCuenta = $('#cuenta');
          var selectedIndex = $selectCuenta.prop('selectedIndex');
          var selectedOption = $selectCuenta.find('option').eq(selectedIndex);

          //var id_cuenta=$selectCuenta.val()
          console.log(selectedOption.data("id"))
          var id_cuenta=selectedOption.data("id")
          var tipo=selectedOption.data("tipo")
          var tipo_aumento_extra=selectedOption.data("tipo_aumento_extra")
          var valor_extra=selectedOption.data("valor_extra")

          if(id_cuenta===undefined){
            $("#btnImprimir").addClass("disabled d-none")
            $("#btnExportar").addClass("disabled d-none")
          }else{
            $("#btnImprimir").removeClass("disabled d-none")
            $("#btnExportar").removeClass("disabled d-none")
          }

          let id_deposito=$("#id_deposito").val()
          let id_deposito_usuario=$("#id_deposito_usuario").val()
          if(id_deposito==undefined && id_deposito_usuario>0){
            id_deposito=id_deposito_usuario
          }
          
          let hayCambioEnLosDatos=0;
          if(desde_aux!==desde || hasta_aux!==hasta || id_cuenta_aux!==id_cuenta || JSON.stringify(id_deposito_aux) !== JSON.stringify(id_deposito)){
            hayCambioEnLosDatos=1;
          }

          if(force==1 || hayCambioEnLosDatos==1){
            tablaCtaCte.DataTable().destroy();
            tablaCtaCte.DataTable({
              "ajax": {
                "url" : "./models/administrar_cta_cte.php?accion=getCtacte&desde="+desde+"&hasta="+hasta+"&id_cuenta="+id_cuenta+"&id_deposito="+id_deposito+"&tipo="+tipo+"&tipo_aumento_extra="+tipo_aumento_extra+"&valor_extra="+valor_extra,
                "dataSrc": "",
              },
              "dom": "rtip",
              "ordering": false,
              "paginate": false,
              "responsive": true,
              "columns":[
                {"data": "fecha_hora_formatted",className: "dt-body-right"},
                //{"data": "id_carga"},
                {render: function(data, type, full, meta) {
                  if(full.id_carga!=undefined){
                    return "C #"+full.id_carga;
                  }else if(full.id_movimiento!=undefined){
                    let id_movimiento=full.id_movimiento;

                    $btnEditar=""
                    $btnEliminar=""
                    if(id_perfil==1){
                      $btnEditar=` <span class='btn btn-sm btn-success btnEditar px-2 py-1' title="Editar" data-id='${id_movimiento}'><i class='fa fa-edit'></i></span>`
                      $btnEliminar=` <span class='btn btn-sm btn-danger btnBorrar px-2 py-1' title="Eliminar" data-id='${id_movimiento}'><i class='fa fa-trash-o'></i></span>`
                    }
                    return "M #"+id_movimiento+$btnEditar+$btnEliminar;
                  }else{
                    return full.descripcion
                  }
                }},
                {
                  render: function(data, type, full, meta) {
                    //return formatCurrency(full.monto);
                    return formatCurrency(full.debe);
                  },
                  className: "dt-body-right",
                },
                {
                  render: function(data, type, full, meta) {
                    //return formatNumber2Decimal(full.total_kilos);
                    return formatCurrency(full.haber);
                  },
                  className: "dt-body-right",
                },
                {render: function(data, type, full, meta) {
                  return full.saldo;
                  //return formatCurrency(full.saldo);
                }},
                //{"data": "origen"},
                /*{render: function(data, type, full, meta) {
                  if(full.origen==undefined){
                    return "";
                  }else{
                    return full.origen
                  }
                }},
                {
                  render: function(data, type, full, meta) {
                    return ()=>{
                      if(full.chofer==undefined){
                        return "";
                      }else{
                        let datos_adicionales_chofer=""
                        if(full.datos_adicionales_chofer!=""){
                          datos_adicionales_chofer=" ("+full.datos_adicionales_chofer+")"
                        }
                        return full.chofer+datos_adicionales_chofer;
                      }
                    };
                  }
                },*/
                //{"data":"detalle_productos"}
                //{"data":"descripcion"}
              ],
              "columnDefs": [
                {
                  //"targets": [1,4,5],
                  "targets": [1,3,4],
                  "className": 'text-right'
                }
              ],
              "language":  idiomaEsp,
              drawCallback: function(settings) {
                if(settings.json){
                  let suma_debe=0;
                  let suma_haber=0;
                  settings.json.forEach(row => {
                    suma_debe+=parseFloat(row.debe)
                    suma_haber+=parseFloat(row.haber)
                  });
                  // Update footer
                  var api = this.api();
                  $(api.column(2).footer()).html(formatCurrency(suma_debe));
                  $(api.column(3).footer()).html(formatCurrency(suma_haber));
                  //$(api.column(4).footer()).html(formatCurrency(suma_debe-suma_haber));

                  var columnToCheck = 4; // Índice de la columna que deseas verificar (comienza en 0)
                  tablaCtaCte.find('tr').each(function() {
                    var $cell = $(this).find('td').eq(columnToCheck);
                    var value = parseFloat($cell.text());
                    if (value < 0) {
                      $cell.css('color', 'red');
                    }
                    $cell.text(formatCurrency(value))
                  });
                }
              },
              initComplete: function(settings, json){
                $('[title]').tooltip();
              }
            });

            desde_aux=desde;
            hasta_aux=hasta;
            id_cuenta_aux=id_cuenta;
            id_deposito_aux=id_deposito;
          }
        }
      }

      // Add event listener for opening and closing details
      $(document).on('click', '#tablaCtaCte tbody td:first-child', function() {
        var tr = $(this).closest('tr');
        //var tr = $(this);
        //console.log(tablaCtaCte);
        var row = tablaCtaCte.DataTable().row(tr);

        if (row.child.isShown()) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        } else {
          // Open this row
          row.child(format(row.data())).show();
          tr.next('tr').addClass('child');
          tr.addClass('shown');
        }
      });

      function format(d) {
        // `d` is the original data object for the row
        console.log(d);
        if(d.id_movimiento!=undefined){
          return `<table class="child" border="0" style="padding-left:50px;">
          <tr>
              <td>Detalle:</td>
              <td>${d.descripcion}</td>
            </tr>
            <tr>
              <td>Usuario creador:</td>
              <td>${d.usuario_creador ? d.usuario_creador : ''}</td>
            </tr>
            <tr>
              <td>Fecha y hora creacion:</td>
              <td>${d.fecha_hora_alta_formatted ? d.fecha_hora_alta_formatted : ''}</td>
            </tr>
            <tr>
              <td>Usuario ultima modificacion:</td>
              <td>${d.usuario_ultima_modificacion ? d.usuario_ultima_modificacion : ''}</td>
            </tr>
            <tr>
              <td>Fecha y hora ultima modificacion:</td>
              <td>${d.fecha_hora_ultima_modificacion_formatted ? d.fecha_hora_ultima_modificacion_formatted : ''}</td>
            </tr>
          </table>`;
        }else if(d.id_carga!=undefined){
          return `<table class="child" border="0" style="padding-left:50px;">
            <tr>
              <td>Origen:</td>
              <td>${d.origen ? d.origen : ''}</td>
            </tr>
            <tr>
              <td>Chofer:</td>
              <td>${d.chofer ? d.chofer : ''}</td>
            </tr>
            <tr>
              <td>Usuario creador:</td>
              <td>${d.usuario}</td>
            </tr>
          </table>`;
        }else{
          return '';
        }
      }

      $("#btnNuevo").click(function(){
        $("#formAdmin").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Alta de movimiento en Cta. Cte.");

        let cuenta=$("#cuenta").val();
        if(cuenta!=""){
          $('#cuenta_registrar').val(cuenta).change();
        }else{
          $('#cuenta_registrar').val('').change();
        }
        $('#tipo_movimiento').val('').change();

        let modal=$('#modalCRUDadmin')
        modal.modal('show');
        modal.on('shown.bs.modal', function (e) {
          document.getElementById("fecha_hora").focus();
        })
        accion = "addMovimiento";
      });

      $('#formAdmin').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
        let id_movimiento = $.trim($('#id_movimiento').html());

        let fecha_hora = $.trim($('#fecha_hora').val());
        let cuenta_registrar = $.trim($('#cuenta_registrar').val());
        let ex=cuenta_registrar.split("-")
        let tipo=ex[0]
        let id=ex[1]
        if(tipo=="responsable"){
          id_deposito="null";
          id_responsable=id;
        }else{
          id_deposito=id;
          id_responsable="null";
        }
        let tipo_movimiento = $.trim($('#tipo_movimiento').val());
        
        let monto = $.trim($('#monto').val());
        let descripcion = $.trim($('#descripcion').val());

        $.ajax({
          url: "models/administrar_cta_cte.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, fecha_hora:fecha_hora, id_deposito:id_deposito, id_responsable:id_responsable, tipo_movimiento:tipo_movimiento, monto:monto, descripcion:descripcion, id_movimiento:id_movimiento},
          success: function(respuesta) {
            respuestaJson = JSON.parse(respuesta);
            if(respuestaJson.ok=="1"){
              //tablaCtaCte.ajax.reload(null, false);
              $('#modalCRUDadmin').modal('hide');
              swal({
                icon: 'success',
                title: 'Accion realizada correctamente'
              });
              getCtacte(forece=1)
            }else{
              swal({
                icon: 'error',
                title: 'La accion ha fallado'
              });
            }
          }
        });
      });

      $(document).on("click", ".btnEditar", function(){
        let id_movimiento = $(this).data('id');

        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar movimiento en Cta. Cte. ID "+id_movimiento);
        $('#modalCRUDadmin').modal('show');

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerDatosMovimientoCtaCte');
        datosUpdate.append('id_movimiento', id_movimiento);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_cta_cte.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#procesando').modal('show');
          },
          success: function(response){
            let datosInput = JSON.parse(response);

            let cuenta_registrar
            if(datosInput.id_destino!=null){
              cuenta_registrar="destino-"+datosInput.id_destino
            }
            if(datosInput.id_responsable!=null){
              cuenta_registrar="responsable-"+datosInput.id_responsable
            }

            $("#fecha_hora").val(datosInput.fecha_hora);
            $("#cuenta_registrar").val(cuenta_registrar).change();
            $("#tipo_movimiento").val(datosInput.tipo_movimiento).change();
            $('#monto').val(datosInput.monto)
            $('#descripcion').val(datosInput.descripcion)
            $('#id_movimiento').html(id_movimiento)

            accion = "updateMovimiento";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $(document).on("click", "#btnImprimir, #btnExportar", function(event) {
        event.preventDefault();

        let desde = $('#desde').val();
        let hasta = $('#hasta').val();
        let id_deposito = $('#id_deposito').val();
        var $selectCuenta = $('#cuenta');
        var selectedIndex = $selectCuenta.prop('selectedIndex');
        var selectedOption = $selectCuenta.find('option').eq(selectedIndex);
        var id_cuenta = selectedOption.data("id");
        var tipo = selectedOption.data("tipo");
        var tipo_aumento_extra = selectedOption.data("tipo_aumento_extra");
        var valor_extra = selectedOption.data("valor_extra");

        // Identificar cuál botón fue presionado
        var botonPresionado = $(this).attr("id");

        if (botonPresionado === "btnImprimir") {
          imprimirCuenta(id_cuenta, desde, hasta, id_deposito, tipo, tipo_aumento_extra, valor_extra);
        } else if (botonPresionado === "btnExportar") {
          exportarCuenta(id_cuenta, desde, hasta, id_deposito, tipo, tipo_aumento_extra, valor_extra);
        }
      });

      function imprimirCuenta(id_cuenta, desde, hasta, id_deposito, tipo, tipo_aumento_extra, valor_extra) {
        const url = "./imprimirCuenta.php?id_cuenta=" + id_cuenta + "&desde=" + desde + "&hasta=" + hasta + "&id_deposito=" + id_deposito + "&tipo=" + tipo + "&tipo_aumento_extra=" + tipo_aumento_extra + "&valor_extra=" + valor_extra;
        let win = window.open(url);
        win.focus();
      }

      function exportarCuenta(id_cuenta, desde, hasta, id_deposito, tipo, tipo_aumento_extra, valor_extra) {
        const url = "./models/administrar_cta_cte.php?accion=exportar_excel&id_cuenta=" + id_cuenta + "&desde=" + desde + "&hasta=" + hasta + "&id_deposito=" + id_deposito + "&tipo=" + tipo + "&tipo_aumento_extra=" + tipo_aumento_extra + "&valor_extra=" + valor_extra;

        fetch(url)
          .then(response => {
            if (!response.ok) throw new Error('Error al exportar el Excel');
            return response.blob();
          })
          .then(blob => {
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Cuenta_Exportada.xlsx";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          })
        .catch(error => console.error('Error exportando la cuenta:', error));
      }

      //Borrar
      $(document).on("click", ".btnBorrar", function(){
        let id_movimiento = $(this).data('id');
        swal({
          title: "Estas seguro?",
          text: "Se eliminará el movimiento y no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarMovimiento";
            $.ajax({
              url: "models/administrar_cta_cte.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, id_movimiento:id_movimiento},
              success: function(result) {
                //tablaCargas.row(fila.parents('tr')).remove().draw();
                //tablaCtaCte.ajax.reload(null, false);
                if(result==1){
                  getCtacte(forece=1)
                  swal({
                    icon: 'success',
                    title: 'Movimiento anulado correctamente'
                  })
                }else{
                  swal({
                    icon: 'error',
                    title: result
                  })
                }
              }
            });
          } else {
            swal("El movimiento no se eliminó!");
          }
        })
      });

      document.addEventListener("DOMContentLoaded", function() {
        // Verifica si se cumple la condición para modificar la tabla
        //const perfil = <?= $id_perfil ?>;  // Supongamos que tienes esta variable en tu script
        if (id_perfil!=1) {  // Ajusta esta condición según tus necesidades
          // Obtén las filas y las celdas que deseas mover
          const row1 = document.getElementById('row1');
          const row2 = document.getElementById('row2');

          const desdeLabel = row1.cells[0];
          const desdeInput = row1.cells[1];
          const hastaLabel = row2.cells[0];
          const hastaInput = row2.cells[1];

          // Crea una nueva fila y agrega las celdas
          const newRow = document.createElement('tr');
          newRow.appendChild(desdeLabel.cloneNode(true));
          newRow.appendChild(desdeInput.cloneNode(true));
          newRow.appendChild(hastaLabel.cloneNode(true));
          newRow.appendChild(hastaInput.cloneNode(true));

          // Reemplaza la tabla actual
          const table = document.getElementById('tableFiltros');
          table.innerHTML = '';  // Limpia la tabla
          table.appendChild(newRow);  // Agrega la nueva fila
        }
      });

    </script>
  </body>
</html>