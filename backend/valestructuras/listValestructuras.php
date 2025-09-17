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

        $rutaJson = BASE_PATH . 'assets/files/reportes/valestructuras/ENT_FECHAS_MAX_EST.json';

        if (!file_exists($rutaJson)) {
            throw new RuntimeException("Archivo JSON no encontrado en: {$rutaJson}");
        }

        $jsonContenido = file_get_contents($rutaJson);

        // Re-encode to UTF-8 to fix malformed bytes
        $jsonContenido = mb_convert_encoding($jsonContenido, 'UTF-8', 'UTF-8');

        // O también probar con:
        // $jsonContenido = utf8_encode($jsonContenido);

        $datos = json_decode($jsonContenido, true);


        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Error decodificando JSON: " . json_last_error_msg());
        }

        if (!is_array($datos) || empty($datos)) {
            return ['success' => false, 'message' => "No hay datos en el archivo JSON", 'datos' => []];
        }

        $resultados = array_filter($datos, fn($fila) => isset($fila['RUC_ENTIDAD']) && trim($fila['RUC_ENTIDAD']) === $ruc);

        if (empty($resultados)) {
            return ['success' => false, 'message' => "RUC {$ruc} no encontrado", 'datos' => []];
        }

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

        return [
            'success' => true,
            'message' => count($estructuras) . " estructuras encontradas",
            'datos' => $estructuras,
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
