<?php 
  session_start();
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
    <title>MYLA - Cta Cte Clientes</title>
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
                    <h3>Cta Cte Clientes</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Cta Cte Clientes</li>
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
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Administrar cta cte Clientes</h5>
                     <div class="row mt-2">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <select class="form-control" id="clientes" required>
                                <option value="0">Seleccione Cliente...</option>
                              </select>
                            </div>
                          </div>
                      </div>

                  </div>
                  <div class="card-body">
                    <div class="table-responsive d-none" id="contTablaCtaCte">
                      <table class="table table-hover" id="tablaCtaCteCliente">
                        <thead class="text-center">
                          <tr>
                            <th>Proveedor</th>
                            <th>Saldo</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
    </div>
     <!--Modal detalle cuenta corriente-->
      <div class="modal fade" id="detalleCtaCte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_tipo_caja" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>  
                  <div class="modal-body">
                    <div class="table-responsive" id="contTablaOrden" style="">
                      <table class="table table-hover" id="tablaDetalleCtaCte">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Tipo Movimiento</th>
                            <th>Monto</th>
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
  <!-- fin Modal detalle cuenta corriente -->

  <!-- Modal emitir pagos -->
      <div class="modal fade" id="emitirPagos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_caja_diaria" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>   
                  <div class="modal-body">
                    <form id="formEmitirPago">
                      <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Importe:</label>
                              <input type="number" class="form-control" id="importePago" required>
                            </div>
                          </div> 
                      </div>
                      <!--<div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Detalle:</label>
                              <textarea class="form-control" id="detalle"  placeholder="Ingrese comentarios" required></textarea>
                            </div>
                          </div>
                      </div>-->
                    
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Agregar</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                  </div>
                  </form>
              </div>
          </div>
      </div>
    <!-- Fin Modal emitir pagos-->
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
    <!--<script src="assets/js/datatable/datatables/datatable.custom.js"></script>-->
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
                let id_empresa = parseInt(document.getElementById("id_empresa").textContent)
                let datosIniciales = new FormData();
                datosIniciales.append('accion', 'traerDatosIniciales');
                datosIniciales.append('id_empresa', id_empresa);
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_cta_cte_clientes.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                       /*Identifico el select de proveedores*/
                        $selectClientes = document.getElementById('clientes');

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select proveedor*/
                        respuestaJson.clientes.forEach((clientes)=>{
                          let $option = document.createElement("option");
                          let $optionText = document.createTextNode(clientes.cliente);
                          $option.appendChild($optionText);
                          $option.setAttribute("value", clientes.id_cliente);
                          $selectClientes.appendChild($option);
                        })

                    }
                });
            }
   

    $(document).on("change", "#clientes", function(){
      let id_cliente = $(this).val();
      let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
      let $tablaCtaCte = document.getElementById("contTablaCtaCte");
      $tablaCtaCte.classList.remove("d-none");
      $("#tablaCtaCteCliente").dataTable().fnDestroy();
      tablaCtaCteCliente= $('#tablaCtaCteCliente').DataTable({
            "ajax": {
                "url" : "./models/administrar_cta_cte_clientes.php?accion=traerCtacCteClientes&id_empresa="+id_empresa+"&id_cliente="+id_cliente,
                "dataSrc": "",
              },
            "columns":[
              {
                    render: function(data, type, full, meta) {
                        return '<span class="d-none">'+full.id_cliente+'</span><span class="">'+full.cliente+'</span>';
                    }
                },
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          if(full.saldo < 0 ){
                            return '<span class="text-danger">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.saldo)+'</span>'
                          }else{
                             return '<span class="text-success">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.saldo)+'</span>'
                          }
                        };
                    }
                },
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-warning btnVer' title='Ver movimientos'><i class='fa fa-eye'></i></button></div></div>"}
            ],
            "language":  idiomaEsp
        });


    });



$(document).on("click", ".btnVer", function(){
    
    accion = "traerDetalleCtaCte";
    let id_cliente = document.getElementById("clientes").value;

    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Detalle cuenta corriente");
    $('#detalleCtaCte').modal('show');      


    let $tablaDetalleCtaCte = document.getElementById("contTablaCtaCte");
      $tablaDetalleCtaCte.classList.remove("d-none");
      $("#tablaDetalleCtaCte").dataTable().fnDestroy();
      tablaCtaCteCliente= $('#tablaDetalleCtaCte').DataTable({
            "ajax": {
                "url" : "./models/administrar_cta_cte_clientes.php?accion=traerDetalleCtaCte&id_cliente="+id_cliente,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_movimiento"},
              {"data": "tipo"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          if(full.saldo < 0 ){
                            return '<span class="text-danger">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.saldo)+'</span>'
                          }else{
                             return '<span class="text-success">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.saldo)+'</span>'
                          }
                        };
                    }
                },
            ],
            "language":  idiomaEsp
        });
 
 }); 
    </script>
  </body>
</html>