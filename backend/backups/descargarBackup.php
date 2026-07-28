<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

/* CONFIGURACIÓN */
define('RUTA_BACKUPS',BACKUPS_PATH);

/* VALIDAR PARÁMETROS */
if (empty($_GET['carpeta']) || empty($_GET['archivo'])) {
    http_response_code(400);
    exit('Parámetros inválidos.');
}

/* LIMPIAR PARÁMETROS */
$carpeta = basename(trim($_GET['carpeta']));
$archivo = basename(trim($_GET['archivo']));

//echo "Carpeta limpia: " . $carpeta . PHP_EOL;
//echo "Archivo limpio : " . $archivo . PHP_EOL;

/* CONSTRUIR RUTA */
$rutaArchivo = RUTA_BACKUPS.DIRECTORY_SEPARATOR.$carpeta.DIRECTORY_SEPARATOR.$archivo;

/* echo "Ruta:" . PHP_EOL;
echo $rutaArchivo . PHP_EOL; */

/* echo "Existe: ";
var_dump(file_exists($rutaArchivo)); 
exit; */

/* VALIDAR EXISTENCIA */
if (!file_exists($rutaArchivo)) {
    http_response_code(404);
    exit('El archivo '. $rutaArchivo . ' solicitado no existe.');
}

/* VALIDAR QUE PERTENEZCA AL DIRECTORIO DE BACKUPS */
$rutaReal = realpath($rutaArchivo);
$rutaBase = realpath(RUTA_BACKUPS);

if ($rutaReal === false || strpos($rutaReal, $rutaBase) !== 0) {
    http_response_code(403);
    exit('Acceso denegado.');
}

if (ob_get_length()) {
    ob_end_clean();
}
/* ENVIAR ARCHIVO */
header('Content-Description: File Transfer');
header('Content-Type: application/x-7z-compressed');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($rutaArchivo) .'"');
header('Content-Length: ' . filesize($rutaArchivo));
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Expires: 0');
readfile($rutaArchivo);
exit;