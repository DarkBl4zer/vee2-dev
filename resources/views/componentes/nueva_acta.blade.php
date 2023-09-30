<!-- Modal -->
<div class="modal fade" id="modalNuevaActa" tabindex="-1" role="dialog" aria-labelledby="modalNuevaActaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalNuevaActaLabel">Nueva Acta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" onclick="LimpiarNuevaActa();">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="nombreActa">Nombre del acta</label>
                        <input type="text" maxlength="250" class="form-control" id="nombreActa" onkeyup="FiltrarCaracteres(this.id, 'texto2');" style="font-size: 14px;">
                    </div>
                </div>
                <div class="col-6">
                    <label for="nombreActa">Archivo del acta</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <form id="formActa" method="post" enctype="multipart/form-data">
                                <input type="file" class="custom-file-input" id="inputActa" name="inputActa" aria-describedby="inputGroupActa" accept=".pdf">
                                <label class="custom-file-label" for="inputActa" style="border-radius: 5px; font-size: 14px;" data-browse="Elejir">Seleccionar archivo</label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="LimpiarNuevaActa();">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="ConfirmarGuardarNuevaActa();">Guardar</button>
        </div>
        </div>
    </div>
</div>
<script src="{{asset('vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
<script src="{{asset('js/componentes/nueva_acta.js')}}"></script>
