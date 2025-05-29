<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos


function obtenerInformesInrPorUsuario($nickname)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT I.COD_INFORME
                ,I.RUC_ENTIDAD
                ,CSF.RAZON_SOCIAL
                ,I.COD_TIPO_INFORME
                ,TI.TIPO_INFORME
                ,TI.AREA_REQUIRIENTE
                ,I.FECHA_ASIGNACION
                ,I.FECHA_SOLICITUD_REVISION
                ,I.COD_ESTADO
                ,CAT.NEMONICO
                ,I.NUM_INFORME
                ,I.FECHA_INFORME
                ,I.NUM_MEMORANDO
                ,I.FECHA_MEMORANDO
                ,I.FECHA_CARGA_COMPARTIDA
                ,I.OBSERVACIONES
                ,I.LINEA_BASE
                ,I.deletedAt
                ,I.EST_REGISTRO
                ,I.USR_CREACION
                ,I.FECHA_CREACION
                ,I.FECHA_ACTUALIZACION
            FROM informesinr I
            LEFT JOIN informesinrcatalogo CAT ON I.COD_ESTADO = CAT.COD_CATALOGO
            LEFT JOIN informesinrtipoinforme TI ON I.COD_TIPO_INFORME = TI.COD_TIPO_INF
            LEFT JOIN (SELECT * 
                        FROM CATASTROSF 
                        WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)) CSF ON I.RUC_ENTIDAD = CSF.RUC_ENTIDAD 
            WHERE I.EST_REGISTRO =1 AND I.USR_CREACION = ?
            ORDER BY I.FECHA_CREACION DESC, CAT.NEMONICO DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerInformesInrFull()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT I.COD_INFORME
                ,I.RUC_ENTIDAD
                ,CSF.RAZON_SOCIAL
                ,I.COD_TIPO_INFORME
                ,TI.TIPO_INFORME
                ,TI.AREA_REQUIRIENTE
                ,I.FECHA_ASIGNACION
                ,I.FECHA_SOLICITUD_REVISION
                ,I.COD_ESTADO
                ,CAT.NEMONICO
                ,I.NUM_INFORME
                ,I.FECHA_INFORME
                ,I.NUM_MEMORANDO
                ,I.FECHA_MEMORANDO
                ,I.FECHA_CARGA_COMPARTIDA
                ,I.OBSERVACIONES
                ,I.LINEA_BASE
                ,I.deletedAt
                ,I.EST_REGISTRO
                ,I.USR_CREACION
                ,I.FECHA_CREACION
                ,I.FECHA_ACTUALIZACION
            FROM informesinr I
            LEFT JOIN informesinrcatalogo CAT ON I.COD_ESTADO = CAT.COD_CATALOGO
            LEFT JOIN informesinrtipoinforme TI ON I.COD_TIPO_INFORME = TI.COD_TIPO_INF
            LEFT JOIN (SELECT * 
                        FROM CATASTROSF 
                        WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)) CSF ON I.RUC_ENTIDAD = CSF.RUC_ENTIDAD 
            WHERE I.EST_REGISTRO =1
            ORDER BY I.FECHA_CREACION DESC, CAT.NEMONICO DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerInformesInrPorId($id)
{
    global $conn; // Asegúrate de tener acceso a la conexión de la base de datos
    $stmt = $conn->prepare("SELECT I.COD_INFORME
                ,I.RUC_ENTIDAD
                ,CSF.RAZON_SOCIAL
                ,I.COD_TIPO_INFORME
                ,TI.TIPO_INFORME
                ,TI.AREA_REQUIRIENTE
                ,DATE(I.FECHA_ASIGNACION) AS FECHA_ASIGNACION
                ,DATE(I.FECHA_SOLICITUD_REVISION) AS FECHA_SOLICITUD_REVISION
                ,I.COD_ESTADO
                ,CAT.NEMONICO AS ESTADO
                ,I.NUM_INFORME
                ,DATE(I.FECHA_INFORME) AS FECHA_INFORME
                ,I.NUM_MEMORANDO
                ,DATE(I.FECHA_MEMORANDO) AS FECHA_MEMORANDO
                ,DATE(I.FECHA_CARGA_COMPARTIDA) AS FEC_CARGA_COMP
                ,I.OBSERVACIONES
                ,I.LINEA_BASE
                ,I.deletedAt
                ,I.EST_REGISTRO
                ,I.USR_CREACION AS ANALISTA
                ,I.FECHA_CREACION
                ,I.FECHA_ACTUALIZACION
            FROM informesinr I
            LEFT JOIN informesinrcatalogo CAT ON I.COD_ESTADO = CAT.COD_CATALOGO
            LEFT JOIN informesinrtipoinforme TI ON I.COD_TIPO_INFORME = TI.COD_TIPO_INF
            LEFT JOIN (SELECT * 
                        FROM CATASTROSF 
                        WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)) CSF ON I.RUC_ENTIDAD = CSF.RUC_ENTIDAD 
            WHERE I.EST_REGISTRO =1 AND I.COD_INFORME = ?
            ORDER BY I.FECHA_CREACION DESC, CAT.NEMONICO DESC");

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

function obtenerTiposInforme()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT COD_TIPO_INF
                ,TIPO_INFORME
                ,AREA_REQUIRIENTE
            FROM informesinrtipoinforme
            WHERE EST_REGISTRO = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = obtenerInformesInrPorId($id);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No se encontraron datos']);
    }
} else {
    //echo json_encode(['error' => 'ID no proporcionado']);
}

if (isset($_GET['ruc'])) {
    $ruc = $_GET['ruc'];
    echo buscarEntidadInput($ruc);
    exit; // Asegúrate de detener la ejecución aquí
}