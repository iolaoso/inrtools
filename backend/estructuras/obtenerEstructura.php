<?php
include_once '../session.php';
include_once '../config.php';
include_once 'estructurasList.php'; // Incluye el archivo donde está definida la función
header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON

// Verificar si se ha proporcionado un ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $estructuraData = obtenerEstructuraPorId($id);

    // Comprobar si se encontró la estructura
    if ($estructuraData) {
        header('Content-Type: application/json');
        echo json_encode($estructuraData);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Estructura no encontrada']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
}
