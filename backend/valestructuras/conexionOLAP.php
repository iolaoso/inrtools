<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

// Incluir el archivo de ADODB
require_once BASE_PATH . '/assets/adodb5/adodb.inc.php'; // Ajusta la ruta según tu estructura

// Configuración de conexión
$connectionString = "Provider=MSOLAP;Data Source=frigga;Initial Catalog=SEPS_NET_VALIDACION_ESTRUCTURAS;";
$conn = ADONewConnection('ado');

// Intentar conectar
if ($conn->Connect($connectionString)) {
    echo json_encode(['status' => 'Conectado']);
} else {
    echo json_encode(['status' => 'No se pudo conectar: ' . $conn->ErrorMsg()]);
}

// Cerrar conexión
$conn->Close();
