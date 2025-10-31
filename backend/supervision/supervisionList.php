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

