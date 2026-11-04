<?php

include_once __DIR__ . '/../config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

$sql="
    SELECT
        c.RUC,
        c.RAZON_SOCIAL,
        s.NUMERO_SEGUIMIENTO,
        s.OFICIO_SEGUIMIENTO,
        ecc.DESCRIPCION ESTADO_CUMPLIMIENTO
    FROM seguimiento s
    INNER JOIN catastro c
        ON s.ID_ENTIDAD = c.ID
    LEFT JOIN cat_estado_cumplimiento ecc
        ON ecc.ID = s.ID_ESTADO_CUMPLIMIENTO
    WHERE ecc.CODIGO='INCU';
";

// Incumplimientos
exportarExcel(
    $connEmpAux,
    $sql,
    'Incumplimientos_EmpAux',
    'Reporte de Incumplimientos'
);