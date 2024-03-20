<?php 
  session_start();
  include_once('./../conexion.php');
  date_default_timezone_set("America/Buenos_Aires");
  $hora = date('Hi');
  if (!isset($_SESSION['rowUsers']['id_usuario'])) {
      header("location:./redireccionar.php");
  }
?>
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
    <title>MYLA - Movimientos Caja</title>
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
                    <h3>Movimientos Caja</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Movimientos Caja</li>
                    </ol>
                     <span class="d-none" id="id_empresa"><?php echo $_SESSION['rowUsers']['id_empresa']?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Ajax Generated content for a column start-->
              <div class="col-xl-12">
                <div class="card">
                  <!--<div class="card-header">
                    <h5>Filtrar</h5>
                  </div>-->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-3">
                        <label>Tipo de Movimiento</label>
                        <select id="tipoMovimiento" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-3">
                        <label>Caja:</label>
                        <select id="caja" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-3">
                        <label>Fecha:</label>
                        <input type="date" id="fecha" class="form-control">
                      </div>
                      <div class="col-12 align-self-end mt-2">
                        <button class="btn btn-success btnBuscar">Buscar</button>
                        <button id="btnExportar" type="button" class="btn btn-outline-primary" data-toggle="modal"><i class="fa fa-file-excel-o"></i> Exportar</button>
                      </div>
                      <div class="col-2 align-self-end">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </div>

              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <h5>Movimientos</h5>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive" id="contTablaListas" >
                        <table class="table table-hover" id="tablaMovimientos">
                          <thead class="text-center">
                            <tr>
                              <th class="text-center">#ID</th>
                              <th>Tipo</th>
                              <th>Nro. Comprobante</th>
                              <th>Caja</th>
                              <th>Monto</th>
                              <th>Detalle</th>
                              <th>Adjuntos</th>
                              <th>Fecha</th>
                            </tr>
                          </thead>
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
    <script src="assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/chart/chartjs/chart.min.js"></script>
    <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="assets/js/typeahead/handlebars.js"></script>
    <script src="assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="assets/js/typeahead/typeahead.custom.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <script src="assets/js/typeahead-search/handlebars.js"></script>
    <script src="assets/js/typeahead-search/typeahead-custom.js"></script>
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
       tablaMovimientos= $('#tablaMovimientos').DataTable({
            "ajax": {
                "url" : "./models/administrar_movimientos_caja.php?accion=traerMovimientos",
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_cdd"},
              {"data": "tipo"},
              {"data": "nroComprobante"},
              {"data": "caja"},
              {"data":"monto"},
              {"data": "detalle"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                            if(full.adjunto !==""){
                              return `<a href="./views/adjuntosCajaDiaria/${full.adjunto}" target="_blank">Descargar</a>`
                            }else{
                              return ""
                            }
                        };
                    }
                },
              {"data": "fecha_hora"},
            ],
            "language":  idiomaEsp
        });
      
      });

    function cargarDatosComponentes(){
                let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
                let datosIniciales = new FormData();
                datosIniciales.append('accion', 'traerDatosIniciales');
                datosIniciales.append('id_empresa', id_empresa);
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_movimientos_caja.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de tipo de movimientos*/
                        $selecTmov = document.getElementById("tipoMovimiento");

                        /*Identifico el select de tipo de caja*/
                        $selectTC = document.getElementById("caja");

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select tipo de movimiento*/
                        respuestaJson.tipos_mov.forEach((tMovimientos)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(tMovimientos.tipo_mov);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", tMovimientos.id_tipo_mov);
                            $selecTmov.appendChild($option);
                        })

                        /*Genero los options del select tipo de caja*/
                        respuestaJson.tipos_caja.forEach((tCajas)=>{
                            let $option = document.createElement("option");
                            let optionText = document.createTextNode(tCajas.tipo_caja);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", tCajas.id_tipo_caja);
                            $selectTC.appendChild($option);
                        })

                    }
                });
            }

  $(document).on("click", ".btnBuscar", function(){
      let id_tipo_movimiento = $("#tipoMovimiento").val();
      let id_tipo_caja =$("#caja").val();
      let fecha_hora = $("#fecha").val()
      let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
      accion = "traerMovimientosFiltro";

      let filtros={
        id_tipo_movimiento,
        id_tipo_caja,
        fecha_hora
      }

      for(const propiedad in filtros){
        if(filtros[propiedad]==""){
          delete filtros[propiedad];
        }
        
      }

      let filtroEnvair = JSON.stringify(filtros);
      $("#tablaMovimientos").dataTable().fnDestroy();
      tablaMovimientos= $('#tablaMovimientos').DataTable({
              "ajax": {
                  "url" : "./models/administrar_movimientos_caja.php?accion=traerMovimientosFiltro&id_empresa="+id_empresa+"&filtros="+filtroEnvair,
                  "dataSrc": "",
                },
              "columns":[
                {"data": "id_cdd"},
              {"data": "tipo"},
              {"data": "nroComprobante"},
              {"data": "caja"},
              {"data":"monto"},
              {"data": "detalle"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                            if(full.adjunto !==""){
                              return `<a href="./views/adjuntosCajaDiaria/${full.adjunto}" target="_blank">Descargar</a>`
                            }else{
                              return ""
                            }
                        };
                    }
                },
              {"data": "fecha_hora"},
              ],
              "language":  idiomaEsp
          });

      
                      
         
  })

  $(document).on('click', '#btnExportar', function(){

      let id_tipo_movimiento = $("#tipoMovimiento").val();
      let id_tipo_caja =$("#caja").val();
      let fecha_hora = $("#fecha").val()
      let id_empresa = parseInt(document.getElementById("id_empresa").textContent)

      let filtros={
        id_tipo_movimiento,
        id_tipo_caja,
        fecha_hora
      }

      for(const propiedad in filtros){
        if(filtros[propiedad]==""){
          delete filtros[propiedad];
        }
        
      }

      let filtroEnvair = JSON.stringify(filtros);

    window.open(`./models/descarga_archivos.php?accion=exportarReporteMovCajas&id_empresa=${id_empresa}&filtros=${filtroEnvair}`);
    
  });

    </script>
  </body>
</html>