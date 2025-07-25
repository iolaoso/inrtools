<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/infinr_connection.php';

// Función para obtener áreas requerientes
function obtenerAreasRequirientes()
{
    global $connInf; // Usar la conexión global
    $result = $connInf->query("SELECT DISTINCT(AREA_REQUIRIENTE) AS AREA_REQUIRIENTE
                                FROM T_TIPO_INFORME 
                                WHERE EST_REGISTRO = 'ACT';");

    $areas = [];
    while ($row = $result->fetch_assoc()) {
        $areas[] = $row;
    }

    return $areas;
}

// Manejo de la solicitud
echo json_encode(obtenerAreasRequirientes());