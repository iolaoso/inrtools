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

function getTareaById($id)
{
    global $connTask;
    $sql = "SELECT * FROM tareas WHERE id = ?";
    $stmt = $connTask->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function manejarTarea($id, $accion, $analista)
{
    global $connTask;

    $accionesValidas = [
        'completar' => "UPDATE tareas SET ESTADO_TAREA='COMPLETADA', ULTIMA_EJECUCION=NOW() WHERE id=?",
        'eliminar' => "UPDATE tareas SET ESTADO_TAREA='ELIMINADA', EST_REGISTRO='INA', DELETED_AT = NOW(), DELETED_USR = ? WHERE id=?"
    ];

    if (!array_key_exists($accion, $accionesValidas)) {
        return ['success' => false, 'message' => 'Acción no válida'];
    }

    $stmt = $connTask->prepare($accionesValidas[$accion]);
    if ($accion === 'eliminar') {
        $stmt->bind_param("si", $analista, $id);
    } else {
        $stmt->bind_param("i", $id);
    }

    return $stmt->execute()
        ? ['success' => true, 'message' => 'Acción realizada con éxito']
        : ['success' => false, 'message' => $stmt->error];
}

function tareaSaveEdit($datos, $analista)
{
    global $connTask;

    if (empty($datos['taskName']) || empty($datos['processType']) || empty($datos['frequency'])) {
        return ['success' => false, 'message' => 'Campos requeridos faltantes'];
    }

    // Preparar datos para la base de datos
    $tareaData = [
        'TAREA' => $datos['taskName'],
        'TIPO_PROCESO' => $datos['processType'],
        'FRECUENCIA' => $datos['frequency'],
        'RUC' => $datos['ruc'] ?? null,
        'DESCRIPCION' => $datos['description'],
        'PROXIMA_FECHA' => $datos['nextExecutionDate'],
        'PROXIMA_HORA' => $datos['nextExecutionTime'],
        'ULTIMA_EJECUCION' => $datos['lastExecution'] ?? null,
        'ANALISTA_ASIGNADO' => $datos['assignAnalyst'],
        'ESTADO_TAREA' => $datos['taskStatus'],
        'IDEN_ANALISTA' => $analista,
        'USR_CREACION' => $analista,
        'UPDATED_AT' => date('Y-m-d H:i:s')
    ];

    if (empty($datos['taskId'])) {
        // INSERTAR nueva tarea
        $sql = "INSERT INTO tareas (" . implode(',', array_keys($tareaData)) . ", CREATED_AT, EST_REGISTRO) 
                VALUES (?" . str_repeat(',?', count($tareaData)) . ", ?, 'ACT')";

        $stmt = $connTask->prepare($sql);
        $types = str_repeat('s', count($tareaData)) . 's';
        $params = array_values($tareaData);
        $params[] = date('Y-m-d H:i:s');
        $stmt->bind_param($types, ...$params);
    } else {
        // ACTUALIZAR tarea existente
        $sql = "UPDATE tareas SET ";
        $updates = [];
        foreach ($tareaData as $field => $value) {
            $updates[] = "$field = ?";
        }
        $sql .= implode(', ', $updates) . " WHERE id = ?";

        $stmt = $connTask->prepare($sql);
        $types = str_repeat('s', count($tareaData)) . 'i';
        $params = array_values($tareaData);
        $params[] = $datos['taskId'];
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Tarea guardada correctamente'];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Para obtener datos de una tarea específica al editar
    if (isset($_GET['action']) && $_GET['action'] === 'getTask' && isset($_GET['id'])) {
        $task = getTareaById($_GET['id']);
        header('Content-Type: application/json');
        echo json_encode($task);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true) ?? $_POST;

    if (isset($data['action'])) {
        if ($data['action'] == 'insertar' || $data['action'] == 'editar') {
            $response = tareaSaveEdit($data, $nickname);
        } else {
            $response = manejarTarea($data['id'], $data['action'], $nickname);
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}