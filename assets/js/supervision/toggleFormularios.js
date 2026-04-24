// toggleFormularios.js - Funcionalidad para expandir y contraer formularios

// Función para inicializar los botones de expandir/contraer
function inicializarToggleFormularios() {
    // Agregar event listeners a todos los botones de toggle
    document.querySelectorAll('.toggle-formulario').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            toggleFormulario(targetId, this);
        });
    });
    
    // Inicializar todos los formularios como expandidos
    document.querySelectorAll('[id^="s"]').forEach(formulario => {
        const cardBody = formulario.querySelector('.card-body');
        if (cardBody) {
            cardBody.classList.remove('collapsed');
        }
    });
}

// Función para expandir/contraer un formulario específico
function toggleFormulario(formularioId, button) {
    const formulario = document.getElementById(formularioId);
    if (!formulario) return;
    
    const cardBody = formulario.querySelector('.card-body');
    if (!cardBody) return;
    
    const isCollapsed = cardBody.classList.contains('collapsed');
    
    if (isCollapsed) {
        // Expandir
        cardBody.classList.remove('collapsed');
        if (button) {
            button.classList.remove('collapsed');
        }
        console.log(`Formulario expandido: ${formularioId}`);
    } else {
        // Contraer
        cardBody.classList.add('collapsed');
        if (button) {
            button.classList.add('collapsed');
        }
        console.log(`Formulario contraído: ${formularioId}`);
    }
}

// Función para expandir un formulario específico
function expandirFormulario(formularioId) {
    const formulario = document.getElementById(formularioId);
    if (!formulario) return;
    
    const cardBody = formulario.querySelector('.card-body');
    const toggleButton = formulario.querySelector('.toggle-formulario');
    
    if (cardBody) {
        cardBody.classList.remove('collapsed');
    }
    if (toggleButton) {
        toggleButton.classList.remove('collapsed');
    }
}

// Función para contraer un formulario específico
function contraerFormulario(formularioId) {
    const formulario = document.getElementById(formularioId);
    if (!formulario) return;
    
    const cardBody = formulario.querySelector('.card-body');
    const toggleButton = formulario.querySelector('.toggle-formulario');
    
    if (cardBody) {
        cardBody.classList.add('collapsed');
    }
    if (toggleButton) {
        toggleButton.classList.add('collapsed');
    }
}

// Función para expandir todos los formularios visibles
function expandirTodosFormularios() {
    document.querySelectorAll('[id^="s"]').forEach(formulario => {
        if (formulario.style.display !== 'none') {
            expandirFormulario(formulario.id);
        }
    });
    console.log('Todos los formularios visibles expandidos');
}

// Función para contraer todos los formularios
function contraerTodosFormularios() {
    document.querySelectorAll('[id^="s"]').forEach(formulario => {
        if(formulario.id !== 'sAvancesSupervision' && formulario.id !== 'sDatosEntidad') { // Mantener el formulario de Avances de Supervisión expandido
            contraerFormulario(formulario.id);
        }
    });
    console.log('Todos los formularios contraídos');
}

// Función para expandir solo los formularios activos
function expandirFormulariosActivos() {
    // Obtener formularios activos desde la gestión principal si está disponible
    let formulariosActivos = [];
    
    if (window.gestionFormularios && typeof window.gestionFormularios.getFormulariosActivos === 'function') {
        formulariosActivos = window.gestionFormularios.getFormulariosActivos();
    } else {
        // Fallback: obtener formularios visibles
        document.querySelectorAll('[id^="s"]').forEach(formulario => {
            if (formulario.style.display !== 'none') {
                formulariosActivos.push(formulario.id);
            }
        });
    }
    
    formulariosActivos.forEach(formularioId => {
        expandirFormulario(formularioId);
    });
    
    console.log('Formularios activos expandidos:', formulariosActivos);
}

/* // Función para agregar controles globales de expandir/contraer
function agregarControlesGlobales() {
    // Verificar si los controles ya existen
    if (document.getElementById('controlesGlobalesFormularios')) {
        return;
    }
    
    const controlesHTML = `
        <div class="row mb-3" id="controlesGlobalesFormularios">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" id="expandirTodos">
                                <i class="fas fa-expand-arrows-alt me-1"></i> Expandir Todos
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="contraerTodos">
                                <i class="fas fa-compress-arrows-alt me-1"></i> Contraer Todos
                            </button>
                            <button type="button" class="btn btn-outline-info" id="expandirActivos">
                                <i class="fas fa-eye me-1"></i> Expandir Activos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Insertar después del primer formulario (Avances de Supervisión)
    const primerFormulario = document.getElementById('sAvancesSupervision');
    if (primerFormulario) {
        primerFormulario.insertAdjacentHTML('afterend', controlesHTML);
        
        // Agregar event listeners a los botones globales
        document.getElementById('expandirTodos').addEventListener('click', expandirTodosFormularios);
        document.getElementById('contraerTodos').addEventListener('click', contraerTodosFormularios);
        document.getElementById('expandirActivos').addEventListener('click', expandirFormulariosActivos);
        
        console.log('Controles globales agregados');
    }
}
 */

// Función para actualizar el estado de los botones toggle después de mostrar formularios
function actualizarToggleFormularios() {
    // Asegurarse de que los formularios visibles estén expandidos
    document.querySelectorAll('[id^="s"]').forEach(formulario => {
        if (formulario.style.display !== 'none') {
            expandirFormulario(formulario.id);
        }
    });
}

// Inicialización automática cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño delay para asegurar que otros scripts se hayan cargado
    setTimeout(() => {
        inicializarToggleFormularios();
        //agregarControlesGlobales();
    }, 100);
});

// Exportar funciones para uso global
window.toggleFormularios = {
    inicializar: inicializarToggleFormularios,
    toggle: toggleFormulario,
    expandir: expandirFormulario,
    contraer: contraerFormulario,
    expandirTodos: expandirTodosFormularios,
    contraerTodos: contraerTodosFormularios,
    expandirActivos: expandirFormulariosActivos,
    //agregarControles: agregarControlesGlobales,
    actualizar: actualizarToggleFormularios
};