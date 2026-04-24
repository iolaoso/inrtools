<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos


function obtenerGestionInrPorUsuario($nickname)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT G.COD_GESTION
                ,CASE 
                    WHEN G.DIRECCION IS NULL OR G.DIRECCION = '' THEN D.DIRECCION
                    ELSE G.DIRECCION 
                END AS DIRECCION
                ,G.COD_CATEGORIA
                ,C.CATEGORIA
                ,G.COD_SUBCATEGORIA
                ,SC.SUBCATEGORIA
                ,SC.COMPLEJIDAD
                ,DATE(G.FECHA_REGISTRO) AS FECHA_REGISTRO
                ,G.GESTION
                ,G.FECHA_INICIO
                ,G.FECHA_FIN
                ,G.ESTADO
                ,G.ANALISTA
                ,G.RUC_ENTIDAD
                ,COALESCE(G.RAZON_SOCIAL,CA.NOMBRE_CORTO) AS RAZON_SOCIAL
                ,CA.SEGMENTO
                ,DATE(G.FECHA_OFIC_TRAM) AS FECHA_OFIC_TRAM
                ,G.OFICIO_TRAMITE
                ,G.COMENTARIO
            FROM GESTIONINR G
            LEFT JOIN (SELECT * 
            			FROM CATASTROSF 
            			WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)) CA ON G.RUC_ENTIDAD = CA.RUC_ENTIDAD 
            LEFT JOIN GESTIONINRCATEGORIA C ON G.COD_CATEGORIA = C.COD_CATEGORIA
            LEFT JOIN GESTIONINRSUBCATEGORIA SC ON G.COD_SUBCATEGORIA = SC.COD_SUBCATEGORIA
            LEFT JOIN INRDIRECCION D ON C.COD_DIRECCION = D.ID 
            WHERE G.EST_REGISTRO=1
            AND G.ANALISTA = ?
            ORDER BY 1 DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerGestionInrFull()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT G.COD_GESTION
                ,CASE 
                    WHEN G.DIRECCION IS NULL OR G.DIRECCION = '' THEN D.DIRECCION
                    ELSE G.DIRECCION 
                END AS DIRECCION
                ,G.COD_CATEGORIA
                ,C.CATEGORIA
                ,G.COD_SUBCATEGORIA
                ,SC.SUBCATEGORIA
                ,SC.COMPLEJIDAD
                ,DATE(G.FECHA_REGISTRO) AS FECHA_REGISTRO
                ,G.ANALISTA
                ,G.GESTION
                ,G.FECHA_INICIO
                ,G.FECHA_FIN
                ,G.ESTADO
                ,G.RUC_ENTIDAD
                ,COALESCE(G.RAZON_SOCIAL,CA.NOMBRE_CORTO) AS RAZON_SOCIAL
                ,CA.SEGMENTO
                ,DATE(G.FECHA_OFIC_TRAM) AS FECHA_OFIC_TRAM
                ,G.OFICIO_TRAMITE
                ,G.COMENTARIO
            FROM GESTIONINR G
            LEFT JOIN (SELECT * 
            			FROM CATASTROSF 
            			WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)) CA ON G.RUC_ENTIDAD = CA.RUC_ENTIDAD 
            LEFT JOIN GESTIONINRCATEGORIA C ON G.COD_CATEGORIA = C.COD_CATEGORIA
            LEFT JOIN GESTIONINRSUBCATEGORIA SC ON G.COD_SUBCATEGORIA = SC.COD_SUBCATEGORIA
            LEFT JOIN INRDIRECCION D ON C.COD_DIRECCION = D.ID 
            WHERE G.EST_REGISTRO=1
            ORDER BY 1 DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerGestionInrDireccion($dirInrId)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT G.COD_GESTION
                ,CASE 
                    WHEN G.DIRECCION IS NULL OR G.DIRECCION = '' THEN D.DIRECCION
                    ELSE G.DIRECCION 
                END AS DIRECCION
                ,G.COD_CATEGORIA
                ,C.CATEGORIA
                ,G.COD_SUBCATEGORIA
                ,SC.SUBCATEGORIA
                ,SC.COMPLEJIDAD
                ,DATE(G.FECHA_REGISTRO) AS FECHA_REGISTRO
                ,G.ANALISTA
                ,G.GESTION
                ,G.FECHA_INICIO
                ,G.FECHA_FIN
                ,G.ESTADO
                ,G.RUC_ENTIDAD
                ,COALESCE(G.RAZON_SOCIAL,CA.NOMBRE_CORTO) AS RAZON_SOCIAL
                ,CA.SEGMENTO
                ,DATE(G.FECHA_OFIC_TRAM) AS FECHA_OFIC_TRAM
                ,G.OFICIO_TRAMITE
                ,G.COMENTARIO
            FROM GESTIONINR G
            LEFT JOIN CATASTROSDB.CATASTROSF CA ON G.RUC_ENTIDAD = CA.RUC_ENTIDAD 
            LEFT JOIN GESTIONINRCATEGORIA C ON G.COD_CATEGORIA = C.COD_CATEGORIA
            LEFT JOIN GESTIONINRSUBCATEGORIA SC ON G.COD_SUBCATEGORIA = SC.COD_SUBCATEGORIA
            LEFT JOIN INRDIRECCION D ON C.COD_DIRECCION = D.ID 
            WHERE G.EST_REGISTRO=1
            AND C.COD_DIRECCION = ?
            ORDER BY 1 DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dirInrId);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerGestionInrPorId($id)
{
    global $conn; // Asegúrate de tener acceso a la conexión de la base de datos
    $stmt = $conn->prepare("SELECT G.COD_GESTION
                                ,CASE 
                                    WHEN G.DIRECCION IS NULL OR G.DIRECCION = '' THEN D.DIRECCION
                                    ELSE G.DIRECCION 
                                END AS DIRECCION
                                ,G.COD_CATEGORIA
                                ,C.CATEGORIA
                                ,G.COD_SUBCATEGORIA
                                ,SC.SUBCATEGORIA
                                ,SC.COMPLEJIDAD
                                ,DATE(G.FECHA_REGISTRO) AS FECHA_REGISTRO
                                ,G.ANALISTA
                                ,G.GESTION
                                ,G.FECHA_INICIO
                                ,G.FECHA_FIN
                                ,G.ESTADO
                                ,G.RUC_ENTIDAD
                                ,COALESCE(G.RAZON_SOCIAL,CA.NOMBRE_CORTO) AS RAZON_SOCIAL
                                ,CA.SEGMENTO
                                ,DATE(G.FECHA_OFIC_TRAM) AS FECHA_OFIC_TRAM
                                ,G.OFICIO_TRAMITE
                                ,G.COMENTARIO
                            FROM GESTIONINR G
                            LEFT JOIN (SELECT * FROM CATASTROSF WHERE FECHA_CORTE = (SELECT MAX(FECHA_CORTE) FROM catastrosf)) CA 
                            ON G.RUC_ENTIDAD = CA.RUC_ENTIDAD 
                            LEFT JOIN gestioninrcategoria C ON G.COD_CATEGORIA = C.COD_CATEGORIA
                            LEFT JOIN gestioninrsubcategoria SC ON G.COD_SUBCATEGORIA = SC.COD_SUBCATEGORIA
                            LEFT JOIN INRDIRECCION D ON C.COD_DIRECCION = D.ID
                            WHERE G.EST_REGISTRO = 1 
                            AND G.COD_GESTION = ?");

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

function obtenerCategorias($dirInrId, $rolId)
{
    global $conn; // Usar la conexión global
    // Consulta para obtener las categorías
    if ($rolId == 1) {
        $query = "SELECT COD_CATEGORIA
                    ,COD_DIRECCION
                    ,CATEGORIA 
                FROM gestioninrcategoria 
                  WHERE EST_REGISTRO = 1"; // Cambia el nombre de la tabla según tu base de datos
        $stmt = $conn->prepare($query);
        $stmt->execute();
    } else {
        $query = "SELECT COD_CATEGORIA
                    ,COD_DIRECCION
                    ,CATEGORIA 
                FROM gestioninrcategoria 
                WHERE EST_REGISTRO = 1
                AND COD_DIRECCION = ?"; // Cambia el nombre de la tabla según tu base de datos
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $dirInrId);
        $stmt->execute();
    }
    return $stmt->get_result();
}

function obtenerSubCategorias($categoriaId)
{
    global $conn; // Usar la conexión global
    // Consulta para obtener las categorías
    $query = "SELECT COD_SUBCATEGORIA 
                    ,COD_CATEGORIA
                    ,SUBCATEGORIA 
            FROM gestioninrSubCategoria 
            WHERE EST_REGISTRO = 1
            AND COD_CATEGORIA = ?;"; // Cambia el nombre de la tabla según tu base de datos
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $categoriaId);
    $stmt->execute();
    $result = $stmt->get_result();

    $subcategorias = array();
    while ($row = $result->fetch_assoc()) {
        $subcategorias[] = $row;
    }

    // Devuelve el array como JSON
    header('Content-Type: application/json');
    echo json_encode($subcategorias);
}

if (isset($_POST['categoria_id'])) {
    $categoriaId = $_POST['categoria_id'];
    obtenerSubCategorias($categoriaId);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = obtenerGestionInrPorId($id);

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
