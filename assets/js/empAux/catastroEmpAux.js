console.log("catastroEmpAux fucnionando");

$(document).ready(function() {
    $('#tablaCatastroEmpAux').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Catastro_EmpAux_Simplificado',
                        exportOptions: {columns: ':visible:not(.not-export)'}
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: 'Catastro_EmpAux_Simplificado',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open',
                        exportOptions: {columns: ':visible:not(.not-export)'}
                    },
                    {
                        extend: 'print',
                        messageTop: 'Catastro_EmpAux_Simplificado',
                        orientation: 'landscape',
                        exportOptions: {columns: ':visible'},
                        customize: function ( win ) {
                            $(win.document.body)
                                .css( 'font-size', '10pt' );
                            $(win.document.body).find( 'table' )
                                .addClass( 'compact' )
                                .css( 'font-size', 'inherit' );
                        },
                        exportOptions: {columns: ':visible:not(.not-export)'}
                    },
                    'colvis',
        ],
        "paging": true, // Activa la paginación
        //"lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
        "lengthChange": false, // Oculta el menú de selección de entradas
        "pageLength": 5, // Número de registros por página
        "ordering": false, // Habilita la ordenación
        //"order": [[0, 'desc'],], // Ordena la primera columna en orden descendente
        "columnDefs": [
            { "orderable": false, "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }], // Deshabilita la ordenación para las demás columnas
        "language": {
            //"lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
});