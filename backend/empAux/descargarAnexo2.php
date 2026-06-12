<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

/**
 * ============================================
 * ARCHIVO A DESCARGAR
 * ============================================
 */

$nombreArchivo = 'Anexo2_EMP_AUX_v1_2.xlsm';

$rutaArchivo =
    BASE_PATH .
    'assets/files/reportes/empaux/' .
    $nombreArchivo;

/**
 * ============================================
 * VALIDAR EXISTENCIA
 * ============================================
 */

if (!file_exists($rutaArchivo)) {

    http_response_code(404);

    exit(
        'No se encontró el archivo: '
        . $nombreArchivo
    );
}

/**
 * ============================================
 * DESCARGA
 * ============================================
 */

if (ob_get_length()) {
    ob_end_clean();
}

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
header('Content-Length: ' . filesize($rutaArchivo));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

readfile($rutaArchivo);

exit;