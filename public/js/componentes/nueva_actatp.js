window.onload = (event) => {
    $('#modalNuevaActa').on('shown.bs.modal', function (e) {
        bsCustomFileInput.init();
    })
};

function ConfirmarGuardarNuevaActaTP(){
    let valido = true;
    let requeridos = ['nombreActa', 'inputActa'];
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        $('#confirmacionMsj').html('¿Seguro desea guardar la nueva acta?');
        $('#confirmacionBtn').attr("onclick","GuardarNuevaActaTP();");
        Mostrar('confirmacionModal');
    }
}

function GuardarNuevaActaTP(){
    let datos = new FormData(document.getElementById('formActa'));
    _RQ('POST','/back/guardar_actatp', datos, function(result) {
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    }, true);
}

function LimpiarNuevaActaTP(){
    RemoveInvalid('nombreActa');
    RemoveInvalid('inputActa');
    $('#nombreActa').val('');
    $('#inputActa').val('');
    $('#selectActa0').val('');
}
