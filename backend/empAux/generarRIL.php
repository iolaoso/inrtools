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
 * CREAR EXCEL
 * ============================================
 */

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('RIL');

/**
 * ============================================
 * ENCABEZADOS
 * ============================================
 */

$columna = 1;

foreach ($headers as $header) {

    $celda =
        Coordinate::stringFromColumnIndex($columna)
        . '1';

    $sheet->setCellValue(
        $celda,
        $header
    );

    $columna++;
}

/**
 * Encabezado en negrita
 */

$ultimaColumna = $sheet->getHighestColumn();

$sheet->getStyle(
    'A1:' . $ultimaColumna . '1'
)->getFont()->setBold(true);

/**
 * ============================================
 * DATOS
 * ============================================
 */

$fila = 2;

foreach ($dfFinal as $registro) {

    $columna = 1;

    foreach ($headers as $header) {

        $valor = $registro[$header] ?? '';

        $celda =
            Coordinate::stringFromColumnIndex($columna)
            . $fila;

        /**
         * RUC_EMPRESA como texto
         */
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

        $columna++;
    }

    $fila++;
}

/**
 * ============================================
 * NOMBRE ARCHIVO
 * ============================================
 */

$nombreArchivo =
    'RIL_EMPRESAS_AUX_' .
    date('Ymd_His') .
    '.xlsx';

/**
 * ============================================
 * DESCARGAR
 * ============================================
 */

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header(
    'Content-Disposition: attachment; filename="' .
    $nombreArchivo .
    '"'
);

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);

$writer->save('php://output');

exit;