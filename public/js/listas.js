var datosTabla, dataTable;
datosTabla = dataTable = null;

$(document).ready(function() {
    $('.select2').select2({
        language: "es",
        theme: 'bootstrap4',
        dropdownCssClass: 'select2_14'
    });
    $('#loading').hide();
});

function ConsultarLista(valor){
    DisableI('btnNuevo', false, 'Nuevo();');
    $('#btnNuevo').hide();
    ValidarCampo('lista');
    LimpiarTabla('dataTable');
    if (valor != "") {
        let datos = {valor};
        _RQ('GET','/back/datos_config_lista', datos, function(result) {$('#loading').hide();
            datosTabla = result;
            LlenaTabla(result);
        });
    }
}

// Metodo que permite limpiar los datos y eliminar la tabla
function LimpiarTabla(idTabla) {
    if ($.fn.DataTable.isDataTable('#'+idTabla)) {
        var table = $('#'+idTabla).DataTable();
        table.destroy();
        table.clear();
        $('#'+idTabla).empty();
    }
}

function LlenaTabla(datos) {
    let filas = [];
    datos.forEach(element => {
        let columna = [];
        columna.push(plantillaHTML.itemInputText({
            id: element.id,
            xid: "Nombre"+element.id,
            valor: element.nombre,
            filtro: "itemLista",
            max: 250
        }));
        columna.push(plantillaHTML.itemValorLista({
            id: element.id,
            tipo_valor: element.tipo_valor,
            valor_numero: element.valor_numero,
            valor_texto: element.valor_texto,
            filtro: "valorItem",
            max: 15
        }));
        let tipoValor = $('#plantillaTipoValor').html();
        columna.push(tipoValor.replaceAll('@valor', element.tvalorn).replaceAll('@id', element.id));
        columna.push(plantillaHTML.itemEstadoTabla({
            activo: element.activo,
            id: element.id
        }));
        columna.push(plantillaHTML.itemAccionesTabla({
            id: element.id,
            editar: true,
            guardar: true
        }));
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: [
            {title: "Nombre"},
            {title: "Valor"},
            {title: "Tipo de valor"},
            {title: "Estado"},
            {title: "Acciones"}
        ],
        data: filas,
        columnDefs: [
            {targets: [3,4], className: "align-middle text-center", width: "70px"},
            {targets: '_all', className: "align-middle"}
        ],
        language: {url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'}
    });
}

$('#dataTable').on('draw.dt', function () {
    $('td > i').tooltip({template: '<div class="tooltip dtTooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'});
    datosTabla.forEach(element => {
        $('#tipoValor'+element.id).val(element.tipo_valor);
        $('.inputFila'+element.id).hide();
    });
    $('#btnNuevo').show();
} );

function CambioTipoValor(id){
    $('#valorT1Id'+id+', #valorT2Id'+id+', #valorT3Id'+id).hide();
    let valor = $('#tipoValor'+id).val();
    $('#valorT'+valor+'Id'+id).show();
}

function Editar(id){
    if ($('.dataFila'+id).is(":visible")) {
        $('.dataFila'+id).hide();
        $('.inputFila'+id).show();
        CambioTipoValor(id);
    } else {
        $('.inputFila'+id).hide();
        $('.dataFila'+id).show();
    }
}

function ConfirmarGuardar(id){
    let valido = true;
    let requeridos = ['inputTextNombre'+id];
    if ($('#tipoValor'+id).val()!=1) {
        requeridos.push('valorT'+$('#tipoValor'+id).val()+'Id'+id);
    }
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        let txtMensaje = '¿Seguro desea guardar el nuevo item?';
        if (id != 0) {
            txtMensaje = '¿Seguro desea guardar los cambios realizados?';
        }
        $('#confirmacionMsj').html(txtMensaje);
        $('#confirmacionBtn').attr("onclick","Guardar("+id+");");
        Mostrar('confirmacionModal');
    }
}

function Guardar(id){
    Ocultar('confirmacionModal');
    let datos = {
        id,
        tipo: $('#lista').val(),
        nombre: $('#inputTextNombre'+id).val(),
        valor_numero: $('#valorT2Id'+id).val(),
        valor_texto: $('#valorT3Id'+id).val(),
        tipo_valor: $('#tipoValor'+id).val()
    };
    _RQ('POST','/back/crear_actualizar_item_lista', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            ConsultarLista($('#lista').val());
        });
    });
}

function Nuevo(){
    DisableI('btnNuevo', true);
    let element = {
        id: 0,
        nombre: null,
        valor_numero: null,
        valor_texto: null,
        tipo_valor: 1,
        tvalorn: ""
    };
    let columna = [];
    columna.push(plantillaHTML.itemInputText({
        id: element.id,
        xid: "Nombre"+element.id,
        valor: element.nombre,
        filtro: "itemLista",
        max: 250
    }));
    columna.push(plantillaHTML.itemValorLista({
        id: element.id,
        tipo_valor: element.tipo_valor,
        valor_numero: element.valor_numero,
        valor_texto: element.valor_texto,
        filtro: "valorItem",
        max: 15
    }));
    let tipoValor = $('#plantillaTipoValor').html();
    columna.push(tipoValor.replaceAll('@valor', element.tvalorn).replaceAll('@id', element.id));
    columna.push("");
    columna.push(plantillaHTML.itemAccionesTabla({
        id: element.id,
        guardar: true
    }));
    dataTable.row.add(columna).draw(false);
    dataTable.order([1, 'asc']).draw();
    dataTable.order([0, 'asc']).draw();
}

function ConfirmarActivar(id, activar){
    let tipo = (activar)?'<span style="font-weight: bold; color: var(--success);">activar</span>':'<span style="font-weight: bold; color: var(--danger);">inactivar</span>';
    $('#confirmacionMsj').html('¿Seguro desea '+tipo+' el item seleccionado?');
    $('#confirmacionBtn').attr("onclick","Activar("+id+", "+activar+");");
    $('#confirmacionBtn').prop('disabled', false);
    Mostrar('confirmacionModal');
}

function Activar(id, activar){
    $('#confirmacionBtn').prop('disabled', true);
    let datos = {id,activar};
    _RQ('POST','/back/activar_item', datos, function(result) {
        Ocultar('confirmacionModal');
        _MSJ(result.tipo, result.txt, function() {
            ConsultarLista($('#lista').val());
        });
    });
}

