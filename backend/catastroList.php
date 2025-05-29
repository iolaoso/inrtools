<?php
include_once __DIR__ . '/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

function entidadesActivasSf()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT FECHA_CORTE
                ,RUC_ENTIDAD
                ,NOMBRE_CORTO AS RAZON_SOCIAL
                ,TIPO_ORGANIZACION
                ,ZONAL
                ,ESTADO_JURIDICO
                ,SEGMENTO
                ,FEC_ULT_BALANCE
                ,UPPER(NVL_RIESGO) AS NVL_RIESGO
            FROM catastrosf
            WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)
            AND ESTADO_JURIDICO= 'ACTIVA'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function entidadesSfFULL()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT FECHA_CORTE
                ,RUC_ENTIDAD
                ,NOMBRE_CORTO AS RAZON_SOCIAL
                ,TIPO_ORGANIZACION
                ,ZONAL
                ,ESTADO_JURIDICO
                ,SEGMENTO
                ,FEC_ULT_BALANCE
                ,UPPER(NVL_RIESGO) AS NVL_RIESGO
            FROM catastrosf
            WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function buscarEntidadInput($ruc)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT FECHA_CORTE,
                   RUC_ENTIDAD,
                   NOMBRE_CORTO AS RAZON_SOCIAL,
                   TIPO_ORGANIZACION,
                   ZONAL,
                   ESTADO_JURIDICO,
                   SEGMENTO,
                   FEC_ULT_BALANCE,
                   UPPER(NVL_RIESGO) AS NVL_RIESGO
            FROM catastrosf
            WHERE RUC_ENTIDAD = ?;"; // Usar '?' para preparar la consulta

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        return ['success' => false, 'message' => 'Error en la preparación de la consulta.'];
    }

    $stmt->bind_param("s", $ruc); // Vincular el RUC como parámetro
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return json_encode(['success' => true, 'entidad' => $data['RAZON_SOCIAL']]);
    } else {
        return json_encode(['success' => false, 'message' => 'No se encontró la entidad.']);
    }
}
