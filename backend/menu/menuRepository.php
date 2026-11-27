<?php

function obtenerMenusRaiz(
    mysqli $conn
): array
{
    $sql = "
        SELECT id
        FROM menu
        WHERE id_padre IS NULL
          AND activo = 1
    ";

    $rs = $conn->query($sql);

    $menus = [];

    while ($row = $rs->fetch_assoc()) {
        $menus[] = (int)$row['id'];
    }

    return $menus;
}

function obtenerMenuIdsPermitidos(
    mysqli $conn,
    int $userId,
    int $rolId,
    int $direccionId
): array
{
    $roles = [];
    $direcciones = [];
    $usuarios = [];

    // Roles
    $stmt = $conn->prepare("
        SELECT DISTINCT menu_id
        FROM menu_permiso
        WHERE rol_id = ?
    ");

    $stmt->bind_param('i', $rolId);
    $stmt->execute();

    $rs = $stmt->get_result();

    while ($row = $rs->fetch_assoc()) {
        $roles[] = (int)$row['menu_id'];
    }

    // Direcciones
    $stmt = $conn->prepare("
        SELECT DISTINCT menu_id
        FROM menu_permiso
        WHERE direccion_id = ?
    ");

    $stmt->bind_param('i', $direccionId);
    $stmt->execute();

    $rs = $stmt->get_result();

    while ($row = $rs->fetch_assoc()) {
        $direcciones[] = (int)$row['menu_id'];
    }

    // Usuarios
    $stmt = $conn->prepare("
        SELECT DISTINCT menu_id
        FROM menu_permiso
        WHERE user_id = ?
    ");

    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $rs = $stmt->get_result();

    while ($row = $rs->fetch_assoc()) {
        $usuarios[] = (int)$row['menu_id'];
    }

    return array_values(
        array_unique(
            array_merge(
                array_intersect(
                    $roles,
                    $direcciones
                ),
                $usuarios
            )
        )
    );
}

function obtenerMenuIdsConPadres(
    mysqli $conn,
    array $menuIds
): array
{
    $resultado = $menuIds;

    foreach ($menuIds as $menuId) {

        $actual = $menuId;

        while ($actual) {

            $sql = "
                SELECT
                    id_padre
                FROM menu
                WHERE id = ?
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                'i',
                $actual
            );

            $stmt->execute();

            $row = $stmt
                ->get_result()
                ->fetch_assoc();

            if (
                !$row ||
                empty($row['id_padre'])
            ) {
                break;
            }

            $actual =
                (int)$row['id_padre'];

            $resultado[] =
                $actual;
        }
    }

    return array_values(
        array_unique($resultado)
    );
}

function obtenerMenuIdsConHijos(
    mysqli $conn,
    array $menuIds
): array
{
    $resultado = $menuIds;

    $pendientes = $menuIds;

    while (!empty($pendientes)) {

        $actual = array_shift(
            $pendientes
        );

        $sql = "
            SELECT id
            FROM menu
            WHERE id_padre = ?
        ";

        $stmt = $conn->prepare(
            $sql
        );

        $stmt->bind_param(
            'i',
            $actual
        );

        $stmt->execute();

        $rs =
            $stmt->get_result();

        while (
            $row =
            $rs->fetch_assoc()
        ) {

            $hijo =
                (int)$row['id'];

            if (
                !in_array(
                    $hijo,
                    $resultado,
                    true
                )
            ) {

                $resultado[] =
                    $hijo;

                $pendientes[] =
                    $hijo;
            }
        }
    }

    return $resultado;
}

function obtenerMenus(
    mysqli $conn,
    array $menuIds
): array
{
    if (empty($menuIds)) {
        return [];
    }

    $ids = implode(
        ',',
        array_map(
            'intval',
            $menuIds
        )
    );

    $sql = "
        SELECT
            id,
            id_padre,
            titulo,
            url,
            icono,
            orden
        FROM menu
        WHERE id IN ($ids)
          AND activo = 1
        ORDER BY orden,id
    ";

    $rs =
        $conn->query($sql);

    $menus = [];

    while (
        $row =
        $rs->fetch_assoc()
    ) {
        $menus[] = $row;
    }

    return $menus;
}

function construirArbolMenu(array $menus): array
{
    $items = [];

    foreach ($menus as $menu) {

        $items[$menu['id']] = [
            'id'       => (int)$menu['id'],
            'title'    => $menu['titulo'],
            'url'      => $menu['url'],
            'icon'     => $menu['icono'],
            'id_padre' => $menu['id_padre'],
            'subMenu'  => []
        ];
    }

    $arbol = [];

    foreach ($items as $id => &$item) {

        if (
            !empty($item['id_padre'])
            && isset($items[$item['id_padre']])
        ) {

            $items[$item['id_padre']]['subMenu'][] = &$item;

        } else {

            $arbol[] = &$item;

        }
    }

    return $arbol;
}

function obtenerMenuUsuario(
    mysqli $conn,
    int $userId,
    int $rolId,
    int $direccionId
): array
{
    $menuIds =
        obtenerMenuIdsPermitidos(
            $conn,
            $userId,
            $rolId,
            $direccionId
        );

    $menuIds =
        obtenerMenuIdsConPadres(
            $conn,
            $menuIds
        );

    $menuIds =
        obtenerMenuIdsConHijos(
            $conn,
            $menuIds
        );

    $menusRaiz =
        obtenerMenusRaiz(
            $conn
        );

    $menuIds =
        array_unique(
            array_merge(
                $menuIds,
                $menusRaiz
            )
        );

    $menus =
        obtenerMenus(
            $conn,
            $menuIds
        );

    return construirArbolMenu(
        $menus
    );
}


