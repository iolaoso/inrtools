<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

require_once __DIR__ . '/empAuxModel.php';
require_once BASE_PATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Normalizar nombres de columnas
 * La fuccion biene precargada en el archivo fuciones.php
*/


/**
 * ============================================
 * OBTENER DATOS
 * ============================================
 */

$entidades   = resultToArray(obtenerEntidadesRIL());
$eeff        = resultToArray(obtenerEEFFRIL());
$indicadores = resultToArray(obtenerIndicadoresRIL());

/**
 * ============================================
 * DATASET BASE
 * ============================================
 */

$dfFinal = [];

foreach ($entidades as $row) {

    $key =
        $row['FECHA_CORTE']
        . '|'
        . $row['RUC_EMPRESA'];

    $dfFinal[$key] = $row;
}

/**
 * ============================================
 * PIVOT EEFF
 * ============================================
 */

foreach ($eeff as $row) {

    $key =
        $row['FECHA_CORTE']
        . '|'
        . $row['RUC_EMPRESA'];

    if (!isset($dfFinal[$key])) {
        continue;
    }

    $columna =
        'CTA_' .
        $row['CODIGO_CTA'];

    $dfFinal[$key][$columna] =
        $row['VALOR'];
}

/**
 * ============================================
 * PIVOT INDICADORES
 * ============================================
 */

foreach ($indicadores as $row) {

    $key =
        $row['FECHA_CORTE']
        . '|'
        . $row['RUC_EMPRESA'];

    if (!isset($dfFinal[$key])) {
        continue;
    }

    $columna =
        'IND_' .
        normalizarNombreColumna(
            $row['INDICADOR']
        );

    $dfFinal[$key][$columna] =
        $row['VALOR'];
}


/**
 * 
 * 
 * 
 * ============================================
 * CABECERAS DINÁMICAS
 * ============================================
 */

$headersMap = [];

foreach ($dfFinal as $row) {

    foreach ($row as $campo => $valor) {
        $headersMap[$campo] = true;
    }
}

$headers = array_keys($headersMap);




/**
 * ============================================
 * SANITIZAR HEADERS
 * ============================================
 */

$headers = array_map(function($h) {

    $h = preg_replace('/[\x00-\x1F\x7F]/u', '', $h);

    if (strlen($h) > 150) {
        $h = substr($h, 0, 150);
    }

    return trim($h);

}, $headers);

/**
 * ============================================
 * CREAR EXCEL
 * ============================================
 */

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('RIL');

/**
 * ============================================
 * CABECERAS
 * ============================================
 */

foreach ($headers as $i => $header) {

    $celda = Coordinate::stringFromColumnIndex($i + 1) . '1';

    $sheet->setCellValue($celda, $header);
}

$sheet->getStyle(
    'A1:' . $sheet->getHighestColumn() . '1'
)->getFont()->setBold(true);

/**
 * ============================================
 * DATOS
 * ============================================
 */

$fila = 2;

foreach ($dfFinal as $registro) {

    foreach ($headers as $i => $header) {

        $celda =
            Coordinate::stringFromColumnIndex($i + 1)
            . $fila;

        $valor = $registro[$header] ?? '';

        if (is_string($valor)) {

            $valor = preg_replace(
                '/[\x00-\x1F\x7F]/u',
                '',
                $valor
            );
        }

        if ($header === 'RUC_EMPRESA') {

            $sheet->setCellValueExplicit(
                $celda,
                (string)$valor,
                DataType::TYPE_STRING
            );

        } else {

            $sheet->setCellValue(
                $celda,
                $valor
            );
        }
    }

    $fila++;
}

/**
 * ============================================
 * OPCIONES EXCEL
 * ============================================
 */

$sheet->freezePane('A2');

$sheet->setAutoFilter(
    'A1:' .
    $sheet->getHighestColumn() .
    $sheet->getHighestRow()
);

/**
 * ============================================
 * GUARDAR TEMPORALMENTE
 * ============================================
 */

$nombreArchivo =
    'RIL_EMPRESAS_AUX_' .
    date('Ymd_His') .
    '.xlsx';

$tempFile =
    sys_get_temp_dir() .
    DIRECTORY_SEPARATOR .
    $nombreArchivo;

$writer = new Xlsx($spreadsheet);

$writer->save($tempFile);

/**
 * ============================================
 * VALIDACIONES
 * ============================================
 */

if (!file_exists($tempFile)) {

    die('No se pudo generar el archivo');
}

if (filesize($tempFile) <= 0) {

    die('Archivo generado vacío');
}

/**
 * ============================================
 * DESCARGA
 * ============================================
 */

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
header('Content-Length: ' . filesize($tempFile));
header('Cache-Control: must-revalidate');
header('Pragma: public');

readfile($tempFile);

unlink($tempFile);

exit;