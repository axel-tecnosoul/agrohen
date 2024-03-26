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
    <title>MYLA - Clientes</title>
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
                      <li class="breadcrumb-item active">Clientes</li>
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
                    <h5>Administrar Clientes</h5>
                     <button id="btnNuevo" type="button" class="btn btn-info mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 
                     <span class="d-none" id="id_empresa"><?php echo $_SESSION['rowUsers']['id_empresa']?></span>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaClientes">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID&nbsp;</th>
                            <th>&nbsp;&nbsp;Alias&nbsp;&nbsp;&nbsp;</th>
                            <th>Razon Social&nbsp;&nbsp;</th>
                            <th>Dirección&nbsp;</th>
                            <th>Localidad&nbsp;&nbsp;</th>
                            <th>Tel1</th>
                            <th>CUIT</th>
                            <th>Estado</th>
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
                      <span id="id_cliente" class="d-none"></span>
                      <span id="id_clienteC" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formUsuarios">    
                  <div class="modal-body">
                    <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Alias:</label>
                              <input type="text" class="form-control" id="alias" required>
                            </div>
                          </div>   
                      </div>
                      <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Razón Social:</label>
                              <input type="text" class="form-control" id="razonSocial" required>
                            </div>
                          </div>
                          <div class="col-lg-12">    
                            <div class="form-group">
                              <label for="" class="col-form-label">CUIT</label>
                              <input type="number" class="form-control" id="cuit" required>
                            </div>            
                          </div>    
                      </div>
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Teléfono 1:</label>
                              <input type="text" class="form-control" id="telefono1" required>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Teléfono 2:</label>
                              <input type="text" class="form-control" id="telefono2" required>
                            </div>
                          </div>    
                      </div>
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Fax:</label>
                              <input type="text" class="form-control" id="fax" required>
                            </div>
                          </div>
                          <div class="col-lg-6">    
                            <div class="form-group">
                              <label for="" class="col-form-label">Email:</label>
                              <input type="email" class="form-control" id="email" required>
                            </div>            
                          </div>    
                      </div>
                      <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Direccion:</label>
                              <input type="text" class="form-control" id="direccionCliente" required>
                            </div>
                          </div> 
                      </div>
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Localidad:</label>
                              <input type="text" class="form-control" id="localidad" required>
                            </div>
                          </div>
                          <div class="col-lg-6">    
                            <div class="form-group">
                              <label for="" class="col-form-label">Provincia</label>
                              <select class="form-control" id="provinciaCli" required>
                                <option value="">Seleccione</option>
                              </select>
                            </div>             
                          </div>    
                      </div>
                      <div class="row">  
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
                          <div class="col-lg-6">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Condicion IVA</label>
                              <select class="form-control" id="condicionIVA" required>
                                <option value="">Seleccione...</option>
                              </select>
                              </div>            
                          </div> 
                      </div>               
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </form>
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
                              <!--<div class="d-block text-right" id="contBuscadorContactos">
                                <label>Buscar:<input type="text" class="form-control buscadorContactos" placeholder="Nombre, Email o Teléfono">
                                  </label>
                              </div>-->
                              <div class="" id="addContactos" style="display: none;">
                                <form id="formAddContactos">    
                                  <div class="modal-body">
                                      <div class="row">
                                          <div class="col-lg-4">    
                                              <div class="form-group">
                                              <label for="" class="col-form-label">Locacion: </label>
                                              <select class="form-control" id="locaciones" required>
                                                <option value="">Seleccione</option>
                                              </select>           
                                          </div> 
                                          </div>
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
                                      </div>
                                      <div class="row">
                                        <div class="col-lg-4">    
                                              <div class="form-group">
                                              <label for="" class="col-form-label">Teléfono: </label>
                                              <input type="number" class="form-control" id="telContacto" required>
                                              </div>            
                                          </div>   
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
                                    <th>Locación</th>
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
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                          <label for="" class="col-form-label">Centro de costos</label>
                                          <select class="form-control" id="centro_costos_ubicacion" required>
                                            <option value="">Seleccione</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-lg-4">
                                        <div class="form-group">
                                          <label for="" class="col-form-label">Almacen</label>
                                          <select class="form-control" id="almacen_ubicacion" required>
                                            <option value="">Seleccione</option>
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
                                    <th>Centro costos</th>
                                    <th>Almacen</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
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
      </div>

    <!-- MODAL CONTACTOS -->
    <div class="modal fade" id="contactos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <tbody></tbody>
              </table>
            </div><p></p>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
     </div>
    <!-- FIN MODAL CONTACTOS -->

    <!-- MODAL DIRECCIONES -->
    <div class="modal fade" id="direcciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Domicilio</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body text-center">
            <span id="id_clienteD" class="d-none"></span>
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
                <tbody></tbody>
              </table>
            </div><p></p>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FIN MODAL DIRECCIONES -->

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
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">
      $(document).ready(function(){
        let id_empresa = document.getElementById("id_empresa").textContent;
        realizarBusquedaContactos = 0;
         realizarBusquedaDirecciones = 0;
        tablaClientes= $('#tablaClientes').DataTable({
          "ajax": {
            "url" : "./models/administrar_clientes.php?accion=traerClientes&id_empresa="+id_empresa,
            "dataSrc": "",
          },
          "columns":[
            {"data": "id_cliente"},
            {"data": "alias"},
            {"data": "razon_social"},
            {"data": "direccion_cliente"},
            {"data": "localidad_cliente"},
            {"data": "tel1"},
            {"data": "cuit"},
            {"data": "activo"},
            {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
          ],
          "language":  idiomaEsp
        });
        cargarDatosComponentes(id_empresa);
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
      
      function cargarDatosComponentes(id_empresa){
        console.log()
        let datosIniciales = new FormData();
        datosIniciales.append('accion', 'traerDatosInicialesClientes');
        $.ajax({
          data: datosIniciales,
          url: "./models/administrar_clientes.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
              //$('#addProdLocal').modal('hide');
          },
          success: function(respuesta){
            console.log(respuesta);
            /*Convierto en json la respuesta del servidor*/
            respuestaJson = JSON.parse(respuesta);
            console.log(respuestaJson);

            /*Identifico el select de provincia*/
            $selecProv = document.getElementById("provincia");
            /*Genero los options del select provincia*/
            respuestaJson.provincias.forEach((provincias)=>{
              $option = document.createElement("option");
              let optionText = document.createTextNode(provincias.nombreProv);
              $option.appendChild(optionText);
              $option.setAttribute("value", provincias.id_provincia);
              $selecProv.appendChild($option);
            })

            /*Identifico el select de provincia*/
            $selecProvCli = document.getElementById("provinciaCli");
            /*Genero los options del select provincia en el alta*/
            respuestaJson.provincias.forEach((provincias)=>{
              let $option = document.createElement("option");
              let optionText = document.createTextNode(provincias.nombreProv);
              $option.appendChild(optionText);
              $option.setAttribute("value", provincias.id_provincia);
              $selecProvCli.appendChild($option);
            })
            
            /*Identifico el select de condicion iva*/
            $condIva = document.getElementById("condicionIVA");
            /*Genero los options del select condicion iva*/
            respuestaJson.condicion_iva.forEach((condIva)=>{
              $optionIva = document.createElement("option");
              let $optionIvaText = document.createTextNode(condIva.tipoIva);
              $optionIva.appendChild($optionIvaText)
              $optionIva.setAttribute("value", condIva.id_iva);
              $condIva.appendChild($optionIva);
            })

            /*Identifico el select de cargos contactos*/
            $cargoContactos = document.getElementById("cargoContacto");
            /*Genero los options del select cargo contactos*/
            respuestaJson.cargos.forEach((cargos)=>{
              $optionCargo = document.createElement("option");
              let $optionCargoText = document.createTextNode(cargos.cargo);
              $optionCargo.appendChild($optionCargoText);
              $optionCargo.setAttribute("value", cargos.id_cargo);
              $cargoContactos.appendChild($optionCargo);
            })

            /*Identifico select tipo de direccion*/
            $selTipoDireccion = document.getElementById("tipoDireccion");
            /*Genero los options del select tipo de dirección*/
            respuestaJson.tipo_domicilio.forEach((tipoDom)=>{
              $optionTipoDom = document.createElement("option");
              let $optionTipoDomText = document.createTextNode(tipoDom.tipoDireccion);
              $optionTipoDom.appendChild($optionTipoDomText);
              $optionTipoDom.setAttribute("value", tipoDom.idTipoDireccion);
              $selTipoDireccion.appendChild($optionTipoDom);
            })

            /*Identifico select tipo de direccion*/
            $selCentrosCosto = document.getElementById("centro_costos_ubicacion");
            /*Genero los options del select tipo de dirección*/
            respuestaJson.centros_costo.forEach((centro_costo)=>{
              $optionCentroCosto = document.createElement("option");
              let $optionCentroCostoText = document.createTextNode(centro_costo.centroCosto);
              $optionCentroCosto.appendChild($optionCentroCostoText);
              $optionCentroCosto.setAttribute("value", centro_costo.idCentroCosto);
              $selCentrosCosto.appendChild($optionCentroCosto);
            })

            /*Identifico select tipo de direccion*/
            $selAlmacenes = document.getElementById("almacen_ubicacion");
            /*Genero los options del select tipo de dirección*/
            respuestaJson.almacenes.forEach((almacen)=>{
              $optionAlmacen = document.createElement("option");
              let $optionAlmacenText = document.createTextNode(almacen.almacen);
              $optionAlmacen.appendChild($optionAlmacenText);
              $optionAlmacen.setAttribute("value", almacen.id_almacen);
              $selAlmacenes.appendChild($optionAlmacen);
            })
          }
        });
      }
    
      $(document).on("click", ".btnEditar", function(){
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Editar Cliente");
        $('#modalCRUD').modal('show');   
        $card_contactos = document.getElementById("card-contactos");
        $card_contactos.classList.remove("d-none");
        fila = $(this).closest("tr");
        let id_usuario = fila.find('td:eq(0)').text();    

        let datosUpdate = new FormData();
        datosUpdate.append('accion', 'traerClienteUpdate');
        datosUpdate.append('id_cliente', id_usuario);
        $.ajax({
          data: datosUpdate,
          url: './models/administrar_clientes.php',
          method: "post",
          cache: false,
          contentType: false,
          processData: false,
          beforeSed: function(){
            //$('#procesando').modal('show');
          },
          success: function(datosProcesados){
            let datosInput = JSON.parse(datosProcesados);
            $("#alias").val(datosInput[0].alias);
            $("#razonSocial").val(datosInput[0].razon_social);
            $("#cuit").val(datosInput[0].cuit);
            $("#telefono1").val(datosInput[0].tel1);
            $("#telefono2").val(datosInput[0].tel2);
            $("#fax").val(datosInput[0].fax);
            $("#email").val(datosInput[0].email);
            $("#direccionCliente").val(datosInput[0].direccion_cliente);
            $("#localidad").val(datosInput[0].localidad_cliente);
            $("#provinciaCli").val(datosInput[0].provincia);
            $("#estado").val(datosInput[0].activo);
            $("#condicionIVA").val(datosInput[0].iva);
            $('#exampleModalLabel').html("Editar cliente");
            $('#id_cliente').html(datosInput[0].id_cliente);
            accion = "updateCliente";
          }
        });

        $('#modalCRUD').modal('show');
      });

      $('#formUsuarios').submit(function(e){
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página

        alias = $.trim($('#alias').val())
        razon_social = $.trim($('#razonSocial').val());    
        cuit = $.trim($('#cuit').val());
        tel1 = $.trim($('#telefono1').val());
        tel2 = $.trim($('#telefono2').val());
        fax = $.trim($('#fax').val());
        email = $.trim($('#email').val());
        direccionCliente = $.trim($('#direccionCliente').val());
        localidad = $.trim($('#localidad').val());
        provinciaCli = $.trim($('#provinciaCli').val());
        estado = $.trim($('#estado').val());
        condicion_iva = $.trim($('#condicionIVA').val());     
        id_cliente = $.trim($('#id_cliente').html());
        let id_empresa = document.getElementById("id_empresa").textContent; 

        $.ajax({
          url: "models/administrar_clientes.php",
          type: "POST",
          datatype:"json",
          data:  {accion: accion,id_cliente:id_cliente, alias: alias, razon_social:razon_social, cuit:cuit, tel1:tel1, tel2:tel2, fax:fax, email:email, direccionCliente:direccionCliente, localidad: localidad, provinciaCli: provinciaCli, estado:estado, condicion_iva:condicion_iva, id_empresa:id_empresa},    
          success: function(data) {
            tablaClientes.ajax.reload(null, false);
           }
        });
        $('#modalCRUD').modal('hide'); 
        swal({
          icon: 'success',
          title: 'Cliente creado correctamente'
        });
      });


      $("#btnNuevo").click(function(){
        accion = "nuevoCliente"
        $("#formUsuarios").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Alta de Cliente");
        $('#modalCRUD').modal('show');
      });

      //Borrar
      $(document).on("click", ".btnBorrar", function(){
        fila = $(this);
        cliente_id = parseInt($(this).closest('tr').find('td:eq(0)').text());

        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado este cliente, no volveras a verlo",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            accion = "eliminarCliente";
            $.ajax({
              url: "models/administrar_clientes.php",
              type: "POST",
              datatype:"json",
              data:  {accion:accion, cliente_id:cliente_id},
              success: function() {
                tablaClientes.row(fila.parents('tr')).remove().draw();
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

        let id_cliente = $.trim($('#id_cliente').html());
        accion = "traerContactos";
        if(realizarBusquedaContactos ==0){
          
        realizarBusquedaContactos=1;
          $.ajax({
            url: "models/administrar_clientes.php",
            type: "POST",
            datatype:"json",
            data:  {accion:accion, id_cliente:id_cliente},
            success: function(respuesta) {

              let $tablaContactos = document.querySelector("#tablaContactos tbody");
              let respuestaJson = JSON.parse(respuesta);
              if(respuestaJson.contactos.length==0){
                $trEmpty = document.createElement("tr");
                $tablaContactos.innerHTML="";
                $trEmpty.innerHTML=`<td colspan="7">No hay datos</td>`;
                $tablaContactos.appendChild($trEmpty);
              }else{
                  jsonResultado=respuestaJson;
                  arrayBuscador = [];
              }
              completarTablaContactos(respuestaJson)
            }
          });
        }
      });

      /*COMPLETO TABLA CONTACTOS*/
      function completarTablaContactos(respuestaJson){
        console.log(respuestaJson)
        let $tablaContactos = document.querySelector("#tablaContactos tbody");
        $tablaContactos.innerHTML = "";
        respuestaJson.contactos.forEach((contactos)=>{
          $tr = document.createElement("tr");
          $tr.innerHTML=`<td>${contactos.id_contacto}</td>
                          <td>${contactos.nombre_completo}</td>
                          <td>${contactos.email}</td>
                          <td>${contactos.telefono}</td>
                          <td><span class="d-none">${contactos.id_cargo}</span>${contactos.cargo}</td>
                          <td>${contactos.activo}</td>
                          <td><span class="d-none">${contactos.id_direccion}</span> ${contactos.direccion}</td>
                          <td>
                            <div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarContacto'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrarContacto'><i class='fa fa-trash-o'></i></button></div></div>
                          </td>
                        `;
          $tablaContactos.appendChild($tr);
        })

        if (respuestaJson.locaciones.length >0 ) {
          /*Identifico select tipo de direccion*/
          $selLocaciones = document.getElementById("locaciones");
          $selLocaciones.innerHTML = "<option value=''>Seleccione</option>";
          /*Convierto en json la respuesta del servidor*/
          //respuestaJson = JSON.parse(respuesta);

          /*Genero los options del select provincia*/
          respuestaJson.locaciones.forEach((locaciones)=>{
            let $option = document.createElement("option");
            let optionText = document.createTextNode(locaciones.direccion);
            $option.appendChild(optionText);
            $option.setAttribute("value", locaciones.id_locaciones);
            $selLocaciones.appendChild($option);
          })
        }else{

        }
      }

      /*BUSCADOR CONTACTOS*/
      $(document).on("keyup", ".buscadorContactos", function(e){
        let caracter= e.target.value.toLowerCase();
        if(caracter !==""){
          let arrayDatos = Array.from(jsonResultado);
        }else{
          console.log("se vació el campo");
        }
      })

      /*ACCIONES CONTACTO*/

      $(document).on("click", '.btnAddContacto', function(){
        $("#formAddContactos").trigger("reset");
        $("#addContactos").css("display", "block");
        $(".tablaContactos").css("display", "none");
        /*document.getElementById("contBuscadorContactos").classList.remove("d-block");
        document.getElementById("contBuscadorContactos").classList.add("d-none");*/
        accionContacto = "addContacto";
      })

      $(document).on("click", '.cancelAddContacto', function(){
        $("#addContactos").css("display", "none");
        $(".tablaContactos").css("display", "block");
        /*document.getElementById("contBuscadorContactos").classList.remove("d-none");
        document.getElementById("contBuscadorContactos").classList.add("d-block");*/
      })

      /*SUBMIT ADHERIR CONTACTO*/
      $('#formAddContactos').submit(function(e){

        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   

        nombreContacto = $.trim($('#nombreContacto').val());
        emailContacto = $.trim($('#emailContacto').val());
        telContacto = $.trim($('#telContacto').val());
        estadoContacto = $.trim($('#estadoContacto').val());
        cargoContacto = $.trim($('#cargoContacto').val());
        //let id_cliente = $.trim($('#id_clienteC').html());
        locaciones = $.trim($('#locaciones').val());
        id_cliente = $.trim($('#id_cliente').html());
        

        if(accionContacto == 'editarContacto'){
          contactoID = $.trim($('#id_contacto').html());
        }else{
          contactoID = "";
        }

        $.ajax({
          url: "models/administrar_clientes.php",
          type: "POST",
          datatype:"json",
          data:  {accion: accionContacto,id_cliente:id_cliente, id_contacto:contactoID, nombreContacto:nombreContacto, emailContacto:emailContacto, telContacto:telContacto, estadoContacto:estadoContacto, cargoContacto:cargoContacto, locaciones: locaciones},
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
        let id_cliente = $.trim($('#id_cliente').html());

        accion = "traerContactos";
        $.ajax({
          url: "models/administrar_clientes.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, id_cliente:id_cliente},
          success: function(respuesta) {
            $('#resParam').html(respuesta);

            let $tablaContactos = document.querySelector("#tablaContactos tbody");
            let respuestaJson = JSON.parse(respuesta);
            if(respuestaJson.length==0){
              $tablaContactos.innerHTML = "";
              $trEmpty = document.createElement("tr");
              $trEmpty.innerHTML=`<td colspan="7">No hay datos</td>`;
              $tablaContactos.appendChild($trEmpty);
            }else{
              $tablaContactos.innerHTML = "";
              respuestaJson.contactos.forEach((contactos)=>{
                $tr = document.createElement("tr");
                $tr.innerHTML=`<td>${contactos.id_contacto}</td>
                                <td>${contactos.nombre_completo}</td>
                                <td>${contactos.email}</td>
                                <td>${contactos.telefono}</td>
                                <td><span class="d-none">${contactos.id_cargo}</span>${contactos.cargo}</td>
                                <td>${contactos.activo}</td>
                                <td><span class="d-none">${contactos.id_direccion}</span> ${contactos.direccion}</td>
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
              url: "models/administrar_clientes.php",
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
          estadoContacto: $(this).closest('tr').find('td:eq(5)').text(),
          id_locacion : $(this).closest('tr').find('td:eq(6)').find('span').text()
        }

        accionContacto = "editarContacto";
        $("#addContactos").css("display", "block");
        $("#nombreContacto").val(datosContactos.nombreContacto);
        $("#emailContacto").val(datosContactos.emailContacto);
        $("#telContacto").val(datosContactos.telContacto);
        $("#estadoContacto").val(datosContactos.estadoContacto);
        $("#cargoContacto").val(datosContactos.id_cargo);
        $("#locaciones").val(datosContactos.id_locacion);
        $('#id_contacto').html(datosContactos.id_contacto);
        $(".tablaContactos").css("display", "none");
      })

      /*
        #################DOMICILIOS##########################
      */

      $(document).on("click", '.btnDirecciones', function(){
        let id_cliente = $.trim($('#id_cliente').html());
        accion = "traerDirecciones";

        if(realizarBusquedaDirecciones ==0){
          realizarBusquedaDirecciones=1;

          $.ajax({
            url: "models/administrar_clientes.php",
            type: "POST",
            datatype:"json",
            data:  {accion:accion, id_cliente:id_cliente},
            success: function(respuesta) {

                let $tablaDomicilios = document.querySelector("#tablaDomicilios tbody");
                let respuestaJson = JSON.parse(respuesta);
                if(respuestaJson.length==0){
                  $tablaDomicilios.innerHTML="";
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
                                    <td><span class="d-none">${domicilios.id_centro_costos}</span>${domicilios.centro_costo}</td>
                                    <td><span class="d-none">${domicilios.id_almacen}</span>${domicilios.almacen}</td>
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
        centro_costos_ubicacion = $.trim($('#centro_costos_ubicacion').val());
        almacen_ubicacion = $.trim($('#almacen_ubicacion').val());
        let id_cliente = $.trim($('#id_cliente').html());
        id_domicilio = $.trim($('#id_domicilio').html());

        $.ajax({
          url: "models/administrar_clientes.php",
          type: "POST",
          datatype:"json",
          data:  {accion: accionDomicilio,id_cliente:id_cliente, id_domicilio:domicilioID, direccion:direccion, tipoDireccion:tipoDireccion, provincia:provincia, estadoDomicilio:estadoDomicilio,centro_costos_ubicacion:centro_costos_ubicacion,almacen_ubicacion:almacen_ubicacion},
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
        let id_cliente = $.trim($('#id_cliente').html());
        //$("#id_clienteD").html(id_cliente); 
        accion = "traerDirecciones";
        $.ajax({
          url: "models/administrar_clientes.php",
          type: "POST",
          datatype:"json",
          data:  {accion:accion, id_cliente:id_cliente},
          success: function(respuesta) {

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
                                <td><span class="d-none">${domicilios.id_centro_costos}</span>${domicilios.centro_costo}</td>
                                <td><span class="d-none">${domicilios.id_almacen}</span>${domicilios.almacen}</td>
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
              url: "models/administrar_clientes.php",
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
          estadoDomicilio: $(this).closest('tr').find('td:eq(6)').text(),
          id_centro_costos: $(this).closest('tr').find('td:eq(4)').find('span').text(),
          id_almacen: $(this).closest('tr').find('td:eq(5)').find('span').text(),
        }

        console.log(datosDomicilio);

        accionDomicilio = "editarDomicilio";
        $("#formAddDireccion").trigger("reset");
        $("#addDireccion").css("display", "block");
        $(".tablaDomicilios").css("display", "none");
        $("#id_domicilio").html(datosDomicilio.id_domicilio);
        $("#direccion").val(datosDomicilio.direccion);
        $("#provincia").val(datosDomicilio.provincia);
        $("#tipoDireccion").val(datosDomicilio.tipo_domicilio);
        $("#estadoDomicilio").val(datosDomicilio.estadoDomicilio);
        $("#centro_costos_ubicacion").val(datosDomicilio.id_centro_costos);
        $("#almacen_ubicacion").val(datosDomicilio.id_almacen);
      })
    </script>
  </body>
</html>