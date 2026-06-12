console.log("empAuxTables.js Funcionando");
/**
 * =====================================================
 * CONFIGURACIÓN BASE DATATABLES
 * =====================================================
 */
const dtConfigBase = {
    autoWidth: true,
    paging: true,
    lengthChange: false,
    pageLength: 5,
    dom: '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
    language: {
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay registros disponibles",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "Buscar:",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
};

/**
 * =====================================================
 * FUNCIÓN GENÉRICA PARA CREAR DATATABLES
 * =====================================================
 */
function crearDataTable(
    idTabla,
    tituloExcel,
    columnasTexto = [],
    opcionesExtra = {}
) {

    return $(idTabla).DataTable({

        ...dtConfigBase,

        ...opcionesExtra,

        buttons: [
            {
                extend: 'excelHtml5',
                title: tituloExcel,
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column) {

                            if (columnasTexto.includes(column)) {
                                return "'" + data;
                            }

                            return data;
                        }
                    }
                }
            }
        ]
    });
}

/**
 * =====================================================
 * INICIALIZACIÓN DE TABLAS
 * =====================================================
 */
$(document).ready(function () {

    const opcionesCatastro = {
    responsive: true
    };

    const opcionesCoop = {
        responsive: true
    };

    const opcionesFormulario = {
        responsive: true,
        order: [[1, 'desc']]
    };

     // CATÁSTRO EMPRESAS AUXILIARES
    crearDataTable(
    '#tablaCatastroEmpAux',
    'CatastroEmpAux',
    [],
    opcionesCatastro
    );

    // COOPERATIVAS - EMPRESAS AUXILIARES
     
     /*
     * Columnas exportadas como texto:
     * 0 = RUC_COOP
     * 3 = RUC_EMPRESA
     * 4 = RUC_EMP_CATASTRO
     */
    
    crearDataTable(
        '#tablaCoopEmpAux',
        'CoopEmpAux',
        [0, 3, 4],
        opcionesCoop
    );

    // FORMULARIO EMPRESAS AUXILIARES
    crearDataTable(
        '#tablaFormEmpAux',
        'FormularioEmpAux',
        [],
        opcionesFormulario
    );

});