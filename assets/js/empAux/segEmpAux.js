console.log("segEmpAux.js funcionando")

// convertir la tabla a datatable 
$(document).ready(function() {
    $('#tablaSegEmpAux').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Seguimeinto_EmpAux',
                        exportOptions: {columns: ':visible'},
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: 'Seguimeinto_EmpAux',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open'
                    },
                    {
                        extend: 'print',
                        messageTop: 'Seguimeinto_EmpAux',
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

    /* tablaBitacoraEnvio */
    $('#tablaBitacoraEnvio').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Bitacora_Envio_EmpAux',
                        exportOptions: {columns: ':visible'},
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: 'Bitacora_Envio_EmpAux',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open'
                    },
                    {
                        extend: 'print',
                        messageTop: 'Bitacora_Envio_EmpAux',
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
        "pageLength": 15, // Número de registros por página
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

    /* tablaCatastroEmpAux */
    $('#tablaCatastroEmpAux').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Catastro_EmpAux',
                        exportOptions: {columns: ':visible'},
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: 'Catastro_EmpAux',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        download: 'open'
                    },
                    {
                        extend: 'print',
                        messageTop: 'Catastro_EmpAux',
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
        "pageLength": 10, // Número de registros por página
        "ordering": true, // Habilita la ordenación
        //"order": [[0, 'desc'],], // Ordena la primera columna en orden descendente
        "columnDefs": [
            { "orderable":true}], // HABILITA LA ORDENACION 
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

// 
document.getElementById('seguimientoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const idSeguimiento = document.getElementById('mAsIdSeguimiento').value;
    const data = {
        idSeguimiento: idSeguimiento,
        idEntidad: document.getElementById('mAsIdEntidad').value,
        ruc: document.getElementById('mAsRucCatastro').value,
        periodo: document.getElementById('mAsPeriodo').value,
        fechaCorte: document.getElementById('mAsFechaCorte').value,
        idEstadoSeguimiento: document.getElementById('mAsIdEstadoSeguimiento').value, 
        estadoSeguimiento: document.getElementById('mAsEstadoSeguimiento').value,
        oficioSeguimiento: document.getElementById('mAsOficioSeguimiento').value,
        fechaOfSeguimiento: document.getElementById('mAsFechaOfSeguimiento').value,
        idEstadoCumplimiento: document.getElementById('mAsIdEstadoCumplimiento').value, 
        estadoCumplimiento: document.getElementById('mAsEstadoCumplimiento').value,
        motivoIncumplimiento: document.getElementById('mAsMotivoIncumplimiento').value,
        indRegularizado: document.getElementById('mAsIndRegularizado').checked ? 1 : 0,
        observacionDns: document.getElementById('mAsObservacionDns').value
    };

    var url = baseurl + '/backend/empAux/guardarSeguimiento.php'; 
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const esEdicion = idSeguimiento && idSeguimiento !== '';
            Swal.fire({
                icon: 'success',
                title: esEdicion
                    ? 'Seguimiento actualizado'
                    : 'Seguimiento guardado',
                text: esEdicion
                    ? 'El seguimiento fue actualizado correctamente.'
                    : 'El seguimiento fue registrado correctamente.',
                confirmButtonColor: '#198754'
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('agregarSeguimiento')
                );
                if (modal) {
                    modal.hide();
                }
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'No fue posible guardar el seguimiento.',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'Ocurrió un error al guardar el seguimiento.',
            confirmButtonColor: '#dc3545'
        });
    });
});

document.getElementById('btnNuevoSeguimiento').addEventListener('click', () => {
    document.getElementById('mAsIdSeguimiento').value           = '';
    document.getElementById('mAsEstadoSeguimiento').value       = '';
    document.getElementById('mAsOficioSeguimiento').value       = '';
    document.getElementById('mAsFechaOfSeguimiento').value      = '';
    document.getElementById('mAsEstadoCumplimiento').value      = '';
    document.getElementById('mAsMotivoIncumplimiento').value    = '';
    document.getElementById('mAsIndRegularizado').checked       = false;
    document.getElementById('mAsObservacionDns').value          = '';
    document.getElementById('tituloSeguimiento').textContent    = 'Registro de Seguimiento';
    document.getElementById('btnGuardarSeguimiento').innerHTML  = '<i class="fas fa-save"></i> Guardar';
});

/* document.getElementById('mAsSeguimientoExistente').addEventListener('change', function () {

    const idSeguimiento = this.value;

    if (!idSeguimiento) {
        return;
    }

    fetch(`${baseurl}/backend/empAux/empAuxList.php?idSeguimiento=${idSeguimiento}`)
    .then(response => response.json())
    .then(data => {
        document.getElementById('mAsIdSeguimiento').value =
            data.ID_SEGUIMIENTO || '';
        document.getElementById('mAsEstadoSeguimiento').value =
            data.ID_ESTADO_SEGUIMIENTO || '';
        document.getElementById('mAsOficioSeguimiento').value =
            data.OFICIO_SEGUIMIENTO || '';
        document.getElementById('mAsFechaOfSeguimiento').value =
            data.FECHA_OF_SEGUIMIENTO || '';
        document.getElementById('mAsEstadoCumplimiento').value =
            data.ID_ESTADO_CUMPLIMIENTO || '';
        document.getElementById('mAsMotivoIncumplimiento').value =
            data.ID_MOTIVO_INCUMPLIMIENTO || '';
        document.getElementById('mAsIndRegularizado').checked =
            (data.IND_REGULARIZADO == 1);
        document.getElementById('mAsObservacionDns').value =
            data.OBSERVACION_DNS || '';
        document.getElementById('tituloSeguimiento').textContent =
            'Edición de Seguimiento';
        document.getElementById('btnGuardarSeguimiento').innerHTML =
            '<i class="fas fa-save"></i> Actualizar';

    })
    .catch(error => {
        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No fue posible cargar el seguimiento.',
            confirmButtonColor: '#dc3545'
        });
    });

}); */

//PARA VALIDAR Y GUARDAR EL FORM DEL PROCESO DNR
document.getElementById('procesoDNRForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        idEntidad: document.getElementById('mApIdEntidad').value,
        ruc: document.getElementById('mApRucCatastro').value,
        fecRes: document.getElementById('mApFechaRes').value,
        periodo: document.getElementById('mApPeriodo').value,
        fechaCorte: document.getElementById('mApFechaCorte').value,
        OfComunicacionRes: document.getElementById('mApOfComunicacionRes').value,
        fecOfComRes: document.getElementById('mApFecOfComunicacionRes').value,
        fecRegularizacion: document.getElementById('mApFecRegularizacion').value,
        fecProrroga: document.getElementById('mApFecProrroga').value,
        obsDNR: document.getElementById('mApObservacionDNR').value
    };

    const url = baseurl + '/backend/empAux/guardarProcesoDNR.php';
    console.log(url);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            let mensaje =
                result.accion === 'update'
                    ? 'El proceso DNR fue actualizado correctamente.'
                    : 'El proceso DNR fue registrado correctamente.';
            Swal.fire({
                icon: 'success',
                title: 'Proceso DNR guardado',
                text: mensaje,
                confirmButtonColor: '#198754'
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('agregarProcesoDNR')
                );
                if (modal) {
                    modal.hide();
                }
                location.reload();
                // o tabla.ajax.reload(null, false);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'No fue posible guardar el Proceso DNR.',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'Ocurrió un error al guardar el Proceso DNR.',
            confirmButtonColor: '#dc3545'
        });
    });
});
