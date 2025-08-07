<?php
// download.php
$rutaBase = '//Seps-mv-fileser/inr/Gestión de IR/DIR-NAC-RPLA/5. Productos/Reporte de Diagnostico/';
$archivo = 'Reporte Diagnostico V3_136.xlsm';
$rutaCompleta = $rutaBase . $archivo;

// Verificar que el archivo existe y es legible
if (file_exists($rutaCompleta) && is_readable($rutaCompleta)) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . basename($rutaCompleta) . '"');
    header('Content-Length: ' . filesize($rutaCompleta));
    readfile($rutaCompleta);
    exit;
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Archivo no encontrado";
}