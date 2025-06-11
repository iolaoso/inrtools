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
                document.getElementById("detalleFecCorte").value = selectedData.FECHA_CORTE_INFORMACION;
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
                document.getElementById("detalleResFinPSI").value = selectedData.NUM_RESOLUCION_FIN_PSI;
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


function excelDateToJSDate(excelDate) {
    const date = new Date((excelDate - (25567 + 1)) * 86400 * 1000);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Meses empiezan desde 0
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`; // Formato YYYY-MM-DD
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('previewBtn').addEventListener('click', function() {
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        if (!file) {
            alert("Por favor, selecciona un archivo.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });

            const worksheet = workbook.Sheets["BASE_PSI"];
            if (!worksheet) {
                alert("No se encontró la hoja 'BASE_PSI'.");
                return;
            }

            const json = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
            const startRow = json.findIndex(row => row[0] === "T_PSIDATA");
            const filteredData = json.slice(startRow + 1);

            const dateHeaders = [
                "FECHA_INICIO",
                "FECHA_FIN",
                "FECHA_APROBACION_PLAN_FISICO",
                "FECHA_INFORME",
                "FECHA_RESOLUCION",
                "FECHA_RESOLUCION_AMPLIACION",
                "FECHA_ULTIMO_BALANCE",
                "FECHA_RESOLUCION_FIN_PSI",
                "FECHA_CORTE_INFORMACION",
                "FECHA_CREACION",
                "FECHA_ACTUALIZACION",
                "DELETED_AT"
            ]; // Campos de fecha
            const headers = filteredData[0];

            // Convertir las fechas en el JSON y mantener los datos de las celdas vacías o nulas 
            const formattedData = filteredData.map((row, rowIndex) => {
                if (rowIndex === 0) return row; // Dejar los encabezados sin cambios
                // Crear un nuevo array de celdas para la fila actual
                const newRow = new Array(headers.length).fill(''); // Inicializar con cadenas vacías
                row.forEach((cell, index) => {
                    if (dateHeaders.includes(headers[index]) && typeof cell === 'number') {
                        newRow[index] = excelDateToJSDate(cell); // Formatear la fecha
                    } else if (cell === null || cell === undefined) {
                        newRow[index] = ''; // Mantener celdas vacías
                    } else if (typeof cell === 'string') {
                        newRow[index] = cell.trim(); // Limpiar espacios en blanco
                    } else {
                        newRow[index] = cell; // Mantener el valor original si es de otro tipo
                    }
                });
                return newRow; // Retornar la nueva fila con el mismo número de celdas
            });

            console.log("Datos formateados:", JSON.stringify(formattedData, null, 2));

            document.getElementById('tableHeaders').innerHTML = '';
            document.getElementById('tableBody').innerHTML = '';

            if (formattedData.length > 0) {
                headers.forEach(header => {
                    const th = document.createElement('th');
                    th.textContent = header;
                    document.getElementById('tableHeaders').appendChild(th);
                });

                formattedData.slice(1).forEach(row => {
                    const tr = document.createElement('tr');
                    row.forEach(cell => {
                        const td = document.createElement('td');
                        td.textContent = cell === undefined ? '' : cell; // Mantener celdas vacías
                        tr.appendChild(td);
                    });
                    document.getElementById('tableBody').appendChild(tr);
                });
            } else {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = '100%';
                td.textContent = 'No hay datos para mostrar.';
                tr.appendChild(td);
                document.getElementById('tableBody').appendChild(tr);
            }

            
            // Inicializar DataTable con 5 registros por página
            if ($.fn.dataTable.isDataTable('#tablePreviewPsi')) {
                table = $('#tablePreviewPsi').DataTable();
            } else {
                $('#tablePreviewPsi').DataTable({
                    dom: 'Bfrtip',
                    retrieve: true, // para que solo cree una instancia de DataTable
                    pageLength: 4, // Establecer el número de registros por página
                    lengthChange: false, // Desactivar la opción "Show entries"
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'Reporte_Gestiones_INR',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ],
                    columnDefs: [
                        { targets: [0, 1, 2, 33, 34, 35, 36, 37, 38], visible: false }
                    ]
                });
            }

            $('#previewModal').modal('show');

            // Destruir DataTable al cerrar el modal
            $('#previewModal').on('hidden.bs.modal', function () {
                $('#tablePreviewPsi').DataTable().destroy(); // Destruir la instancia de DataTable
                $('#tableBody').empty(); // Limpiar el cuerpo de la tabla
                $('#tableHeaders').empty(); // Limpiar los encabezados
            });
        };

        reader.readAsArrayBuffer(file);
    });
});






            

 