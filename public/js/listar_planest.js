$(document).ready(function() {
    if(!permisos.nuevo){
        $('#btnNuevo').remove();
    }
    bsCustomFileInput.init();
    ConsultarPlanes();
});

var thisYear = new Date().getFullYear();

function ConsultarPlanes(){
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
        _RQ('GET','/back/planest_por_periodo', datos, function(result) {$('#loading').hide();
            LlenaTabla(result);
        });
    }
}

var maxVersion = 0;
function LlenaTabla(datos) {
    let filas = [];
    let columns = [
        {title: "#"},
        {title: "Acción(es)"},
        {title: "Fechas"},
        {title: "Acciones"}
    ];
    let targets = [3];
    if(!permisos.nuevo){
        columns = [
            {title: "#"},
            {title: "Acción(es)"},
            {title: "Delegada / Local"},
            {title: "Fechas"},
            {title: "Acciones"}
        ];
        targets = [4];
    }
    datos.forEach(element => {
        let columna = [];
        columna.push(element.year+'-'+element.version+'<br>'+element.nombreestado);
        columna.push(element.str_acciones);
        if (!permisos.nuevo) {
            columna.push(element.delegada.nombre);
        }
        columna.push(element.fechas);
        let editar = false;
        if(ppEditar && permisos.editar && permisos.editar.includes(parseInt(element.estado))){
            editar = true;
        }
        if (ppEditar) {
            columna.push(plantillaHTML.itemAccionesTabla({
                id: element.id,
                estado: element.estado,
                archivo: element.archivo_firmado,
                editar: editar,
                generar: true,
                aprobar: true
            }));
        } else{
            columna.push('');
        }
        filas.push(columna);
        maxVersion = element.version;
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
    $('td i').tooltip({template: '<div class="tooltip dtTooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'});
    if (ppEditar) {
        $('#btnNuevo').show();
    }
    $('#loading').hide();
});

var DatosTablaAcciones = [];
function Nuevo(id) {
    $('#idCreaEdita').val(id);
    LimpiarTabla('dataTableAcciones');
    let datos = {periodo: thisYear, id};
    _RQ('GET','/back/acciones_por_periodo_pt', datos, function(result) {$('#loading').hide();
        DatosTablaAcciones = result;
        Mostrar('modalNuevoPlan');
    });
}

$('#modalNuevoPlan').on('shown.bs.modal', function () {
    arrAcciones = DatosTablaAcciones.chks;
    LlenaTablaAcciones(DatosTablaAcciones.datos);
});

function LlenaTablaAcciones(datos) {
    let filas = [];
    let columns = [
        {title: ""},
        {title: "#"},
        {title: "Título"},
        {title: "Entidad(es)"}
    ];
    datos.forEach(element => {
        if (element.estado == 2 || element.estado == 4 || element.estado == 7) {
            let columna = [];
            columna.push(plantillaHTML.itemCheckbox({
                id: element.id,
                checked: element.checked
            }));
            columna.push(element.numero);
            columna.push(element.titulo.substring(0, 150)+' (...)');
            columna.push(element.entidades.string);
            filas.push(columna);
        }
    });
    dataTableAcciones = $('#dataTableAcciones').DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        lengthMenu: [
            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100]
        ],
        columnDefs: [
            {targets: [0, 1], width: "20px"},
            {targets: '_all', className: "align-middle"}
        ],
        language: {url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'}
    });
}

var arrAcciones = [];
function CheckAccion(idCh){
    $('#divErrorAcciones').hide();
    let ch = $("#accionNo"+idCh);
    if (ch.is(':checked')) {
        arrAcciones.push(idCh);
    } else {
        let index = arrAcciones.indexOf(idCh);
        if (index !== -1) {
            arrAcciones.splice(index, 1);
        }
    }
    if (arrAcciones.length != 0) {
        $('#divErrorAcciones').hide();
    } else{
        $('#divErrorAcciones').show();
    }
}

function ConfirmarGuardarNuevoPlan() {
    if (arrAcciones.length > 0) {
        let acciones = arrAcciones.join(', ');
        let txt = 'crear el plan de trabajo';
        let id = $('#idCreaEdita').val();
        if (id != 0) {
            txt = `actualizar el plan de trabajo ${thisYear}-${id}`;
        }
        $('#confirmacionMsj').html('¿Seguro desea '+txt+' con las acciones '+acciones+'?');
        $('#confirmacionBtn').attr("onclick","GuardarNuevoPlan();");
        Mostrar('confirmacionModal');
    } else{
        $('#divErrorAcciones').show();
    }
}

function GuardarNuevoPlan(){
    let datos = {
        arrAcciones,
        id: $('#idCreaEdita').val(),
        version: maxVersion+1
    };
    _RQ('POST','/back/crear_actualizar_plantrabajo', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            location.reload();
        });
    });
}

function Editar(id) {
    Nuevo(id);
}

function GenerarFirmar(id) {
    $('#idCreaEdita').val(id);
    if (!baseTrabajo.firma) {
        $('#alertNoFirma').show();
        $('#botonesFirma').hide();
    }
    Mostrar('modalFirmar');
}

function FirmarPlanT(previa) {
    if (previa) {
        window.open('/back/previa_plantrabajo?id='+$('#idCreaEdita').val(), '_blank');
    } else {
        if (ValidarCampo('inputActa')) {
            let datos = new FormData(document.getElementById('formActa'));
            datos.append('id', $('#idCreaEdita').val());
            _RQ('POST','/back/firmar_plantrabajo_d', datos, function(result) {
                _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
                    location.reload();
                });
            }, true);
        }
    }
}

function VerFirmado(archivo) {
    window.open('/back/descargar_archivo/?carpeta=vee2_generados&archivo='+archivo, '_blank');
}

function ConfirmarAprobar(id, estado){
    $('#idCreaEdita').val(id);
    Mostrar('modalAprobar');
}

function AprobarPT(respuesta){
    $('#apruebaPTSi').removeClass('btn-primary');
    $('#apruebaPTSi').addClass('btn-secondary');
    $('#apruebaPTNo').removeClass('btn-primary');
    $('#apruebaPTNo').addClass('btn-secondary');
    $('#apruebaPT'+respuesta).removeClass('btn-secondary');
    $('#apruebaPT'+respuesta).addClass('btn-primary');
    if (respuesta == 'Si') {
        $('#apruebaBotonesFirma').show();
    } else {
        $('#apruebaBotonesFirma').hide();
    }
}

function FirmarCoorPlanT() {
    let datos = {
        id: $('#idCreaEdita').val()
    };
    _RQ('POST','/back/firmar_plantrabajo_c', datos, function(result) {
        _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
            location.reload();
        });
    });
}
