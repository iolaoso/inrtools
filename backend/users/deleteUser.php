<?php
// deleteUser.php - Versión GET

// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';
include_once '../session.php'; // Asegúrate de que la sesión esté iniciada

// Configura el encabezado para la respuesta JSON
header('Content-Type: application/json');

// Verificar que es una solicitud GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die('Acceso no permitido');
}

// Obtener el ID del usuario a eliminar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /INRtools/frontend/configuracion/users/addDelUser.php');
    exit;
}

$id = intval($_GET['id']);

if ($id <= 0) {
    header('Location: /INRtools/frontend/configuracion/users/addDelUser.php');
    exit;
}

// Realizar la eliminación lógica (UPDATE)
$sql = "UPDATE users 
        SET estRegistro = 0, 
            deletedAt = NOW(), 
            updatedAt = NOW() 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Éxito - redireccionar con mensaje de éxito
    $_SESSION['mensaje'] = 'Usuario eliminado correctamente';
    $_SESSION['tipo_mensaje'] = 'success';
} else {
    // Error - redireccionar con mensaje de error
    $_SESSION['mensaje'] = 'Error al eliminar usuario: ' . $stmt->error;
    $_SESSION['tipo_mensaje'] = 'error';
}

$stmt->close();
$conn->close();

// Redireccionar a la página principal de usuarios
header('Location: /INRtools/frontend/configuracion/users/addDelUser.php');
exit;