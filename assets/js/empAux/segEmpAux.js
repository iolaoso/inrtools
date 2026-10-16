console.log("segEmpAux.js funcionando")


// Función para cargar datos en el modal
function cDatSegEmpAux(button) {
    const idEntidad = button.getAttribute('data-id');
    //console.log(idEntidad);
    const ruc = button.getAttribute('data-ruc');
    const fecResolucion = button.getAttribute('data-fecRes');
    const periodo = button.getAttribute('data-periodo');
    const fecCorte = button.getAttribute('data-fCorte');
    console.log(`ID: ${idEntidad} | RUC: ${ruc} | Resolución: ${fecResolucion} | Período: ${periodo} | Corte: ${fecCorte}`);
    const params = new URLSearchParams({idEntidad,ruc,fecResolucion,periodo,fecCorte});
    const url = `${baseurl}/backend/empAux/empAuxList.php?${params.toString()}`;
    console.log(url);
    fetch(url)
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
            document.getElementById("mNumeroPeriodo").value     = selectedData.bitacora?.NUM_PERIODO || periodo || '';
            document.getElementById("mIdEntidad").value         = selectedData.bitacora?.ID_ENTIDAD || idEntidad || '';
            document.getElementById("mRucCatastro").value       = selectedData.bitacora?.RUC_CATASTRO || ruc || '';
            document.getElementById("mRazonSocial").value       = selectedData.bitacora?.RAZON_SOCIAL || '';
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
function cDatAgregarSeg(btn) {
    const idEntidad = btn.dataset.id || '';
    const ruc = btn.dataset.ruc || '';
    const fecResolucion = btn.dataset.fecres || '';
    const periodo = btn.dataset.periodo || '';
    const fecCorte = btn.dataset.fcorte || '';
    // Datos cabecera
    document.getElementById('mAsIdEntidad').value = idEntidad;
    document.getElementById('mAsPeriodo').value = periodo;
    document.getElementById('mAsRucCatastro').value = ruc;
    document.getElementById('mAsFechaCorte').value = fecCorte;
    // Modo nuevo seguimiento
    document.getElementById('tituloSeguimiento').textContent = 'Registro de Seguimiento';
    document.getElementById('mAsIdSeguimiento').value = '';
    document.getElementById('mAsEstadoSeguimiento').value = '';
    document.getElementById('mAsOficioSeguimiento').value = '';
    document.getElementById('mAsFechaOfSeguimiento').value = '';
    document.getElementById('mAsEstadoCumplimiento').value = '';
    document.getElementById('mAsMotivoIncumplimiento').value = '';
    document.getElementById('mAsIndRegularizado').checked = false;
    document.getElementById('mAsObservacionDns').value = '';
    document.getElementById('btnGuardarSeguimiento').innerHTML = '<i class="fas fa-save"></i> Guardar';
    // Limpiar combo
    const cmbSeguimientos = document.getElementById('mAsSeguimientoExistente');
    cmbSeguimientos.innerHTML = '<option value="">Nuevo seguimiento...</option>';
    // Limpiar historial
    /* document.querySelector('#tablaHistorialSeguimientos tbody').innerHTML = ''; */

    const params = new URLSearchParams({idEntidad,ruc,fecResolucion,periodo,fecCorte});
    fetch(`${baseurl}/backend/empAux/empAuxList.php?${params.toString()}`)
    .then(response => response.json())
    .then(result => {
        //console.log(result);
        // Razon social
        document.getElementById('mAsRazonSocial').value = result.bitacora?.RAZON_SOCIAL || '';
        // Seguimientos
        if (result.segEnt) {
            result.segEnt.forEach(seg => {
                // Combo
                cmbSeguimientos.innerHTML += `
                    <option value="${seg.ID_SEGUIMIENTO}">
                        # ${seg.NUMERO_SEGUIMIENTO}
                        - ${seg.FECHA_OF_SEGUIMIENTO ?? ''}
                        - ${seg.OFICIO_SEGUIMIENTO ?? ''}
                    </option>
                `;
                // Historial
                /* document.querySelector('#tablaHistorialSeguimientos tbody').innerHTML += `
                    <tr>
                        <td class="text-center">${seg.NUMERO_SEGUIMIENTO}</td>
                        <td>${seg.FECHA_OF_SEGUIMIENTO ?? ''}</td>
                        <td>${seg.OFICIO_SEGUIMIENTO ?? ''}</td>
                        <td>${seg.ESTADO_SEGUIMIENTO ?? ''}</td>
                        <td>${seg.ESTADO_CUMPLIMIENTO ?? ''}</td>
                        <td class="text-center">
                            ${seg.IND_REGULARIZADO == 1
                                ? '<span class="badge bg-success">Sí</span>'
                                : '<span class="badge bg-secondary">No</span>'}
                        </td>
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-warning btn-sm"
                                onclick="cargarSeguimiento(${seg.ID_SEGUIMIENTO})">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                `; */
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No fue posible cargar los seguimientos.'
        });
    });
}

// Función para cargar datos en el modal de agregar seguimiento 
function cDatAgregarProcDNR(button) {
    const idEntidad = button.getAttribute('data-id');
    const ruc = button.getAttribute('data-ruc');
    const fecResolucion = button.getAttribute('data-fecRes');
    const periodo = button.getAttribute('data-periodo');
    const fecCorte = button.getAttribute('data-fCorte');
    //console.log(`ID: ${idEntidad} | RUC: ${ruc} | Resolución: ${fecResolucion} | Período: ${periodo} | Corte: ${fecCorte}`);
    const params = new URLSearchParams({idEntidad,ruc,fecResolucion,periodo,fecCorte});
    const url = `${baseurl}/backend/empAux/empAuxList.php?${params.toString()}`;
    //console.log(url);
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(selectedData => {
            if (!selectedData) return;
            console.log(selectedData.bitacora);
            // DATOS GENERALES
            document.getElementById("mApIdEntidad").value   = selectedData.bitacora?.ID_ENTIDAD || idEntidad || '';
            document.getElementById("mApRucCatastro").value = selectedData.bitacora?.RUC || ruc || '';
            document.getElementById("mApFechaRes").value    = selectedData.bitacora?.FECHA_RESOLUCION || fecResolucion || '';
            document.getElementById("mApPeriodo").value     = selectedData.bitacora?.NUM_PERIODO || periodo || '';
            document.getElementById("mApFechaCorte").value  = selectedData.bitacora?.FECHA_CORTE || fecCorte || '';
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

    /* tablaBitacoraEnvio */
    $('#tablaBitacoraEnvio').DataTable({
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
        estadoSeguimiento: document.getElementById('mAsEstadoSeguimiento').value,
        oficioSeguimiento: document.getElementById('mAsOficioSeguimiento').value,
        fechaOfSeguimiento: document.getElementById('mAsFechaOfSeguimiento').value,
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

document.getElementById('mAsSeguimientoExistente').addEventListener('change', function () {

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

});

//PARA VALIDAR Y GUARDAR EL FORM DEL ÑPROCESO DNR
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
