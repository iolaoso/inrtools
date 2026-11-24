<?php 
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

function obtenerPersonasFull()
{
     global $conn; // Usar la conexión global

    $sql = "SELECT
                p.id,
                p.identificacion,
                p.nombre,
                p.email,
                d.direccion
            FROM personas p
            LEFT JOIN inrdireccion d
                ON p.inrdireccion_id = d.id
            where p.estRegistro = 1
            ORDER BY p.nombre";

    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Conexión fallida: ' . $conn->connect_error]));
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

