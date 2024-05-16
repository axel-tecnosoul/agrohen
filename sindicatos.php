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
    <title>MYLA - Sindicatos</title>
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
                    <h3>Sindicatos</h3>
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home_users.php"><i data-feather="home"></i></a></li>
                      <li class="breadcrumb-item active">Sindicatos</li>
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
                    <h5>Administrar Sindicatos</h5>
                     <button id="btnNuevo" type="button" class="btn btn-warning mt-2" data-toggle="modal"><i class="fa fa-plus-square"></i> Agregar</button> 

                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover" id="tablaSindicatos">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">#ID</th>
                            <th>Sindicato</th>
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
                      <span id="id_sindicato" class="d-none"></span>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <form id="formRubros">    
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Nombre del sindicato:</label>
                              <input type="text" class="form-control" id="sindicato" required>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="" class="col-form-label">Jornadas:</label>
                              <select class="form-control" id="jornadas">
                                  <option value="">Selecciones...</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-12 d-none" id="contAddJornada">
                            <div class="row">
                              <div class="col-lg-7">
                                <div class="form-group">
                                  <label for="" class="col-form-label">Valor $:                                   <span id="msgErrValJor" class="text-danger font-italic d-none">(Debe completar este campo)</span></label>
                                  <input type="number" class="form-control" id="valorJornada">
                                </div>
                              </div>
                              <div class="col-lg-5 align-self-center mt-lg-3">
                                  <button class='btn btn-success btnAgregarJornada'><i class='fa fa-plus-circle'></i></button>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <h5 class="mt-2 text-center">Jornadas</h5>
                            <table class="table table-hover" id="tablaJornadas">
                              <thead>
                                <th>Jornada</th>
                                <th>Valor</th>
                                <th>acciones</th>
                              </thead>
                              <tbody class="tJornadasBody">
                                <td colspan="3" class="text-center">Sin jornadas agregadas</td>
                              </tbody>
                              <tfoot></tfoot>
                            </table>
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

      jornadas = new Map();
      jornadasDel = new Map();

      $(document).ready(function(){
       tablaSindicatos= $('#tablaSindicatos').DataTable({
            "ajax": {
                "url" : "./models/administrar_sindicatos.php?accion=traerRubros",
                "dataSrc": "",
              },
            "columns":[
              {"data": "id_sindicato"},
              {"data": "sindicato"},
              {"defaultContent" : "<div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditar'><i class='fa fa-edit'></i></button><button class='btn btn-danger btnBorrar'><i class='fa fa-trash-o'></i></button></div></div>"},
            ],
            "language":  idiomaEsp
        });
       cargarDatosComponentes();

       $('#modalCRUD').on('hidden.bs.modal', function (e) {

          document.getElementById("sindicato").value = "";
          document.getElementById("jornadas").value = "";
          jornadas.clear();
          jornadasDel.clear();
          document.getElementById("contAddJornada").classList.add("d-none");
          let $bodyTablaJornadas= document.querySelector("#tablaJornadas tbody");
          $bodyTablaJornadas.innerHTML=`<td colspan="3" class="text-center">Sin jornadas agregadas</td>`;

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
                    url: "./models/administrar_sindicatos.php",
                    method: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSed: function(){
                        //$('#addProdLocal').modal('hide');
                    },
                    success: function(respuesta){
                       
                        /*Identifico el select de provincia*/
                        $selectJornadas = document.getElementById("jornadas");

                        
                        /*Convierto en json la respuesta del servidor*/
                        respuestaJson = JSON.parse(respuesta);

                        /*Genero los options del select jornadas*/
                        respuestaJson.jornadas.forEach((jornadas)=>{
                            $option = document.createElement("option");
                            let optionText = document.createTextNode(jornadas.tipo_jornada);
                            $option.appendChild(optionText);
                            $option.setAttribute("value", jornadas.id_jornada);
                            $selectJornadas.appendChild($option);
                        })

                    }
                });
            }
    $(document).on("click", ".btnEditar", function(){
      $(".modal-header").css( "background-color", "#22af47");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Editar Rubro");
      $('#modalCRUD').modal('show');      
      fila = $(this).closest("tr");
      let id_sindicato = fila.find('td:eq(0)').text();
      let $bodyTablaJornadas= document.querySelector("#tablaJornadas tbody");
      $bodyTablaJornadas.innerHTML="";    
      let datosUpdate = new FormData();
      datosUpdate.append('accion', 'traerSindicatoUpdate');
      datosUpdate.append('id_sindicato', id_sindicato);
      $.ajax({
            data: datosUpdate,
            url: './models/administrar_sindicatos.php',
            method: "post",
            cache: false,
            contentType: false,
            processData: false,
            beforeSed: function(){
              //$('#procesando').modal('show');
            },
            success: function(datosProcesados){
              let datosInput = JSON.parse(datosProcesados);

              $("#sindicato").val(datosInput.sindicatos[0].sindicato);
              $("#id_sindicato").html(id_sindicato);
              

              if(datosInput.sueldos.length > 0){
                datosInput.sueldos.forEach(sueldos=>{

                  jornadas.set(parseInt(sueldos.id_jornadas_laborales), {nombre: sueldos.tipo_jornada, valor: sueldos.valor, idj: sueldos.id_jornadas_laborales})

                    $tr = `<tr>
                            <td>${sueldos.tipo_jornada}</td><td>${new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(sueldos.valor)}</td><td><div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarJornada' data-id-jor-update="${sueldos.id_jornadas_laborales}"><i class='fa fa-edit'></i></button><button class="btn btn-danger btnQuitarJornada" data-id-jor-del="${sueldos.id_jornadas_laborales}"><i class="fa fa-trash-o"></i></button></div></div></td>
                          </tr>
                          `
                    $bodyTablaJornadas.innerHTML+=$tr;
              });
              }else{
                $bodyTablaJornadas.innerHTML=`<td colspan="3" class="text-center">Sin jornadas agregadas</td>`;
              }

              accion = "updateSindicato";
            }
          });

      $('#modalCRUD').modal('show');
    });

    $('#formRubros').submit(function(e){                         
    e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página   
    let sindicato = $.trim($('#sindicato').val());    
    let id_sindicato = $.trim($('#id_sindicato').text());


      const arrayJornadas = [];
      const arrayJornadasDel = [];

      for(let[key, value] of jornadas){
        arrayJornadas.push({
            id: key,
            valor: value.valor
          });  
      }

      for(let[key, value] of jornadasDel){
        arrayJornadasDel.push({
            id: key,
            id_sindicato: value.id_sindicato
          });  
      }


      let jornadasEnviar = JSON.stringify(arrayJornadas);
      let jornadasEliminar = JSON.stringify(arrayJornadasDel);

        $.ajax({
          url: "models/administrar_sindicatos.php",
          type: "POST",
          datatype:"json",    
          data:  {accion: accion,sindicato:sindicato, jornadas: jornadasEnviar, id_sindicato: id_sindicato, jornadasDel: jornadasEliminar},    
          success: function(data) {
            tablaSindicatos.ajax.reload(null, false);
           }
        });             
      $('#modalCRUD').modal('hide'); 
      swal({
                  icon: 'success',
                  title: 'Accion realizada correctamente'
                });                            
});


    $("#btnNuevo").click(function(){
      accion = "addSindicato";
    $("#formRubros").trigger("reset");
    $(".modal-header").css( "background-color", "#17a2b8");
    $(".modal-header").css( "color", "white" );
    $(".modal-title").text("Alta de Sindicato");
    $('#modalCRUD').modal('show');      
});

//Borrar
$(document).on("click", ".btnBorrar", function(){
    fila = $(this);           
    id_sindicato = parseInt($(this).closest('tr').find('td:eq(0)').text());       

    swal({
                    title: "Estas seguro?",
                    text: "Una vez eliminado este registro, no volveras a verlo",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        accion = "eliminarSindicato";
                        $.ajax({
                                url: "models/administrar_sindicatos.php",
                                type: "POST",
                                datatype:"json",    
                                data:  {accion:accion, id_sindicato:id_sindicato},    
                                success: function() {
                                    tablaSindicatos.row(fila.parents('tr')).remove().draw();                  
                                }
                            }); 
                    } else {
                        swal("El registro no se eliminó!");
                    }
                })
 }); 

$(document).on("change", '#jornadas', function(e){
    let id_jornada = e.target.value;

    if(id_jornada != ""){
      document.getElementById("contAddJornada").classList.remove("d-none");
      document.getElementById("msgErrValJor").classList.add("d-none");
    }else{
      document.getElementById("contAddJornada").classList.add("d-none");
    }

})



$(document).on("click", '.btnAgregarJornada', function(e){
    e.preventDefault();

    let valorJornada = document.getElementById("valorJornada").value;
    let id_jornada = parseInt(document.getElementById("jornadas").value);
    let nombreJornada = $("#jornadas").find('option:selected').text()
    if(valorJornada !=""){

      document.getElementById("msgErrValJor").classList.add("d-none");

      jornadas.set(id_jornada, {nombre: nombreJornada, valor: parseFloat(valorJornada), idj: id_jornada})
      
      document.getElementById("contAddJornada").classList.add("d-none");

      let $bodyTablaJornadas= document.querySelector("#tablaJornadas tbody");

      $bodyTablaJornadas.innerHTML="";

      jornadas.forEach(jornada=>{

        $tr = `<td>${jornada.nombre}</td><td>${new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(jornada.valor)}</td><td><div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarJornada' data-id-jor-update="${jornada.idj}"><i class='fa fa-edit'></i></button><button class="btn btn-danger btnQuitarJornada" data-id-jor-del="${jornada.idj}"><i class="fa fa-trash-o"></i></button></div></div></td>
              `  
        $bodyTablaJornadas.innerHTML+=$tr;
      });

      document.getElementById("valorJornada").value = "";
      document.getElementById("jornadas").value = "";


    }else{
      document.getElementById("msgErrValJor").classList.remove("d-none");
      document.getElementById("valorJornada").focus();
    }
})

  $(document).on("click", ".btnQuitarJornada", function(e){
    e.preventDefault();
    let id_jornada = parseInt(this.getAttribute("data-id-jor-del"));
    let id_sindicato = $.trim($('#id_sindicato').text());
    jornadasDel.set(parseInt(id_jornada), {id_sindicato: parseInt(id_sindicato)})
    jornadas.delete(id_jornada)

    console.log(jornadasDel);

    let $bodyTablaJornadas= document.querySelector("#tablaJornadas tbody");

      $bodyTablaJornadas.innerHTML="";

      if (jornadas.size > 0) {
        jornadas.forEach(jornada=>{
        $tr = `<tr>
                            <td>${jornada.nombre}</td><td>${new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(jornada.valor)}</td><td><div class='text-center'><div class='btn-group'><button class='btn btn-success btnEditarJornada' data-id-jor-update="${jornada.idj}"><i class='fa fa-edit'></i></button><button class="btn btn-danger btnQuitarJornada" data-id-jor-del="${jornada.idj}"><i class="fa fa-trash-o"></i></button></div></div></td>
              </tr>
              `  
        $bodyTablaJornadas.innerHTML+=$tr;
      });
      }else{
        $bodyTablaJornadas.innerHTML=`<td colspan="3" class="text-center">Sin jornadas agregadas</td>`;
      }

  });

  $(document).on("click", ".btnEditarJornada", function(e){
    e.preventDefault();
    let id_jornada = this.getAttribute("data-id-jor-update");

    if(id_jornada != ""){
      document.getElementById("contAddJornada").classList.remove("d-none");
      document.getElementById("msgErrValJor").classList.add("d-none");
      document.getElementById("jornadas").value=id_jornada;
      document.getElementById("valorJornada").focus();
    }else{
      document.getElementById("contAddJornada").classList.add("d-none");
    }
  });

    </script>
  </body>
</html>