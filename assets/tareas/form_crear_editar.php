<form id="formAddTarea"></form>

<form id="formAddItem"></form>

<!-- Datos de la tarea Accordion card-->
<div class="card border-secondary">
  <!-- Card header -->
  <a class="collapsed" data-toggle="collapse" href="#collapseTwo2NuevaTarea" aria-expanded="false" aria-controls="collapseTwo2NuevaTarea">
    <!--  data-parent="#accordionExNuevaTarea" -->
    <div class="card-header" role="tab" id="headingTwo2NuevaTarea">
      <h6 class="mb-0">Datos de la tarea <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
      <span class="d-none" id="id_tarea"></span>
    </div>
  </a>
  <!-- Card body -->
  <div id="collapseTwo2NuevaTarea" class="collapse" role="tabpanel" >
    <div class="card-body border-secondary">
      
      <div class="row">
        <div class="col-lg-6 d-none">
          <div class="form-group d-none">
            <label for="" class="col-form-label">*Ubicacion:</label>
            <select name="ubicacion" class="form-control" id="ubicacionTarea" form="formAddTarea">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="form-group d-none">
            <label for="" class="col-form-label">*Elemento</label>
            <select name="id_elemento" class="form-control" form="formAddTarea" id="id_elemento_cliente">
              <option value="">Seleccione</option>
            </select>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="" class="col-form-label">*Rubro:</label>
            <select name="rubro" class="form-control" id="rubroTarea" form="formAddTarea">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="form-group d-none">
            <label for="" class="col-form-label">*Prioridad:</label>
            <select name="prioridad" class="form-control" id="prioridadTarea" form="formAddTarea">
              <option value="">Seleccione</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="" class="col-form-label">*Titulo:</label>
            <input type="text" name="asunto" class="form-control" id="asuntoTarea" form="formAddTarea">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="" class="col-form-label">*Descripcion:</label>
            <!-- <input type="text" name="detalle" class="form-control" id="detalleTarea" form="formAddTarea"> -->
            <textarea class="form-control" name="detalle" id="detalleTarea" form="formAddTarea"></textarea>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="" class="col-form-label">*Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" step='0.1' min="0" id="cantidadTarea" form="formAddTarea">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="" class="col-form-label">*Unidad de medida:</label>
            <select name="unidadMedida" class="form-control" id="unidadMedidaTarea" form="formAddTarea">
              <option value="">Seleccione</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-4 mb-2"><button id="btnAddAdjuntosTarea" type="button" class="btn btn-secondary"> Agregar adjuntos</button></div>
        <div class="col-lg-12 d-none" id="masAdjuntosTarea">
          <div id="dropMasArchivosTarea"></div>
        </div>
      </div>
      <div class="row border p-2 mt-2 ml-2 mr-2" id="adjuntosTarea"></div>

    </div>
  </div>
</div>

<!-- Materiales Accordion card-->
<div class="card border-secondary">
  <!-- Card header -->
  <a class="collapsed" data-toggle="collapse" href="#collapse2NuevaTarea" aria-expanded="false" aria-controls="collapse2NuevaTarea">
    <div class="card-header" role="tab" id="heading2NuevaTarea">
      <h6 class="mb-0">Materiales previstos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
    </div>
  </a>
  <!-- Card body -->
  <div id="collapse2NuevaTarea" class="collapse" role="tabpanel" >
    
    <!-- lista de items -->
    <div class="card-body border-secondary" id="listaItems">
      
      <div class="table-responsive" style="">
        <table class="table table-hover table-sm" id="tablaItems">
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
      </div>

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
    
    <!-- agregar item -->
    <div class="card-body border-secondary d-none" id="altaItem">
      <div id="contAddItem" class="border p-3 rounded border-dark">
        <h5 class="d-block text-center font-weight-bold">Agregar Item</h5>
        
        
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="descripcion_producto" class="col-form-label">Descripción:</label>
                <input type="text" name="descripcion_producto" class="form-control" form="formAddItem" id="descripcion_producto" required>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="categoria" class="col-form-label">Categoria:</label>
                <select name="categoria" class="form-control" form="formAddItem" id="categoria" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="Umedida" class="col-form-label">Unidad de medida:</label>
                <select name="Umedida" class="form-control" form="formAddItem" id="Umedida" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="id_proveedor" class="col-form-label">Proveedor:</label>
                <select name="id_proveedor" class="form-control" form="formAddItem" id="id_proveedor" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group d-none">
                <label for="id_almacen" class="col-form-label">Almacen:</label>
                <select name="id_almacen" class="form-control" form="formAddItem" id="id_almacen">
                  <option value="">Seleccione</option>
                </select>
              </div>
              <div class="form-group">
                <label for="id_centro_costos" class="col-form-label">Centro de costos:</label>
                <select name="id_centro_costos" class="form-control" form="formAddItem" id="id_centro_costos" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4">
            <div class="form-group">
                <label for="tipo" class="col-form-label">Tipo:</label>
                <select name="tipo" class="form-control" form="formAddItem" id="tipo" required>
                  <option value="">Seleccione</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row"> 
            <div class="col-lg-4">
              <div class="form-group">
                <label for="preposicion" class="col-form-label">Punto de reposición:</label>
                <input type="number" name="preposicion" class="form-control" form="formAddItem" id="preposicion" required>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="precioNewItem" class="col-form-label">Precio:</label>
                <input type="number" name="precioNewItem" class="form-control" form="formAddItem" id="precioNewItem">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="cantidadNewItem" class="col-form-label">Cantidad a utilizar en esta tarea:</label>
                <input type="number" name="cantidadNewItem" class="form-control" form="formAddItem" id="cantidadNewItem">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-4">    
              <div class="form-group">
                <label for="estado" class="col-form-label">Estado:</label>
                <select name="estado" class="form-control" form="formAddItem" id="estado" required>
                  <option value="">Seleccione</option>
                  <option value="Activo">Activo</option>
                  <option value="Inactivo">Inactivo</option>
                </select>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="form-group">
                <label for="linkVideo" class="col-form-label">Link video:</label>
                <input type="url" name="linkVideo" class="form-control" form="formAddItem" id="linkVideo">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-8">
              <div class="form-group">
                <label for="imagen" class="col-form-label">Imagen:</label>
                <input type="file" name="imagen" class="form-control" form="formAddItem" id="imagen" accept="image/*">
              </div>
            </div>
          </div>
          
          <div class="text-right">
            <button type="button" class="btn btn-light" id="btnCancelAddItem">Cancelar</button>
            <input type="submit" id="btnGuardarItem" class="btn btn-dark" form="formAddItem" value="Guardar">
          </div>
          
      </div>
    </div>
  </div>
</div>

<!-- cargos  Accordion card-->
<div class="card border-secondary">
  <!-- Card header -->
  <a class="collapsed" data-toggle="collapse" href="#collapse3NuevaTarea" aria-expanded="false" aria-controls="collapse3NuevaTarea">
    <div class="card-header" role="tab" id="heading3NuevaTarea">
      <h6 class="mb-0">Cargos <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
    </div>
  </a>
  <!-- Card body -->
  <div id="collapse3NuevaTarea" class="collapse" role="tabpanel">
    <div class="card-body border-secondary">
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label for="cargos">Cargos: </label>
            <select id="cargos" name="cargos" data-close-on-select="true" class="js-example-basic-single" multiple style="width: 100%" form="formAddTarea"></select>
          </div>
        </div>
      </div>
      
      <div class="table-responsive tablaCargos">
        <table class="table table-hover" id="tablaCargos">
          <thead class="text-center">
            <tr>
              <th width="20%">Cargo</th>
              <th width="15%">$/hs</th>
              <th width="15%">Cant. personas</th>
              <th width="15%">Jornadas</th>
              <th width="15%">Hs/Jornada</th>
              <th width="20%">Subtotal</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <div class="row">
        <div class="col-12 mt-3">
          <span class="float-right">
            <label class="font-weight-bold h4">Total:</label>
            <span class="subtotalCargosFormateado h4">$ 0,00</span>
            <span class="subtotalCargos d-none">0</span>
          </span>
        </div>
      </div>
    
    </div>
  </div>
</div>

<!-- totales tarea Accordion card-->
<div class="card border-secondary d-none">
  <!-- Card header -->
  <a class="collapsed" data-toggle="collapse" href="#collapse4NuevaTarea" aria-expanded="false" aria-controls="collapse4NuevaTarea">
    <div class="card-header" role="tab" id="heading4NuevaTarea">
      <h6 class="mb-0">Totales <i class="fa fa-angle-down rotate-icon float-right"></i></h6>
    </div>
  </a>
  <!-- Card body -->
  <!-- <div id="collapse4NuevaTarea" class="collapse show" role="tabpanel">
    <div class="card-body border-secondary">
      
      <div class="row">
        <div class="col-4 mt-3 d-flex align-items-center">
          <label class="font-weight-bold w-100 text-right">Total materiales y cargos:</label>
        </div>
        <div class="col-3 mt-3 align-self-center">
          <span class="totalCargosMaterialesFormateado pull-right">$ 0,00</span>
          <span class="totalCargosMateriales d-none"></span>
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
          <div class="input-group">
            <input type="number" value="10" class="form-control" id="porcentaje_gastos_generales">
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
          <span class="subtotalMovilidad d-none"></span>
        </div>
        <div class="col-2 mt-3">
          <div class="input-group">
            <input type="number" value="5" class="form-control" id="porcentaje_movilidad">
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
          <span class="subtotalRentabilidad d-none"></span>
        </div>
        <div class="col-2 mt-3">
          <div class="input-group">
            <input type="number" value="15" class="form-control" id="porcentaje_rentabilidad">
            <div class="input-group-append">
              <span class="input-group-text">%</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row f-20">
        <div class="col-4 mt-3 d-flex align-items-center">
          <label class="font-weight-bold w-100 text-right">Total tarea:</label>
        </div>
        <div class="col-3 mt-3 align-self-center">
          <span class="totalTarea d-none"></span>
          <span class="totalTareaFormateado pull-right font-weight-bold">$ 0,00</span>
        </div>
        <div class="col-2 mt-3"></div>
      </div>

    </div>
  </div> -->
</div>