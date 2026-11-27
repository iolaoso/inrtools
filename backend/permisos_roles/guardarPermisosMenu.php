<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

header('Content-Type: application/json; charset=utf-8');

try {

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!$input) {
        throw new Exception(
            'No se recibió información'
        );
    }

    $menuId = (int)($input['menu_id'] ?? 0);

    $roles =
        $input['roles'] ?? [];

    $direcciones =
        $input['direcciones'] ?? [];

    $usuarios =
        $input['usuarios'] ?? [];

    if ($menuId <= 0) {
        throw new Exception(
            'Menú inválido'
        );
    }

    $conn->begin_transaction();

    /* =====================================
       ELIMINAR PERMISOS EXISTENTES
    ====================================== */

    $sql = "
        DELETE
        FROM menu_permiso
        WHERE menu_id = ?
    ";

    $stmt =
        $conn->prepare($sql);

    $stmt->bind_param(
        'i',
        $menuId
    );

    $stmt->execute();

    $stmt->close();

    /* =====================================
       INSERT ROLES
    ====================================== */

    $sql = "
        INSERT INTO menu_permiso
        (
            menu_id,
            rol_id,
            tipo_restriccion,
            efecto,
            UsrCreacion
        )
        VALUES
        (
            ?,
            ?,
            'NORMAL',
            'ALLOW',
            ?
        )
    ";

    $stmt =
        $conn->prepare($sql);

    foreach ($roles as $rolId) {

        $rolId =
            (int)$rolId;

        $stmt->bind_param(
            'iis',
            $menuId,
            $rolId,
            $nickname
        );

        $stmt->execute();
    }

    $stmt->close();

    /* =====================================
       INSERT DIRECCIONES
    ====================================== */

    $sql = "
        INSERT INTO menu_permiso
        (
            menu_id,
            direccion_id,
            tipo_restriccion,
            efecto,
            UsrCreacion
        )
        VALUES
        (
            ?,
            ?,
            'NORMAL',
            'ALLOW',
            ?
        )
    ";

    $stmt =
        $conn->prepare($sql);

    foreach ($direcciones as $direccionId) {

        $direccionId =
            (int)$direccionId;

        $stmt->bind_param(
            'iis',
            $menuId,
            $direccionId,
            $nickname
        );

        $stmt->execute();
    }

    $stmt->close();

    /* =====================================
       INSERT USUARIOS
    ====================================== */

    $sql = "
        INSERT INTO menu_permiso
        (
            menu_id,
            user_id,
            tipo_restriccion,
            efecto,
            UsrCreacion
        )
        VALUES
        (
            ?,
            ?,
            'NORMAL',
            'ALLOW',
            ?
        )
    ";

    $stmt =
        $conn->prepare($sql);

    foreach ($usuarios as $userId) {

        $userId =
            (int)$userId;

        $stmt->bind_param(
            'iis',
            $menuId,
            $userId,
            $nickname
        );

        $stmt->execute();
    }

    $stmt->close();

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' =>
            'Permisos actualizados correctamente'
    ]);

} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();