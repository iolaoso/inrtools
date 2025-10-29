// gestionFormularios.js

console.log("gestionFormularios.js Funcionando")

// Mapeo de estrategias a formularios
const estrategiaFormularios = {
    '1': ['sSupervisiones'], // Supervisión Preventiva
    '2': ['sSupervisiones','sCorrectivas'], // Supervisión Correctiva (usa el mismo formulario con diferentes campos)
    '3': ['sSupervisiones','sCorrectivas','sSupervisionPsi'], // PSI
    '4': ['sSupervisionPsi','sLevantamientoPsi'], // Levantamiento PSI
    '5': ['sSupervisionPsi','sSeguimientoPsi'], // Seguimiento PSI
    '6': ['sLiquidacion'], // Mecanismo de Resolución con PSI Ext
    '7': ['sSupervisionPsi','sLevantamientoPsi'], // Terminación PSI Extra Situ (usa Levantamiento PSI)
    '8': ['sAlertas'], // Alerta Preventiva
    '9': ['sAlertas'] // Alerta Correctiva
};

// Estados disponibles por fase/estrategia
const estadosPorEstrategia = {
    '1': ['EN PROCESO', 'CERRADO', 'PENDIENTE'], // Preventiva
    '2': ['EN PROCESO', 'CERRADO', 'PENDIENTE', 'CON OBSERVACIONES'], // Correctiva
    '3': ['EN EJECUCIÓN', 'SUSPENDIDO', 'LEVANTADO'], // PSI
    '4': ['SOLICITADO', 'EN PROCESO', 'APROBADO'], // Levantamiento PSI
    '5': ['EN SEGUIMIENTO', 'COMPLETADO'], // Seguimiento PSI
    '6': ['EN NEGOCIACIÓN', 'RESUELTO'], // Mecanismo Resolución
    '7': ['SOLICITADO', 'APROBADO'], // Terminación PSI
    '8': ['ACTIVA', 'ATENDIDA'], // Alerta Preventiva
    '9': ['ACTIVA', 'ATENDIDA'] // Alerta Correctiva
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
    const selectFase = document.getElementById('fase');
    const selectEstado = document.getElementById('estado_supervision');
    
    // Ocultar todos los formularios primero
    ocultarTodosLosFormularios();
    
    // Mostrar sección de estado si existe
    if (selectEstado) {
        const seccionEstado = selectEstado.closest('.seccion-formulario');
        if (seccionEstado) {
            seccionEstado.style.display = 'block';
        }
    }
    
    if (estrategia !== '0') {
        // Actualizar opciones de fase (simuladas - en producción vendrían del backend)
        actualizarOpcionesFase(estrategia);
        
        // Actualizar opciones de estado
        actualizarEstadosSupervision(estrategia);
        
        // Mostrar formulario correspondiente
        const formularioId = estrategiaFormularios[estrategia];
        if (formularioId) {
            mostrarFormulario(formularioId);
        }
        
        // Mostrar sección de estado
        mostrarSeccionEstado();
    } else {
        // Si no hay estrategia seleccionada, ocultar todo
        if (selectFase) selectFase.innerHTML = '<option value="0">Seleccione...</option>';
        if (selectEstado) selectEstado.innerHTML = '<option value="">Seleccione...</option>';
        ocultarSeccionEstado();
    }
}

function manejarCambioFase() {
    const fase = document.getElementById('fase').value;
    const estrategia = document.getElementById('estrategia').value;
    
    // Aquí puedes agregar lógica adicional basada en la fase seleccionada
    if (fase !== '0' && estrategia !== '0') {
        console.log(`Estrategia: ${estrategia}, Fase: ${fase}`);
        // Puedes agregar más lógica específica por fase aquí
    }
}

function actualizarOpcionesFase(estrategia) {
    const selectFase = document.getElementById('fase');
    if (!selectFase) return;
    
    // Limpiar opciones actuales
    selectFase.innerHTML = '<option value="0">Seleccione...</option>';
    
    // Agregar opciones basadas en la estrategia (simuladas)
    const fases = obtenerFasesPorEstrategia(estrategia);
    
    fases.forEach(fase => {
        const option = document.createElement('option');
        option.value = fase.id;
        option.textContent = fase.nombre;
        selectFase.appendChild(option);
    });
}

function actualizarEstadosSupervision(estrategia) {
    const selectEstado = document.getElementById('estado_supervision');
    if (!selectEstado) return;
    
    // Limpiar opciones actuales
    selectEstado.innerHTML = '<option value="">Seleccione...</option>';
    
    // Agregar estados correspondientes a la estrategia
    const estados = estadosPorEstrategia[estrategia] || ['EN PROCESO', 'CERRADO'];
    
    estados.forEach(estado => {
        const option = document.createElement('option');
        option.value = estado;
        option.textContent = estado;
        selectEstado.appendChild(option);
    });
}

function obtenerFasesPorEstrategia(estrategia) {
    // Datos simulados - en producción vendrían del backend
    const fasesPorEstrategia = {
        '1': [ // Preventiva
            { id: '1', nombre: 'Planificación' },
            { id: '2', nombre: 'Ejecución' },
            { id: '3', nombre: 'Informe Final' }
        ],
        '2': [ // Correctiva
            { id: '1', nombre: 'Investigación' },
            { id: '2', nombre: 'Análisis' },
            { id: '3', nombre: 'Resolución' }
        ],
        '3': [ // PSI
            { id: '1', nombre: 'Imposición' },
            { id: '2', nombre: 'Seguimiento' },
            { id: '3', nombre: 'Levantamiento' }
        ],
        '4': [ // Levantamiento PSI
            { id: '1', nombre: 'Solicitud' },
            { id: '2', nombre: 'Evaluación' },
            { id: '3', nombre: 'Aprobación' }
        ],
        '5': [ // Seguimiento PSI
            { id: '1', nombre: 'Monitoreo' },
            { id: '2', nombre: 'Verificación' },
            { id: '3', nombre: 'Informe' }
        ],
        '6': [ // Mecanismo Resolución
            { id: '1', nombre: 'Negociación' },
            { id: '2', nombre: 'Acuerdo' },
            { id: '3', nombre: 'Implementación' }
        ],
        '7': [ // Terminación PSI
            { id: '1', nombre: 'Solicitud' },
            { id: '2', nombre: 'Revisión' },
            { id: '3', nombre: 'Aprobación' }
        ],
        '8': [ // Alerta Preventiva
            { id: '1', nombre: 'Detección' },
            { id: '2', nombre: 'Notificación' },
            { id: '3', nombre: 'Seguimiento' }
        ],
        '9': [ // Alerta Correctiva
            { id: '1', nombre: 'Identificación' },
            { id: '2', nombre: 'Corrección' },
            { id: '3', nombre: 'Verificación' }
        ]
    };
    
    return fasesPorEstrategia[estrategia] || [{ id: '1', nombre: 'Fase General' }];
}

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