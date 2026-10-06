console.log("segEmpAux.js funcionando")


// Función para cargar datos en el modal
function cDatSegEmpAux(button) {
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
            document.getElementById("mNumeroPeriodo").value     = selectedData.NUMERO_PERIODO || '';
            document.getElementById("mIdEntidad").value         = selectedData.ID_ENTIDAD || '';
            document.getElementById("mRucCatastro").value       = selectedData.RUC_CATASTRO || '';
            document.getElementById("mRazonSocial").value       = selectedData.RAZON_SOCIAL || '';
            // SERVICIOS PRESTADOS
            document.getElementById("mSoftwareFinanciero").checked  = selectedData.SOFTWARE_FINANCIERO_Y_COMPUTACIONAL == 1;
            document.getElementById("mTransaccionalesPago").checked = selectedData.TRANSACCIONALES_Y_DE_PAGO == 1;
            document.getElementById("mTransporteValores").checked   = selectedData.TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES == 1;
            document.getElementById("mRedCajeros").checked          = selectedData.RED_Y_CAJEROS_AUTOMATICOS == 1;
            document.getElementById("mCobranzas").checked           = selectedData.COBRANZAS == 1;
            document.getElementById("mServiciosContables").checked  = selectedData.SERVICIOS_CONTABLES == 1;
            document.getElementById("mGeneradorasCartera").checked  = selectedData.GENERADORAS_DE_CARTERA == 1;
            document.getElementById("mOperadorasTarjetas").checked  = selectedData.ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS == 1;
            document.getElementById("mGiroInmobiliario").checked    = selectedData.GIRO_INMOBILIARIO == 1;
            // CALIFICACIÓN
            document.getElementById("mOfComunicacionResolucion").value  = selectedData.OF_COMUNICACION_RESOLUCION || '';
            document.getElementById("mFechaOficioComRes").value         = selectedData.FECHA_OFICIO_COM_RES || '';
            document.getElementById("mNumResolucionCalificacion").value = selectedData.NUM_RESOLUCION_CALIFICACION || '';
            document.getElementById("mFechaResolucion").value           = selectedData.FECHA_RESOLUCION || '';
            document.getElementById("mFechaVencimientoRes").value       = selectedData.FECHA_VENCIMIENTO_RES || '';
            // ENTREGA
            document.getElementById("mEstado").value                = selectedData.ESTADO || '';
            document.getElementById("mMedioEnvio").value            = selectedData.MEDIO_ENVIO || '';
            document.getElementById("mCorreo").value                = selectedData.CORREO || '';
            document.getElementById("mFechaRegistro").value         = selectedData.FECHA_REGISTRO || '';
            document.getElementById("mFechaCorte").value            = selectedData.FECHA_CORTE || '';
            document.getElementById("mFechaLineaBase").value        = selectedData.FECHA_LINEA_BASE || '';
            document.getElementById("mFechaRegularizacion").value   = selectedData.FECHA_REGULARIZACION || '';
            document.getElementById("mFechaProrroga").value         = selectedData.FECHA_PRORROGA || '';
            document.getElementById("mObservacionDnr").value        = selectedData.OBSERVACION_DNR || '';
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}
