<?php

include_once __DIR__ . '/../config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';

$sql="
select s.RUC
    ,c.RAZON_SOCIAL
    ,s.ID_SEGUIMIENTO
	,s.ID_ENTIDAD
	,s.PERIODO
	,s.FECHA_CORTE
	,s.NUMERO_SEGUIMIENTO
	,s.OFICIO_SEGUIMIENTO
	,s.ID_TIPO_ULTIMO_OFICIO_ENVIADO
	,s.FECHA_OF_SEGUIMIENTO
	,s.ID_ESTADO_CUMPLIMIENTO
	,ecc.DESCRIPCION as ESTADO_CUMPLIMIENTO
	,s.ID_MOTIVO_INCUMPLIMIENTO
	,cmi.DESCRIPCION as MOTIVO_INCUMPLIMIENTO  
	,s.ID_ESTADO_SEGUIMIENTO
	,ecs.DESCRIPCION AS ESTADO_SEGUIMIENTO
	,s.IND_REGULARIZADO
	,s.FECHA_REUNION
	,s.NUM_ACTA_REUNION
	,s.FECHA_INFORME_SEG
	,s.NUM_INFORME_SEG
	,s.OBSERVACION_DNS
	,s.EST_REGISTRO
	,s.USR_CREACION
FROM  seguimiento s
INNER JOIN catastro c
    ON s.ID_ENTIDAD = c.ID
LEFT JOIN cat_estado_cumplimiento ecc
    ON ecc.ID = s.ID_ESTADO_CUMPLIMIENTO
LEFT JOIN cat_estado_seguimiento ecs
    ON ecs.ID = s.ID_ESTADO_SEGUIMIENTO
LEFT JOIN cat_motivo_incumplimiento cmi 
    ON cmi.ID = s.ID_MOTIVO_INCUMPLIMIENTO 	
ORDER BY s.CREATED_AT DESC";

// Seguimientos
exportarExcel(
    $connEmpAux,
    $sql,
    'Seguimientos_EmpAux',
    'Reporte de Seguimientos'
);