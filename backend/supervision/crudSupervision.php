<?php

use SebastianBergmann\Environment\Console;

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos




// Función para guardar una nueva supervisión en la base de datos
function guardarSupervision($data)
{
    global $conn;

    if (!$conn || $conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Iniciar transacción para asegurar consistencia
    $conn->begin_transaction();

    try {
        // 1. GUARDAR EN as_avances_supervision (tabla principal)
        if (isset($data['ruc']) && !empty($data['ruc'])) {
            $queryAvances = "INSERT INTO as_avances_supervision (
                COD_UNICO, RUC, RAZON_SOCIAL, SEGMENTO, RESPONSABLE, CATALOGO_ID,
                FEC_ASIG, ANIO_PLAN, TRIM_PLAN, 
                EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmtAvances = $conn->prepare($queryAvances);
            if (!$stmtAvances) {
                throw new Exception("Error preparando as_avances_supervision: " . $conn->error);
            }

            $codUnico = uniqid('AS_'); // Generar un código único para COD_UNICO

            // Crear variables para cada parámetro y asegurar que no sean null
            $ruc = $data['ruc'] ?? '';
            $razonSocial = $data['tbrazonSocial'] ?? '';
            $segmento = $data['tbsegmento'] ?? '';
            $responsable = $data['analista'] ?? '';
            $catalogoId = $data['fase'] ?? '';
            $fecAsig = $data['fec_asig'] ?? null;
            $anioPlan = $data['anio_plan'] ?? null;
            $trimPlan = $data['trim_plan'] ?? null;
            $usrCreacion = $data['analista'] ?? 'SISTEMA';

            // Convertir null a valores vacíos o por defecto
            if ($fecAsig === null) $fecAsig = '';
            if ($anioPlan === null) $anioPlan = 0;
            if ($trimPlan === null) $trimPlan = '';

            $stmtAvances->bind_param(
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

            if (!$stmtAvances->execute()) {
                throw new Exception("Error ejecutando as_avances_supervision: " . $stmtAvances->error);
            }

            $idAvancesSupervision = $stmtAvances->insert_id;
            $stmtAvances->close();
        }
       
        // 2. GUARDAR EN as_supervisiones (si hay datos)
        if (isset($data['fec_solicitud']) && !empty($data['fec_solicitud'])) {
            $querySupervisiones = "INSERT INTO as_supervisiones (
                ID_AVANCES_SUPERVISION
                ,RUC
                ,COD_UNICO
                ,FEC_SOLICITUD
                ,NUM_OFICIO_SOLICITUD
                ,FEC_INSISTENCIA
                ,NUM_OFICIO_INSISTENCIA
                ,FEC_COMUNICACION
                ,NUM_OFICIO_RESULTADOS
                ,FEC_LIMITE_ENTREGA
                ,NUM_OFICIO_RESPUESTA
                ,FEC_RESPUESTA
                ,FEC_INFORME_FINAL
                ,NUM_INFORME_FINAL
                ,FEC_COMUNICACION_FINAL
                ,NUM_COMUNICACION_FINAL
                ,FEC_LIMITE_PLAN_ACCION
                ,FEC_INSISTENCIA_PLAN_ACCION
                ,NUM_INSISTENCIA_PLAN_ACCION
                ,FEC_APROBACION_PLAN_ACCION
                ,SANCION
                ,EST_REGISTRO
                ,USR_CREACION
                ,FECHA_CREACION
                ,FECHA_ACTUALIZACION
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'ACT',?, NOW(), NOW())";


            $stmtSupervisiones = $conn->prepare($querySupervisiones);
            if ($stmtSupervisiones) {
                // CORRECCIÓN: Crear variables para cada campo y manejar nulls
                $fecSolicitud = $data['fec_solicitud'] ?? '';
                $numOficioSolicitud = $data['num_oficio_solicitud'] ?? '';
                $fecInsistencia = $data['fec_insistencia'] ?? '';
                $numOficioInsistencia = $data['num_oficio_insistencia'] ?? '';
                $fecComunicacion = $data['fec_comunicacion'] ?? '';
                $numOficioResultados = $data['num_oficio_resultados'] ?? '';
                $fecLimiteEntrega = $data['fec_limite_entrega'] ?? '';
                $fecRespuesta = $data['fec_respuesta'] ?? '';
                $numOficioRespuesta = $data['num_oficio_respuesta'] ?? '';
                $fecInformeFinal = $data['fec_informe_final'] ?? '';
                $numInformeFinal = $data['informe_final'] ?? ''; 
                $fecComunicacionFinal = $data['fec_comunicacion_final'] ?? '';
                $numComunicacionFinal = $data['num_comunicacion_final'] ?? '';
                $fecLimitePlanAccion = $data['fec_limite_plan_accion'] ?? '';
                $fecInsistenciaPlanAccion = $data['fec_insistencia_plan_accion'] ?? '';
                $numInsistenciaPlanAccion = $data['num_insistencia_plan_accion'] ?? '';
                $fecAprobacionPlanAccion = $data['fec_aprobacion_plan_accion'] ?? '';
                $sancion = $data['sancion'] ?? '';
             
                // Manejar valores null convirtiéndolos a cadenas vacías
                if ($fecSolicitud === null) $fecSolicitud = '';
                if ($numOficioSolicitud === null) $numOficioSolicitud = '';
                if ($fecInsistencia === null) $fecInsistencia = '';
                if ($numOficioInsistencia === null) $numOficioInsistencia = '';
                if ($fecComunicacion === null) $fecComunicacion = '';
                if ($numOficioResultados === null) $numOficioResultados = '';
                if ($fecLimiteEntrega === null) $fecLimiteEntrega = '';
                if ($fecRespuesta === null) $fecRespuesta = '';
                if ($numOficioRespuesta === null) $numOficioRespuesta = '';
                if ($fecInformeFinal === null) $fecInformeFinal = '';
                if ($numInformeFinal === null) $numInformeFinal = '';
                if ($fecComunicacionFinal === null) $fecComunicacionFinal = '';
                if ($numComunicacionFinal === null) $numComunicacionFinal = '';
                if ($fecLimitePlanAccion === null) $fecLimitePlanAccion = '';
                if ($fecInsistenciaPlanAccion === null) $fecInsistenciaPlanAccion = '';
                if ($numInsistenciaPlanAccion === null) $numInsistenciaPlanAccion = '';
                if ($fecAprobacionPlanAccion === null) $fecAprobacionPlanAccion = '';
                if ($sancion === null) $sancion = '';

                $stmtSupervisiones->bind_param(
                    "isssssssssssssssssssss",
                    $idAvancesSupervision,            // ID_AVANCES_SUPERVISION
                    $ruc,                             // RUC
                    $codUnico,                        // COD_UNICO
                    $fecSolicitud,                    // FEC_SOLICITUD
                    $numOficioSolicitud,              // NUM_OFICIO_SOLICITUD
                    $fecInsistencia,                  // FEC_INSISTENCIA
                    $numOficioInsistencia,            // NUM_OFICIO_INSISTENCIA
                    $fecComunicacion,                 // FEC_COMUNICACION
                    $numOficioResultados,             // NUM_OFICIO_RESULTADOS
                    $fecLimiteEntrega,                // FEC_LIMITE_ENTREGA
                    $fecRespuesta,                    // FEC_RESPUESTA
                    $numOficioRespuesta,              // NUM_OFICIO_RESPUESTA
                    $fecInformeFinal,                 // FEC_INFORME_FINAL
                    $numInformeFinal,                 // NUM_INFORME_FINAL
                    $fecComunicacionFinal,            // FEC_COMUNICACION_FINAL
                    $numComunicacionFinal,            // NUM_COMUNICACION_FINAL
                    $fecLimitePlanAccion,             // FEC_LIMITE_PLAN_ACCION
                    $fecInsistenciaPlanAccion,        // FEC_INSISTENCIA_PLAN_ACCION
                    $numInsistenciaPlanAccion,        // NUM_INSISTENCIA_PLAN_ACCION
                    $fecAprobacionPlanAccion,         // FEC_APROBACION_PLAN_ACCION
                    $sancion,                         // SANCION
                    $usrCreacion                      // USR_CREACION
                );

                if (!$stmtSupervisiones->execute()) {
                    throw new Exception("Error ejecutando as_supervisiones: " . $stmtSupervisiones->error);
                }

                $idSupervisionInsertada = $stmtSupervisiones->insert_id;
                $stmtSupervisiones->close();

                error_log("Insertado en as_supervisiones. ID: $idSupervisionInsertada");
            } else {
                throw new Exception("Error preparando as_supervisiones: " . $conn->error);
            }
        }
 
        // 3. GUARDAR EN as_correctivas (si hay datos)
        if (isset($data['fec_aprobacion_pa_fisico']) && !empty($data['fec_aprobacion_pa_fisico'])) {
            $queryCorrectivas = "INSERT INTO as_correctivas (
                ID_AVANCES_SUPERVISION, ID_SUPERVISION, RUC, COD_UNICO, FEC_REUNION_COMUNICACION_RESULTADOS,
                FEC_APROBACION_PA_FISICO, NUM_APROBACION_PA_FISICO, FEC_APROBACION_PA_SISTEMA, EST_REGISTRO,
                USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmtCorrectivas = $conn->prepare($queryCorrectivas);
            if ($stmtCorrectivas) {
                // Crear variables para cada campo
                $fecReunionComunicacion = $data['fec_reunion_comunicacion_resultados'] ?? '';
                $fecAprobacionPaFisico = $data['fec_aprobacion_pa_fisico'] ?? '';
                $numAprobacionPaFisico = $data['num_aprobacion_pa_fisico'] ?? '';
                $fecAprobacionPaSistema = $data['fec_aprobacion_pa_sistema'] ?? '';
                $usrCreacion = $data['analista'] ?? 'SISTEMA';

                // Manejar valores null convirtiéndolos a cadenas vacías
                if ($fecReunionComunicacion === null) $fecReunionComunicacion = '';
                if ($fecAprobacionPaFisico === null) $fecAprobacionPaFisico = '';
                if ($numAprobacionPaFisico === null) $numAprobacionPaFisico = '';
                if ($fecAprobacionPaSistema === null) $fecAprobacionPaSistema = '';

                $stmtCorrectivas->bind_param(
                    "iisssssss",
                    $idAvancesSupervision,           // ID_AVANCES_SUPERVISION (integer)
                    $idSupervisionInsertada,         // ID_SUPERVISION (integer)
                    $ruc,                            // RUC (string)
                    $codUnico,                       // COD_UNICO (string)
                    $fecReunionComunicacion,         // FEC_REUNION_COMUNICACION_RESULTADOS (string)
                    $fecAprobacionPaFisico,          // FEC_APROBACION_PA_FISICO (string)
                    $numAprobacionPaFisico,          // NUM_APROBACION_PA_FISICO (string)
                    $fecAprobacionPaSistema,         // FEC_APROBACION_PA_SISTEMA (string)
                    $usrCreacion                     // USR_CREACION (string)
                );

                if (!$stmtCorrectivas->execute()) {
                    throw new Exception("Error ejecutando as_correctivas: " . $stmtCorrectivas->error);
                }

                $idCorrectiva = $stmtCorrectivas->insert_id;
                $stmtCorrectivas->close();

                error_log("Insertado en as_correctivas. ID: $idCorrectiva");
            }
        }

        // 4. GUARDAR EN as_supervision_psi (si hay datos)
        if (isset($data['num_resolucion_psi']) && !empty($data['num_resolucion_psi'])) {
            $querySupervisionPsi = "INSERT INTO as_supervision_psi (
                ID_AVANCES_SUPERVISION
                ,ID_SUPERVISION
                ,RUC
                ,COD_UNICO
                ,FEC_RESOLUCION_PSI
                ,NUM_RESOLUCION_PSI
                ,FEC_IMPOSICION_PSI
                ,NUM_OFICIO_IMPOSICION_PSI
                ,FEC_FIN_PSI
                ,FEC_MEMORANDO_COMUNICACION_PSI
                ,NUM_MEMORANDO_COMUNICACION_PSI
                ,FEC_AMPLIACION_PSI
                ,NUM_AMPLIACION_PSI
                ,FEC_INFORME_AMPLIACION_PSI
                ,NUM_INFORME_AMPLIACION_PSI
                ,EST_REGISTRO
                ,USR_CREACION
                ,FECHA_CREACION
                ,FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmtSupervisionPsi = $conn->prepare($querySupervisionPsi);
            if ($stmtSupervisionPsi) {
                // Crear variables para cada campo
                $fecResolucionPsi = $data['fec_resolucion_psi'] ?? '';
                $numResolucionPsi = $data['num_resolucion_psi'] ?? '';
                $fecImposicionPsi = $data['fec_imposicion_psi'] ?? '';
                $numOficioImposicionPsi = $data['num_oficio_imposicion_psi'] ?? '';
                $fecFinPsi = $data['fec_fin_psi'] ?? '';
                $fecMemorandoComunicacionPsi = $data['fec_memorando_comunicacion_psi'] ?? '';
                $numMemorandoComunicacionPsi = $data['num_memorando_comunicacion_psi'] ?? '';
                $fecAmpliacionPsi = $data['fec_ampliacion_psi'] ?? '';
                $numAmpliacionPsi = $data['num_ampliacion_psi'] ?? '';
                $fecInformeAmpliacionPsi = $data['fec_informe_ampliacion_psi'] ?? '';
                $numInformeAmpliacionPsi = $data['num_informe_ampliacion_psi'] ?? '';

                // Manejar valores null convirtiéndolos a cadenas vacías
                if ($fecResolucionPsi === null) $fecResolucionPsi = '';
                if ($numResolucionPsi === null) $numResolucionPsi = '';
                if ($fecImposicionPsi === null) $fecImposicionPsi = '';
                if ($numOficioImposicionPsi === null) $numOficioImposicionPsi = '';
                if ($fecFinPsi === null) $fecFinPsi = '';
                if ($fecMemorandoComunicacionPsi === null) $fecMemorandoComunicacionPsi = '';
                if ($numMemorandoComunicacionPsi === null) $numMemorandoComunicacionPsi = '';
                if ($fecAmpliacionPsi === null) $fecAmpliacionPsi = '';
                if ($numAmpliacionPsi === null) $numAmpliacionPsi = '';
                if ($fecInformeAmpliacionPsi === null) $fecInformeAmpliacionPsi = '';
                if ($numInformeAmpliacionPsi === null) $numInformeAmpliacionPsi = '';

                $stmtSupervisionPsi->bind_param(
                    "iissssssssssssss",
                    $idAvancesSupervision,           // ID_AVANCES_SUPERVISION (integer)
                    $idSupervisionInsertada,         // ID_SUPERVISION (integer)
                    $ruc,                            // RUC (string)
                    $codUnico,                       // COD_UNICO (string)
                    $fecResolucionPsi,               // FEC_RESOLUCION_PSI (string)
                    $numResolucionPsi,               // NUM_RESOLUCION_PSI (string)
                    $fecImposicionPsi,               // FEC_IMPOSICION_PSI (string)
                    $numOficioImposicionPsi,         // NUM_OFICIO_IMPOSICION_PSI (string)
                    $fecFinPsi,                      // FEC_FIN_PSI (string)
                    $fecMemorandoComunicacionPsi,    // FEC_MEMORANDO_COMUNICACION_PSI (string)
                    $numMemorandoComunicacionPsi,    // NUM_MEMORANDO_COMUNICACION_PSI (string)
                    $fecAmpliacionPsi,               // FEC_AMPLIACION_PSI (string)
                    $numAmpliacionPsi,               // NUM_AMPLIACION_PSI (string)
                    $fecInformeAmpliacionPsi,        // FEC_INFORME_AMPLIACION_PSI (string)
                    $numInformeAmpliacionPsi,        // NUM_INFORME_AMPLIACION_PSI (string)
                    $usrCreacion                     // USR_CREACION (string)
                );

                if (!$stmtSupervisionPsi->execute()) {
                    throw new Exception("Error ejecutando as_supervision_psi: " . $stmtSupervisionPsi->error);
                }

                $idSupervisionPsi = $stmtSupervisionPsi->insert_id;
                $stmtSupervisionPsi->close();

                error_log("Insertado en as_supervision_psi. ID: $idSupervisionPsi");
            }
        }

        // 5. GUARDAR EN as_seguimiento_psi (si hay datos)
        if (isset($data['num_informe_seguimiento']) && !empty($data['num_informe_seguimiento'])) {
            $querySeguimientoPsi = "INSERT INTO as_seguimiento_psi (
                    ID_AVANCES_SUPERVISION,
                    ID_SUPERVISION_PSI,
                    RUC,
                    COD_UNICO,
                    NUM_INFORME_SEGUIMIENTO,
                    FEC_INFORME_SEG,
                    NUM_OFICIO_COMUNICACION_SEG_PSI,
                    FEC_OFICIO_COMUNICACION_SEG_PSI,
                    NUM_OF_APROBACION_PSI_FISICO,
                    FEC_APROBACION_PSI_FISICO,
                    FEC_APROBACION_PSI_SISTEMA,
                    EST_REGISTRO,
                    USR_CREACION,
                    FECHA_CREACION,
                    FECHA_ACTUALIZACION
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";


            $stmtSeguimientoPsi = $conn->prepare($querySeguimientoPsi);
            if ($stmtSeguimientoPsi) {
                // Crear variables para cada campo
                $numInformeSeg = $data['num_informe_seguimiento'] ?? '';
                $fecInformeSeg = $data['fec_informe_seg'] ?? '';
                $numOficioComunicacionSegPsi = $data['num_oficio_comunicacion_seg_psi'] ?? '';
                $fecOficioComunicacionSegPsi = $data['fec_oficio_comunicacion_seg_psi'] ?? '';
                $numOfAprobacionPsiFisico = $data['num_of_aprobacion_psi_fisico'] ?? '';
                $fecAprobacionPsiFisico = $data['fec_aprobacion_psi_fisico'] ?? '';
                $fecAprobacionPsiSistema = $data['fec_aprobacion_psi_sistema'] ?? '';

                // Manejar valores null convirtiéndolos a cadenas vacías
                if ($numInformeSeg === null) $numInformeSeg = '';
                if ($fecInformeSeg === null) $fecInformeSeg = '';
                if ($numOficioComunicacionSegPsi === null) $numOficioComunicacionSegPsi = '';
                if ($fecOficioComunicacionSegPsi === null) $fecOficioComunicacionSegPsi = '';
                if ($numOfAprobacionPsiFisico === null) $numOfAprobacionPsiFisico = '';
                if ($fecAprobacionPsiFisico === null) $fecAprobacionPsiFisico = '';
                if ($fecAprobacionPsiSistema === null) $fecAprobacionPsiSistema = '';

                $stmtSeguimientoPsi->bind_param(
                    "iissssssssss", // 12 parámetros
                    $idAvancesSupervision,           // ID_AVANCES_SUPERVISION (integer)
                    $idSupervisionPsi,               // ID_SUPERVISION_PSI (integer)
                    $ruc,                            // RUC (string)
                    $codUnico,                       // COD_UNICO (string)
                    $numInformeSeg,                  // NUM_INFORME_SEGUIMIENTO (string)
                    $fecInformeSeg,                  // FEC_INFORME_SEG (string)
                    $numOficioComunicacionSegPsi,    // NUM_OFICIO_COMUNICACION_SEG_PSI (string)
                    $fecOficioComunicacionSegPsi,    // FEC_OFICIO_COMUNICACION_SEG_PSI (string)
                    $numOfAprobacionPsiFisico,       // NUM_OF_APROBACION_PSI_FISICO (string)
                    $fecAprobacionPsiFisico,         // FEC_APROBACION_PSI_FISICO (string)
                    $fecAprobacionPsiSistema,        // FEC_APROBACION_PSI_SISTEMA (string)
                    $usrCreacion
                );

                if (!$stmtSeguimientoPsi->execute()) {
                    throw new Exception("Error ejecutando as_supervision_psi: " . $stmtSeguimientoPsi->error);
                }

                $idSeguimientoPsi = $stmtSeguimientoPsi->insert_id;
                $stmtSeguimientoPsi->close();

                error_log("Insertado en as_supervision_psi. ID: $idSeguimientoPsi");
            }
        }

        //6. GUARDAR EN as_levantamiento_psi (si hay datos) 
        if (isset($data['mem_solicitud_cierre_psi']) && !empty($data['mem_solicitud_cierre_psi'])) {

            $queryLevantamientoPsi = "INSERT INTO as_levantamiento_psi (
                ID_AVANCES_SUPERVISION
                ,ID_SUPERVISION_PSI
                ,RUC
                ,COD_UNICO
                ,MEM_SOLICITUD_CIERRE_PSI
                ,FEC_MEM_SOLICITUD_CIERRE_PSI
                ,MEM_ENTREGA_INFORME_CIERRE_PSI
                ,FEC_MEM_ENTREGA_INFORME_CIERRE_PSI
                ,INFORME_CIERRE_PSI
                ,FEC_INFORME_CIERRE_PSI
                ,RESOLUCION_TERMINACION_PSI
                ,FEC_RESOLUCION_TERMINACION_PSI
                ,FEC_REUNION_CIERRE_PSI
                ,FEC_OFICIO_ENVIO_CIERRE_PSI
                ,OF_ENVIO_DOC_CIERRE_PSI
                ,FEC_ENTREGA_INFMR
                ,EST_REGISTRO
                ,USR_CREACION
                ,FECHA_CREACION
                ,FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmtLevantamientoPsi = $conn->prepare($queryLevantamientoPsi);
            if ($stmtLevantamientoPsi) {

                $memSolicitudCierrePsi = $data['mem_solicitud_cierre_psi'] ?? '';
                $fecMemSolicitudCierrePsi = $data['fec_mem_solicitud_cierre_psi'] ?? '';
                $memEntregaInformeCierrePsi = $data['mem_entrega_informe_cierre_psi'] ?? '';
                $fecMemEntregaInformeCierrePsi = $data['fec_mem_entrega_informe_cierre_psi'] ?? '';
                $informeCierrePsi = $data['informe_cierre_psi'] ?? '';
                $fecInformeCierrePsi = $data['fec_informe_cierre_psi'] ?? '';
                $resolucionTerminacionPsi = $data['resolucion_terminacion_psi'] ?? '';
                $fecResolucionTerminacionPsi = $data['fec_resolucion_terminacion_psi'] ?? '';
                $fecReunionCierrePsi = $data['fec_reunion_cierre_psi'] ?? '';
                $fecOficioEnvioCierrePsi = $data['fec_oficio_envio_cierre_psi'] ?? '';
                $ofEnvioDocCierrePsi = $data['of_envio_doc_cierre_psi'] ?? '';
                $fecEntregaInfmr = $data['fec_entrega_infmr'] ?? '';

                // Manejar valores null convirtiéndolos a cadenas vacías
                if ($memSolicitudCierrePsi === null) $memSolicitudCierrePsi = '';
                if ($fecMemSolicitudCierrePsi === null) $fecMemSolicitudCierrePsi = '';
                if ($memEntregaInformeCierrePsi === null) $memEntregaInformeCierrePsi = '';
                if ($fecMemEntregaInformeCierrePsi === null) $fecMemEntregaInformeCierrePsi = '';
                if ($informeCierrePsi === null) $informeCierrePsi = '';
                if ($fecInformeCierrePsi === null) $fecInformeCierrePsi = '';
                if ($resolucionTerminacionPsi === null) $resolucionTerminacionPsi = '';
                if ($fecResolucionTerminacionPsi === null) $fecResolucionTerminacionPsi = '';
                if ($fecReunionCierrePsi === null) $fecReunionCierrePsi = '';
                if ($fecOficioEnvioCierrePsi === null) $fecOficioEnvioCierrePsi = '';
                if ($ofEnvioDocCierrePsi === null) $ofEnvioDocCierrePsi = '';
                if ($fecEntregaInfmr === null) $fecEntregaInfmr = '';

                $stmtLevantamientoPsi->bind_param(
                    "iisssssssssssssss",
                    $idAvancesSupervision,           // ID_AVANCES_SUPERVISION (integer)
                    $idSupervisionPsi,               // ID_SUPERVISION_PSI (integer)
                    $ruc,                            // RUC (string)    
                    $codUnico,                       // COD_UNICO (string)
                    $memSolicitudCierrePsi,          // MEM_SOLICITUD_CIERRE_PSI (string)
                    $fecMemSolicitudCierrePsi,       // FEC_MEM_SOLICITUD_CIERRE_PSI (string)
                    $memEntregaInformeCierrePsi,     // MEM_ENTREGA_INFORME_CIERRE_PSI (string)
                    $fecMemEntregaInformeCierrePsi,  // FEC_MEM_ENTREGA_INFORME_CIERRE_PSI (string)
                    $informeCierrePsi,               // INFORME_CIERRE_PSI (string)
                    $fecInformeCierrePsi,            // FEC_INFORME_CIERRE_PSI (string)
                    $resolucionTerminacionPsi,       // RESOLUCION_TERMINACION_PSI (string)
                    $fecResolucionTerminacionPsi,    // FEC_RESOLUCION_TERMINACION_PSI (string)
                    $fecReunionCierrePsi,            // FEC_REUNION_CIERRE_PSI (string)
                    $fecOficioEnvioCierrePsi,        // FEC_OFICIO_ENVIO_CIERRE_PSI (string)
                    $ofEnvioDocCierrePsi,            // OF_ENVIO_DOC_CIERRE_PSI (string)
                    $fecEntregaInfmr,                // FEC_ENTREGA_INFMR (string)                 
                    $usrCreacion
                );

                if (!$stmtLevantamientoPsi->execute()) {
                    throw new Exception("Error ejecutando as_levantamiento_psi: " . $stmtLevantamientoPsi->error);
                }

                $idLevantamientoPsi = $stmtLevantamientoPsi->insert_id;
                $stmtLevantamientoPsi->close();
                error_log("Insertado en as_levantamiento_psi. ID: $idLevantamientoPsi");
            }
        }

        //7. GUARDAR EN as_liquidacion_psi (si hay datos) 
        if (isset($data['num_informe_final_liq']) && !empty($data['num_informe_final_liq'])) {
            $queryLiquidacion = "INSERT INTO as_liquidacion (
                ID_AVANCES_SUPERVISION
                ,ID_SUPERVISION
                ,RUC
                ,COD_UNICO
                ,NUM_INFORME_FINAL_LIQ
                ,FEC_INFORME_FINAL_LIQ
                ,MEMO_COMUNICACION_IGT
                ,FEC_COMUNICACION_IGT
                ,MEMO_COMUNICACION_IGJ
                ,FEC_COMUNICACION_IGJ
                ,EST_REGISTRO
                ,USR_CREACION
                ,FECHA_CREACION
                ,FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmtLiquidacion = $conn->prepare($queryLiquidacion);
            if ($stmtLiquidacion) {
                // Crear variables para cada campo
                $numInformeLiq = $data['num_informe_final_liq'] ?? '';
                $fecInformeLiq = $data['fec_informe_final_liq'] ?? '';
                $memoComunicacionIgt = $data['memo_comunicacion_igt'] ?? '';
                $fecComunicacionIgt = $data['fec_comunicacion_igt'] ?? '';
                $memoComunicacionIgj = $data['memo_comunicacion_igj'] ?? '';
                $fecComunicacionIgj = $data['fec_comunicacion_igj'] ?? '';

                // Manejar valores null convirtiéndolos a cadenas vacías
                if ($numInformeLiq === null) $numInformeLiq = '';
                if ($fecInformeLiq === null) $fecInformeLiq = '';
                if ($memoComunicacionIgt === null) $memoComunicacionIgt = '';
                if ($fecComunicacionIgt === null) $fecComunicacionIgt = '';
                if ($memoComunicacionIgj === null) $memoComunicacionIgj = '';
                if ($fecComunicacionIgj === null) $fecComunicacionIgj = '';

                $stmtLiquidacion->bind_param(
                    "iisssssssss",
                    $idAvancesSupervision,
                    $idSupervisionInsertada,
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

                if (!$stmtLiquidacion->execute()) {
                    throw new Exception("Error ejecutando as_liquidacion: " . $stmtLiquidacion->error);
                }

                $idLiquidacion = $stmtLiquidacion->insert_id;
                error_log("Insertado en as_liquidacion. ID: $idLiquidacion");

                $stmtLiquidacion->close();
            }
        }

        //8. GUARDAR EN as_alertas (si hay datos)
        if (isset($data['tipo_alerta']) && !empty($data['tipo_alerta'])) {
            $queryAlertas = "INSERT INTO as_alertas (
                ID_AVANCES_SUPERVISION
                ,RUC
                ,COD_UNICO
                ,TIPO_ALERTA
                ,DESCRIPCION_ALERTA
                ,FEC_INICIO_SUPERVISION_ALERTA
                ,FEC_INFORME_ALERTA
                ,NUM_INFORME_ALERTA
                ,FEC_OF_COMUNICACION_ALERTA
                ,NUM_OF_COMUNICACION_ALERTA
                ,FEC_APROBACION_SSI
                ,EST_REGISTRO
                ,USR_CREACION
                ,FECHA_CREACION
                ,FECHA_ACTUALIZACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', ?, NOW(), NOW())";

            $stmtAlertas = $conn->prepare($queryAlertas);
            if ($stmtAlertas) {
                // Crear variables para cada campo
                $tipoAlerta = $data['tipo_alerta'] ?? '';
                $descripcionAlerta = $data['descripcion_alerta'] ?? '';
                $fecInicioSupervisionAlerta = $data['fec_inicio_supervision_alerta'] ?? '';
                $fecInformeAlerta = $data['fec_informe_alerta'] ?? '';
                $numInformeAlerta = $data['num_informe_alerta'] ?? '';
                $fecOfComunicacionAlerta = $data['fec_of_comunicacion_alerta'] ?? '';
                $numOfComunicacionAlerta = $data['num_of_comunicacion_alerta'] ?? '';
                $fecAprobacionSsi = $data['fec_aprobacion_ssi'] ?? '';

                //manejar valores null convirtiéndolos a cadenas vacías
                if ($tipoAlerta === null) $tipoAlerta = '';
                if ($descripcionAlerta === null) $descripcionAlerta = '';
                if ($fecInicioSupervisionAlerta === null) $fecInicioSupervisionAlerta = '';
                if ($fecInformeAlerta === null) $fecInformeAlerta = '';
                if ($numInformeAlerta === null) $numInformeAlerta = '';
                if ($fecOfComunicacionAlerta === null) $fecOfComunicacionAlerta = '';
                if ($numOfComunicacionAlerta === null) $numOfComunicacionAlerta = '';
                if ($fecAprobacionSsi === null) $fecAprobacionSsi = '';


                $stmtAlertas->bind_param(
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

                if (!$stmtAlertas->execute()) {
                    throw new Exception("Error ejecutando as_alertas: " . $stmtAlertas->error);
                }

                $idAlerta = $stmtAlertas->insert_id;
                error_log("Insertado en as_alertas. ID: $idAlerta");

                $stmtAlertas->close();
            }
        }

        // Confirmar transacción
        $conn->commit();

        error_log("Guardado exitoso. ID: $idAvancesSupervision, Código: $codUnico");

        // Usar echo json_encode() en lugar de return
        echo json_encode([
            'success' => true,
            'id_avances_supervision' => $idAvancesSupervision,
            'cod_unico' => $codUnico,
            //'datosG' => $data, // Opcional: para debugging
            'message' => 'Datos guardados exitosamente en cascada'
        ]);

        // Asegurarse de que el script termine después de enviar la respuesta
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error en guardado en cascada - Mensaje: " . $e->getMessage());
        throw new Exception("Error en guardado en cascada: " . $e->getMessage());
    }
} // Cierre de la función guardarSupervision


/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
// Evaluar POST para determinar la accion a seguir
/////////////////////////////////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // switch para determinar la action
    switch (isset($_GET['action']) && $_GET['action']) {
        case 'guardar_supervision':
            // guardar en una variable los datos recibidos
            $datos_recibidos = file_get_contents('php://input');
            // Decodificar los datos JSON
            $datos_decode_json = json_decode($datos_recibidos, true);
            // Llamar a la función para guardar la supervisión
            guardarSupervision($datos_decode_json);
            break;
        case 'eliminar_supervision':
            // Llamar a la función para eliminar la supervisión
            //eliminarSupervision($_POST['supervision_id']);
            break;
        default:
            // Acción no reconocida     
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Acción no reconocida']);
            exit;
    }
}
