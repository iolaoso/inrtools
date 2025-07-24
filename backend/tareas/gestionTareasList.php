<?php
include_once __DIR__ . '/../config.php'; // Asegúrate de que la ruta es correcta
include_once BASE_PATH . 'backend/conexiones/tareas_connection.php'; // Incluir la conexión a la base de datos

// Función para agregar una nueva tarea
function agregarTarea($data)
{
    global $connTask;
    $stmt = $connTask->prepare("INSERT INTO tareas (TAREA, TIPO_PROCESO, FRECUENCIA, RUC, DESCRIPCION, PROXIMA_FECHA, PROXIMA_HORA, ULTIMA_EJECUCION, ANALISTA_ASIGNADO, ESTADO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $data['taskName'], $data['processType'], $data['frequency'], $data['ruc'], $data['description'], $data['nextExecutionDate'], $data['nextExecutionTime'], $data['lastExecution'], $data['assignAnalyst'], $data['taskStatus']);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

// Función para obtener todas las tareas
function obtenerTareas()
{
    global $connTask;
    $result = $connTask->query("SELECT * FROM VI_TAREAS_ACCIONES");
    $tareas = [];

    while ($row = $result->fetch_assoc()) {
        $tareas[] = $row;
    }

    return $tareas;
}

// Función para editar una tarea
function editarTarea($id, $data)
{
    global $connTask;
    $stmt = $connTask->prepare("UPDATE tareas SET TAREA=?, TIPO_PROCESO=?, FRECUENCIA=?, RUC=?, DESCRIPCION=?, PROXIMA_FECHA=?, PROXIMA_HORA=?, ULTIMA_EJECUCION=?, ANALISTA_ASIGNADO=?, ESTADO=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $data['taskName'], $data['processType'], $data['frequency'], $data['ruc'], $data['description'], $data['nextExecutionDate'], $data['nextExecutionTime'], $data['lastExecution'], $data['assignAnalyst'], $data['taskStatus'], $id);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

// Función para eliminar una tarea
function eliminarTarea($id)
{
    global $connTask;
    $stmt = $connTask->prepare("DELETE FROM tareas WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

// Manejo de solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'agregar':
                echo json_encode(agregarTarea($_POST));
                break;
            case 'editar':
                echo json_encode(editarTarea($_POST['id'], $_POST));
                break;
            case 'eliminar':
                echo json_encode(eliminarTarea($_POST['id']));
                break;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(obtenerTareas());
}
