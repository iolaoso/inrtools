<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

// Incluir el archivo de ADODB
require_once BASE_PATH . '/assets/adodb5/adodb.inc.php'; // Ajusta la ruta según tu estructura

function conectarADODB($server, $catalogo)
{
    // Configuración de conexión
    $connectionString = "Provider=MSOLAP;Data Source=$server;Initial Catalog=$catalogo;";
    $connOLAP = ADONewConnection('ado');

    // Intentar conectar
    if ($connOLAP->Connect($connectionString)) {
        return $connOLAP; // Devuelve el objeto de conexión
    } else {
        throw new Exception('No se pudo conectar: ' . $connOLAP->ErrorMsg());
    }
}