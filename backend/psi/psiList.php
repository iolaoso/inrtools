<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/psidb_connection.php';

function obtenerPsiFull()
{
    global $connPsi; // Usar la conexión global
    $sql = "SELECT SELECT id
                        ,NUMERO
                        ,COD_UNICO
                        ,RUC
                        ,RAZON_SOCIAL
                        ,SEGMENTO
                        ,ZONAL
                        ,ESTADO_JURIDICO
                        ,TIPO_SUPERVISION
                        ,DATE(FECHA_INICIO) AS FECHA_INICIO
                        ,DATE(FECHA_FIN) AS FECHA_FIN
                        ,ANIO_INICIO
                        ,MES_INICIO
                        ,ANIO_VENCIMIENTO
                        ,MES_VENCIMIENTO
                        ,TRIMESTRE
                        ,ESTADO_PSI
                        ,VIGENCIA_PSI
                        ,DATE(FECHA_APROBACION_PLAN_FISICO) AS FECHA_APROBACION_PLAN_FISICO
                        ,NUM_INFORME
                        ,DATE(FECHA_INFORME) AS FECHA_INFORME
                        ,NUM_RESOLUCION
                        ,DATE(FECHA_RESOLUCION) AS FECHA_RESOLUCION
                        ,NUM_RESOLUCION_AMPLIACION
                        ,DATE(FECHA_RESOLUCION_AMPLIACION) AS FECHA_RESOLUCION_AMPLIACION
                        ,DATE(FECHA_ULTIMO_BALANCE) AS FECHA_ULTIMO_BALANCE
                        ,ACTIVOS
                        ,ULTIMO_RIESGO
                        ,NUM_RESOLUCION_FIN_PSI
                        ,DATE(FECHA_RESOLUCION_FIN_PSI) AS FECHA_RESOLUCION_FIN_PSI
                        ,MOTIVO_CIERRE
                        ,ESTRATEGIA_SUPERVISION
                        ,DATE(FECHA_CORTE_INFORMACION) AS FECHA_CORTE_INFORMACION
                        ,ULTIMO_CORTE
                        ,EST_REGISTRO
                        ,USR_CREACION
                        ,FECHA_CREACION
                        ,FECHA_ACTUALIZACION
                        ,DELETED_AT
            FROM PSI WHERE EST_REGISTRO = 'ACT'";
    $stmt = $connPsi->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerPsiPorId($id)
{
    global $connPsi; // Asegúrate de tener acceso a la conexión de la base de datos
    $stmt = $connPsi->prepare("SELECT id
                                ,NUMERO
                                ,COD_UNICO
                                ,RUC
                                ,RAZON_SOCIAL
                                ,SEGMENTO
                                ,ZONAL
                                ,ESTADO_JURIDICO
                                ,TIPO_SUPERVISION
                                ,DATE(FECHA_INICIO) AS FECHA_INICIO
                                ,DATE(FECHA_FIN) AS FECHA_FIN
                                ,ANIO_INICIO
                                ,MES_INICIO
                                ,ANIO_VENCIMIENTO
                                ,MES_VENCIMIENTO
                                ,TRIMESTRE
                                ,ESTADO_PSI
                                ,VIGENCIA_PSI
                                ,DATE(FECHA_APROBACION_PLAN_FISICO) AS FECHA_APROBACION_PLAN_FISICO
                                ,NUM_INFORME
                                ,DATE(FECHA_INFORME) AS FECHA_INFORME
                                ,NUM_RESOLUCION
                                ,DATE(FECHA_RESOLUCION) AS FECHA_RESOLUCION
                                ,NUM_RESOLUCION_AMPLIACION
                                ,DATE(FECHA_RESOLUCION_AMPLIACION) AS FECHA_RESOLUCION_AMPLIACION
                                ,DATE(FECHA_ULTIMO_BALANCE) AS FECHA_ULTIMO_BALANCE
                                ,ACTIVOS
                                ,ULTIMO_RIESGO
                                ,NUM_RESOLUCION_FIN_PSI
                                ,DATE(FECHA_RESOLUCION_FIN_PSI) AS FECHA_RESOLUCION_FIN_PSI
                                ,MOTIVO_CIERRE
                                ,ESTRATEGIA_SUPERVISION
                                ,DATE(FECHA_CORTE_INFORMACION) AS FECHA_CORTE_INFORMACION
                                ,ULTIMO_CORTE
                                ,EST_REGISTRO
                                ,USR_CREACION
                                ,FECHA_CREACION
                                ,FECHA_ACTUALIZACION
                                ,DELETED_AT
                            FROM PSI WHERE EST_REGISTRO = 'ACT' AND id = ?");


    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Extraer el registro como un array asociativo
        if ($estructuraData = $result->fetch_assoc()) {
            return $estructuraData; // Devuelve el array asociativo
        }
    }

    return null; // Devuelve null si no hay resultados o si hubo un error
}


/**
 * Obtener registros activos con ULTIMO_CORTE = 2
 * @return array
 */
function obtenerPsiActivos($nickname = null, $rol = null)
{
    global $connPsi;

    if (($rol == 'SUPERUSER' || $rol == 'ADMININISTRADOR')) {
        // Si es SUPERUSER, filtrar por usuario
        $sql = "SELECT id
                    ,NUMERO
                    ,COD_UNICO
                    ,RUC
                    ,RAZON_SOCIAL
                    ,SEGMENTO
                    ,ZONAL
                    ,ESTADO_JURIDICO
                    ,TIPO_SUPERVISION
                    ,FECHA_INICIO
                    ,FECHA_FIN
                    ,ANIO_INICIO
                    ,MES_INICIO
                    ,ANIO_VENCIMIENTO
                    ,MES_VENCIMIENTO
                    ,TRIMESTRE
                    ,ESTADO_PSI
                    ,VIGENCIA_PSI
                    ,FECHA_APROBACION_PLAN_FISICO
                    ,NUM_INFORME
                    ,FECHA_INFORME
                    ,NUM_RESOLUCION
                    ,FECHA_RESOLUCION
                    ,NUM_RESOLUCION_AMPLIACION
                    ,FECHA_RESOLUCION_AMPLIACION
                    ,FECHA_ULTIMO_BALANCE
                    ,ACTIVOS
                    ,ULTIMO_RIESGO
                    ,NUM_RESOLUCION_FIN_PSI
                    ,FECHA_RESOLUCION_FIN_PSI
                    ,MOTIVO_CIERRE
                    ,ESTRATEGIA_SUPERVISION
                    ,FECHA_CORTE_INFORMACION
                    ,ULTIMO_CORTE
                    ,EST_REGISTRO
                    ,USR_CREACION
                    ,FECHA_CREACION
                    ,FECHA_ACTUALIZACION
                FROM PSI 
                WHERE EST_REGISTRO = 'ACT' 
                AND ULTIMO_CORTE = 2
                ORDER BY id DESC";
        $stmt = $connPsi->prepare($sql);
    } else {
        // Si es un usuario normal, filtrar por usuario
        $sql = "SELECT id
                    ,NUMERO
                    ,COD_UNICO
                    ,RUC
                    ,RAZON_SOCIAL
                    ,SEGMENTO
                    ,ZONAL
                    ,ESTADO_JURIDICO
                    ,TIPO_SUPERVISION
                    ,FECHA_INICIO
                    ,FECHA_FIN
                    ,ANIO_INICIO
                    ,MES_INICIO
                    ,ANIO_VENCIMIENTO
                    ,MES_VENCIMIENTO
                    ,TRIMESTRE
                    ,ESTADO_PSI
                    ,VIGENCIA_PSI
                    ,FECHA_APROBACION_PLAN_FISICO
                    ,NUM_INFORME
                    ,FECHA_INFORME
                    ,NUM_RESOLUCION
                    ,FECHA_RESOLUCION
                    ,NUM_RESOLUCION_AMPLIACION
                    ,FECHA_RESOLUCION_AMPLIACION
                    ,FECHA_ULTIMO_BALANCE
                    ,ACTIVOS
                    ,ULTIMO_RIESGO
                    ,NUM_RESOLUCION_FIN_PSI
                    ,FECHA_RESOLUCION_FIN_PSI
                    ,MOTIVO_CIERRE
                    ,ESTRATEGIA_SUPERVISION
                    ,FECHA_CORTE_INFORMACION
                    ,ULTIMO_CORTE
                    ,EST_REGISTRO
                    ,USR_CREACION
                    ,FECHA_CREACION
                FROM PSI 
                WHERE EST_REGISTRO = 'ACT' 
                AND ULTIMO_CORTE = 2 
                AND VIGENCIA_PSI != 'PENDIENTE' 
                ORDER BY id DESC";

        $stmt = $connPsi->prepare($sql);
    }

    $result = $connPsi->query($sql);
    $datos = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
    }
    return $datos;
}

/**
 * Insertar nuevo registro en PSI
 * Recibe arreglo asociativo con campos (excepto id)
 */
function insertarPsi($data)
{
    global $connPsi;

    $fields = [];
    $placeholders = [];
    $values = [];
    $types = '';

    foreach ($data as $key => $value) {
        $fields[] = $key;
        $placeholders[] = '?';

        // Determinar tipo para bind_param
        if (is_int($value)) $types .= 'i';
        elseif (is_double($value) || is_float($value)) $types .= 'd';
        else $types .= 's';

        $values[] = $value;
    }

    $sql = "INSERT INTO PSI (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
    $stmt = $connPsi->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param($types, ...$values);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

/**
 * Actualizar registro PSI por id
 * Recibe id y arreglo asociativo con campos a actualizar
 */
function actualizarPsi($id, $data)
{
    global $connPsi;

    $sets = [];
    $values = [];
    $types = '';

    foreach ($data as $key => $value) {
        $sets[] = "$key = ?";
        if (is_int($value)) $types .= 'i';
        elseif (is_double($value) || is_float($value)) $types .= 'd';
        else $types .= 's';
        $values[] = $value;
    }

    $sql = "UPDATE PSI SET " . implode(',', $sets) . " WHERE id = ?";
    $types .= 'i';
    $values[] = $id;

    $stmt = $connPsi->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param($types, ...$values);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}

/**
 * Marcar registro como eliminado (soft delete)
 */
function eliminarPsi($id)
{
    global $connPsi;

    $sql = "UPDATE PSI SET EST_REGISTRO = 'DEL', DELETED_AT = NOW() WHERE id = ?";
    $stmt = $connPsi->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param('i', $id);
    $res = $stmt->execute();
    $stmt->close();
    return $res;
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = obtenerPsiPorId($id);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No se encontraron datos']);
    }
} else {
    //echo json_encode(['error' => 'ID no proporcionado']);
}