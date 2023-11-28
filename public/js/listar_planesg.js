$(document).ready(function() {
    if(!permisos.nuevo){
        $('#btnNuevo').remove();
    }
    let hoy = new Date();
    SetCampoFecha('fecha_informe', hoy.toISOString().split('T')[0]);
    bsCustomFileInput.init();
    ConsultarPlanes();
});

var thisYear = new Date().getFullYear();

function ConsultarPlanes(){
    LimpiarTabla('dataTable');
    if ($('#periodo').val() != "") {
        _RQ('GET','/back/planes_gestion', null, function(result) {
            LlenaTabla(result);
        });
    }
}

function LlenaTabla(datos) {
    let filas = [];
    let columns = [
        {title: "#"},
        {title: "Título"},
        {title: "Equipo"},
        {title: "Fechas"},
        {title: "Acciones"}
    ];
    let targets = [4];
    if(!permisos.nuevo){
        columns = [
            {title: "#"},
            {title: "Título"},
            {title: "Equipo"},
            {title: "Delegada / Local"},
            {title: "Fechas"},
            {title: "Acciones"}
        ];
        targets = [5];
    }
    datos.forEach(element => {
        let columna = [];
        columna.push(element.accion.numero+'<br>'+element.nombreestado);
        columna.push(element.accion.titulo);
        columna.push(plantillaHTML.itemEquipoPlangestion(element.declaraciones));
        if (!permisos.nuevo) {
            columna.push(element.delegada);
        }
        columna.push(element.fechas);
        let editar = false;
        if(permisos.editar && permisos.editar.includes(parseInt(element.estado))){
            editar = true;
        }
        let conflicto = false;
        if (element.estado > 1) {
            conflicto = true;
        }
        columna.push(plantillaHTML.itemAccionesTabla({
            id: element.id_accion,
            id_accion: element.id_accion,
            estado: element.estado,
            editar: editar,
            documentos: true,
            detalle: true,
            generar_pg: true,
            conflicto: conflicto,
            dec_firmada: element.dec_firmada,
            aprobar: true,
            aprobar_d: 'Viabilidad delegado'
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
            {targets: [2], className: "align-middle", width: "270px"},
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
    $('td i').tooltip({template: '<div class="tooltip dtTooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'});
    $('#loading').hide();
});

var DatosTablaUsuarios = null;
function Nuevo() {
    $('#idCreaEdita').val(0);
    arrUsuarios = [];
    LimpiarTabla('dataTableUsuariosIn');
    LimpiarTabla('dataTableUsuariosOut');
    _RQ('GET','/back/usuarios_plan_gestion', null, function(result) {$('#loading').hide();
        DatosTablaUsuarios = result;
        $('#accion').html(plantillaHTML.options(result.acciones));
        Mostrar('modalNuevoPlan');
    });
}

$('#modalNuevoPlan').on('shown.bs.modal', function () {
    $('#accion').select2({
        dropdownParent: $('#modalNuevoPlan')
    });
    LlenaTablaUsuarios(DatosTablaUsuarios.usuariosIn, 'dataTableUsuariosIn');
    LlenaTablaUsuarios(DatosTablaUsuarios.usuariosOut, 'dataTableUsuariosOut');
});

function LlenaTablaUsuarios(datos, tabla) {
    let filas = [];
    let columns = [
        {title: ""},
        {title: "Cedula"},
        {title: "Nombre"}
    ];
    if (tabla == 'dataTableUsuariosOut'){
        columns = [
            {title: ""},
            {title: "Cedula"},
            {title: "Nombre"},
            {title: "Delegada / Local"}
        ];
    }
    datos.forEach(element => {
        let columna = [];
        columna.push(plantillaHTML.itemCheckbox({
            id: element.id,
            checked: false
        }));
        columna.push(element.cedula);
        columna.push(element.nombre);
        if (tabla == 'dataTableUsuariosOut') {
            columna.push(element.delegada);
        }
        filas.push(columna);
    });
    $('#'+tabla).DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        lengthMenu: [
            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100]
        ],
        columnDefs: [
            {targets: [0], width: "20px"},
            {targets: [1], width: "68px"},
            {targets: '_all', className: "align-middle"}
        ],
        language: {url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'}
    });
}

var arrUsuarios = [];
var arrNombres = [];
function CheckAccion(idCh){
    $('#divErrorAcciones').hide();
    let ch = $("#accionNo"+idCh);
    if (ch.is(':checked')) {
        arrUsuarios.push(idCh);
        arrNombres['N'+idCh] = $("#accionNo"+idCh).parent().parent().children().get(2).textContent;
    } else {
        let index = arrUsuarios.indexOf(idCh);
        if (index !== -1) {
            arrUsuarios.splice(index, 1);
            delete arrNombres['N'+idCh];
        }
    }
    if (arrUsuarios.length != 0) {
        $('#divErrorAcciones').hide();
    } else{
        $('#divErrorAcciones').show();
    }
}

function ModtrarUsuariosOut(){
    if ($('#rowUsuariosOut').is(':visible')) {
        $('#rowUsuariosOut').hide();
    } else{
        $('#rowUsuariosOut').show();
    }
}

function ConfirmarGuardarEquipo() {
    let valido = true;
    let accion = $('#accion').select2('val');
    if (accion == "") {
        $('#select2-accion-container').parent().addClass('invalid-select2');
        valido = false;
    } else{
        $('#select2-accion-container').parent().removeClass('invalid-select2');
    }
    if (!ValidarCampo('fecha_informe')) {
        valido = false;
    }
    if (arrUsuarios.length == 0) {
        $('#divErrorUsuarios').show();
        valido = false;
    } else{
        $('#divErrorUsuarios').hide();
    }
    if (valido) {
        let usuarios = [];
        arrUsuarios.forEach(element => {
            usuarios.push(arrNombres['N'+element]);
        });
        let nombres = usuarios.join(', ');
        let accion = $('#accion').select2('data')[0].text;
        $('#confirmacionMsj').html('El equipo de trabajo de la accion "'+accion+'" quedaría asi: '+nombres+', ¿es correcto?');
        $('#confirmacionBtn').attr("onclick","GuardarNuevoPlan();");
        $('#confirmacionMsj').css('font-size', '13px');
        Mostrar('confirmacionModal');
    }
}

function GuardarNuevoPlan(){
    let datos = {
        id: $('#idCreaEdita').val(),
        arrUsuarios,
        accion: $('#accion').select2('val'),
        fecha_informe: $('#fecha_informe').val()
    };
    _RQ('POST','/back/crear_actualizar_equipo_plangestion', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            Ocultar('modalNuevoPlan');
            ConsultarPlanes();
        });
    });
}

var textoPasoActual = "";
function Editar(id, tipo=1) {
    $('#idCreaEdita').val(id);
    _RQ('POST','/back/obtener_paso_plangestion', {id, tipo}, function(result) {
        if (typeof(result.id) != "undefined") {
            textoPasoActual = result.texto;
            $('#textAreaP'+tipo).summernote('code', result.texto);
        }
        $('#loading').hide();
        Mostrar('modalNext');
    });
}

function VistaPrevia(id, estado) {
    window.open('/back/previa_plangestion?id='+id, '_blank');
}

function VerDetalle(id) {
    let datos = {id};
    _RQ('GET','/back/accion_por_id', datos, function(result) {
        $('#loading').hide();
        $('#bodyTablaDetalle').html(plantillaHTML.itemsTablaDetalleAccion(result));
        Mostrar('modalDetalle');
    });
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

function ConfirmarAprobar(id, estado){
    $('#idCreaEdita').val(id);
    if (estado == 3) {
        Mostrar('modalAprobarDelegado');
    }
    if (estado == 4) {
        Mostrar('modalAprobarEnlace');
    }
    if (estado == 5) {
        Mostrar('modalAprobarCoordinador');
    }
}
