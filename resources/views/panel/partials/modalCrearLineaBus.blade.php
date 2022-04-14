<div class="modal fade" id="modalCrearLineaBus" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-default">Datos de la Solicitud</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="choferID">
                <!-- Input groups with icon -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input class="form-control" id="linea_nombre" placeholder="Nombre de la línea de Bus" type="text">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-coins"></i></span>
                        </div>
                        <input class="form-control p-0" id="amountLinea"  step="0.1" type="number">
                        <a href="#" onclick="agregarmonto()" class="btn btn-sm btn-primary btn-round btn-icon d-inline" data-toggle="tooltip" data-original-title="agregar tarifa">
                            <span class="btn-inner--text"><i class="fas fa-plus"></i></span>
                        </a>
                      </div>
                    </div>
                  </div>
               
                  <div class="col-12" id="listaDeMontos">
                    
                  </div>
                </div>
          
              </form>
        </div>
        <div class="modal-footer">
         
          <button type="button" class="btn   ml-auto btn-primary" onclick="guardarLineadeBus()" >Guardar</button>
        </div>
      </div>
    </div>
</div>