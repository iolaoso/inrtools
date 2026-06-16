<?php
// config.php

// configuración de zona horaria
date_default_timezone_set('America/Bogota');

// En config.php
define('BASE_PATH', dirname(__DIR__) . '/'); // Esto apunta a C:/laragon/www/INRtools/

// En tu archivo config.php
define('ENVIRONMENT', 'desarrollo'); // o 'development' según tu entorno

if (ENVIRONMENT === 'Produccion') {

    define(
        'BASE_URL',
        'http://ilitia.seps.local/INRtools'
    );

} else {

    define(
        'BASE_URL',
        'http://localhost/INRtools'
    );

}


require_once BASE_PATH . '/backend/helpers/funciones.php';

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);