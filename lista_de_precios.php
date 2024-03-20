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
    <title>MYLA - Lista de precios</title>
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
                    <h3>Lista de precios</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Lista de precios</li>
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
                  <div class="card-header">
                    <h5>Lista de precios</h5>
                      <div class="row mt-2">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <select class="form-control" id="proveedor" required>
                                <option value="0">Seleccione proveedor...</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-3 col-lg-2">
                            <button id="btnImportar" type="button" class="btn btn-outline-success" data-toggle="modal"><i class="fa fa-cloud-upload"></i> importar</button>
                          </div>
                          <div class="col-3 col-lg-2">
                            <button id="btnExportar" type="button" class="btn btn-outline-primary" data-toggle="modal"><i class="fa fa-file-excel-o"></i> Exportar</button>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" id="contTablaListas" style="display: none;">
                      <table class="table table-hover" id="tablaListas">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th>Proveedor</th>
                            <th>&nbsp;&nbsp;&nbsp;Precio&nbsp;&nbsp;&nbsp;</th>
                            <th>Udad. Medida</th>
                            <th>Ultima actualización</th>
                            <th>Codigo Proveedor</th>
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
 
    <!-- MODAL SUBIR ARCHIVO ITEMS -->
    <div class="modal fade mt-5" id="importarLista" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detalle</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
        <div class="modal-body text-center p-4">
          <div id="dropListas">
            
          </div>
        </div>
      </div>
     </div>
    </div>
    <!-- FIN MODAL SUBIR ARCHIVO ITEMS-->

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
      });

    function cargarDatosComponentes(){
                let datosIniciales = new FormData();
                datosIniciales.append('accion', 'traerDatosIniciales');
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_listas.php",
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

    $(document).on("change", "#proveedor", function(){
      let id_proveedor = $("#proveedor").val();
      $("#contTablaListas").css("display", "block");

      $("#tablaListas").dataTable().fnDestroy();

      tablaListas= $('#tablaListas').DataTable({
            "ajax": {
                "url" : "./models/administrar_listas.php?accion=traerItems&id_proveedor="+id_proveedor,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_item"},
              {"data": "item"},
              {"data": "proveedor"},
              {
                    render: function(data, type, full, meta) {
                        return '<input class="intputPrecio form-control" type="number" value="'+full.precio+'" min="0" step="0.01">';
                    }
                },
               {"data": "unidad_medida"},
              {"data": "ultima_actualizacion"},
              {"data": "codigo_proveedor"},
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            ],
            "language":  idiomaEsp
        });


    });

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este item, no volveras a verlo para este proveedor",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarItemLista";
                        $.ajax({
                                url: "models/administrar_listas.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_item:id_item},    
                                success: function() {
                                    tablaListas.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 });

 
  /*SUBIR ARCHIVO ITEMS*/
      
  $(document).on('click', '#btnImportar', function(){
    let id_proveedor = parseInt($("#proveedor").val());
    if (id_proveedor > 0) {
      $('#importarLista').modal('show');
      $(".modal-header").css( "background-color", "#17a2b8");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Importar items");
      $.ajax({
        url: "dropListas.html",
        type: "POST",
        datatype:"json",    
        data:  {},    
        success: function(response) {
          $('#dropListas').html(response);
        }
      });
      }else{
        swal("Proveedor no seleccionado", "Por favor seleccine un proveedor de la lista", "error");
      }
  });

  $(document).on('click', '#btnExportar', function(){
    let id_proveedor = parseInt($("#proveedor").val());
    let nombreProveedor = $("#proveedor option:selected").text();
    console.log(nombreProveedor);
    accion="traerListaExportar"
    if (id_proveedor > 0) {
        $.ajax({
          url: "models/administrar_listas.php",
          type: "POST",
          datatype: "json",
          data: {accion: accion, id_proveedor: id_proveedor},
          success: function(response){

            let respuestaJson= JSON.parse(response);
            if(respuestaJson.length > 0){

              let itemsExportar = JSON.stringify(respuestaJson); 
              window.open('./exportarlista.php?items='+itemsExportar+"&proveedor="+nombreProveedor,'_blank');

            }else{
              swal("No hay datos", "No se encontraron datos para exportar", "error");
            }
          }
        })
      }else{
        swal("Proveedor no seleccionado", "Por favor seleccine un proveedor de la lista", "error");
      }
  });

  /*BOTON PROCESAR ARCHIVO*/
$(document).on("click", "#procesarArchivo", function(){
         if (arrayFiles.length == 0) {
                swal({
                        icon: 'error',
                        title: 'Debe seleccionar una archivo para procesar'
                      });
        }else{
          $('#dropListas').html("");
          $('#importarLista').modal('hide');

          let id_proveedor = parseInt($("#proveedor").val());
          var archivoListas = new FormData();

          for(var i = 0; i < arrayFiles.length; i++) {
                  archivoListas.append('file'+i, arrayFiles[i]);
              };

          archivoListas.append("accion", "importarItems");
          archivoListas.append("id_proveedor", id_proveedor);
          $.ajax({
            data: archivoListas,
            url: './models/administrar_listas.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
                $('#procesando').modal('show');
            },
            success: function(respuesta){
              tablaListas.ajax.reload(null, false);
              /*Convierto en json la respuesta del servidor*/
              let respuestaJson = JSON.parse(respuesta);
              console.log(respuesta);
              console.log(respuestaJson);

              let datos = `Items ingresados: ${respuestaJson[0].ingresados}
                          Items actualizados: ${respuestaJson[0].actualizados}
                          Items no procesados: ${respuestaJson[0].no_procesados}
                          `;
              swal("Archivo procesado exitosamente", datos, "success");

               $('#procesando').modal('hide');
             
            }
          });
            
        }
      })


  $(document).on("keypress", ".intputPrecio", function(e){
      if(e.which == 13) {
          let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());
          let precio = parseFloat($(this).closest('tr').find('input:eq(0)').val());
          let accion = "editarCantidadStock";

          $.ajax({
            url: "models/administrar_listas.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion,id_item:id_item, precio:precio},    
            success: function(data) {
              
             }
            })
        }
  })
    </script>
  </body>
</html>