<?php

include_once __DIR__ . '/../config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

$sql="
select p.ID_PROCESO_DNR
	,p.ID_ENTIDAD
	,p.RUC
	,c.RAZON_SOCIAL
	,p.OFICIO_COMUNICACION_RES
	,p.FECHA_COMUNICACION_RES
	,p.FECHA_RESOLUCION
	,p.NUM_PERIODO
	,p.FECHA_CORTE
	,p.OBSERVACION_DNR
	,p.FECHA_REGULARIZACION
	,p.FECHA_PRORROGA
	,p.EST_REGISTRO
	,p.USR_CREACION
	,p.CREATED_AT
	,p.UPDATED_AT
	,p.DELETED_AT
from proceso_dnr p
INNER JOIN catastro c
    ON p.ID_ENTIDAD = c.ID
WHERE p.EST_REGISTRO = 1
";

// Procesos DNR
exportarExcel(
    $connEmpAux,
    $sql,
    'Procesos_DNR',
    'Reporte de Procesos DNR'
);