<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$idEntidad         = $data['idEntidad'] ?? null;
$ruc               = $data['ruc'] ?? null;
$fechaRes          = $data['fecRes'] ?? null;
$periodo           = $data['periodo'] ?? null;
$fechaCorte        = $data['fechaCorte'] ?? null;

$oficioComRes      = $data['OfComunicacionRes'] ?? null;
$fecOfComRes       = !empty($data['fecOfComRes']) ? $data['fecOfComRes'] : null;
$fecRegularizacion = !empty($data['fecRegularizacion']) ? $data['fecRegularizacion'] : null;
$fecProrroga       = !empty($data['fecProrroga']) ? $data['fecProrroga'] : null;
$obsDNR            = trim($data['obsDNR'] ?? '');

$usuario = $_SESSION['nickname'] ?? 'DESCONOCIDO';

try {
    // INSERT
    $sql = "INSERT INTO proceso_dnr (ID_ENTIDAD,RUC,FECHA_RESOLUCION,NUM_PERIODO,FECHA_CORTE,
                OFICIO_COMUNICACION_RES,FECHA_COMUNICACION_RES,FECHA_REGULARIZACION,FECHA_PRORROGA,OBSERVACION_DNR,
                EST_REGISTRO,USR_CREACION,CREATED_AT,UPDATED_AT)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, NOW(),NOW())";
    $stmt = $connEmpAux->prepare($sql);
    $stmt->bind_param("ississsssss",$idEntidad,$ruc,$fechaRes,$periodo,$fechaCorte,$oficioComRes,$fecOfComRes,
                        $fecRegularizacion,$fecProrroga,$obsDNR,$usuario);
    $ok = $stmt->execute();

    echo json_encode([
        'success' => $ok,
        'accion' => 'insert',
        'idProcesoDNR' => $connEmpAux->insert_id
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}