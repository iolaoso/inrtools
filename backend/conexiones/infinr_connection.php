<?php
// db_connection.php

// Configuración de la base de datos
$serverInf = "ilitia.seps.local"; // Cambia si es necesario
$userInf = "userInf"; // Tu usuario de base de datos
$pdwInf = "u1nf@2021"; // Tu contraseña de base de datos
$dbnameInf = "informesinrdb_test"; // Nombre de la base de datos
$port = 3306; // Cambia por el puerto que deseas verificar (80 para HTTP, 443 para HTTPS)

function checkServerStatusInf($host, $port)
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
$primaryHost = $serverInf; // Servidor principal
$backupHost = 'localhost'; // Servidor alternativo

// Verificar el servidor principal
if (checkServerStatusInf($primaryHost, $port)) {
    $servername = $primaryHost; // Cambia si es necesario
    //echo "El servidor principal ($primaryHost) está activo.";
} elseif (checkServerStatusInf($backupHost, $port)) {
    $serverInf = $backupHost;
    //echo "El servidor principal no está disponible. Conectando a localhost...";
} else {
    echo "Ambos servidores no están disponibles.";
}


// Crear conexión
$connInf = new mysqli($serverInf, $userInf, $pdwInf, $dbnameInf, $port);

// Verificar conexión
if ($connInf->connect_error) {
    die("Conexión fallida: " . $connInf->connect_error);
}

$connInf->set_charset("utf8mb4");
