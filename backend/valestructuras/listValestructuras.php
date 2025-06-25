<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
require_once BASE_PATH . '/assets/adodb5/adodb.inc.php'; // Ajusta la ruta según tu estructura
require_once BASE_PATH . 'backend/conexiones/SPSS_connection.php'; // archivo que contiene la conexion OLAP

function valEstructurasRuc($ruc)
{
    try {
        $connSPSS = getConnectionSPSS();
        if (!$connSPSS) {
            throw new Exception('Error al conectar a SPSS');
        }

        // Aquí puedes realizar la consulta a la base de datos usando $connSPSS
        $query = "SELECT NUM_RUC,
                         TRUNC(FECHA_CORTE) AS FECHA_CORTE
                  FROM ODS.ODS_MVW_ACOPIO_C02 
                  WHERE TRUNC(FECHA_CORTE) >= TO_DATE('2025-01-01', 'YYYY-MM-DD')
                  AND NUM_RUC = ?
                  GROUP BY NUM_RUC, TRUNC(FECHA_CORTE);";
        $stmt = $connSPSS->prepare($query);
        // Vincular el parámetro RUC AL PRIMER PARAMETRO ?
        $stmt->bindParam(1, $ruc, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $connSPSS = null; // Cerrar la conexión
        return $result;
    } catch (Exception $e) {
        error_log($e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'Error',
            'message' => 'Error interno del servidor'
        ]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener el RUC de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que el RUC esté presente y no esté vacío
    if (empty($data['ruc'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'Error',
            'message' => 'RUC no puede estar vacio'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Datos obtenidos correctamente',
        'datos' => valEstructurasRuc($data['ruc'])
    ]);
}
