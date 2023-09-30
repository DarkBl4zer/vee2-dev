$(document).ready(function() {
    ConsultarPlanes();
});

var plantillaHTML = new PlantillaHTML();
function ConsultarPlanes(){
    $('#loading').show();
    LimpiarTabla('dataTable');
    let datos = datos = [
        {id:1, activo:1, year:"2022", acciones:"ACCION DE PREVENCIÓN PRUEBA 1, ACCION DE PREVENCIÓN PRUEBA 2", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"},
        {id:1, activo:1, year:"2023", titulo:"ACCION DE PREVENCIÓN PRUEBA 10", entidades:"ENTIDAD 1, ENTIDAD 2, ENTIDAD 3", delegada:"PD PARA EL SECTOR HABITAT", fecha:"19/09/2023"}
    ];
    LlenaTabla(datos);
}

function LlenaTabla(datosTabla) {
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
