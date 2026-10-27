console.log("funcEmpAux.js funcionando")

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
    // Limpiar historial
    document.querySelector('#tablaHistorialSeguimientos tbody').innerHTML = ''; 

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
                // Historial
                document.querySelector('#tablaHistorialSeguimientos tbody').innerHTML += `
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
                `; 
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

//para cargar el seguimiento al form
function cargarSeguimiento(idSeguimiento) {

    fetch(
        `${baseurl}/backend/empAux/empAuxList.php?opt=seguimiento&idSeguimiento=${idSeguimiento}`
    )
    .then(response => response.json())
    .then(seg => {
        console.log(seg);
        // Identificador
        document.getElementById('mAsIdSeguimiento').value =
            seg.ID_SEGUIMIENTO ?? '';
        // Datos generales
        /* document.getElementById('mAsIdEntidad').value =
            seg.ID_ENTIDAD ?? '';
        document.getElementById('mAsPeriodo').value =
            seg.PERIODO ?? '';
        document.getElementById('mAsRucCatastro').value =
            seg.RUC ?? '';
        document.getElementById('mAsFechaCorte').value =
            seg.FECHA_CORTE ?? '';
        document.getElementById('mAsRazonSocial').value =
            seg.RAZON_SOCIAL ?? ''; */
        // Estado (solo lectura)
        document.getElementById('mAsIdEstadoSeguimiento').value =
            seg.ID_ESTADO_CUMPLIMIENTO;
        document.getElementById('mAsEstadoSeguimiento').value =
            seg.DESC_ESTADO_SEG ?? '';
        document.getElementById('mAsIdEstadoCumplimiento').value =
            seg.ID_ESTADO_CUMPLIMIENTO;
        document.getElementById('mAsEstadoCumplimiento').value =
            seg.DESC_ESTADO_CUMPLIMIENTO ?? '';
        // Datos editables
        document.getElementById('mAsOficioSeguimiento').value =
            seg.OFICIO_SEGUIMIENTO ?? '';
        document.getElementById('mAsFechaOfSeguimiento').value =
            seg.FECHA_OF_SEGUIMIENTO ?? '';
        document.getElementById('mAsMotivoIncumplimiento').value =
            seg.ID_MOTIVO_INCUMPLIMIENTO ?? '';
        document.getElementById('mAsObservacionDns').value =
            seg.OBSERVACION_DNS ?? '';
        document.getElementById('mAsIndRegularizado').checked =
            parseInt(seg.IND_REGULARIZADO) === 1;
        // Actualizar título y botón
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

// función para ver el detalle del catastro
function cDatCatastroEmpAux(button) {
    const rucid = button.getAttribute('data-ruc');
    //console.log(`RUC: ${ruc} `);
    const params = new URLSearchParams({rucid});
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
            const catastro = selectedData.catastro || selectedData;
            // DATOS GENERALES
            document.getElementById("mIdEntidad").value          = catastro.ID_ENTIDAD || '';
            document.getElementById("mRuc").value               = catastro.RUC || '';
            document.getElementById("mRazonSocial").value       = catastro.RAZON_SOCIAL || '';
            document.getElementById("mTipoOrganizacion").value  = catastro.TIPO_ORGANIZACION || '';
            // CALIFICACIÓN
            document.getElementById("mOfComunicacionResolucion").value  = catastro.OF_COMUNICACION_RESOLUCION || '';
            document.getElementById("mFechaOfComRes").value             = catastro.FECHA_OFICIO_COM_RES || '';
            document.getElementById("mNumResolucionCalificacion").value = catastro.NUM_RESOLUCION_CALIFICACION || '';
            document.getElementById("mFechaResolucion").value           = catastro.FECHA_RESOLUCION || '';
            document.getElementById("mFechaVencimientoRes").value       = catastro.FECHA_VENCIMIENTO_RES || '';
            // SERVICIOS PRESTADOS
            document.getElementById("mSoftwareFinanciero").checked =
                catastro.SOFTWARE_FINANCIERO_Y_COMPUTACIONAL == 1;
            document.getElementById("mTransaccionalesPago").checked =
                catastro.TRANSACCIONALES_Y_DE_PAGO == 1;
            document.getElementById("mTransporteValores").checked =
                catastro.TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES == 1;
            document.getElementById("mRedCajeros").checked =
                catastro.RED_Y_CAJEROS_AUTOMATICOS == 1;
            document.getElementById("mCobranzas").checked =
                catastro.COBRANZAS == 1;
            document.getElementById("mServiciosContables").checked =
                catastro.SERVICIOS_CONTABLES == 1;
            document.getElementById("mGeneradorasCartera").checked =
                catastro.GENERADORAS_DE_CARTERA == 1;
            document.getElementById("mOperadorasTarjetas").checked =
                catastro.ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS == 1;
            document.getElementById("mGiroInmobiliario").checked =
                catastro.GIRO_INMOBILIARIO == 1;
            // REPRESENTANTE Y CORREO
            document.getElementById("mRepresentanteLegal").value =
                catastro.REPRESENTANTE_LEGAL || '';
            document.getElementById("mCorreoElectronico").value =
                catastro.CORREO_ELECTRONICO || '';
            // NUEVA
            document.getElementById("mNueva").checked =
                catastro.NUEVA == 1;
            // CONDICIÓN
           document.getElementById("mConCondicion").checked =
            catastro.CON_CONDICION == 1;
            document.getElementById("mSinCondicion").checked =
                catastro.CON_CONDICION != 1;
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}