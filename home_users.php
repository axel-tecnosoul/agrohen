<?php 
session_start();
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
    <title>MYLA - Home Usuarios</title>
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
     <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="assets/css/datatables.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/feather-icon.css">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="assets/css/chartist.css">
    <link rel="stylesheet" type="text/css" href="assets/css/prism.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/light-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
  </head>
  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
      <div class="loader bg-white">
        <div class="whirly-loader"> </div>
      </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
      <?php
        include_once('./views/main_header.php');
      ?>
      <!-- Page Header Ends-->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="page-sidebar">
          <?php
            include_once('./views/slideBar.php');
          ?>
        </div>
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-header">
              <div class="row">
                <div class="col">
                  <div class="page-header-left">
                    <h3>Home</h3>
                    <span class="d-none" id="tipo_usuario"><?php echo $_SESSION['tipo']; ?></span>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item">Dashboard</li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <!--<div class="container-fluid">
            <div class="col-xl-12">
                <div class="row">
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="chart-widget-dashboard">
                          <div class="media">
                            <div class="media-body">
                              <h5 class="mt-0 mb-0 f-w-600"><span class="counter" id="totRegiProces"></span></h5>
                              <p>Total registros procesados</p>
                            </div><i data-feather="check-square"></i>
                          </div>
                          <div class="dashboard-chart-container">
                            <div class="small-chart-gradient-1"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="chart-widget-dashboard">
                          <div class="media">
                            <div class="media-body">
                              <h5 class="mt-0 mb-0 f-w-600"><span class="counter" id="totRegRech"></span></h5>
                              <p>Total registros rechazados</p>
                            </div><i data-feather="x-circle"></i>
                          </div>
                          <div class="dashboard-chart-container">
                            <div class="small-chart-gradient-2"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="chart-widget-dashboard">
                          <div class="media">
                            <div class="media-body">
                              <h5 class="mt-0 mb-0 f-w-600"><i data-feather="dollar-sign"></i><span class="counter" id="montoProcesadoG"></span></h5>
                              <p>Monto total procesado</p>
                            </div><i data-feather="dollar-sign"></i>
                          </div>
                          <div class="dashboard-chart-container">
                            <div class="small-chart-gradient-3"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
          </div>
            <div class="row">
              <div class="col-xl-12">
                <div class="card" data-intro="This is University Earning Chart">
                  <div class="card-header university-header">
                    <div class="row">
                      <div class="col-sm-6">
                        <h5>Débitos procesados</h5>
                      </div>
                      <div class="col-sm-6">
                        <div class="pull-right d-flex buttons-right">
                          <div class="right-header">
                            <div class="onhover-dropdown">
                              <button class="btn btn-primary" type="button">Filtrar <span class="pr-0"><i class="fa fa-angle-down"></i></span></button>
                              <div class="onhover-show-div right-header-dropdown"><a class="d-block" href="#" id="anual">Anual</a><a class="d-block" href="#" id="sieteDias">7 dias</a><a class="d-block" href="#" id="trintaDias">30&nbsp;dias</a></div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="show-value-top d-flex pull-right">
                      <div class="value-left d-inline-block">
                        <div class="circle-graph bg-primary d-inline-block m-r-5"></div><span>Procesados</span>
                      </div>
                      <div class="value-right d-inline-block">
                        <div class="circle-graph d-inline-block bg-danger m-r-5"></div><span>Rechazados</span>
                      </div>
                    </div>
                    <div class="chart-block">
                      <canvas id="linecharts-bitcoin"></canvas>
                      <canvas id="sieteDiasGraf" style="display: none;"></canvas>
                      <canvas id="treintaDiasGraf" style="display: none;"></canvas>
                    </div>
                  </div>
                </div>
              </div>-->
          <!-- Container-fluid Ends-->
        </div>
      </div>
      <!--<div class="card col-12">
        <div class="card-body">
                <table class="table-hover table-responsive" id="tablaLotes">
                  <thead class="text-center">
                    <tr>
                      <th class="text-center">Lote</th>
                      <th>Usuario</th>
                      <th>Fecha proceso</th>
                      <th>Cantidad procesados</th>
                      <th>Monto cobrado</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                  </tbody>
                  <tfoot>
                          <tr>
                            <th>Lote</th>
                            <th>Usuario</th>
                            <th>Fecha proceso</th>
                            <th>Cantidad procesados</th>
                            <th>Monto cobrado</th>
                          </tr>
                        </tfoot>
                </table>
              </div>
        </div>-->
        <!-- footer start-->
        
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
    <script src="assets/js/chart/chartjs/chart.min.js"></script>
    <script src="assets/js/chart/chartist/chartist.js"></script>
    <script src="assets/js/chart/knob/knob.min.js"></script>
    <script src="assets/js/chart/knob/knob-chart.js"></script>
    <script src="assets/js/prism/prism.min.js"></script>
    <script src="assets/js/clipboard/clipboard.min.js"></script>
    <script src="assets/js/counter/jquery.waypoints.min.js"></script>
    <script src="assets/js/counter/jquery.counterup.min.js"></script>
    <!--<script src="assets/js/counter/counter-custom.js"></script>-->
    <script src="assets/js/custom-card/custom-card.js"></script>
    <!--<script src="assets/js/notify/bootstrap-notify.min.js"></script>-->
    <script src="assets/js/dashboard/default.js"></script>
    <script src="assets/js/notify/index.js"></script>
    <script src="assets/js/typeahead/handlebars.js"></script>
    <script src="assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="assets/js/typeahead/typeahead.custom.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/height-equal.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <script src="assets/js/typeahead-search/handlebars.js"></script>
    <script src="assets/js/typeahead-search/typeahead-custom.js"></script>
    <script src="assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">
      function iniciarHomeUser(){

        let tipoUsuario = document.getElementById('tipo_usuario').innerHTML;

        traerDatosGraficos('traerTodos', tipoUsuario);
        traerDatosGlobales('traerAnualGlobal', tipoUsuario);
        traerDatosDateTable('dateTable', tipoUsuario);

        btnAnual = document.getElementById('anual');
        btnSieteDias = document.getElementById('sieteDias');
        btnTreintaDias = document.getElementById('trintaDias');


        btnAnual.addEventListener('click', function(){
          /*traerDatosGraficos();*/
          document.getElementById('linecharts-bitcoin').style.display="block";
          document.getElementById('sieteDiasGraf').style.display="none";
          document.getElementById('treintaDiasGraf').style.display="none";
          traerDatosGlobales("traerAnualGlobal", tipoUsuario);
        }, false);



        btnSieteDias.addEventListener('click', function(){         
          traerDatosGraficos("sieteDias", tipoUsuario);
          document.getElementById("linecharts-bitcoin").style.display="none";
          document.getElementById('treintaDiasGraf').style.display="none";
          document.getElementById('sieteDiasGraf').style.display="block";
          traerDatosGlobales("sieteDiasGlobales", tipoUsuario);
        }, false);

        btnTreintaDias.addEventListener('click', function(){
          traerDatosGraficos("treintaDias", tipoUsuario);
          document.getElementById("linecharts-bitcoin").style.display="none";
          document.getElementById('sieteDiasGraf').style.display="none";
          document.getElementById('treintaDiasGraf').style.display="block";
          traerDatosGlobales("treintaDiasGlobales", tipoUsuario);
        }, false);

      }

      function traerDatosGraficos(accion = 'traerTodos', tipoUsuario=""){
        $.ajax({
          url:"models/dashboard.php",
          type: "POST",
          datatype: "json",
          data: {accion: accion, tipo_usuario: tipoUsuario},
          success: function(respuesta){
            //console.log(respuesta);
            cantidadProcesado = [];
            mesProcesado = [];
            datosParse = JSON.parse(respuesta);
            
            iniciarGrafico(datosParse, accion);
          }
        });
      }


      function iniciarGrafico(datos, tipo = "traerTodos"){
         const{
              rechazados,
              procesados
            } = datos;

        var linecharts = {
        labels: procesados.map(item=>item.fecha),
        datasets: [{
            fillColor: "transparent",
            strokeColor: endlessAdminConfig.primary,
            pointColor: endlessAdminConfig.primary,
            data: procesados.map(item=>item.cantidad_procesados)
        },
        {
            fillColor: "transparent",
            strokeColor: "#ff5370",
            pointColor: "#ff5370",
            data: rechazados.map(item=> item.cantidad_rechazados)
        }
        ]
      }


      switch (tipo){
        case 'traerTodos':
        ctx = document.getElementById("linecharts-bitcoin").getContext("2d")
        break
        case 'sieteDias':
         ctx = document.getElementById("sieteDiasGraf").getContext("2d");
         break;
        case 'treintaDias':
          ctx = document.getElementById("treintaDiasGraf").getContext("2d");
      }

      //var ctx = document.getElementById("linecharts-bitcoin").getContext("2d");
      var LineChartDemo = new Chart(ctx).Line(linecharts, {
        pointDotRadius: 2,
        pointDotStrokeWidth: 5,
        pointDotStrokeColor: "#ffffff",
        bezierCurve: false,
        scaleShowVerticalLines: false,
        scaleGridLineColor: "#eeeeee"
      });
      }

      function traerDatosGlobales(accion="traerAnualGlobal", tipoUsuario=""){
         $.ajax({
          url:"models/dashboard.php",
          type: "POST",
          datatype: "json",
          data: {accion: accion, tipo_usuario: tipoUsuario},
          success: function(respuesta){

            datosGlobalesParse = JSON.parse(respuesta);
            
            const{
              procesados,
              rechazados
            } = datosGlobalesParse
            
            cantProces = procesados[0].cantidad_procesados;
            if (cantProces == null) {
              document.getElementById('totRegiProces').innerHTML= "0";
            }else{
              document.getElementById('totRegiProces').innerHTML= cantProces;
            }
            
            document.getElementById('montoProcesadoG').innerHTML=procesados[0].monto_procesados;
            document.getElementById('totRegRech').innerHTML=rechazados[0].cantidad_rechazados;

          }
        });
      }



      function traerDatosDateTable(accion, tipoUsuario){

         tablaLotes= $('#tablaLotes').DataTable({
            "ajax": {
                "url" : `./models/dashboard.php?accion=${accion}&tipo_usuario=${tipoUsuario}`,
                "dataSrc": "",
              },
            "columns":[
              {"data": "lote"},
              {"data": "usuario"},
              {"data": "fecha_procesado"},
              {"data": "reg_proces"},
              {"data": "monto_cobrado"},
            ],
            "language":  {
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
        });

    $('#tablaLotes tfoot th').each( function () {
          var title = $(this).text();
          $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
          console.log("algo");
        } );
    
   tablaLotes.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });



      }

       


      //window.addEventListener('load', iniciarHomeUser, false)
    </script>
  </body>
</html>