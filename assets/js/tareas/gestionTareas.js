document.addEventListener('DOMContentLoaded', () => {
    console.log("Gestión de Tareas Funcionando.");

    const isDirector = usrRol; 
    mostrarAnalistaEjecutante(isDirector);
    
    // Manejo delegado de eventos para mejor performance
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        console.log("Botón presionado:", btn.className);
        
        const url = baseurl + '/backend/tareas/gestionTareasList.php';
        let taskId = btn.dataset.id;
        let action;
        let formData = {};
        const form = document.getElementById('frmTareas');
        
        if (btn.classList.contains('complete-btn')) {
            action = 'completar';
            formData = { id: taskId, action };
        } else if (btn.classList.contains('delete-btn')) {
            action = 'eliminar';
            formData = { id: taskId, action };
        } else if (btn.classList.contains('edit-btn')) {
            console.log("Editando tarea con ID:", taskId);
            action = 'getTask';
            try {
                const response = await fetch(`${url}?action=${action}&id=${taskId}`);
                const task = await response.json();
                
                if (!response.ok) {
                    throw new Error('Error al obtener datos de la tarea');
                }
                
                // Mapeo de campos de la base de datos a los nombres del formulario
                const fieldMap = {
                    'id': 'taskId',
                    'TAREA': 'taskName',
                    'TIPO_PROCESO': 'processType',
                    'FRECUENCIA': 'frequency',
                    'RUC': 'ruc',
                    'DESCRIPCION': 'description',
                    'PROXIMA_FECHA': 'nextExecutionDate',
                    'PROXIMA_HORA': 'nextExecutionTime',
                    'ULTIMA_EJECUCION': 'lastExecution',
                    'ANALISTA_ASIGNADO': 'analistaEjecutante',
                    'ESTADO_TAREA': 'taskStatus'
                };
                
                // Llenar el formulario con los datos de la tarea
                Object.keys(fieldMap).forEach(dbField => {
                    const formField = fieldMap[dbField];
                    const input = form.querySelector(`[name="${formField}"]`);
                    if (input) {
                        // Formatear fecha y hora si es necesario
                        if (dbField === 'PROXIMA_FECHA' || dbField === 'ULTIMA_EJECUCION') {
                            if (task[dbField]) {
                                const date = new Date(task[dbField]);
                                if (dbField === 'PROXIMA_FECHA') {
                                    input.value = date.toISOString().split('T')[0];
                                } else {
                                    // Para campos datetime-local
                                    const localDateTime = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
                                        .toISOString()
                                        .slice(0, 16);
                                    input.value = localDateTime;
                                }
                            }
                        } else {
                            input.value = task[dbField] || '';
                        }
                    }
                });
                
                // Cambiar texto del botón de guardar
                const saveBtn = form.querySelector('.save-btn');
                if (saveBtn) {
                    saveBtn.textContent = 'Actualizar Tarea';
                    saveBtn.classList.remove('btn-primary');
                    saveBtn.classList.add('btn-warning');
                }
                
                // Desplazar el viewport al formulario
                form.scrollIntoView({ behavior: 'smooth' });
                
                return;
            } catch (error) {
                showToast(error.message, 'error');
                console.error('Error:', error);
                return;
            }
        } else if (btn.classList.contains('save-btn')) {
            action = 'guardar';
            const formData = new FormData(form);
            formData.append('action', action);
            // Validar campos requeridos
            const requiredFields = ['taskName', 'processType', 'frequency', 'ruc', 'description', 'nextExecutionDate', 'nextExecutionTime'];
            for (const field of requiredFields) {
                if (!formData.get(field)) {
                    showToast(`El campo ${field} es obligatorio.`, 'error');
                    return;
                }
            }
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Error al guardar la tarea');
                }
                
                const result = await response.json();
                showToast(result.message);
                limpiarForm('frmTareas');
                return;
            } catch (error) {
                showToast(error.message, 'error');
                console.error('Error:', error);
                return;
            }
        }
    });
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast show align-items-center text-white bg-${type}`;
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function limpiarForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        form.taskId.value = ''; // Asegurar que el ID esté limpio
        const saveBtn = form.querySelector('.save-btn');
        if (saveBtn) {
            saveBtn.textContent = 'Guardar Tarea';
        }
    }
}