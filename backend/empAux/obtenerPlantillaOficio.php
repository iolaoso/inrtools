<?php
// obtenerPlantillaOficio.php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';


$tipoOficioId=$_GET['tipoOficioId'] ?? 0;

$sql="
    SELECT CONTENIDO
    FROM cat_plantilla_oficio
    WHERE ID_TIPO_OFICIO=?
    AND EST_REGISTRO=1
";

$stmt=$connEmpAux->prepare($sql);
$stmt->bind_param("i",$tipoOficioId);
$stmt->execute();

$row=$stmt->get_result()->fetch_assoc();

echo json_encode([
    'success'=>true,
    'tipoOficioId' => $tipoOficioId,
    'plantilla'=>$row['CONTENIDO']??''
]);