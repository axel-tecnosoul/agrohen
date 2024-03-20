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
    <title>MYLA - Elementos</title>
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
    <!-- Loader ends--><?php
      include_once('./views/main_header.php');?>
      <!-- Page Header Ends                              -->
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
                    <h3>Elementos</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Elementos</li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Administrar Elementos</h5>
                    <button id="btnNuevoElemento" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nuevo elemento</button>
                  </div><?php
                  //var_dump($_SESSION);?>
                  <div class="card-body">
                    <div class="table-responsive" id="contTablaListas" >
                      <table class="table table-hover" id="tablaElementos">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Imagen</th>
                            <th>Cliente</th>
                            <th>Direccion</th>
                            <th>Descripcion</th>
                            <th>Ubicacion</th>
                            <th>Tecnico Ult. revision</th>
                            <th>Fecha y Hora Ult. revision</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tfoot class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Imagen</th>
                            <th>Cliente</th>
                            <th>Direccion</th>
                            <th>Descripcion</th>
                            <th>Ubicacion</th>
                            <th>Tecnico Ult. revision</th>
                            <th>Fecha y Hora Ult. revision</th>
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
            <span id="id_elemento" class="d-none"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formElementos">
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Cliente:</label>
                    <select class="form-control" id="cliente">
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Direccion</label>
                    <select class="form-control" id="id_direccion_cliente" required>
                      <option value="">Sin resultados</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Descripcion:</label>
                    <input type="text" class="form-control" id="descripcion" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Ubicacion:</label>
                    <input type="text" class="form-control" id="ubicacion" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Rubro:</label>
                    <select class="form-control" id="id_rubro" required>
                      <option value="">Seleccione</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Subrubro:</label>
                    <select class="form-control" id="id_subrubro" required>
                      <option value="">Sin resultados</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group" id="select_imagen">
                    <label for="" class="col-form-label">*Imagen:</label>
                    <input type="file" class="form-control" id="imagen" accept="image/*">
                  </div>
                  <div class="form-group d-none" id="show_imagen">
                    <label for="" class="col-form-label">*Imagen:</label>
                    <div class="row border p-2 ml-2 mr-2">
                      <div class="col-lg-12 mb-2">
                        <div class="text-center">
                          <img src="" class="img-thumbnail w-50" id="imagen_img"></br>
                          <span id="nombre_imagen"></span>
                        </div>
                      </div> 
                      <div class="col-lg-12 text-center mt-1">
                        <a class='btn btn-outline-danger text-danger' id="btnBorrarFoto"><i class='fa fa-trash-o'></i></a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Codigo:</label>
                    <input type="text" class="form-control" id="codigo" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="" class="col-form-label">*Datos adicionales:</label>
                    <textarea class="form-control" id="datos_adicionales"></textarea>
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
    <!-- FINAL MODAL CRUD-->

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
        tablaElementos= $('#tablaElementos').DataTable({
          "ajax": {
              "url" : "./models/administrar_elementos.php?accion=traerElementos",
              "dataSrc": "",
            },
          "columns":[
            {"data": "id_elemento"},
            {
              render: function(data, type, full, meta) {
                return ()=>{
                  let $img = "";
                  if (full.imagen !=""){
                    return `<img src="./views/elementos/${full.imagen}" class="img-thumbnail">`;
                  }else{
                    return ""
                  }
                };
              }
            },
            {"data": "cliente"},
            {"data": "direccion"},
            {"data": "descripcion"},
            {"data": "ubicacion"},
            {"data": "tecnico_ultima_revision"},
            {"data": "fecha_hora_ultima_revision"},
            {"data": "estado"},
            {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            //<button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button><button class='btn btn-primary btnAddMantenimiento' title='Añadir tarea de mantenimiento'><i class='fa fa-wrench'></i></button>
          ],
          "language":  idiomaEsp,
          dom: '<"mr-2 d-inline"l>Bfrtip',
          buttons: [
            {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel-o"></i>',
              titleAttr: 'Excel',
              title:     "Elementos",
              className: 'btn-success',
              exportOptions: {
                columns: [ 0, 2, 2, 4, 5, 6, 7, 8 ],
                //columns: ':not(:last-child)',
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
              title:     "Elementos",
              titleAttr: 'PDF',
              download: 'open',
              className: 'btn-danger',
              exportOptions: {
                columns: [ 0, 2, 2, 4, 5, 6, 7, 8 ],
                //columns: ':not(:last-child)',
              }
            }
          ],
          initComplete: function(){
            var b=1;
            var c=0;
            this.api().columns.adjust().draw();//Columns sin parentesis
            this.api().columns().every(function(){//Columns() con parentesis
              if(b!=1 && b!=2 && b!=10){
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

      });

      function cargarDatosComponentes(){
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesElementos');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_elementos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
              //$('#addProdLocal').modal('hide');
          },
          success: function(respuesta){
            console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            console.log(respuestaJson);

            /*Identifico el select de clientes*/
            $selectClientes= document.getElementById("id_rubro");
            //Genero los options del select de clientes
            respuestaJson.rubros.forEach((rubro)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(rubro.rubro);
                $option.appendChild(optionText);
                $option.setAttribute("value", rubro.id_rubro);
                $selectClientes.appendChild($option);
            });

            /*Identifico el select de clientes*/
            $selectClientes= document.getElementById("cliente");
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

      $(document).on("change", "#id_rubro", function(){
        getSubrubros();
      });

      function getSubrubros(id_rubro,id_subrubro){
        let datosIniciales = new FormData();
        if(id_rubro==undefined){
          id_rubro=document.getElementById("id_rubro").value;
        }
        console.log(id_rubro);
        datosIniciales.append('id_rubro', id_rubro);
        datosIniciales.append('accion', 'traerSubrubros');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_subrubros.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(respuesta){
            console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            console.log(respuestaJson);

            /*Identifico el select de direcciones*/
            $selectSubrubros= document.getElementById("id_subrubro");
            $selectSubrubros.innerHTML = "";
            /*Genero los options del select de direcciones*/
            $option = document.createElement("option");
            let texto="Sin resultados";
            /*if(respuestaJson.length==0){
              texto="El cliente no posee direcciones";
            }*/
            if(respuestaJson.length>0){
              texto="Seleccione un subrubro";
            }
            let optionText = document.createTextNode(texto);
            $option.appendChild(optionText);
            //$option.setAttribute("value", "");
            $selectSubrubros.appendChild($option);
            
            respuestaJson.forEach((subrubro)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(subrubro.subrubro);
                $option.appendChild(optionText);
                $option.setAttribute("value", subrubro.id_subrubro);
                if(id_subrubro==subrubro.id_subrubro){
                  $option.setAttribute("selected", true);
                }
                $selectSubrubros.appendChild($option);
            });

          }
        });
      }

      $(document).on("change", "#cliente", function(){
        getDireccionesCliente();
      });

      function getDireccionesCliente(id_cliente,id_direccion_cliente){
        let datosIniciales = new FormData();
        if(id_cliente==undefined){
          id_cliente=document.getElementById("cliente").value;
        }
        console.log(id_cliente);
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
            console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            console.log(respuestaJson);

            /*Identifico el select de direcciones*/
            $selectDireccionesCliente= document.getElementById("id_direccion_cliente");
            $selectDireccionesCliente.innerHTML = "";
            /*Genero los options del select de direcciones*/
            $option = document.createElement("option");
            let texto="Sin resultados";
            /*if(respuestaJson.length==0){
              texto="El cliente no posee direcciones";
            }*/
            if(respuestaJson.length>0){
              texto="Seleccione una direccion";
            }
            let optionText = document.createTextNode(texto);
            $option.appendChild(optionText);
            //$option.setAttribute("value", "");
            $selectDireccionesCliente.appendChild($option);
            
            respuestaJson.forEach((direccion_cliente)=>{
                $option = document.createElement("option");
                let optionText = document.createTextNode(direccion_cliente.direccion);
                $option.appendChild(optionText);
                $option.setAttribute("value", direccion_cliente.id_direccion);
                if(id_direccion_cliente==direccion_cliente.id_direccion){
                  $option.setAttribute("selected", true);
                }
                $selectDireccionesCliente.appendChild($option);
            });

          }
        });
      }

      $('#formElementos').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        
        let datosEnviar = new FormData();
        
        datosEnviar.append("id_direccion_cliente", $.trim($('#id_direccion_cliente').val()));
        datosEnviar.append("descripcion", $.trim($('#descripcion').val()));
        datosEnviar.append("ubicacion", $.trim($('#ubicacion').val()));
        datosEnviar.append("id_subrubro", $.trim($('#id_subrubro').val()));
        datosEnviar.append("codigo", $.trim($('#codigo').val()));
        datosEnviar.append("datos_adicionales", $.trim($('#datos_adicionales').val()));
        
        datosEnviar.append("id_elemento", $.trim($('#id_elemento').html()));
        datosEnviar.append("accion", accion);

        img = document.getElementById("imagen");
        if (img.files.length > 0) {
          datosEnviar.append("file", img.files[0]);
        }else{
          datosEnviar.append("file", "");
        }

        $.ajax({
          data: datosEnviar,
          url: "models/administrar_elementos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            console.log(data);
            if(data==""){
              tablaElementos.ajax.reload(null, false);

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
              tablaElementos.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });

            }
          })
        }

      })*/

      $("#btnNuevoElemento").click(function(){
          accion = "addElemento"
          $("#formElementos").trigger("reset");
          $(".modal-header").css( "background-color", "#17a2b8");
          $(".modal-header").css( "color", "white" );
          $(".modal-title").text("Nuevo elemento");
          $('#modalCRUD').modal('show');
          $("#select_imagen").removeClass("d-none");
          $("#show_imagen").addClass("d-none");
      });

      $(document).on("click", ".btnEditar", function(){
        fila = $(this).closest("tr");
        let id_elemento = fila.find('td:eq(0)').text();
        $('#id_elemento').html(id_elemento);

        $(".modal-header").css( "background-color", "#22af47");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar Elemento ID "+id_elemento);
        $("#formElementos").trigger("reset");
        $('#modalCRUD').modal('show');

        let datosUpdate = new FormData();
        //datosUpdate.append('accion', 'traerElementoUpdate');
        datosUpdate.append('accion', 'traerElementoUpdate');
        datosUpdate.append('id_elemento', id_elemento);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_elementos.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          success: function(datosProcesados){

            let datosInput = JSON.parse(datosProcesados);
            console.log(datosInput);
            console.log(datosInput[0]);
            let datos=datosInput[0];

            $("#cliente").val(datos.id_cliente);
            getDireccionesCliente(datos.id_cliente,datos.id_direccion_cliente);
            $("#id_direccion_cliente").val(datos.id_direccion_cliente);
            $("#descripcion").val(datos.descripcion);
            $("#ubicacion").val(datos.ubicacion);

            $('#id_rubro').val(datos.subrubro.rubro.id_rubro);

            getSubrubros(datos.subrubro.rubro.id_rubro,datos.subrubro.id_subrubro);
            //$('#id_subrubro').val(datos.id_subrubro);
            $('#codigo').val(datos.codigo);
            $('#datos_adicionales').val(datos.datos_adicionales);

            if(datos.imagen==""){
              $("#select_imagen").removeClass("d-none");
              $("#show_imagen").addClass("d-none");
            }else{
              $("#select_imagen").addClass("d-none");
              $("#show_imagen").removeClass("d-none");
              $("#imagen_img").attr("src","./views/elementos/"+datos.imagen)
              $("#nombre_imagen").html(datos.imagen);

            }
            
            accion = "updateElemento";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $(document).on("click", "#btnBorrarFoto", function(){
        //let id_item = parseInt($('#id_item').text());

        let id_elemento=$('#id_elemento').html();

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado esta imagen, no volveras a verla",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            /*$padre = this.parentElement.parentElement
            $padre.classList.add("d-none");*/
            $.ajax({
              url: "models/administrar_elementos.php",
              type: "POST",
              datatype:"json",
              data:  {accion:"eliminarImagen", id_elemento:id_elemento},
              success: function() {
                $("#select_imagen").removeClass("d-none");
                $("#show_imagen").addClass("d-none");
              }
            });
          } else {
            swal("La imagen no se eliminó!");
          }
        });
      })

      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);           
        id_elemento = parseInt($(this).closest('tr').find('td:eq(0)').text());       

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este elemento, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarElemento";
            $.ajax({
              url: "models/administrar_elementos.php",
              type: "POST",
              datatype:"json",    
              data:  {accion:accion, id_elemento:id_elemento},    
              success: function() {
                tablaElementos.row(fila.parents('tr')).remove().draw();                  
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