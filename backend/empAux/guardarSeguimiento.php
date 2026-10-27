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

$idEstadoSeguimiento = $data['idEstadoSeguimiento'];
$oficioSeguimiento = $data['oficioSeguimiento'];
$fechaOfSeguimiento = $data['fechaOfSeguimiento'];

$idEstadoCumplimiento = $data['idEstadoCumplimiento'];
$motivoIncumplimiento = !empty($data['motivoIncumplimiento'])
    ? $data['motivoIncumplimiento']
    : null;

$indRegularizado = $data['indRegularizado'];
$observacionDns = $data['observacionDns'];

$usuario = $_SESSION['usuario'] ?? 'SISTEMA';

try {

    // ==========================================
    // UPDATE
    // ==========================================
    if (!empty($idSeguimiento)) {

        $sql = "
            UPDATE seguimiento
            SET
                OFICIO_SEGUIMIENTO = ?,
                FECHA_OF_SEGUIMIENTO = ?,
                ID_ESTADO_CUMPLIMIENTO = ?,
                ID_MOTIVO_INCUMPLIMIENTO = ?,
                ID_ESTADO_SEGUIMIENTO = ?,
                IND_REGULARIZADO = ?,
                OBSERVACION_DNS = ?,
                USR_CREACION = ?,
                UPDATED_AT = NOW()
            WHERE ID_SEGUIMIENTO = ?
        ";

        $stmt = $connEmpAux->prepare($sql);

        $stmt->bind_param(
            "ssiiiissi",
            $oficioSeguimiento,
            $fechaOfSeguimiento,
            $idEstadoCumplimiento,
            $motivoIncumplimiento,
            $idEstadoSeguimiento,
            $indRegularizado,
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

    $sql = "
        INSERT INTO seguimiento(
            ID_ENTIDAD,
            RUC,
            PERIODO,
            FECHA_CORTE,
            NUMERO_SEGUIMIENTO,
            OFICIO_SEGUIMIENTO,
            FECHA_OF_SEGUIMIENTO,
            ID_ESTADO_CUMPLIMIENTO,
            ID_MOTIVO_INCUMPLIMIENTO,
            ID_ESTADO_SEGUIMIENTO,
            IND_REGULARIZADO,
            OBSERVACION_DNS,
            EST_REGISTRO,
            USR_CREACION,
            CREATED_AT,
            UPDATED_AT
        )
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, NOW(), NOW()
        )
    ";

    $stmt = $connEmpAux->prepare($sql);

    $stmt->bind_param(
        "isisissiiiiss",
        $idEntidad,
        $ruc,
        $periodo,
        $fechaCorte,
        $numeroSeguimiento,
        $oficioSeguimiento,
        $fechaOfSeguimiento,
        $idEstadoCumplimiento,
        $motivoIncumplimiento,
        $idEstadoSeguimiento,
        $indRegularizado,
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