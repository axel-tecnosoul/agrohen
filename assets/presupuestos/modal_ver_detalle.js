function verDetallePresupuesto(id_presupuesto){
  let modal=$('#modalVerDetalle');
  modal.find(".modal-header").css( "background-color", "#ffc107").css( "color", "white" );
  modal.find(".modal-title").text("Ver presupuesto N° "+id_presupuesto);
  modal.modal('show');

  let datosUpdate = new FormData();
  datosUpdate.append('accion', 'traerPresupuestos');
  datosUpdate.append('id_presupuesto', id_presupuesto);
  $.ajax({
    data: datosUpdate,
    url: './models/administrar_presupuestos.php',
    method: "post",
    cache: false,
    contentType: false,
    processData: false,
    success: function(datosProcesados){
      //console.log(datosProcesados);
      let datosInput = JSON.parse(datosProcesados);
      console.log(datosInput);
      /*let dmp=datosInput.datos_mantenimiento_preventivo;
      console.log(dmp);*/

      $("#lblClienteVerDetalle").html(datosInput.cliente);
      $("#lblUbicacionVerDetalle").html(datosInput.direccion);
      $("#lblElementoVerDetalle").html(datosInput.descripcion_activo);
      $("#lblAsuntoVerDetalle").html(datosInput.asunto);
      $("#lblDetalleVerDetalle").html(datosInput.detalle);
      $("#lblPrioridadVerDetalle").html(datosInput.prioridad);
      //$("#lblContacto").html(datosInput.contacto_cliente);
      //$("#lblid_vehiculo_asignado").html(datosInput.id_vehiculo_asignado);
      //$("#lblcosto_movilidad_estimado").html(datosInput.costo_movilidad_estimado);
      // $("#lblFecha").html(datosInput.fecha_mostrar);
      // $("#lblHoraDesde").html(datosInput.hora_desde_mostrar);
      // $("#lblHoraHasta").html(datosInput.hora_hasta_mostrar);

      $tabla = document.getElementById("tablaItemsVerDetalle");
      $bodyTablaItems = $tabla.querySelector("tbody");
      $bodyTablaItems.innerHTML="";

      if(datosInput.materiales.length>0){
        let totalItems=0;
        datosInput.materiales.forEach((items)=>{
          info_items=items.item
          console.log(items);
          let img = " ";
          if (info_items.imagen !=""){
            let dir="./views/img_items/"+info_items.imagen
            img=`<a href="${dir}" target="_blank" ><img src="${dir}" class="img-thumbnail"></a>`;
          }
          let subtotal=parseFloat(items.cantidad)*parseFloat(info_items.precio_unitario_sin_formato);
          totalItems=totalItems+subtotal
          subtotal=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(subtotal);
          $tr=`<tr>
                <td>${info_items.item}</td>
                <td>${img}</td>
                <td>${info_items.unidad_medida}</td>
                <td>${info_items.proveedor}</td>
                <td>${info_items.tipo}</td>
                <td>${info_items.categoria}</td>
                <td class="pull-right">${info_items.precio_unitario}</td>
                <td>${items.cantidad}</td>
                <td class="pull-right">${subtotal}</td>
            </tr>`;
          $bodyTablaItems.innerHTML +=$tr;
        })
        //$(".subtotalMateriales")
        totalItems=new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(totalItems);
        $(".subtotalMaterialesFormateado").html(totalItems);
      }else{
        $bodyTablaItems.innerHTML=`<tr>
                <td colspan="5" class="text-center">No se han encontrado registros</td>
            </tr>`;
      }

      $('#id_presupuesto').html(id_presupuesto);
      
      accion = "updateMantenimientoPreventivo";
    }
  });
}