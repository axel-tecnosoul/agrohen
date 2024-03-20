<!--Modal para ver detalle-->
<div class="modal fade" id="modalVerDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelVerDetalle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabelVerDetalle"></h5>
        <span id="id_presupuesto_detalle" class="d-none"></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!--Accordion wrapper-->
        <div class="accordion md-accordion" id="accordionVerDetalle" role="tablist" aria-multiselectable="true">
          <!-- Accordion card -->
          <div class="card border-secondary">
            <!-- Card header -->
            <a data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseOne1VerDetalle" aria-expanded="true" aria-controls="collapseOne1VerDetalle">
              <div class="card-header" role="tab" id="headingOne1VerDetalle">
                <h6 class="mb-0">Datos del pedido <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseOne1VerDetalle" class="collapse show" role="tabpanel" aria-labelledby="headingOne1VerDetalle" data-parent="#accordionVerDetalle">
              <div class="card-body border-secondary">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Cliente: </label><span id="lblClienteVerDetalle"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Ubicacion: </label><span id="lblUbicacionVerDetalle"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Contacto: </label><span id="lblContactoVerDetalle"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Prioridad: </label><span id="lblPrioridadVerDetalle"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Descripcion: </label><span id="lblDescripcionVerDetalle"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Fecha: </label><span id="lblFechaVerDetalle"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Número: </label><span id="lblNumeroVerDetalle"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Caducidad: </label><span id="lblCaducidadVerDetalle"></span>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Tipo: </label><span id="lblTipoVerDetalle"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="" class="col-form-label font-weight-bold">Comentarios: </label><span id="lblComentariosVerDetalle"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
          <!-- Accordion card -->
          <div class="card border-secondary">
            <!-- Card header -->
            <a class="collapsed" data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseTwo2VerDetalle" aria-expanded="false" aria-controls="collapseTwo2VerDetalle">
              <div class="card-header" role="tab" id="headingTwo2VerDetalle">
                <h6 class="mb-0">Materiales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseTwo2VerDetalle" class="collapse" role="tabpanel" aria-labelledby="headingTwo2VerDetalle" data-parent="#accordionVerDetalle">
              <div class="card-body border-secondary">
                <div class="table-responsive">
                  <table class="table table-hover" id="tablaItemsVerDetalle">
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
                    <tbody></tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-12 mt-3">
                    <span class="float-right">
                      <label class="font-weight-bold h4">Total:</label>
                      <span class="subtotalMaterialesFormateado h4">$ 0,00</span>
                      <span class="subtotalMateriales d-none"></span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
          <!-- Accordion card -->
          <div class="card border-secondary">
            <!-- Card header -->
            <a class="collapsed" data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseThree3VerDetalle" aria-expanded="false" aria-controls="collapseThree3VerDetalle">
              <div class="card-header" role="tab" id="headingThree3VerDetalle">
                <h6 class="mb-0">Cargos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseThree3VerDetalle" class="collapse" role="tabpanel" aria-labelledby="headingThree3VerDetalle" data-parent="#accordionVerDetalle">
              <div class="card-body border-secondary">
                <div class="table-responsive tablaCargos">
                  <table class="table table-hover" id="tablaCargosVerDetall">
                    <thead class="text-center">
                      <tr>
                        <th>#ID</th>
                        <th>Cargo</th>
                        <th>$/hs</th>
                        <th>Jornadas</th>
                        <th>Hs/Jornada</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tfoot class="text-center">
                      <tr>
                        <th>#ID</th>
                        <th>Cargo</th>
                        <th>$/hs</th>
                        <th>Jornadas</th>
                        <th>Hs/Jornada</th>
                        <th>Subtotal</th>
                      </tr>
                    </tfoot>
                    <tbody></tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-12 mt-3">
                    <span class="float-right">
                      <label class="font-weight-bold h4">Total:</label>
                      <span class="subtotalCargosFormateado h4">$ 0,00</span>
                      <span class="subtotalCargos d-none"></span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
          <!-- Accordion card -->
          <div class="card border-secondary">
            <!-- Card header -->
            <a class="collapsed" data-toggle="collapse" data-parent="#accordionVerDetalle" href="#collapseFour4VerDetalle" aria-expanded="false" aria-controls="collapseFour4VerDetalle">
              <div class="card-header" role="tab" id="headingFour4VerDetalle">
                <h6 class="mb-0">Totales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
              </div>
            </a>
            <!-- Card body -->
            <div id="collapseFour4VerDetalle" class="collapse" role="tabpanel" aria-labelledby="headingFour4VerDetalle" data-parent="#accordionVerDetalle">
              <div class="card-body border-secondary">
                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Total materiales y cargos:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalCargosFormateado pull-right">$ 0,00</span>
                    <span class="subtotalCargos d-none"></span>
                  </div>
                  <div class="col-2 mt-3"></div>
                </div>
                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Gastos generales:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalGastosGeneralesFormateado pull-right">$ 0,00</span>
                    <span class="subtotalGastosGenerales d-none"></span>
                  </div>
                  <div class="col-2 mt-3">
                    <label for="" class="col-form-label" id="lblPorcentajeGastosGenerales"></label>%
                  </div>
                </div>
                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Movilidad:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalMovilidadFormateado pull-right">$ 0,00</span>
                    <span class="subtotalMovilidad d-none"></span>
                  </div>
                  <div class="col-2 mt-3">
                    <label for="" class="col-form-label" id="lblPorcentajeMovilidad"></label>%
                  </div>
                </div>
                <div class="row">
                  <div class="col-4 mt-3 d-flex align-items-center">
                    <label class="font-weight-bold w-100 text-right">Rentabilidad:</label>
                  </div>
                  <div class="col-3 mt-3 align-self-center">
                    <span class="subtotalRentabilidadFormateado pull-right">$ 0,00</span>
                    <span class="subtotalRentabilidad d-none"></span>
                  </div>
                  <div class="col-2 mt-3">
                    <div class="input-group">
                      <label for="" class="col-form-label" id="lblPorcentajeRentabilidad"></label>%
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- FIN Accordion card -->
        </div>
        <!-- Accordion wrapper -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!-- FINAL MODAL para ver detalle-->