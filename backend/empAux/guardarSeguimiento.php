<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$idSeguimiento = $data['idSeguimiento'] ?? null;

$idEntidad = $data['idEntidad'];
$ruc = $data['ruc'];
$periodo = $data['periodo'];
$fechaCorte = $data['fechaCorte'];

$numOfComunicacionRes = mb_strtoupper($data['numOfComunicacionRes']);
$fechaOfComunicacionRes = $data['fechaOfComunicacionRes'];
$numResolucion = mb_strtoupper($data['numResolucion']);
$fechaResolucion = $data['fechaResolucion'];


$oficioSeguimiento = mb_strtoupper($data['oficioSeguimiento']);
$fechaOfSeguimiento = $data['fechaOfSeguimiento'];

$indRegularizado = $data['indRegularizado'];

$estadoSeguimiento  = ($indRegularizado == 1) ? 4: (!empty($data['estadoSeguimiento']) ? $data['estadoSeguimiento'] : null);
$estadoCumplimiento = ($indRegularizado == 1) ? 1: (!empty($data['estadoCumplimiento']) ? $data['estadoCumplimiento'] : null);

$motivoIncumplimiento = !empty($data['motivoIncumplimiento']) ? $data['motivoIncumplimiento'] : null;

$tipoOficio = !empty($data['tipoOficio']) ? $data['tipoOficio']: null;

$fechaReunion = !empty($data['fechaReunion']) ? $data['fechaReunion'] : null;
$numActaReunion = !empty($data['numActaReunion']) ? trim($data['numActaReunion']) : null;
$fechaInformeSeg = !empty($data['fechaInformeSeg']) ? $data['fechaInformeSeg'] : null;
$numInformeSeg = !empty($data['numInformeSeg']) ? trim($data['numInformeSeg']) : null;

$observacionDns = $data['observacionDns'];

$usuario = $_SESSION['nickname'] ?? 'Desconocido';

try {

    // ==========================================
    // UPDATE
    // ==========================================
    if (!empty($idSeguimiento)) {

        $sql = "
            UPDATE seguimiento
            SET
                OFICIO_SEGUIMIENTO = ?,
                ID_TIPO_ULTIMO_OFICIO_ENVIADO = ?,
                FECHA_OF_SEGUIMIENTO = ?,
                ID_ESTADO_CUMPLIMIENTO = ?,
                ID_MOTIVO_INCUMPLIMIENTO = ?,
                ID_ESTADO_SEGUIMIENTO = ?,
                IND_REGULARIZADO = ?,
                FECHA_REUNION = ?,
                NUM_ACTA_REUNION = ?,
                FECHA_INFORME_SEG = ?,
                NUM_INFORME_SEG = ?,
                OBSERVACION_DNS = ?,
                USR_CREACION = ?,
                UPDATED_AT = NOW()
            WHERE ID_SEGUIMIENTO = ?
        ";

        $stmt = $connEmpAux->prepare($sql);

        $stmt->bind_param(
            "sisiiiissssssi",
            $oficioSeguimiento,
            $tipoOficio,
            $fechaOfSeguimiento,
            $estadoCumplimiento,
            $motivoIncumplimiento,
            $estadoSeguimiento,
            $indRegularizado,
            $fechaReunion,
            $numActaReunion,
            $fechaInformeSeg,
            $numInformeSeg,
            $observacionDns,
            $usuario,
            $idSeguimiento
        );

        $ok = $stmt->execute();

        echo json_encode([
            'success' => $ok,
            'accion' => 'update'
        ]);

        exit;
    }

    // ==========================================
    // NUEVO SEGUIMIENTO (INSERT)
    // ==========================================

    $sqlNumero = "
        SELECT COALESCE(MAX(NUMERO_SEGUIMIENTO),0) + 1 AS NUMERO
        FROM seguimiento
        WHERE ID_ENTIDAD = ?
    ";

    $stmtNumero = $connEmpAux->prepare($sqlNumero);
    $stmtNumero->bind_param("i", $idEntidad);
    $stmtNumero->execute();

    $numeroSeguimiento = $stmtNumero
        ->get_result()
        ->fetch_assoc()['NUMERO'];

    // Desactivar seguimientos anteriores
    $sqlUpdate = "
        UPDATE seguimiento
        SET EST_REGISTRO = 0,
            UPDATED_AT = NOW()
        WHERE ID_ENTIDAD = ?
        AND EST_REGISTRO = 1
    ";

    $stmtUpdate = $connEmpAux->prepare($sqlUpdate);
    $stmtUpdate->bind_param("i", $idEntidad);
    $stmtUpdate->execute();


    // Insertar nuevo seguimiento activo
    $sql = "
        INSERT INTO seguimiento(
            ID_ENTIDAD,
            RUC,
            PERIODO,
            FECHA_CORTE,
            NUMERO_SEGUIMIENTO,
            OFICIO_SEGUIMIENTO,
            ID_TIPO_ULTIMO_OFICIO_ENVIADO,
            FECHA_OF_SEGUIMIENTO,
            ID_ESTADO_CUMPLIMIENTO,
            ID_MOTIVO_INCUMPLIMIENTO,
            ID_ESTADO_SEGUIMIENTO,
            IND_REGULARIZADO,
            FECHA_REUNION,
            NUM_ACTA_REUNION,
            FECHA_INFORME_SEG,
            NUM_INFORME_SEG,
            OBSERVACION_DNS,
            EST_REGISTRO,
            USR_CREACION,
            CREATED_AT,
            UPDATED_AT
        )
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, NOW(), NOW()
        )
    ";

    $stmt = $connEmpAux->prepare($sql);

    $stmt = $connEmpAux->prepare($sql);

    $stmt->bind_param(
        "isisissiiiisssssss",
        $idEntidad,
        $ruc,
        $periodo,
        $fechaCorte,
        $numeroSeguimiento,
        $oficioSeguimiento,
        $tipoOficio,
        $fechaOfSeguimiento,
        $estadoCumplimiento,
        $motivoIncumplimiento,
        $estadoSeguimiento,
        $indRegularizado,
        $fechaReunion,
        $numActaReunion,
        $fechaInformeSeg,
        $numInformeSeg,
        $observacionDns,
        $usuario
    );

    $ok = $stmt->execute();

    echo json_encode([
        'success' => $ok,
        'accion' => 'insert',
        'numeroSeguimiento' => $numeroSeguimiento
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}