<?php
/* 
error_reporting(E_ALL);
ini_set('display_errors', 1);

 */
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';
require_once BASE_PATH . 'backend/menu/menuRepository.php';

header('Content-Type: application/json');

$menu = obtenerMenuUsuario(
    $conn,
    (int)$user_id,
    (int)$rol_id,
    (int)$inrdireccion_id
);



/* file_put_contents(
    __DIR__ . '/debug_menu.txt',
    json_encode(
        $menu,
        JSON_PRETTY_PRINT
    )
); */

echo json_encode(
    $menu,
    JSON_UNESCAPED_UNICODE
);