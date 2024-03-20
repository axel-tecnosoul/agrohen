/*
INCLUIR tareas/form_crear_editar.js LUEGO DE INCLUIR ESTE ARCHIVO
*/

//var tablaTareas
var tablaPedidos=$('#tablaPedidos')
var tablaPresupuestos=$('#tablaPresupuestos')
var id_empresa=$("#id_empresa").html()
var id_estado_guardar_presupuesto

$(document).ready(function(){

  $('#modalPresupuesto').on('hidden.bs.modal', function (e) {
    $("#btnCancelAddTarea").click();
    $("#btnCancelAddItem").click();
    tablaPresupuestos.DataTable().ajax.reload(null, false);
  });

})

function crearPresupuesto(id_pedido){

  $(document).on("click","#btnCancelarCotizacion",function(e){
    cancelarCotizacion(e)
  })

  let modal=$('#modalPresupuesto')
  modal.find("#id_pedido_cotizar").text(id_pedido);
  modal.find(".modal-header").css( "background-color", "#4466f2").css( "color", "white" );
  modal.find(".titulo").html("Cotizar pedido ID "+id_pedido)

  modal.modal('show');
  $("#formPedido").trigger("reset");
  $("#id_estado_pedido").html(2);

  getItems();//tablaItems.ajax.reload(null, false);//getItems();

  //IMPORTANTE: se iran creando las tareas asociando a id_pedido en lugar de hacerlo al id_presupuesto. Si decide cancelar se le advierte que las tareas se eliminaran y se pregunta si desea guardar el presupuesto en estado borrador
  getTareas()

  let datosUpdate = new FormData();
  datosUpdate.append('accion', 'traerPedidos');
  datosUpdate.append('id_pedido', id_pedido);
  $.ajax({
    data: datosUpdate,
    url: './models/administrar_pedidos.php',
    //url: './models/administrar_pedidos.php?accion=traerPedidos&id_pedido='+id_pedido,
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(datosProcesados){
      //console.log(datosProcesados);
      let datosInput = JSON.parse(datosProcesados);
      datosInput=datosInput[0];
      console.log(datosInput);

      $("#id_cliente_cotizar").html(datosInput.id_cliente)
      //console.log($("#id_cliente_cotizar"));
      $("#lblCliente").html(datosInput.cliente);
      $("#lblUbicacion").html(datosInput.direccion);
      $("#lblContacto").html(datosInput.contacto_cliente);
      $("#lblPrioridad").html(datosInput.prioridad);
      $("#lblDescripcion").html(datosInput.descripcion);
      $("#lblFecha").html(datosInput.fecha_mostrar);
      $("#lblNumero").html(datosInput.nro_ticket);
      $("#lblCaducidad").html(datosInput.fecha_caducidad_mostrar);
      $("#lblTipo").html(datosInput.tipo);
      $("#lblComentarios").html(datosInput.comentarios);
      
      $('#id_pedido').html(id_pedido);
      traerDatosInicialesParaTarea(datosInput.id_cliente)
      
      //accion = "addPresupuesto";
    }
  });
}

function editarPresupuesto(id_pedido){
  let modal=$('#modalPresupuesto')
  modal.find("#id_pedido_cotizar").text(id_pedido);
  modal.find(".modal-header").css( "background-color", "#22af47").css( "color", "white" );
  modal.find(".titulo").text("Editar presupuesto ID "+id_pedido);
  modal.modal('show');

  $(document).on("click","#btnCancelarCotizacion",function(e){
    modal.modal('hide');
  })

  getItems();//tablaItems.ajax.reload(null, false);//getItems();
  
  //IMPORTANTE: se iran creando las tareas asociando a id_pedido en lugar de hacerlo al id_pedido. Si decide cancelar se le advierte que las tareas se eliminaran y se pregunta si desea guardar el presupuesto en estado borrador
  getTareas()

  $("#formCotizar").trigger("reset");

  let datosUpdate = new FormData();
  datosUpdate.append('accion', 'traerPresupuestos');
  datosUpdate.append('id_pedido', id_pedido);
  $.ajax({
    data: datosUpdate,
    url: './models/administrar_presupuestos.php',
    //url: './models/administrar_presupuestos.php?accion=traerPresupuestos&id_pedido='+id_pedido,
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(datosProcesados){
      //console.log(datosProcesados);
      let datosInput = JSON.parse(datosProcesados);
      datosInput=datosInput[0]
      console.log(datosInput);
      let dp=datosInput.pedido;
      //console.log(dmp);

      $("#id_cliente_cotizar").html(dp.id_cliente)
      $("#lblCliente").html(dp.cliente);
      $("#lblUbicacion").html(dp.direccion);
      $("#lblContacto").html(dp.contacto_cliente);
      $("#lblPrioridad").html(dp.prioridad);
      $("#lblDescripcion").html(dp.descripcion);
      $("#lblFecha").html(dp.fecha_mostrar);
      $("#lblNumero").html(dp.numero);
      $("#lblCaducidad").html(dp.caducidad_mostrar);
      $("#lblTipo").html(dp.tipo);
      $("#lblComentarios").html(dp.comentarios);
      
      $("#id_estado_pedido").html(dp.id_estado_pedido);
      $('#id_pedido').html(id_pedido);

      $("#porcentaje_gastos_generales").val(datosInput.porcentaje_gastos);
      $("#porcentaje_movilidad").val(datosInput.porcentaje_movilidad);
      $("#porcentaje_rentabilidad").val(datosInput.porcentaje_rentabilidad);

      calcularPorcentajes()
      traerDatosInicialesParaTarea(dp.id_cliente)
      //accion = "updatePresupuesto";
    }
  });
}

function traerDatosInicialesParaTarea(id_cliente){
  let datosIniciales = new FormData();
  datosIniciales.append('accion', 'traerDatosInicialesTarea');
  datosIniciales.append('id_cliente', id_cliente);
  $.ajax({
    data: datosIniciales,
    url: "./models/administrar_tareas.php",
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(respuesta){
      /*Convierto en json la respuesta del servidor*/
      //console.log(respuesta);
      respuestaJson = JSON.parse(respuesta);
      //console.log(respuestaJson);

      $optionSeleccione = document.createElement("option");
      let optionText = document.createTextNode("Seleccione");
      $optionSeleccione.appendChild(optionText);
      $optionSeleccione.setAttribute("value", "");

      //Identifico el select de categorias
      /*$selectUbicacion= document.getElementById("ubicacionTarea");
      $selectUbicacion.innerHTML="";
      $selectUbicacion.appendChild($optionSeleccione.cloneNode(true));
      respuestaJson.ubicaciones.forEach((ubicacion)=>{
        $option = document.createElement("option");
        let optionText = document.createTextNode(ubicacion.direccion);
        $option.appendChild(optionText);
        $option.setAttribute("value", ubicacion.id_direccion);
        $selectUbicacion.appendChild($option);
      });*/

      $selectRubros= document.getElementById("rubroTarea");
      $selectRubros.innerHTML="";
      $selectRubros.appendChild($optionSeleccione.cloneNode(true));
      respuestaJson.rubros.forEach((rubro)=>{
        $option = document.createElement("option");
        let optionText = document.createTextNode(rubro.rubro);
        $option.appendChild(optionText);
        $option.setAttribute("value", rubro.id_rubro);
        $selectRubros.appendChild($option);
      });

      $selectUnidadesMedida= document.getElementById("unidadMedidaTarea");
      $selectUnidadesMedida.innerHTML="";
      $selectUnidadesMedida.appendChild($optionSeleccione.cloneNode(true));
      respuestaJson.unidades.forEach((unidad_medida)=>{
        $option = document.createElement("option");
        let optionText = document.createTextNode(unidad_medida.unidad_medida);
        $option.appendChild(optionText);
        $option.setAttribute("value", unidad_medida.id_unidad);
        $selectUnidadesMedida.appendChild($option);
      });

    }
  });
}

function cancelarCotizacion(e){
  e.preventDefault();
  swal({
    title: "Estas seguro?",
    text: "Las tareas creadas se eliminarán y no volveras a verlas. Si deseas continuar luego guarda la cotizacion como borrador",
    icon: "warning",
    //buttons: true,
    buttons: ["No, continuar cotizando!",'Si, cancelar cotizacion'],
    //confirmButtonText: 'Si, cancelar cotizacion',
    //cancelButtonText: "No, continuar cotizando!",
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      //accion = "borrarAdjunto";
      $("#modalPresupuesto").modal('hide');
      swal({
        icon: 'success',
        title: 'Cotizacion cancelada'
      });
      /*$.ajax({
        url: "models/administrar_mantenimieno_preventivo.php",
        type: "POST",
        datatype:"json",    
        data:  {accion: accion, id_adjunto: id_adjunto, nombre_adjunto: nombre_adjunto},    
        success: function(data) {
          $("#modalPresupuesto").hide();
          swal({
            icon: 'success',
            title: 'Cotizacion cancelada'
          });
        }
      })*/ 
    } else {
      //swal("El registro no se eliminó!");
    }
  })
}

/*$(document).on("click","#btnGuardarCotizacion",function(){
  id_estado_guardar_presupuesto=1;
  $("#formCotizar").submit();
})
$(document).on("click","#btnEnviarCotizacion",function(){
  id_estado_guardar_presupuesto=2;
  $("#formCotizar").submit();
})*/

$("#formCotizar").submit(function(e){
  //console.log(id_estado_guardar_presupuesto);
  e.preventDefault();

  let id_pedido = parseInt(document.getElementById("id_pedido_cotizar").innerText); 
  let id_estado_pedido = parseInt(document.getElementById("id_estado_pedido").innerText); 
  
  let datosEnviar = new FormData();
  /*let aTareas=[]
  $("#tablaTareas tbody tr").each(function(){
    aTareas.push($(this).find('td:eq(0)').text());
  })*/
  
  datosEnviar.append("porcentaje_gastos_generales", $.trim($('#porcentaje_gastos_generales').val()))
  datosEnviar.append("porcentaje_movilidad", $.trim($('#porcentaje_movilidad').val()));
  datosEnviar.append("porcentaje_rentabilidad", $.trim($('#porcentaje_rentabilidad').val()));
  
  datosEnviar.append("id_pedido", id_pedido);
  datosEnviar.append("id_estado_pedido", id_estado_pedido);
  //datosEnviar.append("tareas", aTareas);
  datosEnviar.append("accion", "editar_pedido_agregando_presupuesto");

  $.ajax({
    data: datosEnviar,
    url: "models/administrar_presupuestos.php",
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
      if(data==""){
        $("#modalPresupuesto").modal("hide")
        /*let msg="Cotizacion enviada correctamente";
        if(id_estado_guardar_presupuesto==1){
          msg="Borrador guardado correctamente";
        }*/
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
      }else{
        swal({
          title: 'Ha ocurrido un error'
        });
      }
      tablaPedidos.DataTable().ajax.reload(null, false);
      tablaPresupuestos.DataTable().ajax.reload(null, false);
    }
  });
});

$("#btnNuevaTarea").click(function(){
  nuevaTarea();
});

$(document).on("click", ".btnEditarTareaPresupuesto", function(){
  fila = $(this).closest("tr");
  let id_tarea = fila.find('td:eq(0)').text();

  editarTarea(id_tarea)
})

$(document).on("click", ".btnBorrarTarea", function(){
  fila = $(this).closest("tr");
  let id_tarea = fila.find('td:eq(0)').text();

  borrarTarea(id_tarea)

})

$("#btnCancelAddTarea").click(function(){
  $("#listaTareas").removeClass("d-none")
  $("#divFormTarea").addClass("d-none")
  $("#btnGuardarCotizacion").prop("disabled",false);
  $("#btnCancelarCotizacion").prop("disabled",false);
  $("#btnEnviarCotizacion").prop("disabled",false);
  $("#headingTwo2NuevaTarea").collapse("hide");
  document.getElementById('headingTwo2Cotizar').scrollIntoView()
});

$(document).on("change keyup","#porcentaje_gastos_generales, #porcentaje_movilidad, #porcentaje_rentabilidad",function(){
  calcularPorcentajes();
})

//llamada cuando actualizamos los porcentajes o cargamos las tareas
function calcularPorcentajes(){
  let totalTareas=parseFloat($(".totalTareas").html())
  //console.log(totalTareas);

  let porcentaje_gastos_generales=parseFloat($("#porcentaje_gastos_generales").val());
  let subtotalGastosGenerales=porcentaje_gastos_generales*totalTareas/100
  if(isNaN(porcentaje_gastos_generales)){
    subtotalGastosGenerales=0;
  }
  $(".subtotalGastosGenerales").html(subtotalGastosGenerales)
  let subtotalGastosGeneralesFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotalGastosGenerales)
  $(".subtotalGastosGeneralesFormateado").html(subtotalGastosGeneralesFormateado)
  
  let porcentaje_movilidad=parseFloat($("#porcentaje_movilidad").val());
  let subtotalMovilidad=porcentaje_movilidad*totalTareas/100
  if(isNaN(porcentaje_movilidad)){
    subtotalMovilidad=0;
  }
  $(".subtotalMovilidad").html(subtotalMovilidad)
  let subtotalMovilidadFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotalMovilidad)
  $(".subtotalMovilidadFormateado").html(subtotalMovilidadFormateado)
  
  let porcentaje_rentabilidad=parseFloat($("#porcentaje_rentabilidad").val())
  let subtotalRentabilidad=porcentaje_rentabilidad*totalTareas/100
  if(isNaN(porcentaje_rentabilidad)){
    subtotalRentabilidad=0;
  }
  $(".subtotalRentabilidad").html(subtotalRentabilidad)
  let subtotalRentabilidadFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotalRentabilidad)
  $(".subtotalRentabilidadFormateado").html(subtotalRentabilidadFormateado)

  actualizarTotal()
}

//llamada cuando cargamos el presupuesto o actualizamos los porcentajes
function actualizarTotal(){
  let totalTareas=parseFloat($(".totalTareas").html())
  let subtotalGastosGenerales=parseFloat($(".subtotalGastosGenerales").html())
  let subtotalMovilidad=parseFloat($(".subtotalMovilidad").html())
  let subtotalRentabilidad=parseFloat($(".subtotalRentabilidad").html())
  
  let total=totalTareas+subtotalGastosGenerales+subtotalMovilidad+subtotalRentabilidad;
  let totalFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(total)
  $("#totalPresupuesto").html(total)
  $("#totalPresupuestoFormateado").html(totalFormateado)
}

function getTareas(){
  /*var mapMateriales=new Map()
  var mapCargos=new Map()*/
  var mapTotalTarea=new Map()
  let id_pedido=$('#id_pedido_cotizar').html();
  let tablaTareas=$('#tablaTareas');
  tablaTareas.dataTable().fnDestroy();//dataTable() con "d" minuscula, retorna un objeto jquery
  tablaTareas.DataTable({//DataTable() con "D" mayuscula, retorna una instancia de la API datatable
    //responsive: true,
    "ajax": {
      //"url" : "./models/administrar_presupuestos.php?accion=traerTareas&"+filtro+"="+id,
      //"url" : "./models/administrar_tareas.php?accion=traerTareas&"+filtros,
      "url" : "./models/administrar_tareas.php?accion=traerTareas&id_pedido="+id_pedido,
      "dataSrc": "",
    },
    "stateSave": true,
    "columns":[
      {"data": "id_tarea"},
      {"data": "rubro","className":"d-none"},
      {"data": "asunto"},
      {"data": "detalle"},
      {"data": "cantidad_mostrar"},
      {"data": "unidad_medida"},
      /*{render: function(data, type, row, meta) {
        return ()=>{
          //RENDER se ejecuta muchas veces, por lo que hago el calculo solo cuando se muestra
          if(type=="display"){
            mapMateriales.set(row.id_tarea,row.total_materiales)
            return new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(row.total_materiales)
          }else{
            return row.total_materiales;
          }
        };
      }},
      {render: function(data, type, row, meta) {
        return ()=>{
          //RENDER se ejecuta muchas veces, por lo que hago el calculo solo cuando se muestra
          if(type=="display"){
            mapCargos.set(row.id_tarea,row.total_cargos)
            //sumaTotalCargos=sumaTotalCargos+parseFloat(row.total_cargos);
            return new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(row.total_cargos);
          }else{
            return row.total_cargos
          }
        };
      }},*/
      {render: function(data, type, row, meta) {
        return ()=>{
          //RENDER se ejecuta muchas veces, por lo que hago el calculo solo cuando se muestra
          if(type=="display"){
            mapTotalTarea.set(row.id_tarea,row.total_tarea)
            return new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(row.total_tarea)
          }else{
            return row.total_tarea;
          }
        };
      }},
      {render: function(data, type, row, meta) {
        return ()=>{
          //si la orden esta finalizada no se puede editar
          
          let btnEditar="<button type='button' class='btn btn-success btnEditarTareaPresupuesto'><i class='fa fa-edit'></i></button>";
          //let btnVer="<button class='btn btn-warning btnVer'><i class='fa fa-eye'></i></button>";
          let btnVer="";
          let btnBorrar="<button type='button' class='btn btn-danger btnBorrarTarea'><i class='fa fa-trash-o'></i></button>";

          if(row.id_estado==3){
            btnBorrar=btnEditar="";
          }
          let buttons=btnEditar+btnVer+btnBorrar;
          return `
          <div class='text-center'>
            <div class='btn-group'>${buttons}</div>
          </div>`;
        };
      }},
    ],
    //order: [[1, 'asc']],
    rowGroup: {
      endRender: function ( rows, group ) {
        var sum = rows.data().pluck("total_tarea").reduce( function (a, b) {
          //return a + b.replace(/[^\d]/g, '')*1;
          //return a + b.replace(/[$.]/g, '')*1;
          return parseFloat(a) + parseFloat(b);
        }, 0);
        return 'Total: '+$.fn.dataTable.render.number('.',',',2,'$').display( sum );
      },
      dataSrc: "rubro"
    },
    columnDefs: [
      {target: 1,visible: false},
    ],
    "language":  idiomaEsp,
    dom: '<"mr-2 d-inline"l>Bfrtip',
    buttons: [
      {
        extend:    'excelHtml5',
        text:      '<i class="fa fa-file-excel-o"></i>',
        titleAttr: 'Excel',
        title:     "Tareas",
        className: 'btn-success',
        exportOptions: {
          columns: ':not(:last-child)',
          /*format: {
            body: function ( data, row, column, node ) {
              // Strip $ from salary column to make it numeric
              return column === 7 ? data.replace( /[$.]/g, '' ).replace( /[,]/g, '.' ) : data;
            }
          }*/
        }
      },
      {
        extend:    'pdfHtml5',
        text:      '<i class="fa fa-file-pdf-o"></i>',
        title:     "Tareas",
        titleAttr: 'PDF',
        download: 'open',
        className: 'btn-danger',
        exportOptions: {
          columns: ':not(:last-child)',
        }
      }
    ],
    initComplete: function(){
      /*let sumaTotalMateriales=0
      mapMateriales.forEach(function(value, key) {
        sumaTotalMateriales+=parseFloat(value)
      })
      let sumaTotalCargos=0
      mapCargos.forEach(function(value, key) {
        sumaTotalCargos+=parseFloat(value)
      })*/
      let sumaTotalTarea=0
      mapTotalTarea.forEach(function(value, key) {
        sumaTotalTarea+=parseFloat(value)
      })
      /*$(".totalMateriales").html(sumaTotalMateriales)
      let sumaTotalMaterialesFormateado=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(sumaTotalMateriales)
      $(".totalMaterialesFormateado").html(sumaTotalMaterialesFormateado)
      
      $(".totalCargos").html(sumaTotalCargos)
      let sumaTotalCargosFormateado=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(sumaTotalCargos)
      $(".totalCargosFormateado").html(sumaTotalCargosFormateado)*/
      
      //let totalTareas=sumaTotalMateriales+sumaTotalCargos
      let totalTareas=sumaTotalTarea
      $(".totalTareas").html(totalTareas)
      let totalTareasFormateado=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(totalTareas)
      $(".totalTareasFormateado").html(totalTareasFormateado)

      calcularPorcentajes()

      var b=1;
      var c=0;
      this.api().columns.adjust().draw();//Columns sin parentesis
      this.api().columns().every(function(){//Columns() con parentesis
        if(b!=1 && b<5){
          var column=this;
          var name=$(column.footer()).text();
          var select=$("<select id='filtro"+name+"' class='form-control form-control-sm filtrosTrato'><option value=''>Todos</option></select>")
            .appendTo($(column.footer()).empty())
            .on("change",function(){
              var val=$.fn.dataTable.util.escapeRegex(
                $(this).val()
              );
              column.search(val ? '^'+val+'$':'',true,false).draw();
            });
          column.data().unique().sort().each(function(d,j){
            var val=$("<div/>").html(d).text();
            if(column.search()==='^'+val+'$'){
              select.append("<option value='"+val+"' selected='selected'>"+val+"</option>");
            }else{
              select.append("<option value='"+val+"'>"+val+"</option>");
            }
          })
        }
        b++;
      })
    }
  });
}