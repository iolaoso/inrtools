<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';

function formatearFecha($fecha)
{
    if (!$fecha) return null;
    $timestamp = strtotime($fecha);
    return $timestamp === false ? $fecha : date('d/m/Y', $timestamp);
}

function valEstructurasRuc($ruc)
{
    try {
        if (!preg_match('/^[0-9]{13}$/', $ruc)) {
            throw new InvalidArgumentException("RUC debe tener 13 dígitos");
        }

        //$rutaJson = BASE_PATH . 'assets/files/reportes/valestructuras/ENT_FECHAS_MAX_EST.json';
        $rutaJson = BASE_PATH . 'assets/files/reportes/valestructuras/ENT_FECHAS_MAX_EST_NO_BOM.json';

        if (!file_exists($rutaJson)) {
            throw new RuntimeException("Archivo JSON no encontrado en: {$rutaJson}");
        }

        $jsonContenido = file_get_contents($rutaJson);

        if ($jsonContenido === false) {
            throw new RuntimeException("No se pudo leer el archivo JSON");
        }

        // Remover BOM (Byte Order Mark) si está presente
        if (substr($jsonContenido, 0, 3) === "\xEF\xBB\xBF") {
            $jsonContenido = substr($jsonContenido, 3);
        }

        $datos = json_decode($jsonContenido, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Error decodificando JSON: " . json_last_error_msg());
        }

        // Verificar que los datos sean un array y no estén vacíos
        if (!is_array($datos) || empty($datos)) {
            return ['success' => false, 'message' => "No hay datos en el archivo JSON", 'datos' => []];
        }

        // Filtrar resultados por RUC
        $resultados = array_filter($datos, fn($fila) => isset($fila['RUC_ENTIDAD']) && trim($fila['RUC_ENTIDAD']) === $ruc);

        // Verificar si se encontraron resultados
        if (empty($resultados)) {
            return ['success' => false, 'message' => "RUC {$ruc} no encontrado", 'datos' => []];
        }

        // Mapear resultados para formatear fechas
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
            ];
        }, $resultados);

        // Retornar resultados
        return [
            'success' => true,
            'message' => count($estructuras) . " estructuras encontradas",
            'datos' => $estructuras, //json_encode($estructuras, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'count' => count($estructuras)
        ];
    } catch (Exception $e) {
        error_log("Error en valEstructurasRuc - RUC: {$ruc} - " . $e->getMessage());
        return ['success' => false, 'message' => "Error al buscar RUC: " . $e->getMessage(), 'datos' => []];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['ruc'])) {
        http_response_code(400);
        echo json_encode(['status' => 'Error', 'message' => 'RUC no puede estar vacío']);
        exit;
    }

    echo json_encode(valEstructurasRuc($data['ruc']));
}
