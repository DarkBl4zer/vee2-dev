var jsonUsuariosSP = [];
$(document).ready(function() {
    ConsultarUsuarios();
    $('.select2').select2({
        language: "es",
        theme: 'bootstrap4',
        dropdownParent: $('#addPerfilModal')
    });
    _RQ('GET',getUsuariosVEE, null, function(result) {
        jsonUsuariosSP = result;
    });
});

function ConsultarUsuarios(){
    LimpiarTabla('dataTable');
    _RQ('GET','/back/usuarios', null, function(result) {
        LlenaTabla(result);
    });
}

function LlenaTabla(datosTabla) {
    let filas = [];
    let columns = [
        {title: "Cedula"},
        {title: "Nombre"},
        {title: "Perfiles"},
        {title: "Estado"}
    ];
    datosTabla.forEach(element => {
        let columna = [];
        columna.push(element.cedula);
        columna.push(element.nombre);
        columna.push(plantillaHTML.itemPerfilesUsuario({
            id: element.id,
            apperfiles: element.apperfiles
        }));
        columna.push(plantillaHTML.itemEstadoTabla({
            activo: element.activo,
            id: element.id
        }));
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        columnDefs: [
            {targets: [0], className: "align-middle", width: "68px"},
            {targets: [3], className: "align-middle text-center", width: "70px"},
            {targets: '_all', className: "align-middle"}
        ],
        language: {url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'}
    });
}

function LimpiarTabla(idTabla) {
    if ($.fn.DataTable.isDataTable('#'+idTabla)) {
        var table = $('#'+idTabla).DataTable();
        table.destroy();
        table.clear();
        $('#'+idTabla).empty();
    }
}

$('#dataTable').on('draw.dt', function () {
    $('td > i').tooltip({template: '<div class="tooltip dtTooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'});
    $('#btnSync').show();
    $('#loading').hide();
});

function NuevoPerfil(id){
    $('#rol').val('').trigger('change');
    $('#delegada').val('').trigger('change');
    $('#tipo_coord').val('').trigger('change');
    $('#idUsuario').val(id);
    LimpiarRequerido(['rol', 'delegada', 'tipo_coord']);
    Mostrar('addPerfilModal');
}

function CambioRol(){
    ValidarCampo('rol');
    if ($('#rol').val() == 1) {
        $('#div_delegada').hide();
        $('#div_tipo_coord').hide();
    }
    if ($('#rol').val() == 2) {
        $('#div_delegada').hide();
        $('#div_tipo_coord').show();
    }
    if ($('#rol').val() > 2) {
        $('#div_delegada').show();
        $('#div_tipo_coord').hide();
    }
}

function GuardarPerfil(){
    let requeridos = ['idUsuario', 'rol'];
    if ($('#rol').val() == 2) {
        requeridos.push('tipo_coord')
    }
    if ($('#rol').val() > 2) {
        requeridos.push('delegada')
    }
    let valido = true;
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        $('#btnGuardarPerfil').prop('disabled', true);
        let datos = {
            id: $('#idUsuario').val(),
            rol: $('#rol').val(),
            delegada: $('#delegada').val(),
            tipo: $('#tipo_coord').val()
        };
        _RQ('POST','/back/agregar_perfil', datos, function(result) {
            _MSJ(result.tipo, result.txt, function() {
                location.reload();
            });
        });
    }
}

function ConfirmarEliminarPerfil(id){
    $('#confirmacionMsj').html('¿Seguro desea eliminar el perfil seleccionado?');
    $('#confirmacionBtn').attr("onclick","EliminarPerfil("+id+");");
    Mostrar('confirmacionModal');
}

function EliminarPerfil(id){
    $('#confirmacionBtn').prop('disabled', true);
    let datos = {id};
    _RQ('POST','/back/eliminar_perfil', datos, function(result) {
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    });
}

function ConfirmarActivar(id, activar){
    let tipo = (activar)?'<span style="font-weight: bold; color: var(--success);">activar</span>':'<span style="font-weight: bold; color: var(--danger);">inactivar</span>';
    $('#confirmacionMsj').html('¿Seguro desea '+tipo+' el usuario seleccionado?');
    $('#confirmacionBtn').attr("onclick","Activar("+id+", "+activar+");");
    Mostrar('confirmacionModal');
}

function Activar(id, activar){
    $('#confirmacionBtn').prop('disabled', true);
    let datos = {id,activar};
    _RQ('POST','/back/activar_usuario', datos, function(result) {
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    });
}

function ConfSyncUsuariosSinproc(){
    $('#confirmacionMsj').html('¿Esta apunto de traer la información de usuarios registrados en SINPROC? Esto puede tardar varios minutos.');
    $('#confirmacionBtn').attr("onclick","SyncUsuariosSinproc();");
    Mostrar('confirmacionModal');
}

function SyncUsuariosSinproc(){
    if (jsonUsuariosSP.length > 0) {
        $('#btnSync').prop('disabled', true);
        _RQ('POST','/back/sincronizar_usuarios', jsonUsuariosSP, function(result) {
            _MSJ(result.tipo, result.txt, function() {
                location.reload();
            });
        });
    } else {
        console.error('No se pudo cargar la información desde el servicio web de SINPROC.');
        _MSJ("error", "¡Error!, detalle en consola.", function() {
            location.reload();
        });
    }
}
