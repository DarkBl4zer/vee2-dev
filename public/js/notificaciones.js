$(document).ready(function() {
    $('#loading').hide();
    $('#dataTable').DataTable({
        language: {url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'}
    });
});

function EliminarNotificacion(id){
    let datos = {id};
    _RQ('POST','/back/eliminar_notificacion', datos, function(result) {$('#loading').hide();
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    });
}
