/* FORMA DE USO DE LA FUNCION 
'success' | 'danger' | 'info' | 'primary' | 'warning'

// Mostrar mensaje de confirmación
mostrarAlerta(`Supervisión ${idSupervision} seleccionada correctamente`, 'success');  

*/

// Función para mostrar alertas
/* 

function mostrarAlerta(mensaje, tipo) {
    // Usar Toast de Bootstrap o alerta simple
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alerta);
    
    setTimeout(() => {
        alerta.remove();
    }, 5000);
}
 */


function mostrarAlerta(mensaje, tipo) {
    // Crear contenedor de alertas si no existe
    let alertContainer = document.getElementById('alert-container');
    
    /* if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 90%;
            max-width: 600px;
        `;
        document.body.appendChild(alertContainer);
    } */
    
    // Crear la alerta
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show mb-2`;
    alerta.style.cssText = `
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    `;
    alerta.innerHTML = `
        <div class="d-flex align-items-center">
            <span class="flex-grow-1">${mensaje}</span>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Agregar la alerta al contenedor (al inicio para que aparezca arriba)
    alertContainer.insertBefore(alerta, alertContainer.firstChild);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 5000);
}