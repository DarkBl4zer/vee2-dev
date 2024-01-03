$(document).ready(function() {
    if(!permisos.nuevo){
        $('#btnNuevo').remove();
    }
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
        let conflicto = false;
        if (element.estado > 1) {
            conflicto = true;
        }
        columna.push(plantillaHTML.itemAccionesPGTabla({
            id: element.id_accion,
            estado: element.estado,
            editar: true,
            documentos: true,
            detalle: true,
            generar_pg: true,
            conflicto: conflicto,
            dec_firmada: element.dec_firmada,
            aprobar: true,
            archivo: element.archivo_firmado,
            equipo: true
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
            {targets: [2], className: "align-middle", width: "280px"},
            {targets: [3], className: "align-middle", width: "210px"},
            {targets: targets, className: "align-middle text-center", width: "150px"},
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
    //$('td i').tooltip({template: '<div class="tooltip dtTooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'});
    $('td i').tooltip();
    $('#loading').hide();
});

var DatosTablaUsuarios = null;
function Nuevo(id) {
    $('#idCreaEdita').val(id);
    arrUsuarios = [];
    LimpiarTabla('dataTableUsuariosIn');
    LimpiarTabla('dataTableUsuariosOut');
    let datos = {id}
    _RQ('GET','/back/usuarios_plan_gestion', datos, function(result) {$('#loading').hide();
        DatosTablaUsuarios = result;
        $('#accion').html(plantillaHTML.options(result.acciones));
        Mostrar('modalNuevoPlan');
    });
}

$('#modalNuevoPlan').on('shown.bs.modal', function () {
    $('#accion').select2({
        language: "es",
        dropdownParent: $('#modalNuevoPlan')
    });
    arrUsuarios = DatosTablaUsuarios.arrUsuarios;
    arrNombres = DatosTablaUsuarios.arrNombres;
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
            checked: element.checked
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
    if ($('#idCreaEdita').val() != 0) {
        $('#accion').val($('#idCreaEdita').val()).trigger('change');
    }
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
        let nombres = `<ul><li>${usuarios.join('</li><li>')}</li></ul>`;
        let txtAccion = DatosTablaUsuarios.titulo;
        if ($('#idCreaEdita').val() == 0) {
            txtAccion = $('#accion').select2('data')[0].text;
        }
        $('#confirmacionMsj').html('<strong>Acción:</strong> '+txtAccion+'<br><br><strong>Equipo:</strong>'+nombres+'¿es correcto?');
        $('#confirmacionBtn').attr("onclick","GuardarNuevoPlan();");
        $('#confirmacionMsj').removeClass('text-center');
        $('#confirmacionMsj').css({'font-size': '13px', 'text-align': 'left'});
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
            //ConsultarPlanes();
            location.reload();
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
        Mostrar('modalMesaTrabajo');
    }
    if (estado == 5) {
        Mostrar('modalAprobarCoordinador');
    }
}

function AprobarDelegado(respuesta){
    $('#delegadoSi').removeClass('btn-primary');
    $('#delegadoSi').addClass('btn-secondary');
    $('#delegadoNo').removeClass('btn-primary');
    $('#delegadoNo').addClass('btn-secondary');
    $('#delegado'+respuesta).removeClass('btn-secondary');
    $('#delegado'+respuesta).addClass('btn-primary');
    if (respuesta == 'Si') {
        $('.apruebaBotonesD').show();
    } else {
        $('.apruebaBotonesD').hide();
    }
}

function FirmarDelegado(previa) {
    if (previa) {
        VistaPrevia($('#idCreaEdita').val(), null);
    } else {
        if (ValidarCampo('inputActaD')) {
            let datos = new FormData(document.getElementById('formActaD'));
            datos.append('id', $('#idCreaEdita').val());
            _RQ('POST','/back/firmar_plangestion_d', datos, function(result) {
                _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
                    location.reload();
                });
            }, true);
        }
    }
}

function MostrarMesaT(id_pg){
    $('#id_pg').val(id_pg);
    $('#aproboMesaSi').removeClass('btn-primary');
    $('#aproboMesaSi').addClass('btn-secondary');
    $('#aproboMesaNo').removeClass('btn-primary');
    $('#aproboMesaNo').addClass('btn-secondary');
    $('#realizoMTSi').removeClass('btn-primary');
    $('#realizoMTSi').addClass('btn-secondary');
    $('#realizoMTNo').removeClass('btn-primary');
    $('#realizoMTNo').addClass('btn-secondary');

    $('#rowArchivoMesaT').hide();
    $('#rowAprobarMesaT').hide();
    $('#rowMotivoMesaT').hide();

    $('#realizo').val('');
    $('#mesaTrabajo').val('');
    $('#aproboMesa').val('');
    $('#motivo_rechazo_mt').val('');

    $('#realizo').removeClass('is-invalid');
    $('#mesaTrabajo').removeClass('is-invalid');
    $('#aproboMesa').removeClass('is-invalid');
    $('#motivo_rechazo_mt').removeClass('is-invalid');

    Mostrar('modalMesaTrabajo');
}

function RealizoMesa(respuesta){
    $('#Mrealizo').hide();
    $('#realizoMTSi').removeClass('btn-primary');
    $('#realizoMTSi').addClass('btn-secondary');
    $('#realizoMTNo').removeClass('btn-primary');
    $('#realizoMTNo').addClass('btn-secondary');
    $('#realizoMT'+respuesta).removeClass('btn-secondary');
    $('#realizoMT'+respuesta).addClass('btn-primary');
    $('#realizo').val(respuesta);
    if (respuesta=="Si") {
        $('#rowAprobarMesaT').show();
    } else {
        $('#rowAprobarMesaT').hide();
        $('#rowArchivoMesaT').hide();
    }
}

function AprobarMesa(respuesta){
    $('#MaproboMesa').hide();
    $('#aproboMesaSi').removeClass('btn-primary');
    $('#aproboMesaSi').addClass('btn-secondary');
    $('#aproboMesaNo').removeClass('btn-primary');
    $('#aproboMesaNo').addClass('btn-secondary');
    $('#aproboMesa'+respuesta).removeClass('btn-secondary');
    $('#aproboMesa'+respuesta).addClass('btn-primary');
    $('#aproboMesa').val(respuesta);
    if (respuesta=="No") {
        $('#rowMotivoMesaT').show();
        $('#rowArchivoMesaT').hide();
    } else {
        $('#rowMotivoMesaT').hide();
        $('#rowArchivoMesaT').show();
    }
}

function GuardarMesaTrabajo(){
    let requeridos = ['realizo'];
    if ($('#realizo').val()=="Si") {
        requeridos.push('aproboMesa');
    }
    if ($('#realizo').val()=="No") {
        $('#aproboMesa').val("Si");
    }
    if ($('#aproboMesa').val()=="Si") {
        requeridos.push('inputActaE');
    }
    if ($('#aproboMesa').val()=="No") {
        requeridos.push('motivo_rechazo_mt');
    }
    let valido = true;
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        let datos = new FormData(document.getElementById('formActaE'));
        datos.append('id', $('#idCreaEdita').val());
        datos.append('realizo', $('#realizo').val());
        datos.append('aproboMesa', $('#aproboMesa').val());
        datos.append('motivo_rechazo_mt', $('#motivo_rechazo_mt').val());
        _RQ('POST','/back/firmar_plangestion_e', datos, function(result) {
            _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
                location.reload();
            });
        }, true);
    }
}

function AprobarPG(respuesta){
    $('#apruebaPGSi').removeClass('btn-primary');
    $('#apruebaPGSi').addClass('btn-secondary');
    $('#apruebaPGNo').removeClass('btn-primary');
    $('#apruebaPGNo').addClass('btn-secondary');
    $('#apruebaPG'+respuesta).removeClass('btn-secondary');
    $('#apruebaPG'+respuesta).addClass('btn-primary');
    $('#aproboPg').val(respuesta);
    if (respuesta == 'Si') {
        $('#apruebaBotonesFirma').show();
    } else {
        $('#apruebaBotonesFirma').hide();
    }
}

function FirmarPlanG(previa) {
    if (previa) {
        window.open('/back/previa_plangestion?id='+$('#idCreaEdita').val(), '_blank');
    } else {
        let requeridos = ['aproboPg'];
        if ($('#aproboPg').val()=="No") {
            requeridos.push('motivo_rechazo_c');
        }
        let valido = true;
        requeridos.forEach(item => {
            if (!ValidarCampo(item)) {
                valido = false;
            }
        });
        if (valido) {
            let datos = {
                id: $('#idCreaEdita').val(),
                aproboPg: $('#aproboPg').val()
            };
            _RQ('POST','/back/firmar_plangestion_c', datos, function(result) {
                _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
                    location.reload();
                });
            });
        }
    }
}

function VerFirmado(archivo) {
    window.open('/back/descargar_archivo/?carpeta=vee2_generados&archivo='+archivo, '_blank');
}

function DefinirFechaMaxima() {
    let fecha_final = null;
    DatosTablaUsuarios.acciones.forEach(element => {
        if (element.id == $('#accion').val()) {
            fecha_final = element.fecha_final;
        }
    });
    SetCampoFecha('fecha_informe', _HOY, fecha_final);
}

function CambiarEquipo(id) {
    $('#rowDatoAccion').hide();
    $('#fecha_informe').val(id);
    Nuevo(id);
}
