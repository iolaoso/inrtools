// gestionFormularios.js

console.log("gestionFormularios.js Funcionando")

// Inicialización cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    inicializarGestionFormularios();
});

function inicializarGestionFormularios() {
    // Ocultar todos los formularios inicialmente
    ocultarTodosLosFormularios();
    
    // Configurar event listeners
    const selectEstrategia = document.getElementById('estrategia');
    const selectFase = document.getElementById('fase');
    
    if (selectEstrategia) {
        selectEstrategia.addEventListener('change', manejarCambioEstrategia);
    }
    
    if (selectFase) {
        selectFase.addEventListener('change', manejarCambioFase);
    }
    
    // Inicializar estado basado en selección actual
    if (selectEstrategia && selectEstrategia.value !== '0') {
        manejarCambioEstrategia();
    }
}


async function manejarCambioEstrategia() {
    const estrategiaSelect = document.getElementById('estrategia');
    const estrategia = estrategiaSelect.value;
    const estrategiaText = estrategiaSelect.options[estrategiaSelect.selectedIndex].text; 
    const selectFase = document.getElementById('fase');
    
    // Ocultar todo inmediatamente
    ocultarTodosLosFormularios();
    
    if (estrategia === '0') {
        selectFase.innerHTML = '<option value="0">Seleccione...</option>';
        return;
    }
    
    try {
        // Loading state
        selectFase.innerHTML = '<option value="0">Cargando...</option>';
        selectFase.disabled = true;
        
        // Ejecutar en secuencia
        await actualizarOpcionesFase(estrategiaText);
        
        const formularioId = estrategiaFormularios[estrategia];
        if (formularioId) {
            mostrarFormulario(formularioId);
        }
        
    } catch (error) {
        console.error('Error:', error);
        selectFase.innerHTML = '<option value="0">Error</option>';
    } finally {
        selectFase.disabled = false;
    }
}

async function manejarCambioFase() {
    const faseId = document.getElementById('fase').value;
    const inputEstado = document.getElementById('estado_supervision');
    
    //console.log(`Fase seleccionada: ${faseId}`);
    // Actualizar estados según la fase seleccionada (backend)
    const resultado = await actualizarEstadosPorFase(faseId);
    //console.log('Estados obtenidos:', resultado.estados); 
}

async function actualizarEstadosPorFase(faseId) {
    const inputEstado = document.getElementById('estadoSupervision');
    if (!inputEstado) return;
    // Mostrar loading
    inputEstado.value = '';
    inputEstado.placeholder = '🔄 Cargando estado...';
    inputEstado.disabled = true;
     try {
        
        const url = baseurl + `/backend/supervision/supervisionList.php?action=getEstados&faseId=${encodeURIComponent(faseId)}`;
        //console.log('📡 URL de consulta:', url);
        
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
        
        //console.log('✅ Datos de estado recibidos:', data);
        
        // Procesar la respuesta para input text
        if (data.success && data.estados && Array.isArray(data.estados) && data.estados.length > 0) {
            // Tomar el primer estado (o puedes implementar otra lógica)
            const primerEstado = data.estados[0];
            inputEstado.value = primerEstado.ESTADO_PROCESO || primerEstado.estado || '';
            // Si hay porcentaje de avance, actualizarlo también
            const inputPorcAvance = document.getElementById('porc_avance');
            if (inputPorcAvance && primerEstado.PORC_AVANCE) {
                inputPorcAvance.value = primerEstado.PORC_AVANCE;
            }            
        }
    } catch (error) {
        console.error('❌ Error al obtener estado:', error);
    } finally {
        inputEstado.disabled = false;
        if (inputEstado.value === '') {
            inputEstado.placeholder = 'Seleccione estado...';
        }
    }
}

async function actualizarOpcionesFase(estrategiaText) {
    const selectFase = document.getElementById('fase');
    if (!selectFase) return;
    
    // Mostrar loading
    selectFase.innerHTML = '<option value="0">Cargando...</option>';
    selectFase.disabled = true;

    try {
        const fases = await obtenerFasesPorEstrategia(estrategiaText);
        //console.log('Fases obtenidas:', fases);
        
        selectFase.innerHTML = '<option value="0">Seleccione...</option>';
        
        // Agregar fases al select
        fases.fases.forEach(fase => {
            selectFase.innerHTML += `<option value="${fase.ID}">${fase.FASE}</option>`;
        }); 
        
    } catch (error) {
        console.error('Error:', error);
        selectFase.innerHTML = '<option value="0">Error cargando fases</option>';
    } finally {
        selectFase.disabled = false;
    }
    
    
}

async function obtenerFasesPorEstrategia(estrategiaText) {
    try {
        const url = baseurl + `/backend/supervision/supervisionList.php?action=getFases&estrategiaText=${estrategiaText}`;
        
        //console.log('Obteniendo fases desde:', url);
        const response = await fetch(url);
        const data = await response.json();
        return {
            success: true,
            fases: data.fases || [],
            estrategia: estrategiaText
        };
    } catch (error) {
        console.error('Error al obtener fases:', error);
        return {
            success: false,
            fases: [],
            error: error.message,
            estrategia: estrategiaText
        };
    }
}



/* ************************************************************* */
/* ************************************************************* */
/* logica para mostrar u ocultar los formularios  */

// Mapeo que seccion mostrar en cada caso
const estrategiaFormularios = {
    '1': ['sSupervisiones'], // Supervisión Preventiva
    '11': ['sSupervisiones','sCorrectivas'], // Supervisión Correctiva (usa el mismo formulario con diferentes campos)
    '22': ['sSupervisiones','sCorrectivas','sSupervisionPsi'], // PSI
    '35': ['sSupervisiones','sCorrectivas','sSupervisionPsi','sSeguimientoPsi','sLevantamientoPsi'], // Levantamiento PSI
    '42': ['sSupervisiones','sCorrectivas','sSupervisionPsi','sSeguimientoPsi'], // Seguimiento PSI
    '48': ['sSupervisiones','sCorrectivas','sLiquidacion'], // Mecanismo de Resolución con PSI Ext
    '53': ['sSupervisiones','sCorrectivas','sSupervisionPsi','sSeguimientoPsi','sLevantamientoPsi'], // Terminación PSI Extra Situ (usa Levantamiento PSI)
    '56': ['sAlertas'], // Alerta Preventiva
    '62': ['sAlertas'] // Alerta Correctiva
};

function ocultarTodosLosFormularios() {
    const formularios = [
        'sAvancesSupervision',
        'sSupervisiones',
        'sCorrectivas', 
        'sSupervisionPsi',
        'sSeguimientoPsi',
        'sLevantamientoPsi',
        'sLiquidacion',
        'sAlertas'
    ];
    
    formularios.forEach(id => {
        const formulario = document.getElementById(id);
        if (formulario) {
            formulario.style.display = 'none';
        }
    });
}

function mostrarFormulario(formularioId) {
     mostrarFormularioIndividual('sAvancesSupervision'); // Mostrar siempre el formulario de avances    
    // Si recibe un array, procesa múltiples formularios
    if (Array.isArray(formularioId)) {
        formularioId.forEach(id => {
            mostrarFormularioIndividual(id);
        });
    } 
    // Si recibe un string, procesa un solo formulario
    else if (typeof formularioId === 'string') {
        mostrarFormularioIndividual(formularioId);
    }

    // APLICAR REQUIRED DINÁMICOS después de mostrar los formularios
    aplicarRequiredDinamicos();
}

// Función auxiliar para mostrar un formulario individual
function mostrarFormularioIndividual(formularioId) {
    const formulario = document.getElementById(formularioId);
    if (formulario) {
        formulario.style.display = 'block';
        //console.log(`Formulario mostrado: ${formularioId}`);

        if (formularioId == 'sAlertas') {
        const idEstrategia = document.getElementById('estrategia').value
        if (idEstrategia == '56' || idEstrategia == '62') { // tipo de alerta
            document.getElementById('tipo_alerta').value = 'ALERTA ' + (idEstrategia === '56' ? 'PREVENTIVA' : 'CORRECTIVA');
        }

    }

    }
}

function mostrarSeccionEstado() {
    const seccionEstado = document.querySelector('#estado_supervision')?.closest('.seccion-formulario');
    if (seccionEstado) {
        seccionEstado.style.display = 'block';
    }
}

function ocultarSeccionEstado() {
    const seccionEstado = document.querySelector('#estado_supervision')?.closest('.seccion-formulario');
    if (seccionEstado) {
        seccionEstado.style.display = 'none';
    }
}

// Función para limpiar todos los formularios
function limpiarTodosLosFormularios() {
    const formularios = [
        'frmDatosFull'
    ];
    
    formularios.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
        }
    });
    ocultarTodosLosFormularios();
}

// Función para obtener la estrategia actual seleccionada
function getEstrategiaActual() {
    return document.getElementById('estrategia').value;
}

// Función para obtener el formulario activo actual
function getFormularioActivo() {
    const estrategia = getEstrategiaActual();
    return estrategiaFormularios[estrategia] || null;
}

// Exportar funciones para uso global
window.gestionFormularios = {
    inicializar: inicializarGestionFormularios,
    limpiarTodos: limpiarTodosLosFormularios,
    getEstrategiaActual: getEstrategiaActual,
    getFormularioActivo: getFormularioActivo
};