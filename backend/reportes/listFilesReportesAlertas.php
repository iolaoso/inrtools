<?php
include_once __DIR__ . '/../../backend/config.php';

// Función para obtener los archivos de diagnóstico
function obtenerReportesAlertas($carpetaReportes)
{
    $directory = BASE_PATH . $carpetaReportes;
    // Obtener archivos
    $archivos = [];

    // Verificar si el directorio existe
    if (is_dir($directory)) {
        $files = scandir($directory);
        $extensionesPermitidas = ['xlsm', 'xls', 'xlsx', 'pbix', 'pdf']; // Extensiones permitidas
        $archivos = [];
        foreach ($files as $file) {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (is_file($filePath) && in_array($extension, $extensionesPermitidas)) {
                $modTime = filemtime($filePath);
                $archivos[] = [
                    'name' => $file,
                    'path' => $carpetaReportes,
                    'directory' => $directory,
                    'filePath' => $filePath,  // Añadido para acceso directo
                    'lastModified' => date('Y-m-d H:i:s', $modTime),
                    'size' => filesize($filePath),
                    'extension' => $extension,
                    'timestamp' => $modTime  // Para ordenar más eficientemente
                ];
            }
        }

        // Ordenar por fecha de modificación (más reciente primero)
        usort($archivos, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];  // Usamos el timestamp precalculado
        });
    } else {
        http_response_code(404);
        echo json_encode([
            'error' => 'El directorio no existe',
            'ruta' => $directory
        ]);
        exit;
    }
    echo json_encode($archivos);
}

// Llama a la función con el nombre de la carpeta recibido como parámetro
$carpetaReportes = isset($_GET['carpeta']) ? $_GET['carpeta'] : '';

if (empty($carpetaReportes)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'La carpeta no puede estar vacía.']);
    exit;
}

obtenerReportesAlertas($carpetaReportes);