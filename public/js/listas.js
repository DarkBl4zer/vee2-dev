var plantillaHTML = new PlantillaHTML();
var datosTabla, dataTable;
datosTabla = dataTable = null;

$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        dropdownCssClass: 'select2_14'
    });
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
        columna.push(plantillaHTML.itemInputText(element, 2));
        columna.push(plantillaHTML.itemValorTabla(element));
        let tipoValor = $('#plantillaTipoValor').html();
        columna.push(tipoValor.replaceAll('@valor', element.tvalorn).replaceAll('@id', element.id));
        columna.push(plantillaHTML.itemInputNumber(element));
        columna.push(plantillaHTML.itemEstadoTabla(element));
        columna.push(plantillaHTML.itemAccionesTabla(element));
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: [
            {title: "Nombre"},
            {title: "Valor"},
            {title: "Tipo de valor"},
            {title: "Elemento padre"},
            {title: "Estado"},
            {title: "Acciones"}
        ],
        data: filas,
        columnDefs: [
            {targets: 4, className: "text-center"},
            {targets: 5, className: "align-middle text-center"}
        ],
        language: {url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'}
    });
}

$('#dataTable').on('draw.dt', function () {
    $('[data-toggle="tooltip"]').tooltip();
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
    let regex = /(\@prefijo|\@nombre|\@sufijo)\b/;
    if ($('#textT4Id'+id).val() != '' && !regex.test($('#textT4Id'+id).val())) {
        $('#textT4Id'+id).addClass('is-invalid');
        valido = false;
    }else{
        $('#textT4Id'+id).removeClass('is-invalid');
    }
    if (id != 0) {
        if ($('.inputFila'+id).is(":visible")) {
            if (valido) {
                $('#confirmacionMsj').html('¿Seguro desea guardar los cambios realizados?');
                $('#confirmacionBtn').attr("onclick","Guardar("+id+");");
                Mostrar('confirmacionModal');
            }
        }
    } else {
        let requeridos = ['textT2Id0'];
        requeridos.forEach(item => {
            if (!ValidarCampo(item)) {
                valido = false;
            }
        });
        if (valido) {
            $('#confirmacionMsj').html('¿Seguro desea guardar el nuevo item?');
            $('#confirmacionBtn').attr("onclick","Guardar("+id+");");
            Mostrar('confirmacionModal');
        }
    }
}

function Guardar(id){
    Ocultar('confirmacionModal');
    let datos = {
        id,
        tipo: $('#lista').val(),
        prefijo: $('#textT1Id'+id).val(),
        nombre: $('#textT2Id'+id).val(),
        sufijo: $('#textT3Id'+id).val(),
        valor_numero: $('#valorT2Id'+id).val(),
        valor_texto: $('#valorT3Id'+id).val(),
        tipo_valor: $('#tipoValor'+id).val(),
        formato: $('#textT4Id'+id).val(),
        id_padre: $('#numberId'+id).val()
    };
    _RQ('POST','/back/crear_actualizar_item_lista', datos, function(result) {$('#loading').hide();
        _MSJ(result.tipo, result.txt, function() {
            ConsultarLista($('#lista').val());
            $('#btnNuevo').prop('disabled', false);
            DisableI('btnNuevo', false, 'Nuevo();');
        });
    });
}

function Nuevo(){
    DisableI('btnNuevo', true);
    let element = {
        id: 0,
        prefijo: null,
        nombre: null,
        sufijo: null,
        valor_numero: null,
        valor_texto: null,
        tipo_valor: 1,
        formato: null,
        id_padre: null,
        tvalorn: null
    };
    let columna = [];
    columna.push(plantillaHTML.itemInputText(element, 2));
    columna.push(plantillaHTML.itemValorTabla(element));
    let tipoValor = $('#plantillaTipoValor').html();
    columna.push(tipoValor.replaceAll('@valor', '').replaceAll('@id', element.id));
    columna.push(plantillaHTML.itemInputNumber(element));
    columna.push('');
    columna.push(plantillaHTML.itemAccionesTabla(element));
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
    _RQ('POST','/back/activar_item', datos, function(result) {$('#loading').hide();
        Ocultar('confirmacionModal');
        _MSJ(result.tipo, result.txt, function() {
            ConsultarLista($('#lista').val());
        });
    });
}

