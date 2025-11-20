// buscarSupervisiones.js


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
    //limpiar resultados previos
    mostrarResultadosSupervisiones([]);
}

// Función para buscar supervisiones por RUC
async function buscarSupervisionesPorRuc() {
    const ruc = document.getElementById('ruc').value;
    
    if (!ruc) {
        mostrarAlerta('No hay RUC especificado', 'error');
        return;
    }
    
    // Mostrar loading
    const tbodySup = document.getElementById('tbodySupervisiones');
    tbodySup.innerHTML = `
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
                //mostrarAlerta(`Se encontraron ${data.supervisiones.length} supervisiones`, 'success');
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
    //console.log('Mostrando supervisiones:', supervisiones);
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
                <td>${supervision.catalogo_id || 'N/A'}</td>
                <td>${supervision.estrategia || 'No especificado'}</td>
                <td>${supervision.fase || 'No especificado'}</td>
                <td>
                    <button class="btn btn-sm ${obtenerClaseEstado(supervision.estado)} text-center text-white w-100" disabled>
                        ${supervision.estado || 'Desconocido'}
                    </button>
                </td>
                <td>
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary"
                            onclick="seleccionarSupervision('${supervision.id}', '${supervision.catalogo_id}')">
                        <i class="fas fa-check me-1"></i> Seleccionar
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Función para obtener la clase CSS según el estado
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
function seleccionarSupervision(idSupervision, catalogoId) {
    //console.log('Supervisión seleccionada:', idSupervision , "\nFase:", catalogoId);
    const idFase = parseInt(catalogoId);
    if (idFase>=56 && idFase>=67){
        //console.log('Alerta:', idSupervision, 'en fase:', idFase);
        // Mostrar mensaje de confirmación
        mostrarAlerta(`Alerta ${idSupervision} seleccionada en Fase: ${idFase}`, 'success');    
        cargarDatosAlerta(idSupervision);
    } else {
        // Mostrar mensaje de confirmación
        //console.log('Supervisión:', idSupervision, 'en fase:', idFase);
        //mostrarAlerta(`Supervisión ${idSupervision} seleccionada en Fase: ${idFase}`, 'success');
        // Aquí podrías cargar los datos de la supervisión en el formulario principal
        cargarDatosSupervision(idSupervision);
    }
    // cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('buscarSupervisionModal'));
    modal.hide();
}

// Función para cargar llenar los datos de la supervisión seleccionada
async function cargarDatosSupervision(supervisionId) {
    // En producción, harías una llamada AJAX para obtener los datos completos
    //console.log('Cargando datos de la supervisión:', supervisionId);
    
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
        
        //console.log('✅ Datos de recibidos:', data);
        
        // Procesar la respuesta para input text
        if (data.success && data.supervision && Array.isArray(data.supervision) && data.supervision.length > 0) {
            // Tomar el primer estado (o puedes implementar otra lógica)

            const supevisonData = data.supervision[0];
            document.getElementById('id_avances').value = supervisionId;
            document.getElementById('cod_unico_avances').value = supevisonData.COD_UNICO;
            document.getElementById('IDcentral').value = supevisonData.ID;
            await establecerValorSelectPorTexto('estrategia', supevisonData.ESTRATEGIA); 
            await establecerValorSelect('trim_plan', supevisonData.TRIM_PLAN);
            await establecerValorSelectPorTexto('analistaSelect', supevisonData.USR_NOMBRE);
            await establecerValorSelect('fase', supevisonData.CATALOGO_ID);
            document.getElementById('estadoSupervision').value = supevisonData.ESTADO_PROCESO;
            document.getElementById('analista').value = supevisonData.RESPONSABLE;
            document.getElementById('fec_asig').value = supevisonData.FEC_ASIG;
            document.getElementById('anio_plan').value = supevisonData.ANIO_PLAN;
            document.getElementById('porc_avance').value = supevisonData.PORC_AVANCE;
            // campos de supervisión
            document.getElementById('id_supervision').value = supevisonData.SUP_ID;
            document.getElementById('fec_solicitud').value = supevisonData.FEC_SOLICITUD;
            document.getElementById('num_oficio_solicitud').value = supevisonData.NUM_OFICIO_SOLICITUD;
            document.getElementById('fec_insistencia').value = supevisonData.FEC_INSISTENCIA;
            document.getElementById('num_oficio_insistencia').value = supevisonData.NUM_OFICIO_INSISTENCIA;
            document.getElementById('fec_comunicacion').value = supevisonData.FEC_COMUNICACION;
            document.getElementById('num_oficio_resultados').value = supevisonData.NUM_OFICIO_RESULTADOS;
            document.getElementById('fec_limite_entrega').value = supevisonData.FEC_LIMITE_ENTREGA;
            document.getElementById('fec_respuesta').value = supevisonData.FEC_RESPUESTA;
            document.getElementById('num_oficio_respuesta').value = supevisonData.NUM_OFICIO_RESPUESTA;
            document.getElementById('fec_informe_final').value = supevisonData.FEC_INFORME_FINAL;
            document.getElementById('informe_final').value = supevisonData.NUM_INFORME_FINAL;
            document.getElementById('fec_comunicacion_final').value = supevisonData.FEC_COMUNICACION_FINAL;
            document.getElementById('fec_limite_plan_accion').value = supevisonData.FEC_LIMITE_PLAN_ACCION;
            document.getElementById('fec_insistencia_plan_accion').value = supevisonData.FEC_INSISTENCIA_PLAN_ACCION;
            document.getElementById('num_comunicacion_final').value = supevisonData.NUM_COMUNICACION_FINAL;           
            document.getElementById('num_insistencia_plan_accion').value = supevisonData.NUM_INSISTENCIA_PLAN_ACCION;
            document.getElementById('fec_aprobacion_plan_accion').value = supevisonData.FEC_APROBACION_PLAN_ACCION;
            document.getElementById('sancion').value = supevisonData.SANCION;
            // campos de correctivas
            document.getElementById('id_correctiva').value = supevisonData.COR_ID;
            document.getElementById('fec_reunion_comunicacion_resultados').value = supevisonData.FEC_REUNION_COMUNICACION_RESULTADOS;
            document.getElementById('fec_aprobacion_pa_fisico').value = supevisonData.FEC_APROBACION_PA_FISICO;
            document.getElementById('num_aprobacion_pa_fisico').value = supevisonData.NUM_APROBACION_PA_FISICO;
            document.getElementById('fec_aprobacion_pa_sistema').value = supevisonData.FEC_APROBACION_PA_SISTEMA;
            // campos de PSI
            document.getElementById('id_supervision_psi').value = supevisonData.PSI_ID;
            document.getElementById('fec_resolucion_psi').value = supevisonData.FEC_RESOLUCION_PSI;
            document.getElementById('num_resolucion_psi').value = supevisonData.NUM_RESOLUCION_PSI;
            document.getElementById('fec_imposicion_psi').value = supevisonData.FEC_IMPOSICION_PSI;
            document.getElementById('num_oficio_imposicion_psi').value = supevisonData.NUM_OFICIO_IMPOSICION_PSI;
            document.getElementById('fec_fin_psi').value = supevisonData.FEC_FIN_PSI;
            document.getElementById('fec_memorando_comunicacion_psi').value = supevisonData.FEC_MEMORANDO_COMUNICACION_PSI;
            document.getElementById('num_memorando_comunicacion_psi').value = supevisonData.NUM_MEMORANDO_COMUNICACION_PSI;
            document.getElementById('fec_ampliacion_psi').value = supevisonData.FEC_AMPLIACION_PSI;
            document.getElementById('num_ampliacion_psi').value = supevisonData.NUM_AMPLIACION_PSI;
            document.getElementById('fec_informe_ampliacion_psi').value = supevisonData.FEC_INFORME_AMPLIACION_PSI;
            document.getElementById('num_informe_ampliacion_psi').value = supevisonData.NUM_INFORME_AMPLIACION_PSI;
            // campos de seguimiento PSI
            document.getElementById('id_seguimiento_psi').value = supevisonData.SEGP_ID;
            document.getElementById('num_informe_seguimiento').value = supevisonData.NUM_INFORME_SEGUIMIENTO;
            document.getElementById('fec_informe_seg').value = supevisonData.FEC_INFORME_SEG;
            document.getElementById('num_oficio_comunicacion_seg_psi').value = supevisonData.NUM_OFICIO_COMUNICACION_SEG_PSI;
            document.getElementById('fec_oficio_comunicacion_seg_psi').value = supevisonData.FEC_OFICIO_COMUNICACION_SEG_PSI;
            document.getElementById('num_of_aprobacion_psi_fisico').value = supevisonData.NUM_OF_APROBACION_PSI_FISICO;
            document.getElementById('fec_aprobacion_psi_fisico').value = supevisonData.FEC_APROBACION_PSI_FISICO;
            document.getElementById('fec_aprobacion_psi_sistema').value = supevisonData.FEC_APROBACION_PSI_SISTEMA;
            // campos de cierre PSI
            document.getElementById('id_levantamiento_psi').value = supevisonData.LEV_ID;
            document.getElementById('mem_solicitud_cierre_psi').value = supevisonData.MEM_SOLICITUD_CIERRE_PSI;
            document.getElementById('fec_mem_solicitud_cierre_psi').value = supevisonData.FEC_MEM_SOLICITUD_CIERRE_PSI;
            document.getElementById('mem_entrega_informe_cierre_psi').value = supevisonData.MEM_ENTREGA_INFORME_CIERRE_PSI;
            document.getElementById('fec_mem_entrega_informe_cierre_psi').value = supevisonData.FEC_MEM_ENTREGA_INFORME_CIERRE_PSI;
            document.getElementById('informe_cierre_psi').value = supevisonData.INFORME_CIERRE_PSI;
            document.getElementById('fec_informe_cierre_psi').value = supevisonData.FEC_INFORME_CIERRE_PSI;
            document.getElementById('resolucion_terminacion_psi').value = supevisonData.RESOLUCION_TERMINACION_PSI;
            document.getElementById('fec_resolucion_terminacion_psi').value = supevisonData.FEC_RESOLUCION_TERMINACION_PSI;
            document.getElementById('fec_reunion_cierre_psi').value = supevisonData.FEC_REUNION_CIERRE_PSI;
            document.getElementById('fec_oficio_envio_cierre_psi').value = supevisonData.FEC_OFICIO_ENVIO_CIERRE_PSI;
            document.getElementById('of_envio_doc_cierre_psi').value = supevisonData.OF_ENVIO_DOC_CIERRE_PSI;
            document.getElementById('fec_entrega_infmr').value = supevisonData.FEC_ENTREGA_INFMR;
            // campos de liquidación
            document.getElementById('id_liquidacion').value = supevisonData.LIQ_ID;
            document.getElementById('num_informe_final_liq').value = supevisonData.LIQ_NUM_INFORME_FINAL;
            document.getElementById('fec_informe_final_liq').value = supevisonData.LIQ_FEC_INFORME_FINAL;
            document.getElementById('memo_comunicacion_igt').value = supevisonData.MEMO_COMUNICACION_IGT;
            document.getElementById('fec_comunicacion_igt').value = supevisonData.FEC_COMUNICACION_IGT;
            document.getElementById('memo_comunicacion_igj').value = supevisonData.MEMO_COMUNICACION_IGJ;
            document.getElementById('fec_comunicacion_igj').value = supevisonData.FEC_COMUNICACION_IGJ
        }
    } catch (error) {
        console.error('❌ Error al obtener estado:', error);
    } finally {    
    // ... otros campos
        mostrarAlerta('Datos de la supervisión cargados correctamente', 'success');
    }
}

// Función para cargar datos de la Alerta seleccionada
async function cargarDatosAlerta(alertaId) {
    console.log('Cargando datos de la Alerta:', alertaId);
    try {
        const url = baseurl + `/backend/supervision/alertaList.php?action=getAlertaData&alertaId=${encodeURIComponent(alertaId)}&catalogoId=${encodeURIComponent(catalogoId)}`;       
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
        }
        catch (parseError) {
            console.error('❌ Error parseando JSON:', parseError);
            throw new Error('Error parseando la respuesta del servidor');
        }
        console.log('✅ Datos de alerta recibidos:', data);
        // Procesar la respuesta para input text
        if (data.success && data.alerta && Array.isArray(data.alerta) && data.alerta.length > 0) {
            const alertaData = data.alerta[0];
            document.getElementById('id_alerta').value = alertaData.ID;
            document.getElementById('tipo_alerta').value = alertaData.TIPO_ALERTA;
            document.getElementById('fec_inicio_supervision_alerta').value = alertaData.FEC_INICIO_SUPERVISION_ALERTA;
            document.getElementById('fec_informe_alerta').value = alertaData.FEC_INFORME_ALERTA;
            document.getElementById('num_informe_alerta').value = alertaData.NUM_INFORME_ALERTA;
            document.getElementById('fec_of_comunicacion_alerta').value = alertaData.FEC_OF_COMUNICACION_ALERTA;
            document.getElementById('num_of_comunicacion_alerta').value = alertaData.NUM_OF_COMUNICACION_ALERTA;
            document.getElementById('fec_aprobacion_ssi').value = alertaData.FEC_APROBACION_SSI;
        }   
    } catch (error) {
        console.error('❌ Error al obtener alerta:', error);
    } finally {    
        mostrarAlerta('Datos de la alerta cargados correctamente', 'success');
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