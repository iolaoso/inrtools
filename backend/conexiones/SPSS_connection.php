<?php

function getConnectionSPSS()
{
    // Configuración de la conexión
    $dsn = 'SPSS_SEPS'; // Nombre del DSN configurado
    $username = 'ilopez'; // Tu User ID
    $password = 'iolaoso@0303!'; // Introduce tu contraseña

    try {
        $connSPSS = new PDO("odbc:$dsn", $username, $password);
        $connSPSS->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connSPSS; // Devuelve la conexión
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        return null; // Devuelve null en caso de error
    }
}
