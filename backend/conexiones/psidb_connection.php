<?php
// psidb_connection.php

// Configuración de la base de datos
$servername = "ilitia.seps.local"; // Cambia si es necesario
$username = "psiUsr"; // Tu usuario de base de datos
$password = "userpsi2024@il"; // Tu contraseña de base de datos
$dbname = "psidb_test"; // Nombre de la base de datos
$port = 3306; // Cambia por el puerto que deseas verificar (80 para HTTP, 443 para HTTPS)

function checkServerStatus($host, $port)
{
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);

    if ($connection) {
        fclose($connection);
        return true; // Servidor activo
    } else {
        return false; // Servidor inactivo
    }
}

// Parámetros de entrada
$primaryHost = 'ilitia.seps.local'; // Servidor principal
$backupHost = 'localhost'; // Servidor alternativo
$port = 80; // Puerto a verificar (80 para HTTP, 443 para HTTPS)

// Verificar el servidor principal
if (checkServerStatus($primaryHost, $port)) {
    $servername = $primaryHost; // Cambia si es necesario
    //echo "El servidor principal ($primaryHost) está activo.";
} elseif (checkServerStatus($backupHost, $port)) {
    $servername = $backupHost;
    echo "El servidor principal no está disponible. Conectando a localhost...";
} else {
    echo "Ambos servidores no están disponibles.";
}

// Crear conexión
$connPsi = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($connPsi->connect_error) {
    die("Conexión fallida: " . $connPsi->connect_error);
}

$connPsi->set_charset("utf8mb4");
