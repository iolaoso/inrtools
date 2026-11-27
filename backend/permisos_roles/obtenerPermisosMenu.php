<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

header('Content-Type: application/json; charset=utf-8');


$menuId = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

if ($menuId <= 0) {

    echo json_encode([
        'success' => false,
        'roles' => [],
        'direcciones' => [],
        'usuarios' => []
    ]);

    exit;
}

/* ==========================================
   ROLES
========================================== */

$roles = [];

$sql = "
SELECT
    r.id,
    r.nombre,

    CASE
        WHEN EXISTS (
            SELECT 1
            FROM menu_permiso mp
            WHERE mp.menu_id = ?
              AND mp.rol_id = r.id
        )
        THEN 1
        ELSE 0
    END asignado

FROM roles r

WHERE
    r.estRegistro = 1
    AND r.deletedAt IS NULL

ORDER BY r.nombre
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $menuId);
$stmt->execute();

$rs = $stmt->get_result();

while ($row = $rs->fetch_assoc()) {

    $roles[] = [
        'id' => (int)$row['id'],
        'nombre' => $row['nombre'],
        'asignado' => (bool)$row['asignado']
    ];
}

/* ==========================================
   DIRECCIONES
========================================== */

$direcciones = [];

$sql = "
SELECT
    d.id,
    d.direccion,

    CASE
        WHEN EXISTS (
            SELECT 1
            FROM menu_permiso mp
            WHERE mp.menu_id = ?
              AND mp.direccion_id = d.id
        )
        THEN 1
        ELSE 0
    END asignado

FROM inrdireccion d

WHERE
    d.estRegistro = 1
    AND d.deletedAt IS NULL

ORDER BY d.direccion
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $menuId);
$stmt->execute();

$rs = $stmt->get_result();

while ($row = $rs->fetch_assoc()) {

    $direcciones[] = [
        'id' => (int)$row['id'],
        'direccion' => $row['direccion'],
        'asignado' => (bool)$row['asignado']
    ];
}

/* ==========================================
   USUARIOS
========================================== */

$usuarios = [];

$sql = "
SELECT
    u.id,
    u.nickname,

    CASE
        WHEN EXISTS (
            SELECT 1
            FROM menu_permiso mp
            WHERE mp.menu_id = ?
              AND mp.user_id = u.id
        )
        THEN 1
        ELSE 0
    END asignado

FROM users u

WHERE
    u.estRegistro = 1
    AND u.deletedAt IS NULL

ORDER BY u.nickname
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $menuId);
$stmt->execute();

$rs = $stmt->get_result();

while ($row = $rs->fetch_assoc()) {

    $usuarios[] = [
        'id' => (int)$row['id'],
        'nickname' => $row['nickname'],
        'asignado' => (bool)$row['asignado']
    ];
}

/* ==========================================
   RESPUESTA
========================================== */

echo json_encode([
    'success' => true,
    'menu_id' => $menuId,
    'roles' => $roles,
    'direcciones' => $direcciones,
    'usuarios' => $usuarios
], JSON_UNESCAPED_UNICODE);

$conn->close();