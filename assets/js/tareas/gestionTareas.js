console.log("Gestión de Tareas Funcionando.");

// Mejor práctica: Manejar eventos con addEventListener
document.querySelectorAll('.complete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const taskId = this.getAttribute('data-id');
        marcarComoCompletada(taskId);
    });
});

function marcarComoCompletada(taskId) {
    console.log(`Marcando tarea ${taskId} como completada...`);
    const url = `${baseurl}/backend/tareas/gestionTareasList.php?id=${encodeURIComponent(taskId)}&action=complete`;
    fetch(url)
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload(); // O actualizar solo la fila
        } else {
            alert('Error: ' + (data.message || 'No se pudo completar'));
        }
    })
    .catch(error => console.error('Error:', error)); 
}