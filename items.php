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
    <title>MYLA - Items</title>
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
                    <h3>Items</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Items</li>
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
                    <h5>Administrar Items</h5>
                    <span class="d-none" id="id_empresa"><?php echo $_SESSION['rowUsers']['id_empresa']?></span>
                     <button id="btnNuevo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 
                      <button id="btnImportar" type="button" class="btn btn-outline-success mt-2" data-toggle="modal"><i class="fa fa-cloud-upload"></i> importar</button> 

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaItems">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Item</th>
                            <th>Imagen</th>
                            <th>Tipo</th>
                            <th>Categoria</th>
                            <th>Punto reposic.</th>
                            <th>U. Medida</th>
                            <th>Activo</th>
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
                      <span id="id_item" class="d-none"></span>
                      <span id="id_itemC" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formItems">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Descripción:</label>
                              <input type="text" class="form-control" id="descripcion_producto" required>
                            </div>
                          </div>
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Categoria:</label>
                              <select class="form-control" id="categoria" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Unidad de medida:</label>
                              <select class="form-control" id="Umedida" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>     
                      </div>
                      <div class="row"> 
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Tipo</label>
                              <select class="form-control" id="tipo" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Punto de reposición:</label>
                              <input type="number" class="form-control" id="preposicion" required>
                            </div>
                          </div>
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Centro de costos</label>
                              <select class="form-control" id="cCostos" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div> 
                      </div>
                      <div class="row">
                        <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Estado</label>
                              <select class="form-control" id="estado" required>
                                <option value="">Seleccione</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                              </select>
                              </div>            
                          </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Link video:</label>
                              <input type="url" class="form-control" id="linkVideo">
                            </div>
                          </div>  
                      </div>
                      <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                              <label for="" class="col-form-label">Imagen:</label>
                              <input class="form-control" type="file" id="imagen" accept="image/*">
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                              <img src="" class="img-thumbnail w-25 d-none" id="imgUpdate">
                            </div>
                        </div> 
                        <div class="col-lg-12 text-center mt-1">
                           <a class='btn btn-outline-danger btnBorrarFoto text-danger'><i class='fa fa-trash-o'></i></a>
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
        let id_empresa = parseInt(document.getElementById("id_empresa").innerText)
        tablaItems= $('#tablaItems').DataTable({
            "ajax": {
                "url" : "./models/administrar_items.php?accion=traerArticulos&id_empresa="+id_empresa,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_item"},
              {"data": "item"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          let $img = "";
                          if (full.imagen !=""){
                             
                             return `<img src="./views/img_items/${full.imagen}" class="img-thumbnail">`;
                          }else{
                            return "vacio"
                          }                               
                          
                        };
                    }
                },
              {"data": "tipo"},
              {"data": "categoria"},
              {"data": "preposicion"},
              {"data": "unidad_medida"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{

                          const estados = {
                            0: "Inactivo",
                            1: "Activo",
                          }

                          $options="";

                          for(key in estados){
                                  if(full.activo == estados[key]){
                                    $options+=`<option selected value="${full.activo}">${estados[key]}</option>`
                                  }else{
                                    $options+=`<option value="${key}">${estados[key]}</option>
                                  `;
                                  }
                                  }
                        $selectInit = `<select class="estado">`;
                        $selectEnd = "</select>";
                        $selectComplete = $selectInit + $options+$selectEnd
      
                          return $selectComplete;
                        };
                    }
                },
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            ],
            "language":  idiomaEsp
        });
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
                let datosIniciales = new FormData();
                let id_empresa = parseInt(document.getElementById("id_empresa").innerText)

                datosIniciales.append('accion', 'traerDatosInicialesItems');
                datosIniciales.append('id_empresa', id_empresa);
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_items.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de provincia
                        $selecAlmacen= document.getElementById("almacen");*/

                        /*Identifico el select de proveedores
                        $selectProveedores = document.getElementById('proveedor');*/

                        /*Identifico el select de categorias*/
                        $selectCategorias = document.getElementById('categoria');

                        /*Identifico el select de unidad de medida*/
                        $selectUmedida = document.getElementById('Umedida');

                        /*Identifico el select tipo de items*/
                        $selectTipo = document.getElementById('tipo');

                        /*Identifico el select centro de costo*/
                        $selectCentroCosto = document.getElementById("cCostos");

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);


                        /*Genero los options del select almacenes
                        respuestaJson.almacenes.forEach((almacenes)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(almacenes.almacen);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", almacenes.id_almacen);
                            $selecAlmacen.appendChild($option);
                        })*/

                        /*Genero los options del select proveedor
                        respuestaJson.proveedores.forEach((proveedores)=>{
                          let $option = document.createElement("option");
                          let $optionText = document.createTextNode(proveedores.razon_social);
                          $option.appendChild($optionText);
                          $option.setAttribute("value", proveedores.id_proveedor);
                          $selectProveedores.appendChild($option);
                        })*/

                        /*Genero los options del select categoria*/
                        respuestaJson.categorias.forEach((categorias)=>{
                          let $optionCat = document.createElement("option");
                          let $optionText = document.createTextNode(categorias.categoria);
                          $optionCat.appendChild($optionText);
                          $optionCat.setAttribute("value", categorias.id_categoria);
                          $selectCategorias.appendChild($optionCat);
                        })

                        /*Genero los options del select Unidad de medida*/
                        respuestaJson.unidad_medida.forEach((unidad_medida)=>{
                          let $optionMedida = document.createElement("option");
                          let $optionText = document.createTextNode(unidad_medida.unidad_medida);
                          $optionMedida.appendChild($optionText);
                          $optionMedida.setAttribute("value", unidad_medida.id_medida);
                          $selectUmedida.appendChild($optionMedida);
                        });

                        /*Genero los options del select Tipo de items*/

                        respuestaJson.tipo_items.forEach((tipo_items)=>{
                          let $optionTipo = document.createElement("option");
                          let $optionTipoText = document.createTextNode(tipo_items.nombre_tipo);
                          $optionTipo.appendChild($optionTipoText);
                          $optionTipo.setAttribute("value", tipo_items.id_tipo);
                          $selectTipo.appendChild($optionTipo);
                        });

                        /*Genero los options del select Centro Costos*/

                        respuestaJson.centroCostos.forEach((cCosto)=>{
                          let $option = document.createElement("option");
                          let optionText= document.createTextNode(cCosto.nombreCC);
                          $option.appendChild(optionText);
                          $option.setAttribute("value", cCosto.id_cc);
                          $selectCentroCosto.appendChild($option);
                        });

                    }
                });
            }
    $(document).on("click", ".btnEditar", function(){
      $(".modal-header").css( "background-color", "#22af47");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Editar artículo");
      $("#formItems").trigger("reset");
      $('#modalCRUD').modal('show');      
      fila = $(this).closest("tr");
      let id_item = fila.find('td:eq(0)').text();    

      let datosUpdate = new FormData();
      datosUpdate.append('accion', 'traerItemUpdate');
      datosUpdate.append('id_item', id_item);
      $.ajax({
            data: datosUpdate,
            url: './models/administrar_items.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(datosProcesados){
              let datosInput = JSON.parse(datosProcesados);
              $("#descripcion_producto").val(datosInput[0].item);
              $("#categoria").val(datosInput[0].id_categoria);
              $("#Umedida").val(datosInput[0].unidad_medida);
              $("#tipo").val(datosInput[0].id_tipo);
              $("#preposicion").val(datosInput[0].punto_reposicion);
              $("#estado").val(datosInput[0].activo);
              $("#linkVideo").val(datosInput[0].link_video);
              $('#id_item').html(id_item);
              $('#cCostos').val(datosInput[0].id_cc);
              if(datosInput[0].imagen !=""){
                $srcImagen = "./views/img_items/"+datosInput[0].imagen;
                $('#imgUpdate').attr("src", $srcImagen);
                $('#imgUpdate').removeClass("d-none")
                $('.btnBorrarFoto').removeClass("d-none");
              }else{
                $('#imgUpdate').attr("src", "");
                $('#imgUpdate').addClass("d-none");
                $('.btnBorrarFoto').addClass("d-none");
              }
              accion = "updateItem";
            }
          });

      $('#modalCRUD').modal('show');
    });

    $('#formItems').submit(function(e){                         
    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
    
    let id_empresa = parseInt(document.getElementById("id_empresa").innerText); 
    
    let datosEnviar = new FormData();
    datosEnviar.append("descripcion", $.trim($('#descripcion_producto').val()))
    datosEnviar.append("categoria", $.trim($('#categoria').val()));
    datosEnviar.append("Umedida", $.trim($('#Umedida').val()));
    datosEnviar.append("tipo", $.trim($('#tipo').val()));
    datosEnviar.append("preposicion", $.trim($('#preposicion').val()));
    datosEnviar.append("estado", $.trim($('#estado').val()));
    datosEnviar.append("linkVideo", $.trim($('#linkVideo').val()));
    datosEnviar.append("id_item", $.trim($('#id_item').html()));
    datosEnviar.append("accion", accion);
    datosEnviar.append("id_cc", $.trim($('#cCostos').val()));
    datosEnviar.append("id_empresa", id_empresa);
    img = document.getElementById("imagen");

    if (img.files.length > 0) {
      datosEnviar.append("file", img.files[0]);
    }else{
      datosEnviar.append("file", "");
    }
        $.ajax({
          data: datosEnviar,
          url: "models/administrar_items.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            tablaItems.ajax.reload(null, false);
           }
        });             
    $('#modalCRUD').modal('hide'); 
    swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });                               
});


    $("#btnNuevo").click(function(){
      accion = "addArticulo"
    $("#formItems").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Nuevo artículo");
    $('#modalCRUD').modal('show');  
    $('#imgUpdate').attr("src", "");
    $('#imgUpdate').addClass("d-none");
    $('.btnBorrarFoto').addClass("d-none");    
});

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este item, no volveras a verla",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarItem";
                        $.ajax({
                                url: "models/administrar_items.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_item:id_item},    
                                success: function() {
                                    tablaItems.row(fila.parents('tr')).remove().draw();                  
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
      url: "dropArticulos.html",
      type: "POST",
      datatype:"json",    
      data:  {},    
      success: function(response) {
        $('#dropArticulos').html(response);
      }
    });
  })

/*BOTON PROCESAR ARCHIVO*/
$(document).on("click", "#procesarArchivo", function(){

    let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
    
         if (arrayFiles.length == 0) {
                swal({
                        icon: 'error',
                        title: 'Debe seleccionar una archivo para procesar'
                      });
        }else{
          $('#dropArticulos').html("");
          $('#importarArticulos').modal('hide');
          //$('#procesando').modal('show');
          var archivoArticulos = new FormData();
          for(var i = 0; i < arrayFiles.length; i++) {
                  archivoArticulos.append('file'+i, arrayFiles[i]);
              };
          archivoArticulos.append("accion", "importarArticulos");
          archivoArticulos.append("id_empresa", id_empresa);
          $.ajax({
            data: archivoArticulos,
            url: './models/administrar_items.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
            },
            success: function(datosProcesados){
              tablaItems.ajax.reload(null, false);
              swal({
                    icon: 'success',
                    title: 'Archivo importado correctamente'
                  });
               //$('#procesando').modal('hide');
             
            }
          });
            
        }
      })


  /*BUSCAR ITEMS POR PROVEEDOR*/
$(document).on("change", ".estado", function(){
    fila = $(this);           
    nuevoEstado = $(this).val();
    id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());
    
    
    accion = "cambiarEstado";

$.ajax({
            url: "models/administrar_items.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion, id_item: id_item, estado: nuevoEstado},    
            success: function(data) {

              tablaItems.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Estado cambiado exitosamente'
                });

             }
          })
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
    let id_item = parseInt($('#id_item').text());

    /*SACAMOS EL NOMBRE DE LA FOTO A ELIMINAR*/
    /*1 - Tomo el src de la foto*/
    let nombreFotoCompleto = $("#imgUpdate").attr("src");
    /*2 - Separo todo el src y creo un array*/
    let nombreFotoSeparada = nombreFotoCompleto.split('/');
    /*3 Tomo la última posición del array donde estaría el nombre*/
    let nombreFoto = nombreFotoSeparada[nombreFotoSeparada.length-1];
    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este archivo, no volveras a verla",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarArchivoItem";
                        $.ajax({
                                url: "models/administrar_items.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_item:id_item, nombreFoto: nombreFoto},    
                                success: function() {
                                   $(".btnBorrarFoto").addClass("d-none"); 
                                   $("#imgUpdate").addClass("d-none");
                                   $("#imgUpdate").attr("src", "");              
                                }
                            }); 
                    } else {
                        swal("El archivo no se eliminó!");
                    }
                })

})
$(document).on("click", ".btnVer", function(){
  
  let fila = $(this);           
  let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text()); 
  let accion = "trerFotosProducto";
  
  $.ajax({
    url: "models/administrar_items.php",
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