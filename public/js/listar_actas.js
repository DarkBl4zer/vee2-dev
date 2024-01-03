$(document).ready(function() {
    if(!permisos.nuevo){
        $('#btnNuevo').remove();
    }
    ConsultarActas();
});

var actas_tp = [];
function ConsultarActas(){
    $('#loading').show();
    LimpiarTabla('dataTable');
    _RQ('GET','/back/actas_tp', null, function(result) {$('#loading').hide();
        actas_tp = result;
        LlenaTabla(result);
    });
}

function LlenaTabla(datosTabla) {
    let filas = [];
    let columns = [
        {title: "Nombre"},
        {title: "Archivo"},
        {title: "Fecha"},
        {title: "Estado"},
        {title: "Acciones"}
    ];
    datosTabla.forEach(element => {
        let columna = [];
        columna.push(element.descripcion);
        columna.push(plantillaHTML.itemLinkTabla({
            xid: "Archivo",
            id: `'${element.archivo}'`,
            texto: element.nombre_archivo
        }));
        columna.push(element.creado);
        columna.push(plantillaHTML.itemEstadoTabla({
            activo: element.activo,
            id: element.id
        }));
        columna.push(plantillaHTML.itemAccionesActaTabla({
            id: element.id,
            reemplazar: true
        }));
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        columnDefs: [
            {targets: [3,4], className: "align-middle text-center", width: "70px"},
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
    $('#btnNuevo').show();
    $('#loading').hide();
});

function Nuevo() {
    Mostrar('modalNuevaActa');
}

function ClickLinkArchivo(archivo) {
    window.open('/back/descargar_archivo/?carpeta=vee2_cargados&archivo='+archivo, '_blank');
}

function ConfirmarActivar(id, activar){
    let tipo = (activar)?'<span style="font-weight: bold; color: var(--success);">activar</span>':'<span style="font-weight: bold; color: var(--danger);">inactivar</span>';
    $('#confirmacionMsj').html('¿Seguro desea '+tipo+' el acta seleccionada?');
    $('#confirmacionBtn').attr("onclick","Activar("+id+", "+activar+");");
    $('#confirmacionBtn').prop('disabled', false);
    Mostrar('confirmacionModal');
}

function Activar(id, activar){
    $('#confirmacionBtn').prop('disabled', true);
    let datos = {id,activar};
    _RQ('POST','/back/activar_acta', datos, function(result) {
        Ocultar('confirmacionModal');
        _MSJ(result.tipo, result.txt, function() {
            ConsultarActas();
        });
    });
}

function Reemplazar(id) {
    let ops = '<option value="">...</option>';
    actas_tp.forEach(element => {
        if (element.id != id) {
            ops += `<option value="${element.id}">${element.descripcion}</option>`;
        }
    });
    $('#actaReemplazo').html(ops);
    $('#btnGuardarReemplazo').attr('onclick', 'ConfirmarReemplazarActa('+id+');');
    Mostrar('modalReemplazar');
}

function ConfirmarReemplazarActa(id){
    if (ValidarCampo('actaReemplazo')) {
        $('#confirmacionMsj').html('¿Seguro desea reemplazar el acta?');
        $('#confirmacionBtn').attr("onclick","Guardar("+id+");");
        Mostrar('confirmacionModal');
    }
}

function Guardar(id){
    Ocultar('modalReemplazar');
    Ocultar('confirmacionModal');
    let datos = {
        id,
        reemplazo: $('#actaReemplazo').val()
    };
    _RQ('POST','/back/reemplazar_acta', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            ConsultarActas();
        });
    });
}
