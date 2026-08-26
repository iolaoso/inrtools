<?php
include_once '../helpers/rbitacora.php';

header('Content-Type: application/json; charset=utf-8');

try {

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('No se recibieron datos JSON válidos.');
    }

    $accion = $input['accion'] ?? '';
    $modulo = $input['modulo'] ?? '';
    $descripcion = $input['descripcion'] ?? '';
    $nombreArchivo = $input['nombreArchivo'] ?? '';
    $rutaArchivo = $input['rutaArchivo'] ?? '';
    $tipoArchivo = $input['tipoArchivo'] ?? '';
    $tamanioArchivo = $input['tamanioArchivo'] ?? 0;

    // Aquí va tu lógica de inserción en la bitácora
    insertarBitacora('DESCARGA',$modulo,$descripcion,
                      $nombreArchivo,$rutaArchivo,$tipoArchivo,$tamanioArchivo); 
    
    echo json_encode([
        'success' => true,
        'message' => 'Bitacora registrada correctamente.',
        'accion' => $accion,
        'modulo' => $modulo,
        'descripcion' => $descripcion,
        'nombreArchivo' => $nombreArchivo,
        'rutaArchivo' => $rutaArchivo,
        'tipoArchivo' => $tipoArchivo,
        'tamanioArchivo' => $tamanioArchivo
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}