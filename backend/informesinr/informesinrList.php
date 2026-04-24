<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/infinr_connection.php';


function obtInformesInrUsr($nickname)
{
    global $connInf; // Usar la conexión global
    $sql = "SELECT *
            FROM vi_allinformes 
            WHERE EST_REGISTRO ='ACT' AND ANALISTA = ?
            ORDER BY FECHA_CREACION DESC, ESTADO DESC";
    $stmt = $connInf->prepare($sql);
    $stmt->bind_param('s', $nickname);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerInformesInrFull()
{
    global $connInf; // Usar la conexión global
    $sql = "SELECT *
            FROM vi_allinformes 
            WHERE EST_REGISTRO ='ACT'
            ORDER BY FECHA_CREACION DESC, ESTADO DESC";
    $stmt = $connInf->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerInformesInrId($id)
{
    global $connInf; // Asegúrate de tener acceso a la conexión de la base de datos
    $stmt = $connInf->prepare("SELECT *
            FROM vi_allinformes 
            WHERE EST_REGISTRO = 'ACT' AND COD_INFORME = ?
            ORDER BY FECHA_CREACION DESC, ESTADO DESC");

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

function obtenerAreasRequirientes()
{
    global $connInf; // Usar la conexión global global $connInf; // Usar la conexión global
    $sql = "SELECT DISTINCT(AREA_REQUIRIENTE) AS AREA_REQUIRIENTE
            FROM T_TIPO_INFORME 
            WHERE EST_REGISTRO = 'ACT';";
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
