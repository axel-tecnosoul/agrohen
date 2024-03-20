<?php 
  session_start();
  include_once('./../conexion.php');
  date_default_timezone_set("America/Buenos_Aires");
  $hora = date('Hi');
  if (!isset($_SESSION['rowUsers']['id_usuario'])) {
      header("location:./models/redireccionar.php");
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
    <title>MYLA - Stock</title>
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
                    <h3>Stock</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Stock</li>
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
              <div class="col-xl-12">
                <div class="card">
                  <!--<div class="card-header">
                    <h5>Filtrar</h5>
                  </div>-->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-3">
                        <label>Proveedor</label>
                        <select id="proveedor" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-3">
                        <label>Almacen:</label>
                        <select id="almacen" class="form-control">
                            <option value="">Seleccione...</option>
                        </select>
                      </div>
                      <div class="col-2 align-self-end">
                        <button class="btn btn-success btnBuscar">Buscar</button>
                      </div>
                      <div class="col-2 align-self-end">
                        <button id="btnExportar" type="button" class="btn btn-outline-primary" data-toggle="modal"><i class="fa fa-file-excel-o"></i> Exportar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </div>

              <div class="row">
                <div class="col-xl-3">
                      <div class="card">
                        <div class="card-body ecommerce-icons text-center"><i data-feather="box"></i>
                          <div><span>Total Items</span></div>
                          <h4 class="font-primary mb-0 counter" id="totItems">0</h4>
                        </div>
                      </div>
                 </div>

                 <div class="col-xl-3">
                      <div class="card">
                        <div class="card-body ecommerce-icons text-center"><i data-feather="thumbs-up"></i>
                          <div><span>Cantidad disponible</span></div>
                          <h4 class="font-primary mb-0 counter" id="cantDisp">0</h4>
                        </div>
                      </div>
                 </div>

                 <div class="col-xl-3">
 
                      <div class="card">
                        <div class="card-body ecommerce-icons text-center"><i data-feather="gift"></i>
                          <div><span>Cantidad reservada</span></div>
                          <h4 class="font-primary mb-0 counter" id="cantReserv">0</h4>
                        </div>
                      </div>
                 </div>

                 <div class="col-xl-3">
 
                      <div class="card">
                        <div class="card-body ecommerce-icons text-center"><i data-feather="dollar-sign"></i>
                          <div><span>Total Valorización</span></div>
                          <h4 class="font-primary mb-0 counter" id="totValor">0.00</h4>
                        </div>
                      </div>
                 </div>
              </div>

              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <h5>Stock</h5>
                      <button id="btnConciliar" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-check-square-o"></i> Conciliar Stock</button>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive" id="contTablaListas" >
                        <table class="table table-hover" id="tablaStock">
                          <thead class="text-center">
                            <tr>
                              <th class="text-center">#ID</th>
                              <th>Item</th>
                              <th>Proveedor</th>
                              <th>Almacen</th>
                              <th>Cant. Disp.</th>
                              <th>Cant. Reservada</th>
                              <th>Punto reposición</th>
                              <th>Hash</th>
                              <th>Precio Unitario</th>
                              <th>Ultima actualizac.</th>
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
 
    <!--Modal para CRUD-->
      <!-- MODAL SUBIR ARCHIVO ITEMS -->
    <div class="modal fade mt-5" id="modalConciliar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detalle</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
        <div class="modal-body text-center p-4">
          <div id="dropConciliar">
            
          </div>
        </div>
      </div>
     </div>
    </div>
    <!-- FIN MODAL SUBIR ARCHIVO ITEMS-->
 
    <!-- FINAL MODAL CRUD-->



    <!-- MODAL PROCESANDO ARCHIVO -->
    <div class="modal fade mt-5" id="procesando" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-body text-center p-4">
              <!--<div class="loader-box">-->
                <div class="loader">
                  <div class="line bg-dark"></div>
                    <div class="line bg-dark"></div>
                    <div class="line bg-dark"></div>
                    <div class="line bg-dark"></div>
                </div><p></p>
                Procesando archivo
              <!--</div>-->  
          </div>
        </div>
      </div>
     </div>
    <!-- FIN MODAL PROCESANDO ARCHIVO -->

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
       tablaStock= $('#tablaStock').DataTable({
            "ajax": {
                "url" : "./models/administrar_stock.php?accion=traerItems",
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_item"},
              {"data": "item"},
              {"data": "proveedor"},
              {"data":"almacen"},
              {"data": "cantDisp"},
              {"data": "cantReserv"},
              {"data": "punto_reposicion"},
              {"data": "hash"},
              {"data": "precio_unitario"},
              {"data": "fecha"}
            ],
            "language":  idiomaEsp
        });


      $('#modalConciliar').on('hidden.bs.modal', function (e) {
          
        });
      
      });

    function cargarDatosComponentes(){
                let datosIniciales = new FormData();
                datosIniciales.append('accion', 'traerDatosIniciales');
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_stock.php",
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

                        /*Identifico el select de provincia*/
                        $selecAlmacen= document.getElementById("almacen");


                        
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

                        /*Genero los options del select almacenes*/
                        respuestaJson.almacenes.forEach((almacenes)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(almacenes.almacen);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", almacenes.id_almacen);
                            $selecAlmacen.appendChild($option);
                        });

                        /*Ingreso datos en totalizadores*/
                        console.log(respuestaJson.totalizadores[0].totItem);
                        document.getElementById("totItems").innerText=respuestaJson.totalizadores[0].totItem;
                        document.getElementById("cantDisp").innerText=respuestaJson.totalizadores[0].cantDisp;
                        document.getElementById("cantReserv").innerText=respuestaJson.totalizadores[0].cantReserv;
                        document.getElementById("totValor").innerText=respuestaJson.totalizadores[0].valoracion;

                    }
                });
            }


$(document).on("click", "#btnGuardar", function(){
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
                        title: 'Debe seleccionar proveedor, almacen e items'
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
              tablaStock.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });    

             }
          })
  }
  
})

  $(document).on("click", ".btnBuscar", function(){
      let almacen = $("#almacen").val();
      let proveedor =$("#proveedor").val();

      
        cargarDataTable(almacen, proveedor);

      
  })

  function cargarDataTable(almacen, proveedor){
    
    $("#tablaStock").dataTable().fnDestroy();

    tablaStock= $('#tablaStock').DataTable({
            "ajax": {
                "url" : "./models/administrar_stock.php?accion=traerStockFiltro&almacen="+almacen+"&proveedor="+proveedor,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_item"},
              {"data": "item"},
              {"data": "proveedor"},
              {"data":"almacen"},
              {"data": "cantDisp"},
              {"data": "cantReserv"},
              {"data": "punto_reposicion"},
              {"data": "hash"},
              {"data": "precio_unitario"},
              {"data": "fecha"}
              ],
            "language":  idiomaEsp
        });

    actualizarTotalizadores(almacen, proveedor);
  }

 function actualizarTotalizadores(almacen, proveedor){

    let accion= "actualizarTotalizadores";
    $.ajax({
              url: "models/administrar_stock.php",
              type: "POST",
              datatype:"json",    
              data:  {accion: accion, almacen:almacen, proveedor: proveedor},    
              success: function(response) {

                let respuestaJson = JSON.parse(response);

                 /*Ingreso datos en totalizadores*/

                 let totItem = respuestaJson[0].totItem;
                 let cantDisp = respuestaJson[0].cantDisp;
                 let cantReserv = respuestaJson[0].cantReserv;
                 let valoracion = respuestaJson[0].valoracion;

                 if(totItem == null)totItem=0;
                 if(cantDisp == null)cantDisp=0;
                 if(cantReserv == null)cantReserv=0;

                document.getElementById("totItems").innerText=totItem;
                document.getElementById("cantDisp").innerText=cantDisp;
                document.getElementById("cantReserv").innerText=cantReserv;
                document.getElementById("totValor").innerText=valoracion;
                
               }
            })
 }


$(document).on('click', '#btnExportar', function(){
    let almacen = $("#almacen").val();
    let proveedor =$("#proveedor").val();
    accion="traerStockFiltro";
    $.ajax({
          url: "models/administrar_stock.php",
          type: "GET",
          datatype: "json",
          data: {accion: accion, proveedor: proveedor, almacen: almacen},
          success: function(response){

            let respuestaJson= JSON.parse(response);
            if(respuestaJson.length > 0){

              let stockExportar = JSON.stringify(respuestaJson); 
              window.open('./exportarstock.php?stock='+stockExportar,+'_blank');

            }else{
              swal("No hay datos", "No se encontraron datos para exportar", "error");
            }
          }
        })
  });

  $(document).on("click", "#btnConciliar", function(){
      $('#modalConciliar').modal('show');
      $('#importarLista').modal('show');
      $(".modal-header").css( "background-color", "#17a2b8");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Importar items");
      $.ajax({
        url: "dropConciliar.html",
        type: "POST",
        datatype:"json",    
        data:  {},    
        success: function(response) {
          $('#dropConciliar').html(response);
        }
      });
  });

    /*BOTON PROCESAR ARCHIVO*/
$(document).on("click", "#procesarArchivo", function(){
         if (arrayFiles.length == 0) {
                swal({
                        icon: 'error',
                        title: 'Debe seleccionar una archivo para procesar'
                      });
        }else{
          $('#dropConciliar').html("");
          $('#modalConciliar').modal('hide');

          var archivoConciliar = new FormData();

          for(var i = 0; i < arrayFiles.length; i++) {
                  archivoConciliar.append('file'+i, arrayFiles[i]);
              };

          archivoConciliar.append("accion", "conciliar");
          $.ajax({
            data: archivoConciliar,
            url: './models/administrar_stock.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
                $('#procesando').modal('show');
            },
            success: function(respuesta){
              //tablaListas.ajax.reload(null, false);
              /*Convierto en json la respuesta del servidor*/
              //let respuestaJson = JSON.parse(respuesta);
              /*console.log(respuesta);
              console.log(respuestaJson);

              let datos = `Items ingresados: ${respuestaJson[0].ingresados}
                          Items actualizados: ${respuestaJson[0].actualizados}
                          Items no procesados: ${respuestaJson[0].no_procesados}
                          `;
              swal("Archivo procesado exitosamente", datos, "success");*/

               $('#procesando').modal('hide');
               swal("Archivo procesado exitosamente", "", "success");
               location.reload();

             
            }
          });
            
        }
      })

    </script>
  </body>
</html>