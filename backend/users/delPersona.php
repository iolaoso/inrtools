<?php

include '../conexiones/db_connection.php';
include_once '../session.php';

header('Content-Type: application/json');

$delPersonaId = $_GET['delPersonaId'] ?? '';

if (empty($delPersonaId)) {

    echo json_encode([
        'success' => false,
        'error' => 'No se recibió el identificador.'
    ]);

    exit();
}

$sql = "UPDATE personas
        SET estRegistro = 0,
            deletedAt   = NOW(),
            updatedAt   = NOW()
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode([
        'success' => false,
        'error' => $conn->error
    ]);

    exit();
}

$stmt->bind_param("i", $delPersonaId);

if ($stmt->execute()) {

    echo json_encode([
        'success' => true,
        'message' => 'Persona desactivada correctamente.'
    ]);

} else {

    echo json_encode([
        'success' => false,
        'error' => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
