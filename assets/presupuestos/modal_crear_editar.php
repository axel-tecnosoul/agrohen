<!--Modal para cotizar-->
<div class="modal fade" id="modalPresupuesto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelCotizar" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabelCotizar">
          <span class="titulo"></span>
          <span class="d-none" id='id_pedido_cotizar'></span>
          <div class="d-inline pl-5 f-24">
            Total: 
            <span id="totalPresupuesto" class="d-none"></span>
            <span id="totalPresupuestoFormateado" class="pull-right font-weight-bold pl-1">$ 0,00</span>
          </div>
        </h5>
        <button type="button" class="close btnCancelarCotizacion" aria-label="Close"><span aria-hidden="true">&times;</span><!--  -->
        </button>
      </div>

      
      <div class="modal-body">
        <!--Accordion wrapper-->
        <div class="accordion md-accordion" id="accordionCotizar" role="tablist" aria-multiselectable="true">
          
          <!-- Accordion card datos del pedido -->
          <div class="card border-secondary">
            <!-- Card header -->
            <a data-toggle="collapse" href="#collapseOne1Cotizar" aria-expanded="true" aria-controls="collapseOne1Cotizar">
              <div class="card-header" role="tab" id="headingOne1Cotizar">
                <h6 class="mb-0">Datos del pedido <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
                <span id="id_estado_pedido" class="d-none"></span>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseOne1Cotizar" class="collapse show" role="tabpanel">
              <div class="card-body border-secondary">
                
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <span class="d-none" id="id_cliente_cotizar"></span>
                      <label for="" class="col-form-label font-weight-bold">Cliente: </label><span id="lblCliente"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Ubicacion: </label><span id="lblUbicacion"></span>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Contacto: </label><span id="lblContacto"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Prioridad: </label><span id="lblPrioridad"></span>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Descripcion: </label><span id="lblDescripcion"></span>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Fecha: </label><span id="lblFecha"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Número: </label><span id="lblNumero"></span>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Caducidad: </label><span id="lblCaducidad"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Tipo: </label><span id="lblTipo"></span>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Comentarios: </label><span id="lblComentarios"></span>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->

          <!-- Accordion card tareas -->
          <div class="card border-secondary">
            <!-- Card header -->
            <a class="collapsed" data-toggle="collapse" href="#collapseTwo2Cotizar" aria-expanded="false" aria-controls="collapseTwo2Cotizar">
              <div class="card-header" role="tab" id="headingTwo2Cotizar">
                <h6 class="mb-0">Tareas <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseTwo2Cotizar" class="collapse" role="tabpanel">

              <!-- lista de tareas -->
              <div class="card-body border-secondary" id="listaTareas">
                
                <button id="btnNuevaTarea" type="button" class="btn btn-primary mb-3" data-toggle="modal"><i class="fa fa-check-square-o"></i> Nueva tarea</button>
                
                <div class="table-responsive" id="contTablaTareas">  
                  <table class="table table-hover" id="tablaTareas">
                    <thead class="text-center">
                      <tr>
                        <th class="text-center">#ID</th>
                        <th class="d-none">Rubro</th>
                        <th>Titulo</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>UM</th>
                        <th>Total</th>
                        <!-- <th>Estado</th> -->
                        <th data-priority="1">Acciones</th>
                      </tr>
                    </thead>
                    <tfoot class="text-center">
                      <tr>
                        <th class="text-center">#ID</th>
                        <th class="d-none">Rubro</th>
                        <th>Titulo</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>UM</th>
                        <th>Total</th>
                        <!-- <th>Estado</th> -->
                        <th>Acciones</th>
                      </tr>
                    </tfoot>
                    <tbody></tbody>
                  </table>
                </div>

              </div>
              
              <!-- alta de tareas -->
              <div class="card-body border-secondary d-none" id="divFormTarea">
                <div id="contAddTarea" class="border p-3 rounded border-dark">
                  <h5 class="d-block text-center font-weight-bold pb-2" id="accionFormTarea">Agregar Tarea</h5>
                  <!--Accordion wrapper-->
                  <div class="accordion md-accordion" id="accordionExNuevaTarea" role="tablist" aria-multiselectable="true">

                    <?php
                    include_once("assets/tareas/form_crear_editar.php")?>
                  
                  </div>
                  <!-- Accordion wrapper -->
                  
                  <div class="text-right">
                    <button type="button" id="btnCancelAddTarea" class="btn btn-light">Cancelar</button>
                    <input type="submit" id="btnGuardarTarea" class="btn btn-dark" form="formAddTarea" value="Guardar">
                  </div>

                </div>
              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
          
          <!-- Accordion card materiales -->
          <div class="card border-secondary d-none">
            <!-- Card header -->
            <a class="collapsed" data-toggle="collapse" href="#collapseTwo2CotizarBis" aria-expanded="false" aria-controls="collapseTwo2CotizarBis">
              <div class="card-header" role="tab" id="headingTwo2Cotizar">
                <h6 class="mb-0">Materiales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseTwo2CotizarBis" class="collapse" role="tabpanel">
              <div class="card-body border-secondary">
                <div class="table-responsive" id="contTablaOrdenBis" style="">
                
                  <table class="table table-hover" id="tablaItemsBis">
                    <thead class="text-center">
                      <tr>
                        <th>Item</th>
                        <th>Imagen</th>
                        <th>UM</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Precio unit.</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tfoot class="text-center">
                      <tr>
                        <th>Item</th>
                        <th>Imagen</th>
                        <th>UM</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Precio unit.</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                      </tr>
                    </tfoot>
                    <tbody></tbody>
                  </table>
                  
                  <div class="row">
                    <div class="col-12 mt-3">
                      <span class="float-right">
                        <label class="font-weight-bold h4">Total:</label>
                        <span class="subtotalMaterialesFormateado h4">$ 0,00</span>
                        <span class="subtotalMateriales d-none">0</span>
                      </span>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
          
          <!-- Accordion card totales-->
          <div class="card border-secondary">
            <!-- Card header -->
            <a class="collapsed" data-toggle="collapse" href="#collapseFour4Cotizar" aria-expanded="false" aria-controls="collapseFour4Cotizar">
              <div class="card-header" role="tab" id="headingFour4Cotizar">
                <h6 class="mb-0">Totales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseFour4Cotizar" class="collapse show" role="tabpanel">
              <div class="card-body border-secondary">
                
                <!-- <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Total materiales:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="totalMaterialesFormateado pull-right">$ 0,00</span>
                    <span class="totalMateriales d-none"></span>
                  </div>
                  <div class="col-2 mt-3"></div>
                </div>
                
                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Total cargos:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="totalCargosFormateado pull-right">$ 0,00</span>
                    <span class="totalCargos d-none">0</span>
                  </div>
                  <div class="col-2 mt-3"></div>
                </div> -->
                
                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Total tareas:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="totalTareasFormateado pull-right">$ 0,00</span>
                    <span class="totalTareas d-none">0</span>
                  </div>
                  <div class="col-2 mt-3"></div>
                </div>

                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Gastos generales:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalGastosGeneralesFormateado pull-right">$ 0,00</span>
                    <span class="subtotalGastosGenerales d-none">0</span>
                  </div>
                  <div class="col-2 mt-3">
                    <div class="input-group">
                      <input type="number" name="porcentaje_gastos_generales" value="10" class="form-control" id="porcentaje_gastos_generales" form="formCotizar" required>
                      <div class="input-group-append">
                        <span class="input-group-text">%</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Movilidad:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalMovilidadFormateado pull-right">$ 0,00</span>
                    <span class="subtotalMovilidad d-none">0</span>
                  </div>
                  <div class="col-2 mt-3">
                    <div class="input-group">
                      <input type="number" name="porcentaje_movilidad" value="5" class="form-control" id="porcentaje_movilidad" form="formCotizar" required>
                      <div class="input-group-append">
                        <span class="input-group-text">%</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Rentabilidad:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalRentabilidadFormateado pull-right">$ 0,00</span>
                    <span class="subtotalRentabilidad d-none">0</span>
                  </div>
                  <div class="col-2 mt-3">
                    <div class="input-group">
                      <input type="number" name="porcentaje_rentabilidad" value="15" class="form-control" id="porcentaje_rentabilidad" form="formCotizar" required>
                      <div class="input-group-append">
                        <span class="input-group-text">%</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row f-20 d-none">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Total presupuesto:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="totalPresupuesto d-none">0</span>
                    <span class="totalPresupuestoFormateado pull-right font-weight-bold">$ 0,00</span>
                  </div>
                  <div class="col-2 mt-3"></div>
                </div>

              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
        
        </div>
        <!-- Accordion wrapper -->
      </div>

      <div class="modal-footer" id="footerOT">
        <button type="button" id="btnCancelarCotizacion" class="btn btn-light">Cancelar</button><!-- data-dismiss="modal" -->
        <input type="submit" id="btnGuardarCotizacion" class="btn btn-dark" form="formCotizar" value="Guardar">
        <!-- <button type="button" id="btnEnviarCotizacion" class="btn btn-dark">Enviar cotizacion</button> -->
      </div>

      <form id="formCotizar"></form>
    </div>
  </div>
</div>
<!-- FINAL MODAL cotizar-->