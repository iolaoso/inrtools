<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos


// Función para guardar una nueva supervisión en la base de datos
function guardarSupervision($data) {
    global $conn;

    if (!$conn || $conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Iniciar transacción para asegurar consistencia
    $conn->begin_transaction();

    try {
        // 1. GUARDAR EN as_avances_supervision (tabla principal)
        $queryAvances = "INSERT INTO as_avances_supervision (
            COD_UNICO,RUC,RAZON_SOCIAL,SEGMENTO,RESPONSABLE,CATALOGO_ID
            ,FEC_ASIG,ANIO_PLAN,TRIM_PLAN
            ,EST_REGISTRO,USR_CREACION,FECHA_CREACION,FECHA_ACTUALIZACION
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

        $stmtAvances = $conn->prepare($queryAvances);
        if (!$stmtAvances) {
            throw new Exception("Error preparando as_avances_supervision: " . $conn->error);
        }

        $stmtAvances->bind_param(
            "sssssssssssss", 
            $data['cod_unico'], 
            $data['ruc'], 
            $data['razon_social'],
            $data['segmento'],
            $data['responsable'], 
            $data['catalogo_id'], 
            $data['fec_asig'], 
            $data['anio_plan'], 
            $data['trim_plan']
        );

        if (!$stmtAvances->execute()) {
            throw new Exception("Error ejecu00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000tando as_avances_supervision: " . $stmtAvances->error);
        }

        $idAvancesSupervision = $stmtAvances->insert_id;
        $stmtAvances->close();

        // 2. GUARDAR EN as_supervisiones (si hay datos)
        if (isset($data['supervisiones']) && !empty($data['supervisiones'])) {
            $querySupervisiones = "INSERT INTO as_supervisiones (
                ID_AVANCES_SUPERVISION, RUC, COD_UNICO, FEC_SOLICITUD, NUM_OFICIO_SOLICITUD,
                FEC_INSISTENCIA, NUM_OFICIO_INSISTENCIA, FEC_COMUNICACION, NUM_OFICIO_RESULTADOS,
                FEC_LIMITE_ENTREGA, NUM_OFICIO_RESPUESTA, FEC_RESPUESTA, FEC_INFORME_FINAL,
                NUM_INFORME_FINAL, FEC_COMUNICACION_FINAL, NUM_COMUNICACION_FINAL,
                FEC_LIMITE_PLAN_ACCION, FEC_INSISTENCIA_PLAN_ACCION, NUM_INSISTENCIA_PLAN_ACCION,
                FEC_APROBACION_PLAN_ACCION, SANCION, EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtSupervisiones = $conn->prepare($querySupervisiones);
            if ($stmtSupervisiones) {
                $sup = $data['supervisiones'];
                $stmtSupervisiones->bind_param(
                    "issssssssssssssssssss",
                    $idAvancesSupervision, $data['ruc'], $data['cod_unico'],
                    $sup['fec_solicitud'], $sup['num_oficio_solicitud'],
                    $sup['fec_insistencia'], $sup['num_oficio_insistencia'],
                    $sup['fec_comunicacion'], $sup['num_oficio_resultados'],
                    $sup['fec_limite_entrega'], $sup['num_oficio_respuesta'],
                    $sup['fec_respuesta'], $sup['fec_informe_final'],
                    $sup['num_informe_final'], $sup['fec_comunicacion_final'],
                    $sup['num_comunicacion_final'], $sup['fec_limite_plan_accion'],
                    $sup['fec_insistencia_plan_accion'], $sup['num_insistencia_plan_accion'],
                    $sup['fec_aprobacion_plan_accion'], $sup['sancion']
                );
                $stmtSupervisiones->execute();
                $idSupervision = $stmtSupervisiones->insert_id;
                $stmtSupervisiones->close();
            }
        }

        // 3. GUARDAR EN as_correctivas (si hay datos)
        if (isset($data['correctivas']) && !empty($data['correctivas'])) {
            $queryCorrectivas = "INSERT INTO as_correctivas (
                ID_SUPERVISION, RUC, COD_UNICO, FEC_REUNION_COMUNICACION_RESULTADOS,
                FEC_APROBACION_PA_FISICO, NUM_APROBACION_PA_FISICO, FEC_APROBACION_PA_SISTEMA,
                EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtCorrectivas = $conn->prepare($queryCorrectivas);
            if ($stmtCorrectivas) {
                $corr = $data['correctivas'];
                $stmtCorrectivas->bind_param(
                    "issssss",
                    $idSupervision, $data['ruc'], $data['cod_unico'],
                    $corr['fec_reunion_comunicacion_resultados'],
                    $corr['fec_aprobacion_pa_fisico'], $corr['num_aprobacion_pa_fisico'],
                    $corr['fec_aprobacion_pa_sistema']
                );
                $stmtCorrectivas->execute();
                $idCorrectiva = $stmtCorrectivas->insert_id;
                $stmtCorrectivas->close();
            }
        }

        // 4. GUARDAR EN as_supervision_psi (si hay datos)
        if (isset($data['supervision_psi']) && !empty($data['supervision_psi'])) {
            $querySupervisionPsi = "INSERT INTO as_supervision_psi (
                ID_CORRECTIVA, RUC, COD_UNICO, FEC_RESOLUCION_PSI, NUM_RESOLUCION_PSI,
                FEC_IMPOSICION_PSI, NUM_OFICIO_IMPOSICION_PSI, FEC_FIN_PSI,
                FEC_MEMORANDO_COMUNICACION_PSI, NUM_MEMORANDO_COMUNICACION_PSI,
                FEC_AMPLIACION_PSI, NUM_AMPLIACION_PSI, FEC_INFORME_AMPLIACION_PSI,
                NUM_INFORME_AMPLIACION_PSI, EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtSupervisionPsi = $conn->prepare($querySupervisionPsi);
            if ($stmtSupervisionPsi) {
                $psi = $data['supervision_psi'];
                $stmtSupervisionPsi->bind_param(
                    "isssssssssssss",
                    $idCorrectiva, $data['ruc'], $data['cod_unico'],
                    $psi['fec_resolucion_psi'], $psi['num_resolucion_psi'],
                    $psi['fec_imposicion_psi'], $psi['num_oficio_imposicion_psi'],
                    $psi['fec_fin_psi'], $psi['fec_memorando_comunicacion_psi'],
                    $psi['num_memorando_comunicacion_psi'], $psi['fec_ampliacion_psi'],
                    $psi['num_ampliacion_psi'], $psi['fec_informe_ampliacion_psi'],
                    $psi['num_informe_ampliacion_psi']
                );
                $stmtSupervisionPsi->execute();
                $idSupervisionPsi = $stmtSupervisionPsi->insert_id;
                $stmtSupervisionPsi->close();
            }
        }

        // 5. GUARDAR EN as_seguimiento_psi (si hay datos)
        if (isset($data['seguimiento_psi']) && !empty($data['seguimiento_psi'])) {
            $querySeguimientoPsi = "INSERT INTO as_seguimiento_psi (
                ID_SUPERVISION_PSI, RUC, COD_UNICO, NUM_INFORME_SEGUIMIENTO, FEC_INFORME,
                NUM_OFICIO_COMUNICACION_SEG_PSI, FEC_OFICIO_COMUNICACION_SEG_PSI,
                NUM_OF_APROBACION_PSI_FISICO, FEC_APROBACION_PSI_FISICO,
                FEC_APROBACION_PSI_SISTEMA, EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtSeguimientoPsi = $conn->prepare($querySeguimientoPsi);
            if ($stmtSeguimientoPsi) {
                $seg = $data['seguimiento_psi'];
                $stmtSeguimientoPsi->bind_param(
                    "isssssssss",
                    $idSupervisionPsi, $data['ruc'], $data['cod_unico'],
                    $seg['num_informe_seguimiento'], $seg['fec_informe'],
                    $seg['num_oficio_comunicacion_seg_psi'], $seg['fec_oficio_comunicacion_seg_psi'],
                    $seg['num_of_aprobacion_psi_fisico'], $seg['fec_aprobacion_psi_fisico'],
                    $seg['fec_aprobacion_psi_sistema']
                );
                $stmtSeguimientoPsi->execute();
                $stmtSeguimientoPsi->close();
            }
        }

        // 6. GUARDAR EN as_levantamiento_psi (si hay datos)
        if (isset($data['levantamiento_psi']) && !empty($data['levantamiento_psi'])) {
            $queryLevantamientoPsi = "INSERT INTO as_levantamiento_psi (
                ID_SUPERVISION_PSI, RUC, COD_UNICO, MEM_SOLICITUD_CIERRE_PSI,
                FEC_MEM_SOLICITUD_CIERRE_PSI, MEM_ENTREGA_INFORME_CIERRE_PSI,
                FEC_MEM_ENTREGA_INFORME_CIERRE_PSI, INFORME_CIERRE_PSI,
                FEC_INFORME_CIERRE_PSI, RESOLUCION_TERMINACION_PSI,
                FEC_RESOLUCION_TERMINACION_PSI, FEC_REUNION_CIERRE_PSI,
                FEC_OFICIO_ENVIO_CIERRE_PSI, OF_ENVIO_DOC_CIERRE_PSI,
                FEC_ENTREGA_INFMR, EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtLevantamientoPsi = $conn->prepare($queryLevantamientoPsi);
            if ($stmtLevantamientoPsi) {
                $lev = $data['levantamiento_psi'];
                $stmtLevantamientoPsi->bind_param(
                    "issssssssssssss",
                    $idSupervisionPsi, $data['ruc'], $data['cod_unico'],
                    $lev['mem_solicitud_cierre_psi'], $lev['fec_mem_solicitud_cierre_psi'],
                    $lev['mem_entrega_informe_cierre_psi'], $lev['fec_mem_entrega_informe_cierre_psi'],
                    $lev['informe_cierre_psi'], $lev['fec_informe_cierre_psi'],
                    $lev['resolucion_terminacion_psi'], $lev['fec_resolucion_terminacion_psi'],
                    $lev['fec_reunion_cierre_psi'], $lev['fec_oficio_envio_cierre_psi'],
                    $lev['of_envio_doc_cierre_psi'], $lev['fec_entrega_infmr']
                );
                $stmtLevantamientoPsi->execute();
                $stmtLevantamientoPsi->close();
            }
        }

        // 7. GUARDAR EN as_liquidacion (si hay datos)
        if (isset($data['liquidacion']) && !empty($data['liquidacion'])) {
            $queryLiquidacion = "INSERT INTO as_liquidacion (
                ID_AVANCES_SUPERVISION, ID_SUPERVISION, RUC, COD_UNICO,
                NUM_INFORME_FINAL, FEC_INFORME_FINAL, MEMO_COMUNICACION_IGT,
                FEC_COMUNICACION_IGT, MEMO_COMUNICACION_IGJ, FEC_COMUNICACION_IGJ,
                EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtLiquidacion = $conn->prepare($queryLiquidacion);
            if ($stmtLiquidacion) {
                $liq = $data['liquidacion'];
                $stmtLiquidacion->bind_param(
                    "iissssssss",
                    $idAvancesSupervision, $idSupervision, $data['ruc'], $data['cod_unico'],
                    $liq['num_informe_final'], $liq['fec_informe_final'],
                    $liq['memo_comunicacion_igt'], $liq['fec_comunicacion_igt'],
                    $liq['memo_comunicacion_igj'], $liq['fec_comunicacion_igj']
                );
                $stmtLiquidacion->execute();
                $stmtLiquidacion->close();
            }
        }

        // 8. GUARDAR EN as_alertas (si hay datos)
        if (isset($data['alertas']) && !empty($data['alertas'])) {
            $queryAlertas = "INSERT INTO as_alertas (
                ID_AVANCES_SUPERVISION, RUC, COD_UNICO, TIPO_ALERTA,
                FEC_INFORME_ALERTA, NUM_INFORME_ALERTA, FEC_OF_COMUNICACION_ALERTA,
                NUM_OF_COMUNICACION_ALERTA, FEC_APROBACION_SSI, TIPO_SUPERVISION,
                ESTADO_PROCESO, OBSERVACION_DEL_ESTADO, EST_REGISTRO, FECHA_CREACION
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACT', NOW())";

            $stmtAlertas = $conn->prepare($queryAlertas);
            if ($stmtAlertas) {
                $alert = $data['alertas'];
                $stmtAlertas->bind_param(
                    "isssssssssss",
                    $idAvancesSupervision, $data['ruc'], $data['cod_unico'],
                    $alert['tipo_alerta'], $alert['fec_informe_alerta'],
                    $alert['num_informe_alerta'], $alert['fec_of_comunicacion_alerta'],
                    $alert['num_of_comunicacion_alerta'], $alert['fec_aprobacion_ssi'],
                    $alert['tipo_supervision'], $alert['estado_proceso'],
                    $alert['observacion_del_estado']
                );
                $stmtAlertas->execute();
                $stmtAlertas->close();
            }
        }

        // Confirmar transacción
        $conn->commit();

        return [
            'success' => true,
            'id_avances_supervision' => $idAvancesSupervision,
            'message' => 'Datos guardados exitosamente en cascada'
        ];

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conn->rollback();
        throw new Exception("Error en guardado en cascada: " . $e->getMessage());
    }
}


/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
// Evaluar POST para determinar la accion a seguir
/////////////////////////////////////////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // switch para determinar la action
    switch (isset($_GET['action']) && $_GET['action']) {
        case 'guardar_supervision':
            // Llamar a la función para guardar la supervisión
            guardarSupervision($_POST);
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
        

    


     