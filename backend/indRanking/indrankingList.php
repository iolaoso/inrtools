<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

function claseNivelRiesgo($nivel = NULL)
{
    return match (strtoupper(trim($nivel))) {
        'CRÍTICO', 'CRITICO','CRíTICO'  => 'background-color: #FF0000; color: #000000;',
        'ALTO'                          => 'background-color: #FFC000; color: #000000;',
        'MEDIO'                         => 'background-color: #FFFF00; color: #000000;',
        'BAJO'                          => 'background-color: #BDD7EE; color: #000000;',
        'MUY BAJO'                      => 'background-color: #92D050; color: #000000;',
        default                         => 'background-color: #484952; color: #000000;'
    };
}


function obtenerRiesgoEntidad_old(){
    try {
        $directorio = BASE_PATH . 'assets/files/reportes/indRanking/05 Reporte de calificación/';
        $prefijo = 'rep_ind_ranking_ult_bal_coacs_y_mutualistas_';
        $archivos = glob($directorio . DIRECTORY_SEPARATOR . $prefijo . '*.xlsx');
        if (empty($archivos)) {
            return [
                'success' => false,
                'message' => 'No se encontró el archivo de ranking.'
            ];
        }
        // ordena para saber cual es el ultimo archivo 
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
            return [
                'success' => false,
                'message' => 'El archivo de ranking no contiene información.'
            ];
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
            'causal_liquidacion_cat',
            'criterio_solv_seps_cat',
            'calif_riesgo_def_cat_unif',
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
            return [
                'success' => false,
                'message' => 'El archivo no contiene todas las columnas requeridas.',
                'faltantes' => $columnasFaltantes
            ]; 
        }

        // Preparar resultado
        $datos  = [];

        // Recorrer registros
        foreach ($filas as $fila) {
            $rucFila = trim((string) ($fila[$columnas['ruc']] ?? ''));

            if ($rucFila === '') {
                continue;
            }

            $datos [] = [
                'ruc' => $rucFila,
                'nom_inst' => strtoupper(trim((string) ($fila[$columnas['nom_inst']] ?? ''))),
                'fecha_balance' => strtoupper(trim((string) ($fila[$columnas['fecha_balance']] ?? ''))),
                'seg_comyf' => strtoupper(trim((string) ($fila[$columnas['seg_comyf']] ?? ''))),
                'estado_actual' => strtoupper(trim((string) ($fila[$columnas['estado_actual']] ?? ''))),
                'c_n_cr_nivel' => strtoupper(trim((string) ($fila[$columnas['c_n_cr_nivel']] ?? ''))),
                'c_calidad_adm_riesgo' => strtoupper(trim((string) ($fila[$columnas['c_calidad_adm_riesgo']] ?? ''))),
                'causal_liquidacion_cat' => strtoupper(trim((string) ($fila[$columnas['causal_liquidacion_cat']] ?? ''))),
                'criterio_solv_seps_cat' => strtoupper(trim((string) ($fila[$columnas['criterio_solv_seps_cat']] ?? ''))),
                'calif_riesgo_def_cat_unif' => strtoupper(trim((string) ($fila[$columnas['calif_riesgo_def_cat_unif']] ?? ''))),
                'metodologia_unif' => strtoupper(trim((string) ($fila[$columnas['metodologia_unif']] ?? '')))
            ];
        }

        // Respuesta JSON - con el riesgo entidad
        return [
            'success' => true,
            'archivo' => basename($archivo),
            'total' => count($datos),
            'data' => $datos
        ];

    } catch (Throwable $e) {
        return [
            'success' => false,
            'message' => 'Error al consultar el ranking.'
        ];
    }
}

function obtenerRiesgoEntidad()
{
    try {

        $directorio = BASE_PATH . 'assets/files/reportes/indRanking/05 Reporte de calificación/';
        $prefijo = 'rep_ind_ranking_ult_bal_coacs_y_mutualistas_';

        $archivos = glob($directorio . DIRECTORY_SEPARATOR . $prefijo . '*.xlsx');

        if (empty($archivos)) {
            return [
                'success' => false,
                'message' => 'No se encontró el archivo de ranking.'
            ];
        }

        // Obtener el archivo más reciente
        usort($archivos, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        $archivo = $archivos[0];

        // Cargar Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);
        $hoja = $spreadsheet->getActiveSheet();

        // Obtener encabezados (fila 1)
        $encabezados = $hoja->rangeToArray(
            'A1:' . $hoja->getHighestColumn() . '1',
            null,
            true,
            true,
            true
        )[1];

        if (empty($encabezados)) {
            return [
                'success' => false,
                'message' => 'El archivo de ranking no contiene información.'
            ];
        }

        // Mapear nombre de columna => letra Excel
        $columnas = [];

        foreach ($encabezados as $letra => $nombre) {

            $nombre = trim((string)$nombre);

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
            'causal_liquidacion_cat',
            'criterio_solv_seps_cat',
            'calif_riesgo_def_cat_unif',
            'metodologia_unif'
        ];

        // Validar columnas obligatorias
        $columnasFaltantes = [];

        foreach ($columnasRequeridas as $columna) {
            if (!isset($columnas[$columna])) {
                $columnasFaltantes[] = $columna;
            }
        }

        if (!empty($columnasFaltantes)) {
            return [
                'success' => false,
                'message' => 'El archivo no contiene todas las columnas requeridas.',
                'faltantes' => $columnasFaltantes
            ];
        }

        $datos = [];
        $ultimaFila = $hoja->getHighestRow();

        // Recorrer todas las filas de datos
        for ($numFila = 2; $numFila <= $ultimaFila; $numFila++) {

            $registro = [];

            foreach ($columnasRequeridas as $nombreColumna) {

                $letra = $columnas[$nombreColumna];
                $celda = $hoja->getCell($letra . $numFila);

                // Detectar automáticamente si es una fecha
                if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($celda)) {

                    $valor = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                        $celda->getValue()
                    )->format('Y-m-d');

                } else {

                    $valor = trim((string)$celda->getFormattedValue());

                    // Mantener RUC tal como está
                    if ($nombreColumna !== 'ruc') {
                        $valor = strtoupper($valor);
                    }
                }

                $registro[$nombreColumna] = $valor;
            }

            // Omitir filas vacías
            if (empty($registro['ruc'])) {
                continue;
            }

            $datos[] = $registro;
        }

        return [
            'success' => true,
            'archivo' => basename($archivo),
            'total' => count($datos),
            'data' => $datos
        ];

    } catch (\Throwable $e) {

        error_log($e->getMessage());

        return [
            'success' => false,
            'message' => 'Error al consultar el ranking.',
            'error' => $e->getMessage()
        ];
    }
}

