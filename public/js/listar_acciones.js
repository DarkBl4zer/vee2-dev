$(document).ready(function() {
    if(!permisos.nuevo){
        $('#btnNuevo').remove();
    }
    let hoy = new Date();
    SetCampoFecha('fechaPG', _HOY);
    ConsultarAcciones();
    _RQ('GET','/back/entidades_por_delegada', null, function(result) {
        $('#entidades').html(plantillaHTML.options(result));
    });
});

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
        _RQ('GET','/back/acciones_por_periodo', datos, function(result) {
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
        {title: "Fechas"},
        {title: "Acciones"}
    ];
    let targets = [4];
    let targets2 = [3];
    if(!permisos.nuevo){
        columns = [
            {title: "#"},
            {title: "Título"},
            {title: "Entidad(es)"},
            {title: "Delegada / Local"},
            {title: "Fechas"},
            {title: "Acciones"}
        ];
        targets = [5];
        targets2 = [4];
    }
    datos.forEach(element => {
        let columna = [];
        columna.push(element.numero+'<br>'+element.nombreestado);
        columna.push(element.titulo);
        columna.push(element.entidades.string);
        if (!permisos.nuevo) {
            columna.push(element.delegada);
        }
        columna.push(element.fechas);
        let editar = false;
        if(permisos.editar && permisos.editar.includes(parseInt(element.estado))){
            editar = true;
        }
        columna.push(plantillaHTML.itemAccionesTabla({
            id: element.id,
            id_accion: element.id,
            estado: element.estado,
            dec_firmada: element.dec_firmada,
            editar: editar,
            conflicto: true,
            documentos: true,
            detalle: true
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
            {targets: targets2, width: "250px"},
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
    if (ppEditar) {
        $('#btnNuevo').show();
    }
    $('#loading').hide();
    $('#entidades').select2({
        language: "es",
        dropdownParent: $('#modalNuevaAccion')
    });
} );

function Nuevo() {
    $('#modalNuevaAccionLabel').html('Nueva acción');
    $('#idCreaEdita').val(0);
    LimpiarFormulario();
    Mostrar('modalNuevaAccion');
}

function CambiarTipoAccion(){
    if ($('#id_actuacion').val() == 2) {
        $('#rowPadre').show();
    }else{
        $('#rowPadre').hide();
    }
}

function CambiarTemaP(load=true) {
    if(ValidarCampo('temap')){
        let datos = {temap: $('#temap').val()};
        _RQ('GET','/back/temas_por_temap', datos, function(result) {
            if (load) {
                $('#loading').hide();
            }
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
        if ($('#idCreaEdita').val()==0) {
            $('#confirmacionMsj').html('¿Seguro desea guardar la nueva acción?');
        } else {
            $('#confirmacionMsj').html('¿Seguro desea actualizar la acción seleccionada?');
        }
        $('#confirmacionBtn').attr("onclick","Guardar();");
        Mostrar('confirmacionModal');
    }
}

function Guardar(){
    Ocultar('confirmacionModal');
    Ocultar('modalNuevaAccion');
    let datos = {
        id: $('#idCreaEdita').val(),
        id_actuacion: $('#id_actuacion').val(),
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

function Editar(id) {
    $('#modalNuevaAccionLabel').html('Actualizar acción');
    LimpiarFormulario();
    let datos = {id};
    _RQ('GET','/back/accion_por_id', datos, function(result) {
        $('#id_actuacion').val(result.id_actuacion);
        $('#temap').val(result.id_temap);
        CambiarTemaP(false);
        if (result.id_padre != null) {
            $('#id_padre').val(result.id_padre);
            $('#rowPadre').show();
        }
        if (result.id_temas != null) {
            $('#temas').html(result.id_temas);
            $('#rowTemaS').show();
        }
        $('#rowActa').attr("onclick","DescargaActa('"+result.archivoacta.archivo+"');");
        $('#rowActa').show();
        $('#titulo').val(result.titulo);
        $('#objetivo_general').val(result.objetivo_general);
        $('#entidades').select2().val(result.entidades.arr).trigger("change");
        $('#numero_profesionales').val(result.numero_profesionales);
        $('#fechaPG').val(result.fecha_plangestion.substring(0, 10).split('-').reverse().join('/'));
        $('#fechaIni').val(result.fecha_inicio.substring(0, 10).split('-').reverse().join('/'));
        $('#fechaFin').val(result.fecha_final.substring(0, 10).split('-').reverse().join('/'));
        setTimeout(() => {
            if (result.id_temas != null) {
                $('#temas').val(result.id_temas);
                $('#rowTemaS').show();
            }
            $('#idCreaEdita').val(id);
            Mostrar('modalNuevaAccion');
            $('#loading').hide();
        }, 1000);
    });
}

function LimpiarFormulario(){
    $('#id_padre').val('');
    $('#rowPadre').hide();
    $('#temas').html("");
    $('#rowTemaS').hide();
    $('#rowActa').attr("onclick","");
    $('#rowActa').hide();
    $('#temap').val('');
    $('#titulo').val('');
    $('#objetivo_general').val('');
    $('#entidades').select2().val([]).trigger("change");
    $('#numero_profesionales').val('');
    $('#fechaPG').val('');
    $('#fechaIni').val('');
    $('#fechaFin').val('');
}

function CrearDeclaracion(id) {
    if (!baseTrabajo.firma) {
        $('#alertNoFirma0').show();
    }
    $('#idCreaEdita').val(id);
    Mostrar('modalImparcialidad');
}

function VerDeclaracion(id, estado, archivo){
    if (permisos.editar && permisos.editar.includes(parseInt(estado))) {
        $('#iconVerDeclaracion').attr('onclick', "window.open('/back/descargar_archivo/?carpeta=vee2_generados&archivo="+archivo+"', '_blank');");
        $('#iconRepetirDeclaracion').attr('onclick', "CrearDeclaracion("+id+")");
        Mostrar('modalVerRepetirDeclaracion');
    } else{
        window.open('/back/descargar_archivo/?carpeta=vee2_generados&archivo='+archivo, '_blank');
    }
}

function VerDetalle(id) {
    let datos = {id};
    _RQ('GET','/back/accion_por_id', datos, function(result) {
        $('#loading').hide();
        $('#bodyTablaDetalle').html(plantillaHTML.itemsTablaDetalleAccion(result));
        Mostrar('modalDetalle');
    });
}
