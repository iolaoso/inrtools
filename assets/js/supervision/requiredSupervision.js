// Función para aplicar required dinámicos basado solo en secciones visibles
function aplicarRequiredDinamicos() {
    // Limpiar todos los required primero
    limpiarTodosLosRequired();
    
    // 1. CAMPOS BASE SIEMPRE REQUERIDOS (sección sAvancesSupervision siempre visible)
    const camposBaseRequeridos = [
        'id_avances', 'cod_unico_avances', 'ruc', 'tbsegmento', 'tbrazonSocial',
        'estrategia', 'fase', 'analista', 'estadoSupervision', 'fec_asig',
        'anio_plan', 'trim_plan', 'porc_avance'
    ];
    aplicarRequiredACampos(camposBaseRequeridos);
    
    // 2. REQUIRED POR SECCIONES VISIBLES
    const seccionesVisibles = obtenerSeccionesVisibles();
    
    seccionesVisibles.forEach(seccionId => {
        switch(seccionId) {
            case 'sSupervisiones':
                aplicarRequiredACampos([
                    //'id_supervision',
                    'fec_solicitud', 
                    'num_oficio_solicitud',
                    'fec_insistencia', 
                    'num_oficio_insistencia',
                    'fec_comunicacion', 
                    'num_oficio_resultados', 
                    'fec_limite_entrega',
                    'fec_respuesta', 
                    'num_oficio_respuesta',
                    'fec_informe_final', 
                    'informe_final',
                    'fec_comunicacion_final', 
                    'num_comunicacion_final',
                    'fec_limite_plan_accion', 
                    'fec_insistencia_plan_accion', 
                    'num_insistencia_plan_accion', 
                    'fec_aprobacion_plan_accion',
                    'sancion'
                ]);
                break;
                
            case 'sCorrectivas':
                aplicarRequiredACampos([
                    //'id_correctiva',
                    'fec_reunion_comunicacion_resultados',
                    'fec_aprobacion_pa_fisico', 
                    'num_aprobacion_pa_fisico', 
                    'fec_aprobacion_pa_sistema'
                ]);
                break;
                
            case 'sSupervisionPsi':
                aplicarRequiredACampos([
                    //'id_supervision_psi',
                    'fec_resolucion_psi', 
                    'num_resolucion_psi',
                    'fec_imposicion_psi', 
                    'num_oficio_imposicion_psi',
                    'fec_fin_psi',
                    'fec_memorando_comunicacion_psi', 
                    'num_memorando_comunicacion_psi',
                    'fec_ampliacion_psi', 
                    'num_ampliacion_psi', 
                    'fec_informe_ampliacion_psi',
                     'num_informe_ampliacion_psi'
                ]);
                break;
                
            case 'sSeguimientoPsi':
                aplicarRequiredACampos([
                    //'id_seguimiento_psi',
                    'num_informe_seguimiento',
                     'fec_informe',
                    'num_oficio_comunicacion_seg_psi', 
                    'fec_oficio_comunicacion_seg_psi',
                    'num_of_aprobacion_psi_fisico', 
                    'fec_aprobacion_psi_fisico', 
                    'fec_aprobacion_psi_sistema'
                ]);
                break;
                
            case 'sLevantamientoPsi':
                aplicarRequiredACampos([
                    //'id_levantamiento_psi',
                    'mem_solicitud_cierre_psi',
                     'fec_mem_solicitud_cierre_psi',
                    'mem_entrega_informe_cierre_psi', 
                    'fec_mem_entrega_informe_cierre_psi',
                    'informe_cierre_psi', 
                    'fec_informe_cierre_psi',
                    'resolucion_terminacion_psi', 
                    'fec_resolucion_terminacion_psi',
                    'fec_reunion_cierre_psi', 
                    'fec_oficio_envio_cierre_psi', 
                    'of_envio_doc_cierre_psi',
                    'fec_entrega_infmr'
                ]);
                break;
                
            case 'sLiquidacion':
                aplicarRequiredACampos([
                    //'id_liquidacion',
                    'num_informe_final_liq', 
                    'fec_informe_final_liq',
                    'memo_comunicacion_igt', 
                    'fec_comunicacion_igt',
                    'memo_comunicacion_igj', 
                    'fec_comunicacion_igj'
                ]);
                break;
                
            case 'sAlertas':
                aplicarRequiredACampos([
                    //'id_alerta', 
                    'id_avances_supervision_alert', 
                    'ruc_alerta',
                     'cod_unico_alerta',
                    'tipo_alerta', 
                    'tipo_supervision', 
                    'estado_proceso', 
                    'observacion_estado',
                    'fec_informe_alerta',
                     'num_informe_alerta',
                    'fec_of_comunicacion_alerta', 
                    'num_of_comunicacion_alerta',
                    'fec_aprobacion_ssi'
                ]);
                break;
        }
    });
    
    //console.log("Required aplicados para secciones visibles:", seccionesVisibles);
}

// Función para limpiar TODOS los campos posibles
function limpiarTodosLosRequired() {
    const todosLosCampos = [
        // Campos base
        /* 'id_avances', 'cod_unico_avances', 'ruc', 'tbsegmento', 'tbrazonSocial',
        'estrategia', 'fase', 'analista', 'estadoSupervision', 'fec_asig',
        'anio_plan', 'trim_plan', 'porc_avance',  */

        'id_supervision', 'id_correctiva', 'id_supervision_psi', 'id_seguimiento_psi',
        'id_levantamiento_psi', 'id_liquidacion', 'id_alerta', 
        
        // Supervisiones
        'fec_solicitud', 'num_oficio_solicitud',
        'fec_insistencia', 'num_oficio_insistencia', 'fec_comunicacion', 
        'num_oficio_resultados', 'fec_limite_entrega', 'fec_respuesta', 
        'num_oficio_respuesta', 'fec_informe_final', 'informe_final',
        'fec_comunicacion_final', 'num_comunicacion_final', 'fec_limite_plan_accion',
        'fec_insistencia_plan_accion', 'num_insistencia_plan_accion', 'fec_aprobacion_plan_accion',
        'sancion',
        
        // Correctivas
        'fec_reunion_comunicacion_resultados', 'fec_aprobacion_pa_fisico',
        'num_aprobacion_pa_fisico', 'fec_aprobacion_pa_sistema',
        
        // Supervision PSI
        'fec_resolucion_psi', 'num_resolucion_psi',
        'fec_imposicion_psi', 'num_oficio_imposicion_psi', 'fec_fin_psi',
        'fec_memorando_comunicacion_psi', 'num_memorando_comunicacion_psi',
        'fec_ampliacion_psi', 'num_ampliacion_psi', 'fec_informe_ampliacion_psi',
        'num_informe_ampliacion_psi',
        
        // Seguimiento PSI
        'num_informe_seguimiento', 'fec_informe',
        'num_oficio_comunicacion_seg_psi', 'fec_oficio_comunicacion_seg_psi',
        'num_of_aprobacion_psi_fisico', 'fec_aprobacion_psi_fisico', 'fec_aprobacion_psi_sistema',
        
        // Levantamiento PSI
        'mem_solicitud_cierre_psi', 'fec_mem_solicitud_cierre_psi',
        'mem_entrega_informe_cierre_psi', 'fec_mem_entrega_informe_cierre_psi',
        'informe_cierre_psi', 'fec_informe_cierre_psi', 'resolucion_terminacion_psi',
        'fec_resolucion_terminacion_psi', 'fec_reunion_cierre_psi', 'fec_oficio_envio_cierre_psi',
        'of_envio_doc_cierre_psi', 'fec_entrega_infmr',
        
        // Liquidación
        'num_informe_final_liq', 'fec_informe_final_liq',
        'memo_comunicacion_igt', 'fec_comunicacion_igt', 'memo_comunicacion_igj',
        'fec_comunicacion_igj',
        
        // Alertas
        'id_avances_supervision_alert', 'ruc_alerta', 'cod_unico_alerta',
        'tipo_alerta', 'tipo_supervision', 'estado_proceso', 'observacion_estado',
        'fec_informe_alerta', 'num_informe_alerta', 'fec_of_comunicacion_alerta',
        'num_of_comunicacion_alerta', 'fec_aprobacion_ssi'
    ];
    
    todosLosCampos.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.required = false;
            actualizarLabelRequerido(campoId, false);
        }
    });
}

function aplicarRequiredACampos(campos){
    campos.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.required = true;
            actualizarLabelRequerido(campoId, true);
        }
    });
}

function actualizarLabelRequerido(campoId, esRequerido) {
    const label = document.querySelector(`label[for="${campoId}"]`);
    if (label) {
        if (esRequerido) {
            if (!label.innerHTML.includes('<span class="text-danger">*</span>')) {
                label.innerHTML += ' <span class="text-danger">*</span>';
            }
        } else {
            label.innerHTML = label.innerHTML.replace(' <span class="text-danger">*</span>', '');
        }
    }
}

function obtenerSeccionesVisibles() {
    const secciones = [
        'sSupervisiones', 'sCorrectivas', 'sSupervisionPsi',
        'sSeguimientoPsi', 'sLevantamientoPsi', 'sLiquidacion', 'sAlertas'
    ];
    return secciones.filter(seccionId => {
        const seccion = document.getElementById(seccionId);
        return seccion && seccion.style.display !== 'none';
    });
}