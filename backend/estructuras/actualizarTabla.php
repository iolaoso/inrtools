<?php
include_once '../session.php';
include_once '../config.php';
include_once '../conexiones/db_connection.php'; // Incluye el archivo donde está definida la función
include_once 'estructurasList.php'; // Incluye el archivo donde está definida la función

header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON

// Consulta para obtener datos
if ($rol_nombre == "ADMININSTRADOR") {
    $result = obtenerEstructurasFull();
} else {
    $result = obtenerEstructurasPorUsuario($nickname);
}

$data = [];

if ($result->num_rows > 0) {
    // Salida de cada fila
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'solicitante' => htmlspecialchars($row['solicitante']),
            'direccion_solicitante' => htmlspecialchars($row['direccion_solicitante']),
            'ruc' => htmlspecialchars($row['ruc']),
            'estructura' => htmlspecialchars($row['estructura']),
            'fechaCorte' => htmlspecialchars($row['fechaCorte']),
            'fecha_solicitud' => htmlspecialchars($row['fecha_solicitud']),
            'estado' => htmlspecialchars($row['estado']),
            'analista_ejecutante' => htmlspecialchars($row['analista_ejecutante']),
            'id' => htmlspecialchars($row['id']),
        ];
    }
} else {
    $data = []; // No hay datos
}

$conn->close(); // Cerrar conexión

// Devolver los datos en formato JSON
echo json_encode($data);
