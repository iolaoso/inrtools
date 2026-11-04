<?php

include_once __DIR__ . '/../config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

$sql="
SELECT
    a.IDENTIFICACION,
    a.NICKNAME,
    a.RUC,
    c.RAZON_SOCIAL,
    COALESCE(c.CON_CONDICION,0) as CON_CONDICION
FROM asignacion a
LEFT JOIN catastro c
    ON c.RUC = a.RUC
WHERE a.EST_REGISTRO = 1
ORDER BY a.NICKNAME,c.RAZON_SOCIAL
";

// Portafolio de Analistas
exportarExcel(
    $connEmpAux,
    $sql,
    'Portafolio_EmpAux',
    'Portafolio de Empresas por Analista'
);