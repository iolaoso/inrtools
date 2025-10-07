<?php
// Iniciar sesión
session_start();

// Redirigir al usuario a la página de inicio de sesión
header("Location: frontend/login.php");
exit();
?>

index