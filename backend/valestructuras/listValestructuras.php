<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
require_once BASE_PATH . 'backend/conexiones/excel_connection.php';

use PhpOffice\PhpSpreadsheet\Shared\Date;


function valEstructuras()
{
    try {
        $archivoExcel = conectarExcel(BASE_PATH . 'assets\files\reportes\valestructuras\VALEST_v2_2.xlsm');
        $spreadsheet = $archivoExcel->getSheetByName('REG_GEN');
        $datos = $spreadsheet->toArray();

        // Eliminar cabecera si existe
        if (!empty($datos)) {
            array_shift($datos);
        }

        $resultado = [];
        foreach ($datos as $fila) {
            if (count($fila) >= 3) {
                $resultado[] = [
                    'codigo' => $fila[0] ?? '',
                    'nombre' => $fila[1] ?? '',
                    'fecha' => $fila[2] ?? ''
                ];
            }
        }

        return $resultado;
    } catch (Exception $e) {
        error_log("Error en valEstructuras: " . $e->getMessage());
        return [];
    }
}

//////////////////////////////////////////
// Nueva función para leer desde JSON //
//////////////////////////////////////////

function valEstructurasRuc($ruc)
{
    try {
        // Validar formato de RUC
        if (!preg_match('/^[0-9]{13}$/', $ruc)) {
            throw new InvalidArgumentException("RUC debe tener 13 dígitos");
        }

        // Ruta del archivo JSON (ajusta según ubicación real)
        $rutaJson = BASE_PATH . 'assets/files/reportes/valestructuras/ENT_FECHAS_MAX_EST.json';

        if (!file_exists($rutaJson)) {
            throw new RuntimeException("Archivo JSON no encontrado en: {$rutaJson}");
        }

        // Leer y decodificar JSON
        $jsonContenido = file_get_contents($rutaJson);
        $datos = json_decode($jsonContenido, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Error decodificando JSON: " . json_last_error_msg());
        }

        if (!is_array($datos) || empty($datos)) {
            return [
                'success' => false,
                'message' => "No hay datos en el archivo JSON",
                'datos' => []
            ];
        }

        // Filtrar por RUC (clave 'RUC_ENTIDAD', ajusta si es distinto)
        $resultados = array_filter($datos, function ($fila) use ($ruc) {
            return isset($fila['RUC_ENTIDAD']) && trim($fila['RUC_ENTIDAD']) === $ruc;
        });

        if (empty($resultados)) {
            return [
                'success' => false,
                'message' => "RUC {$ruc} no encontrado",
                'datos' => []
            ];
        }

        // Procesar resultados (mapear columnas)
        $estructuras = array_map(function ($fila) {
            return [
                'RUC_ENTIDAD' => $fila['RUC_ENTIDAD'] ?? null,
                'RAZON_SOCIAL' => $fila['RAZON_SOCIAL'] ?? null,
                'SEGMENTO' => $fila['SEGMENTO'] ?? null,
                'NVL_RIESGO' => $fila['NVL_RIESGO'] ?? null,
                'ESTRUCTURA' => $fila['ESTRUCTURA'] ?? null,
                'NOM_ESTRUCTURA' => $fila['NOM_ESTRUCTURA'] ?? null,
                'CUMPLE' => $fila['CUMPLE'] ?? null,
                'MAX_FECHA_CORTE' => isset($fila['MAX_FECHA_CORTE']) ? formatearFecha($fila['MAX_FECHA_CORTE']) : null,
                'FECHA_ENTREGA_ACTUAL' => isset($fila['FECHA_ENTREGA_ACTUAL']) ? formatearFecha($fila['FECHA_ENTREGA_ACTUAL']) : null,
                'MAX_FECHA_VALIDACION' => isset($fila['MAX_FECHA_VALIDACION']) ? formatearFecha($fila['MAX_FECHA_VALIDACION']) : null,
                // Añade más campos si es necesario
            ];
        }, $resultados);

        return [
            'success' => true,
            'message' => count($estructuras) . " estructuras encontradas",
            'datos' => $estructuras,
            'count' => count($estructuras)
        ];
    } catch (Exception $e) {
        error_log("Error en valEstructurasRuc - RUC: {$ruc} - " . $e->getMessage());
        return [
            'success' => false,
            'message' => "Error al buscar RUC: " . $e->getMessage(),
            'datos' => []
        ];
    }
}

/**
 * Función para formatear fechas en formato deseado.
 * Si tus fechas vienen en formato texto ISO, puedes ajustar aquí.
 */
function formatearFecha($fecha)
{
    if (!$fecha) {
        return null;
    }
    // Ejemplo: convertir formato YYYY-MM-DD o similar a dd/mm/yyyy
    $timestamp = strtotime($fecha);
    if ($timestamp === false) {
        return $fecha; // Devolver original si no reconoce formato
    }
    return date('d/m/Y', $timestamp);
}

//////////////////////////////////////////////
// Función auxiliar para formatear fechas de Excel si es necesario //
//////////////////////////////////////////////

/* 
// Función auxiliar para formatear la fecha si es necesario
function formatearFechaExcel($valorFecha)
{
    if (is_numeric($valorFecha)) {
        //return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valorFecha)->format('d/m/Y');
        return Date::excelToDateTimeObject($valorFecha)->format('d/m/Y');
    }
    return $valorFecha;
}

/*
 //* Obtiene datos de estructuras filtrados por RUC (comenzando desde fila 3)
 //* @param string $ruc Número de RUC a buscar (13 dígitos)
 //* @return array Resultado con estructura consistente

function valEstructurasRucExcel($ruc)
{
    try {
        // Validar formato de RUC
        if (!preg_match('/^[0-9]{13}$/', $ruc)) {
            throw new InvalidArgumentException("RUC debe tener 13 dígitos");
        }

        $archivoExcel = conectarExcel(BASE_PATH . 'assets/files/reportes/valestructuras/VALEST_v2_2.xlsm');
        $sheet = $archivoExcel->getSheetByName('REG_GEN');

        if (!$sheet) {
            throw new RuntimeException("Hoja 'REG_GEN' no encontrada");
        }

        // Obtener datos desde fila 5 (índice 2 en base 0)
        $highestRow = $sheet->getHighestRow();
        $data = [];

        for ($row = 5; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray("A{$row}:Z{$row}", null, true, false)[0];
            if (!empty($rowData[0])) {  // Filtra filas vacías
                $data[] = $rowData;
            }
        }

        // Filtrar por RUC (asumiendo columna A = RUC)
        $resultados = array_filter($data, function ($fila) use ($ruc) {
            return trim($fila[0]) === $ruc;
        });

        if (empty($resultados)) {
            return [
                'success' => false,
                'message' => "RUC {$ruc} no encontrado",
                'datos' => []
            ];
        }

        // Procesar resultados (mapear columnas)
        $estructuras = array_map(function ($fila) {
            return [
                'RUC_ENTIDAD' => $fila[0] ?? null,    // Columna A
                'RAZON_SOCIAL' => $fila[1] ?? null,    // Columna B
                'SEGMENTO' => $fila[2] ?? null,    // Columna C
                'NVL_RIESGO' => $fila[3] ?? null,    // Columna D
                'ESTRUCTURA' => $fila[4] ?? null,    // Columna E
                'NOM_ESTRUCTURA' => $fila[5] ?? null,    // Columna F
                'CUMPLE' => $fila[6] ?? null,    // Columna G
                'MAX_FECHA_CORTE' => formatearFechaExcel($fila[7]) ?? null,    // Columna H
                'FECHA_ENTREGA_ACTUAL' => formatearFechaExcel($fila[8]) ?? null,    // Columna I
                'MAX_FECHA_VALIDACION' => formatearFechaExcel($fila[9]) ?? null,    // Columna J
                // Añadir más campos según estructura real
            ];
        }, $resultados);

        return [
            'success' => true,
            'message' => count($estructuras) . " estructuras encontradas",
            'datos' => $estructuras,
            'count' => count($estructuras)
        ];
    } catch (Exception $e) {
        error_log("Error en valEstructurasRuc - RUC: {$ruc} - " . $e->getMessage());
        return [
            'success' => false,
            'message' => "Error al buscar RUC: " . $e->getMessage(),
            'datos' => []
        ];
    }
}
*/


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener el RUC de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que el RUC esté presente y no esté vacío
    if (empty($data['ruc'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'Error',
            'message' => 'RUC no puede estar vacio'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Datos obtenidos correctamente',
        'datos' => valEstructurasRuc($data['ruc'])
    ]);
}
