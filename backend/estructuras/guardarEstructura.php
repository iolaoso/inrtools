<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';

// Verificar si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $id = isset($_POST['idEstructura']) ? $_POST['idEstructura'] : null;
    $solicitante = mb_strtoupper($_POST['analistaSolicitante'], 'UTF-8');
    $direccion_solicitante = mb_strtoupper($_POST['direccionSolicitante'], 'UTF-8');
    $ruc = $_POST['ruc'];
    $estructura = mb_strtoupper($_POST['nombreReporte'], 'UTF-8');
    $fechaCorte = $_POST['fechaCorte'];
    $fecha_solicitud = $_POST['fechaSolicitud'];
    $estado = $_POST['estadoSolicitud'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $detalle = mb_strtoupper($_POST['detalleObs'], 'UTF-8');
    $analista_ejecutante = mb_strtoupper($_POST['analistaEjecutante'], 'UTF-8');
    $UsrCreacion = $_POST['analistaEjecutante'];

    // Obtener la fecha y hora actual
    // configurar php.ini date.timezone = America/Guayaquil
    $fecha_actual = date('Y-m-d H:i:s');

    // Verificar si es un insert o un update
    if ($id) {
        // Es un update
        $sql = "UPDATE estructuras SET 
                    solicitante = ?, 
                    direccion_solicitante = ?, 
                    ruc = ?, 
                    estructura = ?, 
                    fechaCorte = ?,
                    fecha_solicitud = ?, 
                    estado = ?, 
                    fechaInicio = ?,
                    fechaFin = ?,
                    detalle = ?,
                    analista_ejecutante = ?, 
                    updatedAt = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssi", $solicitante, $direccion_solicitante, $ruc, $estructura, $fechaCorte, $fecha_solicitud, $estado, $fechaInicio, $fechaFin, $detalle, $analista_ejecutante, $fecha_actual, $id);
    } else {
        // Es un insert
        $sql = "INSERT INTO estructuras (solicitante, direccion_solicitante, ruc, estructura, fechaCorte, fecha_solicitud, estado, fechaInicio, fechaFin, detalle, analista_ejecutante, UsrCreacion, createdAt, updatedAt) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssss", $solicitante, $direccion_solicitante, $ruc, $estructura, $fechaCorte, $fecha_solicitud, $estado, $fechaInicio, $fechaFin, $detalle, $analista_ejecutante, $UsrCreacion, $fecha_actual, $fecha_actual);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro guardado exitosamente.";
    } else {
        echo "Error al guardar el registro: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
