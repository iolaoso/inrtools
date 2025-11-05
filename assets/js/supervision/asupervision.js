// gestionFormularios.js

console.log("gestionFormularios.js Funcionando")

// Mapeo de estrategias a formularios
const estrategiaFormularios = {
    '1': ['sSupervisiones'], // Supervisión Preventiva
    '11': ['sSupervisiones','sCorrectivas'], // Supervisión Correctiva (usa el mismo formulario con diferentes campos)
    '22': ['sSupervisiones','sCorrectivas','sSupervisionPsi'], // PSI
    '35': ['sSupervisionPsi','sLevantamientoPsi'], // Levantamiento PSI
    '42': ['sSupervisionPsi','sSeguimientoPsi'], // Seguimiento PSI
    '48': ['sLiquidacion'], // Mecanismo de Resolución con PSI Ext
    '53': ['sSupervisionPsi','sLevantamientoPsi'], // Terminación PSI Extra Situ (usa Levantamiento PSI)
    '56': ['sAlertas'], // Alerta Preventiva
    '62': ['sAlertas'] // Alerta Correctiva
};


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

function manejarCambioEstrategia() {
    const estrategia = document.getElementById('estrategia').value;
    const estrategiaText = document.getElementById('estrategia').options[document.getElementById('estrategia').selectedIndex].text; 
    const selectFase = document.getElementById('fase');
    
    // Ocultar todos los formularios 
    ocultarTodosLosFormularios();
       
    //console.log(`Estrategia seleccionada: ${estrategia} - ${estrategiaText}`);

    if (estrategia !== '0') {
        // Actualizar opciones de fase (backend)
        actualizarOpcionesFase(estrategiaText);
                
        // Mostrar formulario correspondiente
        const formularioId = estrategiaFormularios[estrategia];
        if (formularioId) {
            mostrarFormulario(formularioId);
        }
        
    } else {
        // Si no hay estrategia seleccionada, ocultar todo
        if (selectFase) selectFase.innerHTML = '<option value="0">Seleccione...</option>';
        if (selectEstado) selectEstado.innerHTML = '<option value="">Seleccione...</option>';
    }
}

async function manejarCambioFase() {
    const faseId = document.getElementById('fase').value;
    const inputEstado = document.getElementById('estado_supervision');
    
    console.log(`Fase seleccionada: ${faseId}`);
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
        console.log('📡 URL de consulta:', url);
        
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
        console.log('Fases obtenidas:', fases);
        
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

function ocultarTodosLosFormularios() {
    const formularios = [
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
    // Si recibe un array, procesa múltiples formularios
    if (Array.isArray(formularioId)) {
        formularioId.forEach(id => {
            mostrarFormularioIndividual(id);
        });
        
        // Scroll suave al primer formulario del array
        if (formularioId.length > 0) {
            setTimeout(() => {
                const primerFormulario = document.getElementById(formularioId[0]);
                if (primerFormulario) {
                    primerFormulario.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 300);
        }
    } 
    // Si recibe un string, procesa un solo formulario
    else if (typeof formularioId === 'string') {
        mostrarFormularioIndividual(formularioId);
        
        // Scroll suave al formulario mostrado
        setTimeout(() => {
            const formulario = document.getElementById(formularioId);
            if (formulario) {
                formulario.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }, 300);
    }
}

// Función auxiliar para mostrar un formulario individual
function mostrarFormularioIndividual(formularioId) {
    const formulario = document.getElementById(formularioId);
    if (formulario) {
        formulario.style.display = 'block';
        console.log(`Formulario mostrado: ${formularioId}`);
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
        'frmSupervisiones',
        'frmCorrectivas',
        'frmSupervisionPsi', 
        'frmSeguimientoPsi',
        'frmLevantamientoPsi',
        'frmLiquidacion',
        'frmAlertas'
    ];
    
    formularios.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
        }
    });
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