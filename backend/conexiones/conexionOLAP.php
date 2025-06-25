<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

// Verificar si la constante BASE_PATH está definida
if (!defined('BASE_PATH')) {
    die('BASE_PATH no está definida');
}

// Incluir ADODB con verificación de archivo
$adodbPath = BASE_PATH . '/assets/adodb5/adodb.inc.php';
if (!file_exists($adodbPath)) {
    die('Archivo ADODB no encontrado: ' . $adodbPath);
}
require_once $adodbPath;

function conectarADODB($server, $catalogo)
{
    global $ADODB_DEBUG; // Variable de depuración de ADODB

    // Habilitar depuración en entorno de desarrollo
    $env = defined('ENVIRONMENT') ? constant('ENVIRONMENT') : 'production';
    $ADODB_DEBUG = ($env === 'development');

    $connOLAP = ADONewConnection('ado');
    if (!$connOLAP) {
        error_log('Error al crear objeto de conexión ADODB');
        return false;
    }

    // Proveedores en orden de prioridad
    $providers = [
        "MSOLAP.8" => "SQL Server 2019+",
        "MSOLAP.7" => "SQL Server 2017",
        "MSOLAP.6" => "SQL Server 2016",
        "MSOLAP.5" => "SQL Server 2014",
        "MSOLAP"   => "Versión genérica"
    ];

    $lastError = '';
    foreach ($providers as $provider => $version) {
        try {
            // Agregar tiempo de espera al string de conexión
            $connStr = "Provider=$provider;Data Source=$server;Initial Catalog=$catalogo;Connect Timeout=10;";

            if ($connOLAP->Connect($connStr)) {
                // Configuración adicional para conexión estable
                $connOLAP->SetFetchMode(ADODB_FETCH_ASSOC);
                $connOLAP->Execute('SET QUOTED_IDENTIFIER ON');
                return $connOLAP;
            }
        } catch (Exception $e) {
            $lastError = "Intento con $version ($provider) falló: " . $e->getMessage();
            error_log($lastError);

            // Cerrar conexión fallida
            if ($connOLAP->IsConnected()) {
                $connOLAP->Close();
            }

            continue;
        }
    }

    // Si llegamos aquí, todos los intentos fallaron
    error_log('Todos los proveedores OLAP fallaron. Último error: ' . $lastError);
    return false;
}
