<?php 
  session_start();
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
    <title>MYLA - Legajos Técnicos</title>
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
                    <h3>Legajos Técnicos</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Legajos Técnicos</li>
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
                    <h5>Administrar Técnicos</h5>
                     <button id="btnNuevo" type="button" class="btn btn-primary mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 
                      <!--<button id="btnImportar" type="button" class="btn btn-outline-success mt-2" data-toggle="modal"><i class="fa fa-cloud-upload"></i> importar</button>-->

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="tablaTecnicos" class="display responsive nowrap" style="width:100%">
                        <thead class="text-center">
                          <tr>
                            <th>Legajo</th>
                            <th>Imagen</th>
                            <th>Nombre completo</th>
                            <th>Cuit</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                            <th>Tipo Iva</th>
                            <th>Cargo</th>
                            <th>EMail</th>
                            <th>Valor hora</th>
                            <th>Dirección</th>
                            <th>Provincia</th>
                            <th>Saldo</th>
                            <th>Fecha Alta</th>
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
                      <span id="id_tecnico" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formTecnicos">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-5">
                            <div class="form-group">
                              <label for="" class="col-form-label">Nombre completo:</label>
                              <input type="text" class="form-control" id="nombre" required>
                            </div>
                          </div> 
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="" class="col-form-label">Cuit:</label>
                              <input type="number" class="form-control" id="cuit" required>
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
                        <div class="col-lg-4">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Cargo</label>
                              <select class="form-control" id="cargos" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-5">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Condicion Iva</label>
                              <select class="form-control" id="iva" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                        </div> 
                        <div class="col-lg-7">
                            <div class="form-group">
                              <label for="" class="col-form-label">Dirección:</label>
                              <input type="text" class="form-control" id="direccion" required>
                            </div>
                        </div>
                      </div>
                      <div class="row"> 
                          <div class="col-lg-5">    
                              <div class="form-group">
                              <label for="" class="col-form-label">Provincia</label>
                              <select class="form-control" id="provincia" required>
                                <option value="">Seleccione</option>
                              </select>
                              </div>            
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="" class="col-form-label">Valor hora:</label>
                              <input type="number" class="form-control" id="valHora" required>
                            </div>
                          </div>
                      </div>
                      <div class="row"> 
                          <div class="col-lg-8">
                            <div class="form-group">
                              <label for="" class="col-form-label">Adjuntar foto de perfil:</label>
                              <input class="form-control" type="file" id="adjuntos" accept="image/*" data-original-title="" title="">
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
                           <a class="btn btn-outline-danger btnBorrarFoto text-danger d-none"><i class='fa fa-trash-o'></i></a>
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

        let id_empresa = parseInt(document.getElementById("id_empresa").textContent)
       tablaTecnicos= $('#tablaTecnicos').DataTable({
            responsive: true,
            "ajax": {
                "url" : "./models/administrar_legajos_tecnicos.php?accion=traerTecnicos&id_empresa="+id_empresa,
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_tecnico"},
              {
                    render: function(data, type, full, meta) {
                        return ()=>{
                          let $img = "";
                          if (full.imagen !=""){
                             
                             return `<img src="./views/adjuntosTecnicos/${full.imagen}" class="img-thumbnail">`;
                          }else{
                            return `<img src="./views/adjuntosTecnicos/user.png" class="img-thumbnail">`
                          }                               
                          
                        };
                    }
                },
              {"data": "nombre"},
              {"data": "cuil"},
              {"data": "telefono"},
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
               {
                    render: function(data, type, full, meta) {
                        return `<div class="text-center"><div class="btn-group"><button class="btn btn-success btnEditar" data-id-tecnico-edit = "${full.id_tecnico}"><i class="fa fa-edit"></i></button><button class="btn btn-danger btnBorrar" data-id-tecnico-del = "${full.id_tecnico}"><i class="fa fa-trash-o"></i></button></div></div>`;
                    }
                },
              {"data": "tipo_iva"},
              {"data": "cargo"},
              {"data": "email"},
              {"data": "valor_hora"},
              {"data": "direccion"},
              {"data": "provincia"},
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
              {"data": "fecha_alta"},
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
                    url: "./models/administrar_legajos_tecnicos.php",
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

                        /*Identifico el select de cargos*/
                        $selectCargos= document.getElementById("cargos");

                        /*Identifico el select de condición iva*/
                        $selectIva= document.getElementById("iva");
                     
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);


                        /*Genero los options del select provincia*/
                        respuestaJson.provincias.forEach((provincia)=>{
                            let $option = document.createElement("option");
                            let optionText = document.createTextNode(provincia.provincia);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", provincia.id_provincia);
                            $selectProvincias.appendChild($option);
                        })

                        /*Genero los options del select cargos*/
                        respuestaJson.cargos.forEach((cargos)=>{
                          let $option = document.createElement("option");
                          let optionText = document.createTextNode(cargos.cargo);
                          $option.appendChild(optionText);
                          $option.setAttribute("value", cargos.id_cargo);
                          $selectCargos.appendChild($option)
                        });

                        /*Genero los options del select cargos*/
                        respuestaJson.condicion_iva.forEach((iva)=>{
                          let $option = document.createElement("option");
                          let optionText = document.createTextNode(iva.tipoIva);
                          $option.appendChild(optionText);
                          $option.setAttribute("value", iva.id_iva);
                          $selectIva.appendChild($option)
                        });

                    }
                });
            }
    $(document).on("click", ".btnEditar", function(){
      $(".modal-header").css( "background-color", "#22af47");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Editar técnico");
      $("#formTecnicos").trigger("reset");
      $('#modalCRUD').modal('show');      
      fila = $(this).closest("tr");
      let id_tecnico = $(this).attr("data-id-tecnico-edit");

      let datosUpdate = new FormData();
      datosUpdate.append('accion', 'traerTecnicoUpdate');
      datosUpdate.append('id_tecnico', id_tecnico);
      $.ajax({
            data: datosUpdate,
            url: './models/administrar_legajos_tecnicos.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(datosProcesados){

              let datosInput = JSON.parse(datosProcesados);
              $("#nombre").val(datosInput.datos_tecnico[0].nombre);
              //$("#legajo").val(datosInput.datos_tecnico[0].legajo);
              $("#cuit").val(datosInput.datos_tecnico[0].cuil);
              $("#telefono").val(datosInput.datos_tecnico[0].telefono);
              $("#email").val(datosInput.datos_tecnico[0].email);
              $("#cargos").val(datosInput.datos_tecnico[0].cargo);
              $("#iva").val(datosInput.datos_tecnico[0].tipo_iva);
              $("#direccion").val(datosInput.datos_tecnico[0].direccion);
              $("#provincia").val(datosInput.datos_tecnico[0].provincia);
              $("#valHora").val(datosInput.datos_tecnico[0].valor_hora);
              $('#id_tecnico').html(id_tecnico);
              
  
              if(datosInput.datos_tecnico[0].imagen !=""){
                $srcImagen = "./views/adjuntosTecnicos/"+datosInput.datos_tecnico[0].imagen;
                $('#imgUpdate').attr("src", $srcImagen);
                $('#imgUpdate').removeClass("d-none")
                $('.btnBorrarFoto').removeClass("d-none");
              }else{
                $('#imgUpdate').attr("src", "");
                $('#imgUpdate').addClass("d-none");
                $('.btnBorrarFoto').addClass("d-none");
              }

              accion = "updateTecnico";
            }
          });

      $('#modalCRUD').modal('show');
    });

    $('#formTecnicos').submit(function(e){                         
    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
     
    
    let datosEnviar = new FormData();
    let adjunto = document.getElementById("adjuntos");
    datosEnviar.append("nombre", $.trim($('#nombre').val()))
    //datosEnviar.append("legajo", $.trim($('#legajo').val()));
    datosEnviar.append("cuit", $.trim($('#cuit').val()));
    datosEnviar.append("telefono", $.trim($('#telefono').val()));
    datosEnviar.append("email", $.trim($('#email').val()));
    datosEnviar.append("cargo", $.trim($('#cargos').val()));
    datosEnviar.append("iva", $.trim($('#iva').val()));
    datosEnviar.append("direccion", $.trim($('#direccion').val()));
    datosEnviar.append("provincia", $.trim($('#provincia').val()));
    datosEnviar.append("valHora", $.trim($('#valHora').val()));
    datosEnviar.append("id_empresa", $.trim($('#id_empresa').html()));


    if(accion == 'updateTecnico'){
       datosEnviar.append("id_tecnico", $.trim($('#id_tecnico').html()));
    }else{
       datosEnviar.append("id_tecnico", "");
    }

     if (adjunto.files.length > 0) {
        datosEnviar.append("file", adjunto.files[0]);
      }else{
        datosEnviar.append("file", "");
      }

    datosEnviar.append("accion", accion);
        $.ajax({
          data: datosEnviar,
          url: "models/administrar_legajos_tecnicos.php",
          method: "post",
          cache: false,
          contentType: false,
          processData: false,     
          success: function(data) {
            tablaTecnicos.ajax.reload(null, false);
           }
        });             
    $('#modalCRUD').modal('hide'); 
    swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });                               
});


    $("#btnNuevo").click(function(){
      accion = "addTecnico"
    $("#formTecnicos").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Nuevo técnico");
    $('#modalCRUD').modal('show');    
});

//Cambiar estado
$(document).on("change", ".estado", function(){
    fila = $(this);           
    nuevoEstado = $(this).val();
    id_tecnico = parseInt($(this).closest('tr').find('td:eq(0)').text());
      console.log(`cambio usuario ${id_tecnico} a estado ${nuevoEstado}`);
    accion = "cambiarEstado";

   $.ajax({
            url: "models/administrar_legajos_tecnicos.php",
            type: "POST",
            datatype:"json",    
            data:  {accion: accion, id_tecnico: id_tecnico, estado: nuevoEstado},    
            success: function(data) {
              $('#modalCRUD').modal('hide');
              tablaTecnicos.ajax.reload(null, false);
              swal({
                  icon: 'success',
                  title: 'Estado cambiado exitosamente'
                });

             }
          })
})

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    let id_tecnico = $(this).attr("data-id-tecnico-del");       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este registro, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarTecnico";
                        $.ajax({
                                url: "models/administrar_legajos_tecnicos.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_tecnico:id_tecnico},    
                                success: function() {
                                    tablaTecnicos.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 }); 


$(document).on("click", ".btnBorrarFoto", function(){
    let id_tecnico = parseInt($('#id_tecnico').text());

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
                        accion = "eliminarArchivoTecnico";
                        $.ajax({
                                url: "models/administrar_legajos_tecnicos.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_tecnico:id_tecnico, nombreFoto: nombreFoto},    
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

    </script>
  </body>
</html>