window.onload = (event) => {
    $('#modalNuevaActa').on('shown.bs.modal', function (e) {
        bsCustomFileInput.init();
    })
};

function ConfirmarGuardarNuevaActa(){
    let valido = true;
    let requeridos = ['nombreActa', 'inputActa'];
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        $('#confirmacionMsj').html('¿Seguro desea guardar la nueva acta?');
        $('#confirmacionBtn').attr("onclick","GuardarNuevaActa();");
        Mostrar('confirmacionModal');
    }
}

function GuardarNuevaActa(){
    Ocultar('modalNuevaActa');
    _MSJ("success", "¡Registrado!", function() {
        location.reload();
    });
    //************ TODO Guardado de acta *****************************
    /*let datos = new FormData(document.getElementById('formActa'));
    datos.append('nombreActa',$('#nombreActa').val());
    _RQ('POST','/back/guardar_frima', datos, function(result) {$('#loading').hide();
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    }, true);*/
}

function LimpiarNuevaActa(){
    RemoveInvalid('nombreActa');
    RemoveInvalid('inputActa');
    $('#nombreActa').val('');
    $('#inputActa').val('');
    $('#selectActa0').val('');
}
