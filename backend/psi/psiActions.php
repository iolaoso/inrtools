<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/psidb_connection.php';

// Asegúrate de que el método de la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica si los datos fueron recibidos correctamente
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['message' => 'Datos inválidos']);
        exit;
    }

    // Lógica para manejar los datos recibidos
    try {
        // Conexión a la base de datos
        $connPsi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($connPsi->connect_error) {
            throw new Exception("Conexión fallida: " . $connPsi->connect_error);
        }

        // Prepara la consulta de inserción
        $stmt = $connPsi->prepare("INSERT INTO psi (NUMERO, COD_UNICO, RUC, RAZON_SOCIAL) VALUES (?, ?, ?, ?)");

        // Vincula los parámetros
        $stmt->bind_param("isss", $data['NUMERO'], $data['COD_UNICO'], $data['RUC'], $data['RAZON_SOCIAL']);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Datos insertados correctamente']);
        } else {
            throw new Exception("Error al insertar datos: " . $stmt->error);
        }

        // Cierra la conexión
        $stmt->close();
        $connPsi->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => $e->getMessage()]);
    }
} else {
    // Respuesta para métodos no permitidos
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
}