console.log("psi.js loaded");

// Función para cargar datos en el modal
function cargarDatosPsi(button) {
    const id = button.getAttribute('data-id');
    //console.log(id);
    const url = baseurl + "/backend/psi/psiList.php"; // URL de tu script PHP

    // Realizar la solicitud AJAX usando fetch
    fetch(`${url}?id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red');
            }
            return response.json();
        })
        .then(selectedData => {
            if (selectedData) {
                //console.log(selectedData.i);
                document.getElementById("detalleid").value = selectedData.id;
                document.getElementById("detalleFecCorte").value = selectedData.FECHA_CORTE;
                document.getElementById("detalleRUC").value = selectedData.RUC;
                document.getElementById("detalleSegmento").value = selectedData.SEGMENTO;
                document.getElementById("detalleZonal").value = selectedData.ZONAL;
                document.getElementById("detalleEstadoJuridico").value = selectedData.ESTADO_JURIDICO;
                document.getElementById("detalleTipoSupervision").value = selectedData.TIPO_SUPERVISION;
                document.getElementById("detalleRazonSocial").value = selectedData.RAZON_SOCIAL;
                document.getElementById("detallefechaIni").value = selectedData.FECHA_INICIO;
                document.getElementById("detallefechaFin").value = selectedData.FECHA_FIN;
                document.getElementById("detalleMesVenc").value = selectedData.MES_VENCIMIENTO;
                document.getElementById("detalleAnioVenc").value = selectedData.ANIO_VENCIMIENTO;
                document.getElementById("detalleTrime").value = selectedData.TRIMESTRE;
                document.getElementById("detalleEstado").value = selectedData.ESTADO_PSI;
                document.getElementById("detalleVigencia").value = selectedData.VIGENCIA_PSI;
                document.getElementById("detalleFecAproPF").value = selectedData.FECHA_APROBACION_PLAN_FISICO;
                document.getElementById("detalleNumInf").value = selectedData.NUM_INFORME;
                document.getElementById("detalleFecInf").value = selectedData.FECHA_INFORME;
                document.getElementById("detalleNumRes").value = selectedData.NUM_RESOLUCION;
                document.getElementById("detalleFecRes").value = selectedData.FECHA_RESOLUCION;
                document.getElementById("detalleResAmp").value = selectedData.NUM_RESOLUCION_AMPLIACION;
                document.getElementById("detalleFecResAmp").value = selectedData.FECHA_RESOLUCION_AMPLIACION;
                document.getElementById("detalleUltBal").value = selectedData.FECHA_ULTIMO_BALANCE;
                document.getElementById("detalleActivos").value = selectedData.ACTIVOS;
                document.getElementById("detalleUltRiesgo").value = selectedData.ULTIMO_RIESGO;
                document.getElementById("detalleResFinPSI").value = selectedData.RESOLUCION_FIN_PSI;
                document.getElementById("detalleFecResFinPSI").value = selectedData.FECHA_RESOLUCION_FIN_PSI;
                document.getElementById("detalleMotCierre").value = selectedData.MOTIVO_CIERRE;
                document.getElementById("detalleEstSup").value = selectedData.ESTRATEGIA_SUPERVISION;
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos:", error);
        });
}


function asignarActions(button, action) {
    // Verificar si el botón es válido
    if (!button) {
        console.error("El botón no está definido.");
        return;
    }

    // Obtener el ID del psi desde el botón
    const id = button.getAttribute('data-id');
    
    switch (action) {
        case 'edit':
            // Lógica para editar
            console.log("Editando PSI con ID:", id);

            // Nueva URL para obtener la estructura
            const url = baseurl + '/backend/psi/psiList.php?id=' + id;
            // Realizar la solicitud para obtener los detalles del PSI
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la red');
                    }
                    return response.json();
                })
                .then(data => {
                    // Llenar los campos del formulario con los datos obtenidos
                    if (data) {
                        document.getElementById('id').value = data.id;
                        document.getElementById('NUMERO').value = data.NUMERO;
                        document.getElementById('COD_UNICO').value = data.COD_UNICO;
                        document.getElementById('RUC').value = data.RUC;
                        document.getElementById('RAZON_SOCIAL').value = data.RAZON_SOCIAL;
                        document.getElementById('SEGMENTO').value = data.SEGMENTO;
                        document.getElementById('ZONAL').value = data.ZONAL;
                        document.getElementById('ESTADO_JURIDICO').value = data.ESTADO_JURIDICO;
                        document.getElementById('TIPO_SUPERVISION').value = data.TIPO_SUPERVISION;
                        document.getElementById('FECHA_INICIO').value = data.FECHA_INICIO;
                        document.getElementById('FECHA_FIN').value = data.FECHA_FIN;
                        document.getElementById('ULTIMO_CORTE').value = data.ULTIMO_CORTE;
                        document.getElementById('EST_REGISTRO').value = data.EST_REGISTRO;
                        document.getElementById('USR_CREACION').value = data.USR_CREACION;

                        // Mostrar el formulario
                        document.getElementById('formPsi').style.display = 'block'; // Asegúrate de que el formulario esté oculto inicialmente
                    }
                })
                .catch(error => {
                    console.error("Error al obtener los datos del PSI:", error);
                });
            break;

        case 'save':
            // Lógica para guardar los cambios
            const formData = new FormData(document.getElementById('formPsi'));
            fetch(base_url  + '/backend/psiActions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la red');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert("Registro guardado exitosamente.");
                    location.reload(); // Recargar la página
                } else {
                    alert("Error al guardar el registro: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error al guardar el registro:", error);
            });
            break;

        case 'delete':
            // Lógica para eliminar
            console.log("Eliminando PSI con ID:", id);
            if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                // Realizar la solicitud para eliminar el registro
                fetch(base_url  + '/backend/psiActions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ action: 'delete', id: id })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la red');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert("Registro eliminado exitosamente.");
                        location.reload(); // Recargar la página
                    } else {
                        alert("Error al eliminar el registro: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error al eliminar el registro:", error);
                });
            }
            break;

        default:
            console.error("Acción no reconocida:", action);
            break;
    }
}


