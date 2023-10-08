<!-- Modal -->
<div class="modal fade" id="modalNuevaActa" tabindex="-1" role="dialog" aria-labelledby="modalNuevaActaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalNuevaActaLabel">Nueva Acta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" onclick="LimpiarNuevaActaTP();">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formActa" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nombreActa">Nombre del acta</label>
                            <input type="text" maxlength="250" class="form-control" id="nombreActa" name="nombreActa" onkeyup="FiltrarCaracteres(this.id, 'itemLista');" style="font-size: 14px;">
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="nombreActa">Archivo del acta</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputActa" name="inputActa" aria-describedby="inputGroupActa" accept=".pdf">
                                <label class="custom-file-label" for="inputActa" style="border-radius: 5px; font-size: 14px;" data-browse="Elejir">Seleccionar archivo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="LimpiarNuevaActaTP();">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="ConfirmarGuardarNuevaActaTP();">Guardar</button>
        </div>
        </div>
    </div>
</div>
<script src="{{asset('vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
<script src="{{asset('js/componentes/nueva_actatp.js')}}"></script>
