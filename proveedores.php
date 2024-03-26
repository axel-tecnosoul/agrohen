<?php 
  session_start();
  include_once('models/conexion.php');
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
    <title>MYLA - Proveedores</title>
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
                    <h3>Clientes</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Proveedores</li>
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
                    <h5>Administrar Proveedores</h5>
                    <span class="d-none" id="id_empresa"><?php echo $_SESSION['rowUsers']['id_empresa']?></span>
                     <button id="btnNuevo" type="button" class="btn btn-warning mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaProveedores">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Razon Social</th>
                            <th>CUIT</th>
                            <th>Rubro</th>
                            <th>Iva</th>
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
    <!-- MODAL CONTACTOS -->
    <!--<div class="modal fade" id="contactos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Contactos</h5>
              <span id="id_contacto" class="d-none"></span>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body text-center">
            <div class="pb-2 flex-wrap">
              <div class="d-flex">
                <button class="btn btn-info btnAddContacto"><i class='fa fa-plus'></i></button>
              </div>
              <div class="" id="addContactos" style="display: none;">
                <form id="formAddContactos">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Nombre completo:</label>
                              <input type="text" class="form-control" id="nombreContacto" required>
                            </div>
                          </div>
                          <div class="col-lg-4">    
                            <div class="form-group">
                              <label for="" class="col-form-label">Email:</label>
                              <input type="email" class="form-control" id="emailContacto" required>
                            </div>            
                          </div>
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Teléfono: </label>
                              <input type="number" class="form-control" id="telContacto" required>
                              </div>            
                          </div>     
                      </div>
                      <div class="row">  
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Estado</label>
                              <select class="form-control" id="estadoContacto" required>
                                <option value="">Seleccione</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                              </select>
                              </div>            
                          </div> 
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Cargo:</label>
                              <select class="form-control" id="cargoContacto" required>
                                <option value="">Seleccione...</option>
                              </select>
                              </div>            
                          </div> 
                      </div>               
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light cancelAddContacto">Cancelar</button>
                      <button type="submit" id="btnGuardarContacto" class="btn btn-dark">Guardar</button>
                  </div>
              </form>
              </div>
            </div>
            <div class="table-responsive tablaContactos">
                      <table class="table table-hover" id="tablaContactos">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Cargo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div><p></p>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
     </div>-->
    <!-- FIN MODAL CONTACTOS -->  
    <!-- MODAL DIRECCIONES -->
    <!--<div class="modal fade" id="direcciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Domicilio</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body text-center">
              <span id="id_proveedorD" class="d-none"></span>
              <span id="id_domicilio" class="d-none"></span>
            <div class="pb-2 flex-wrap">
              <div class="d-flex">
                <button class="btn btn-info btnAddDirecciones"><i class='fa fa-plus'></i></button>
              </div>
              <div class="" id="addDireccion" style="display: none;">
                <form id="formAddDireccion">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Dirección completa:</label>
                              <input type="text" class="form-control" id="direccion" required>
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
                              <label for="" class="col-form-label">Tipo de dirección:</label>
                              <select class="form-control" id="tipoDireccion" required>
                                <option value="">Seleccione...</option>
                              </select>
                              </div>            
                          </div> 
                            
                      </div>
                      <div class="row">  
                          <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Estado</label>
                              <select class="form-control" id="estadoDomicilio" required>
                                <option value="">Seleccione</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                              </select>
                              </div>            
                          </div> 
                      </div>               
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light cancelAddDomicilio">Cancelar</button>
                      <button type="submit" id="btnGuardarDomicilio" class="btn btn-dark">Guardar</button>
                  </div>
              </form>
              </div>
            </div>
            <div class="table-responsive tablaDomicilios">
                      <table class="table table-hover" id="tablaDomicilios">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Dirección</th>
                            <th>Provincia</th>
                            <th>Tipo dirección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div><p></p>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
     </div>-->
    <!-- FIN MODAL PARAMETROS --> 
     <!--Modal para CRUD-->
      <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_proveedor" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formProveedores">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Razón Social:</label>
                              <input type="text" class="form-control" id="razonSocial" required>
                            </div>
                          </div>
                          <div class="col-lg-6">    
                            <div class="form-group">
                              <label for="" class="col-form-label">CUIT</label>
                              <input type="number" class="form-control" id="cuit" required>
                            </div>            
                          </div>    
                      </div>
                      <div class="row">  
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Rubro</label>
                              <select class="form-control" id="rubro" required>
                                <option value="">Seleccione...</option>
                              </select>
                              </div>            
                          </div> 
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Estado</label>
                              <select class="form-control" id="estado" required>
                                <option value="">Seleccione</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                              </select>
                              </div>            
                          </div> 
                      </div>
                      <div class="row">
                        <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Condicion IVA</label>
                              <select class="form-control" id="condicionIVA" required>
                                <option value="">Seleccione...</option>
                              </select>
                              </div>            
                          </div> 
                      </div>
                      <!--<div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Saldo:</label>
                              <input type="number" class="form-control" id="saldoProv" required>
                            </div>
                          </div>   
                      </div>               
                    </div>-->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </form> 
                  </div>
                </div>
                 <div class="card mr-2 ml-2 d-none" id="card-contactos">
                    <div class="card-header">
                      <h5>Contactos y Locaciones</h5>
                    </div>
                    <div class="card-body">
                    <div class="default-according" id="verDetalleOrdenes">
                          <div class="card">
                            <div class="card-header" id="heading2">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed btnContactos" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="heading2">Contactos</button>
                              </h5>
                            </div>
                            <div class="collapse" id="collapse1" aria-labelledby="heading2" data-parent="#accordionclose">
                              <div class="card-body">
                                <div class="pb-2 flex-wrap">
                                  <div class="d-flex">
                                    <button class="btn btn-info btnAddContacto"><i class='fa fa-plus'></i></button>
                                  </div>
                                  <div class="d-block text-right" id="contBuscadorContactos">
                                    <label>Buscar:<input type="text" class="form-control buscadorContactos" placeholder="Nombre, Email o Teléfono">
                                      </label>
                                  </div>
                                  <div class="" id="addContactos" style="display: none;">
                                    <form id="formAddContactos">    
                                      <div class="modal-body">
                                          <div class="row">
                                              <div class="col-lg-4">
                                                <div class="form-group">
                                                  <label for="" class="col-form-label">Nombre completo:</label>
                                                  <input type="text" class="form-control" id="nombreContacto" required>
                                                </div>
                                              </div>
                                              <div class="col-lg-4">    
                                                <div class="form-group">
                                                  <label for="" class="col-form-label">Email:</label>
                                                  <input type="email" class="form-control" id="emailContacto" required>
                                                </div>            
                                              </div>
                                              <div class="col-lg-4">    
                                                  <div class="form-group">
                                                  <label for="" class="col-form-label">Teléfono: </label>
                                                  <input type="number" class="form-control" id="telContacto" required>
                                                  </div>            
                                              </div>     
                                          </div>
                                          <div class="row">  
                                              <div class="col-lg-4">    
                                                  <div class="form-group">
                                                  <label for="" class="col-form-label">Estado</label>
                                                  <select class="form-control" id="estadoContacto" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="Activo">Activo</option>
                                                    <option value="Inactivo">Inactivo</option>
                                                  </select>
                                                  </div>            
                                              </div> 
                                              <div class="col-lg-4">    
                                                  <div class="form-group">
                                                  <label for="" class="col-form-label">Cargo:</label>
                                                  <select class="form-control" id="cargoContacto" required>
                                                    <option value="">Seleccione...</option>
                                                  </select>
                                                  </div>            
                                              </div> 
                                          </div>               
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-light cancelAddContacto">Cancelar</button>
                                          <button type="submit" id="btnGuardarContacto" class="btn btn-dark">Guardar</button>
                                          </form>
                                      </div>
                                  
                                  </div>
                                </div>
                                <span id="id_contacto" class="d-none"></span>
                                <div class="table-responsive tablaContactos">
                                  <table class="table table-hover" id="tablaContactos">
                                    <thead class="text-center">
                                      <tr>
                                        <th class="text-center">#ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Cargo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                  </table>
                                </div><p></p>
                                <!--<div id="loaderDetalle" style="padding-left: 45%;">
                                    <div class="loader-box text-center">
                                      <span class="rotate dotted"></span>
                                    </div>
                                </div>-->
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header" id="heading1">
                              <h5 class="mb-0">
                                <button class="btn btn-link btnDirecciones" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="heading1">Locaciones</button>
                              </h5>
                            </div>
                            <div class="collapse" id="collapse2" aria-labelledby="heading1" data-parent="#accordionclose">
                              <div class="card-body">
                                <div class="pb-2 flex-wrap">
                                    <div class="d-flex">
                                      <button class="btn btn-info btnAddDirecciones"><i class='fa fa-plus'></i></button>
                                    </div>
                                    <div class="" id="addDireccion" style="display: none;">
                                      <form id="formAddDireccion">    
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                  <div class="form-group">
                                                    <label for="" class="col-form-label">Dirección completa:</label>
                                                    <input type="text" class="form-control" id="direccion" required>
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
                                                    <label for="" class="col-form-label">Tipo de dirección:</label>
                                                    <select class="form-control" id="tipoDireccion" required>
                                                      <option value="">Seleccione...</option>
                                                    </select>
                                                    </div>            
                                                </div> 
                                                  
                                            </div>
                                            <div class="row">  
                                                <div class="col-lg-4">    
                                                    <div class="form-group">
                                                    <label for="" class="col-form-label">Estado</label>
                                                    <select class="form-control" id="estadoDomicilio" required>
                                                      <option value="">Seleccione</option>
                                                      <option value="Activo">Activo</option>
                                                      <option value="Inactivo">Inactivo</option>
                                                    </select>
                                                    </div>            
                                                </div> 
                                            </div>               
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light cancelAddDomicilio">Cancelar</button>
                                            <button type="submit" id="btnGuardarDomicilio" class="btn btn-dark">Guardar</button>
                                        </div>
                                    </form>
                                    </div>
                                  </div>
                                  <span id="id_domicilio" class="d-none"></span>
                                  <div class="table-responsive tablaDomicilios">
                                            <table class="table table-hover" id="tablaDomicilios">
                                              <thead class="text-center">
                                                <tr>
                                                  <th class="text-center">#ID</th>
                                                  <th>Dirección</th>
                                                  <th>Provincia</th>
                                                  <th>Tipo dirección</th>
                                                  <th>Estado</th>
                                                  <th>Acciones</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                              </tbody>
                                            </table>
                                          </div><p></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
    <script type="text/javascript">
      $(document).ready(function(){

      let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
      realizarBusquedaContactos = 0;
      realizarBusquedaDirecciones = 0;

      tablaProveedores= $('#tablaProveedores').DataTable({
            "ajax": {
                "url" : "./models/administrar_proveedores.php?accion=traerProveedores&id_empresa="+id_empresa,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_proveedor"},
              {"data": "razon_social"},
              {"data": "cuit"},
              {"data": "rubro"},
              {"data": "iva"},
              {"data": "activo"},
              {"data": "fecha_alta"},
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            ],
            "language":  idiomaEsp
        });
       cargarDatosComponentes();
       $('#modalCRUD').on('hidden.bs.modal', function (e) {
          document.getElementById('card-contactos').classList.add("d-none");
          realizarBusquedaContactos = 0;
          realizarBusquedaDirecciones = 0;
          $("#collapse1").collapse("hide");
          $("#collapse2").collapse("hide");

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

                let id_empresa = parseInt(document.getElementById("id_empresa").innerText)
                let datosIniciales = new FormData();

                datosIniciales.append('accion', 'traerDatosIniciales');
                datosIniciales.append('id_empresa', id_empresa);

                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_proveedores.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de provincia*/
                        $selecProv = document.getElementById("provincia");

                        /*Identifico el select de condicion iva*/
                        $condIva = document.getElementById("condicionIVA");

                        /*Identifico el select de cargos contactos*/
                        $cargoContactos = document.getElementById("cargoContacto");

                        /*Identifico select tipo de direccion*/
                        $selTipoDireccion = document.getElementById("tipoDireccion");

                        /*Identifico select rubros*/
                        $selRubro = document.getElementById("rubro");

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select provincia*/
                        respuestaJson.provincias.forEach((provincias)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(provincias.nombreProv);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", provincias.id_provincia);
                            $selecProv.appendChild($option);
                        })

                         /*Genero los options del select condicion iva*/
                        respuestaJson.condicion_iva.forEach((condIva)=>{
                            $optionIva = document.createElement("option");
                            let $optionIvaText = document.createTextNode(condIva.tipoIva);
                            $optionIva.appendChild($optionIvaText)
                            $optionIva.setAttribute("value", condIva.id_iva);
                            $condIva.appendChild($optionIva);
                         })

                        /*Genero los options del select cargo contactos*/

                        respuestaJson.cargos.forEach((cargos)=>{
                          $optionCargo = document.createElement("option");
                          let $optionCargoText = document.createTextNode(cargos.cargo);
                          $optionCargo.appendChild($optionCargoText);
                          $optionCargo.setAttribute("value", cargos.id_cargo);
                          $cargoContactos.appendChild($optionCargo);
                        })

                        /*Genero los options del select tipo de dirección*/

                        respuestaJson.tipo_domicilio.forEach((tipoDom)=>{
                          $optionTipoDom = document.createElement("option");
                          let $optionTipoDomText = document.createTextNode(tipoDom.tipoDireccion);
                          $optionTipoDom.appendChild($optionTipoDomText);
                          $optionTipoDom.setAttribute("value", tipoDom.idTipoDireccion);
                          $selTipoDireccion.appendChild($optionTipoDom);

                        })

                        respuestaJson.rubros.forEach((rubros)=>{
                          $optionRubros = document.createElement("option");
                          let $optionRubrosText = document.createTextNode(rubros.rubro);
                          $optionRubros.appendChild($optionRubrosText);
                          $optionRubros.setAttribute("value", rubros.id_rubro);
                          $selRubro.appendChild($optionRubros);

                        })

                    }
                });
            }
    $(document).on("click", ".btnEditar", function(){
      $(".modal-header").css( "background-color", "#22af47");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Editar Proveedor");
      $('#modalCRUD').modal('show');   
      $card_contactos = document.getElementById("card-contactos");
      $card_contactos.classList.remove("d-none");   
      fila = $(this).closest("tr");
      let id_proveedor = fila.find('td:eq(0)').text();    

      let datosUpdate = new FormData();
      datosUpdate.append('accion', 'traerProveedorUpdate');
      datosUpdate.append('id_proveedor', id_proveedor);
      $.ajax({
            data: datosUpdate,
            url: './models/administrar_proveedores.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(datosProcesados){
              let datosInput = JSON.parse(datosProcesados);
              $("#razonSocial").val(datosInput[0].razon_social);
              $("#cuit").val(datosInput[0].cuit);
              $("#estado").val(datosInput[0].activo);
              $("#condicionIVA").val(datosInput[0].iva);
              $("#rubro").val(datosInput[0].rubro_prov);
              $('#id_proveedor').html(datosInput[0].id_proveedor);
              accion = "updateProveedor";
            }
          });

      $('#modalCRUD').modal('show');
    });

    $('#formProveedores').submit(function(e){                         
    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página
    let id_empresa = parseInt(document.getElementById("id_empresa").innerText)   
    let razon_social = $.trim($('#razonSocial').val());    
    let cuit = $.trim($('#cuit').val());
    let rubro = $.trim($('#rubro').val());
    let estado = $.trim($('#estado').val());    
    let condicion_iva = $.trim($('#condicionIVA').val());  
    //saldo = $.trim($('#saldoProv').val());    
    let id_proveedor = $.trim($('#id_proveedor').html());                
        $.ajax({
          url: "models/administrar_proveedores.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accion,id_proveedor:id_proveedor, razon_social:razon_social, cuit:cuit, rubro:rubro, estado:estado, condicion_iva:condicion_iva, id_empresa},    
          success: function(data) {
            tablaProveedores.ajax.reload(null, false);
           }
        });             
    $('#modalCRUD').modal('hide'); 
    swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });                               
});


    $("#btnNuevo").click(function(){
      accion = "nuevoProveedor"
    $("#formProveedores").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Alta de proveedores");
    $('#modalCRUD').modal('show');      
});

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    proveedor_id = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este proveedor, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarProveedor";
                        $.ajax({
                                url: "models/administrar_proveedores.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, proveedor_id:proveedor_id},    
                                success: function() {
                                    tablaProveedores.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 });
/*
  #################CONTACTOS##########################
*/

$(document).on("click", '.btnContactos', function(){
  
  let id_proveedor = $("#id_proveedor").html();
  //$("#id_proveedorC").html(id_proveedor); 
  accion = "traerContactos";

  if(realizarBusquedaContactos ==0){
    
    realizarBusquedaContactos=1;

    $.ajax({
            url: "models/administrar_proveedores.php",
            type: "POST",
            datatype:"json",    
            data:  {accion:accion, id_proveedor:id_proveedor},    
            success: function(respuesta) {
                  

                  let $tablaContactos = document.querySelector("#tablaContactos tbody");
                  $tablaContactos.innerHTML = "";
                  let respuestaJson = JSON.parse(respuesta);
                  if(respuestaJson.length==0){
                   $trEmpty = document.createElement("tr");
                    $trEmpty.innerHTML=`<td colspan="7">No hay datos</td>`;
                    $tablaContactos.appendChild($trEmpty);
                  }else{
                      $tablaContactos.innerHTML = "";
                      respuestaJson.forEach((contactos)=>{
                      $tr = document.createElement("tr");
                      $tr.innerHTML=`<td>${contactos.id_contacto}</td>
                                      <td>${contactos.nombre_completo}</td>
                                      <td>${contactos.email}</td>
                                      <td>${contactos.telefono}</td>
                                      <td><span class="d-none">${contactos.id_cargo}</span>${contactos.cargo}</td>
                                      <td>${contactos.activo}</td>
                                      <td>
                                        <div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarContacto'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrarContacto'><i class='fa fa-trash-o'></i></button></div></div>
                                      </td>
                                    `;
                      $tablaContactos.appendChild($tr);
                      })
                      
                  }

             }
          });
  }
 
});

/*ACCIONES CONTACTO*/

$(document).on("click", '.btnAddContacto', function(){
  $("#formAddContactos").trigger("reset");
    $("#addContactos").css("display", "block");
    $(".tablaContactos").css("display", "none");
    accionContacto = "addContacto";
  })

 $(document).on("click", '.cancelAddContacto', function(){
    $("#addContactos").css("display", "none");
    $(".tablaContactos").css("display", "block");
  })

 /*ACCIONES A REALIZAR AL OCULTAR EL MODAL DEL CONTACTOS*/
    $('#contactos').on('hidden.bs.modal', function (e) {
      $("#addContactos").css("display", "none");
      $(".tablaContactos").css("display", "block");
      $("#tablaContactos tbody").html("");
    });

/*SUBMIT ADHERIR CONTACTO*/
$('#formAddContactos').submit(function(e){                         

    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   

    nombreContacto = $.trim($('#nombreContacto').val());    
    emailContacto = $.trim($('#emailContacto').val());
    telContacto = $.trim($('#telContacto').val());
    estadoContacto = $.trim($('#estadoContacto').val());   
    cargoContacto = $.trim($('#cargoContacto').val());   
    let id_contacto = $.trim($('#id_contacto').html());
    let id_proveedor = $.trim($('#id_proveedor').html());

    console.log(id_proveedor)
    if(accionContacto == 'editarContacto'){
       contactoID = $.trim($('#id_contacto').html());     
    }else{
       contactoID = "";
    }

    $.ajax({
          url: "models/administrar_proveedores.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accionContacto, id_proveedor:id_proveedor, id_contacto:contactoID, nombreContacto:nombreContacto, emailContacto:emailContacto, telContacto:telContacto, estadoContacto:estadoContacto, cargoContacto:cargoContacto},
          success: function(data) {
            traerContactosAgain();
            $("#formAddContactos").trigger("reset");
            $("#addContactos").css("display", "none");
            $(".tablaContactos").css("display", "block");
           }
        });             
    swal({
                  icon: 'success',
                  title: '¡REALIZADO!'
                });                               
});

function traerContactosAgain(){
 let id_proveedor = $.trim($('#id_proveedor').html());
  accion = "traerContactos";
  $.ajax({
          url: "models/administrar_proveedores.php",
          type: "POST",
          datatype:"json",    
          data:  {accion:accion, id_proveedor:id_proveedor},    
          success: function(respuesta) {
                $('#resParam').html(respuesta);
                $(".modal-header").css( "background-color", "#ff9f40");
                $(".modal-header").css( "color", "white" );
                $(".modal-title").text("Contactos");
                $('#contactos').modal('show');

                let $tablaContactos = document.querySelector("#tablaContactos tbody");
                let respuestaJson = JSON.parse(respuesta);
                if(respuestaJson.length==0){
                   $tablaContactos.innerHTML = "";
                  $trEmpty = document.createElement("tr");
                  $trEmpty.innerHTML=`<td colspan="7">No hay datos</td>`;
                  $tablaContactos.appendChild($trEmpty);
                }else{
                  $tablaContactos.innerHTML = "";
                  respuestaJson.forEach((contactos)=>{
                      $tr = document.createElement("tr");
                    $tr.innerHTML=`<td>${contactos.id_contacto}</td>
                                    <td>${contactos.nombre_completo}</td>
                                    <td>${contactos.email}</td>
                                    <td>${contactos.telefono}</td>
                                    <td><span class="d-none">${contactos.id_cargo}</span>${contactos.cargo}</td>
                                    <td>${contactos.activo}</td>
                                    <td>
                                      <div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarContacto'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrarContacto'><i class='fa fa-trash-o'></i></button></div></div>
                                    </td>
                                  `;
                    $tablaContactos.appendChild($tr);
                    })
                }

           }
        });
}

$(document).on("click", ".btnBorrarContacto", function(){
  let contactoID = parseInt($(this).closest('tr').find('td:eq(0)').text());
  swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este registro, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accionContacto = "eliminarContacto";
                        $.ajax({
                          url: "models/administrar_proveedores.php",
                          type: "POST",
                          datatype: "json",
                          data:{accion: accionContacto, id_contacto: contactoID},
                          success: function(){
                            swal("Contacto eliminado!", {
                            icon: "success",
                              });
                            traerContactosAgain();
                          }
                        });
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
});

$(document).on("click",".btnEditarContacto", function(){
    $("#estadoContacto option[value='']").attr("selected", true);
    datosContactos={
    id_contacto:parseInt($(this).closest('tr').find('td:eq(0)').text()),
    nombreContacto: $(this).closest('tr').find('td:eq(1)').text(),
    emailContacto: $(this).closest('tr').find('td:eq(2)').text(),
    telContacto: $(this).closest('tr').find('td:eq(3)').text(),
    cargo: $(this).closest('tr').find('td:eq(4)').text(),
    id_cargo: $(this).closest('tr').find('td:eq(4)').find('span').text(),
    estadoContacto: $(this).closest('tr').find('td:eq(5)').text()
  }
  accionContacto = "editarContacto";
  $("#addContactos").css("display", "block");
   $("#nombreContacto").val(datosContactos.nombreContacto);
   $("#emailContacto").val(datosContactos.emailContacto);
   $("#telContacto").val(datosContactos.telContacto);
   $("#estadoContacto").val(datosContactos.estadoContacto);
   $("#cargoContacto").val(datosContactos.id_cargo);
   $('#id_contacto').html(datosContactos.id_contacto);
  $(".tablaContactos").css("display", "none");
  
})

/*
  #################DOMICILIOS##########################
*/

$(document).on("click", '.btnDirecciones', function(){
  
  let id_proveedor = $("#id_proveedor").html();
  accion = "traerDirecciones";

  if(realizarBusquedaDirecciones ==0){
    
    realizarBusquedaDirecciones=1;

  $.ajax({
          url: "models/administrar_proveedores.php",
          type: "POST",
          datatype:"json",    
          data:  {accion:accion, id_proveedor:id_proveedor},    
          success: function(respuesta) {
                 

                let $tablaDomicilios = document.querySelector("#tablaDomicilios tbody");
                $tablaDomicilios.innerHTML="";
                let respuestaJson = JSON.parse(respuesta);
                if(respuestaJson.length==0){
                 $trEmpty = document.createElement("tr");
                  $trEmpty.innerHTML=`<td colspan="7">No hay datos</td>`;
                  $tablaDomicilios.appendChild($trEmpty);
                }else{

                    $tablaDomicilios.innerHTML="";

                    respuestaJson.forEach((domicilios)=>{
                      $tr = document.createElement("tr");

                    $tr.innerHTML=`<td>${domicilios.id_direccion}</td>
                                    <td>${domicilios.direccion}</td>
                                    <td><span class="d-none">${domicilios.id_provincia}</span>${domicilios.provincia}</td>
                                    <td><span class="d-none">${domicilios.id_tipo_dir}</span>${domicilios.tipo_direccion}</td>
                                    <td>${domicilios.activo}</td>
                                    <td>
                                      <div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarDomicilio'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrarDomicilio'><i class='fa fa-trash-o'></i></button></div></div>
                                    </td>
                                  `;
                    $tablaDomicilios.appendChild($tr);
                    })
                    
                }

           }
        });
}
 
});

/*ACCIONES DIRECCIONES*/

$(document).on("click", '.btnAddDirecciones', function(){
  $("#formAddDireccion").trigger("reset");
    $("#addDireccion").css("display", "block");
    $(".tablaDomicilios").css("display", "none");
    accionDomicilio = "addDomicilio";
  });

$(document).on("click", '.cancelAddDomicilio', function(){
    $("#addDireccion").css("display", "none");
    $(".tablaDomicilios").css("display", "block");
  })

/*ACCIONES A REALIZAR AL OCULTAR EL MODAL DEL DOMICILIO*/
      $('#direcciones').on('hidden.bs.modal', function (e) {
      $("#addDireccion").css("display", "none");
      $(".tablaDomicilios").css("display", "block");
      $("#tablaDomicilios tbody").html("");
    });

/*SUBMIT ADHERIR DIRECCIONES*/
$('#formAddDireccion').submit(function(e){                         

    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página

    if(accionDomicilio == 'editarDomicilio'){
       domicilioID = $.trim($('#id_domicilio').html());     
    }else{
       domicilioID = "";
  }   

    direccion = $.trim($('#direccion').val());    
    provincia = $.trim($('#provincia').val());
    tipoDireccion = $.trim($('#tipoDireccion').val());
    estadoDomicilio = $.trim($('#estadoDomicilio').val()); 
    id_proveedor = $.trim($('#id_proveedor').html());
    id_domicilio = $.trim($('#id_domicilio').html());

        $.ajax({
          url: "models/administrar_proveedores.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accionDomicilio,id_proveedor:id_proveedor, id_domicilio:domicilioID, direccion:direccion, tipoDireccion:tipoDireccion, provincia:provincia, estadoDomicilio:estadoDomicilio},    
          success: function(data) {
            traerDomiciliosAgain();
            $("#formAddDireccion").trigger("reset");
            $("#addDireccion").css("display", "none");
            $(".tablaDomicilios").css("display", "block");
           }
        });             
    swal({
                  icon: 'success',
                  title: '¡REALIZADO!'
                });                               
})

function traerDomiciliosAgain(){
  let id_proveedor = $.trim($('#id_proveedor').html());
  //$("#id_proveedorD").html(id_cliente); 
  accion = "traerDirecciones";
  $.ajax({
          url: "models/administrar_proveedores.php",
          type: "POST",
          datatype:"json",    
          data:  {accion:accion, id_proveedor:id_proveedor},    
          success: function(respuesta) {
                  $(".modal-header").css( "background-color", "#007bff");
                  $(".modal-header").css( "color", "white" );
                  $(".modal-title").text("Direcciones");
                  $('#direcciones').modal('show');

                let $tablaDomicilios = document.querySelector("#tablaDomicilios tbody");
                let respuestaJson = JSON.parse(respuesta);
                if(respuestaJson.length==0){
                  $tablaDomicilios.innerHTML = "";
                 $trEmpty = document.createElement("tr");
                  $trEmpty.innerHTML=`<td colspan="7">No hay datos</td>`;
                  $tablaDomicilios.appendChild($trEmpty);
                }else{
                  $tablaDomicilios.innerHTML = "";
                    respuestaJson.forEach((domicilios)=>{
                      $tr = document.createElement("tr");

                    $tr.innerHTML=`<td>${domicilios.id_direccion}</td>
                                    <td>${domicilios.direccion}</td>
                                    <td><span class="d-none">${domicilios.id_provincia}</span>${domicilios.provincia}</td>
                                    <td><span class="d-none">${domicilios.id_tipo_dir}</span>${domicilios.tipo_direccion}</td>
                                    <td>${domicilios.activo}</td>
                                    <td>
                                      <div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarDomicilio'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrarDomicilio'><i class='fa fa-trash-o'></i></button></div></div>
                                    </td>
                                  `;
                    $tablaDomicilios.appendChild($tr);
                    })
                    
                }

           }
        });
}

$(document).on("click", ".btnBorrarDomicilio", function(){
  let domicilioID = parseInt($(this).closest('tr').find('td:eq(0)').text());
  swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este registro, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accionDomicilio = "eliminarDomicilio";
                        $.ajax({
                          url: "models/administrar_proveedores.php",
                          type: "POST",
                          datatype: "json",
                          data:{accion: accionDomicilio, id_domicilio: domicilioID},
                          success: function(){
                            swal("Domicilio eliminado!", {
                            icon: "success",
                              });
                            traerDomiciliosAgain();
                          }
                        });
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
});
$(document).on("click",".btnEditarDomicilio", function(){
    //$("#estadoContacto option[value='']").attr("selected", true);
    datosDomicilio={
    id_domicilio:parseInt($(this).closest('tr').find('td:eq(0)').text()),
    direccion: $(this).closest('tr').find('td:eq(1)').text(),
    provincia: $(this).closest('tr').find('td:eq(2)').find('span').text(),
    tipo_domicilio: $(this).closest('tr').find('td:eq(3)').find('span').text(),
    estadoDomicilio: $(this).closest('tr').find('td:eq(4)').text()
  }


  accionDomicilio = "editarDomicilio";
  $("#formAddDireccion").trigger("reset");
  $("#addDireccion").css("display", "block");
  $(".tablaDomicilios").css("display", "none");
  $("#id_domicilio").html(datosDomicilio.id_domicilio);
  $("#direccion").val(datosDomicilio.direccion);
  $("#provincia").val(datosDomicilio.provincia);
  $("#tipoDireccion").val(datosDomicilio.tipo_domicilio);
  $("#estadoDomicilio").val(datosDomicilio.estadoDomicilio);

  
  
})
    </script>
  </body>
</html>