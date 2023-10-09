$(document).ready(function() {
    let hoy = new Date();
    SetCampoFecha('fechaPG', hoy.toISOString().split('T')[0]);
    ConsultarAcciones();
});

var plantillaHTML = new PlantillaHTML();
function ConsultarAcciones(){
    let thisYear = new Date().getFullYear();
    if (thisYear == $('#periodo').val()) {
        ppEditar = true;
    } else{
        ppEditar = false;
    }
    DisableI('btnNuevo', false, 'Nuevo();');
    $('#btnNuevo').hide();
    LimpiarTabla('dataTable');
    if ($('#periodo').val() != "") {
        let datos = {periodo: $('#periodo').val()};
        _RQ('GET','/back/acciones_por_periodo', datos, function(result) {$('#loading').hide();
            LlenaTabla(result);
        });
    }
}

function LlenaTabla(datos) {
    let filas = [];
    let columns = [
        {title: "#"},
        {title: "Título"},
        {title: "Entidad(es)"},
        {title: "Fecha creación"},
        {title: "Acciones"}
    ];
    let targets = [4];
    if(!puedeEditar){
        columns = [
            {title: "#"},
            {title: "Título"},
            {title: "Entidad(es)"},
            {title: "Delegada / Local"},
            {title: "Fecha creación"},
            {title: "Acciones"}
        ];
        targets = [5];
    }
    datos.forEach(element => {
        let columna = [];
        columna.push(element.numero);
        columna.push(element.titulo);
        columna.push(element.entidades);
        if (!puedeEditar) {
            columna.push(element.delegada);
        }
        columna.push(element.creado);
        columna.push(plantillaHTML.itemAccionesTabla({
            id: element.id,
            editar: true
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
    if (ppEditar) {
        $('#btnNuevo').show();
    }
    $('#loading').hide();
} );

function Nuevo() {
    _RQ('GET','/back/entidades_por_delegada', null, function(result) {$('#loading').hide();
        $('#entidades').html(plantillaHTML.options(result));
        Mostrar('modalNuevaAccion');
    });
    Mostrar('modalNuevaAccion');
}

$('#modalNuevaAccion').on('shown.bs.modal', function () {
    $('#entidades').select2({
        dropdownParent: $('#modalNuevaAccion')
    });
})

function CambiarTipoAccion(){
    if ($('#accion').val() == 2) {
        $('#rowPadre').show();
    }else{
        $('#rowPadre').hide();
    }
}

function CambiarTemaP() {
    if(ValidarCampo('temap')){
        let datos = {temap: $('#temap').val()};
        _RQ('GET','/back/temas_por_temap', datos, function(result) {$('#loading').hide();
            if (result.temas.length > 0) {
                $('#temas').html(plantillaHTML.options(result.temas));
                $('#rowTemaS').show();
            }
            $('#rowActa').attr("onclick","DescargaActa('"+result.acta+"');");
            $('#rowActa').show();
        });
    } else{
        $('#temas').html("");
        $('#rowTemaS').hide();
    }
}

function DescargaActa(archivo){
    window.open('/back/descargar_archivo/?carpeta=vee2_cargados&archivo='+archivo, '_blank');
}

function CheckEntidad(esto){
    $('#entidades_nombres').removeClass('is-invalid');
    $('#entidades_valores').removeClass('is-invalid');
    let oldlabel = $('#entidades_nombres').html();
    let oldvalue = $('#entidades_valores').val();
    if ($(esto).is(':checked')) {
        $('#entidades_nombres').html(oldlabel+esto.dataset.entname+", ");
        $('#entidades_valores').val(oldvalue+esto.value+",");
    } else {
        $('#entidades_nombres').html(oldlabel.replace(esto.dataset.entname+", ", ""));
        $('#entidades_valores').val(oldvalue.replace(esto.value+",", ""));
    }
}

function CambioFecha(idx){
    $('#'+idx).removeClass('is-invalid');
    let valor = $('#'+idx).val();
    let fechaSel = valor.split('/').join('/');
    if (fechaSel != '') {
        if (idx == "fechaPG") {
            SetCampoFecha('fechaIni', fechaSel);
        }
        if (idx == "fechaIni") {
            SetCampoFecha('fechaFin', fechaSel);
        }
    }
}

function ConfirmarGuardarNuevaAccion(){
    $('#idCreaEdita').val(0);
    let valido = true;
    let requeridos = ['temap', 'titulo', 'objetivo_general', 'numero_profesionales', 'fechaPG', 'fechaIni', 'fechaFin'];
    if ($('#rowTemaS').is(':visible')) {
        requeridos.push('temas');
    }
    if ($('#rowPadre').is(':visible')) {
        requeridos.push('id_padre');
    }
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    let entidades = $('#entidades').select2('val');
    if (entidades.length == 0) {
        $('#select2-entidades-container').parent().addClass('invalid-select2');
        valido = false;
    } else{
        $('#select2-entidades-container').parent().removeClass('invalid-select2');
    }
    if (valido) {
        $('#confirmacionMsj').html('¿Seguro desea guardar la nueva acción?');
        $('#confirmacionBtn').attr("onclick","Guardar();");
        Mostrar('confirmacionModal');
    }
}

function Guardar(){
    Ocultar('confirmacionModal');
    Ocultar('modalNuevaAccion');
    let datos = {
        id: $('#idCreaEdita').val(),
        id_actuacion: $('#accion').val(),
        id_temap: $('#temap').val(),
        id_temas: $('#temas').val(),
        titulo: $('#titulo').val(),
        objetivo_general: $('#objetivo_general').val(),
        entidades: $('#entidades').select2('val'),
        numero_profesionales: $('#numero_profesionales').val(),
        fecha_plangestion: $('#fechaPG').val(),
        fecha_inicio: $('#fechaIni').val(),
        fecha_final: $('#fechaFin').val(),
        id_padre: $('#id_padre').val()
    };
    _RQ('POST','/back/crear_actualizar_accion', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            ConsultarAcciones();
        });
    });
}
