<?php 
  session_start();
  include_once('./conexion.php');
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
    <title>MYLA - Usuarios</title>
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
                    <h3>Usuarios</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Usuarios</li>
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
                    <h5>Administrar Usuarios</h5>
                     <button id="btnNuevo" type="button" class="btn btn-warning mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaUsuarios">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Fecha alta</th>
                            <th>Perfil</th>
                            <th>Cliente</th>
                            <th>Proveedor</th>
                            <th>Empresa</th>
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
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_usuario" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formAlmacen">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Email:</label>
                              <input type="text" class="form-control" id="email" required>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Clave:</label>
                              <input type="text" class="form-control" id="clave" required>
                            </div>
                          </div>   
                      </div>
                      <div class="row">
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Proveedor</label>
                              <select class="form-control" id="proveedor">
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>  
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Cliente</label>
                              <select class="form-control" id="cliente">
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>  
                      </div>
                      <div class="row">  
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Perfil</label>
                              <select class="form-control" id="perfil" disabled="true">
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Empresa</label>
                              <select class="form-control" id="empresaU" >
                                <option value="">Seleccione</option>
                              </select>
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

      <!--Modal para CRUD admin-->
      <div class="modal fade" id="modalCRUDadmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formAdmin">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Email:</label>
                              <input type="text" class="form-control" id="emailAdmin" required>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Clave:</label>
                              <input type="text" class="form-control" id="claveAdmin" required>
                            </div>
                          </div>   
                      </div>
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Empresa</label>
                              <select class="form-control" id="empresas" required>
                                <option value="">Seleccione...</option>
                              </select>
                              </div>            
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Monto aprobación mínimo:</label>
                              <input type="number" min="0" class="form-control" id="aprobMin" required>
                            </div>
                          </div>  
                      </div>             
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Monto aprobación Máximo:</label>
                              <input type="number" min="0" class="form-control" id="aprobMax" required>
                            </div>
                          </div>   
                      </div>      
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="submit" id="btnGuardarAdmin" class="btn btn-dark">Guardar</button>
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
  </body>
    <script type="text/javascript">
      $(document).ready(function(){
       tablaUsuarios= $('#tablaUsuarios').DataTable({
            "ajax": {
                "url" : "./models/administrar_usuarios.php?accion=traerUsuarios",
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_usuario"},
              {"data": "email"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{

                          const estados = {
                            0: "Inactivo",
                            1: "Activo",
                          }

                          $options="";

                          for(key in estados){
                                  if(full.activo == key){
                                    $options+=`<option selected value="${full.estado}">${estados[key]}</option>`
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
              {"data": "fecha_alta"},
              {"data": "perfil"},
              {"data": "cliente"},
              {"data": "proveedor"},
              {"data": "empresa"},
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
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
                datosIniciales.append('accion', 'traerDatosIniciales');
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_usuarios.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){

                        /*identifico el select de empresas*/
                         $selecEmp = document.getElementById("empresas");

                        /*identifico el select de empresas para actualizar*/
                         $selecEmpU = document.getElementById("empresaU");
                       
                        /*Identifico el select de proveedor*/
                        $selecProv = document.getElementById("proveedor");

                        /*Identifico el select de cliente*/
                        $selecCli = document.getElementById("cliente");

                        /*Identifico el select de perfiles*/
                        $selecPerfil = document.getElementById("perfil");

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select provincia*/
                        respuestaJson.proveedores.forEach((proveedores)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(proveedores.proveedor);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", proveedores.id_proveedor);
                            $selecProv.appendChild($option);
                        });

                        /*Genero los options del select cliente*/
                        respuestaJson.clientes.forEach((cliente)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(cliente.cliente);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", cliente.id_cliente);
                            $selecCli.appendChild($option);
                        })

                        /*Genero los options del select perfiles*/
                        respuestaJson.perfiles.forEach((perfil)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(perfil.perfil);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", perfil.id_perfil);
                            $selecPerfil.appendChild($option);
                        })

                        /*Genero los options del select empresas*/
                        respuestaJson.empresas.forEach((empresas)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(empresas.empresa);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", empresas.id_empresa);
                            $selecEmp.appendChild($option);
                        })

                        /*Genero los options del select empresas*/
                        respuestaJson.empresas.forEach((empresas)=>{
                            let $option = document.createElement("option");
                            let optionText = document.createTextNode(empresas.empresa);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", empresas.id_empresa);
                            $selecEmpU.appendChild($option);
                        })

                    }
                });
            }
    $(document).on("click", ".btnEditar", function(){
      $(".modal-header").css( "background-color", "#22af47");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Editar usuario");
      $('#modalCRUD').modal('show');      
      fila = $(this).closest("tr");
      let id_usuario = fila.find('td:eq(0)').text();    

      let datosUpdate = new FormData();
      datosUpdate.append('accion', 'traerUsuarioUpdate');
      datosUpdate.append('id_usuario', id_usuario);
      $.ajax({
            data: datosUpdate,
            url: './models/administrar_usuarios.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(response){
              let datosInput = JSON.parse(response);
              $("#email").val(datosInput[0].email);
              $("#clave").val(datosInput[0].clave);

              let id_proveedor = "";
              let id_cliente = "";
              let id_empresa ="";
              (parseInt(datosInput[0].id_proveedor)== 0)
              ? id_proveedor = ""
              : id_proveedor = datosInput[0].id_proveedor;

              (parseInt(datosInput[0].id_cliente)== 0)
              ? id_cliente = ""
              : id_cliente = datosInput[0].id_cliente;

               (parseInt(datosInput[0].id_empresa)== 0)
              ? id_empresa = ""
              : id_empresa = datosInput[0].id_empresa;

              $("#perfil").val(datosInput[0].id_perfil);
              $("#proveedor").val(id_proveedor);
              $('#cliente').val(id_cliente);
              $('#empresaU').val(id_empresa);
              $('#id_usuario').html(datosInput[0].id_usuario)

              accion = "updateUsuario";
            }
          });

      $('#modalCRUD').modal('show');
    });

    $('#formAlmacen').submit(function(e){                         
    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
    email = $.trim($('#email').val());    
    clave = $.trim($('#clave').val());
    proveedor = $.trim($('#proveedor').val());     
    cliente = $.trim($('#cliente').val());
    id_usuario = $.trim($('#id_usuario').html());
    id_perfil = $.trim($('#perfil').val());
    id_empresa = $.trim($('#empresaU').val());

    if(cliente == ""){
      clienteEnviar = "null"
    }else{
      clienteEnviar = cliente;
    };
    if(proveedor == ""){
      proveedorEnviar = "null"
    }else{
      proveedorEnviar = proveedor
    }
    if(id_empresa == ""){
      id_empresa_enviar = "null"
    }else{
      id_empresa_enviar = id_empresa
    }

    if (proveedor =="" && cliente =="" && id_empresa =="") {
        swal("Debe seleccionar cliente, proveedor o empresa", "","error");
    }else{

      $.ajax({
          url: "models/administrar_usuarios.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accion,id_usuario:id_usuario, email:email, clave:clave, proveedor:proveedorEnviar, cliente:clienteEnviar, id_perfil:id_perfil, id_empresa: id_empresa_enviar},    
          success: function(data) {
            tablaUsuarios.ajax.reload(null, false);
           }
        });             
      $('#modalCRUD').modal('hide'); 
      swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });          
    }                     
});


$('#formAdmin').submit(function(e){                         
    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
    let emailAdmin = $.trim($('#emailAdmin').val());    
    let claveAdmin = $.trim($('#claveAdmin').val());
    let aprobMin = $.trim($('#aprobMin').val());
    let aprobMax = $.trim($('#aprobMax').val());
    let id_perfil = 1;
    let id_empresa = $.trim($('#empresas').val());
    let accion = "verificarCuenta";

    $.ajax({
          url: "models/administrar_usuarios.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accion,  email: emailAdmin},    
          success: function(respuesta) {
            if(respuesta == 1){
              swal("Usuario existente", "Cuenta ya creada con el mail ingresado","error")
              }else{
                registrarUsuario(emailAdmin, claveAdmin, id_perfil, aprobMin, aprobMax, id_empresa);
            }
           }
        }); 
                  
});

function registrarUsuario(emailAdmin, claveAdmin, id_perfil, aprobMin, aprobMax, id_empresa){
   $.ajax({
          url: "models/administrar_usuarios.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accion, email:emailAdmin, clave:claveAdmin, id_perfil:id_perfil, aprobMin: aprobMin, aprobMax: aprobMax, id_empresa},    
          success: function(data) {
            tablaUsuarios.ajax.reload(null, false);
           }
        });             
      $('#modalCRUDadmin').modal('hide'); 
      swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });          
}


    $("#btnNuevo").click(function(){
      accion = "addAdmin"
    $("#formAdmin").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Alta usuarios del sistema");
    $('#modalCRUDadmin').modal('show');      
});

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    id_usuario = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este usuario, no volveras a verla",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarUsuario";
                        $.ajax({
                                url: "models/administrar_usuarios.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_usuario:id_usuario},    
                                success: function() {
                                    tablaUsuarios.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 });
$(document).on("change", ".estado", function(){
    fila = $(this);           
    nuevoEstado = $(this).val();
    id_usuario = parseInt($(this).closest('tr').find('td:eq(0)').text());
    accion = "cambiarEstado";

   $.ajax({
            url: "models/administrar_usuarios.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion, id_usuario: id_usuario, estado: nuevoEstado},    
            success: function(data) {
              $('#modalCRUD').modal('hide');
              tablaUsuarios.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Estado cambiado exitosamente'
                });

             }
          })
})
$(document).on("change", "#proveedor", function(){
    $('#cliente').val("");
    $('#perfil').val(3);
});

$(document).on("change", "#cliente", function(){
    $('#proveedor').val("");
    $('#perfil').val(2);
});
    </script>
  </body>
</html>