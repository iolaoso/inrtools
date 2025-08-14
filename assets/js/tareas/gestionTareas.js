document.addEventListener('DOMContentLoaded', () => {
    console.log("Gestión de Tareas Funcionando.");
    
    // Manejo delegado de eventos para mejor performance
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
        
        const taskId = btn.dataset.id;
        let action, endpoint;
        
        if (btn.classList.contains('complete-btn')) {
            action = 'completar';
        } else if (btn.classList.contains('edit-btn')) {
            action = 'editar';
        } else if (btn.classList.contains('delete-btn')) {
            action = 'eliminar';
        } else {
            return;
        }
        
        try {
            const response = await fetch(`${baseurl}/backend/tareas/gestionTareasList.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: taskId, action })
            });
            
            const data = await response.json();
            
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Error en la operación');
            }
            
            showToast('Operación exitosa', 'success');
            setTimeout(() => location.reload(), 1000);
            
        } catch (error) {
            showToast(error.message, 'error');
            console.error('Error:', error);
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