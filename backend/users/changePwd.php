<?php
include_once __DIR__ . '/../../backend/config.php';
include_once '../session.php';  // Incluye la sesión
include '../conexiones/db_connection.php';

// Establecer el encabezado para JSON
header('Content-Type: application/json');

// Obtener los datos del formulario
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$userId = $_POST['userid']; // Cambia esto por el ID del usuario actual

// Comprobar si la conexión es exitosa
if ($conn) {
    // Obtener la contraseña actual del usuario
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Verificar la contraseña actual
        $hashedPassword = $user['password'];
        if (password_verify($currentPassword, $hashedPassword)) {
            // Validar y hashear la nueva contraseña
            if (!empty($newPassword) && strlen($newPassword) >= 6) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Actualizar la nueva contraseña
                $updateSql = "UPDATE users SET password = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $newHashedPassword, $userId);
                $updateStmt->execute();

                echo json_encode(['message' => 'La contraseña se ha cambiado correctamente.']);
            } else {
                echo json_encode(['message' => 'La nueva contraseña debe tener al menos 6 caracteres.']);
            }
        } else {
            echo json_encode(['message' => 'La contraseña actual es incorrecta.']);
        }
    } else {
        echo json_encode(['message' => 'No se encontró el usuario.']);
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['message' => 'Error en la conexión a la base de datos.']);
}
