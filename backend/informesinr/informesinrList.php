<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/infinr_connection.php';


function obtInformesInrUsr($nickname)
{
    global $connInf; // Usar la conexión global
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
                ,I.EST_REGISTRO
                ,I.USR_CREACION
                ,I.FECHA_CREACION
                ,I.FECHA_ACTUALIZACION
            FROM T_INFORMES I
            LEFT JOIN T_CATALOGO CAT ON I.COD_ESTADO = CAT.COD_CATALOGO
            LEFT JOIN T_TIPO_INFORME TI ON I.COD_TIPO_INFORME = TI.COD_TIPO_INF
            LEFT JOIN (SELECT * 
                        FROM T_CATASTRO 
                        WHERE CORTE_INFORMACION = (SELECT MAX(CORTE_INFORMACION) FROM T_CATASTRO)) CSF ON I.RUC_ENTIDAD = CSF.RUC_ENTIDAD 
            WHERE I.EST_REGISTRO ='ACT' AND I.USR_CREACION = ?
            ORDER BY I.FECHA_CREACION DESC, CAT.NEMONICO DESC";
    $stmt = $connInf->prepare($sql);
    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerInformesInrFull()
{
    global $connInf; // Usar la conexión global
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
                ,I.EST_REGISTRO
                ,I.USR_CREACION
                ,I.FECHA_CREACION
                ,I.FECHA_ACTUALIZACION
            FROM T_INFORMES I
            LEFT JOIN T_CATALOGO CAT ON I.COD_ESTADO = CAT.COD_CATALOGO
            LEFT JOIN T_TIPO_INFORME TI ON I.COD_TIPO_INFORME = TI.COD_TIPO_INF
            LEFT JOIN (SELECT * 
                        FROM T_CATASTRO 
                        WHERE CORTE_INFORMACION = (SELECT MAX(CORTE_INFORMACION) FROM T_CATASTRO)) CSF ON I.RUC_ENTIDAD = CSF.RUC_ENTIDAD 
            WHERE I.EST_REGISTRO ='ACT'
            ORDER BY I.FECHA_CREACION DESC, CAT.NEMONICO DESC";
    $stmt = $connInf->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerInformesInrId($id)
{
    global $connInf; // Asegúrate de tener acceso a la conexión de la base de datos
    $stmt = $connInf->prepare("SELECT I.COD_INFORME
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
                ,I.EST_REGISTRO
                ,I.USR_CREACION AS ANALISTA
                ,I.FECHA_CREACION
                ,I.FECHA_ACTUALIZACION
            FROM T_INFORMES I
            LEFT JOIN T_CATALOGO CAT ON I.COD_ESTADO = CAT.COD_CATALOGO
            LEFT JOIN T_TIPO_INFORME TI ON I.COD_TIPO_INFORME = TI.COD_TIPO_INF
            LEFT JOIN (SELECT * 
                        FROM T_CATASTRO 
                        WHERE CORTE_INFORMACION = (SELECT MAX(CORTE_INFORMACION) FROM T_CATASTRO)) CSF ON I.RUC_ENTIDAD = CSF.RUC_ENTIDAD 
            WHERE I.EST_REGISTRO = 'ACT' AND I.COD_INFORME = ?
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
    global $connInf; // Usar la conexión global
    $sql = "SELECT COD_TIPO_INF
                ,TIPO_INFORME
                ,AREA_REQUIRIENTE
            FROM T_TIPO_INFORME
            WHERE EST_REGISTRO = 'ACT'";
    $stmt = $connInf->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = obtenerInformesInrId($id);

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
