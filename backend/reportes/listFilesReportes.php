<?php
include_once __DIR__ . '/../../backend/config.php';

function obtenerReportes($carpetaReportes)
{
    $directory = BASE_PATH . $carpetaReportes;

    $meses = [
        'ENERO' => 1,
        'FEBRERO' => 2,
        'MARZO' => 3,
        'ABRIL' => 4,
        'MAYO' => 5,
        'JUNIO' => 6,
        'JULIO' => 7,
        'AGOSTO' => 8,
        'SEPTIEMBRE' => 9,
        'OCTUBRE' => 10,
        'NOVIEMBRE' => 11,
        'DICIEMBRE' => 12
    ];

    $data = [];

    // Escanear el directorio
    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            // Para archivos de Excel, PDF y HTML
            if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['xlsm', 'xlsx', 'pdf', 'html'])) {
                $fileParts = explode('_', pathinfo($file, PATHINFO_FILENAME));
                $numElementos = count($fileParts) - 1;

                // Seleccionar el mes y año
                $monthName = $fileParts[$numElementos - 1]; // Nombre del mes en mayúsculas
                $year = $fileParts[$numElementos]; // Año

                // Convertir el nombre del mes a número
                $monthId = isset($meses[$monthName]) ? $meses[$monthName] : null;
                $data[] = [
                    'file' => $file,
                    'repPath' => $carpetaReportes,
                    'year' => $year,
                    'month' => $monthName,
                    'monthId' => $monthId
                ];
            }
        }

        // Ordenar el array por year (descendente), month (descendente) y monthId (descendente)
        usort($data, function ($a, $b) use ($meses) {
            if ($a['year'] !== $b['year']) {
                return $b['year'] <=> $a['year'];
            }
            if ($meses[$a['month']] !== $meses[$b['month']]) {
                return $meses[$b['month']] <=> $meses[$a['month']];
            }
            return $b['monthId'] <=> $a['monthId'];
        });

        header('Content-Type: application/json'); // Asegúrate de que el contenido sea JSON
        echo json_encode($data);
    } else {
        // Manejo de error si el directorio no existe
        header('Content-Type: application/json');
        echo json_encode([]);
    }
}

// Llama a la función con el nombre de la carpeta recibido como parámetro
$carpetaReportes = isset($_GET['carpeta']) ? $_GET['carpeta'] : '';

if (empty($carpetaReportes)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'La carpeta no puede estar vacía.']);
    exit;
}

obtenerReportes($carpetaReportes);
