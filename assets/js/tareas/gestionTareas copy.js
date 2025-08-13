console.log("Gestión de Tareas Funcionando.");

// Función para guardar el formulario
function guardarForm(formId, event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    const form = document.getElementById(formId);
    const formData = new FormData(form);

    // Realiza la solicitud AJAX para guardar la tarea
    fetch('path/to/your/server/endpoint', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tarea guardada correctamente.');
            form.reset(); // Limpiar el formulario
            // Opcional: Recargar la tabla de tareas aquí
            cargarTareas();
        } else {
            alert('Error al guardar la tarea: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al guardar la tarea.');
    });
}

// Función para limpiar el formulario
function limpiarForm(formId) {
    document.getElementById(formId).reset();
}


// Función para cargar las tareas en la tabla
async function cargarTareas(analistaAsignado = null) {
    const url = baseurl + "/backend/tareas/gestionTareasList.php?analista=" + encodeURIComponent(analistaAsignado);
    
    try {
        const response = await fetch(url);
        
        // Verificar si la respuesta está vacía
        const responseText = await response.text();
        if (!responseText.trim()) {
            throw new Error('La respuesta del servidor está vacía');
        }
        
        // Parsear manualmente el JSON para mejor manejo de errores
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Respuesta del servidor:', responseText);
            throw new Error('La respuesta no es un JSON válido');
        }

        // Verificar si es un array
        if (!Array.isArray(data)) {
            throw new Error('Se esperaba un array de tareas');
        }

        const tbody = document.querySelector('#tablaActividades tbody');
        tbody.innerHTML = ''; // Limpiar la tabla

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="12" class="text-center">No hay tareas registradas</td></tr>`;
            return;
        }

        data.forEach(task => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${escapeHtml(task.TAREA || '-')}</td>
                <td>${escapeHtml(task.TIPO_PROCESO || '-')}</td>
                <td>${escapeHtml(task.FRECUENCIA || '-')}</td>
                <td>${escapeHtml(task.RUC || '-')}</td>
                <td>${escapeHtml(task.DESCRIPCION || '-')}</td>
                <td>${formatDate(task.PROXIMA_FECHA)}</td>
                <td>${escapeHtml(task.PROXIMA_HORA || '-')}</td>
                <td>${formatDate(task.ULTIMA_EJECUCION)}</td>
                <td>${escapeHtml(task.ANALISTA_ASIGNADO || '-')}</td>
                <td><span class="badge ${getStatusBadgeClass(task.ESTADO_TAREA)}">${escapeHtml(task.ESTADO_TAREA || '-')}</span></td>
                <td>
                    ${task.ESTADO_TAREA === 'COMPLETADA' 
                        ? `<i class="fa fa-thumbs-up text-success" title="Tarea completada"></i>`
                        : `<button class="btn btn-success doit-btn" data-id="${task.id}" title="Marcar como completada">
                            <i class="fa fa-check-circle-o"></i>
                          </button>`
                    }
                </td>
                <td>
                    <button class="btn btn-info edit-btn btn-sm mx-1" data-id="${task.id}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger delete-btn btn-sm mx-1" data-id="${task.id}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });

    } catch (error) {
        console.error('Error al cargar tareas:', error);
        mostrarAlerta(`Error al cargar tareas: ${error.message}`, 'danger');
        
        // Mostrar mensaje en la tabla
        const tbody = document.querySelector('#tablaActividades tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center text-danger">
                    Error al cargar tareas. Por favor intente nuevamente.
                    ${DEBUG_MODE ? `<br><small>${error.message}</small>` : ''}
                </td>
            </tr>
        `;
    }
}

// Función para escapar HTML (seguridad XSS)
function escapeHtml(unsafe) {
    return unsafe?.toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;") || '';
}

// Función para formatear fechas
function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? dateString : date.toLocaleDateString('es-ES');
    } catch {
        return dateString;
    }
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'danger') {
    const alertContainer = document.getElementById('alert-container') || document.body;
    const alertId = 'alert-' + Date.now();
    const alerta = `
        <div id="${alertId}" class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${escapeHtml(mensaje)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    alertContainer.insertAdjacentHTML('afterbegin', alerta);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }
    }, 5000);
}

// Función para cargar las tareas en la tabla
function cargarTareas1() {
    const url = baseurl + "/backend/tareas/gestionTareasList.php";
    fetch(url)
    .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
    .then(data => {

        console.log(data); // Ver los datos en la consola para depuración

        /*const tbody = document.querySelector('#tablaActividades tbody');
        tbody.innerHTML = ''; // Limpiar la tabla

        data.forEach(task => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${task.TAREA}</td>
                <td>${task.TIPO_PROCESO}</td>
                <td>${task.FRECUENCIA}</td>
                <td>${task.RUC}</td>
                <td>${task.DESCRIPCION}</td>
                <td>${task.PROXIMA_FECHA}</td>
                <td>${task.PROXIMA_HORA}</td>
                <td>${task.ULTIMA_EJECUCION}</td>
                <td>${task.ANALISTA_ASIGNADO}</td>
                <td>${task.ESTADO_TAREA}</td>
                <td>
                    ${task.ESTADO_TAREA === 'COMPLETADA' 
                        ? `<i class="fa fa-thumbs-up text-success" title="Tarea completada"></i>`
                        : `<button class="btn btn-success doit-btn" data-id="${task.id}" title="Hecho">
                            <i class="fa fa-check-circle-o"></i>
                        </button>`
                    }
                </td>
                <td>
                    <button class="btn btn-info edit-btn btn-sm" data-id="${task.id}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger delete-btn btn-sm" data-id="${task.id}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });*/
    })
    .catch(error => console.error('Error al cargar tareas:', error));
}
 

// Ejemplo de función para mostrar errores
function mostrarMensajeError(mensaje) {
    const contenedor = document.getElementById('mensajes-error');
    contenedor.innerHTML = `<div class="alert alert-danger">${mensaje}</div>`;
    setTimeout(() => contenedor.innerHTML = '', 5000);
}

// Función para asignar eventos a los botones de la tabla
function asignarEventosBotones() {
    const doitButtons = document.querySelectorAll('.doit-btn');
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');

    doitButtons.forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.id;
            // Lógica para marcar tarea como hecha
            console.log('Marcar tarea como hecha:', taskId);
            const url = baseurl + "/backend/tareas/gestionTareasList.php?action=hecho";
            fetch(url), {
                method: 'POST',
                body: JSON.stringify({ id: taskId }),
            headers: {
                'Content-Type': 'application/json'
            }
            }.then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tarea marcada como hecha.');
                    cargarTareas(); // Recargar la tabla de tareas
                } else {
                    alert('Error al marcar tarea como hecha: ' + data.message);
                }
            })  
        });
    });

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.id;
            // Lógica para editar tarea
            console.log('Editar tarea:', taskId);
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.id;
            if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
                // Lógica para eliminar tarea
                console.log('Eliminar tarea:', taskId);
            }
        });
    });
}

// Cargar tareas al iniciar
document.addEventListener('DOMContentLoaded', cargarTareas);