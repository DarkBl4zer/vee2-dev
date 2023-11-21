<!-- Modal Ver Accion-->
<div class="modal fade" id="modalVerAccion" tabindex="-1" role="dialog" aria-labelledby="modalVerAccionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalVerAccionLabel">Acción APCXX</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="id_actuacion">Tipo de actuación</label>
                            <input type="text" class="form-control" id="id_actuacion" readonly>
                        </div>
                    </div>
                </div>
                <div class="row" id="rowPadre" style="display: none;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="accion">Acción para seguimiento</label>
                            <input type="text" class="form-control" id="accion" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="temap">Tema principal</label>
                            <input type="text" class="form-control" id="temap" readonly>
                        </div>
                    </div>
                    <div class="col-md-5" id="rowTemaS" style="display: none;">
                        <div class="form-group">
                            <label for="temas">Tema secundario</label>
                            <input type="text" class="form-control" id="temas" readonly>
                        </div>
                    </div>
                    <div class="col-md-1 text-center" id="rowActa" style="display: none;">
                        <i class="fas fa-file-pdf" data-toggle="tooltip" data-placement="top" title="Acta" style="font-size: 30px; padding-top: 33px; cursor: pointer; color: var(--danger);"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <textarea class="form-control" id="titulo" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="objetivo_general">Objetivo general</label>
                            <textarea class="form-control" id="objetivo_general" rows="3" readonly></textarea>
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="entidades">Entidad(s)</label>
                        <textarea class="form-control" id="entidades" rows="1" readonly></textarea>
                    </div>
                </div>
                <div class="row top10">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="numero_profesionales"># Profesionales</label>
                            <input type="text" class="form-control" id="numero_profesionales" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaPG">Fecha plan de gestión</label>
                        <input type="text" class="form-control" id="fechaPG" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaIni">Fecha inicio acción</label>
                        <input type="text" class="form-control" id="fechaIni" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaFin">Fecha fin acción</label>
                        <input type="text" class="form-control" id="fechaFin" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
        </div>
      </div>
    </div>
</div>
<script src="{{asset('js/componentes/ver_accion.js')}}"></script>
