<?php
// files.php - Backend para listar archivos
header('Content-Type: application/json');
include_once __DIR__ . '/../../backend/config.php';

// Validar sesión primero
include BASE_PATH . 'backend/session.php';
//session_start();

// Obtener parámetros
$carpetaSolicitada = isset($_GET['carpeta']) ? $_GET['carpeta'] : '';

// Validar ruta - seguridad importante
//$rutaBase = '//Seps-mv-fileser/inr/Gestión de IR/DIR-NAC-RPLA/5. Productos/Reporte de Diagnostico';
$rutaBase = $carpetaSolicitada;
$rutaCompleta = realpath($rutaBase);

// Verificar que la ruta solicitada esté dentro del directorio permitido
if (strpos(realpath($carpetaSolicitada), $rutaCompleta) !== 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso no autorizado a la ruta especificada']);
    exit;
}

// Verificar si el directorio existe
if (!is_dir($carpetaSolicitada)) {
    http_response_code(404);
    echo json_encode(['error' => 'El directorio no existe']);
    exit;
}

// Obtener archivos
$archivos = [];
$elementos = scandir($carpetaSolicitada);
$extensionesPermitidas = ['xlsm', 'xls', 'xlsx']; // Extensiones permitidas

foreach ($elementos as $elemento) {
    if ($elemento === '.' || $elemento === '..') continue;

    // Saltar archivos ocultos (que comienzan con punto)
    if (substr($elemento, 0, 1) === '.' || substr($elemento, 0, 1) === '~') continue;

    //$ruta = $carpetaSolicitada . DIRECTORY_SEPARATOR . $elemento;
    $ruta = $carpetaSolicitada . "/" . $elemento;
    $extension = strtolower(pathinfo($elemento, PATHINFO_EXTENSION));
    $rutaNet = str_replace('/', '\\', $ruta); // Reemplaza / por \

    // Solo archivos (no directorios) con extensiones permitidas
    if (is_file($ruta) && in_array($extension, $extensionesPermitidas)) {
        $archivos[] = [
            'name' => $elemento,
            'path' => $ruta,
            'net_path' => $rutaNet,
            'lastModified' => date('Y-m-d H:i:s', filemtime($ruta)),
            'size' => filesize($ruta),
            'extension' => $extension
        ];
    }
}

// Ordenar por fecha de modificación (más reciente primero)
usort($archivos, function ($a, $b) {
    return filemtime($b['path']) - filemtime($a['path']);
});

echo json_encode($archivos);