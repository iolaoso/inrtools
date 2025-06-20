<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
require_once BASE_PATH . '/assets/adodb5/adodb.inc.php'; // Ajusta la ruta según tu estructura
require_once BASE_PATH . 'backend/conexiones/conexionOLAP.php'; // archivo que contiene la conexion OLAP

function valEstructurasRuc($ruc)
{
    $server = 'frigga';
    $catalogo = 'SEPS_NET_VALIDACION_ESTRUCTURAS'; // Eliminar el punto y coma
    try {
        $connOLAP = conectarADODB($server, $catalogo);

        // Consulta MDX
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
        CELL PROPERTIES VALUE";

        // Ejecutar la consulta
        $result = $connOLAP->Execute($query);
        $data = [];

        // Procesar resultados
        while (!$result->EOF) {
            $data[] = [
                'conteo_registros' => $result->fields[0]->Value, // Acceder al valor
                'ruc' => $result->fields[1]->Value // Acceder al valor
            ];
            $result->MoveNext();
        }

        // Cerrar conexión
        $connOLAP->Close();

        // Devolver resultados
        return json_encode(['status' => 'success', 'data' => $data]);
    } catch (Exception $e) {
        // Cerrar conexión en caso de error
        if (isset($connOLAP)) {
            $connOLAP->Close();
        }
        return json_encode(['status' => 'Error al ejecutar el Query', 'message' => $e->getMessage()]);
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
            'message' => 'RUC no puede estar vacío'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Datos obtenidos correctamente',
        'datos' => ['ruc' => $data['ruc']]
    ]);
}