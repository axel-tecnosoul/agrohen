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
    <title>MYLA - Ordenes de compra</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/datatable-extension.css">
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


    .btnCancelAdd{
      position: fixed;
    }

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
                    <h3>Ordenes de compra</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Ordenes de compra</li>
                      <span class="d-none" id="id_empresa"><?php echo $_SESSION['rowUsers']['id_empresa']?></span>
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
                    <span id="minimoAprobacion" class="d-none">
                    <?php 
                      echo $_SESSION['rowUsers']['monto_aprobacion_minimo'];
                    ?>
                    </span>
                    <span id="maximoAprobacion" class="d-none">
                    <?php 
                      echo $_SESSION['rowUsers']['monto_aprobacion_maximo'];
                    ?>
                    </span>
                    <div class="row">
                      <div class="col-3">
                        <label>Fecha desde:</label>
                        <input type="date" id="fdesde" class="form-control">
                      </div>
                      <div class="col-3">
                        <label>Fecha hasta:</label>
                        <input type="date" id="fhasta" class="form-control">
                      </div>
                      <div class="col-3 align-self-end">
                        <button class="btn btn-success btnBuscar">Buscar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="card">
                  <div class="card-header">
                    <h5>Estados Ordenes</h5>
                  </div>
                  <div class="card-body chart-block chart-vertical-center" id="contGraf">
                    <canvas id="myDoughnutGraph"></canvas>
                  </div>
                  <div class="card-body chart-block chart-vertical-center d-none" id="errorGraf">
                    No hay datos
                  </div>
                </div>
              </div>
              <div class="col-xl-8">
                <div class="card">
                  <div class="card-header">
                    <h5>Ordenes de compra</h5>
                    <button id="btnNuevo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" id="contTablaListas" >
                      <table class="table table-hover nowrap" style="width: 100%;" id="tablaOrdenes">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Proveedor</th>
                            <th>Almacen</th>
                            <th>Total</th>
                            <th>Fecha</th>
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
                      <span id="id_item" class="d-none"></span>
                      <span id="id_itemC" class="d-none"></span>
                      <span class="id_ordenEditar d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>   
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Proveedor:</label>
                              <select class="form-control" id="proveedor" required>
                                <option value="0">Seleccione proveedor...</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Almacen:</label>
                              <select class="form-control" id="almacen" required>
                                <option value="0">Seleccione almacen...</option>
                              </select>
                            </div>
                          </div> 
                      </div>
                      <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="" class="col-form-label">Centro de costos:</label>
                              <select class="form-control" id="cCostos" required>
                                <option value="0">Seleccione centro de costos...</option>
                              </select>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="" class="col-form-label">ingrese 3 letras para buscar:</label>
                              <input type="text" name="" class="form-control" id="item_buscar">
                              <div style="background-color:#EBF5FB; border-radius: 0 0 5px 5px;" id="resultadoSearch"></div>
                          </div>
                        </div>
                        <div class="d-none col-lg-5" id="contAgregarProdItem">
                          <div class="row">
                            <div class="col-lg-6">
                                <input type="hidden" id="id_item_agragar">
                                <label for="" class="col-form-label">Cantidad:</label>
                                <input type="number" name="" id="cantidad" class="form-control">
                            </div>
                            <div class="col-lg-6 align-self-end mt-2 mt-lg-0">
                                  <button class='btn btn-success btnAgregarProdItem'><i class='fa fa-plus-circle'></i></button>
                            </div>
                          </div>
                        </div>
                      </div>                
                      <div class="table-responsive" id="contTablaOrden" style="">
                      <table class="table table-hover" id="tablaItems">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>imagen</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unit.</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Comentarios:</label>
                              <textarea class="form-control" id="comentarios"  placeholder="Ingrese comentarios"></textarea>
                            </div>
                          </div>
                        <div class="col-6">

                          
                        </div>
                      </div>
                       <span class="mt5">
                          <label class="font-weight-bold h4">Total:</label>
                          <span class="totalFormateado h4">$ 0,00</span>
                          <span class="total d-none"></span>
                      </span>
                    </div>
                    <div id="contAddItem" class="d-none border p-3 rounded border-dark">
                      <h5 class="d-block text-center font-weight-bold">Agregar Item</h5>
                      <form id="formAddItem">
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
                                <label for="" class="col-form-label">Precio</label>
                                <input type="number" class="form-control" id="precioNewItem">
                                </div>
                            </div>
                            <!--<div class="col-lg-4">    
                                <div class="form-group">
                                <label for="" class="col-form-label">Centro de costos</label>
                                <select class="form-control" id="cCostos" required>
                                  <option value="">Seleccione</option>
                                </select>
                                </div>            
                            </div>-->
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
                          <div class="col-lg-8">
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
                        <button type="button" class="btn btn-light bntCancelAddItem">Cancelar</button>
                        <button type="submit" id="" class="btn btn-dark">Guardar</button>
                      </form>
                      </div>
                  </div>
                  <div class="modal-footer" id="footerOC">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                  </div>
              </div>
          </div>
      </div>
 
    <!-- FINAL MODAL CRUD-->


    <!--Modal para CRUD recibir pedido-->
      <div class="modal fade" id="recibirPedidos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_item" class="d-none"></span>
                      <span id="id_itemC" class="d-none"></span>
                      <span class="id_ordenEditarRecibido d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>   
                  <div class="modal-body">               
                      <div class="table-responsive" id="contTablaOrden" style="">
                      <table class="table table-hover" id="tablaRecibirOrden">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Producto</th>
                            <th>Cantidad Pedida</th>
                            <th>Cant. recibida</th>
                            <th>Recibe</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                       <!--<span class="mt5">
                          <label class="font-weight-bold h4">Total:</label>
                          <span class="totalFormateado h4">$ 0,00</span>
                          <span class="total d-none"></span>
                      </span>-->
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="submit" id="btnGuardarRecibido" class="btn btn-dark">Guardar</button>
                  </div>
              </div>
          </div>
      </div>
 
    <!-- FINAL MODAL CRUD recibir pedidos-->



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

    <!--Modal para ver detalle-->
      <div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <!--SPANS CON INFORMACIÓN QUE VOY A OCUPAR-->
                      <span id="id_orden_ver" class="d-none"></span>
                      <span id="proveedor_ver" class="d-none"></span>
                      <span id="almacen_ver" class="d-none"></span>
                      <span id="total_ver" class="d-none"></span>
                      <span id="fecha_ver" class="d-none"></span>
                      <span id="estado_ver" class="d-none"></span>
                      <!--SPANS CON INFORMACIÓN QUE VOY A OCUPAR-->
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>   
                  <div class="modal-body">
                      <div class="default-according" id="verDetalleOrdenes">
                      <div class="card">
                        <div class="card-header" id="heading2">
                          <h5 class="mb-0">
                            <button class="btn btn-link collapsed btnDetalleOrden" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="heading2">Detalle orden</button>
                          </h5>
                        </div>
                        <div class="collapse" id="collapse2" aria-labelledby="heading2" data-parent="#accordionclose">
                          <div class="card-body">
                            <div id="titleDetalles" class="row d-none">
                              <div class="offset-10 col-4 mb-4">
                                <button class="btn btn-primary btnImprimir"><i class="fa fa-print"></i></button>
                              </div>
                              <div class="col-4">
                                Número: <span class="font-weight-bold" id="dnum"></span>
                              </div>
                              <div class="col-4">
                                Proveedor: <span class="font-weight-bold" id="dprov"></span>
                              </div>
                              <div class="col-4">
                                Almacen: <span class="font-weight-bold" id="dalm"></span>
                              </div>
                              <div class="col-4">
                                Total: <span class="font-weight-bold" id="dtot"></span>
                              </div>
                              <div class="col-4">
                                Fecha: <span class="font-weight-bold" id="dfech"></span>
                              </div>
                              <div class="col-4">
                                Estado: <span class="font-weight-bold" id="dest"></span>
                              </div>
                            </div>

                            <div class="table-responsive d-none mt-3" id="contTablaDO">
                              <table class="table table-hover" id="tablaDetalleOrden">
                                <thead class="text-center">
                                  <tr>
                                    <th class="text-center">#ID</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio unit.</th>
                                    <th>Total</th>
                                  </tr>
                                </thead>
                                <tbody id="tbodyDetalleOrden">
                                </tbody>
                              </table>
                            </div>
                            <div id="loaderDetalle" style="padding-left: 45%;">
                                <div class="loader-box text-center">
                                  <span class="rotate dotted"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header" id="heading1">
                          <h5 class="mb-0">
                            <button class="btn btn-link btnEstados" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="heading1">Historial de estados</button>
                          </h5>
                        </div>
                        <div class="collapse" id="collapse1" aria-labelledby="heading1" data-parent="#accordionclose">
                          <div class="card-body">
                            <div class="timeline-small" id="historialEstados">
                              <div id="loaderEstados" style="padding-left: 45%;">
                                <div class="loader-box text-center">
                                  <span class="rotate dotted"></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header" id="heading3">
                          <h5 class="mb-0">
                            <button class="btn btn-link collapsed" id="btnAdjuntos" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">Adjuntos</button>
                          </h5>
                        </div>
                        <div class="collapse" id="collapse3" aria-labelledby="heading3" data-parent="#accordionclose">
                          <div class="card-body">
                            <button id="btnNuevoAdjunto" type="button" class="btn btn-outline-primary mb-2" data-toggle="modal"><i class="fa fa-cloud-upload"></i></button>
                            <div id="inputFile" style="display: none;" class="border p-1 rounded mb-2">
                              <input type="file" class="form-control" id="adjunto">
                              <textarea class="form-control mt-2" id="comentarios" maxlength="100" placeholder="comentarios"></textarea>
                              <div class="pt-2" id="msgErrAdjunto" style="display: none">
                                <div class="alert alert-outline-danger" role="alert" id="textoErrorAdjunto">
                                </div>
                              </div>
                              <button class="btn btn-success mt-2" id="btnEnviarAdj">Enviar</button>
                            </div>
                            <div class="table-responsive">
                              <table class="table table-hover" id="tablaAdjuntos">
                                <thead>
                                    <th>#ID</th>
                                    <th>Archivo</th>
                                    <th>Usuario</th>
                                    <th>Comentarios</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody></tbody>
                                <tfoot></tfoot>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
          </div>
      </div>
 
    <!-- FINAL MODAL CRUD-->

    <!-- MODAL MOSTRAR CONTACTOS -->
    <div class="modal fade mt-5" id="contactos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center p-4">
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
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
     </div>
    <!-- FIN MODAL MOSTRAR CONTACTOS -->

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
    <script src="assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
    <script src="assets/js/chart/chartjs/chart.min.js"></script>
    <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
    <script src="assets/js/chat-menu.js"></script>
    <script src="assets/js/tooltip-init.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="assets/js/script.js"></script>
    <!--<script src="assets/js/theme-customizer/customizer.js"></script>-->
    <!-- Plugin used-->
    <script type="text/javascript">

       itemsOrden = new Map();
       montoTotalOC = new Map();
       itemsPedir = new Map();
       itemsXProveedor = new Map();
       itemsImprimir = [];
       var accion = "";
       let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
      $(document).ready(function(){
        cargarDatosComponentes();
        cargarEventos();
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
        tablaOrdenes= $('#tablaOrdenes').DataTable({
            responsive: false,
            "ajax": {
                "url" : "./models/administrar_ordenes.php?accion=traerOrdenes&id_empresa="+id_empresa,
                "dataSrc": "",
              },
            "stateSave": true,
            "columns":[
              {"data": "id_oc"},
              {"data": "proveedor"},
              {"data":"almacen"},
              {"data": "total"},
              {"data": "fecha"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{

                          const estados = {
                            1: "Borrador",
                            2: "Verificada",
                            3: "Aceptada",
                            4: "En distribución",
                            5: "Cerrada",
                            6: "Finalizada"
                          }

                          $options="";

                          for(key in estados){
                                  if(full.id_estado == key){
                                    $options+=`<option selected value="${full.id_estado}">${estados[key]}</option>`
                                  }else{

                                    switch(full.id_estado){
                                      case "1":
                                        if(key == 5){
                                          $options+=`<option value="${key}">${estados[key]}</option>`
                                        }
                                      break
                                      case "2":
                                        if(key == 3 || key == 5){
                                        $options+=`<option value="${key}">${estados[key]}</option>`
                                      }
                                      break;

                                      case "4":
                                        if(key == 5){
                                        $options+=`<option value="${key}">${estados[key]}</option>`
                                      }                                    }

                                    
                                    ;
                                  }
                                  }
                        $selectInit = `<select class="estado">`;
                        $selectEnd = "</select>";
                        $selectComplete = $selectInit + $options+$selectEnd
      
                          return $selectComplete;
                        };
                    }
                },
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button><button class='btn btn-primary btnContactos'><i class='fa fa-comments-o'></i></button><button class='btn btn-light btnRecibirOrden'><i class='fa fa-truck'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            ],
            "language":  idiomaEsp
        });

       cargarGrafico();


      $('#modalCRUD').on('hidden.bs.modal', function (e) {
          montoTotalOC.clear();
          itemsOrden.clear();
          document.querySelector(".id_ordenEditar").innerHTML="";
          document.getElementById("item_buscar").value="";
          document.getElementById("id_item_agragar").value="";
          document.getElementById("cantidad").value = "";
          document.getElementById("contAgregarProdItem").classList.add("d-none")

          document.getElementById("contAddItem").classList.add("d-none");
          document.getElementById("contTablaOrden").classList.remove("d-none");
          document.getElementById("resultadoSearch").innerHTML="";
          document.getElementById("item_buscar").focus();
          document.getElementById("footerOC").classList.remove("d-none");
          $("#formAddItem").trigger("reset");

        });

      $('#modalVer').on('hidden.bs.modal', function (e) {
        $("#collapse1").collapse("hide");
        $("#collapse2").collapse("hide");
        $("#collapse3").collapse("hide");
        $("#loaderEstados").css("display", "block");
        $("#loaderDetalle").css("display", "block");
      });

      $('#recibirPedidos').on('hidden.bs.modal', function (e) {
          itemsPedir.clear();
        });
      
      });

    function cargarDatosComponentes(){
                let datosIniciales = new FormData();
                let id_empresa = parseInt(document.getElementById("id_empresa").innerText)
                datosIniciales.append('id_empresa', id_empresa);
                datosIniciales.append('accion', 'traerDatosInicialesOC');
                $.ajax({
                    data: datosIniciales,
                    url: "./models/administrar_ordenes.php",
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


                        /*Identifico el select centro de costo*/
                        $selectCentroCosto = document.getElementById("cCostos");


                        /*Identifico el select de categorias*/
                        $selectCategorias = document.getElementById('categoria');

                        /*Identifico el select de unidad de medida*/
                        $selectUmedida = document.getElementById('Umedida');

                        /*Identifico el select tipo de items*/
                        $selectTipo = document.getElementById('tipo');

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
                        })

                        /*Genero los options del select Centro Costos*/

                        respuestaJson.centroCostos.forEach((cCosto)=>{
                          let $option = document.createElement("option");
                          let optionText= document.createTextNode(cCosto.nombreCC);
                          $option.appendChild(optionText);
                          $option.setAttribute("value", cCosto.id_cc);
                          $selectCentroCosto.appendChild($option);
                        });

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


                    }
                });
            }

  function cargarEventos(){
    $btnAdjuntos = document.getElementById("btnAdjuntos");
    $btnAdjuntos.addEventListener("click", traerAdjuntos);
  }

$("#btnNuevo").click(function(){
      window.accion = "addOrdenCompra";
    //$("#formItems").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Nueva orden de compra");
    $('#tablaItems').DataTable().clear().draw();
    $("#tablaItems").dataTable().fnDestroy();
    $("#tablaItems").dataTable({"language":  idiomaEsp});
    $("#proveedor").prop("disabled", false);
    $("#proveedor").val(0);
    $("#almacen").val(0);
    $("#cCostos").val(0);
    $("#comentarios").val("");
    $(".totalFormateado").text("$ 0,00");
    $(".total").text("0.00");
    $('#modalCRUD').modal('show');      
});




/*BUSCAR ITEMS POR PROVEEDOR*/
$(document).on("change", "#proveedor", function(){
    let id_proveedor = $(this).val();
    itemsXProveedor.clear();
    document.getElementById("item_buscar").value = "";
    let accion = "traerItems";
    $.ajax({
          url: "./models/administrar_ordenes.php",
          type: "POST",
          datatype: "json",
          data: {accion: accion, id_proveedor: id_proveedor},
          success: function(response){
            //itemsOrden.set(id_item, {cantidad, precio});
              let respuestaJson = JSON.parse(response);

              respuestaJson.forEach(item=>{
                itemsXProveedor.set(item.id_item, {imagen: item.imagen, item:item.item, precio:item.precio})
              });
          }
    });
});

$(document).on("keyup", "#item_buscar", function(){
    let palabraABuscar = $(this).val();
    let $divResultadoSearch = document.getElementById("resultadoSearch");
    let $id_item_agragar = document.getElementById("id_item_agragar");
    let contador = 0;

    if(palabraABuscar.length > 2){
      $id_item_agragar.value = "";
      $divResultadoSearch.innerHTML="";
      for(let[key, value] of itemsXProveedor){

        /*console.log(value.item.toLowerCase().indexOf(palabraABuscar.toLowerCase()))
        console.log(value.item);*/

      if (value.item.toLowerCase().indexOf(palabraABuscar.toLowerCase())!=-1){
          let precio = new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(value.precio);
          let $span = `<a href="" class="selItem" id="${key}">
                        <span class="d-block p-1">
                          ${value.item}  ${precio}
                        </span>
                      </a>`
           $divResultadoSearch.innerHTML+=$span;
           contador++;
      }
    }

    if(contador == 0 && document.getElementById("proveedor").value !=0){
      let $spanNone = `<span class="d-block p-2 text-center">
                        No se encontraron resultados.<br>
                        <button class="btn btn-success btnAgregarNuevoProducto" data-original-title="" title="">Agregar</button>
                  </span>`
           $divResultadoSearch.innerHTML+=$spanNone;
    }

    if(contador == 0 && document.getElementById("proveedor").value ==0){
      let $spanNone = `<span class="d-block p-2 text-center">
                        Seleccione proveedor.<br>
                  </span>`
           $divResultadoSearch.innerHTML+=$spanNone;
    }

    }else{
      $divResultadoSearch.innerHTML="";
    }



})

$(document).on("click", ".selItem", function(){
  event.preventDefault();

  let id_producto = $(this).attr("id");
  let $item_buscar = document.getElementById("item_buscar");
  let $resultadoSearch = document.getElementById("resultadoSearch");
  let $contAgregarProdItem = document.getElementById("contAgregarProdItem");
  let $id_item_agragar = document.getElementById("id_item_agragar");
  let $cantidadEnviar = document.getElementById("cantidad");

  $item_buscar.value=itemsXProveedor.get(id_producto).item;
  $resultadoSearch.innerHTML="";
  $id_item_agragar.value=id_producto;
  $contAgregarProdItem.classList.remove("d-none");
  document.getElementById("contTablaOrden").classList.remove("d-none");
  $cantidadEnviar.focus();

});


$(document).on("click", ".btnAgregarProdItem", function(){

    let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
    let id_proveedor = $('#proveedor').val();
    let cCostos = $('#cCostos').val();
    let id_almacen = $('#almacen').val();
    let id_item_agragar = document.getElementById("id_item_agragar").value;
    let cantidad_agregar = document.getElementById("cantidad").value;

    let totalEnviar = parseFloat($('.total').text());
    let id_orden= parseInt($(".id_ordenEditar").text());
    let comentarios = $("#comentarios").val();

    if (id_almacen == 0 || id_proveedor ==0 || id_item_agragar =="" || cCostos ==0) {
      swal({
                          icon: 'error',
                          title: 'Debe seleccionar proveedor, almacen, centro de costos e items'
                        });
  }else{

    const itemsOrdenArray = [];

    let precio = parseFloat(itemsXProveedor.get(id_item_agragar).precio);
    itemsOrdenArray.push({
          id: id_item_agragar,
          valor: cantidad_agregar,
          precio
        })

    items = JSON.stringify(itemsOrdenArray);

    if (!isNaN(cantidad_agregar)) {
     let precioTotal = precio * cantidad_agregar;

    let monto = parseFloat($('.total').text()) +precioTotal
    
    let montoFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(monto);
    $('.totalFormateado').text(montoFormateado);
    $('.total').text(monto)
    montoTotalOC.set(parseInt(id_item_agragar), precioTotal)
  }


  let totalEnviar = parseFloat($('.total').text());

    //let totalEnviar = precio * parseFloat(cantidad_agregar);

    if(!isNaN(id_orden)){
      window.accion = "UpdateOrden";
    }else{
      //window.accion = "addOrdenCompraaaa"
    }

    

    $.ajax({
            url: "models/administrar_ordenes.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion,items:items, total:totalEnviar, proveedor: id_proveedor, almacen: id_almacen, cCostos: cCostos, id_orden: id_orden, comentarios: comentarios, id_empresa: id_empresa},    
            success: function(respuesta) {
              
              
              //$('#modalCRUD').modal('hide');
              tablaOrdenes.ajax.reload(null, false);

              document.getElementById("item_buscar").value="";
              document.getElementById("item_buscar").focus();
              document.getElementById("id_item_agragar").value="";
              document.getElementById("cantidad").value = "";
              document.getElementById("contAgregarProdItem").classList.add("d-none")
              
              document.querySelector(".id_ordenEditar").innerHTML = respuesta;
              window.accion = "UpdateOrden";
              reloadTablaItems("traerItemsUpdateOrden",id_proveedor, respuesta);
              /*swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });   */ 
             }
          })

  }

})

$(document).on("keypress", "#cantidad", function(e){
    if(e.keyCode == 13){
      document.querySelector(".btnAgregarProdItem").click();
    }
});


function reloadTablaItems(accion, id_proveedor, id_orden){

  $("#tablaItems").dataTable().fnDestroy();

  tablaItems= $('#tablaItems').DataTable({
            "ajax": {
                "url" : "./models/administrar_ordenes.php?accion="+accion+"&id_proveedor="+id_proveedor+"&id_orden="+id_orden,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_item"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          let $img = "";
                          if (full.imagen !=""){
                             
                             return `<img src="./views/img_items/${full.imagen}" class="img-thumbnail">`;
                          }else{
                            return ""
                          }                               
                          
                        };
                    }
                },
              {"data": "item"},
              {
                    render: function(data, type, full, meta) {
                        return `<input class="cantidadPedir form-control quitFlechas" type="number" value="${full.cantidad}" min="0" step="0" >`;
                    }
                },
              {
                    render: function(data, type, full, meta) {
                        return '<span class="d-none">'+full.precio+'</span><span class="">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.precio)+'</span>';
                    }
                }
            ],
            "language":  idiomaEsp
        });
}


$(document).on("click", ".btnAgregarNuevoProducto", function(){
    document.getElementById("contTablaOrden").classList.add("d-none");
    document.getElementById("resultadoSearch").innerHTML="";
    document.getElementById("descripcion_producto").value = document.getElementById("item_buscar").value;
    document.getElementById("item_buscar").value="";
    document.getElementById("contAddItem").classList.remove("d-none");
    document.getElementById("descripcion_producto").focus();
    document.getElementById("footerOC").classList.add("d-none");
})

$(document).on("click", ".bntCancelAddItem", function(){

    document.getElementById("contAddItem").classList.add("d-none");
    document.getElementById("contTablaOrden").classList.remove("d-none");
    document.getElementById("resultadoSearch").innerHTML="";
    document.getElementById("item_buscar").focus();
    document.getElementById("footerOC").classList.remove("d-none");
    $("#formAddItem").trigger("reset");


})


$(document).on("click", "#btnGuardar", function(){

  let id_proveedor = $('#proveedor').val();
  let cCostos = $('#cCostos').val();
  let id_almacen = $('#almacen').val();
  let totalEnviar = parseFloat($('.total').text());
  let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
  let id_orden= parseInt($(".id_ordenEditar").text());
  let comentarios = $("#comentarios").val();
  if (isNaN(totalEnviar)) {
    totalEnviar = 0;
  }
  if (id_almacen == 0 || id_proveedor ==0 || totalEnviar ==0 || cCostos ==0) {
    swal({
                        icon: 'error',
                        title: 'Debe seleccionar proveedor, almacen, centro de costos e items'
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
            data:  {accion: accion,items:items, total:totalEnviar, proveedor: id_proveedor, almacen: id_almacen, cCostos: cCostos, id_orden: id_orden, comentarios: comentarios, id_empresa: id_empresa},    
            success: function(data) {
              $('#modalCRUD').modal('hide');
              tablaOrdenes.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });    

             }
          })
  }
  
})

$(document).on("click", ".btnEditar", function(){
 
  fila = $(this).closest("tr");
  let id_orden = fila.find('td:eq(0)').text();
  let accion = "traerOrdenUpdate";

  let estado = fila.closest('tr').find('select option:selected').val();

  if(estado == 1){

      $.ajax({
        url:"models/administrar_ordenes.php",
        type: "POST",
        datatype: "json",
        data: {accion: accion, id_orden: id_orden},
        success: function(response){
           
            $(".modal-header").css( "background-color", "#22af47");
            $(".modal-header").css( "color", "white" );
            $(".modal-title").html(`Editar orden de compra numero: <span>${id_orden}</span>`);
            $('#modalCRUD').modal('show');
            $(".id_ordenEditar").html(id_orden);
            let respuestaJson = JSON.parse(response);

            $("#proveedor").val(respuestaJson.orden[0].id_proveedor);
            $("#proveedor").prop("disabled", true);
            $("#almacen").val(respuestaJson.orden[0].id_almacen);
            $("#comentarios").val(respuestaJson.orden[0].comentarios);
            $("#cCostos").val(respuestaJson.orden[0].id_cc);
            let montoFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(respuestaJson.orden[0].total);
            $('.totalFormateado').text(montoFormateado);
            $('.total').text(respuestaJson.orden[0].total);

            respuestaJson.precioCant.forEach((precioCant)=>{
              

              let id_item = parseInt(precioCant.id_item);
              let precio_total = parseFloat(precioCant.precio_total);
              let precio = parseFloat(precioCant.precio);
              let cantidad = parseInt(precioCant.cantidad);

              /*Agrego los items con los valores para calcular el total.*/
              montoTotalOC.set(id_item, precio_total);

              itemsOrden.set(id_item, {cantidad, precio});

              /*Seteo los datos del maps items por proveedor*/
              reloadMapItemsXProveedor(respuestaJson.orden[0].id_proveedor);

            });

            window.accion = "UpdateOrden";
            traerItemsOrden(id_orden, respuestaJson.orden[0].id_proveedor);

        }
      }) 
    }else{
      swal({
          icon: 'error',
          title: 'Orden debe estar en estado "Borrador" para poder editar'
                      });
    }
});

/*LLENO DATATABLE CON LOS ITEMS DE LA ORDEN*/
function traerItemsOrden(id_orden, id_proveedor){
  $('#tablaItems').DataTable().clear().draw();
  $("#tablaItems").dataTable().fnDestroy();
    tablaItems= $('#tablaItems').DataTable({
            "ajax": {
                "url" : "./models/administrar_ordenes.php?accion=traerItemsUpdateOrden&id_orden="+id_orden+"&id_proveedor="+id_proveedor,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_item"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          let $img = "";
                          if (full.imagen !=""){
                             
                             return `<img src="./views/img_items/${full.imagen}" class="img-thumbnail">`;
                          }else{
                            return ""
                          }                               
                          
                        };
                    }
                },
              {"data": "item"},
              {
                    render: function(data, type, full, meta) {
                        return '<input class="cantidadPedir form-control quitFlechas" type="number" value="'+full.cantidad+'" min="0" step="0" >';
                    }
                },
              {
                    render: function(data, type, full, meta) {
                        return '<span class="d-none">'+full.precio+'</span><span class="">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.precio)+'</span>';
                    }
                },

            ],
            "language":  idiomaEsp,
        });
}

/*FUNCION PARA IR AGREGANDO, RESTANDO LOS ITEMS A SOLICITAR*/

$(document).on("change", '.cantidadPedir', function(){
  let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());  
  let cantidad = parseInt($(this).closest('tr').find('input').val()); 
  let precio = parseFloat($(this).closest('tr').find('td:eq(4)').find('span').text());

  /*FUNCTION PARA ADHERIR ITEMS AL MAPA*/
  let addItem= (id_item, cantidad, precio)=>{
    itemsOrden.set(id_item, {cantidad, precio});
    console.log("itemsOrden");
    console.log(itemsOrden);
  }

  /*LLAMO Y PASO PARAMETROS A LA FUNCION PARA ADHERIR ITEMS AL MAPA*/
  addItem(id_item, cantidad, precio);

})

/*FUNCION PARA IR CALCULANDO EL IMPORTE TOTAL*/
$(document).on("keyup", ".cantidadPedir", function(){
  let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());  
  let cantidad = parseInt($(this).closest('tr').find('input').val());
  let precio = parseFloat($(this).closest('tr').find('td:eq(4)').find('span').text());


  if (!isNaN(cantidad)) {
     let precioTotal = cantidad * parseFloat($(this).closest('tr').find('td:eq(4)').text());

    /*FUNCION PARA IR ACUMULANDO LOS TOTALES POR CADA ITEMS*/
    let addMonto = (id_item, precioTotal)=>{
    
    montoTotalOC.set(id_item, precioTotal);
    let monto = 0;
    console.log("montoTotalOC");
    console.log(montoTotalOC)
    for(let [key, value] of montoTotalOC){
        monto +=value;
    }
    
    let montoFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(monto);
    $('.totalFormateado').text(montoFormateado);
    $('.total').text(monto)
  }

  /*LLAMO FUNCION Y PASO PARAMETROS PARA ACUMULAR TOTALES*/

  addMonto(id_item, precioTotal);
  }

})

/*FUNCION PARA IR CALCULANDO EL IMPORTE TOTAL Y VALIDAR QUE NO SE DEJEN CAMPOS VACIOS*/
$(document).on("change", ".cantidadPedir", function(){
  let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());  
  let cantidad = parseInt($(this).closest('tr').find('input').val());

  if (isNaN(cantidad)) {
      cantidad = 0;
      $(this).closest('tr').find('input').val(0);
     let precioTotal = cantidad * parseFloat($(this).closest('tr').find('td:eq(4)').text());

    /*FUNCION PARA IR ACUMULANDO LOS TOTALES POR CADA ITEMS*/
    let addMonto = (id_item, precioTotal)=>{
    
    montoTotalOC.set(id_item, precioTotal);
    let monto = 0;
    
    for(let [key, value] of montoTotalOC){
        monto +=value;
    }
    
    let montoFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(monto);
    $('.totalFormateado').text(montoFormateado);
    $('.total').text(monto)
  }

  /*LLAMO FUNCION Y PASO PARAMETROS PARA ACUMULAR TOTALES*/

  addMonto(id_item, precioTotal);
  }
  
})

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    id_orden = parseInt($(this).closest('tr').find('td:eq(0)').text());

    let estado = fila.closest('tr').find('select option:selected').val();

    if(estado == 1){     

        swal({
                        title: "Estas seguro?",
                        text: "Una vez eliminado esta orden de compra, no volveras a verlo para este proveedor",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            accion = "eliminarOrdenCompra";
                            $.ajax({
                                    url: "models/administrar_ordenes.php",
                                    type: "POST",
                                    datatype:"json",    
                                    data:  {accion:accion, id_orden:id_orden},    
                                    success: function() {
                                        tablaOrdenes.row(fila.parents('tr')).remove().draw();                  
                                    }
                                }); 
                        } else {
                            swal("El registro no se eliminó!");
                        }
                    })
    }else{
      swal({
          icon: 'error',
          title: 'Orden debe estar en estado "Borrador" para poder eliminar'
                      });
  }
 });


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

  /*BUSCAR ITEMS POR PROVEEDOR*/
$(document).on("change", ".estado", function(){
    fila = $(this);           
    nuevoEstado = $(this).val();
    id_orden = parseInt($(this).closest('tr').find('td:eq(0)').text());
    totalFormateado = $(this).closest('tr').find('td:eq(3)').text();
    
    total = totalFormateado.replace("$", "");

    arrayValor = total.split(",");
    total = parseFloat(arrayValor[0].replace(".","")+"."+arrayValor[1])
    
    accion = "cambiarEstado";

    let minimoAprobacion = parseFloat(document.getElementById("minimoAprobacion").innerText);
    let maximoAprobacion = parseFloat(document.getElementById("maximoAprobacion").innerText);

    if(maximoAprobacion < total){
      swal({
            icon: 'error',
            title: 'Orden excede el límite máximo de aprobación'
           });
      tablaOrdenes.ajax.reload(null, false);
    }else if(minimoAprobacion > total){
      swal({
            icon: 'error',
            title: 'No alcanza el monto mínimo de aprobación'
           });
      tablaOrdenes.ajax.reload(null, false);
    }else{
      $.ajax({
            url: "models/administrar_ordenes.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion, id_orden: id_orden, estado: nuevoEstado},    
            success: function(data) {
              $('#modalCRUD').modal('hide');
              tablaOrdenes.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Estado cambiado exitosamente'
                });

              cargarGrafico();

             }
          })
    }
})

/*VER DETALLES DE LAS ORDENES DE COMPRA*/
$(document).on("click", ".btnVer", function(){
  fila = $(this).closest("tr");
  let id_orden = fila.find('td:eq(0)').text();
  let proveedor = fila.find('td:eq(1)').text();
  let almacen = fila.find('td:eq(2)').text();
  let total = fila.find('td:eq(3)').text();
  let fecha = fila.find('td:eq(4)').text();
  //parseFloat($(this).closest('tr').find('input:eq(0)').val())
  let estado = fila.closest('tr').find('select option:selected').text();

  
  $(".modal-header").css( "background-color", "#ffc107");
  $(".modal-header").css( "color", "white" );
  $(".modal-title").html(`Ver detalles orden numero: #${id_orden}`);

  $("#id_orden_ver").html(id_orden);
  $("#proveedor_ver").html(proveedor);
  $("#almacen_ver").html(almacen);
  $("#total_ver").html(total);
  $("#fecha_ver").html(fecha);
  $("#estado_ver").html(estado);


  $('#modalVer').modal('show');

});

$(document).on("click", ".btnEstados", function(){

  let id_orden =  $("#id_orden_ver").html();
  let accion="traerEstados";

  $.ajax({
    url: "models/administrar_ordenes.php",
    type: "POST",
    datatype: "json",
    data: {accion: accion, id_orden: id_orden},
    success: function(response){

      respuestaJson = JSON.parse(response);
      $historialEstados = document.getElementById("historialEstados");
      $historialEstados.innerHTML = "";

      respuestaJson.forEach((estados)=>{
        $estadoDetalle = `
                      <div class="media">
                        <div class="timeline-round m-r-30 small-line bg-secondary"><i class=""></i></div>
                          <div class="media-body">
                            <h6>${estados.estado}<span class="pull-right f-14">${estados.fecha}</span></h6>
                            <p><b>Usuario:</b>${estados.usuario}</p>
                          </div>
                      </div>
                      `;
        $historialEstados.innerHTML+=$estadoDetalle;
      });

      $("#loaderEstados").css("display", "none");
    }
  })
});

/*TRAER ADJUNTOS*/
function traerAdjuntos(){

  let id_orden =  $("#id_orden_ver").html();
  let accion="traerAdjuntos";

  $tabla = document.getElementById("tablaAdjuntos");
  $bodyTabla = $tabla.querySelector("tbody");

  $.ajax({
                url: "models/administrar_ordenes.php",
                type: "POST",
                datatype: "json",
                 data: {accion: accion, id_orden: id_orden},
                success: function(response){
                    
                        $bodyTabla.innerHTML="";

                        respuestaJson = JSON.parse(response);

                        if(respuestaJson.length > 0){

                          respuestaJson.forEach((adjuntos)=>{
                              $tr = `<tr>
                                      <td>${adjuntos.id_adjunto}</td>
                                      <td><a href="./views/adjuntosOC/${adjuntos.archivo}" target="_blank" download>${adjuntos.archivo}</a></td>
                                      <td>${adjuntos.email}</td>
                                      <td>${adjuntos.comentarios}</td>
                                      <td>${adjuntos.fecha}</td>
                                      <td><button class='btn btn-danger btnBorrarAdjunto'><i class='fa fa-trash-o'></i></button></td>
                                  </tr>`;
                              $bodyTabla.innerHTML +=$tr;
                          })
                        }else{
                          $bodyTabla.innerHTML=`<tr><td colspan="6" class="text-center">No se encontraron adjuntos</td></tr>`;
                        }

                }

            })
  
};

$(document).on("click", "#btnNuevoAdjunto", function(){
  $contenedorInputFile = document.getElementById("inputFile");
  $contenedorInputFile.style.display = "block";
});

$(document).on("click", "#btnEnviarAdj", function(){
  let id_orden =  $("#id_orden_ver").html();
  let file = document.getElementById("adjunto");
  let comentarios = document.getElementById("comentarios").value;


  let datosEnviar = new FormData();

  if (file.files.length > 0) {
    datosEnviar.append("file", file.files[0]);
    datosEnviar.append("accion", "adjuntarArchivo");
    datosEnviar.append("id_orden", id_orden);
    datosEnviar.append("comentarios", comentarios);          

    $.ajax({
      data: datosEnviar,
      url: "models/administrar_ordenes.php",
      method: "post",
      cache: false,
      contentType: false,
      processData: false, 
      success: function(){
        $contenedorInputFile = document.getElementById("inputFile");
        $contenedorInputFile.style.display="none";
        
        //subirArchivosOrdenes(id_orden, id_proveedor);

                    traerAdjuntos();

                    file.value="";
                }

            })


            }else{
              document.getElementById("msgErrAdjunto").style.display="block";
              document.getElementById("textoErrorAdjunto").innerText = "No se adjuntó ningún archivo";
            }

});

$(document).on("click", ".btnBorrarAdjunto", function(){
  fila = $(this).closest("tr");
  let id_adjunto = fila.find('td:eq(0)').text();
  let nombre_adjunto = fila.find('td:eq(1)').text()
  let accion = "borrarAdjunto";


            swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este archivo, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "borrarAdjunto";
                        $.ajax({
                          url: "models/administrar_ordenes.php",
                          type: "POST",
                          datatype:"json",    
                          data:  {accion: accion, id_adjunto: id_adjunto, nombre_adjunto: nombre_adjunto},    
                          
                          success: function(data) {
                            traerAdjuntos();
                            swal({
                                icon: 'success',
                                title: 'Archivo eliminado exitosamente'
                              });

                           }
                        }) 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
  
});

$(document).on("click", ".btnDetalleOrden", function(){
    
    let id_orden =  $("#id_orden_ver").html();
    let accion="traerDetalle";

    $.ajax({
      url: "models/administrar_ordenes.php",
      type: "POST",
      datatype: "json",
      data: {accion: accion, id_orden: id_orden},
      success: function(response){

        respuestaJson = JSON.parse(response);
        /*$historialEstados = document.getElementById("historialEstados");
        $historialEstados.innerHTML = "";*/

        $("#titleDetalles").removeClass("d-none");
        $("#contTablaDO").removeClass("d-none");

        $("#dnum").html($("#id_orden_ver").html());
        $("#dprov").html($("#proveedor_ver").html());
        $("#dalm").html($("#almacen_ver").html());
        $("#dtot").html($("#total_ver").html());
        $("#dfech").html($("#fecha_ver").html());
        $("#dest").html($("#estado_ver").html());

        $tbodyDetalleOrden = document.getElementById("tbodyDetalleOrden");
        $tbodyDetalleOrden.innerHTML="";

        respuestaJson.forEach((detalle)=>{
          $filaDetalleOren=`
                          <tr>
                            <td>${detalle.id_item}</td>
                            <td>${detalle.item}</td>
                            <td>${detalle.cantidad}</td>
                            <td>${new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(detalle.precioUnitario)}</td>
                            <td>${new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(detalle.precio_total)}</td>
                          </tr>
          `;
          $tbodyDetalleOrden.innerHTML+=$filaDetalleOren;
          itemsImprimir.push({
            id_item: detalle.id_item,
            item : detalle.item,
            cantidad: detalle.cantidad,
            precioUnit: new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(detalle.precioUnitario),
            precio_total: new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(detalle.precio_total)
          })
        });

        $("#loaderDetalle").css("display", "none");
      }
    })
});

$(document).on("click", ".btnImprimir", function(){


  let id_orden =  $("#dnum").html();
  let proveedor = $("#dprov").html();
  let almacen = $("#dalm").html();
  let total = $("#dtot").html();
  let fecha = $("#dfech").html();
  let estado = $("#dest").html();
  const datos = [];
  //let detalle = JSON.stringify(itemsImprimir);

  datos.push({id_orden: id_orden, proveedor: proveedor, almacen: almacen, total: total, fecha: fecha, estado:estado, detalle: itemsImprimir})

  datosEnviar = JSON.stringify(datos);

  url = "./imprimirDetalleOrden.php?datos="+datosEnviar;
  win = window.open(url, '_blank', 'toolbar=no,status=no, menubar=no');
  // Cambiar el foco al nuevo tab (punto opcional)
  win.focus();
})

function cargarGrafico(fdesde="", fhasta=""){

  let accion = "traerEstadosGrafico";
  let id_empresa = parseInt(document.getElementById("id_empresa").innerText)

  const doughnutData=[];
  $.ajax({
    url:"models/administrar_ordenes.php",
    type: "POST",
    datatype: "json",
    data: {accion: accion, fdesde: fdesde, fhasta: fhasta, id_empresa: id_empresa},
    success: function(response){
        respuestaJson = JSON.parse(response);

        if (respuestaJson.length===0) {
          const doughnutData=[];
          var doughnutOptions = {
            segmentShowStroke: true,
            segmentStrokeColor: "#fff",
            segmentStrokeWidth: 2,
            percentageInnerCutout: 50,
            animationSteps: 100,
            animationEasing: "easeOutBounce",
            animateRotate: true,
            animateScale: false,
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
          };
          var doughnutCtx = document.getElementById("myDoughnutGraph").getContext("2d");
          var myDoughnutChart = new Chart(doughnutCtx).Doughnut(doughnutData, doughnutOptions);
          $("#contGraf").addClass("d-none");
          $("#errorGraf").removeClass("d-none");

        }else{
          $("#errorGraf").addClass("d-none");
          $("#contGraf").removeClass("d-none");

          respuestaJson.forEach((estados)=>{
              switch(estados.estado){
                case "Borrador":
                  doughnutData.push({
                    value: estados.cantidad,
                    color: "#f6f7fb",
                    highlight: "#f6f7fb",
                    label: "borrador"
                  });
                break;
                case "Verificada":
                  doughnutData.push({
                    value: estados.cantidad,
                    color: "#ff9f40",
                    highlight: "#ff9f40",
                    label: "Verificada"
                  });
                break;
                case "Aceptada":
                  doughnutData.push({
                    value: estados.cantidad,
                    color: "#4466f2",
                    highlight: "#4466f2",
                    label: "Aceptada"
                  });
                break;
                case "En distribucion":
                  doughnutData.push({
                    value: estados.cantidad,
                    color: "#1ea6ec",
                    highlight: "#1ea6ec",
                    label: "En distribución"
                  })
                break;
                case "Cancelada":
                  doughnutData.push({
                    value: estados.cantidad,
                    color: "#FF5370",
                    highlight: "#FF5370",
                    label: "Cancelada"
                  });
                break;
                case "Finalizada":
                  doughnutData.push({
                    value: estados.cantidad,
                    color: "#28a745",
                    highlight: "#28a745",
                    label: "Finalizada"
                  })
                break;
              }

              var doughnutOptions = {
              segmentShowStroke: true,
              segmentStrokeColor: "#fff",
              segmentStrokeWidth: 2,
              percentageInnerCutout: 50,
              animationSteps: 100,
              animationEasing: "easeOutBounce",
              animateRotate: true,
              animateScale: false,
              legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
            };
            var doughnutCtx = document.getElementById("myDoughnutGraph").getContext("2d");
            var myDoughnutChart = new Chart(doughnutCtx).Doughnut(doughnutData, doughnutOptions);
          });
        }
    }
  });

  
  }

  $(document).on("click", ".btnBuscar", function(){
      let fdesde = $("#fdesde").val();
      let fhasta =$("#fhasta").val();

      if(fdesde ==="" || fhasta ===""){
        swal({
          icon: 'error',
          title: 'Debe seleccionar fecha desde y fecha hasta'
                      });
      }else{
        cargarGrafico(fdesde, fhasta);
        cargarDataTable(fdesde, fhasta);
      }
  })

  function cargarDataTable(fdesde, fhasta){
    
    $("#tablaOrdenes").dataTable().fnDestroy();

    tablaOrdenes= $('#tablaOrdenes').DataTable({
            "ajax": {
                "url" : "./models/administrar_ordenes.php?accion=traerOrdenesFiltro&fdesde="+fdesde+"&fhasta="+fhasta,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_oc"},
              {"data": "proveedor"},
              {"data":"almacen"},
              {"data": "total"},
              {"data": "fecha"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{

                          const estados = {
                            1: "Borrador",
                            2: "Verificada",
                            3: "Aceptada",
                            4: "En distribución",
                            5: "Cerrada",
                            6: "Finalizada"
                          }

                          $options="";

                          for(key in estados){
                                  if(full.id_estado == key){
                                    $options+=`<option selected value="${full.id_estado}">${estados[key]}</option>`
                                  }else{

                                    switch(full.id_estado){
                                      case "1":
                                        if(key == 5){
                                          $options+=`<option value="${key}">${estados[key]}</option>`
                                        }
                                      break
                                      case "2":
                                        if(key == 3 || key == 5){
                                        $options+=`<option value="${key}">${estados[key]}</option>`
                                      }
                                      break;

                                      case "4":
                                        if(key == 5){
                                        $options+=`<option value="${key}">${estados[key]}</option>`
                                      }                                    }

                                    
                                    ;
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
  }

  $(document).on("click",".btnContactos", function(){
    let fila = $(this).closest("tr");
    let id_orden = fila.find('td:eq(0)').text();
    $(".modal-header").css( "background-color", "#4466f2");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").html(`Contactos`);

    $("#contactos").modal("show");
    let accion = "traerContactosOrden";
    $.ajax({
            url: "models/administrar_ordenes.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion, id_orden: id_orden},    
            success: function(response) {
              
                let $bodyTablaContactos = document.querySelector("#tablaContactos").querySelector("tbody");

                let respuestaJson = JSON.parse(response);
                if(respuestaJson.length > 0){
                    $bodyTablaContactos.innerHTML="";
                    respuestaJson.forEach((contactos)=>{
                      $filaContactos = `
                                        <tr>
                                          <td>${contactos.id_contacto}</td>
                                          <td>${contactos.nombre_completo}</td>
                                          <td><a class="btn btn-pill btn-secondary envMail text-white"><i class="icofont icofont-email"></i> ${contactos.email}</button></td>
                                          <td><a href="https://wa.me/549${contactos.telefono}?text=" target="_blank" class="btn btn-pill btn-secondary text-white"><i class="icofont icofont-brand-whatsapp"></i> ${contactos.telefono}</a></td>
                                          <td>${contactos.id_cargo}</td>
                                          <td>${contactos.activo}</td>
                                        </tr>
                                      `
                      $bodyTablaContactos.innerHTML+= $filaContactos;
                    });
                }else{
                  $bodyTablaContactos.innerHTML=`<tr><td colspan="6">No se encontraron datos.</td></tr>`
                }

             }
          })

  });

  $(document).on("click", ".envMail", function(){
    let mailEnviar= $(this).text();
    swal({
                  icon: 'success',
                  title: `Se envío mail con detalle a: ${mailEnviar}`
                }); 
  });


  $(document).on("click", ".btnRecibirOrden", function(){

    let fila = $(this).closest("tr");
    let id_orden = fila.find('td:eq(0)').text();
    let estado = fila.closest('tr').find('select option:selected').val();

    $(".id_ordenEditarRecibido").html(id_orden);

    accion = "recibirPedido";
    
    if(estado == 4){
      $(".modal-header").css( "background-color", "#f6f7fb");
      $(".modal-header").css( "color", "black" );
      $(".modal-title").html(`Recibir orden de compra`);
      $("#recibirPedidos").modal("show");

      $("#tablaRecibirOrden").dataTable().fnDestroy();
      tablaRecibirOrden= $('#tablaRecibirOrden').DataTable({
              "ajax": {
                  "url" : "./models/administrar_ordenes.php?accion=traerItemsRecibir&id_orden="+id_orden,
                  "dataSrc": "",
                },
              "columns":[
                {"data": "id_item"},
                {"data": "item"},
                {"data": "cantPedida"},
                {"data": "cantRecibida"},
                {
                      render: function(data, type, full, meta) {
                          return ()=>{ 
                          if(parseInt(full.cantRecibida) < parseInt(full.cantPedida)){
                          return '<input class="cantidadRecibir form-control quitFlechas" type="number" value="0" min="0" step="0" >';
                        }else{
                          return'<input class="cantidadRecibir form-control quitFlechas" type="number" value="0" min="0" step="0" readonly>';
                        }
                      }
                      }
                  },
              ],
              "language":  idiomaEsp
          });

    }else{
      swal({
          icon: 'error',
          title: 'Orden debe estar en estado "En distribución"'
                      });
    }

  })

  /*FUNCION PARA IR AGREGANDO LOS ITEMS RECIDOS*/

$(document).on("change", '.cantidadRecibir', function(){
  let id_item = parseInt($(this).closest('tr').find('td:eq(0)').text());
  let cantidad_pedida =   parseInt($(this).closest('tr').find('td:eq(2)').text());
  let cantidad_recibida =   parseInt($(this).closest('tr').find('td:eq(3)').text());
  let cant_recibe = parseInt($(this).closest('tr').find('input').val()); 

  /*FUNCTION PARA ADHERIR ITEMS AL MAPA*/
  let addItemPedir= (id_item, cantidad)=>{
    
    itemsPedir.set(id_item, cantidad);

  }

  /*LLAMO Y PASO PARAMETROS A LA FUNCION PARA ADHERIR ITEMS AL MAPA*/
  if (cantidad_pedida < (cantidad_recibida+cant_recibe)) {
    swal({
      icon: 'error',
      title: 'Cantidad recibida es mayor a la cantidad pedida'
    });
  }else{
    addItemPedir(id_item, cant_recibe);
  }
  

});

$(document).on("click", "#btnGuardarRecibido", function(){

  let id_orden= parseInt($(".id_ordenEditarRecibido").text());
  const arrayOrdenRecibir = [];
    for(let[key, value] of itemsPedir){
      if (value > 0) {
        arrayOrdenRecibir.push({
          id: key,
          cantidad: value
        });
      }  
    }

    if (arrayOrdenRecibir.length > 0) {
      itemsRecibir = JSON.stringify(arrayOrdenRecibir);
      $.ajax({
              url: "models/administrar_ordenes.php",
              type: "POST",
              datatype:"json",    
              data:  {accion: accion, itemsRecibir:itemsRecibir, id_orden: id_orden},    
              success: function(data) {
                if(data==""){
                  $('#recibirPedidos').modal('hide');
                  tablaOrdenes.ajax.reload(null, false);
                  tablaRecibirOrden.ajax.reload(null, false);
                  swal({
                    icon: 'success',
                    title: 'Accion realizada correctamente'
                  });
                }

               }
            })
    }else{
      swal({
                        icon: 'error',
                        title: 'No se registraron cambios'
                      });
    }
  
})

$("#formAddItem").submit(function(e){
  e.preventDefault();

  let id_empresa = parseInt(document.getElementById("id_empresa").innerText); 
  let id_proveedor = parseInt(document.getElementById("proveedor").value)
  let datosEnviar = new FormData();
  datosEnviar.append("descripcion", $.trim($('#descripcion_producto').val()))
  datosEnviar.append("categoria", $.trim($('#categoria').val()));
  datosEnviar.append("Umedida", $.trim($('#Umedida').val()));
  datosEnviar.append("tipo", $.trim($('#tipo').val()));
  datosEnviar.append("preposicion", $.trim($('#preposicion').val()));
  datosEnviar.append("estado", $.trim($('#estado').val()));
  datosEnviar.append("linkVideo", $.trim($('#linkVideo').val()));
  datosEnviar.append("id_item", $.trim($('#id_item').html()));
  datosEnviar.append("accion", "addArticulo");
  datosEnviar.append("id_cc", $.trim($('#cCostos').val()));
  datosEnviar.append("precio", $.trim($('#precioNewItem').val()));
  datosEnviar.append("id_empresa", id_empresa);
  datosEnviar.append("id_proveedor", id_proveedor);
  img = document.getElementById("imagen");

  if (img.files.length > 0) {
    datosEnviar.append("file", img.files[0]);
  }else{
      datosEnviar.append("file", "");
  }
  $.ajax({
          data: datosEnviar,
          url: "models/administrar_ordenes.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {

            swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });            
            document.getElementById("contAddItem").classList.add("d-none");
            document.getElementById("contTablaOrden").classList.remove("d-none");
            document.getElementById("resultadoSearch").innerHTML="";
            document.getElementById("item_buscar").focus();
            document.getElementById("footerOC").classList.remove("d-none");
            $("#formAddItem").trigger("reset");
            reloadMapItemsXProveedor(id_proveedor);
           }
        });             
    


});
function reloadMapItemsXProveedor(id_proveedor){
    itemsXProveedor.clear();
    document.getElementById("item_buscar").value = "";
    let accion = "traerItems";
    $.ajax({
          url: "./models/administrar_ordenes.php",
          type: "POST",
          datatype: "json",
          data: {accion: accion, id_proveedor: id_proveedor},
          success: function(response){
            //itemsOrden.set(id_item, {cantidad, precio});
              let respuestaJson = JSON.parse(response);

              respuestaJson.forEach(item=>{
                itemsXProveedor.set(item.id_item, {imagen: item.imagen, item:item.item, precio:item.precio})
              });
          }
    });
}

    </script>
  </body>
</html>