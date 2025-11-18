<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

// Función para obtener las estrategias según la dirección y el rol del usuario
function obtenerEstrategias($dirInrId, $rolId) {
    global $conn; // Usar la conexión global
    // Consulta para obtener las categorías
    if ($rolId == 1) {
        $query = "SELECT ID
                        ,ESTRATEGIA
                  FROM as_catalogo_supervision
                  WHERE EST_REGISTRO = 'ACT'
                  GROUP BY ESTRATEGIA
                  ORDER BY ID;"; // Cambia el nombre de la tabla según tu base de datos
        $stmt = $conn->prepare($query);
        $stmt->execute();
    } else {
        $query = "SELECT ID
                    ,ESTRATEGIA
                  FROM as_catalogo_supervision
                  WHERE EST_REGISTRO = 'ACT'
                  AND DIRECCION=?
                  GROUP BY ESTRATEGIA
                  ORDER BY ID;"; // Cambia el nombre de la tabla según tu base de datos
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $dirInrId);
        $stmt->execute();
    }
    return $stmt->get_result();
}

// Función para buscar supervisiones por RUC en la base de datos
function buscarSupervisionesPorRuc($ruc) {
    global $conn;
    
    if (!$conn || $conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    // Consulta para obtener supervisiones por RUC
    $query = "SELECT 
                av.ID,
                av.RUC,
                av.CATALOGO_ID,
                av.FEC_ASIG,
                cat.ESTRATEGIA,
                cat.FASE,
                cat.ESTADO_PROCESO as ESTADO_PROCESO,	
                cat.PORC_AVANCE
              FROM as_avances_supervision av
              LEFT JOIN as_catalogo_supervision cat ON av.CATALOGO_ID = cat.ID
              WHERE av.RUC = ? 
              AND av.EST_REGISTRO = 'ACT'
              ORDER BY av.FECHA_CREACION DESC, av.ID DESC";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Error preparando la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $ruc);
    
    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando la consulta: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $supervisiones = [];
    
    while ($row = $result->fetch_assoc()) {
        $supervisiones[] = [
            'id' => $row['ID'],
            'ruc' => $row['RUC'],
            'catalogo_id' => $row['CATALOGO_ID'],
            'estrategia' => $row['ESTRATEGIA'],
            'fase' => $row['FASE'],
            'estado' => $row['ESTADO_PROCESO'],
            'fecha_asignacion' => $row['FEC_ASIG'],
            'porcentaje_avance' => $row['PORC_AVANCE']
        ];
    }
    
    $stmt->close();
    return $supervisiones;
}
    
    
//FUNCIÓN PARA OBTENER FASES
function obtenerFases($estrategiaText) {
    global $conn; // Usar la conexión global
    // Consulta para obtener las categorías
    $query = "SELECT ID
                    ,FASE
              FROM as_catalogo_supervision
              WHERE EST_REGISTRO = 'ACT'
              AND ESTRATEGIA = ? 
              ORDER BY ID;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $estrategiaText);
    $stmt->execute();

    return $stmt->get_result();
}

//FUNCIÓN PARA OBTENER ESTADOS DE SUPERVISIÓN
function obtenerEstadosSupervision($faseId) {
    global $conn; // Usar la conexión global
    // Consulta para obtener el esado de supervisión y el porcentaje de avance
    $query = "SELECT DISTINCT ESTADO_PROCESO
                ,PORC_AVANCE * 100 AS PORC_AVANCE
             FROM as_catalogo_supervision
             WHERE EST_REGISTRO = 'ACT'
             AND ID = ? 
             ORDER BY ESTADO_PROCESO;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $faseId);
    $stmt->execute();

    return $stmt->get_result();
}


if (isset($_GET['action']) && $_GET['action'] === 'getEstrategias') {

    $dirInrId = isset($_GET['dirInrId']) ? intval($_GET['dirInrId']) : 0;
    $rolId = isset($_GET['rolId']) ? intval($_GET['rolId']) : 0;

    $estrategiasResult = obtenerEstrategias($dirInrId, $rolId);
    $estrategias = array();
    while ($row = $estrategiasResult->fetch_assoc()) {
        $estrategias[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($estrategias);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getFases') {
    
    $estrategiaText = isset($_GET['estrategiaText']) ? $_GET['estrategiaText'] : '';

    $fasesResult = obtenerFases($estrategiaText);
    $fases = array();
    while ($row = $fasesResult->fetch_assoc()) {
        $fases[] = $row;
    }

    header('Content-Type: application/json');
    // Cambiar esta línea - retornar objeto con propiedad fases
    echo json_encode(['fases' => $fases]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getEstados') {
    
    $faseId = isset($_GET['faseId']) ? $_GET['faseId'] : 0;

    $estadosResult = obtenerEstadosSupervision($faseId);
    $estados = array();
    while ($row = $estadosResult->fetch_assoc()) {
        $estados[] = $row;
    }

    header('Content-Type: application/json');
    // retorna objeto con propiedad estados
    echo json_encode(['success' => true,
                      'estados' => $estados]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getSupervisionData') {
    
    $supervisionId = isset($_GET['supervisionId']) ? $_GET['supervisionId'] : 0;

    global $conn; // Usar la conexión global
    // Consulta para obtener los datos de la supervisión
    $query = "SELECT *
              FROM VI_FULL_SUPERVISION
              WHERE ID = ?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $supervisionId);
    $stmt->execute();
    $result = $stmt->get_result();

    $supervisionData = array();
    while ($row = $result->fetch_assoc()) {
        $supervisionData[] = $row;
    }

    header('Content-Type: application/json');
    // retorna objeto con propiedad supervision
    echo json_encode(['success' => true,
                      'supervision' => $supervisionData]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'buscarPorRuc') {
    
    $ruc = isset($_GET['ruc']) ? trim($_GET['ruc']) : '';
    
    if (empty($ruc)) {
        echo json_encode([
            'success' => false,
            'error' => 'RUC es requerido',
            'supervisiones' => []
        ]);
        exit;
    }
    
    try {
        $supervisiones = buscarSupervisionesPorRuc($ruc);
        
        echo json_encode([
            'success' => true,
            'supervisiones' => $supervisiones,
            'total' => count($supervisiones)
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'supervisiones' => []
        ]);
    }
    exit;
}


