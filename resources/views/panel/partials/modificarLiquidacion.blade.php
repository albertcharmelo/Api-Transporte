<div class="modal fade" id="modalModificarLiquidacion" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-default">Modificar % de Liquidación</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form>
                
                <!-- Input groups with icon -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                        </div>
                        <input class="form-control p-0" id="comision" value="{{ $comision->comision }}" step="0.1" type="number">
                      </div>
                    </div>
                  </div>
               
                  <div class="col-12" id="listaDeMontos">
                    
                  </div>
                </div>
          
              </form>
        </div>
        <div class="modal-footer">
         
          <button type="button" class="btn   ml-auto btn-primary" id="saveComision" >Guardar</button>
        </div>
      </div>
    </div>
</div>
