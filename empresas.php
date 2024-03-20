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
    <title>MYLA - ABM Empresas</title>
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
                    <h3>ABM Empresas</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">ABM Empresas</li>
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
                    <h5>Administrar Empresas</h5>
                     <button id="btnNuevo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 
                      <!--<button id="btnImportar" type="button" class="btn btn-outline-success mt-2" data-toggle="modal"><i class="fa fa-cloud-upload"></i> importar</button>-->

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaEmpresas">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Empresa</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Fecha Alta</th>
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
     <!--Modal para CRUD-->
      <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_empresa" class="d-none"></span>
                      <span id="id_empresa" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formEmpresas">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Nombre:</label>
                              <input type="text" class="form-control" id="nombre" required>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Cuit:</label>
                              <input type="text" class="form-control" id="cuit" required>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Domicilio:</label>
                              <input type="text" class="form-control" id="domicilio" required>
                            </div>
                          </div>    
                      </div>
                      <div class="row"> 
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Localidad:</label>
                              <input type="text" class="form-control" id="localidad" required>
                            </div>
                          </div>
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Provincia</label>
                              <select class="form-control" id="provincia" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Pais:</label>
                              <input type="text" class="form-control" id="pais" required readonly value="Argentina">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                         <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Teléfono:</label>
                              <input type="number" class="form-control" id="telefono" required>
                            </div>
                          </div> 
                        <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Email:</label>
                              <input type="email" class="form-control" id="email" required>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                              <label for="" class="col-form-label">Logo:</label>
                              <input class="form-control" type="file" id="imagen" accept="image/*">
                            </div>
                          </div>                         
                      </div>
                      <div class="row">
                        <div class="col-lg-4 mb-2"><button id="btnAddAdjuntos" class="btn btn-secondary"> Agregar adjuntos</button></div>
                        <div class="col-lg-12 d-none" id="masAdjuntos">
                          <div id="dropMasArchivos"></div>
                        </div>
                      </div>
                      <div class="row border p-2 ml-2 mr-2" id="adjuntos">
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


    <!-- MODAL SUBIR ARCHIVO CUENTAS DADAS DE BAJA -->
    <div class="modal fade mt-5" id="importarArticulos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detalle</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
        <div class="modal-body text-center p-4">
          <div id="dropArticulos">
            
          </div>
        </div>
      </div>
     </div>
    </div>
    <!-- FIN MODAL SUBIR ARCHIVO CUENTAS DADAS DE BAJA-->

    <!-- MODAL VER FOTO -->
    <div class="modal fade mt-5" id="verFoto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Fotos producto</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
        <div class="modal-body text-center p-4">
          <div id="verFotoProducto">
            
          </div>
        </div>
      </div>
     </div>
    </div>
    <!-- FIN MODAL VER FOTO-->

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
       tablaEmpresas= $('#tablaEmpresas').DataTable({
            "ajax": {
                "url" : "./models/administrar_empresas.php?accion=traerEmpresas",
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_empresa"},
              {"data": "nombre"},
              {"data": "direccion"},
              {"data": "telefono"},
              {"data": "activo"},
              {"data": "fecha_alta"},
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            ],
            "language":  idiomaEsp
        });
       cargarDatosComponentes();

       //

        $('#modalCRUD').on('hidden.bs.modal', function (e) {
          document.getElementById('dropMasArchivos').innerHTML="";
          document.getElementById('masAdjuntos').classList.toggle("d-none");
        });

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
                    url: "./models/administrar_empresas.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de provincia*/
                        $selectProvincias= document.getElementById("provincia");
                     
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);


                        /*Genero los options del select provincia*/
                        respuestaJson.provincias.forEach((provincia)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(provincia.provincia);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", provincia.id_provincia);
                            $selectProvincias.appendChild($option);
                        })

                    }
                });
            }
    $(document).on("click", ".btnEditar", function(){
      $(".modal-header").css( "background-color", "#22af47");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Editar Empresa");
      $("#formEmpresas").trigger("reset");
      $('#modalCRUD').modal('show');      
      fila = $(this).closest("tr");
      let id_empresa = fila.find('td:eq(0)').text();    

      let datosUpdate = new FormData();
      datosUpdate.append('accion', 'traerEmpresaUpdate');
      datosUpdate.append('id_empresa', id_empresa);
      $.ajax({
            data: datosUpdate,
            url: './models/administrar_empresas.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(datosProcesados){

              let datosInput = JSON.parse(datosProcesados);
              $("#nombre").val(datosInput.datos_empresas[0].nombre);
              $("#cuit").val(datosInput.datos_empresas[0].cuit);
              $("#domicilio").val(datosInput.datos_empresas[0].domicilio);
              $("#localidad").val(datosInput.datos_empresas[0].localidad);
              $("#provincia").val(datosInput.datos_empresas[0].provincia);
              $("#pais").val(datosInput.datos_empresas[0].pais);
              $("#telefono").val(datosInput.datos_empresas[0].telefono);
              $("#email").val(datosInput.datos_empresas[0].email);
              $('#id_empresa').html(id_empresa);
              if(datosInput.adjuntosEmpresas.length > 0){
                
                let url_adjuntos = "./views/empresas/";
                let $fragment = document.createDocumentFragment();
                let $divAdjuntos = document.getElementById('adjuntos');

                $divAdjuntos.innerHTML="";

                datosInput.adjuntosEmpresas.forEach((adjuntos)=>{

                    let extension = adjuntos.archivos.split(".");

                    if(extension[1] == 'jpg' || extension[1] == 'jpeg' || extension[1] == 'png' || extension[1] == 'gif'){
                      $divImagen = `<div class="col-lg-4 mb-2">
                                    <div class="col-lg-12">
                                      <div class="text-center">
                                        <img src="./views/empresas/${adjuntos.archivos}" class="img-thumbnail w-50"></br>
                                        ${adjuntos.archivos}
                                      </div>
                                    </div> 
                                    <div class="col-lg-12 text-center mt-1">
                                      <a class='btn btn-outline-danger btnBorrarFoto text-danger' data-id="${adjuntos.id_adjunto}" data-name="${adjuntos.archivos}"><i class='fa fa-trash-o'></i></a>
                                    </div>
                                  </div>`;
                      }else{
                        $divImagen = `<div class="col-lg-4 mb-2">
                                    <div class="col-lg-12">
                                      <div class="text-center">
                                        <img src="./assets/images/imgAdjuntos.jpg" class="img-thumbnail w-50" id=""></br>
                                        ${adjuntos.archivos}
                                      </div>
                                    </div> 
                                    <div class="col-lg-12 text-center mt-1">
                                      <a class='btn btn-outline-danger btnBorrarFoto text-danger' data-id="${adjuntos.id_adjunto}" data-name="${adjuntos.archivos}"><i class='fa fa-trash-o'></i></a>
                                    </div>
                                  </div>`;
                      }
                 $divAdjuntos.innerHTML+=$divImagen
                });

              }else{
                $('#imgUpdate').attr("src", "");
                $('#imgUpdate').addClass("d-none");
                $('.btnBorrarFoto').addClass("d-none");
              }
              accion = "updateEmpresa";
            }
          });

      $('#modalCRUD').modal('show');
    });

    $('#formEmpresas').submit(function(e){                         
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        
        
        let datosEnviar = new FormData();
        datosEnviar.append("nombre", $.trim($('#nombre').val()))
        datosEnviar.append("cuit", $.trim($('#cuit').val()));
        datosEnviar.append("domicilio", $.trim($('#domicilio').val()));
        datosEnviar.append("localidad", $.trim($('#localidad').val()));
        datosEnviar.append("provincia", $.trim($('#provincia').val()));
        datosEnviar.append("pais", $.trim($('#pais').val()));
        datosEnviar.append("telefono", $.trim($('#telefono').val()));
        datosEnviar.append("email", $.trim($('#email').val()));
        datosEnviar.append("id_empresa", $.trim($('#id_empresa').html()));
        datosEnviar.append("accion", accion);
        img = document.getElementById("imagen");

        if (img.files.length > 0) {
          datosEnviar.append("file", img.files[0]);
        }else{
          datosEnviar.append("file", "");
        }

        if(typeof arrayFiles !== 'undefined'){
          let cantArchivos = 0;
          for(let i = 0; i < arrayFiles.length; i++) {
                      datosEnviar.append('file'+i, arrayFiles[i]);
                      cantArchivos++;
                  };
              datosEnviar.append('cantAdjuntos', cantArchivos);
        }else{
          let arrayFiles = "";
        }
            $.ajax({
              data: datosEnviar,
              url: "models/administrar_empresas.php",
              method: "post",
              cache: false,
              contentType: false,
              processData: false,     
              success: function(data) {
                tablaEmpresas.ajax.reload(null, false);
              }
            });             
        $('#modalCRUD').modal('hide'); 
        swal({
                      icon: 'success',
                      title: 'Accion realizada correctamente'
                    });                               
    });


    $("#btnNuevo").click(function(){
      accion = "addEmpresa"
      $("#formEmpresas").trigger("reset");
      $(".modal-header").css( "background-color", "#17a2b8");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Nueva empresa");
      $('#modalCRUD').modal('show');
      $('#adjuntos').html("");
      $('#imgUpdate').attr("src", "");
      $('#imgUpdate').addClass("d-none");
      $('.btnBorrarFoto').addClass("d-none");    
    });

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    id_empresa = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado esta empresa, no volveras a verla",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarEmpresa";
                        $.ajax({
                                url: "models/administrar_empresas.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_empresa:id_empresa},    
                                success: function() {
                                    tablaEmpresas.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 }); 




/*SUBIR ARCHIVO ARTICULOS*/
      
  $(document).on('click', '#btnImportar', function(){
    $('#importarArticulos').modal('show');
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Importar Articulos");
    $.ajax({
      url: "dropMasAdjuntosEmpresas.html",
      type: "POST",
      datatype:"json",    
      data:  {},    
      success: function(response) {
        $('#dropMasArchivos').html(response);
      }
    });
  })

/*BOTTON AGREGAR ADJUNTOS*/

$(document).on('click', '#btnAddAdjuntos', function(e){
  e.preventDefault();
  $rowMasAdjuntos = document.getElementById("masAdjuntos");
  $rowMasAdjuntos.classList.toggle("d-none");
  $.ajax({
      url: "dropMasAdjuntosEmpresas.html",
      type: "POST",
      datatype:"json",    
      data:  {},    
      success: function(response) {
        $('#dropMasArchivos').html(response);
      }
    });
})


/*PARA BORRARRRRRRRRRRRRRRRRRRRRRRRRRRRRRR*/

//imagen
$(document).on("click", "#verImagen", function(e){
  let imagen = document.getElementById("imagen");
  let accion = "imagenes";

  if (imagen.files.length > 0) {
    let datosEnviar = new FormData();
    datosEnviar.append("file", imagen.files[0]);
    mini = document.getElementById("miniatura");
    let reader = new FileReader();
    reader.onload=(function(aImg){return function(e){aImg.src = e.target.result;};})(mini);
    reader.readAsDataURL(imagen.files[0]);
    document.getElementById("miniatura").src=imagen.value;
    $.ajax({
          data: datosEnviar,
          url: "models/pruebaImagenes.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,    
          success: function(data) {
           
           }
        });
    
  }

})

$(document).on("click", ".btnBorrarFoto", function(){
    //let id_item = parseInt($('#id_item').text());

    let id_empresa = $.trim($('#id_empresa').html());
    let id_adjunto = this.getAttribute("data-id");
    let nombreArchivo = this.getAttribute("data-name");

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este archivo, no volveras a verla",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $padre = this.parentElement.parentElement
                        $padre.classList.add("d-none");
                        accion = "eliminarArchivos";
                        $.ajax({
                                url: "models/administrar_empresas.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_adjunto:id_adjunto, nombreArchivo: nombreArchivo, id_empresa: id_empresa},    
                                success: function() {
                                   //$(".btnBorrarFoto").addClass("d-none"); 
                                   //$("#imgUpdate").addClass("d-none");
                                   //$("#imgUpdate").attr("src", "");              
                                }
                            }); 
                    } else {
                        swal("El archivo no se eliminó!");
                    }
                });

})
$(document).on("click", ".btnVer", function(){
  
  let fila = $(this);           
  let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text()); 
  let accion = "trerFotosProducto";
  
  $.ajax({
    url: "models/administrar_empresas.php",
    type: "POST",
    datatype:"json",    
    data:  {accion:accion, id_item:id_item},    
    success: function(response) {

      $(".modal-header").css( "background-color", "#ffc107");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Fotos producto");

      $("#verFoto").modal("show");

      let $contFoto = document.getElementById("verFotoProducto");
      $contFoto.innerHTML= "";
      let urlDirectorio = "./views/img_items/";
      let respuestaJson = JSON.parse(response);

      if (respuestaJson[0].fotos != "") {

        let nombreFoto = respuestaJson[0].fotos;
        let src = urlDirectorio+nombreFoto;

        let $img= document.createElement("img");
        $img.setAttribute("src", src);
        $img.classList.add("img-fluid");
        $contFoto.appendChild($img);

      }else{
        $contFoto.innerHTML="No se encontraron datos.";
      }

    }
});
});
    </script>
  </body>
</html>