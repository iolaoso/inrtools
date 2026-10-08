<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php'; // Asegúrate de incluir la conexión a la base de datos

$data = json_decode(file_get_contents("php://input"), true);

$idEntidad = $data['idEntidad'];
$ruc = $data['ruc'];
$fechaCorte = $data['fechaCorte'];

$estadoSeguimiento = $data['estadoSeguimiento'];
$oficioSeguimiento = $data['oficioSeguimiento'];
$fechaOfSeguimiento = $data['fechaOfSeguimiento'];

$estadoCumplimiento = $data['estadoCumplimiento'];
$motivoIncumplimiento = $data['motivoIncumplimiento'];

$indRegularizado = $data['indRegularizado'];
$observacionDns = $data['observacionDns'];

$usuario = $_SESSION['usuario'] ?? 'SISTEMA';

$sqlNumero = "
    SELECT COALESCE(MAX(NUMERO_SEGUIMIENTO),0) + 1 AS NUMERO
    FROM seguimiento
    WHERE ID_ENTIDAD = ?
";

// Obtener consecutivo del seguimiento
$stmtNumero = $connEmpAux->prepare($sqlNumero);
$stmtNumero->bind_param("i", $idEntidad);
$stmtNumero->execute();

$numeroSeguimiento = $stmtNumero
    ->get_result()
    ->fetch_assoc()['NUMERO'];

//insert
$sql = "
INSERT INTO seguimiento(
    ID_ENTIDAD,
    RUC,
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
    CREATED_AT
)
VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, NOW()
)
";

$stmt = $connEmpAux->prepare($sql);

$stmt->bind_param(
    "ississiiiiss",
    $idEntidad,
    $ruc,
    $fechaCorte,
    $numeroSeguimiento,
    $oficioSeguimiento,
    $fechaOfSeguimiento,
    $estadoCumplimiento,
    $motivoIncumplimiento,
    $estadoSeguimiento,
    $indRegularizado,
    $observacionDns,
    $usuario
);

$ok = $stmt->execute();


// respuesta JSON
header('Content-Type: application/json');
if ($ok) {
    echo json_encode([
        'success' => true,
        'numeroSeguimiento' => $numeroSeguimiento
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => $stmt->error
    ]);
}

