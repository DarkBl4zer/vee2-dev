$(document).ready(function() {
    ConsultarActas();
});

var plantillaHTML = new PlantillaHTML();
function ConsultarActas(){
    $('#loading').show();
    LimpiarTabla('dataTable');
    let datos = [
        {id:1, nombre:"ACTA INICIAL DE TEMAS", activo:1, fecha:"19/09/2023"},
        {id:2, nombre:"ACTA DE PRUEBA 2", activo:1, fecha:"19/09/2023"},
        {id:3, nombre:"ACTA DE PRUEBA 3", activo:1, fecha:"19/09/2023"},
        {id:4, nombre:"ACTA DE PRUEBA 4", activo:1, fecha:"19/09/2023"},
        {id:5, nombre:"ACTA DE PRUEBA 5", activo:1, fecha:"19/09/2023"},
        {id:6, nombre:"ACTA DE PRUEBA 6", activo:1, fecha:"19/09/2023"},
        {id:7, nombre:"ACTA DE PRUEBA 7", activo:1, fecha:"19/09/2023"},
        {id:8, nombre:"ACTA DE PRUEBA 8", activo:1, fecha:"19/09/2023"},
    ];
    LlenaTabla(datos);
}

function LlenaTabla(datosTabla) {
    let filas = [];
    let columns = [
        {title: "Nombre"},
        {title: "Fecha"},
        {title: "Estado"},
        {title: "Acciones"}
    ];
    datosTabla.forEach(element => {
        let columna = [];
        columna.push(element.nombre);
        columna.push(element.fecha);
        columna.push(plantillaHTML.itemEstadoTabla(element, "acta"));
        columna.push(plantillaHTML.itemEliminarTabla(element, "acta"));
        filas.push(columna);
    });
    dataTable = $('#dataTable').DataTable({
        paging: true,
        info: false,
        columns: columns,
        data: filas,
        columnDefs: [
            {targets: [2,3], className: "align-middle text-center", width: "70px"}
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
    $('#btnNuevo').show();
    $('#loading').hide();
} );

function Nuevo() {
    Mostrar('modalNuevaActa');
}
