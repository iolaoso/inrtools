<?php

header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . '/../config.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';


try {

    // =========================================================
    // VALIDAR CONEXIÓN
    // =========================================================

    if ($conn->connect_error) {

        throw new Exception(
            'Conexión fallida: ' . $conn->connect_error
        );

    }


    // =========================================================
    // RECIBIR FILTROS
    // =========================================================

    $fechaDesde = trim($_GET['fechaDesde'] ?? '');
    $fechaHasta = trim($_GET['fechaHasta'] ?? '');
    $usuario    = trim($_GET['usuario'] ?? '');
    $accion     = trim($_GET['accion'] ?? '');
    $modulo     = trim($_GET['modulo'] ?? '');


    // =========================================================
    // CONSTRUIR FILTROS
    // =========================================================

    $where = [];
    $params = [];
    $types = '';


    // Fecha desde
    if ($fechaDesde !== '') {

        $where[] = 'fecha_evento >= ?';

        $params[] = $fechaDesde . ' 00:00:00';

        $types .= 's';

    }


    // Fecha hasta
    if ($fechaHasta !== '') {

        $where[] = 'fecha_evento <= ?';

        $params[] = $fechaHasta . ' 23:59:59';

        $types .= 's';

    }


    // Usuario
    if ($usuario !== '') {

        $where[] = '
            (
                nickname LIKE ?
                OR correo LIKE ?
            )
        ';

        $valorUsuario = '%' . $usuario . '%';

        $params[] = $valorUsuario;
        $params[] = $valorUsuario;

        $types .= 'ss';

    }


    // Acción
    if ($accion !== '') {

        $where[] = 'accion = ?';

        $params[] = $accion;

        $types .= 's';

    }


    // Módulo
    if ($modulo !== '') {

        $where[] = 'modulo LIKE ?';

        $params[] = '%' . $modulo . '%';

        $types .= 's';

    }


    // =========================================================
    // WHERE
    // =========================================================

    $whereSQL = '';

    if (!empty($where)) {

        $whereSQL =
            ' WHERE ' . implode(' AND ', $where);

    }


    // =========================================================
    // CONSULTA PRINCIPAL
    // =========================================================

    $sql = "
        SELECT
            id,
            request_id,
            usuario_id,
            nickname,
            correo,
            direccion,
            rol,
            accion,
            modulo,
            descripcion,
            nombre_archivo,
            ruta_archivo,
            tipo_archivo,
            tamanio_archivo,
            ip,
            user_agent,
            host_cliente,
            session_id,
            estado,
            mensaje,
            datos_extra,
            fecha_evento,
            createdAt

        FROM bitacora_usuarios

        $whereSQL

        ORDER BY fecha_evento DESC

        LIMIT 500
    ";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        throw new Exception(
            'Error preparando consulta: ' .
            $conn->error
        );

    }


    // =========================================================
    // ASIGNAR PARÁMETROS
    // =========================================================

    if (!empty($params)) {

        $stmt->bind_param(
            $types,
            ...$params
        );

    }


    // =========================================================
    // EJECUTAR
    // =========================================================

    $stmt->execute();


    $resultado = $stmt->get_result();


    $registros = [];


    while ($fila = $resultado->fetch_assoc()) {

        $registros[] = $fila;

    }


    $stmt->close();


    // =========================================================
    // KPI
    // =========================================================

    $sqlKpi = "
        SELECT

            COUNT(*) AS total,

            SUM(
                CASE
                    WHEN estado = 'OK'
                    THEN 1
                    ELSE 0
                END
            ) AS ok,

            SUM(
                CASE
                    WHEN accion = 'DESCARGA'
                    THEN 1
                    ELSE 0
                END
            ) AS descargas,

            SUM(
                CASE
                    WHEN estado <> 'OK'
                    THEN 1
                    ELSE 0
                END
            ) AS errores

        FROM bitacora_usuarios

        $whereSQL
    ";


    $stmtKpi = $conn->prepare($sqlKpi);


    if (!$stmtKpi) {

        throw new Exception(
            'Error preparando consulta KPI: ' .
            $conn->error
        );

    }


    if (!empty($params)) {

        $stmtKpi->bind_param(
            $types,
            ...$params
        );

    }


    $stmtKpi->execute();


    $resultadoKpi =
        $stmtKpi->get_result();


    $kpi =
        $resultadoKpi->fetch_assoc();


    $stmtKpi->close();


    // =========================================================
    // RESPUESTA
    // =========================================================

    echo json_encode([

        'success' => true,

        'data' => $registros,

        'kpi' => [

            'total' =>
                (int)($kpi['total'] ?? 0),

            'ok' =>
                (int)($kpi['ok'] ?? 0),

            'descargas' =>
                (int)($kpi['descargas'] ?? 0),

            'errores' =>
                (int)($kpi['errores'] ?? 0)

        ]

    ], JSON_UNESCAPED_UNICODE);


} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>
            $e->getMessage()

    ], JSON_UNESCAPED_UNICODE);

}
