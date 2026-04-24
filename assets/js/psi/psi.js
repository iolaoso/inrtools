console.log("psi.js loaded");

document.addEventListener('DOMContentLoaded', function () {
    // desabiliel formulario al cargar la pagina
    document.getElementById('formPsi').style.display = 'none'; // Ocultar el formulario al cargar la página
});

document.getElementById('formPsi').addEventListener('submit', function (e) {
    e.preventDefault(); // Esto previene el comportamiento por defecto del formulario
});



// Obtiene la fecha actual en formato ISO
const currentDate = new Date().toISOString();

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
            //console.log("Editando PSI con ID:", id);

            // Nueva URL para obtener la estructura
            var url = baseurl + '/backend/psi/psiList.php?id=' + id;
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
                        document.getElementById('FECHA_CORTE_INFORMACION').value = data.FECHA_CORTE_INFORMACION;
                        document.getElementById('NUMERO').value = data.NUMERO;
                        document.getElementById('COD_UNICO').value = data.RUC + data.NUM_INFORME;
                        document.getElementById('ruc').value = data.RUC;
                        document.getElementById('RAZON_SOCIAL').value = data.RAZON_SOCIAL;
                        document.getElementById('SEGMENTO').value = data.SEGMENTO;
                        document.getElementById('ZONAL').value = data.ZONAL;
                        document.getElementById('ESTADO_JURIDICO').value = data.ESTADO_JURIDICO;
                        document.getElementById('TIPO_SUPERVISION').value = data.TIPO_SUPERVISION;
                        document.getElementById('FECHA_INICIO').value = data.FECHA_INICIO;
                        document.getElementById('ANIO_INICIO').value = data.ANIO_INICIO;
                        document.getElementById('MES_INICIO').value = data.MES_INICIO;
                        document.getElementById('FECHA_FIN').value = data.FECHA_FIN;
                        document.getElementById('ANIO_VENCIMIENTO').value = data.ANIO_VENCIMIENTO;
                        document.getElementById('MES_VENCIMIENTO').value = data.MES_VENCIMIENTO;
                        document.getElementById('TRIMESTRE').value = data.TRIMESTRE;
                        document.getElementById('ESTADO_PSI').value = data.ESTADO_PSI;
                        document.getElementById('VIGENCIA_PSI').value = data.VIGENCIA_PSI;
                        document.getElementById('FECHA_APROBACION_PLAN_FISICO').value = data.FECHA_APROBACION_PLAN_FISICO;
                        document.getElementById('NUM_INFORME').value = data.NUM_INFORME;
                        document.getElementById('FECHA_INFORME').value = data.FECHA_INFORME;
                        document.getElementById('NUM_RESOLUCION').value = data.NUM_RESOLUCION;
                        document.getElementById('FECHA_RESOLUCION').value = data.FECHA_RESOLUCION;
                        document.getElementById('NUM_RESOLUCION_AMPLIACION').value = data.NUM_RESOLUCION_AMPLIACION;
                        document.getElementById('FECHA_RESOLUCION_AMPLIACION').value = data.FECHA_RESOLUCION_AMPLIACION;
                        document.getElementById('FECHA_ULTIMO_BALANCE').value = data.FECHA_ULTIMO_BALANCE;
                        document.getElementById('ACTIVOS').value = data.ACTIVOS;
                        document.getElementById('ULTIMO_RIESGO').value = data.ULTIMO_RIESGO;
                        document.getElementById('NUM_RESOLUCION_FIN_PSI').value = data.NUM_RESOLUCION_FIN_PSI;
                        document.getElementById('FECHA_RESOLUCION_FIN_PSI').value = data.FECHA_RESOLUCION_FIN_PSI;
                        document.getElementById('MOTIVO_CIERRE').value = data.MOTIVO_CIERRE;
                        document.getElementById('ESTRATEGIA_SUPERVISION').value = data.ESTRATEGIA_SUPERVISION;
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
            //recibe el formulario a enviar 
            const formData = new FormData(document.getElementById('formPsi'));
            //agrega action insertar al formData si formData no tiene id si no agrega editar 
            if (!formData.has('id') || formData.get('id') === '') {
                formData.append('id', ''); // Asegúrate de que el ID esté vacío para una nueva entrada
                formData.append('action', 'insertar'); // Acción para guardar un nuevo registro
                console.log("Nuevo registro PSI");
            } else {
                formData.append('action', 'actualizar'); // Acción para editar un registro existente
                console.log("Editar registro PSI id: " + formData.get('id'));
            }
            
            //validar los campos del formulario
            const requiredFields = [
                'FECHA_CORTE_INFORMACION','NUMERO', 'ruc', 'RAZON_SOCIAL', 'SEGMENTO', 'ZONAL',
                'ESTADO_JURIDICO', 'TIPO_SUPERVISION', 'FECHA_INICIO', 'FECHA_FIN',
                'ESTADO_PSI', 'VIGENCIA_PSI', 'FECHA_APROBACION_PLAN_FISICO',
                'NUM_INFORME', 'FECHA_INFORME', 'FECHA_ULTIMO_BALANCE', 'ACTIVOS', 'ULTIMO_RIESGO',
            ];
            let isValid = true;
            requiredFields.forEach(field => {
                const value = formData.get(field);
                if (!value || value.trim() === '') {
                    isValid = false;
                    alert(`El campo ${field} es obligatorio.`);
                }
            }); 
            if (!isValid) {
                console.error("Formulario no válido. Por favor, completa todos los campos obligatorios.");
                return; // Detener la ejecución si el formulario no es válido
            }
            // Mostrar los datos del formulario en la consola
            console.log("Datos del Formulario:", Object.fromEntries(formData.entries()));
            //hace la peticion http 
            var url = baseurl + '/backend/psi/psiList.php';
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
            }).then(data => {
                    if (data.success) {
                        console.log("Registro guardado exitosamente:", data);
                        alert("Registro guardado exitosamente.");
                        // Limpiar el formulario    
                        document.getElementById('formPsi').reset();
                        document.getElementById('formPsi').style.display = 'none'; // Ocultar el formulario después de guardar
                        // location.reload(); // Recargar la página (descomenta si lo necesitas)
                    } else {
                        throw new Error(data.error || "Error al guardar el registro");
                    }
            }).catch(error => {
                    console.error("Error en la operación:", error);
                    alert(error.message);
            });

            break;

        case 'delete':
            // Lógica para eliminar
            const formDataDel = new FormData();
            // Agregar los datos al formDataDel
            formDataDel.append('action', 'eliminar');
            formDataDel.append('id', id);
            console.log("FormData para eliminar:", Object.fromEntries(formDataDel.entries()));
            if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                // Realizar la solicitud para eliminar el registro
                var url = baseurl + '/backend/psi/psiList.php';
                fetch(url, {
                    method: 'POST',
                    body:  formDataDel
                    }).then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        console.log("Registro Eliminado Exitosamente ID: ", data.id);
                        alert("Registro Eliminado exitosamente.");
                        location.reload(); // Recargar la página (descomenta si lo necesitas)
                    } else {
                        throw new Error(data.error || "Error al Eliminar el registro" + data.id);
                    }
                }).catch(error => {
                        console.error("Error en la operación:", error);
                        alert(error.message);
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

// Configuración optimizada de DataTable
function initializeDataTable() {
    const tableId = '#tablePreviewPsi';
    const defaultOptions = {
        dom: '<"top"Bf>rt<"bottom"lip>', // Mejor estructura de controles
        pageLength: 3, // 
        lengthChange: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'PSI Data Export', // Título del archivo Excel
                text: '<i class="fas fa-file-excel"></i> Excel', // Icono + texto
                className: 'btn btn-success', // Clases para estilizado
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all' // Exportar todos los datos, no solo la página actual
                    }
                }
            }
        ],
        responsive: true, // Hacer tabla responsive
        //stateSave: true // Recordar configuración del usuario
    };

    // Verificar si la tabla existe en el DOM
    if ($(tableId).length === 0) {
        console.error('Elemento de tabla no encontrado:', tableId);
        return null;
    }

    // Inicializar o recuperar instancia existente
    let table;
    if ($.fn.DataTable.isDataTable(tableId)) {
        table = $(tableId).DataTable();
        table.destroy(); // Limpiar instancia existente para reinicialización
    }

    table = $(tableId).DataTable(defaultOptions);

    // Mejorar accesibilidad
    $(tableId).attr({
        'aria-label': 'Tabla de gestiones INR',
        'role': 'grid'
    });

    return table;
}


document.getElementById('previewBtn').addEventListener('click', function () {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    if (!file) {
        alert("Por favor, selecciona un archivo.");
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
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

        //console.log("Datos formateados:", JSON.stringify(formattedData, null, 2));

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


        // Uso recomendado dentro de document.ready
        $(document).ready(function () {
            const dataTable = initializeDataTable();
        });
        $('#previewModal').modal('show');
    };

    reader.readAsArrayBuffer(file);
});

// Configuración del evento click del botón
document.getElementById('confirmUploadBtn').addEventListener('click', async function () {
    console.log("Iniciando carga de datos...");

    try {
        const button = this;
        const tablaDatos = $('#tablePreviewPsi').DataTable();

        // Cambiar estado del botón
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Procesando...';

        // Obtener y preparar datos
        const formattedData = prepareUploadData(tablaDatos);
        //console.log("Datos preparados:", formattedData);

        // Subir datos
        const result = await uploadToDatabase(formattedData);
        console.log("Resultado de la carga:", { result });
        // Mostrar resultado
        showUploadResult(result);

    } catch (error) {
        console.error('Error en el proceso:', error);
        showUploadError(error);
    } finally {
        const button = document.getElementById('confirmUploadBtn');
        if (button) {
            button.disabled = false;
            button.textContent = 'Cargar a BDD';
        }
    }
});

/**
 * Prepara los datos para subir en formato JSON
 */
function prepareUploadData(tablaDatos) {
    if (!tablaDatos) {
        throw new Error('La tabla de datos no está disponible');
    }

    const rawData = tablaDatos.rows().data().toArray();
    const formattedData = {
        metadata: {
            timestamp: new Date().toISOString(),
            totalRecords: rawData.length
        },
        records: []
    };

    rawData.forEach((row, index) => {
        try {
            // Asume que las columnas tienen un orden específico
            const record = {
                NUMERO: String(row[2] || ''),
                COD_UNICO: String(row[1] || ''),
                RUC: String(row[3] || ''),
                RAZON_SOCIAL: String(row[4] || ''),
                SEGMENTO: String(row[5] || ''),
                ZONAL: String(row[6] || ''),
                ESTADO_JURIDICO: String(row[7] || ''),
                TIPO_SUPERVISION: String(row[8] || ''),
                FECHA_INICIO: String(row[9] || ''),
                FECHA_FIN: String(row[10] || ''),
                ANIO_INICIO: Number(row[11]) || 0,
                MES_INICIO: Number(row[12]) || 0,
                ANIO_VENCIMIENTO: Number(row[13]) || 0,
                MES_VENCIMIENTO: Number(row[14]) || 0,
                TRIMESTRE: String(row[15] || ''),
                ESTADO_PSI: String(row[16] || ''),
                VIGENCIA_PSI: String(row[17] || ''),
                FECHA_APROBACION_PLAN_FISICO: String(row[18] || ''),
                NUM_INFORME: String(row[19] || ''),
                FECHA_INFORME: String(row[20] || ''),
                NUM_RESOLUCION: String(row[21] || ''),
                FECHA_RESOLUCION: String(row[22] || ''),
                NUM_RESOLUCION_AMPLIACION: String(row[23] || ''),
                FECHA_RESOLUCION_AMPLIACION: String(row[24] || ''),
                FECHA_ULTIMO_BALANCE: String(row[25] || ''),
                ACTIVOS: Number(row[26]) || 0,
                ULTIMO_RIESGO: Number(row[27]) || 0,
                NUM_RESOLUCION_FIN_PSI: String(row[28] || ''),
                FECHA_RESOLUCION_FIN_PSI: String(row[29] || ''),
                MOTIVO_CIERRE: String(row[30] || ''),
                ESTRATEGIA_SUPERVISION: String(row[31] || ''),
                FECHA_CORTE_INFORMACION: String(row[32] || ''),
                ULTIMO_CORTE: String(row[33] || ''),
                EST_REGISTRO: String(row[34] || ''),
                USR_CREACION: String(row[35] || ''),
                FECHA_CREACION: String(row[36] || ''),
                FECHA_ACTUALIZACION: String(row[37] || ''),
                rowIndex: index
            };
            formattedData.records.push(record);
        } catch (error) {
            console.warn(`Error procesando fila ${index}:`, error);
        }
    });

    if (formattedData.records.length === 0) {
        throw new Error('No se encontraron datos válidos para enviar');
    }

    return formattedData;
}

/**
 * Función para subir datos al servidor
 */
async function uploadToDatabase(data) {
    const endpoint = `${baseurl}/backend/psi/uploadFilePSI.php`; // Ruta al archivo PHP

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(data)
        });

        const responseText = await response.text(); // Obtiene la respuesta como texto
        //console.log(responseText); // Imprime la respuesta en la consola

        if (!response.ok) {
            // Intenta parsear el texto de respuesta como JSON
            let errorData;
            try {
                errorData = JSON.parse(responseText);
            } catch (e) {
                // Si no se puede parsear, usa un mensaje genérico
                throw new Error(`Error en el servidor: ${response.status}`);
            }
            throw new Error(errorData.message || `Error en el servidor: ${response.status}`);
        }

        return JSON.parse(responseText); // Devuelve la respuesta JSON del servidor
    } catch (error) {
        //console.log('Error al subir datos a la base de datos:', error);
        throw error; // Re-lanza el error para que pueda ser manejado en otro lugar
    }
}

/**
 * Muestra el resultado exitoso
 */
function showUploadResult(result) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        html: `
            <p>${result.message || 'N/A'}</p>
            <small>Registros procesados: ${result.numRecord || 'N/A'}</small>
        `,
        timer: 4000, // opcional para que se cierre solo 
        showConfirmButton: false
    });

    // Opcional: Recargar los datos después de 3 segundos
    setTimeout(() => {
        window.location.reload(); // Recarga toda la página
        //     $('#tablePreviewPsi').DataTable().ajax.reload(null, false);
    }, 4000);
}

/**
 * Muestra errores de carga
 */
function showUploadError(error) {
    // Mostrar un mensaje de error con SweetAlert2
    Swal.fire({
        icon: 'error',
        title: 'Error en la carga',
        html: `
            <p>${error.message || 'Error desconocido'}</p>
            <small>Intente Nuevamente</small>
        `,
        //footer: '<a href="/ayuda" class="btn btn-link">¿Necesita ayuda?</a>'
    });
}