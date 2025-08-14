<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/conexiones/tareas_connection.php';
include_once BASE_PATH . 'backend/session.php';

if (!isset($nickname)) {
    http_response_code(400);
    die(json_encode(['error' => 'Nickname no definido']));
}

function getTareas($estado, $analista)
{
    global $connTask;
    $sql = "SELECT * FROM tareas  
            WHERE EST_REGISTRO='ACT' 
            AND ESTADO_TAREA = ? 
            AND ANALISTA_ASIGNADO = ? 
            ORDER BY PROXIMA_FECHA ASC";

    $stmt = $connTask->prepare($sql);
    $stmt->bind_param("ss", $estado, $analista);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTareasPendientes($analista)
{
    return getTareas('PENDIENTE', $analista);
}

function getTareasCompletas($analista)
{
    return getTareas('COMPLETADA', $analista);
}

function manejarTarea($id, $accion)
{
    global $connTask;

    $accionesValidas = [
        'completar' => "UPDATE tareas SET ESTADO_TAREA='COMPLETADA', ULTIMA_EJECUCION=NOW() WHERE id=?",
        'editar' => "UPDATE tareas SET ESTADO_TAREA='EDITADA' WHERE id=?",
        'eliminar' => "UPDATE tareas SET EST_REGISTRO='ELIMINADA' WHERE id=?"
    ];

    if (!array_key_exists($accion, $accionesValidas)) {
        return ['success' => false, 'message' => 'Acción no válida'];
    }

    $stmt = $connTask->prepare($accionesValidas[$accion]);
    $stmt->bind_param("i", $id);

    return $stmt->execute()
        ? ['success' => true]
        : ['success' => false, 'message' => $stmt->error];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['action'])) {
        $response = manejarTarea($data['id'], $data['action']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}