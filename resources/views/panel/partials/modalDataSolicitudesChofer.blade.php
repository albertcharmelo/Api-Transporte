<div class="modal fade" id="modalDatosSolicitudesChofer" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
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
                <!-- Input groups with icon -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input class="form-control" id="choferName" readonly  type="text">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input class="form-control" id="choferEmail" readonly  type="email">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <input class="form-control" readonly  id="choferPlaca" type="text">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-vr-cardboard"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <input class="form-control" readonly  id="choferMarca"  type="text">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-car"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input class="form-control" id="choferAño" readonly  type="text">
                          </div>
                        </div>
                      </div>   <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-gas-pump"></i></span>
                            </div>
                            <input class="form-control" id="choferCombustible" readonly type="text">
                          </div>
                        </div>
                      </div>
                </div>
              </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>