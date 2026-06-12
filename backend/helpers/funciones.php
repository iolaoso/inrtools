<?php

/**
 * Escapa texto para mostrar en HTML
 */
function h($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Normaliza nombres para columnas dinámicas
 */
function normalizarNombreColumna($texto): string
{
    $texto = strtoupper($texto);
    $texto = preg_replace('/[^A-Z0-9]/', '_', $texto);
    $texto = preg_replace('/_+/', '_', $texto);

    return trim($texto, '_');
}

/**
 * Registrar solicitud/reporte en tabla estructuras
 */
function registrarEstructura(
    mysqli $conn,
    string $ruc,
    string $estructura,
    string $fechaCorte,
    string $detalle = ''
): bool {

    global $nickname;
    global $direccion;

    $sql = "
        INSERT INTO estructuras (
            solicitante,
            direccion_solicitante,
            ruc,
            estructura,
            fechaCorte,
            fecha_solicitud,
            estado,
            detalle,
            estRegistro,
            UsrCreacion,
            createdAt,
            updatedAt
        )
        VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            NOW(),
            'GENERADA',
            ?,
            1,
            ?,
            NOW(),
            NOW()
        )
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        "sssssss",
        $nickname,
        $direccion,
        $ruc,
        $estructura,
        $fechaCorte,
        $detalle,
        $nickname
    );

    return $stmt->execute();
}

/**
 * Registrar gestión en INR
 */
function registrarGestionINR(
    mysqli $conn,
    string $direccion,
    string $categoria,
    string $subcategoria,
    string $ruc,
    string $razonSocial,
    string $gestion,
    string $comentario = ''
): bool {

    global $nickname;

    $sql = "
        INSERT INTO gestioninr (
            DIRECCION,
            COD_CATEGORIA,
            COD_SUBCATEGORIA,
            FECHA_REGISTRO,
            ANALISTA,
            RUC_ENTIDAD,
            RAZON_SOCIAL,
            GESTION,
            ESTADO,
            COMENTARIO,
            EST_REGISTRO,
            USR_CREACION,
            FECHA_CREACION
        )
        VALUES (
            ?,
            ?,
            ?,
            NOW(),
            ?,
            ?,
            ?,
            ?,
            'COMPLETADA',
            ?,
            1,
            ?,
            NOW()
        )
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        "sssssssss",
        $direccion,
        $categoria,
        $subcategoria,
        $nickname,
        $ruc,
        $razonSocial,
        $gestion,
        $comentario,
        $nickname
    );

    return $stmt->execute();
}