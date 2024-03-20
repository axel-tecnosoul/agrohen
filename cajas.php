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
    <title>MYLA - Cajas</title>
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
                    <h3>Cajas</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Cajas</li>
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
                    <h5>Administrar Cajas</h5>
                     <!--<button id="btnNuevo" type="button" class="btn btn-warning mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button>-->

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaCajas">
                        <thead class="text-center">
                          <tr>
                            <th>Tipo</th>
                            <th>Estado</th>
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
     <!--Modal para saldo de apertura-->
      <div class="modal fade" id="saldoApertura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_tipo_caja" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formAperturaCaja">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Saldo apertura:</label>
                              <input type="number" class="form-control" id="importeApertura" required>
                            </div>
                          </div>   
                      </div>              
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                      <button type="submit" id="" class="btn btn-dark">Guardar</button>
                  </div>
              </form>    
              </div>
          </div>
      </div>
  <!-- fin modal para agregar saldo inicial -->

  <!-- Modal agregar movimiento -->
      <div class="modal fade" id="agregarMovimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"></h5>
                      <span id="id_caja_diaria" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>   
                  <div class="modal-body">
                    <form id="formAddNuevoMovimieto">
                      <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Tipo de moviemiento:</label>
                              <select class="form-control" id="tipoMovimiento" required>
                                <option value="">Seleccione tipo de mov...</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Importe neto:</label>
                              <input type="number" class="form-control" id="importeNetoMovmiento" required>
                            </div>
                          </div> 
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Importe impuestos:</label>
                              <input type="number" class="form-control" id="importeImpuestosMovmiento" required>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Nro comprobante:</label>
                              <input type="number" class="form-control" id="nroComprobante">
                            </div>
                          </div>
                          <div class="col-lg-8">
                            <div class="form-group">
                              <label for="" class="col-form-label">Adjuntar comprobante:</label>
                              <input class="form-control" type="file" id="adjuntos" accept="*" data-original-title="" title="">
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
                      <div class="row mb-3">
                        <div class="col-lg-12 text-right">
                          <div class="form-groupt">
                            <button type="submit" class="btn btn-dark">Agregar</button>
                          </div>
                        </div>
                      </div> 
                    </form>              
                      <div class="table-responsive" id="contTablaOrden" style="">
                      <table class="table table-hover" id="tablaMovimientos">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Tipo Movimiento</th>
                            <th>Monto</th>
                            <th>Comprobante</th>
                            <th>Adjuntos</th>
                            <th>Fecha</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                       <span class="mt5">
                          <label class="font-weight-bold h4">Total:</label>
                          <span class="totalFormateado h4">$ 0,00</span>
                          <span class="total d-none"></span>
                      </span>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
          </div>
      </div>
    <!-- Fin Modal agregar movimiento -->
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
        let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
        console.log(id_empresa);
       tablaCajas= $('#tablaCajas').DataTable({
            "ajax": {
                "url" : "./models/administrar_cajas.php?accion=traerTiposCaja&id_empresa="+id_empresa,
                "dataSrc": "",
              },
            "columns":[
              {
                    render: function(data, type, full, meta) {
                        return '<span class="d-none">'+full.id_caja_diaria+'</span><span class="">'+full.tipo+'</span>';
                    }
                },
              {
                    render: function(data, type, full, meta) {
                        return '<span class="d-none">'+full.estado+'</span><span class="">'+full.estado_mostar+'</span>';
                    }
                },
                {
                    render: function(data, type, full, meta) {
                        return '<span class="d-none">'+full.saldo+'</span><span class="">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.saldo)+'</span>';
                    }
                },

              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          if(full.estado != 0){
                            if(full.saldo > 0 ){
                              return `<div class='text-center'><div class='btn-group'><button class="btn btn-danger btnCerrarCaja" data-id_caja_diaria="${full.id_caja_diaria}" title="Cerrar caja"><i class='fa fa-key'"></i></button><button class="btn btn-primary btnAgregarMovimiento" data-id_caja_diaria="${full.id_caja_diaria}" title="Agregar moviemiento de caja"><i class='fa fa-exchange'"></i></button></div></div>`
                            }else{
                              return `<div class='text-center'><div class='btn-group'><button class="btn btn-danger btnCerrarCaja" data-id_caja_diaria="${full.id_caja_diaria}" title="Cerrar caja"><i class='fa fa-key'"></i></button></div></div>`
                            }
                          }else{
                            return `<div class='text-center'><div class='btn-group'><button class="btn btn-success btnAbrirCaja" data-id-tipo="${full.id_tipo_caja}" title="Abrir caja"><i class='fa fa-check-square-o'"></i></button></div></div>`
                          }
                        };
                    }
                },
              
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
                    url: "./models/administrar_cajas.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de tipo de movimientos*/
                        $selecTmov = document.getElementById("tipoMovimiento");

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select tipo de movimiento*/
                        respuestaJson.tipos_mov.forEach((tMovimientos)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(tMovimientos.tipo_mov);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", tMovimientos.id_tipo_mov);
                            $selecTmov.appendChild($option);
                        })

                    }
                });
            }
    $(document).on("click", ".btnAbrirCaja", function(){
        
      let id_tipo_caja = $(this).attr("data-id-tipo");
      $("#id_tipo_caja").html(id_tipo_caja);

      accion="abrirCaja";

      $("#formAperturaCaja").trigger("reset");
      $(".modal-header").css( "background-color", "#17a2b8");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Abrir caja");
      $('#saldoApertura').modal('show');
      
    });

    $(document).on("click", ".btnCerrarCaja", function(){
      let id_caja_diaria = $(this).attr("data-id_caja_diaria");
      let fila = $(this); 
      swal({
                    title: "¿Desea cerrar caja?",
                    text: "Una vez cerrada la caja, deberá realizar la apertura nuevamente",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "cerrarCaja";
                        $.ajax({
                                url: "models/administrar_cajas.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_caja_diaria: id_caja_diaria},    
                                success: function() {
                                    tablaCajas.ajax.reload(null, false);                 
                                }
                            });
                    } else {
                        swal("No se cerró la caja!");
                    }
                })
    });

    $(document).on("click", ".btnAgregarMovimiento", function(){

      $("#formAddNuevoMovimieto").trigger("reset");
      $(".modal-header").css( "background-color", "#17a2b8");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Agregar movimientos");
      $("#agregarMovimiento").modal("show");

      accion = "agregarMovimiento";
      let id_caja_diaria = $(this).attr("data-id_caja_diaria");
      document.getElementById("id_caja_diaria").textContent=id_caja_diaria;

      $("#tablaMovimientos").dataTable().fnDestroy();
      tablaMovimientos= $('#tablaMovimientos').DataTable({
              "ajax": {
                  "url" : "./models/administrar_cajas.php?accion=traerMovimientos&id_caja_diaria="+id_caja_diaria,
                  "dataSrc": "",
                },
              "columns":[
                {"data": "id_cdd"},
                {"data": "tipo"},
                {
                    render: function(data, type, full, meta) {
                        return '<span class="">'+new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(full.monto)+'</span>';
                    }
                },
                {"data": "comprobante"},
                {
                    render: function(data, type, full, meta) {
                        return ()=>{
                            if(full.adjunto !==""){
                              return `<a href="./views/adjuntosCajaDiaria/${full.adjunto}" target="_blank">Descargar</a>`
                            }else{
                              return ""
                            }
                        };
                    }
                },
                {"data": "feha_hora"}
              ],
              "language":  idiomaEsp
          });


      let saldo = parseFloat($(this).closest('tr').find('td:eq(2)').find('span').text());
      let saldo_formateado = new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(saldo);
      $(".totalFormateado").text(saldo_formateado);
      $(".total").text(saldo);
    });

    $("#formAddNuevoMovimieto").submit(function(e){
      e.preventDefault();

      let datosEnviar = new FormData();
      /*let detalle_movimiento = document.getElementById("detalle").value;*/
      let adjunto = document.getElementById("adjuntos");
      let saldo = parseFloat($(".total").html());
      let importeMovimiento = parseFloat(document.getElementById("importeNetoMovmiento").value) + parseFloat(document.getElementById("importeImpuestosMovmiento").value);

      if (adjunto.files.length > 0) {
        datosEnviar.append("file", adjunto.files[0]);
      }else{
        datosEnviar.append("file", "");
      }


      datosEnviar.append("accion", accion);
      datosEnviar.append("idCajaDiaria", parseInt(document.getElementById("id_caja_diaria").textContent));
      datosEnviar.append("idTipoMovimiento", document.getElementById("tipoMovimiento").value);
      datosEnviar.append("importeNetoMovimiento", document.getElementById("importeNetoMovmiento").value);
      datosEnviar.append("importeImpuestosMovmiento", document.getElementById("importeImpuestosMovmiento").value);
      datosEnviar.append("nroComprobante", document.getElementById("nroComprobante").value);
      datosEnviar.append("importeMovimiento", importeMovimiento)


      if(importeMovimiento <= saldo){


              let nuevoSaldo = saldo - importeMovimiento;
              let saldoFormateado = new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(nuevoSaldo);
              $(".totalFormateado").text(saldoFormateado);
              $(".total").text(nuevoSaldo);

              $.ajax({
                  data: datosEnviar,
                  url:"./models/administrar_cajas.php",
                  method: "post",
                  cache: false,
                  contentType: false,
                  processData: false,  
                  success: function(){
                      $("#formAddNuevoMovimieto").trigger("reset");
                      tablaCajas.ajax.reload(null, false);
                      tablaMovimientos.ajax.reload(null, false);
                      $('#saldoApertura').modal('hide');
                      swal({
                            icon: 'success',
                            title: 'Accion realizada correctamente'
                          });
                  }
                });
      }else{
        swal({
            icon: 'error',
            title: 'Importe mayor al saldo disponible'
            });
      }


    });

    $('#formAperturaCaja').submit(function(e){                         
      e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
        
      let id_tipo_caja = $.trim($('#id_tipo_caja').html());
      let id_empresa = parseInt(document.getElementById("id_empresa").textContent);
      let saldo_apertura = $.trim($("#importeApertura").val());

      $.ajax({
        "url":"./models/administrar_cajas.php",
        "type": "POST",
        "datatype":"json",
        "data":{accion: accion, id_empresa: id_empresa, id_tipo_caja: id_tipo_caja, saldo_apertura: saldo_apertura},
        success: function(){
            tablaCajas.ajax.reload(null, false);
            $('#saldoApertura').modal('hide');
            swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });
        }
      });
});


    $("#btnNuevo").click(function(){
      accion = "addTipoCaja";
    $("#formAperturaCaja").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Alta tipo de caja");
    $('#modalCRUD').modal('show');      
});

//Borrar
$(document).on("click", ".btnBorrar", function(){
    let fila = $(this);           
    let id_tipo_caja = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este registro, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarTipoCaja";
                        $.ajax({
                                url: "models/administrar_tipos_caja.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_tipo_caja:id_tipo_caja},    
                                success: function() {
                                    tablaCajas.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 }); 
    </script>
  </body>
</html>