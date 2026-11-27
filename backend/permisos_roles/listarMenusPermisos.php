<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';
require_once BASE_PATH . 'backend/menu/menuRepository.php';

header('Content-Type: application/json');


$sql = "SELECT
            m.id,
            COALESCE(mp.titulo,'RAIZ') padre,
            m.titulo,
            COUNT(DISTINCT p.rol_id) roles,
            COUNT(DISTINCT p.direccion_id) direcciones,
            COUNT(DISTINCT p.user_id) usuarios,
            CASE 
                WHEN COUNT(DISTINCT p.rol_id)=0 AND COUNT(DISTINCT p.direccion_id)=0 AND COUNT(DISTINCT p.user_id)=0
                    THEN 'SIN PERMISOS'
                WHEN COUNT(DISTINCT p.rol_id)=0
                    THEN 'SIN ROL'
                    WHEN COUNT(DISTINCT p.direccion_id)=0 AND COUNT(DISTINCT p.user_id)=0
                    THEN 'SIN DIRECCION'
                ELSE 'OK'
            END estado
        FROM menu m
        LEFT JOIN menu mp ON mp.id = m.id_padre 
        LEFT JOIN menu_permiso p ON p.menu_id = m.id
        WHERE m.estRegistro = 1
            AND m.deletedAt IS NULL
        GROUP BY m.id, mp.titulo, m.titulo
        ORDER BY m.id";

$result = $conn->query($sql);

if (!$result) {

    echo json_encode([
        'success' => false,
        'message' => $conn->error
    ]);

    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {

    $data[] = [
        'id'           => (int)$row['id'],
        'padre'        => $row['padre'],
        'titulo'       => $row['titulo'],
        'roles'        => (int)$row['roles'],
        'direcciones'  => (int)$row['direcciones'],
        'usuarios'     => (int)$row['usuarios']
    ];

}

echo json_encode(
    $data,
    JSON_UNESCAPED_UNICODE
);

$conn->close();