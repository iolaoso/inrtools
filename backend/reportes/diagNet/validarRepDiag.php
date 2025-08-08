<?php
// test_file_access.php
header('Content-Type: text/plain; charset=utf-8');


// nombre del archivo por GET
echo "\n=== INFORMACIÓN DEL ARCHIVO GET ===\n";
if (isset($_GET['archivo']) && !empty($_GET['archivo'])) {
    echo 'Archivo solicitado: ' . htmlspecialchars($_GET['archivo']) . "\n";
} else {
    echo 'No se proporcionó un archivo válido.' . "\n";
    exit;
}

// Configuración
$file = '\\\\Seps-mv-fileser\\inr\\Gestión de IR\\DIR-NAC-RPLA\\5. Productos\\Reporte de Diagnostico\\' . $_GET['archivo'];


// Función para verificar archivo con detalles extendidos
function checkFileAccess($filePath)
{
    $result = [
        'Archivo' => $filePath,
        'Existe' => file_exists($filePath) ? '✅ Sí' : '❌ No',
        'Tamaño' => file_exists($filePath) ? formatBytes(filesize($filePath)) : 'N/A',
        'Legible' => is_readable($filePath) ? '✅ Sí' : '❌ No',
        'Tipo' => file_exists($filePath) ? (is_dir($filePath) ? 'Directorio' : 'Archivo') : 'N/A',
        'Última modificación' => file_exists($filePath) ? date('Y-m-d H:i:s', filemtime($filePath)) : 'N/A',
        'Permisos' => file_exists($filePath) ? substr(sprintf('%o', fileperms($filePath)), -4) : 'N/A',
        'Error' => file_exists($filePath) ? 'Ninguno' : (is_string($filePath) ? 'Archivo no encontrado' : 'Ruta inválida')
    ];

    // Verificación adicional para rutas UNC
    if (strpos($filePath, '\\\\') === 0) {
        $result['Tipo Ruta'] = 'UNC (Red Windows)';
        $result['Acceso a red'] = testNetworkConnection(dirname($filePath));
    }

    return $result;
}

// Función para formatear bytes
function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Función para probar conexión de red
function testNetworkConnection($path)
{
    try {
        $handle = @opendir($path);
        if ($handle) {
            closedir($handle);
            return '✅ Conexión exitosa';
        }
        return '❌ Error al acceder';
    } catch (Exception $e) {
        return '❌ ' . $e->getMessage();
    }
}

// Ejecutar verificación
$fileInfo = checkFileAccess($file);

// Mostrar resultados
echo "\n=== VERIFICACIÓN DE ACCESO A ARCHIVO EN RED ===\n\n";
foreach ($fileInfo as $key => $value) {
    echo str_pad($key . ':', 25) . $value . "\n";
}

echo "\n=== link de descarga ===\n";
echo '<a href="' . htmlspecialchars($file) . '" download>Descargar archivo</a>' . "\n";

// Información adicional del servidor
echo "\n=== INFORMACIÓN DEL SERVIDOR ===\n";
echo 'Sistema Operativo: ' . php_uname() . "\n";
echo 'Usuario PHP: ' . get_current_user() . "\n";
echo 'Módulos Apache: ' . (function_exists('apache_get_modules') ? implode(', ', apache_get_modules()) : 'N/A') . "\n";
echo 'allow_url_fopen: ' . (ini_get('allow_url_fopen') ? 'On' : 'Off') . "\n";
echo 'safe_mode: ' . (ini_get('safe_mode') ? 'On' : 'Off') . "\n";