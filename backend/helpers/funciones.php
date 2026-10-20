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
    $sql = "INSERT INTO gestioninr (DIRECCION,COD_CATEGORIA,COD_SUBCATEGORIA,FECHA_REGISTRO,ANALISTA,RUC_ENTIDAD,
                RAZON_SOCIAL,GESTION,ESTADO,COMENTARIO,EST_REGISTRO,USR_CREACION,FECHA_CREACION)
            VALUES (?,?,?,NOW(),?,?,?,?,'COMPLETADA',?,1,?,NOW())";
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

/**
 * Valida una cédula ecuatoriana.
 */
function validarCedula($cedula)
{
    $cedula = preg_replace('/[^0-9]/', '', trim($cedula));
    if (strlen($cedula) != 10) {
        return false;
    }
    $provincia = intval(substr($cedula, 0, 2));
    if ($provincia < 1 || $provincia > 24) {
        return false;
    }
    $tercerDigito = intval($cedula[2]);
    if ($tercerDigito >= 6) {
        return false;
    }
    $suma = 0;
    for ($i = 0; $i < 9; $i++) {
        $valor = intval($cedula[$i]);
        if ($i % 2 == 0) {
            $valor *= 2;
            if ($valor > 9) {
                $valor -= 9;
            }
        }
        $suma += $valor;
    }
    $digitoVerificador = (10 - ($suma % 10)) % 10;
    return $digitoVerificador == intval($cedula[9]);
}

function validarRuc($ruc)
{
    $ruc = preg_replace('/[^0-9]/', '', trim($ruc));

    if (strlen($ruc) != 13) {
        return false;
    }

    $provincia = intval(substr($ruc, 0, 2));
    if ($provincia < 1 || $provincia > 24) {
        return false;
    }

    $tercerDigito = intval($ruc[2]);

    // Persona natural
    if ($tercerDigito < 6) {

        if (!validarCedula(substr($ruc, 0, 10))) {
            return false;
        }

        return substr($ruc, 10, 3) !== '000';
    }

    // Sociedad privada
    if ($tercerDigito == 9) {

        $coef = [4,3,2,7,6,5,4,3,2];
        $suma = 0;

        for ($i=0; $i<9; $i++) {
            $suma += intval($ruc[$i]) * $coef[$i];
        }

        $residuo = $suma % 11;
        $digito = ($residuo == 0) ? 0 : 11 - $residuo;

        if ($digito != intval($ruc[9])) {
            return false;
        }

        return substr($ruc, 10, 3) !== '000';
    }

    // Entidad pública
    if ($tercerDigito == 6) {

        $coef = [3,2,7,6,5,4,3,2];
        $suma = 0;

        for ($i=0; $i<8; $i++) {
            $suma += intval($ruc[$i]) * $coef[$i];
        }

        $residuo = $suma % 11;
        $digito = ($residuo == 0) ? 0 : 11 - $residuo;

        if ($digito != intval($ruc[8])) {
            return false;
        }

        return substr($ruc, 9, 4) !== '0000';
    }

    return false;
}