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
function cargarTareas() {
    const url = baseurl + "/backend/tareas/gestionTareasList.php";
    fetch(url)
    .then(response => response.json())
    .then(data => {
        const tbody = document.querySelector('#tablaActividades tbody');
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
                <td>${task.ESTADO}</td>
                <td>
                    <button class="btn btn-primary doit-btn btn-sm" data-id="${task.id}" title="Hecho">
                        <i class="fas fa-save"></i>
                    </button>
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
        });
    })
    .catch(error => console.error('Error al cargar tareas:', error));
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