window.onload = (event) => {
    Summernote();
};
var summerBlanco = '\n                        ';

function Summernote() {
    $('.summernote').summernote({
        lang: 'es-ES',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['height', ['height']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview', 'fullscreen']]
        ],
        height: 300
    });
}

let pasoActual = 1;
function Pasos(num){
    let pasoAnterior = pasoActual;
    let titulosPasos = ['','Objetivos específicos', 'Metodología', 'Muestra y/o medio de contraste', 'Contexto de la problemática', 'Información revisada', 'Cronograma de actividades'];
    let porBarra = [0, 0, 20, 40, 60, 80, 100];
    let textoPaso = $('#textAreaP'+pasoAnterior).summernote('code');
    if (pasoAnterior != 6 && textoPaso != textoPasoActual) {
        let datos = {
            id: $('#idCreaEdita').val(),
            tipo: pasoAnterior,
            texto: textoPaso
        };
        _RQ('POST','/back/guardar_paso_plangestion', datos, function(result) {
            pasoActual = num;
            SelectPaso(num);
            $('#modalNextLabel').html(titulosPasos[num]);
            $('#progresoBar').css('width', porBarra[num]+'%');
            MostrarTextarea(num);
        });
    } else{
        pasoActual = num;
        SelectPaso(num);
        $('#modalNextLabel').html(titulosPasos[num]);
        $('#progresoBar').css('width', porBarra[num]+'%');
        MostrarTextarea(num);
    }
}

function SelectPaso(num){
    $('#btnPaso1, #btnPaso2, #btnPaso3, #btnPaso4, #btnPaso5, #btnPaso6').removeClass('btn-info').addClass('btn-light');
    for (let index = 0; index <= num; index++) {
        $('#btnPaso'+index).removeClass('btn-light');
        $('#btnPaso'+index).addClass('btn-info');
    }
}

function MostrarTextarea(num){
    $('#paso1, #paso2, #paso3, #paso4, #paso5, #paso6').hide();
    let datos = {
        id: $('#idCreaEdita').val(),
        tipo: num
    };
    if (num != 6) {
        _RQ('POST','/back/obtener_paso_plangestion', datos, function(result) {
            if (typeof(result.id) != "undefined") {
                textoPasoActual = result.texto;
                $('#textAreaP'+num).summernote('code', result.texto);
            } else{
                textoPasoActual = summerBlanco;
            }
            $('#paso'+num).show();
            $('#loading').hide();
        });
    } else{
        let datos = {
            id: $('#idCreaEdita').val()
        };
        _RQ('POST','/back/obtener_cronograma', datos, function(result) {
            actividadesCreadas = result;
            $('#paso'+num).show();
            $('#loading').hide();
        });
    }
}

function AnteriorPaso(){
    let paso = pasoActual-1;
    if (paso > 0) {
        Pasos(paso);
    }
}

function SiguientePaso(){
    let paso = pasoActual+1;
    if (paso < 7) {
        Pasos(paso);
    }
}


var selEtapa = 0;
function SeleccionarEtapa(etapa){
    $('#Mcron_etapa').hide();
    $('#etapa1').removeClass('btn-primary');
    $('#etapa1').addClass('btn-secondary');
    $('#etapa2').removeClass('btn-primary');
    $('#etapa2').addClass('btn-secondary');
    $('#etapa3').removeClass('btn-primary');
    $('#etapa3').addClass('btn-secondary');
    $('#etapa4').removeClass('btn-primary');
    $('#etapa4').addClass('btn-secondary');
    $('#etapa'+etapa).removeClass('btn-secondary');
    $('#etapa'+etapa).addClass('btn-primary');
    selEtapa = etapa;
}

function LimpiarSemanas(){
    for (let index = 1; index < 13; index++) {
        for (let index2 = 1; index2 < 5; index2++) {
            $('#semana_'+index+'-'+index2).removeClass('btn-primary');
            $('#semana_'+index+'-'+index2).addClass('btn-light');
        }
    }
}
var selSemanas = [];
function SeleccionarSemana(semana){
    $('#Mcron_semanas').hide();
    let index = selSemanas.indexOf(semana);
    if (index == -1) {
        $('#semana_'+semana).removeClass('btn-light');
        $('#semana_'+semana).addClass('btn-primary');
        selSemanas.push(semana);
    } else {
        $('#semana_'+semana).removeClass('btn-primary');
        $('#semana_'+semana).addClass('btn-light');
        selSemanas.splice(index, 1);
    }
}

var actividadesCreadas = [];
var okEtapas = [false, false, false, false];
function AgregarActividad(){
    let valido = true;
    if (!ValidarCampo('cron_actividad')) {
        valido = false;
    }
    let nombreEtapa = '';
    if (selEtapa == 0) {
        $('#Mcron_etapa').show();
        valido = false;
    } else{
        switch (selEtapa) {
            case 1: nombreEtapa = 'Etapa de Planeción'
                break;
            case 2: nombreEtapa = 'Etapa de Ejecución'
                break;
            case 3: nombreEtapa = 'Etapa de Informe'
                break;
            case 4: nombreEtapa = 'Etapa de Cierre'
                break;
        }
    }

    if (selSemanas.length == 0) {
        $('#Mcron_semanas').show();
        valido = false;
    }

    if (valido) {
        okEtapas[selEtapa-1] = true;
        let actividad = {
            'actividad': $('#cron_actividad').val(),
            'etapa': selEtapa,
            'semanas': selSemanas
        };
        actividadesCreadas.push(actividad);
        let index = actividadesCreadas.length - 1;
        let html = '<tr id="trActividad_'+index+'"><td style="text-align: left;">'+$('#cron_actividad').val()+'</td><td>'+nombreEtapa+'</td><td class="text-center"><i class="fas fa-trash-alt" onclick="EliminarActividad('+index+');" style="font-size: 18px;"></i></td></tr>';
        $('#bodyTablaActividades').append(html);
        $('#cron_actividad').val("");
        selEtapa = 0;
        SeleccionarEtapa(0);
        selSemanas = [];
        LimpiarSemanas();
    }
}

function EliminarActividad(index){
    let etapa = actividadesCreadas[index].etapa;
    okEtapas[etapa-1] = false;
    actividadesCreadas.splice(index, 1);
    $('#trActividad_'+index).remove();
}

function GuardarPaso(){
    if (pasoActual != 6) {
        let textoPaso = $('#textAreaP'+pasoActual).summernote('code');
        let datos = {
            id: $('#idCreaEdita').val(),
            tipo: pasoActual,
            texto: textoPaso
        };
        _RQ('POST','/back/guardar_paso_plangestion', datos, function(result) {
            _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
                $('#loading').hide();
            });
        });
    } else {
        let valido = true;
        let faltaEtapa = [];
        if (!okEtapas[0]) {
            faltaEtapa.push('Etapa de Planeción');
        }
        if (!okEtapas[1]) {
            faltaEtapa.push('Etapa de Ejecución');
        }
        if (!okEtapas[2]) {
            faltaEtapa.push('Etapa de Informe');
        }
        if (!okEtapas[3]) {
            faltaEtapa.push('Etapa de Cierre');
        }
        if (faltaEtapa.length > 0) {
            valido = false;
            let nombresFaltaEtapa = faltaEtapa.join(', ');
            $('#Mcronograma').html('Falta en el cronograma: '+nombresFaltaEtapa);
            $('#Mcronograma').show();
        } else{
            $('#Mcronograma').html('');
            $('#Mcronograma').hide();
        }
        if(valido){
            let datos = {
                id: $('#idCreaEdita').val(),
                cronograma: actividadesCreadas
            };
            _RQ('POST','/back/guardar_cronograma', datos, function(result) {
                _MSJ(result.tipo, (result.error != null)?result.error:result.txt, function() {
                    Ocultar('modalNext');
                    location.reload();
                });
            });
        }
    }
}
