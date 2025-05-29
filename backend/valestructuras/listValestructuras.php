<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

// Incluir el archivo de ADODB
require_once BASE_PATH . '/assets/adodb5/adodb.inc.php'; // Ajusta la ruta según tu estructura

// Verificar que se reciba el RUC por POST
$ruc = isset($_POST['ruc']) ? htmlspecialchars($_POST['ruc']) : '';
if (empty($ruc)) {
    echo json_encode(['error' => 'RUC es requerido.']);
    exit;
}

// Configuración de conexión
$connectionString = "Provider=MSOLAP;Data Source=frigga;Initial Catalog=SEPS_NET_VALIDACION_ESTRUCTURAS;";
$conn = ADONewConnection('ado');

// Intentar conectar
if ($conn->Connect($connectionString)) {
    // Consulta MDX para buscar el RUC específico
    $query = "
    SELECT NON EMPTY { [Measures].[CONTEO REGISTROS] } ON COLUMNS, 
    NON EMPTY { 
        ([DW COOPERATIVA RSFPS DIM].[RUC].[RUC].&[$ruc] * 
        [DW ESTRUCTURAS DIM].[COD ESTRUCTURA].[COD ESTRUCTURA].ALLMEMBERS) 
    } DIMENSION PROPERTIES MEMBER_CAPTION, MEMBER_UNIQUE_NAME ON ROWS 
    FROM [BITACORA DIARIA] 
    WHERE ( 
        [DW ESTADO REGISTRO DIM].[ESTADO REGISTRO].&[10], 
        [DW ESTADO JURIDICO DIM].[ESTADO JURIDICO].&[15], 
        [DW ESTADO VALIDACION ESTRUCTURAS DIM].[COD ESTADO VALIDACION ESTRUCTURAS].&[PC] 
    ) 
    CELL PROPERTIES VALUE
    ";

    // Ejecutar la consulta
    $rs = $conn->Execute($query);
    if (!$rs) {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->ErrorMsg()]);
    } else {
        // Obtener resultados
        $data = [];
        while (!$rs->EOF) {
            $data[] = [
                'cod_estructura' => $rs->Fields[0]->Value ?? null,
                'nombre_estructura' => $rs->Fields[1]->Value ?? null,
                'fecha_corte' => $rs->Fields[2]->Value ?? null
            ];
            $rs->MoveNext();
        }
        echo json_encode(['data' => $data]);
    }
} else {
    echo json_encode(['status' => 'No se pudo conectar: ' . $conn->ErrorMsg()]);
}

// Cerrar conexión
$conn->Close();
