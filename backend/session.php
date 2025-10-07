<?php
session_start();

// Tiempo máximo de inactividad (20 minutos)
//$tiempo_maximo = 1200;


// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Verificar si BASE_PATH está definido
    if (defined('BASE_PATH')) {
        // Redirigir a login.php dentro de FRONTEND
        header('Location: ' . BASE_PATH . 'frontend/login.php');
        exit(); // Detener la ejecución del script
    } else {
        // Manejar el caso en que BASE_PATH no está definido
        echo "Error: BASE_PATH no está definido.";
        header('Location: /index.php');
        exit();
    }
}

/* // Verificar tiempo de actividad
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $tiempo_maximo) {
        session_unset();     // Eliminar variables de sesión
        session_destroy();   // Destruir la sesión
        header("Location: " . (defined('BASE_PATH') ? BASE_PATH . 'frontend/login.php' : '/index.php'));
        exit();
    }
}
$_SESSION['LAST_ACTIVITY'] = time(); // Actualiza tiempo de actividad */

// Obtener todas las variables de sesión
$user_id = $_SESSION['user_id'];
$nickname = $_SESSION['nickname'] ?? 'Desconocido';
$persona_id = $_SESSION['persona_id'] ?? 'No asignado';
$identificacion = $_SESSION['identificacion'] ?? 'No asignado';
$nombre = $_SESSION['nombre'] ?? 'No asignado';
$email_persona = $_SESSION['email_persona'] ?? 'No asignado';
$inrdireccion_id = $_SESSION['inrdireccion_id'] ?? 'No asignado';
$direccion = $_SESSION['direccion'] ?? 'No asignado';
$rol_id = $_SESSION['rol_id'] ?? 'No asignado';
$rol_nombre = $_SESSION['rol_nombre'] ?? 'No asignado';
$ambiente = $_SESSION['ambiente'] ?? 'No asignado';

// Obtener la página actual
$current_page = basename($_SERVER['PHP_SELF']); // Nombre del archivo actual

// Base URL para los enlaces
$base_url = '/INRtools'; // Cambia esto según la raíz de tu proyecto

// Mostrar todas las variables de sesión (para pruebas/debug)
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";