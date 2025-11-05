// buscarSupervisiones.js
console.log('buscarSupervisiones.js cargado');

// Función para abrir el modal de búsqueda
function abrirBuscarSupervisionModal() {
    const ruc = document.getElementById('ruc').value;
    const razonSocial = document.getElementById('tbrazonSocial').value;
    
    if (!ruc) {
        mostrarAlerta('Por favor ingrese un RUC primero', 'warning');
        return;
    }
    
    // Actualizar información en el modal
    document.getElementById('modalRuc').textContent = ruc;
    document.getElementById('modalRazonSocial').textContent = razonSocial || 'No disponible';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('buscarSupervisionModal'));
    modal.show();
}

// Función para buscar supervisiones por RUC
function buscarSupervisionesPorRuc() {
    const ruc = document.getElementById('ruc').value;
    
    if (!ruc) {
        mostrarAlerta('No hay RUC especificado', 'error');
        return;
    }
    
    // Mostrar loading
    const tbody = document.getElementById('tbodySupervisiones');
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Buscando supervisiones...</span>
                </div>
                <p class="mt-2 mb-0">Buscando supervisiones...</p>
            </td>
        </tr>
    `;
    
    // Simular búsqueda (en producción sería una llamada AJAX)
    setTimeout(() => {
        // Datos de ejemplo - en producción vendrían del servidor
        const supervisionesEjemplo = [
            {
                id: 'AV-2024-001',
                estrategia: 'Supervisión Preventiva',
                fase: '2. Oficio solicitud de información',
                estado: 'En proceso'
            },
            {
                id: 'AV-2024-002', 
                estrategia: 'Supervisión Correctiva',
                fase: '1. Evaluación preliminar',
                estado: 'No iniciada'
            },
            {
                id: 'AV-2023-045',
                estrategia: 'PSI',
                fase: '7. Resolución PSI',
                estado: 'En proceso'
            }
        ];
        
        mostrarResultadosSupervisiones(supervisionesEjemplo);
    }, 1500);
}

// Función para mostrar resultados en la tabla
function mostrarResultadosSupervisiones(supervisiones) {
    const tbody = document.getElementById('tbodySupervisiones');
    
    if (supervisiones.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No se encontraron supervisiones para este RUC
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    supervisiones.forEach((supervision, index) => {
        html += `
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input supervision-checkbox" 
                               type="radio" 
                               name="supervisionSeleccionada" 
                               value="${supervision.id}"
                               id="supervision_${index}">
                        <label class="form-check-label" for="supervision_${index}"></label>
                    </div>
                </td>
                <td>
                    <strong>${supervision.id}</strong>
                </td>
                <td>${supervision.estrategia}</td>
                <td>${supervision.fase}</td>
                <td>
                    <span class="badge ${obtenerClaseEstado(supervision.estado)}">
                        ${supervision.estado}
                    </span>
                </td>
                <td>
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary"
                            onclick="seleccionarSupervision('${supervision.id}')">
                        <i class="fas fa-check me-1"></i> Seleccionar
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Función para obtener clase CSS según el estado
function obtenerClaseEstado(estado) {
    const clases = {
        'En proceso': 'bg-warning text-dark',
        'No iniciada': 'bg-secondary',
        'Cerrado': 'bg-success',
        'Suspendido': 'bg-danger',
        'Pendiente': 'bg-info'
    };
    return clases[estado] || 'bg-secondary';
}

// Función para seleccionar una supervisión
function seleccionarSupervision(idSupervision) {
    // Buscar la supervisión seleccionada
    const supervisionCheckbox = document.querySelector(`input[value="${idSupervision}"]`);
    if (supervisionCheckbox) {
        supervisionCheckbox.checked = true;
        confirmarSeleccionSupervision(idSupervision);
    }
}

// Función para confirmar la selección
function confirmarSeleccionSupervision(idSupervision) {
    // Aquí cargarías los datos de la supervisión seleccionada
    console.log('Supervisión seleccionada:', idSupervision);
    
    // Cerrar el modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('buscarSupervisionModal'));
    modal.hide();
    
    // Mostrar mensaje de confirmación
    mostrarAlerta(`Supervisión ${idSupervision} seleccionada correctamente`, 'success');
    
    // Aquí podrías cargar los datos de la supervisión en el formulario principal
    cargarDatosSupervision(idSupervision);
}

// Función para cargar datos de la supervisión seleccionada
function cargarDatosSupervision(idSupervision) {
    // En producción, harías una llamada AJAX para obtener los datos completos
    console.log('Cargando datos de la supervisión:', idSupervision);
    
    // Ejemplo de cómo podrías actualizar el formulario principal
    // document.getElementById('id_avances').value = idSupervision;
    // ... otros campos
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Configurar el botón de búsqueda en el formulario principal
    const btnBuscarSupervisiones = document.querySelector('button[onclick="buscarSupervisiones()"]');
    if (btnBuscarSupervisiones) {
        btnBuscarSupervisiones.setAttribute('onclick', 'abrirBuscarSupervisionModal()');
        btnBuscarSupervisiones.innerHTML = '<i class="fas fa-search me-1"></i> Buscar Supervisiones';
    }
});