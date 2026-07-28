<?php
/* Obtiene información de los respaldos y devuelve un JSON */
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/backups/parametrosBackups.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php'; 


date_default_timezone_set('America/Guayaquil');

/* CONFIGURACIÓN */
$rutaBackups = BACKUPS_PATH;

/*  EXTENSIONES VÁLIDAS DE BACKUPS */
define('EXTENSIONES_BACKUP', ['sql','zip','7z','gz','rar']);

/* DIRECTORIOS A IGNORAR */
define('DIRECTORIOS_EXCLUIDOS', [
    '.',
    '..',
    'TEMP',
    'OLD',
    'PRUEBAS',
    '01_GENERADOR',
    '02_GENERADOR_REPORTES',
    'EXTRAS',
    'LastBeforeMig'
]);

/* VALIDAR CARPETA PRINCIPAL */

if (!is_dir($rutaBackups)) {
    echo json_encode([
        'estado'  => false,
        'mensaje' => 'La carpeta de backups no existe.',
        'ruta'    => $rutaBackups
    ]);
    exit;
}

/* OBTENER INFORMACIÓN DE UNA BASE DE DATOS */
function obtenerInformacionBaseDatos(string $rutaBase): array
{

    $nombreBase = basename($rutaBase);
    /* Obtener únicamente los archivos del directorio */
    $archivos = array_filter(scandir($rutaBase),
                             function ($archivo) use ($rutaBase) {
                                $rutaArchivo = $rutaBase . DIRECTORY_SEPARATOR . $archivo; // Debe ser un archivo
                                if (!is_file($rutaArchivo)) {
                                    return false;
                                }
                                // Obtener extensión
                                $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                                // Validar extensión permitida
                                return in_array($extension, EXTENSIONES_BACKUP);
                             });

    /* Si no existen respaldos */
    if (empty($archivos)) {
        return [
            'nombre' => $nombreBase,
            'carpeta' => null,
            'estado' => 'SIN BACKUPS',
            'fechaBackup' => null,
            'dias' => null,
            'tamanoBytes' => 0,
            'tamano' => '0 MB',
            'tamanoTotalBytes' => 0,
            'tamanoTotal' => '0 MB',
            'cantidadBackups' => 0,
            'ruta' => $rutaBase,
            'ultimoArchivo' => null
        ];
    }

    /* Analizar todos los archivos de respaldo */
    $ultimoArchivo     = null;
    $ultimaFecha       = 0;
    $tamanoBytes       = 0;
    $tamanoTotalBytes  = 0;

    foreach ($archivos as $archivo) {
        $rutaArchivo = $rutaBase . DIRECTORY_SEPARATOR . $archivo;
        $fechaArchivo = filemtime($rutaArchivo);
        $tamanoArchivo = filesize($rutaArchivo);
        /* Acumular tamaño total */
        $tamanoTotalBytes += $tamanoArchivo;

        /* Buscar el respaldo más reciente */
        if ($fechaArchivo > $ultimaFecha) {
            $ultimaFecha  = $fechaArchivo;
            $ultimoArchivo = $rutaArchivo;
            $tamanoBytes   = $tamanoArchivo;
        }
    }

    /* Conversión de tamaños */
    $tamanoMB = round($tamanoBytes / UNIDAD_MB, 2);
    $tamanoTotalMB = round($tamanoTotalBytes / UNIDAD_MB, 2);

    /* Antigüedad */
    $dias = floor(
        (time() - $ultimaFecha) / 86400
    );

    /* Estado */
    if ($dias <= DIAS_OK) {
        $estado = 'OK';
    } elseif ($dias <= DIAS_ADVERTENCIA) {
        $estado = 'ADVERTENCIA';
    } else {
        $estado = 'CRITICO';
    }
    
    /* Resultado */
    return [
        'nombre'            => $nombreBase,
        'carpeta'           => $nombreBase,
        'estado'            => $estado,
        'fechaBackup'       => date('Y-m-d H:i:s', $ultimaFecha),
        'dias'              => $dias,
        'tamanoBytes'      => $tamanoBytes,
        'tamano'           => number_format($tamanoMB, 2) . ' MB',
        'tamanoTotalBytes' => $tamanoTotalBytes,
        'tamanoTotal'      => number_format($tamanoTotalMB, 2) . ' MB',
        'cantidadBackups'   => count($archivos),
        'ruta'              => $rutaBase,
        'ultimoArchivo'     => basename($ultimoArchivo)
    ];
}


/* GENERAR RESUMEN GENERAL DE BACKUPS */
function obtenerResumenBackups(array $bases): array
{
    $totalBases = count($bases);
    $ok = 0;
    $advertencias = 0;
    $criticas = 0;
    $sinBackups = 0;
    $espacioTotalBytes = 0;

    foreach ($bases as $base) {
        /* Contabilizar estados */
        switch ($base['estado']) {
            case 'OK':
                $ok++;
                break;
            case 'ADVERTENCIA':
                $advertencias++;
                break;
            case 'CRITICO':
                $criticas++;
                break;
            case 'SIN BACKUPS':
                $sinBackups++;
                break;
        }

        /* Acumular espacio utilizado */
        $espacioTotalBytes += $base['tamanoTotalBytes'];
    }

    /* Conversión tamaño total */
    $espacioGB = round($espacioTotalBytes / UNIDAD_GB,2);

    return [
        'totalBases' => $totalBases,
        'ok' => $ok,
        'advertencias' => $advertencias,
        'criticas' => $criticas,
        'sinBackups' => $sinBackups,
        'espacioTotalBytes' => $espacioTotalBytes,
        'espacioTotal' => $espacioGB . ' GB'
    ];
}

/* RECORRER CARPETAS DE BASES DE DATOS */
$bases = [];
$directorios = array_filter(
    scandir($rutaBackups),
    function ($elemento) use ($rutaBackups) {
        // aqui filtro los directorios que no tomo en cuenta
         if (in_array($elemento, DIRECTORIOS_EXCLUIDOS, true)) {
            return false;
        }
        return is_dir($rutaBackups . DIRECTORY_SEPARATOR . $elemento);
    }
);

sort($directorios, SORT_NATURAL | SORT_FLAG_CASE);

/* Analizar cada base de datos */
foreach ($directorios as $nombreBase) {
    $rutaBase = $rutaBackups . DIRECTORY_SEPARATOR . $nombreBase;
    $bases[] = obtenerInformacionBaseDatos($rutaBase);
}


/* GENERAR RESUMEN GENERAL */
$resumen = obtenerResumenBackups($bases);

/* CONSTRUIR RESPUESTA FINAL */
$respuesta = [
    'estado' => true,
    'fechaActualizacion' => date('Y-m-d H:i:s'),
    'ruta' => $rutaBackups,
    'resumen' => $resumen,
    'bases' => $bases
];

/* DEVOLVER JSON AL FRONTEND */
echo json_encode($respuesta,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;


/* PRUEBA TEMPORAL */
/* header('Content-Type: application/json; charset=utf-8');
echo json_encode($bases,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo json_encode($resumen, JSON_PRETTY_PRINT);
exit; */

