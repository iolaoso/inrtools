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
async function buscarSupervisionesPorRuc() {
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
    
    try {
        // Llamada real al servidor
        const url = baseurl + `/backend/supervision/supervisionList.php?action=buscarPorRuc&ruc=${encodeURIComponent(ruc)}`;
        //console.log('🔍 Buscando supervisiones en:', url);
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Error del servidor: ${response.status} ${response.statusText}`);
        }
        
        const data = await response.json();
        //console.log('📊 Datos recibidos:', data);
        
        if (data.success) {
            if (data.supervisiones && data.supervisiones.length > 0) {
                mostrarResultadosSupervisiones(data.supervisiones);
                mostrarAlerta(`Se encontraron ${data.supervisiones.length} supervisiones`, 'success');
            } else {
                mostrarResultadosSupervisiones([]);
                mostrarAlerta('No se encontraron supervisiones para este RUC', 'info');
            }
        } else {
            throw new Error(data.error || 'Error en la búsqueda');
        }
        
    } catch (error) {
        console.error('❌ Error en búsqueda:', error);
        mostrarResultadosSupervisiones([]);
        mostrarAlerta('Error al buscar supervisiones: ' + error.message, 'error');
    }
}


// Función para mostrar resultados en la tabla
function mostrarResultadosSupervisiones(supervisiones) {
    const tbody = document.getElementById('tbodySupervisiones');
    
    if (!supervisiones || supervisiones.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No se encontraron supervisiones
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
                    <strong>${supervision.id || 'N/A'}</strong>
                </td>
                <td>${supervision.estrategia || 'No especificado'}</td>
                <td>${supervision.fase || 'No especificado'}</td>
                <td>
                    <span class="btn btn-sm ${obtenerClaseEstado(supervision.estado)} text-center w-100">
                        ${supervision.estado || 'Desconocido'}
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
        'No Iniciada': 'bg-secondary',
        'En Proceso': 'bg-warning text-dark',
        'Cerrado': 'bg-success',
        
        'Completado': 'bg-success',
        'Suspendido': 'bg-danger',
        'Pendiente': 'bg-info',
        'En revisión': 'bg-primary',
        'Aprobado': 'bg-success'
    };
    return clases[estado] || 'bg-secondary';
}

// Función para seleccionar una supervisión
function seleccionarSupervision(idSupervision) {
    //console.log('Supervisión seleccionada:', idSupervision);
    // Mostrar mensaje de confirmación
    mostrarAlerta(`Supervisión ${idSupervision} seleccionada correctamente`, 'success');
    // Aquí podrías cargar los datos de la supervisión en el formulario principal
    cargarDatosSupervision(idSupervision);
    // cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('buscarSupervisionModal'));
    modal.hide();
}

// Función para cargar datos de la supervisión seleccionada
async function cargarDatosSupervision(supervisionId) {
    // En producción, harías una llamada AJAX para obtener los datos completos
    console.log('Cargando datos de la supervisión:', supervisionId);
    // Ejemplo de cómo podrías actualizar el formulario principal
    document.getElementById('id_avances').value = supervisionId;
    try {
        const url = baseurl + `/backend/supervision/supervisionList.php?action=getSupervisionData&supervisionId=${encodeURIComponent(supervisionId)}`;       
        const response = await fetch(url);
        const responseText = await response.text();
        
        // Verificar si la respuesta está vacía
        if (!responseText.trim()) {
            throw new Error('El servidor devolvió una respuesta vacía');
        }
        
        // Intentar parsear JSON
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('❌ Error parseando JSON:', parseError);
            throw new Error('Error parseando la respuesta del servidor');
        }
        
        console.log('✅ Datos de estado recibidos:', data);
        
        // Procesar la respuesta para input text
        if (data.success && data.supervision && Array.isArray(data.supervision) && data.supervision.length > 0) {
            // Tomar el primer estado (o puedes implementar otra lógica)
            const supevisonData = data.supervision[0];
            document.getElementById('cod_unico_avances').value = supevisonData.COD_UNICO;
            document.getElementById('IDcentral').value = supevisonData.ID;
            await establecerValorSelectPorTexto('estrategia', supevisonData.ESTRATEGIA); 
            await establecerValorSelect('trim_plan', supevisonData.TRIM_PLAN);
            await establecerValorSelectPorTexto('analistaSelect', supevisonData.USR_NOMBRE);
            await establecerValorSelect('fase', supevisonData.CATALOGO_ID);
            document.getElementById('estadoSupervision').value = supevisonData.ESTADO_PROCESO;
            document.getElementById('analista').value = supevisonData.NICKNAME;
            document.getElementById('fec_asig').value = supevisonData.FEC_ASIG;
            document.getElementById('anio_plan').value = supevisonData.ANIO_PLAN;
           //RELLENAR LOS CAMPOS QUE FALTAN AQUÍ
            document.getElementById('observaciones').value = supevisonData.OBSERVACIONES || '';
            


            
        }
    } catch (error) {
        console.error('❌ Error al obtener estado:', error);
    } finally {    
    // ... otros campos
        mostrarAlerta('Datos de la supervisión cargados correctamente', 'success');
    }
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