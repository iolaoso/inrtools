<?php
include_once __DIR__ . '/../config.php';
include BASE_PATH . 'backend/session.php';
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "Datos recibidos: " . print_r($_POST, true); // Depuración: mostrar los datos recibidos
    $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : null;
    $direccion = mysqli_real_escape_string($conn, strtoupper($_POST['direccion']));
    $dirNombre = mysqli_real_escape_string($conn, strtoupper($_POST['dirNombre']));
    $estRegistro = ($_POST['estRegistro']=='Activo') ? 1 : 0; // Convertir a 1 o 0 según el valor recibido
    $usuario = $_SESSION['username'] ?? 'ILOPEZA';
    
    if ($id) {
        // Actualizar dirección existente
        $sql = "UPDATE inrdireccion SET 
                direccion = '$direccion',
                dirNombre = '$dirNombre',
                updatedAt = NOW()
                WHERE id = $id";
        
        // Nota: deletedAt y updatedAt se manejarían con triggers o manualmente
        // Si necesitas updatedAt, deberías agregar ese campo a la tabla
    } else {
        // Insertar nueva dirección
        $sql = "INSERT INTO inrdireccion (direccion, dirNombre, estRegistro, UsrCreacion, createdAt) 
                VALUES ('$direccion', '$dirNombre', $estRegistro, '$usuario', NOW())";
    }
    
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $base_url . '/frontend/configuracion/direcciones/newdireccion.php?success=1');
    } else {
        header('Location: ' . $base_url . '/frontend/configuracion/direcciones/newdireccion.php?error=1');
    }
    exit();
}