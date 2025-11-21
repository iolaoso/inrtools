<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

///////////////////////////////////////////////////////////////////////
// Función para guardar una nueva supervisión en la base de datos
///////////////////////////////////////////////////////////////////////
function guardarSupervision($data)
{
    global $conn;

    if (!$conn || $conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Iniciar transacción
    $conn->begin_transaction();

    $accionRealizada = [
        'as_avances_supervision' => '',
        'as_supervisiones' => '',
        'as_correctivas' => '',
        'as_supervison_psi' => '',
        'as_seguimiento_psi' => '',
        'as_levantamiento_psi' => '',
        'as_liquidacion' => '',
        'as_alertas' => '',
        'as_catalogo_supervision' => ''
    ];

    try {
        // Variables generales
        $ruc = $data['ruc'] ?? '';
        $razonSocial = strtoupper(trim($data['razon_social'] ?? $data['tbrazonSocial'] ?? ''));
        $segmento = strtoupper($data['tbsegmento'] ?? '');
        $usrCreacion = strtoupper($data['analista'] ?? 'SISTEMA');
        $idAvancesSupervision = $data['id_avances'] ?? '';
        $codUnico = '';

        // 1. GESTIÓN DE as_avances_supervision
        if (!empty($ruc) && empty($idAvancesSupervision)) {
            // INSERT
            $codUnico = uniqid('AS_');

            // Crear variables para bind_param
            $responsable = $data['analista'] ?? '';
            $catalogoId = $data['fase'] ?? '';
            $fecAsig = $data['fec_asig'] ?? '';
            $anioPlan = $data['anio_plan'] ?? 0;
            $trimPlan = $data['trim_plan'] ?? '';

            $query = "INSERT INTO as_avances_supervision (
                COD_UNICO, RUC, RAZON_SOCIAL, SEGMENTO, RESPONSABLE, CATALOGO_ID, 
                FEC_ASIG, ANIO_PLAN, TRIM_PLAN, EST_REGISTRO, USR_CREACION, 
                FECHA_CREACION, FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Error preparando as_avances_supervision: " . $conn->error);
            }

            $stmt->bind_param(
                "ssssssssss",
                $codUnico,
                $ruc,
                $razonSocial,
                $segmento,
                $responsable,
                $catalogoId,
                $fecAsig,
                $anioPlan,
                $trimPlan,
                $usrCreacion
            );

            $accionRealizada['as_avances_supervision'] = 'INSERTAR';
        } else {
            // UPDATE
            $codUnico = $data['cod_unico_avances'] ?? '';

            // Crear variables para bind_param
            $responsable = $data['analista'] ?? '';
            $catalogoId = $data['fase'] ?? '';
            $fecAsig = $data['fec_asig'] ?? '';
            $anioPlan = $data['anio_plan'] ?? 0;
            $trimPlan = $data['trim_plan'] ?? '';

            $query = "UPDATE as_avances_supervision SET 
                RESPONSABLE = ?, CATALOGO_ID = ?, FEC_ASIG = ?, 
                ANIO_PLAN = ?, TRIM_PLAN = ?, USR_CREACION = ?, 
                FECHA_ACTUALIZACION = NOW() WHERE ID = ?";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Error preparando as_avances_supervision: " . $conn->error);
            }

            $stmt->bind_param(
                "ssssssi",
                $responsable,
                $catalogoId,
                $fecAsig,
                $anioPlan,
                $trimPlan,
                $usrCreacion,
                $idAvancesSupervision
            );

            $accionRealizada['as_avances_supervision'] = 'ACTUALIZAR';
        }

        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando as_avances_supervision: " . $stmt->error);
        }

        if (empty($idAvancesSupervision)) {
            $idAvancesSupervision = $stmt->insert_id;
        }
        $stmt->close();

        // 2. GESTIÓN DE as_supervisiones
        if (isset($data['fec_solicitud']) && !empty($data['fec_solicitud'])) {
            $idSupervision = $data['id_supervision'] ?? '';

            // Crear variables para bind_param
            $fecSolicitud = $data['fec_solicitud'] ?? '';
            $numOficioSolicitud = strtoupper($data['num_oficio_solicitud'] ?? '');
            $fecInsistencia = $data['fec_insistencia'] ?? '';
            $numOficioInsistencia = strtoupper($data['num_oficio_insistencia'] ?? '');
            $fecComunicacion = $data['fec_comunicacion'] ?? '';
            $numOficioResultados = strtoupper($data['num_oficio_resultados'] ?? '');
            $fecLimiteEntrega = $data['fec_limite_entrega'] ?? '';
            $fecRespuesta = $data['fec_respuesta'] ?? '';
            $numOficioRespuesta = strtoupper($data['num_oficio_respuesta'] ?? '');
            $fecInformeFinal = $data['fec_informe_final'] ?? '';
            $numInformeFinal = strtoupper($data['informe_final'] ?? '');
            $fecComunicacionFinal = $data['fec_comunicacion_final'] ?? '';
            $numComunicacionFinal = strtoupper($data['num_comunicacion_final'] ?? '');
            $fecLimitePlanAccion = $data['fec_limite_plan_accion'] ?? '';
            $fecInsistenciaPlanAccion = $data['fec_insistencia_plan_accion'] ?? '';
            $numInsistenciaPlanAccion = strtoupper($data['num_insistencia_plan_accion'] ?? '');
            $fecAprobacionPlanAccion = $data['fec_aprobacion_plan_accion'] ?? '';
            $sancion = strtoupper($data['sancion'] ?? '');

            if (empty($idSupervision)) {
                // INSERT - 22 parámetros
                $query = "INSERT INTO as_supervisiones (
                    ID_AVANCES_SUPERVISION, RUC, COD_UNICO, FEC_SOLICITUD, NUM_OFICIO_SOLICITUD,
                    FEC_INSISTENCIA, NUM_OFICIO_INSISTENCIA, FEC_COMUNICACION, NUM_OFICIO_RESULTADOS,
                    FEC_LIMITE_ENTREGA, NUM_OFICIO_RESPUESTA, FEC_RESPUESTA, FEC_INFORME_FINAL,
                    NUM_INFORME_FINAL, FEC_COMUNICACION_FINAL, NUM_COMUNICACION_FINAL,
                    FEC_LIMITE_PLAN_ACCION, FEC_INSISTENCIA_PLAN_ACCION, NUM_INSISTENCIA_PLAN_ACCION,
                    FEC_APROBACION_PLAN_ACCION, SANCION, EST_REGISTRO, USR_CREACION,
                    FECHA_CREACION, FECHA_ACTUALIZACION
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'ACT',?, NOW(), NOW())";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_supervisiones: " . $conn->error);
                }

                $stmt->bind_param(
                    "isssssssssssssssssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $fecSolicitud,
                    $numOficioSolicitud,
                    $fecInsistencia,
                    $numOficioInsistencia,
                    $fecComunicacion,
                    $numOficioResultados,
                    $fecLimiteEntrega,
                    $fecRespuesta,
                    $numOficioRespuesta,
                    $fecInformeFinal,
                    $numInformeFinal,
                    $fecComunicacionFinal,
                    $numComunicacionFinal,
                    $fecLimitePlanAccion,
                    $fecInsistenciaPlanAccion,
                    $numInsistenciaPlanAccion,
                    $fecAprobacionPlanAccion,
                    $sancion,
                    $usrCreacion
                );

                $accionRealizada['as_supervisiones'] = 'INSERTAR';
            } else {
                // UPDATE - 20 parámetros
                $query = "UPDATE as_supervisiones SET 
                    FEC_SOLICITUD = ?, NUM_OFICIO_SOLICITUD = ?, FEC_INSISTENCIA = ?,
                    NUM_OFICIO_INSISTENCIA = ?, FEC_COMUNICACION = ?, NUM_OFICIO_RESULTADOS = ?,
                    FEC_LIMITE_ENTREGA = ?, NUM_OFICIO_RESPUESTA = ?, FEC_RESPUESTA = ?,
                    FEC_INFORME_FINAL = ?, NUM_INFORME_FINAL = ?, FEC_COMUNICACION_FINAL = ?,
                    NUM_COMUNICACION_FINAL = ?, FEC_LIMITE_PLAN_ACCION = ?, FEC_INSISTENCIA_PLAN_ACCION = ?,
                    NUM_INSISTENCIA_PLAN_ACCION = ?, FEC_APROBACION_PLAN_ACCION = ?, SANCION = ?,
                    USR_CREACION = ?, FECHA_ACTUALIZACION = NOW()
                    WHERE ID = ?";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_supervisiones: " . $conn->error);
                }

                $stmt->bind_param(
                    "sssssssssssssssssssi",
                    $fecSolicitud,
                    $numOficioSolicitud,
                    $fecInsistencia,
                    $numOficioInsistencia,
                    $fecComunicacion,
                    $numOficioResultados,
                    $fecLimiteEntrega,
                    $numOficioRespuesta,
                    $fecRespuesta,
                    $fecInformeFinal,
                    $numInformeFinal,
                    $fecComunicacionFinal,
                    $numComunicacionFinal,
                    $fecLimitePlanAccion,
                    $fecInsistenciaPlanAccion,
                    $numInsistenciaPlanAccion,
                    $fecAprobacionPlanAccion,
                    $sancion,
                    $usrCreacion,
                    $idSupervision
                );

                $accionRealizada['as_supervisiones'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_supervisiones: " . $stmt->error);
            }
            $stmt->close();
        }

        // 3. GUARDAR EN as_correctivas (si hay datos)
        if (isset($data['fec_aprobacion_pa_fisico']) && !empty($data['fec_aprobacion_pa_fisico'])) {
            $idCorrectiva = $data['id_correctivas'] ?? '';

            $fecReunionComunicacion = $data['fec_reunion_comunicacion_resultados'] ?? '';
            $fecAprobacionPaFisico = $data['fec_aprobacion_pa_fisico'] ?? '';
            $numAprobacionPaFisico = strtoupper($data['num_aprobacion_pa_fisico'] ?? '');
            $fecAprobacionPaSistema = $data['fec_aprobacion_pa_sistema'] ?? '';

            if (empty($idCorrectiva)) {
                $query = "INSERT INTO as_correctivas (
                    ID_AVANCES_SUPERVISION, RUC, COD_UNICO, FEC_REUNION_COMUNICACION_RESULTADOS,
                    FEC_APROBACION_PA_FISICO, NUM_APROBACION_PA_FISICO, FEC_APROBACION_PA_SISTEMA, 
                    EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_correctivas: " . $conn->error);
                }

                // INSERT - 8 parámetros
                $stmt->bind_param(
                    "isssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $fecReunionComunicacion,
                    $fecAprobacionPaFisico,
                    $numAprobacionPaFisico,
                    $fecAprobacionPaSistema,
                    $usrCreacion
                );

                $accionRealizada['as_correctivas'] = 'INSERTAR';
            } else {
                $query = "UPDATE as_correctivas SET 
                    FEC_REUNION_COMUNICACION_RESULTADOS = ?, FEC_APROBACION_PA_FISICO = ?,
                    NUM_APROBACION_PA_FISICO = ?, FEC_APROBACION_PA_SISTEMA = ?,
                    USR_CREACION = ?, FECHA_ACTUALIZACION = NOW()
                    WHERE ID = ?";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_correctivas: " . $conn->error);
                }

                $ // UPDATE - 6 parámetros
                $stmt->bind_param(
                    "sssssi",
                    $fecReunionComunicacion,
                    $fecAprobacionPaFisico,
                    $numAprobacionPaFisico,
                    $fecAprobacionPaSistema,
                    $usrCreacion,
                    $idCorrectiva
                );

                $accionRealizada['as_correctivas'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_correctivas: " . $stmt->error);
            }
            $stmt->close();
        }

        // 4. GUARDAR EN as_supervision_psi (si hay datos)
        if (isset($data['num_resolucion_psi']) && !empty($data['num_resolucion_psi'])) {
            $idSupervisionPsi = $data['id_supervision_psi'] ?? '';

            $fecResolucionPsi = $data['fec_resolucion_psi'] ?? '';
            $numResolucionPsi = strtoupper($data['num_resolucion_psi'] ?? '');
            $fecImposicionPsi = $data['fec_imposicion_psi'] ?? '';
            $numOficioImposicionPsi = strtoupper($data['num_oficio_imposicion_psi'] ?? '');
            $fecFinPsi = $data['fec_fin_psi'] ?? '';
            $fecMemorandoComunicacionPsi = $data['fec_memorando_comunicacion_psi'] ?? '';
            $numMemorandoComunicacionPsi = strtoupper($data['num_memorando_comunicacion_psi'] ?? '');
            $fecAmpliacionPsi = $data['fec_ampliacion_psi'] ?? '';
            $numAmpliacionPsi = strtoupper($data['num_ampliacion_psi'] ?? '');
            $fecInformeAmpliacionPsi = $data['fec_informe_ampliacion_psi'] ?? '';
            $numInformeAmpliacionPsi = strtoupper($data['num_informe_ampliacion_psi'] ?? '');

            if (empty($idSupervisionPsi)) {
                $query = "INSERT INTO as_supervision_psi (
                    ID_AVANCES_SUPERVISION,
                    RUC,
                    COD_UNICO,
                    FEC_RESOLUCION_PSI,
                    NUM_RESOLUCION_PSI,
                    FEC_IMPOSICION_PSI,
                    NUM_OFICIO_IMPOSICION_PSI,
                    FEC_FIN_PSI,
                    FEC_MEMORANDO_COMUNICACION_PSI,
                    NUM_MEMORANDO_COMUNICACION_PSI,
                    FEC_AMPLIACION_PSI,
                    NUM_AMPLIACION_PSI,
                    FEC_INFORME_AMPLIACION_PSI,
                    NUM_INFORME_AMPLIACION_PSI,
                    EST_REGISTRO,
                    USR_CREACION,
                    FECHA_CREACION,
                    FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";
                //18 - 3 = 15

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_supervision_psi: " . $conn->error);
                }

                // INSERT - 15 parámetros
                $stmt->bind_param(
                    "issssssssssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $fecResolucionPsi,
                    $numResolucionPsi,
                    $fecImposicionPsi,
                    $numOficioImposicionPsi,
                    $fecFinPsi,
                    $fecMemorandoComunicacionPsi,
                    $numMemorandoComunicacionPsi,
                    $fecAmpliacionPsi,
                    $numAmpliacionPsi,
                    $fecInformeAmpliacionPsi,
                    $numInformeAmpliacionPsi,
                    $usrCreacion
                );

                $accionRealizada['as_supervison_psi'] = 'INSERTAR';
            } else {
                $query = "UPDATE as_supervision_psi SET 
                    FEC_RESOLUCION_PSI = ?, 
                    NUM_RESOLUCION_PSI = ?,
                    FEC_IMPOSICION_PSI = ?,
                    NUM_OFICIO_IMPOSICION_PSI = ?, 
                    FEC_FIN_PSI = ?, 
                    FEC_MEMORANDO_COMUNICACION_PSI = ?,
                    NUM_MEMORANDO_COMUNICACION_PSI = ?,
                    FEC_AMPLIACION_PSI = ?,
                    NUM_AMPLIACION_PSI = ?,
                    FEC_INFORME_AMPLIACION_PSI = ?,
                    NUM_INFORME_AMPLIACION_PSI = ?,
                    USR_CREACION = ?,
                    FECHA_ACTUALIZACION = NOW()
                    WHERE ID = ?";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_supervision_psi: " . $conn->error);
                }

                // UPDATE - 13 parámetros
                $stmt->bind_param(
                    "ssssssssssssi",
                    $fecResolucionPsi,
                    $numResolucionPsi,
                    $fecImposicionPsi,
                    $numOficioImposicionPsi,
                    $fecFinPsi,
                    $fecMemorandoComunicacionPsi,
                    $numMemorandoComunicacionPsi,
                    $fecAmpliacionPsi,
                    $numAmpliacionPsi,
                    $fecInformeAmpliacionPsi,
                    $numInformeAmpliacionPsi,
                    $usrCreacion,
                    $idSupervisionPsi
                );

                $accionRealizada['as_supervison_psi'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_supervision_psi: " . $stmt->error);
            }
            $stmt->close();
        }

        // 5. GUARDAR EN as_seguimiento_psi (si hay datos)
        if (isset($data['num_informe_seguimiento']) && !empty($data['num_informe_seguimiento'])) {
            $idSeguimientoPsi = $data['id_seguimiento_psi'] ?? '';

            $numInformeSeg = strtoupper($data['num_informe_seguimiento'] ?? '');
            $fecInformeSeg = $data['fec_informe_seg'] ?? '';
            $numOficioComunicacionSegPsi = strtoupper($data['num_oficio_comunicacion_seg_psi'] ?? '');
            $fecOficioComunicacionSegPsi = $data['fec_oficio_comunicacion_seg_psi'] ?? '';
            $numOfAprobacionPsiFisico = strtoupper($data['num_of_aprobacion_psi_fisico'] ?? '');
            $fecAprobacionPsiFisico = $data['fec_aprobacion_psi_fisico'] ?? '';
            $fecAprobacionPsiSistema = $data['fec_aprobacion_psi_sistema'] ?? '';

            if (empty($idSeguimientoPsi)) {
                $query = "INSERT INTO as_seguimiento_psi (
                    ID_AVANCES_SUPERVISION, RUC, COD_UNICO, NUM_INFORME_SEGUIMIENTO, FEC_INFORME_SEG,
                    NUM_OFICIO_COMUNICACION_SEG_PSI, FEC_OFICIO_COMUNICACION_SEG_PSI,
                    NUM_OF_APROBACION_PSI_FISICO, FEC_APROBACION_PSI_FISICO, FEC_APROBACION_PSI_SISTEMA,
                    EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";
                // 14 - 3 = 11

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_seguimiento_psi: " . $conn->error);
                }

                // INSERT - 11 parámetros
                $stmt->bind_param(
                    "issssssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $numInformeSeg,
                    $fecInformeSeg,
                    $numOficioComunicacionSegPsi,
                    $fecOficioComunicacionSegPsi,
                    $numOfAprobacionPsiFisico,
                    $fecAprobacionPsiFisico,
                    $fecAprobacionPsiSistema,
                    $usrCreacion
                );

                $accionRealizada['as_seguimiento_psi'] = 'INSERTAR';
            } else {
                $query = "UPDATE as_seguimiento_psi SET 
                    NUM_INFORME_SEGUIMIENTO = ?, FEC_INFORME_SEG = ?, NUM_OFICIO_COMUNICACION_SEG_PSI = ?,
                    FEC_OFICIO_COMUNICACION_SEG_PSI = ?, NUM_OF_APROBACION_PSI_FISICO = ?,
                    FEC_APROBACION_PSI_FISICO = ?, FEC_APROBACION_PSI_SISTEMA = ?,
                    USR_CREACION = ?, FECHA_ACTUALIZACION = NOW()
                    WHERE ID = ?";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_seguimiento_psi: " . $conn->error);
                }

                // UPDATE - 9 parámetros
                $stmt->bind_param(
                    "ssssssssi",
                    $numInformeSeg,
                    $fecInformeSeg,
                    $numOficioComunicacionSegPsi,
                    $fecOficioComunicacionSegPsi,
                    $numOfAprobacionPsiFisico,
                    $fecAprobacionPsiFisico,
                    $fecAprobacionPsiSistema,
                    $usrCreacion,
                    $idSeguimientoPsi
                );

                $accionRealizada['as_seguimiento_psi'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_seguimiento_psi: " . $stmt->error);
            }
            $stmt->close();
        }

        // 6. GUARDAR EN as_levantamiento_psi (si hay datos)
        if (isset($data['mem_solicitud_cierre_psi']) && !empty($data['mem_solicitud_cierre_psi'])) {
            $idLevantamientoPsi = $data['id_levantamiento_psi'] ?? '';

            $memSolicitudCierrePsi = strtoupper($data['mem_solicitud_cierre_psi'] ?? '');
            $fecMemSolicitudCierrePsi = $data['fec_mem_solicitud_cierre_psi'] ?? '';
            $memEntregaInformeCierrePsi = strtoupper($data['mem_entrega_informe_cierre_psi'] ?? '');
            $fecMemEntregaInformeCierrePsi = $data['fec_mem_entrega_informe_cierre_psi'] ?? '';
            $informeCierrePsi = strtoupper($data['informe_cierre_psi'] ?? '');
            $fecInformeCierrePsi = $data['fec_informe_cierre_psi'] ?? '';
            $resolucionTerminacionPsi = strtoupper($data['resolucion_terminacion_psi'] ?? '');
            $fecResolucionTerminacionPsi = $data['fec_resolucion_terminacion_psi'] ?? '';
            $fecReunionCierrePsi = $data['fec_reunion_cierre_psi'] ?? '';
            $fecOficioEnvioCierrePsi = $data['fec_oficio_envio_cierre_psi'] ?? '';
            $ofEnvioDocCierrePsi = strtoupper($data['of_envio_doc_cierre_psi'] ?? '');
            $fecEntregaInfmr = $data['fec_entrega_infmr'] ?? '';

            if (empty($idLevantamientoPsi)) {
                $query = "INSERT INTO as_levantamiento_psi (
                    ID_AVANCES_SUPERVISION, 
                    RUC,
                    COD_UNICO,
                    MEM_SOLICITUD_CIERRE_PSI,
                    FEC_MEM_SOLICITUD_CIERRE_PSI,
                    MEM_ENTREGA_INFORME_CIERRE_PSI,
                    FEC_MEM_ENTREGA_INFORME_CIERRE_PSI, 
                    INFORME_CIERRE_PSI,
                    FEC_INFORME_CIERRE_PSI, 
                    RESOLUCION_TERMINACION_PSI, 
                    FEC_RESOLUCION_TERMINACION_PSI,
                    FEC_REUNION_CIERRE_PSI, 
                    FEC_OFICIO_ENVIO_CIERRE_PSI, 
                    OF_ENVIO_DOC_CIERRE_PSI,
                    FEC_ENTREGA_INFMR, 
                    EST_REGISTRO, 
                    USR_CREACION,
                    FECHA_CREACION, 
                    FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";
                // 19 -3 = 16

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_levantamiento_psi: " . $conn->error);
                }

                // INSERT - 17 parámetros
                $stmt->bind_param(
                    "isssssssssssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $memSolicitudCierrePsi,
                    $fecMemSolicitudCierrePsi,
                    $memEntregaInformeCierrePsi,
                    $fecMemEntregaInformeCierrePsi,
                    $informeCierrePsi,
                    $fecInformeCierrePsi,
                    $resolucionTerminacionPsi,
                    $fecResolucionTerminacionPsi,
                    $fecReunionCierrePsi,
                    $fecOficioEnvioCierrePsi,
                    $ofEnvioDocCierrePsi,
                    $fecEntregaInfmr,
                    $usrCreacion
                );

                $accionRealizada['as_levantamiento_psi'] = 'INSERTAR';
            } else {
                $query = "UPDATE as_levantamiento_psi SET 
                    MEM_SOLICITUD_CIERRE_PSI = ?,
                    FEC_MEM_SOLICITUD_CIERRE_PSI = ?,
                    MEM_ENTREGA_INFORME_CIERRE_PSI = ?,
                    FEC_MEM_ENTREGA_INFORME_CIERRE_PSI = ?,
                    INFORME_CIERRE_PSI = ?,
                    FEC_INFORME_CIERRE_PSI = ?,
                    RESOLUCION_TERMINACION_PSI = ?,
                    FEC_RESOLUCION_TERMINACION_PSI = ?,
                    FEC_REUNION_CIERRE_PSI = ?,
                    FEC_OFICIO_ENVIO_CIERRE_PSI = ?,
                    OF_ENVIO_DOC_CIERRE_PSI = ?,
                    FEC_ENTREGA_INFMR = ?,
                    USR_CREACION = ?,
                    FECHA_ACTUALIZACION = NOW()
                    WHERE ID = ?";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_levantamiento_psi: " . $conn->error);
                }

                // UPDATE - 15 parámetros
                $stmt->bind_param(
                    "sssssssssssssi",
                    $memSolicitudCierrePsi,
                    $fecMemSolicitudCierrePsi,
                    $memEntregaInformeCierrePsi,
                    $fecMemEntregaInformeCierrePsi,
                    $informeCierrePsi,
                    $fecInformeCierrePsi,
                    $resolucionTerminacionPsi,
                    $fecResolucionTerminacionPsi,
                    $fecReunionCierrePsi,
                    $fecOficioEnvioCierrePsi,
                    $ofEnvioDocCierrePsi,
                    $fecEntregaInfmr,
                    $usrCreacion,
                    $idLevantamientoPsi
                );

                $accionRealizada['as_levantamiento_psi'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_levantamiento_psi: " . $stmt->error);
            }
            $stmt->close();
        }

        // 7. GUARDAR EN as_liquidacion (si hay datos)
        if (isset($data['num_informe_final_liq']) && !empty($data['num_informe_final_liq'])) {
            $idLiquidacion = $data['id_liquidacion'] ?? '';

            $numInformeLiq = strtoupper($data['num_informe_final_liq'] ?? '');
            $fecInformeLiq = $data['fec_informe_final_liq'] ?? '';
            $memoComunicacionIgt = strtoupper($data['memo_comunicacion_igt'] ?? '');
            $fecComunicacionIgt = $data['fec_comunicacion_igt'] ?? '';
            $memoComunicacionIgj = strtoupper($data['memo_comunicacion_igj'] ?? '');
            $fecComunicacionIgj = $data['fec_comunicacion_igj'] ?? '';

            if (empty($idLiquidacion)) {
                $query = "INSERT INTO as_liquidacion (
                    ID_AVANCES_SUPERVISION, 
                    RUC,
                    COD_UNICO,
                    NUM_INFORME_FINAL_LIQ,
                    FEC_INFORME_FINAL_LIQ,
                    MEMO_COMUNICACION_IGT,
                    FEC_COMUNICACION_IGT,
                    MEMO_COMUNICACION_IGJ,
                    FEC_COMUNICACION_IGJ,
                    EST_REGISTRO,
                    USR_CREACION,
                    FECHA_CREACION,
                    FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_liquidacion: " . $conn->error);
                }

                // INSERT - 10 parámetros
                $stmt->bind_param(
                    "isssssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $numInformeLiq,
                    $fecInformeLiq,
                    $memoComunicacionIgt,
                    $fecComunicacionIgt,
                    $memoComunicacionIgj,
                    $fecComunicacionIgj,
                    $usrCreacion
                );

                $accionRealizada['as_liquidacion'] = 'INSERTAR';
            } else {
                $query = "UPDATE as_liquidacion SET 
                    NUM_INFORME_FINAL_LIQ = ?,
                    FEC_INFORME_FINAL_LIQ = ?,
                    MEMO_COMUNICACION_IGT = ?,
                    FEC_COMUNICACION_IGT = ?, 
                    MEMO_COMUNICACION_IGJ = ?,
                    FEC_COMUNICACION_IGJ = ?,
                    USR_CREACION = ?,
                    FECHA_ACTUALIZACION = NOW()
                    WHERE ID = ?";

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_liquidacion: " . $conn->error);
                }

                // UPDATE - 8 parámetros
                $stmt->bind_param(
                    "sssssssi",
                    $numInformeLiq,
                    $fecInformeLiq,
                    $memoComunicacionIgt,
                    $fecComunicacionIgt,
                    $memoComunicacionIgj,
                    $fecComunicacionIgj,
                    $usrCreacion,
                    $idLiquidacion
                );

                $accionRealizada['as_liquidacion'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_liquidacion: " . $stmt->error);
            }
            $stmt->close();
        }

        // 8. GUARDAR EN as_alertas (si hay datos)
        if (isset($data['tipo_alerta']) && !empty($data['tipo_alerta'])) {
            $idAlerta = $data['id_alertas'] ?? '';

            $tipoAlerta = $data['tipo_alerta'] ?? '';
            $descripcionAlerta = $data['descripcion_alerta'] ?? '';
            $fecInicioSupervisionAlerta = $data['fec_inicio_supervision_alerta'] ?? '';
            $fecInformeAlerta = $data['fec_informe_alerta'] ?? '';
            $numInformeAlerta = strtoupper($data['num_informe_alerta'] ?? '');
            $fecOfComunicacionAlerta = $data['fec_of_comunicacion_alerta'] ?? '';
            $numOfComunicacionAlerta = strtoupper($data['num_of_comunicacion_alerta'] ?? '');
            $fecAprobacionSsi = $data['fec_aprobacion_ssi'] ?? '';

            if (empty($idAlerta)) {
                $query = "INSERT INTO as_alertas (
                    ID_AVANCES_SUPERVISION,
                    RUC,
                    COD_UNICO,
                    TIPO_ALERTA,
                    DESCRIPCION_ALERTA,
                    FEC_INICIO_SUPERVISION_ALERTA,
                    FEC_INFORME_ALERTA,
                    NUM_INFORME_ALERTA,
                    FEC_OF_COMUNICACION_ALERTA,
                    NUM_OF_COMUNICACION_ALERTA,
                    FEC_APROBACION_SSI,
                    EST_REGISTRO,
                    USR_CREACION, 
                    FECHA_CREACION,
                    FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";
                //15-3 = 12

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_alertas: " . $conn->error);
                }

                // INSERT - 12 parámetros
                $stmt->bind_param(
                    "isssssssssss",
                    $idAvancesSupervision,
                    $ruc,
                    $codUnico,
                    $tipoAlerta,
                    $descripcionAlerta,
                    $fecInicioSupervisionAlerta,
                    $fecInformeAlerta,
                    $numInformeAlerta,
                    $fecOfComunicacionAlerta,
                    $numOfComunicacionAlerta,
                    $fecAprobacionSsi,
                    $usrCreacion
                );

                $accionRealizada['as_alertas'] = 'INSERTAR';
            } else {
                $query = "UPDATE as_alertas SET 
                                TIPO_ALERTA = ?,
                                DESCRIPCION_ALERTA = ?,
                                FEC_INICIO_SUPERVISION_ALERTA = ?,
                                FEC_INFORME_ALERTA = ?,
                                NUM_INFORME_ALERTA = ?,
                                FEC_OF_COMUNICACION_ALERTA = ?,
                                NUM_OF_COMUNICACION_ALERTA = ?, 
                                FEC_APROBACION_SSI = ?,
                                USR_CREACION = ?,
                                FECHA_ACTUALIZACION = NOW()
                            WHERE ID = ?";
                // 11 - 3 = 8

                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error preparando as_alertas: " . $conn->error);
                }

                $stmt->bind_param(
                    "ssssssssssi",
                    $tipoAlerta,
                    $descripcionAlerta,
                    $fecInicioSupervisionAlerta,
                    $fecInformeAlerta,
                    $numInformeAlerta,
                    $fecOfComunicacionAlerta,
                    $numOfComunicacionAlerta,
                    $fecAprobacionSsi,
                    $usrCreacion,
                    $idAlerta
                );

                $accionRealizada['as_alertas'] = 'ACTUALIZAR';
            }

            if (!$stmt->execute()) {
                throw new Exception("Error ejecutando as_alertas: " . $stmt->error);
            }
            $stmt->close();
        }

        // Confirmar transacción
        $conn->commit();

        // Respuesta exitosa
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'id_avances_supervision' => $idAvancesSupervision,
            'cod_unico' => $codUnico,
            'accion' => $accionRealizada,
            'message' => 'Datos guardados exitosamente'
        ]);
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error en guardado: " . $e->getMessage());

        // IMPORTANTE: Enviar respuesta JSON incluso en errores
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar los datos: ' . $e->getMessage()
        ]);
        exit;
    }
}


///////////////////////////////////////////////////////////////////////
// Función para ELIMINAR una supervisión en la base de datos
///////////////////////////////////////////////////////////////////////

function eliminarSupervision($data)
{
    global $conn;

    if (!$conn || $conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Variables generales
        $idAvancesSupervision = $data['idavances'] ?? '';
        $usrCreacion = $data['usuario'] ?? 'UsuarioSistema'; // Asegurar valor por defecto

        // Validar datos requeridos
        if (empty($idAvancesSupervision)) {
            throw new Exception("El ID de avance de supervisión es requerido");
        }

        if (empty($usrCreacion)) {
            throw new Exception("El usuario es requerido para la operación");
        }

        // 1. BUSCAR CÓDIGO ÚNICO en as_avances_supervision
        $queryBuscar = "SELECT COD_UNICO FROM as_avances_supervision 
                    WHERE ID = ? AND EST_REGISTRO = 'ACT'";

        $stmtBuscar = $conn->prepare($queryBuscar);
        if (!$stmtBuscar) {
            throw new Exception("Error preparando consulta de búsqueda: " . $conn->error);
        }

        $stmtBuscar->bind_param("i", $idAvancesSupervision);

        if (!$stmtBuscar->execute()) {
            throw new Exception("Error ejecutando búsqueda: " . $stmtBuscar->error);
        }
        
        $resultado = $stmtBuscar->get_result();

        if ($resultado->num_rows === 0) {
            $stmtBuscar->close();
            throw new Exception("No se encontró el registro con ID: " . $idAvancesSupervision);
        }

        $fila = $resultado->fetch_assoc();
        
        if ($fila['COD_UNICO'] != $data['codUnico']){
            $stmtBuscar->close();
            throw new Exception("Codigo Único coincidente con la base de datos: " . $idAvancesSupervision);
        }

        $codUnico = $fila['COD_UNICO'] ?? $data['codUnico'];

        // Validar que se obtuvo el código único
        if (empty($codUnico)) {
            throw new Exception("No se pudo obtener el código único del registro");
        }

        // 2. Inactivar Registro principal
        $queryActualizar = "UPDATE as_avances_supervision SET 
                        EST_REGISTRO = 'INA', 
                        deletedAt = NOW(),
                        deletedUser = ?
                        WHERE ID = ? AND COD_UNICO = ?";

        $stmt = $conn->prepare($queryActualizar);
        if (!$stmt) {
            throw new Exception("Error preparando actualización: " . $conn->error);
        }

        $stmt->bind_param("sis", $usrCreacion, $idAvancesSupervision, $codUnico);

        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando actualización: " . $stmt->error);
        }

        // Verificar si se afectó alguna fila
        if ($stmt->affected_rows === 0) {
            throw new Exception("No se pudo inactivar el registro. Verifique los datos.");
        }

        $stmt->close();

        // 3. Inactivar registros relacionados con el mismo COD_UNICO
        $tablasRelacionadas = [
            'as_supervisiones',
            'as_correctivas',
            'as_supervision_psi',
            'as_seguimiento_psi',
            'as_levantamiento_psi',
            'as_liquidacion',
            'as_alertas'
        ];

        $accionRealizada = ['as_avances_supervision' => 'DELETED'];
        $totalRegistrosAfectados = 0;

        foreach ($tablasRelacionadas as $tabla) {
            $queryRelacionados = "UPDATE $tabla SET 
                                EST_REGISTRO = 'INA',
                                deletedAt = NOW(),
                                deletedUser = ?
                                WHERE COD_UNICO = ? AND EST_REGISTRO = 'ACT'";

            $stmtRel = $conn->prepare($queryRelacionados);
            if (!$stmtRel) {
                throw new Exception("Error preparando consulta para $tabla: " . $conn->error);
            }

            $stmtRel->bind_param("ss", $usrCreacion, $codUnico);

            if (!$stmtRel->execute()) {
                throw new Exception("Error ejecutando actualización en $tabla: " . $stmtRel->error);
            }

            $registrosAfectados = $stmtRel->affected_rows;
            $totalRegistrosAfectados += $registrosAfectados;

            if ($registrosAfectados > 0) {
                $accionRealizada[$tabla] = "DELETED ($registrosAfectados registros)";
            } else {
                $accionRealizada[$tabla] = "NO_ACTION (0 registros)";
            }

            $stmtRel->close();
        }

        // Confirmar transacción
        $conn->commit();

        // Respuesta exitosa
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'id_avances_supervision' => $idAvancesSupervision,
            'cod_unico' => $codUnico,
            'message' => 'Supervisión y registros relacionados eliminados exitosamente',
            'registros_afectados' => [
                'principal' => 1, // Siempre 1 para el registro principal
                'relacionados' => $totalRegistrosAfectados,
                'total' => 1 + $totalRegistrosAfectados
            ],
            'acciones' => $accionRealizada,
            'tablas_procesadas' => count($tablasRelacionadas)
        ]);
 
        exit;
    } catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($conn) {
        $conn->rollback();
    }
    
    error_log("Error en eliminación de supervisión: " . $e->getMessage());

    // Respuesta de error
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al eliminar la supervisión: ' . $e->getMessage(),
        'id_avances_supervision' => $idAvancesSupervision ?? null,
        'cod_unico' => $codUnico ?? null,
        'tablas_procesadas_hasta_error' => $accionRealizada ?? []
    ]);
    exit;
}
}



/////////////////////////////////////////////////////////////////////////
// Evaluar POST para determinar la acción a seguir
/////////////////////////////////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establecer header JSON para todas las respuestas
    header('Content-Type: application/json');

    switch ($_GET['action'] ?? '') {
        case 'guardar_supervision':
            try {
                $datos_recibidos = file_get_contents('php://input');
                $datos_decode_json = json_decode($datos_recibidos, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('JSON inválido: ' . json_last_error_msg());
                }

                guardarSupervision($datos_decode_json);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error procesando solicitud: ' . $e->getMessage()
                ]);
            }
            break;

        case 'eliminar_supervision':
            try {
                $datos_recibidos = file_get_contents('php://input');
                $datos_decode_json = json_decode($datos_recibidos, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('JSON inválido: ' . json_last_error_msg());
                }

                // Validar que se recibió el ID requerido
                if (!isset($datos_decode_json['idavances']) || empty($datos_decode_json['idavances'])) {
                    throw new Exception('El campo idavances es requerido para eliminar');
                }

                // Validar que se recibió el usuario
                if (!isset($datos_decode_json['usuario']) || empty($datos_decode_json['usuario'])) {
                    throw new Exception('El campo usuario es requerido para la eliminación');
                }

                eliminarSupervision($datos_decode_json);
                
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error procesando eliminación: ' . $e->getMessage()
                ]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
            exit;
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}


