$(document).ready(function() {
    bsCustomFileInput.init();
    $('#loading').hide();
});

var plantillaHTML = new PlantillaHTML();
var datosTabla, dataTable;
datosTabla = dataTable = null;

function ConsultarTemas(){
    DisableI('btnNuevo', false, 'Nuevo();');
    $('#btnNuevo').hide();
    LimpiarTabla('dataTable');
    if ($('#tipoTema').val() != "") {
        let datos = {tipo: $('#tipoTema').val()};
        _RQ('GET','/back/temas_por_tipo', datos, function(result) {$('#loading').hide();
            datosTabla = result.datos;
            LlenaTabla(result.datos);
        });
    }
}

function LlenaTabla(datos) {
    let filas = [];
    let columns = [
        {title: "Tema principal"},
        {title: "Acta"},
        {title: "Estado"},
        {title: "Acciones"}
    ];
    let targets = [2,3];
    if($('#tipoTema').val() == 2){
        columns = [
            {title: "Tema secundario"},
            {title: "Acta"},
            {title: "Tema principal"},
            {title: "Estado"},
            {title: "Acciones"}
        ];
        targets = [3,4];
    }
    datos.forEach(element => {
        let columna = [];
        columna.push(plantillaHTML.itemInputText({
            id: element.id,
            xid: "Nombre"+element.id,
            valor: element.nombre,
            filtro: "itemLista",
            max: 250
        }));
        let selectActa = $('#plantillaSelectActa').html();
        columna.push(selectActa.replaceAll('@valor', element.acta).replaceAll('@id', element.id));
        if($('#tipoTema').val() == 2){
            let selectActa = $('#plantillaSelectTemasP').html();
            columna.push(selectActa.replaceAll('@valor', element.padre).replaceAll('@id', element.id));
        }
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
        columns: columns,
        data: filas,
        columnDefs: [
            {targets: targets, className: "align-middle text-center", width: "70px"},
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
    $('[data-toggle="tooltip"]').tooltip();
    datosTabla.forEach(element => {
        $('#selectActa'+element.id).val(element.id_acta);
        $('#selectTemasP'+element.id).val(element.id_padre);
        $('.inputFila'+element.id).hide();
    });
    $('#btnNuevo').show();
    $('#loading').hide();
} );

function ConfirmarActivar(id, activar){
    let tipo = (activar)?'<span style="font-weight: bold; color: var(--success);">activar</span>':'<span style="font-weight: bold; color: var(--danger);">inactivar</span>';
    $('#confirmacionMsj').html('¿Seguro desea '+tipo+' el tema seleccionado?');
    $('#confirmacionBtn').attr("onclick","Activar("+id+", "+activar+");");
    Mostrar('confirmacionModal');
}

function Activar(id, activar){
    $('#confirmacionBtn').prop('disabled', true);
    let datos = {id,activar};
    _RQ('POST','/back/activar_tema', datos, function(result) {
        Ocultar('confirmacionModal');
        _MSJ(result.tipo, result.txt, function() {
            ConsultarTemas();
        });
    });
}

function Nuevo(){
    DisableI('btnNuevo', true);
    let element = {
        id: 0,
        nivel: null,
        nombre: "",
        id_acta: "",
        id_padre: null,
        acta: "",
        padre: ""
    };
    let columna = [];
    columna.push(plantillaHTML.itemInputText({
        id: element.id,
        xid: "Nombre"+element.id,
        valor: element.nombre,
        filtro: "itemLista",
        max: 250
    }));
    let selectActa = $('#plantillaSelectActa').html();
    columna.push(selectActa.replaceAll('@valor', element.acta).replaceAll('@id', element.id));
    if($('#tipoTema').val() == 2){
        let selectActa = $('#plantillaSelectTemasP').html();
        columna.push(selectActa.replaceAll('@valor', element.padre).replaceAll('@id', element.id));
    }
    columna.push("");
    columna.push(plantillaHTML.itemAccionesTabla({
        id: element.id,
        guardar: true
    }));
    dataTable.row.add(columna).draw(false);
    dataTable.order([1, 'asc']).draw();
    dataTable.order([0, 'asc']).draw();
}

function CambioSelectActa(id){
    ValidarCampo('selectActa'+id);
    let valor = $('#selectActa'+id).val();
    if (valor==9999) {
        Mostrar('modalNuevaActa');
    }
}

function ConfirmarGuardar(id){
    let valido = true;
    let requeridos = ['inputTextNombre'+id, 'selectActa'+id];
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        let txtMensaje = '¿Seguro desea guardar el nuevo tema?';
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
        nivel: $('#tipoTema').val(),
        nombre: $('#inputTextNombre'+id).val(),
        id_acta: $('#selectActa'+id).val()
    };
    if ($('#tipoTema').val() == 2) {
        datos = {
            id,
            nivel: $('#tipoTema').val(),
            nombre: $('#inputTextNombre'+id).val(),
            id_acta: $('#selectActa'+id).val(),
            id_padre: $('#selectTemasP'+id).val()
        };
    }
    _RQ('POST','/back/crear_actualizar_tema', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            ConsultarTemas();
        });
    });
}

function Editar(id){
    if ($('.dataFila'+id).is(":visible")) {
        $('.dataFila'+id).hide();
        $('.inputFila'+id).show();
    } else {
        $('.inputFila'+id).hide();
        $('.dataFila'+id).show();
    }
}

function ConfirmarCargaMasiva(){
    if (ValidarCampo('inputCargaMasiva')) {
        $('#confirmacionMsj').html('¿Seguro desea realizar la carga masiva de temas?');
        $('#confirmacionBtn').attr("onclick","CargaMasiva();");
        Mostrar('confirmacionModal');
    }
}

function CargaMasiva(){
    let datos = new FormData(document.getElementById('formCargaMasiva'));
    _RQ('POST','/back/carga_masiva_temas', datos, function(result) {
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    }, true);
}
