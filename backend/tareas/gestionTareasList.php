<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/conexiones/tareas_connection.php'; // Incluir la conexión a la base de datos
include_once BASE_PATH . 'backend/session.php';

// funcion para obtener la lista de tareas
function getTareas($filtro_analista = null)
{
    global $connTask;
    $sql = "SELECT * 
            FROM VI_TAREAS_ACCIONES 
            WHERE EST_REGISTRO='ACT' 
            AND ANALISTA_ASIGNADO = ? 
            ORDER BY ESTADO_TAREA DESC";
    if ($connTask->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Conexión fallida: ' . $connTask->connect_error]));
    }
    $stmt = $connTask->prepare($sql);
    $stmt->bind_param("s", $filtro_analista);
    $stmt->execute();
    return $stmt->get_result();
}

// Asegúrate de que $nickname existe y tiene valor
if (!isset($nickname)) {
    http_response_code(400);
    die(json_encode(['error' => 'Nickname no definido']));
}
