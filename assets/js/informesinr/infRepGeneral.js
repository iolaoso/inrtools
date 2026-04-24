console.log("infRepGeneral.js Funcionando");

document.addEventListener('DOMContentLoaded', function(e) {
   document.getElementById('btnInfFormBack').addEventListener('click', function() {
        window.location.href = 'informes.php'; 
    });
});

$(document).ready(function() {
    $('#tablaInfRepGen').DataTable({
        "autoWidth": true,           // Ajusta automáticamente al contenedor
        "scrollX": false,            // Desactiva scroll horizontal (se ajusta al padre)
        "responsive": true,          // Hace la tabla responsive
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [
            {
                extend: 'excelHtml5',
                title: 'Reporte_Informes_INR',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdfHtml5',
                messageTop: 'Información de los Informes realizadas por la Intendencia Nacional de Riesgos',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                download: 'open'
            },
            {
                extend: 'print',
                messageTop: 'Información de los Informes realizadas por la Intendencia Nacional de Riesgos',
                orientation: 'landscape',
                exportOptions: { columns: ':visible' },
                customize: function(win) {
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            },
            'colvis'
        ],
        "paging": true,
        "lengthChange": false,
        "pageLength": 1,
        "ordering": true,
        "order": [[0, 'desc']],
        "columnDefs": [
            { 
                "orderable": false, 
                "targets": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
            }
        ],
        "language": {
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
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