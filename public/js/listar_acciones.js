$(document).ready(function() {
    ConsultarAcciones(2023);
});

var plantillaHTML = new PlantillaHTML();
function ConsultarAcciones(periodo){
    $('#loading').show();
    LimpiarTabla('dataTable');
    let datos = [];
    let old = false;
    if (periodo==2023) {
        datos = [
            {id:10, id_base:10, activo:1, no:"APC10", titulo:"ACCION DE PREVENCIÓN PRUEBA 10", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:12, id_base:12, activo:1, no:"APC12", titulo:"ACCION DE PREVENCIÓN PRUEBA 12", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:13, id_base:4, activo:1, no:"SEG1", titulo:"ACCION DE SEGUIMIENTO PRUEBA 1", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:14, id_base:5, activo:1, no:"SEG2", titulo:"ACCION DE SEGUIMIENTO PRUEBA 2", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:15, id_base:15, activo:1, no:"APC15", titulo:"ACCION DE PREVENCIÓN PRUEBA 15", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:16, id_base:16, activo:1, no:"APC16", titulo:"ACCION DE PREVENCIÓN PRUEBA 16", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:17, id_base:17, activo:1, no:"APC17", titulo:"ACCION DE PREVENCIÓN PRUEBA 17", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:18, id_base:18, activo:1, no:"APC18", titulo:"ACCION DE PREVENCIÓN PRUEBA 18", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
        ];
    }
    if(periodo==2022) {
        datos = [
            {id:5, id_base:5, activo:1, no:"APC5", titulo:"ACCION DE PREVENCIÓN PRUEBA 5", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
            {id:4, id_base:4, activo:1, no:"APC4", titulo:"ACCION DE PREVENCIÓN PRUEBA 4", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
        ];

        old = true;
    }
    LlenaTabla(datos, old);
}

function LlenaTabla(datosTabla, old) {
    let filas = [];
    let columns = [
        {title: "#"},
        {title: "Título"},
        {title: "Entidad(es)"},
        {title: "Delegada / Local"},
        {title: "Fecha creación"},
        {title: "Estado"},
        {title: "Acciones"}
    ];
    let columnDefs = [
        {targets: [5,6], className: "align-middle text-center", width: "70px"}
    ];
    $('#btnNuevo').show();
    if (old) {
        columns = [
            {title: "#"},
            {title: "Título"},
            {title: "Entidad(es)"},
            {title: "Delegada / Local"},
            {title: "Fecha creación"}
        ];
        $('#btnNuevo').hide();
        columnDefs = [];
    }
    datosTabla.forEach(element => {
        let columna = [];
        columna.push(element.no);
        let link = {
            xid:1
        };
        //columna.push(plantillaHTML.itemEstadoTabla(element, "acta"));
        columna.push(element.titulo);
        columna.push(element.entidades);
        columna.push(element.delegada);
        columna.push(element.fecha);
        if (!old) {
            columna.push(plantillaHTML.itemEstadoTabla(element, "acción"));
            columna.push(plantillaHTML.itemEliminarTabla(element, "acción"));
        }
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        columnDefs: columnDefs,
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
    $('#loading').hide();
} );

function Nuevo() {
    Mostrar('modalNuevaActa');
}
