<?php
include_once __DIR__ . '/../config.php';
include BASE_PATH . 'backend/session.php';
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $usuario = $_SESSION['username'] ?? 'SYSTEM';
    
    // Soft delete: actualizar deletedAt en lugar de eliminar físicamente
    $sql = "UPDATE inrdireccion SET 
            deletedAt = NOW(),
            estRegistro = 0
            WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $base_url . '/frontend/configuracion/direcciones/newdireccion.php?success=1');
    } else {
        header('Location: ' . $base_url . '/frontend/configuracion/direcciones/newdireccion.php?error=1');
    }
} else {
    header('Location: ' . $base_url . '/frontend/configuracion/direcciones/newdireccion.php?error=1');
}
exit();