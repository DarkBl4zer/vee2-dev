$(document).ready(function() {
    bsCustomFileInput.init();
});

var plantillaHTML = new PlantillaHTML();
var datosTabla, dataTable;
datosTabla = dataTable = null;
function ConsultarTemas(tipo){
    $('#loading').show();
    DisableI('btnNuevo', false, 'Nuevo();');
    $('#btnNuevo').hide();
    LimpiarTabla('dataTable');
    if (tipo != "") {
        let datos = {tipo};
        _RQ('GET','/back/temas_por_tipo', datos, function(result) {$('#loading').hide();
            console.log(result);
            /*datosTabla = result;
            LlenaTabla(result);*/



            if (tipo==1) {
                datosTabla = [
                    {id:1, nombre:"TEMA DE PRUEBA 1", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:2, nombre:"TEMA DE PRUEBA 2", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:3, nombre:"TEMA DE PRUEBA 3", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:4, nombre:"TEMA DE PRUEBA 4", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:5, nombre:"TEMA DE PRUEBA 5", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:6, nombre:"TEMA DE PRUEBA 6", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:7, nombre:"TEMA DE PRUEBA 7", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                    {id:8, nombre:"TEMA DE PRUEBA 8", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:null},
                ];
            } else {
                datosTabla = [
                    {id:9, nombre:"TEMA DE PRUEBA 9", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 1"},
                    {id:10, nombre:"TEMA DE PRUEBA 10", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 2"},
                    {id:11, nombre:"TEMA DE PRUEBA 11", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 3"},
                    {id:12, nombre:"TEMA DE PRUEBA 12", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 4"},
                    {id:13, nombre:"TEMA DE PRUEBA 13", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 5"},
                    {id:14, nombre:"TEMA DE PRUEBA 14", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 6"},
                    {id:15, nombre:"TEMA DE PRUEBA 15", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 7"},
                    {id:16, nombre:"TEMA DE PRUEBA 16", activo:1, id_acta:1, nombre_acta:"ACTA INICIAL DE TEMAS", id_padre:"TEMA DE PRUEBA 8"},
                ];
            }


        });
    }
}

function LlenaTabla(datos) {
    let filas = [];
    let padre = false;
    let columns = [
        {title: "Nombre"},
        {title: "Acta"},
        {title: "Estado"},
        {title: "Acciones"}
    ];
    let largo = 3;
    if(datos[0].id_padre != null){
        padre = true;
        columns = [
            {title: "Nombre"},
            {title: "Acta"},
            {title: "Tema principal"},
            {title: "Estado"},
            {title: "Acciones"}
        ];
        largo = 4;
    }
    datos.forEach(element => {
        let columna = [];
        //columna.push(element.nombre);
        columna.push(plantillaHTML.itemInputText(element, 2));
        let htmlActa = `<a href="/files/BLANCO.pdf" target="_blank" class="dataFila${element.id}">${element.nombre_acta}</a>`;
        let selectActa = $('#plantillaSelectActa').html();
        htmlActa += selectActa.replaceAll('@valor', '').replaceAll('@id', element.id);
        columna.push(htmlActa);
        if(padre){
            columna.push(element.id_padre);
        }
        columna.push(plantillaHTML.itemEstadoTabla(element, "tema"));
        columna.push(plantillaHTML.itemAccionesTabla(element));
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        columnDefs: [
            {targets: [largo-1,largo], className: "align-middle text-center", width: "70px"}
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
        $('#tipoValor'+element.id).val(element.tipo_valor);
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
    Ocultar('confirmacionModal');
    _MSJ("success", "¡Registrado!", function() {
        //location.reload();
    });
}

function Nuevo(){
    DisableI('btnNuevo', true);
    let element = {
        id: 0,
        nombre: null,
        id_acta: null,
        id_padre: null,
        activo: 1
    };
    let columna = [];
    columna.push(plantillaHTML.itemInputText(element, 2));
    let selectActa = $('#plantillaSelectActa').html();
    columna.push(selectActa.replaceAll('@valor', '').replaceAll('@id', element.id));
    if($('#tipoTema').val()==2){
        columna.push(element.id_padre);
    }
    columna.push('');
    columna.push(plantillaHTML.itemAccionesTabla(element));
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
    let requeridos = ['textT1Id'+id, 'selectActa'+id];
    requeridos.forEach(item => {
        if (!ValidarCampo(item)) {
            valido = false;
        }
    });
    if (valido) {
        $('#confirmacionMsj').html('¿Seguro desea guardar el tema '+$("#tipoTema option:selected").text()+'?');
        $('#confirmacionBtn').attr("onclick","GuardarTema("+id+");");
        Mostrar('confirmacionModal');
    }
}

function GuardarTema(id){
    $('#loading').show();
    _MSJ("success", "¡Registrado!", function() {
        location.reload();
    });
    /*let datos = {
        id,
        nombre: $('#textT1Id'+id).val(),
        acta: $('#selectActa'+id).val()
    };
    _RQ('POST','/back/guardar_frima', datos, function(result) {$('#loading').hide();
        _MSJ(result.tipo, result.txt, function() {
            location.reload();
        });
    }, true);*/
}

function Editar(id){
    if ($('.dataFila'+id).is(":visible")) {
        $('.dataFila'+id).hide();
        $('.inputFila'+id).show();
        $('#selectActa'+id).val(id);
    } else {
        $('.inputFila'+id).hide();
        $('.dataFila'+id).show();
    }
}

function CargaMasiva(){
    if (ValidarCampo('inputCargaMasiva')) {
        $('#loading').show();
        _MSJ("success", "¡Registrado!", function() {
            location.reload();
        });
    }
}
