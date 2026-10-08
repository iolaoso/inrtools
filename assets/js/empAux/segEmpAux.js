console.log("segEmpAux.js funcionando")


// Función para cargar datos en el modal
function cDatSegEmpAux(button) {
    const idEntidad = button.getAttribute('data-id');
    const url = baseurl + "/backend/empAux/empAuxList.php";
    console.log(idEntidad);
    fetch(`${url}?idEntidad=${idEntidad}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(selectedData => {
            if (!selectedData) return;
            console.log(selectedData);
            // DATOS GENERALES
            document.getElementById("mNumeroPeriodo").value     = selectedData.bitacora.NUM_PERIODO || '';
            document.getElementById("mIdEntidad").value         = selectedData.bitacora.ID_ENTIDAD || '';
            document.getElementById("mRucCatastro").value       = selectedData.bitacora.RUC_CATASTRO || '';
            document.getElementById("mRazonSocial").value       = selectedData.bitacora.RAZON_SOCIAL || '';
            // SERVICIOS PRESTADOS
            document.getElementById("mSoftwareFinanciero").checked  = selectedData.bitacora.SOFTWARE_FINANCIERO_Y_COMPUTACIONAL == 1;
            document.getElementById("mTransaccionalesPago").checked = selectedData.bitacora.TRANSACCIONALES_Y_DE_PAGO == 1;
            document.getElementById("mTransporteValores").checked   = selectedData.bitacora.TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES == 1;
            document.getElementById("mRedCajeros").checked          = selectedData.bitacora.RED_Y_CAJEROS_AUTOMATICOS == 1;
            document.getElementById("mCobranzas").checked           = selectedData.bitacora.COBRANZAS == 1;
            document.getElementById("mServiciosContables").checked  = selectedData.bitacora.SERVICIOS_CONTABLES == 1;
            document.getElementById("mGeneradorasCartera").checked  = selectedData.bitacora.GENERADORAS_DE_CARTERA == 1;
            document.getElementById("mOperadorasTarjetas").checked  = selectedData.bitacora.ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS == 1;
            document.getElementById("mGiroInmobiliario").checked    = selectedData.bitacora.GIRO_INMOBILIARIO == 1;
            // CALIFICACIÓN
            document.getElementById("mOfComunicacionResolucion").value  = selectedData.bitacora.OF_COMUNICACION_RESOLUCION || '';
            document.getElementById("mFechaOficioComRes").value         = selectedData.bitacora.FECHA_OFICIO_COM_RES || '';
            document.getElementById("mNumResolucionCalificacion").value = selectedData.bitacora.NUM_RESOLUCION_CALIFICACION || '';
            document.getElementById("mFechaResolucion").value           = selectedData.bitacora.FECHA_RESOLUCION || '';
            document.getElementById("mFechaVencimientoRes").value       = selectedData.bitacora.FECHA_VENCIMIENTO_RES || '';
            // ENTREGA
            document.getElementById("mEstado").value                = selectedData.bitacora.ESTADO || '';
            document.getElementById("mMedioEnvio").value            = selectedData.bitacora.MEDIO_ENVIO || '';
            document.getElementById("mCorreo").value                = selectedData.bitacora.CORREO || '';
            document.getElementById("mFechaRegistro").value         = selectedData.bitacora.FECHA_REGISTRO || '';
            document.getElementById("mFechaCorte").value            = selectedData.bitacora.FECHA_CORTE || '';
            document.getElementById("mFechaLineaBase").value        = selectedData.bitacora.FECHA_LINEA_BASE || '';
            // OBSERVACIONES DNR
            document.getElementById("mRegularizaciones").value  = selectedData.bitacora.REGULARIZACIONES|| '';
            document.getElementById("mProrrogas").value         = selectedData.bitacora.PRORROGAS || '';
            document.getElementById("mObservacionesDNR").value  = selectedData.bitacora.OBSERVACIONES_DNR || '';
            // TABLA OBSERVACIONES DNR
            const tbodyObsDNR = document.querySelector("#tablaObsDNR tbody");
            tbodyObsDNR.innerHTML = "";
            if (selectedData.obsDNR && selectedData.obsDNR.length > 0) {
                selectedData.obsDNR.forEach(obs => {
                    tbodyObsDNR.innerHTML += `
                        <tr>
                            <td>${obs.UPDATED_AT ?? ''}</td>
                            <td>${obs.FECHA_REGULARIZACION ?? ''}</td>
                            <td>${obs.FECHA_PRORROGA ?? ''}</td>
                            <td>${obs.OBSERVACION_DNR ?? ''}</td>
                        </tr>
                    `;
                });
            } else {
                tbodyObsDNR.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No existen observaciones registradas
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}

// Función para cargar datos en el modal de agregar seguimiento 
function cDatAgregarSeg(button) {
    const idEntidad = button.getAttribute('data-id');
    const url = baseurl + "/backend/empAux/empAuxList.php";
    fetch(`${url}?idEntidad=${idEntidad}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(selectedData => {
            if (!selectedData) return;
            console.log(selectedData);
            // DATOS GENERALES
            document.getElementById("mAsIdEntidad").value         = selectedData.ID_ENTIDAD || '';
            document.getElementById("mAsRucCatastro").value       = selectedData.RUC_CATASTRO || '';
            document.getElementById("mAsRazonSocial").value       = selectedData.RAZON_SOCIAL || '';
            document.getElementById("mAsFechaCorte").value        = selectedData.FECHA_CORTE || '';
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}

// convertir la tabla a datatable 
$(document).ready(function() {
    $('#tablaSegEmpAux').DataTable({
        "autoWidth": true, // Habilita el ajuste automático de ancho
        "dom": '<"botones"B><"filtro"f><"ctabla"rt><"pie"ip>',
        "buttons": [{
                        extend: 'excelHtml5',
                        title: 'Reporte_Gestiones_Simp_INR',
                        exportOptions: {columns: ':visible'},
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



document.getElementById('seguimientoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        idEntidad: document.getElementById('mAsIdEntidad').value,
        ruc: document.getElementById('mAsRucCatastro').value,
        fechaCorte: document.getElementById('mAsFechaCorte').value,
        estadoSeguimiento: document.getElementById('mAsEstadoSeguimiento').value,
        oficioSeguimiento: document.getElementById('mAsOficioSeguimiento').value,
        fechaOfSeguimiento: document.getElementById('mAsFechaOfSeguimiento').value,
        estadoCumplimiento: document.getElementById('mAsEstadoCumplimiento').value,
        motivoIncumplimiento: document.getElementById('mAsMotivoIncumplimiento').value,
        indRegularizado: document.getElementById('mAsIndRegularizado').checked ? 1 : 0,
        observacionDns: document.getElementById('mAsObservacionDns').value
    };

    fetch(baseurl + '/backend/empAux/guardarSeguimiento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Seguimiento guardado',
                text: 'El seguimiento fue registrado correctamente.',
                confirmButtonColor: '#198754'
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('agregarSeguimiento')
                );
                modal.hide();
                // Recargar la tabla o el contenido según sea necesario
                location.reload(); // O puedes actualizar la tabla sin recargar
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
