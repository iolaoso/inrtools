<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $directorio = BASE_PATH . '/assets/files/reportes/indRanking/02 Ind ranking definitivo/';
    $prefijo = 'rep_ind_ranking_ult_bal_coacs_y_mutualistas_';

    $archivos = glob($directorio . DIRECTORY_SEPARATOR . $prefijo . '*.xlsx');

    if (empty($archivos)) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró el archivo de ranking.'
        ]);
        exit;
    }

    usort($archivos, function ($a, $b) {
        return filemtime($b) <=> filemtime($a);
    });

    $archivo = $archivos[0];

    // Lectura del Excel
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);
    $hoja = $spreadsheet->getActiveSheet();

    // Obtener todas las filas como arreglo asociativo por columna
    $filas = $hoja->toArray(null, true, true, true);

    if (empty($filas)) {
        echo json_encode([
            'success' => false,
            'message' => 'El archivo de ranking no contiene información.'
        ]);
        exit;
    }

    // Primera fila = encabezados
    $encabezados = array_shift($filas);

    // Mapear nombre de columna => letra de Excel
    $columnas = [];

    foreach ($encabezados as $letra => $nombre) {
        $nombre = trim((string) $nombre);

        if ($nombre !== '') {
            $columnas[$nombre] = $letra;
        }
    }

    // Columnas requeridas
    $columnasRequeridas = [
        'ruc',
        'nom_inst',
        'fecha_balance',
        'seg_comyf',
        'estado_actual',
        'c_n_cr_nivel',
        'c_calidad_adm_riesgo',
        'causal_liquidacion',
        'calif_riesgo_def_categ_unif',
        'metodologia_unif'
    ];

    // Validar que existan las columnas
    $columnasFaltantes = [];

    foreach ($columnasRequeridas as $columna) {
        if (!isset($columnas[$columna])) {
            $columnasFaltantes[] = $columna;
        }
    }

    if (!empty($columnasFaltantes)) {
        echo json_encode([
            'success' => false,
            'message' => 'El archivo no contiene todas las columnas requeridas.',
            'faltantes' => $columnasFaltantes
        ]);
        exit;
    }

    // Preparar resultado
    $datos = [];

    // Recorrer registros
    foreach ($filas as $fila) {

        $rucFila = trim((string) ($fila[$columnas['ruc']] ?? ''));

        if ($rucFila === '') {
            continue;
        }

        $datos[] = [
            'ruc' => $rucFila,
            'nom_inst' => trim((string) ($fila[$columnas['nom_inst']] ?? '')),
            'fecha_balance' => trim((string) ($fila[$columnas['fecha_balance']] ?? '')),
            'seg_comyf' => trim((string) ($fila[$columnas['seg_comyf']] ?? '')),
            'estado_actual' => trim((string) ($fila[$columnas['estado_actual']] ?? '')),
            'nivel_riesgo' => trim((string) ($fila[$columnas['c_n_cr_nivel']] ?? '')),
            'administracion_riesgo' => trim((string) ($fila[$columnas['c_calidad_adm_riesgo']] ?? '')),
            'causales_normativas' => trim((string) ($fila[$columnas['causal_liquidacion']] ?? '')),
            'calificacion_riesgo' => trim((string) ($fila[$columnas['c_ranking_total']] ?? ''))
        ];
    }

    // Respuesta JSON
    echo json_encode([
        'success' => true,
        'archivo' => basename($archivo),
        'total' => count($datos),
        'data' => $datos
    ], JSON_UNESCAPED_UNICODE);
    exit;
    
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al consultar el ranking.'
    ]);
}