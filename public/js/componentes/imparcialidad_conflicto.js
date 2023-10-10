function TipoUsuario(tipo){
    if (tipo==1) {
        $('#dContrato').hide();
        $('#dSCargo').show();
    } else {
        $('#dSCargo').hide();
        $('#dContrato').show();
    }
}

var cuentaTabla1 = -1;
var arrTabla1 = [];
var cuentaTabla2 = -1;
var arrTabla2 = [];
var fila = '<tr id="trTabla@tipo-@id">'+
    '<td><input class="form-control" type="text" placeholder="Nombre y apellido" id="nombreTabla@tipo-@id"/></td>'+
    '<td><input class="form-control" type="text" placeholder="Cargo" id="cargoTabla@tipo-@id"/></td>'+
    '<td><input class="form-control" type="text" placeholder="Área de la entidad pública" id="areaTabla@tipo-@id"/></td>'+
    '<td><input class="form-control" type="text" placeholder="Tipo de relación" id="tipoTabla@tipo-@id"/></td>'+
    '<td class="text-center">'+
    '<button type="button" class="btn btn-sm btn-success" style="font-size: 12px; margin-right: 10px;" onclick="GuardarTabla(@tipo, @id);" id="btnGuardar@tipo-@id">Guardar</button>'+
    '<button type="button" class="btn btn-sm btn-danger" style="font-size: 12px;" onclick="EliminarTabla(@tipo, @id);">Eliminar</button>'+
    '</td>'+
    '</tr>';

function AgeragrTabla(tipo){
    let html = "";
    if (tipo == 1) {
        cuentaTabla1++;
        html = fila.replaceAll('@id', cuentaTabla1);
    } else{
        cuentaTabla2++;
        html = fila.replaceAll('@id', cuentaTabla2);
    }
    html = html.replaceAll('@tipo', tipo);
    $('#tabla'+tipo+'Body').append(html);
    $('#btnAgregarTabla'+tipo).prop('disabled', true);
}

function EliminarTabla(tipo, id){
    $('#trTabla'+tipo+'-'+id).remove();
    if (tipo == 1) {
        cuentaTabla1--;
        arrTabla1.splice(id, 1);
    } else {
        cuentaTabla2--;
        arrTabla2.splice(id, 1);
    }
    $('#btnAgregarTabla'+tipo).prop('disabled', false);
}

function GuardarTabla(tipo, id){
    let valido = true;
    let requeridos = ['nombreTabla'+tipo, 'cargoTabla'+tipo, 'areaTabla'+tipo, 'tipoTabla'+tipo];
    requeridos.forEach(item => {
        if (!ValidarCampo(item+'-'+id)) {
            valido = false;
        }
    });
    if (valido) {
        $('#errTablas').hide();
        let push = [];
        requeridos.forEach(element => {
            push.push($('#'+element+'-'+id).val());
            $('#'+element+'-'+id).prop('disabled', true);
        });
        if (tipo == 1) {
            arrTabla1[id] = push;
        } else {
            arrTabla2[id] = push;
        }
        $('#btnGuardar'+tipo+'-'+id).hide();
        $('#btnAgregarTabla'+tipo).prop('disabled', false);
    }
}

function CambioDeclara(){
    $('#declara').css('border-color', '#bbbbbb');
    $('#Mdeclara').hide();
    if ($('#declara').val() == 2) {
        $('#conTitulo, #conContenido').show();
    } else {
        $('#conTitulo, #conContenido').hide();
        if ($('#declara').val() == '') {
            $('#declara').css('border-color', '#dc3545');
            $('#Mdeclara').show();
        }
    }
}

function Firmar(){
    let cargo = $('#cargo').val();
    if (tipoUsuario==1) {
        cargo = $('#scargo').val()+'-'+$("#scargo  option:selected").text();
    }
    let datos = {
        previa: 0,
        id_dec,
        lugar_expedicion: $('#lugar_expedicion').val(),
        cargo: cargo,
        contrato: $('#contrato').val(),
        tipoUsuario,
        profesion: $('#profesion').val(),
        declara: $('#declara').val(),
        motivo: $('#motivo').val(),
        arrTabla1,
        arrTabla2
    };
    let retorno = "planesgestion/listar";
    if (id_dec.split('_')[0]=="vee") {
        retorno = "veedurias/listar";
    }
    GuardarDatos("planesgestion/conflicto_guardar", retorno, datos);
}


function ParaFirmar(){
    let valido = true;
    let requeridos = ['lugar_expedicion','profesion', 'tipoUsuario'];
    if ($('#tipoUsuario').val() == 1) {
        requeridos.push('scargo');
    }
    if ($('#tipoUsuario').val() == 2) {
        requeridos.push('cargo');
        requeridos.push('contrato');
    }
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if ($('#declara').val() == '') {
        valido = false;
        $('#declara').css('border-color', 'rgb(231 74 59)');
        $('#Mdeclara').show();
    }
    if ($('#declara').val() == 2) {
        if (arrTabla1.length == 0 && arrTabla2.length == 0) {
            valido = false;
            $('#errTablas').show();
        }
        if (!ValidarCampo('motivo')) {
            valido = false;
        }
    }
    if (valido) {
        if (baseTrabajo.firma == null) {
            $('#alertNoFirma').show();
            $('#botonesFirma').hide();
        }
        Mostrar('modalFirmar');
    }
}

function GenerarVistaPrevia() {
    let cargo = $('#cargo').val();
    if ($('#tipoUsuario').val() == 1) {
        cargo = $('#scargo').val()+'-'+$("#scargo  option:selected").text();
    }
    let datos = {
        lugar_expedicion: $('#lugar_expedicion').val(),
        cargo: cargo,
        contrato: $('#contrato').val(),
        tipoUsuario: $('#tipoUsuario').val(),
        profesion: $('#profesion').val(),
        declara: $('#declara').val(),
        motivo: $('#motivo').val(),
        arrTabla1,
        arrTabla2
    };
    _RQ('POST','/back/previa_imparcialidad', datos, function(result) {
        console.log(result);
    });
}
