console.log("empAux.js Funcionando");

$(document).ready(function () {
    
    if ($('#tablaCatastroEmpAux').length) {
        $('#tablaCatastroEmpAux').DataTable({
            "autoWidth": true, // Habilita el ajuste automático de ancho
            "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
            "buttons": [{
                            extend: 'excelHtml5',
                            title: 'Reporte_Gestiones_Simp_INR',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            extend: 'pdfHtml5',
                            messageTop: 'Reporte_Gestiones_Simp_INR',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            download: 'open'
                        },
                        {
                            extend: 'print',
                            messageTop: 'Reporte_Gestiones_Simp_INR',
                            orientation: 'landscape',
                            exportOptions: {columns: ':visible'},
                            customize: function ( win ) {
                                $(win.document.body)
                                    .css( 'font-size', '10pt' );
                                $(win.document.body).find( 'table' )
                                    .addClass( 'compact' )
                                    .css( 'font-size', 'inherit' );
                            }
                        },
                        'colvis',
            ],
            "paging": true, // Activa la paginación
            //"lengthMenu": [5, 10, 25, 50], // Opciones de número de filas por página
            "lengthChange": false, // Oculta el menú de selección de entradas
            "pageLength": 5, // Número de registros por página
            "ordering": true, // Habilita la ordenación
            "order": [[0, 'desc'],], // Ordena la primera columna (ID) en orden descendente
            "columnDefs": [
                { "orderable": false, "targets": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] }], // Deshabilita la ordenación para las demás columnas
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

    }

});