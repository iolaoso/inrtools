<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/conexiones/tareas_connection.php'; // Incluir la conexión a la base de datos
include_once BASE_PATH . 'backend/session.php';

// Asegúrate de que $nickname existe y tiene valor
if (!isset($nickname)) {
    http_response_code(400);
    die(json_encode(['error' => 'Nickname no definido']));
}

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

// Funcion para marcar una tarea como hecha
function marcarTareaComoHecha($id)
{
    global $connTask;
    $stmt = $connTask->prepare("UPDATE tareas SET ESTADO_TAREA='COMPLETADA' WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return [
            'success' => true,
            'message' => 'Tarea marcada como completada'
        ];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

// Funcion para editar una tarea
function editarTarea($id)
{
    global $connTask;
    // Aquí podrías implementar la lógica para editar una tarea
    // Por ahora, simplemente retornamos un mensaje de éxito
    $stmt = $connTask->prepare("UPDATE tareas SET ESTADO_TAREA='EDITADA' WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

// Funcion para eliminar una tarea
function elimimarTarea($id)
{
    global $connTask;
    $stmt = $connTask->prepare("UPDATE tareas SET EST_REGISTRO='ELIMINADA' WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

function asignarActions($tareaId, $accion)
{
    // Asegúrate de que $tareaId y $accion son válidos
    if (empty($tareaId) || empty($accion)) {
        return ['success' => false, 'message' => 'ID de tarea o acción no válidos'];
    }
    //define las acciones por tarea con un switch
    switch ($accion) {
        case 'marcar_como_hecha':
            return marcarTareaComoHecha($tareaId);
        case 'editar':
            // Aquí podrías implementar la lógica para editar una tarea
            return editarTarea($tareaId);
        case 'eliminar':
            return elimimarTarea($tareaId);
        default:
            return ['success' => false, 'message' => 'Acción no reconocida'];
    }
}

// Manejo de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && isset($_GET['accion'])) {
        $tareaId = $_GET['id'];
        $accion = $_GET['accion'];
        $response = asignarActions($tareaId, $accion);
        echo json_encode($response);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
}
