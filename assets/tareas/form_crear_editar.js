//var tablaTareas
var tablaItems=$('#tablaItems');
var tablaCargos=$('#tablaCargos');
var id_empresa=$("#id_empresa").html()
var id_estado_guardar_presupuesto

$(document).ready(function(){
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

  //copiamos un objeto a otro objeto nuevo
  idiomaEspItems= Object.assign({},idiomaEsp);
  //modificamos el nuevo objeto
  idiomaEspItems.zeroRecords="No se encontraron registros que coincidan con la búsqueda<br><button type='button' class='btn btn-success' id='btnAgregarNuevoItem'>Agregar</button>";

  $('#modalPresupuesto').on('hidden.bs.modal', function (e) {
    $("#btnCancelAddTarea").click();
    $("#btnCancelAddItem").click();
  });

  obtengoDatosParaAltaItem()

})

function obtengoDatosParaAltaItem(){
  let datosIniciales = new FormData();
  datosIniciales.append('accion', 'traerDatosInicialesItems');
  datosIniciales.append('id_empresa', id_empresa);
  $.ajax({
    data: datosIniciales,
    url: "./models/administrar_items.php",
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(respuesta){
      /*Convierto en json la respuesta del servidor*/
      respuestaJson = JSON.parse(respuesta);
      //Identifico el select de categorias
      $selectCategorias = document.getElementById('categoria');
      //Genero los options del select categoria
      respuestaJson.categorias.forEach((categorias)=>{
        let $optionCat = document.createElement("option");
        let $optionText = document.createTextNode(categorias.categoria);
        $optionCat.appendChild($optionText);
        $optionCat.setAttribute("value", categorias.id_categoria);
        $selectCategorias.appendChild($optionCat);
      })

      //Identifico el select de unidad de medida
      $selectUmedida = document.getElementById('Umedida');
      //Genero los options del select Unidad de medida
      respuestaJson.unidad_medida.forEach((unidad_medida)=>{
        let $optionMedida = document.createElement("option");
        let $optionText = document.createTextNode(unidad_medida.unidad_medida);
        $optionMedida.appendChild($optionText);
        $optionMedida.setAttribute("value", unidad_medida.id_medida);
        $selectUmedida.appendChild($optionMedida);
      });

      //Identifico el select de proveedores
      $selectProveedores = document.getElementById('id_proveedor');
      //Genero los options del select proveedor
      respuestaJson.proveedores.forEach((proveedores)=>{
        let $option = document.createElement("option");
        let $optionText = document.createTextNode(proveedores.razon_social);
        $option.appendChild($optionText);
        $option.setAttribute("value", proveedores.id_proveedor);
        $selectProveedores.appendChild($option);
      })

      //Identifico el select de provincia
      $selecAlmacen= document.getElementById("id_almacen");
      //Genero los options del select almacenes
      respuestaJson.almacenes.forEach((almacenes)=>{
          $option = document.createElement("option");
          let optionText = document.createTextNode(almacenes.almacen);
          $option.appendChild(optionText);
          $option.setAttribute("value", almacenes.id_almacen);
          $selecAlmacen.appendChild($option);
      })

      //Identifico el select centro de costo
      $selectCentroCosto = document.getElementById("id_centro_costos");
      //Genero los options del select Centro Costos
      respuestaJson.centroCostos.forEach((cCosto)=>{
        let $option = document.createElement("option");
        let optionText= document.createTextNode(cCosto.nombreCC);
        $option.appendChild(optionText);
        $option.setAttribute("value", cCosto.id_cc);
        $selectCentroCosto.appendChild($option);
      });

      //Identifico el select tipo de items
      $selectTipo = document.getElementById('tipo');
      //Genero los options del select Tipo de items
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

/*function getElementosCliente(id_elemento){
  let id_cliente_cotizar=$("#id_cliente_cotizar").html()
  let datosIniciales = new FormData();
  datosIniciales.append('accion', 'traerElementosCliente');
  datosIniciales.append('id_cliente', id_cliente_cotizar);
  $.ajax({
    data: datosIniciales,
    url: "./models/administrar_elementos.php",
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(respuesta){
      //console.log(respuesta);
      //Convierto en json la respuesta del servidor
      respuestaJson = JSON.parse(respuesta);
      //console.log(respuestaJson);

      //Identifico el select de elementos y genero los options del select
      $selectElementos= document.getElementById("id_elemento_cliente");
      $selectElementos.innerHTML="";
      $option = document.createElement("option");
      let optionText = document.createTextNode("Seleccione");
      $option.appendChild(optionText);
      $option.setAttribute("value", "");
      $selectElementos.appendChild($option);

      respuestaJson.forEach((elemento)=>{
        $option = document.createElement("option");
        let optionText = document.createTextNode(elemento.descripcion);
        $option.appendChild(optionText);
        $option.setAttribute("value", elemento.id_elemento);
        if(id_elemento==elemento.id_elemento){
          $option.setAttribute("selected", "selected");
        }
        $selectElementos.appendChild($option);
      });

    }
  });
}*/

/*function getRubros(id_rubro){
  let id_cliente_cotizar=$("#id_cliente_cotizar").html()
  let datosIniciales = new FormData();
  datosIniciales.append('accion', 'traerRubros');
  datosIniciales.append('id_cliente', id_cliente_cotizar);
  $.ajax({
    data: datosIniciales,
    url: "./models/administrar_rubros.php?accion=traerRubros",
    method: "get",
    cache: false,
    contentType: false,
    processData: false,
    success: function(respuesta){
      //console.log(respuesta);
      //Convierto en json la respuesta del servidor
      respuestaJson = JSON.parse(respuesta);
      //console.log(respuestaJson);

      //Identifico el select de rubros y genero los options del select
      $selectRubros= document.getElementById("rubroTarea");
      $selectRubros.innerHTML="";
      $option = document.createElement("option");
      let optionText = document.createTextNode("Seleccione");
      $option.appendChild(optionText);
      $option.setAttribute("value", "");
      $selectRubros.appendChild($option);

      respuestaJson.forEach((rubro)=>{
        $option = document.createElement("option");
        let optionText = document.createTextNode(rubro.rubro);
        $option.appendChild(optionText);
        $option.setAttribute("value", rubro.id_rubro);
        if(id_rubro==rubro.id_rubro){
          $option.setAttribute("selected", "selected");
        }
        $selectRubros.appendChild($option);
      });

    }
  });
}*/

/*function getUnidadesMedida(id_unidad_medida){
  $.ajax({
    //data: datosIniciales,
    url: "./models/administrar_unidades.php?accion=traerUnidades",
    method: "get",
    cache: false,
    contentType: false,
    processData: false,
    success: function(respuesta){
      //console.log(respuesta);
      //Convierto en json la respuesta del servidor
      respuestaJson = JSON.parse(respuesta);
      //console.log(respuestaJson);

      //Identifico el select de elementos y genero los options del select
      $selectUnidadesMedida= document.getElementById("unidadMedidaTarea");
      $selectUnidadesMedida.innerHTML="";
      $option = document.createElement("option");
      let optionText = document.createTextNode("Seleccione");
      $option.appendChild(optionText);
      $option.setAttribute("value", "");
      $selectUnidadesMedida.appendChild($option);

      respuestaJson.forEach((unidad_medida)=>{
        $option = document.createElement("option");
        let optionText = document.createTextNode(unidad_medida.unidad_medida);
        $option.appendChild(optionText);
        $option.setAttribute("value", unidad_medida.id_unidad);
        if(id_unidad_medida==unidad_medida.id_unidad){
          $option.setAttribute("selected", "selected");
        }
        $selectUnidadesMedida.appendChild($option);
      });

    }
  });
}*/

function getCargosClienteSelect(aCargos){
  let datosUpdate = new FormData();
  datosUpdate.append('accion', 'traerCargos');
  //datosUpdate.append('id_presupuesto', id_presupuesto);
  $.ajax({
    data: datosUpdate,
    url: './models/administrar_cargos.php',
    //url: './models/administrar_presupuestos.php?accion=traerPresupuestos&id_presupuesto='+id_presupuesto,
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(datosProcesados){
      //console.log(datosProcesados);
      let cargos = JSON.parse(datosProcesados);
      //console.log(cargos);
      
      $selectCargo = document.getElementById('cargos');
      $selectCargo.innerHTML="";
      //Genero los options del select Tipo de items
      cargos.forEach((cargo)=>{
        let $optionTipo = document.createElement("option");
        let $optionTipoText = document.createTextNode(cargo.cargo);
        $optionTipo.appendChild($optionTipoText);
        $optionTipo.setAttribute("value", cargo.id_cargo);
        if(aCargos!=undefined && aCargos.includes(cargo.id_cargo)){
          $optionTipo.setAttribute("selected", true);
        }
        $selectCargo.appendChild($optionTipo);
      });
    }
  });
}

function nuevaTarea(){
  $("#accionFormTarea").html("Agregar Tarea");
  $("#listaTareas").addClass("d-none")
  $("#divFormTarea").removeClass("d-none")
  $("#formAddTarea").trigger("reset");
  $("#btnGuardarCotizacion").prop("disabled",true);
  $("#btnCancelarCotizacion").prop("disabled",true);
  $("#btnEnviarCotizacion").prop("disabled",true);
  //$("#headingTwo2NuevaTarea").click();
  //$("#headingTwo2NuevaTarea").collapse("show");
  accion="addTareaCorrectiva"

  //getElementosCliente();
  /*console.log("getUbicacionesCliente");
  //debugger
  getUbicacionesCliente();
  console.log("getUbicacionesCliente");
  getRubros()
  getUnidadesMedida();*/
  //traerDatosInicialesParaTarea()
  getItems()
  getCargosClienteSelect()
  let tablaCargos=$("#tablaCargos tbody");
  tablaCargos.html("");
  calcularTotalCargos()
  $("#masAdjuntosTarea").addClass("d-none")
  $("#adjuntosTarea").html("")

};

function editarTarea(id_tarea){
  $("#accionFormTarea").html("Editar Tarea ID "+id_tarea);
  $("#listaTareas").addClass("d-none")
  $("#divFormTarea").removeClass("d-none")
  $("#formAddTarea").trigger("reset");
  $("#btnGuardarCotizacion").prop("disabled",true);
  $("#btnCancelarCotizacion").prop("disabled",true);
  $("#btnEnviarCotizacion").prop("disabled",true);
  //$("#headingTwo2NuevaTarea").click();
  $('#id_tarea').html(id_tarea);

  let datosUpdate = new FormData();
  datosUpdate.append('accion', 'traerDetalleTarea');
  datosUpdate.append('id_tarea', id_tarea);
  $.ajax({
    data: datosUpdate,
    url: './models/administrar_tareas.php',
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(datosProcesados){
      //console.log(datosProcesados);
      let datosInput = JSON.parse(datosProcesados);
      console.log(datosInput);
      //let dmp=datosInput.datos_mantenimiento_preventivo;
      //console.log(dmp);

      //getElementosCliente(datosInput.tarea.elemento.id_elemento);
      /*getUbicacionesCliente(datosInput.id_ubicacion);
      getRubros(datosInput.id_rubro)*/
      //$("#id_elemento_cliente").val();
      
      //$("#ubicacionTarea").val(datosInput.tarea.id_ubicacion);
      $("#rubroTarea").val(datosInput.tarea.id_rubro);
      $("#unidadMedidaTarea").val(datosInput.tarea.id_unidad_medida);

      $("#asuntoTarea").val(datosInput.tarea.asunto);
      $("#detalleTarea").val(datosInput.tarea.detalle);
      $("#cantidadTarea").val(datosInput.tarea.cantidad);
      
      //getUnidadesMedida(datosInput.tarea.id_unidad_medida);

      let $divAdjuntos = document.getElementById('adjuntosTarea');
      $divAdjuntos.innerHTML="";
      $("#masAdjuntosTarea").addClass("d-none")

      if(datosInput.adjuntos.length > 0){
        let url_adjuntos = "./views/tareas/";
        let $fragment = document.createDocumentFragment();

        datosInput.adjuntos.forEach((adjuntos)=>{
          let extension = adjuntos.adjunto.split(".");
          let img="./assets/images/imgAdjuntos.jpg"
          if(extension[1] == 'jpg' || extension[1] == 'jpeg' || extension[1] == 'png' || extension[1] == 'gif'){
            img=url_adjuntos+id_tarea+"_"+adjuntos.adjunto
          }
          $divImagen = `
            <div class="col-lg-4 mb-2">
              <div class="col-lg-12">
                <div class="text-center">
                  <img src="${img}" class="img-thumbnail w-50" id=""></br>
                  ${adjuntos.adjunto}
                </div>
              </div> 
              <div class="col-lg-12 text-center mt-1">
                <a class='btn btn-outline-danger btnBorrarAdjuntoTarea text-danger' data-id="${adjuntos.id_adjunto}" data-name="${adjuntos.adjunto}"><i class='fa fa-trash-o'></i></a>
              </div>
            </div>
          `;
          $divAdjuntos.innerHTML+=$divImagen
        });
      }else{
        $('#imgUpdate').attr("src", "");
        $('#imgUpdate').addClass("d-none");
        $('.btnBorrarFoto').addClass("d-none");
      }

      let aItems=[];
      datosInput.materiales.forEach((material)=>{
        let subtotal=parseFloat(material.cantidad_estimada)*parseFloat(material.precio_unitario)
        let objMateriales = {
          "id_item":material.id_item,
          "id_proveedor":material.id_proveedor,
          "precio":material.precio_unitario,
          "cantidad":material.cantidad_estimada,
          "subtotal":subtotal,
        }
        aItems.push(objMateriales);
      })
      getItems(aItems)

      let aCargos=[]
      let tablaCargos=$("#tablaCargos tbody");
      tablaCargos.html("");
      //let totalCargos=0
      datosInput.cargos.forEach((cargo)=>{
        console.log(cargo);
        aCargos.push(cargo.id_cargo)
        let valor_hora=cargo.valor_hora
        let cantidad_personas=cargo.cantidad_personas
        let cantidad_jornadas=cargo.cantidad_jornadas
        let horas_jornada=cargo.horas_jornada

        let subtotal=valor_hora*cantidad_personas*cantidad_jornadas*horas_jornada
        //totalCargos=totalCargos+subtotal;
        subtotalFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotal);
        tablaCargos.append(`
          <tr id='${cargo.id_cargo}'>
            <td>${cargo.cargo}</td>
            <td><input type='number' class='form-control valor_hora' value='${valor_hora}'></td>
            <td><input type='number' class='form-control cant_personas' value='${cantidad_personas}' placeholder='Cantidad de personas'></td>
            <td><input type='number' class='form-control cant_jornadas' value='${cantidad_jornadas}' placeholder='Cantidad de jornadas'></td>
            <td><input type='number' class='form-control horas_jornada' value='${horas_jornada}' placeholder='Horas por jornada'></td>
            <td><label class='subtotal_cargo d-none'>${subtotal}</label><label class='subtotal_cargo_formateado pull-right'>${subtotalFormateado}</label></td>
          </tr>
        `)
      })

      getCargosClienteSelect(aCargos)
      calcularTotalCargos()
      
      accion = "updateTareaCorrectiva";
    }
  });
}

$(document).on("click", ".btnBorrarAdjuntoTarea", function(){
  let elem=$(this).parent().parent();
  let id_adjunto = this.dataset.id;
  let nombre_adjunto = this.dataset.name
  let accion = "borrarAdjunto";

  swal({
    title: "Estas seguro?",
    text: "Una vez eliminado este adjunto, no volveras a verlo",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      //accion = "borrarAdjunto";
      $.ajax({
        url: "models/administrar_tareas.php",
        type: "POST",
        datatype:"json",
        data:  {accion: accion, id_adjunto: id_adjunto, nombre_adjunto: nombre_adjunto},
        success: function(data) {
          if(!data){
            elem.remove();
            swal({
              icon: 'success',
              title: 'Archivo eliminado exitosamente'
            });
          }else{
            swal({
              title: 'Ha ocurrido un error'
            });
          }
        }
      }) 
    } else {
      swal("El registro no se eliminó!");
    }
  })
});

function borrarTarea(id_tarea){
  swal({
    title: "Estas seguro?",
    text: "No volveras a ver esta tarea",
    icon: "warning",
    //buttons: true,
    buttons: ["No",'Si, eliminar tarea'],
    //confirmButtonText: 'Si, cancelar cotizacion',
    //cancelButtonText: "No, continuar cotizando!",
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      accion = "borrarTarea";
      $.ajax({
        url: "models/administrar_tareas.php",
        type: "POST",
        datatype:"json",    
        data:  {accion: accion, id_tarea: id_tarea},
        success: function(data) {
          if(data==""){
            getTareas()
            //$("#modalPresupuesto").hide();
            swal({
              icon: 'success',
              title: 'Tarea eliminada correctamente'
            });
          }else{
            swal({
              title: 'Ha ocurrido un error'
            });
          }
        }
      })
    } else {
      //swal("El registro no se eliminó!");
    }
  })
}

/*$("#btnCancelAddTarea").click(function(){
  console.log("1");
  $("#listaTareas").removeClass("d-none")
  $("#formAddTarea").addClass("d-none")
  $("#btnGuardarCotizacion").prop("disabled",false);
  $("#btnEnviarCotizacion").prop("disabled",false);
  $("#headingTwo2NuevaTarea").collapse("hide");
  document.getElementById('headingTwo2Cotizar').scrollIntoView()
});*/

$(document).on("change keyup",".cantidad_item, .precio_unitario",function(){
  let fila=$(this).parent().parent();
  
  let subtotal_item           =fila.find(".subtotal_item")
  let subtotal_item_formateado=fila.find(".subtotal_item_formateado")
  let cantidad_item           =parseFloat(fila.find(".cantidad_item").val())
  let precio_unitario         =parseFloat(fila.find(".precio_unitario").val())
  
  let subtotal=cantidad_item*precio_unitario;
  if(isNaN(cantidad_item) || isNaN(precio_unitario)){
    subtotal=0
  }
  subtotalFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotal);
  subtotal_item_formateado.html(subtotalFormateado);
  subtotal_item.html(subtotal);
  calcularTotalItems();
})

//llamada cuando cargamos los items de la tarea
function calcularTotalItems(){
  let totalItems=0;
  /*console.log(tablaItems.DataTable().cells(".subtotal_item").node());
  console.log(tablaItems.DataTable().rows().node());
  console.log(tablaItems.DataTable().row().column(8).node());*/
  tablaItems.DataTable().rows().iterator('row', function (context, index) {
    let node = $(this.row(index).node());
    let valor=parseFloat(node.find('.subtotal_item').html());
    if(valor!="" && valor>0){
      totalItems=totalItems+valor;
    }
  })
  let totalItemsFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(totalItems);
  $(".subtotalMateriales").html(totalItems)
  $(".subtotalMaterialesFormateado").html(totalItemsFormateado)
  //actualizarTotalTareas()
  //calcularPorcentajes()
}

$("#cargos").on("select2:selecting",function(e){
  let id_cargo=e.params.args.data.id;

  let datosUpdate = new FormData();
  datosUpdate.append('accion', 'traerCargos');
  datosUpdate.append('id_cargo', id_cargo);
  $.ajax({
    data: datosUpdate,
    url: './models/administrar_cargos.php',
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(datosProcesados){
      //console.log(datosProcesados);
      let datos = JSON.parse(datosProcesados);
      datos=datos[0];
      console.log(datos);
      
      let tablaCargos=$("#tablaCargos tbody");
      tablaCargos.append(`
        <tr id='${datos.id_cargo}'>
          <td>${datos.cargo}</td>
          <td><input type='number' class='form-control valor_hora' value='1500'></td>
          <td><input type='number' class='form-control cant_personas' placeholder='Cantidad de personas'></td>
          <td><input type='number' class='form-control cant_jornadas' placeholder='Cantidad de jornadas'></td>
          <td><input type='number' class='form-control horas_jornada' placeholder='Horas por jornada'></td>
          <td><label class='subtotal_cargo d-none'></label><label class='subtotal_cargo_formateado pull-right'></label></td>
        </tr>
      `)

      $("#"+datos.id_cargo).find(".cant_personas").focus()
    }
  });
})

$("#cargos").on("select2:unselecting",function(e){
  let id_cargo=e.params.args.data.id;

  $("#"+id_cargo).remove();
  calcularTotalCargos()
})

$(document).on("keyup",".valor_hora, .cant_jornadas, .horas_jornada",function(){
  let fila=$(this).parent().parent();
  
  let subtotal_cargo            =fila.find(".subtotal_cargo")
  let subtotal_cargo_formateado =fila.find(".subtotal_cargo_formateado")
  let cant_personas             =parseFloat(fila.find(".cant_personas").val())
  let valor_hora                =parseFloat(fila.find(".valor_hora").val())
  let cant_jornadas             =parseFloat(fila.find(".cant_jornadas").val())
  let horas_jornada             =parseFloat(fila.find(".horas_jornada").val())
  
  let subtotal=cant_personas*valor_hora*cant_jornadas*horas_jornada;
  if(isNaN(cant_personas) || isNaN(valor_hora) || isNaN(cant_jornadas) || isNaN(horas_jornada)){
    subtotal=0
  }
  let subtotalFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotal);
  subtotal_cargo_formateado.html(subtotalFormateado);
  subtotal_cargo.html(subtotal);
  calcularTotalCargos();
})

//llamada cuando cargamos los cargos de la tareas, cuando actualizamos el valor hora, cantidad de personas, joranadas, hs x jornada o cuando eliminamos un cargo
function calcularTotalCargos(){
  let totalCargos=0;
  $(".subtotal_cargo").each(function(){
    let valor=parseFloat(this.textContent);
    if(valor!="" && valor>0){
      totalCargos=totalCargos+valor;
    }
  })
  let totalCargosFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(totalCargos);
  $(".subtotalCargos").html(totalCargos)
  $(".subtotalCargosFormateado").html(totalCargosFormateado)
  //actualizarTotalTareas()
  //calcularPorcentajes()
}

/* NO SE USA DE MOMENTO*/
function actualizarTotalTareas(){
  /*let subtotalCargos=parseFloat($(".subtotalCargos").html())
  let subtotalMateriales=parseFloat($(".subtotalMateriales").html())
  let totalCargosMateriales=subtotalCargos+subtotalMateriales
  $(".totalCargosMateriales").html(totalCargosMateriales)
  totalCargosMaterialesFormateado= new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(totalCargosMateriales);
  $(".totalCargosMaterialesFormateado").html(totalCargosMaterialesFormateado)
  calcularPorcentajes()*/
}

function getItems(aItems){
  //console.log(tablaItems);
  tablaItems.dataTable().fnDestroy();
  tablaItems.DataTable({
    "ajax": {
        //"url" : "./models/administrar_stock.php?accion=traerItems",
        "url" : "./models/administrar_listas.php?accion=traerItems",
        "dataSrc": "",
      },
    "columns":[
      //{"data": "id_item"},
      {"data": "item"},
      {render: function(data, type, row, meta) {
        return ()=>{
          let $img = " ";
          if (row.imagen !=""){
            $img=`<img src="./views/img_items/${row.imagen}" class="img-thumbnail">`;
          }
          return $img;
        };
      }},
      {"data": "unidad_medida"},
      {"data": "proveedor"},
      {"data": "tipo"},
      {"data": "categoria"},
      {render: function(data, type, row, meta) {
        return ()=>{
          let precio=row.precio;
          if(aItems!=undefined){
            aItems.forEach((items)=>{
              if(row.id_item==items.id_item && row.id_proveedor==items.id_proveedor){
                precio=items.precio;
              }
            })
          }
          if (type=="display"){
            return `<input type='number' class='form-control precio_unitario' id='precio_item_${row.id_item}_proveedor_${row.id_proveedor}' value='${precio}' placeholder='Precio unitario'>`;
          }else{
            return precio;
          }
        };
      }},
      {render: function(data, type, row, meta) {
        return ()=>{
          let cantidad="";
          if(aItems!=undefined){
            aItems.forEach((items)=>{
              if(row.id_item==items.id_item && row.id_proveedor==items.id_proveedor){
                cantidad=items.cantidad;
              }
            })
          }
          //if (type == "sort" || type == 'type'){
          if (type=="display"){
            return `<input type='number' class='form-control cantidad_item' value='${cantidad}' data-id-item='${row.id_item}' data-id-proveedor='${row.id_proveedor}' placeholder='Cantidad'>`;
          }else{
            return cantidad;
          }
        };
      }},
      {render: function(data, type, row, meta) {
        return ()=>{
          let subtotal="";
          let subtotalFormateado="";
          if(aItems!=undefined){
            aItems.forEach((items)=>{
              if(row.id_item==items.id_item && row.id_proveedor==items.id_proveedor){
                subtotal=items.subtotal;
                subtotalFormateado=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotal);
              }
            })
          }
          if (type=="display"){
            return `<label class='subtotal_item d-none'>${subtotal}</label><label class='subtotal_item_formateado pull-right'>${subtotalFormateado}</label>`;
          }else{
            return subtotal;
          }
        };
      }},
    ],
    "order": [[ 8, "desc" ]],
    "language":  idiomaEspItems,
    initComplete: function(){
      //detectamos cuando presiona Enter para agregar el producto
      let searchBox=$(tablaItems.DataTable().table().container()).find("div.dataTables_filter input");
      $(document).on("keyup", searchBox, function(e){
        if(e.keyCode == 13){
          document.querySelector("#btnAgregarNuevoItem").click();
        }
      });

      calcularTotalItems()
      
      var b=1;
      var c=0;
      this.api().columns.adjust().draw();//Columns sin parentesis
      this.api().columns().every(function(){//Columns() con parentesis
        if(b<7 && b!=2){
          var column=this;
          var name=$(column.header()).text();
          var select=$("<select id='filtro"+name.replace(/ /g, "")+"' class='form-control form-control-sm filtrosTrato'><option value=''>Todos</option></select>")
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

/*function getCargos(aCargos){
  //console.log(tablaCargos);
  tablaCargos.dataTable().fnDestroy();
  tablaCargos.DataTable({
    "ajax": {
        "url" : "./models/administrar_cargos.php?accion=traerCargos",
        "dataSrc": "",
      },
    "columns":[
      {"data": "id_cargo"},
      {"data": "cargo"},
      {"defaultContent": "<input type='text' class='form-control valor_hora'>"},
      {"defaultContent": "<input type='text' class='form-control cant_jornadas'>"},
      {"defaultContent": "<input type='text' class='form-control horas_jornada'>"},
      {"defaultContent": "<label class='subtotal_cargos'></label>"},
    ],
    "language":  idiomaEsp,
    initComplete: function(){
      var b=1;
      var c=0;
      this.api().columns.adjust().draw();//Columns sin parentesis
      this.api().columns().every(function(){//Columns() con parentesis
        if(b==2){
          var column=this;
          var name=$(column.header()).text();
          var select=$("<select id='filtro"+name.replace(/ /g, "")+"' class='form-control form-control-sm filtrosTrato'><option value=''>Todos</option></select>")
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
}*/

$("#formAddTarea").submit(function(e){
  e.preventDefault();
  let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
  let datosEnviar = new FormData();

  //validaciones
  let id_elemento_cliente=$('#id_elemento_cliente')
  let id_rubro=$('#rubroTarea')
  let asunto=$('#asuntoTarea')
  let detalle=$('#detalleTarea')
  //validamos el activo/elemento del cliente
  /*if(id_elemento_cliente.val()<1 || id_elemento_cliente.val()==""){
    //$("#headingTwo2NuevaTarea").collapse("show"); NO FUNCIONA
    let elem="Two2NuevaTarea";
    $("#heading"+elem).parent().removeClass("collapsed")
    $("#collapse"+elem).addClass("show")
    id_elemento_cliente.focus()
    return false
  }*/
  /*if(id_ubicacion.val()<1 || id_ubicacion.val()==""){
    //$("#headingTwo2NuevaTarea").collapse("show"); NO FUNCIONA
    let elem="Two2NuevaTarea";
    $("#heading"+elem).parent().removeClass("collapsed")
    $("#collapse"+elem).addClass("show")
    id_ubicacion.focus()
    return false
  }*/
  if(id_rubro.val()<1 || id_rubro.val()==""){
    //$("#headingTwo2NuevaTarea").collapse("show"); NO FUNCIONA
    let elem="Two2NuevaTarea";
    $("#heading"+elem).parent().removeClass("collapsed")
    $("#collapse"+elem).addClass("show")
    id_rubro.focus()
    return false
  }
  //validamos el asunto
  if(asunto.val().length==0 || asunto.val()==""){
    //$("#headingTwo2NuevaTarea").collapse("show"); NO FUNCIONA
    let elem="Two2NuevaTarea";
    $("#heading"+elem).parent().removeClass("collapsed")
    $("#collapse"+elem).addClass("show")
    asunto.focus()
    /*asunto[0].setCustomValidity("Seleccione un elemento de la lista.");
    asunto[0].reportValidity()*/
    //asunto[0].required=false
    return false
  }
  //validamos el detalle
  if(detalle.val().length==0 || detalle.val()==""){
    //$("#headingTwo2NuevaTarea").collapse("show"); NO FUNCIONA
    let elem="Two2NuevaTarea";
    $("#heading"+elem).parent().removeClass("collapsed")
    $("#collapse"+elem).addClass("show")
    detalle.focus()
    /*detalle[0].setCustomValidity("Seleccione un elemento de la lista.");
    detalle[0].reportValidity()*/
    //detalle[0].required=false
    return false
  }

  let id_pedido=$("#id_pedido_cotizar").html()
  datosEnviar.append("id_pedido", id_pedido);
  datosEnviar.append("id_elemento_cliente", $.trim(id_elemento_cliente.val()));
  //datosEnviar.append("id_ubicacion", $.trim(id_ubicacion.val()));
  datosEnviar.append("id_rubro", $.trim(id_rubro.val()));
  datosEnviar.append("asunto", $.trim(asunto.val()));
  datosEnviar.append("detalle", $.trim(detalle.val()));
  datosEnviar.append("cantidadTarea", $.trim($("#cantidadTarea").val()));
  datosEnviar.append("unidadMedidaTarea", $.trim($("#unidadMedidaTarea").val()));
  
  datosEnviar.append("id_empresa", id_empresa);
  datosEnviar.append("id_tarea", $("#id_tarea").html());
  datosEnviar.append("accion", accion);
  img = document.getElementById("imagen");

  let cantArchivos = 0;
  if(typeof arrayFiles !== 'undefined'){
    for(let i = 0; i < arrayFiles.length; i++) {
      datosEnviar.append('file'+i, arrayFiles[i]);
      cantArchivos++;
    };
  }else{
    let arrayFiles = "";
  }
  datosEnviar.append('cantAdjuntos', cantArchivos);

  let aItems=[]
  //tablaItems.dataTable().$('tr', {"filter":"applied"}).each(function(){
  tablaItems.DataTable().rows().iterator('row', function (context, index) {
    let node = $(this.row(index).node());
    //cantidad_item=$(this).find(".cantidad_item");
    let cantidad_item=node.find('.cantidad_item');
    let cantidad=cantidad_item.val();
    let id_item=cantidad_item.data("idItem");
    let id_proveedor=cantidad_item.data("idProveedor");
    //let precio=$(this).find("#precio_item_"+id_item+"_proveedor_"+id_proveedor).val();
    let precio=node.find("#precio_item_"+id_item+"_proveedor_"+id_proveedor).val();
    let subtotal=parseFloat(precio)*parseFloat(cantidad);
    if(cantidad!="" && cantidad>0){
      let objMateriales = {
        "id_item":id_item,
        "id_proveedor":id_proveedor,
        "cantidad":cantidad,
        "precio":precio,
        "subtotal":subtotal,
      }
      aItems.push(objMateriales);
    }
  })
  /*console.log(aItems);
  console.log(JSON.stringify(aItems));
  console.log(aItems.length);*/
  datosEnviar.append("itemsJSON", JSON.stringify(aItems));

  let aCargos=[]
  tablaCargos.find("tbody tr").each(function(){
    let id_cargo=this.id;
    let valor_hora=$(this).find(".valor_hora").val()
    let cant_personas=$(this).find(".cant_personas").val()
    let cant_jornadas=$(this).find(".cant_jornadas").val()
    let horas_jornada=$(this).find(".horas_jornada").val()

    if(valor_hora>0 && cant_personas>0 && cant_jornadas>0 && horas_jornada){
      let objCargos = {
        "id_cargo":id_cargo,
        "valor_hora":valor_hora,
        "cant_personas":cant_personas,
        "cant_jornadas":cant_jornadas,
        "horas_jornada":horas_jornada,
      }
      aCargos.push(objCargos);
    }
  })
  /*console.log(aCargos);
  console.log(JSON.stringify(aCargos));
  console.log(aCargos.length);*/
  datosEnviar.append("cargosJSON", JSON.stringify(aCargos));

  $.ajax({
    data: datosEnviar,
    url: "models/administrar_tareas.php",
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
      console.log(data);
      if(data=="" || data>0){
        getTareas(id_pedido)
        $("#btnCancelAddTarea").click();
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
      }else{
        swal({
          title: 'Ha ocurrido un error'
        });
      }
    }
  });
});

$(document).on("click", "#btnNuevoAdjunto", function(){
  $("#inputFile").removeClass("d-none");
});

$(document).on('click', '#btnAddAdjuntosTarea', function(e){
  e.preventDefault();
  $rowMasAdjuntos = document.getElementById("masAdjuntosTarea");
  $rowMasAdjuntos.classList.toggle("d-none");
  $.ajax({
      url: "dropAdjuntosMantenimientoPreventivo.html",
      type: "POST",
      datatype:"json",
      data:{},
      success: function(response) {
        $('#dropMasArchivosTarea').html(response);
      }
    });
});

$(document).on("click", "#btnAgregarNuevoItem", function(){
  document.getElementById("listaItems").classList.add("d-none");
  let valorBuscado=tablaItems.DataTable().search();
  //document.getElementById("descripcion_producto").value = document.getElementById("item_buscar").value;
  valorBuscado=valorBuscado.charAt(0).toUpperCase() + valorBuscado.slice(1);//Ponemos en mayuscula la 1er letra
  document.getElementById("descripcion_producto").value = valorBuscado;

  //LIMPIAMOS LOS FILTROS SELECCIONADOS??????
  $("#tablaItems").DataTable().search( '' ).columns().search( '' ).draw();

  //document.getElementById("item_buscar").value="";
  document.getElementById("altaItem").classList.remove("d-none");
  document.getElementById("descripcion_producto").focus();
  //document.getElementById("footerOT").classList.add("d-none");
  $("#btnGuardarTarea").prop("disabled",true);

  /*let aItems=[]
        console.log($(".cantidad_item"));
        console.log(tablaItems.find("tbody"));
        tablaItems.dataTable().$('tr', {"filter":"applied"}).each(function(){
          cantidad_item=$(this).find(".cantidad_item");
          let cantidad=cantidad_item.val();
          let id_item=cantidad_item.data("idItem");
          let id_proveedor=cantidad_item.data("idProveedor");
          let precio=$(this).find("#precio_item_"+id_item+"_proveedor_"+id_proveedor).val();
          let subtotal=parseFloat(precio)*parseFloat(cantidad);
          if(cantidad!="" && cantidad>0){
            let objMateriales = {
              "id_item":id_item,
              "id_proveedor":id_proveedor,
              "cantidad":cantidad,
              "precio":precio,
              "subtotal":subtotal,
            }
            aItems.push(objMateriales);
          }
        })
        console.log(aItems);*/
})

$(document).on("click", "#btnCancelAddItem", function(){
  document.getElementById("altaItem").classList.add("d-none");
  document.getElementById("listaItems").classList.remove("d-none");
  
  $(tablaItems.DataTable().table().container()).find("div.dataTables_filter input").focus();
  //tablaItems.DataTable().search().focus();
  //document.getElementById("resultadoSearch").innerHTML="";
  //document.getElementById("item_buscar").focus();
  //document.getElementById("footerOT").classList.remove("d-none");
  $("#btnGuardarTarea").prop("disabled",false);
  $("#formAddItem").trigger("reset");
})

$("#formAddItem").submit(function(e){
  e.preventDefault();
  let id_empresa = parseInt(document.getElementById("id_empresa").innerText);
  let datosEnviar = new FormData();
  let precioNuevoItem = $.trim($('#precioNewItem').val());
  let idProveedorNuevoItem = $.trim($('#id_proveedor').val())
  datosEnviar.append("descripcion", $.trim($('#descripcion_producto').val()))
  datosEnviar.append("categoria", $.trim($('#categoria').val()));
  datosEnviar.append("Umedida", $.trim($('#Umedida').val()));
  datosEnviar.append("id_proveedor", idProveedorNuevoItem);
  datosEnviar.append("id_cc", $.trim($('#id_centro_costos').val()));
  datosEnviar.append("tipo", $.trim($('#tipo').val()));
  datosEnviar.append("preposicion", $.trim($('#preposicion').val()));
  datosEnviar.append("precio", precioNuevoItem);
  datosEnviar.append("estado", $.trim($('#estado').val()));
  datosEnviar.append("linkVideo", $.trim($('#linkVideo').val()));
  datosEnviar.append("id_item", $.trim($('#id_item').html()));
  
  datosEnviar.append("id_empresa", id_empresa);
  datosEnviar.append("accion", "addArticulo");
  img = document.getElementById("imagen");

  if (img.files.length > 0) {
    datosEnviar.append("file", img.files[0]);
  }else{
    datosEnviar.append("file", "");
  }

  let cantidadUtilizar=$.trim($('#cantidadNewItem').val())

  $.ajax({
    data: datosEnviar,
    url: "models/administrar_ordenes.php",
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
      console.log(data);
      if(data>0){
        let idNuevoItem=data
        swal({
          icon: 'success',
          title: 'Accion realizada correctamente'
        });
        
        $("#btnCancelAddItem").click();
        
        let aItems=[]
        let objMateriales = {
          "id_item":idNuevoItem,
          "id_proveedor":idProveedorNuevoItem,
          "cantidad":cantidadUtilizar,
          "precio":precioNuevoItem,
          "subtotal":cantidadUtilizar*precioNuevoItem,
        }
        aItems.push(objMateriales);
        tablaItems.dataTable().$('tr', {"filter":"applied"}).each(function(){
          cantidad_item=$(this).find(".cantidad_item");
          let cantidad=cantidad_item.val();
          let id_item=cantidad_item.data("idItem");
          let id_proveedor=cantidad_item.data("idProveedor");
          let precio=$(this).find("#precio_item_"+id_item+"_proveedor_"+id_proveedor).val();
          let subtotal=parseFloat(precio)*parseFloat(cantidad);
          if(cantidad!="" && cantidad>0){
            let objMateriales = {
              "id_item":id_item,
              "id_proveedor":id_proveedor,
              "cantidad":cantidad,
              "precio":precio,
              "subtotal":subtotal,
            }
            aItems.push(objMateriales);
          }
        })
        console.log(aItems);

        getItems(aItems)
      }else{
        swal({
          title: 'Ha ocurrido un error'
        });
      }
    }
  });
});