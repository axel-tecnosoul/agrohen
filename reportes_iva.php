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
    <title>MYLA - IVA</title>
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
                    <h3>Reportes IVA</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Reportes IVA</li>
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
                  <div class="card-header">
                    <h5>Filtros</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-3">
                        <label>Proveedor</label>
                        <select id="proveedor" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-3">
                        <label>Origen</label>
                        <select id="origen" class="form-control">
                            <option value="nn">Seleccione...</option>
                            <option value="cd">Caja diaria</option>
                            <option value="oc">Orden de compra</option>
                        </select>
                      </div>
                      <div class="col-3">
                        <label>Fecha desde:</label>
                        <input type="date" id="fdesde" class="form-control">
                      </div>
                      <div class="col-3">
                        <label>Fecha hasta:</label>
                        <input type="date" id="fhasta" class="form-control">
                      </div>
                      <div class="col-4 align-self-end mt-2">
                        <button class="btn btn-success btnBuscar">Buscar</button>
                        <button id="btnExportar" type="button" class="btn btn-outline-primary" data-toggle="modal"><i class="fa fa-file-excel-o"></i> Exportar</button>
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
                      <h5>Reportes IVA</h5>
                      <!--<button id="btnNuevo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>-->
                    </div>
                    <div class="card-body">
                      <div class="table-responsive" id="contTablaListas" >
                        <table class="table table-hover" id="tablaIVA">
                          <thead class="text-center">
                            <tr>
                              <th>Fecha</th>
                              <th class="text-center">Origen</th>
                              <th>Nro. Comprobante</th>
                              <th>Proveedor</th>
                              <th>Total</th>
                              <th>Total impuesto</th>
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

      $(document).ready(function(){
        let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
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
       tablaIVA= $('#tablaIVA').DataTable({
            "ajax": {
                "url" : "./models/administrar_reportes_iva.php?accion=traerReporteIva&id_empresa="+id_empresa,
                "dataSrc": "",
              },
            "columns":[
              {"data": "fecha"},
              {"data": "origen"},
              {"data": "nro_comprobante"},
              {"data": "proveedor"},
              {"data": "total"},
              {"data": "impuesto"}
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
                    url: "./models/administrar_reportes_iva.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de proveedores*/
                        $selectProveedores = document.getElementById('proveedor');
                   
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select proveedor*/
                        respuestaJson.proveedores.forEach((proveedores)=>{
                          let $option = document.createElement("option");
                          let $optionText = document.createTextNode(proveedores.razon_social);
                          $option.appendChild($optionText);
                          $option.setAttribute("value", proveedores.id_proveedor);
                          $selectProveedores.appendChild($option);
                        })

                    }
                });
  }
            

  $(document).on("change", "#origen", function(e){
   
    if($(this).val()==="cd"){
      $("#proveedor").val("");
    }

    /*if($(this).val()==="nn"){
      if($("#proveedor").val()!==""){
        $(this).val("oc");
      }
    }*/
    
  });

  $(document).on("change", "#proveedor", function(e){

    if($("#origen").val()==="cd"){
      $("#origen").val("oc");
    }
    
  });

  $(document).on("click", ".btnBuscar", function(){
      let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
      let fdesde = $("#fdesde").val();
      let fhasta =$("#fhasta").val();
      let id_proveedor = $("#proveedor").val();
      let origen = $("#origen").val();

      if(id_proveedor !== "" && origen =="nn"){
        origen = "oc";
      }

      let filtros={
        fdesde,
        fhasta,
        id_proveedor,
        origen
      }
      
      for(const propiedad in filtros){
        if(filtros[propiedad]==""){
          delete filtros[propiedad];
        }  
      }

      let filtroEnvair = JSON.stringify(filtros);

      if(fdesde !=="" && fhasta ===""){
        swal({
          icon: 'error',
          title: 'Debe seleccionar fecha hasta'
                      });
      }else if(fdesde ==="" && fhasta !==""){
           swal({
          icon: 'error',
          title: 'Debe seleccionar fecha desde'
                      });
      }else{


        $("#tablaIVA").dataTable().fnDestroy();
        tablaIVA= $('#tablaIVA').DataTable({
                "ajax": {
                    "url" : "./models/administrar_reportes_iva.php?accion=traerReporteIvaFiltros&filtros="+filtroEnvair+"&id_empresa="+id_empresa,
                    "dataSrc": "",
                  },
                "columns":[
                  {"data": "fecha"},
                  {"data": "origen"},
                  {"data": "nro_comprobante"},
                  {"data": "proveedor"},
                  {"data": "total"},
                  {"data": "impuesto"}
                  ],
                "language":  idiomaEsp
            });

        /*accion = "traerReporteIvaFiltros";
        $.ajax({
          "url":"./models/administrar_reportes_iva.php",
          "type": "get",
          "datatype": "json",
          "data": {accion: accion, filtros: filtroEnvair, id_empresa: id_empresa},
          success: function(response){

          }
        })*/
      }
      
  })

  function cargarDataTable(fdesde, fhasta){
    

  }

$(document).on('click', '#btnExportar', function(){

    let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
      let fdesde = $("#fdesde").val();
      let fhasta =$("#fhasta").val();
      let id_proveedor = $("#proveedor").val();
      let origen = $("#origen").val();

      if(id_proveedor !== "" && origen =="nn"){
        origen = "oc";
      }

      let filtros={
        fdesde,
        fhasta,
        id_proveedor,
        origen
      }
      
      for(const propiedad in filtros){
        if(filtros[propiedad]==""){
          delete filtros[propiedad];
        }  
      }

      let filtroEnvair = JSON.stringify(filtros);

    window.open(`./models/descarga_archivos.php?accion=exportarReporteIva&id_empresa=${id_empresa}&filtros=${filtroEnvair}`);
    
  });

    </script>
  </body>
</html>