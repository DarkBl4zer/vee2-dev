$(document).ready(function(){
    ConsultarNotificaciones();
});

function ConsultarNotificaciones() {
    let datos = {todo:false};
    _RQ('GET','/back/notificaciones', datos, function(result) {
        let plantillaHTML = new PlantillaHTML();
        let html = "";
        if (result.length == 0) {
            $("#contNoti").html(0);
            $("#contNoti").hide();
        }
        if (result.length > 0 && result.length < 10) {
            $("#contNoti").html(result.length);
            $("#contNoti").show();
        }
        if (result.length > 9) {
            $("#contNoti").html("9+");
            $("#contNoti").show();
        }
        result.forEach(element => {
            html += plantillaHTML.itemNotificacion(element);
        });
        $("#itemsNotificacion").html(html);
    });
}

//Permite hacer consumo de los servicios de backend de la aplicacion
function _RQ(tipo, endpoint, datos, back, file = false){
    let data = (tipo=='GET')?datos:JSON.stringify(datos);
    let dataType = "json";
    let contentType = "application/json; charset=utf-8";
    let processData = true;
    if (file) {
        data = datos;
        dataType = false;
        contentType = false;
        processData = false;
    }
    $.ajax({
        url: endpoint,
        type: tipo,
        data: data,
        contentType: contentType,
        dataType: dataType,
        cache: false,
        processData: processData,
        beforeSend: function (xhr) {
            $('#loading').show();
            //xhr.setRequestHeader("Token", sessionStorage.getItem("token"))
        }
    }).done(back).fail(function (XMLHttpRequest) {
        $('#loading').hide();
        ServicioError(XMLHttpRequest.responseText);
    });
}

//Permite formaterar el error
function ServicioError(response){
    let objResponse = JSON.parse(response);
    console.error(objResponse);
    _MSJ('error','¡Error!, detalle en consola.');
}

//Permite generar los mensajes Noty
function _MSJ(tipo, txt, back=false){
    new Noty({
        theme: 'mint',
        layout: 'topRight',
        type: tipo,
        timeout: 2000,
        text: txt,
        callbacks:{afterClose: back}
    }).show();
}

//Cambia el entorno de trabajo delegada/rol
function Trabajo(id){
    if (baseTrabajo.id_perfil != id) {
        $("#Tperfil"+baseTrabajo.id_perfil).hide();
        $("#Tperfil"+id).show();
        baseTrabajo.id_perfil = id;
        let datos = {id};
        _RQ('POST','/back/trabajo', datos, function(result) {$('#loading').hide();
            _MSJ(result.tipo, result.txt, function() {
                location.reload();
            });
        });
    }
}

function Notificacion(id, url){
    $("#noti"+id).attr("style","display:none !important");
    let datos = {id, estado:false};
    _RQ('POST','/back/notificacion_vista', datos, function(result) {$('#loading').hide();
        _MSJ(result.tipo, result.txt, function() {
            window.location.href = url;
        });
    });
}

function Mostrar(modal){
    $('#'+modal).modal({backdrop: 'static', keyboard: false});
}

function Ocultar(modal){
    $('#'+modal).modal('hide');
}

function ValidarCampo(id){
    if ($('#'+id).val() == "" || $('#'+id).val() == null) {
        SetInvalid(id);
        return false;
    } else {
        RemoveInvalid(id);
        return true;
    }
}

function SetInvalid(id){
    $('#'+id).addClass('is-invalid');
    $("[aria-labelledby=select2-"+id+"-container]").addClass('invalid-select2');
    if ($('#'+id).attr('type')=="file") {
        $('#'+id).addClass('invalid-select2');
    }
    $('#M'+id).show();
    return;
}

function RemoveInvalid(id){
    $('#'+id).removeClass('is-invalid');
    $("[aria-labelledby=select2-"+id+"-container]").removeClass('invalid-select2');
    if ($('#'+id).attr('type')=="file") {
        $('#'+id).removeClass('invalid-select2');
    }
    $('#M'+id).hide();
    return;
}

function LimpiarRequerido(requeridos){
    requeridos.forEach(item => {
        $('#'+item).removeClass('is-invalid');
        $("[aria-labelledby=select2-"+item+"-container]").removeClass('invalid-select2');
        if ($('#'+item).attr('type')=="file") {
            $('#'+item).removeClass('invalid-select2');
        }
        $('#M'+item).hide();
    });
}

function FiltrarCaracteres(id, tipo){
    let valor = $('#'+id).val();
    if (tipo == 'valorItem') {
        $('#'+id).val(valor.replace(/\W/g, '').toUpperCase());
    }
    if (tipo == 'itemLista') {
        $('#'+id).val(valor.replace(/[^\wÀ-ú0-9 -]/g, '').toUpperCase());
    }
    ValidarCampo(id);
}

function FiltrarCaracteresOLD(idx, tipo, cont = false) {
    $('#'+idx).removeClass('is-invalid');
    let valor = $('#'+idx).val();
    let out = '';
    let filtro = '';
    if (tipo == 'numeros') {
        filtro = '1234567890';
    }
    if (tipo == 'contrato') {
        filtro = '1234567890-';
    }
    if (tipo == 'textos') {
        filtro = 'abcdefghijklmnñopqrstuvwxyzáéíóúüABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚÜ-()_,;.?¿/%$#:@1234567890" ';
    }
    if (tipo == 'listas') {
        filtro = 'abcdefghijklmnñopqrstuvwxyzáéíóúüABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚÜ1234567890 ';
    }
    if (tipo == 'mayusculas') {
        filtro = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚÜ ';
    }
    for (let i = 0; i < valor.length; i++){
        if (valor.charAt(i) == "\n") {
            out += valor.charAt(i);
        }
        if (filtro.indexOf(valor.charAt(i)) != -1){
            out += valor.charAt(i);
        }
    }
    if (cont) {
        let cuenta = cont - out.length;
        $('#cont_' + idx).html('<i class="fas fa-align-left"></i> ' + cuenta);
    }
    return out;
}

function DisableI(id, tipo, click=''){
    (tipo)?$('#'+id).css('cursor', 'not-allowed'):$('#'+id).css('cursor', 'pointer');
    $('#'+id).attr('onclick', click);
}

function SetCampoFecha(idx, min = false, max = false){
    let calendario = new Object();
    calendario.lang = 'es';
    calendario.format = "d/m/Y";
    calendario.timepickerScrollbar = false;
    calendario.timepicker = false;
    calendario.closeOnDateSelect = true;
    if (min) {calendario.minDate = min;}
    if (max) {calendario.maxDate = max;}
    calendario.onGenerate = function( ct ){
        jQuery(this).find('.xdsoft_date.xdsoft_weekend').addClass('xdsoft_disabled');
    };
	calendario.disabledDates = festivos;
	calendario.formatDate ='d.m.Y';
    jQuery.datetimepicker.setLocale('es');
    $('#'+idx).datetimepicker(calendario);
}
