<?php
// downloadDiag.php
include_once __DIR__ . '/../../backend/config.php';

// Deshabilitar limitaciones de tiempo
set_time_limit(0);

// Deshabilitar buffers
if (ob_get_level()) {
    ob_end_clean();
}

try {
    // Validar parámetro
    $archivoSolicitado = isset($_GET['archivo']) ? urldecode($_GET['archivo']) : '';

    if (empty($archivoSolicitado)) {
        throw new Exception('Archivo no especificado');
    }

    // Validar ruta (seguridad crítica)
    $rutaBase = '\\\\Seps-mv-fileser\\inr\\Gestión de IR\\DIR-NAC-RPLA\\5. Productos\\Reporte de Diagnostico';
    $rutaNormalizada = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $archivoSolicitado);

    if (strpos($rutaNormalizada, $rutaBase) !== 0) {
        throw new Exception('Acceso no autorizado');
    }

    // Verificar existencia
    if (!file_exists($rutaNormalizada)) {
        throw new Exception('Archivo no encontrado');
    }

    // Obtener información del archivo
    $nombreArchivo = basename($rutaNormalizada);
    $tamanoArchivo = filesize($rutaNormalizada);
    $mimeType = mime_content_type($rutaNormalizada);

    // Configurar headers
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
    header('Content-Length: ' . $tamanoArchivo);
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('X-Accel-Buffering: no'); // Importante para Nginx

    // Leer y enviar el archivo por chunks
    $chunkSize = 8 * 1024 * 1024; // 8MB por chunk

    $handle = fopen($rutaNormalizada, 'rb');
    if ($handle === false) {
        throw new Exception('No se pudo abrir el archivo');
    }

    while (!feof($handle)) {
        echo fread($handle, $chunkSize);
        flush();

        // Verificar si la conexión sigue activa
        if (connection_status() != CONNECTION_NORMAL) {
            fclose($handle);
            exit;
        }
    }

    fclose($handle);
    exit;
} catch (Exception $e) {
    error_log('Error en descarga: ' . $e->getMessage());
    header('HTTP/1.1 500 Internal Server Error');
    die('Error al descargar el archivo: ' . $e->getMessage());
}