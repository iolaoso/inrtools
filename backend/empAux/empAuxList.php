<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php'; // Asegúrate de incluir la conexión a la base de datos


function obtenerCatastroEmpAux()
{
    global $connEmpAux; // Usar la conexión global
    $sql = "select ID
                ,RUC
                ,RAZON_SOCIAL
                ,TIPO_ORGANIZACION
                ,NUM_RESOLUCION_CALIFICACION
                ,FECHA_RESOLUCION
                ,CONCAT_WS(', ',
                    CASE WHEN SOFTWARE_FINANCIERO_Y_COMPUTACIONAL = 1
                        THEN 'Software Financiero y Computacional' END,
                    CASE WHEN TRANSACCIONALES_Y_DE_PAGO = 1
                        THEN 'Transaccionales y de Pago' END,
                    CASE WHEN TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES = 1
                        THEN 'Transporte de Especies Monetarias y Valores' END,
                    CASE WHEN RED_Y_CAJEROS_AUTOMATICOS = 1
                        THEN 'Red y Cajeros Automáticos' END,
                    CASE WHEN COBRANZAS = 1
                        THEN 'Cobranzas' END,
                    CASE WHEN SERVICIOS_CONTABLES = 1
                        THEN 'Servicios Contables' END,
                    CASE WHEN GENERADORAS_DE_CARTERA = 1
                        THEN 'Generadoras de Cartera' END,
                    CASE WHEN ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS = 1
                        THEN 'Administradoras y Operadoras de Tarjetas' END,
                    CASE WHEN GIRO_INMOBILIARIO = 1
                        THEN 'Giro Inmobiliario' END
                ) AS SERVICIO_PRESTADO
                ,REPRESENTANTE_LEGAL
                ,CORREO_ELECTRONICO
                ,CASE WHEN NUEVA = 1
                    THEN 'SI' ELSE 'NO' END AS NUEVA
                , CASE WHEN CON_CONDICION = 1
                    THEN 'SI' ELSE 'NO' END AS CON_CONDICION
            from catastro 
            where EST_REGISTRO = 1
            order by RUC;";
    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerCoopEmpAux()
{
    global $connEmpAux; // Usar la conexión global
    $sql = "select e.ID
                ,e.RUC_COOP
                ,e.SEGMENTO
                ,e.NUM_EMP_AUX
                ,e.RUC_EMPRESA
                ,c.ruc as RUC_EMP_CATASTRO
                ,e.RAZON_SOCIAL_EMPRESA
                ,c.razon_social as RZ_EMP_CATASTRO
            from empaux_coops e
            left join (select ruc,razon_social from catastro) c
                on e.RUC_EMPRESA = c.ruc 
            where e.EST_REGISTRO = 1;";
    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerFormEmpAux()
{
    global $connEmpAux; // Usar la conexión global
    $sql = "select ID
                ,FECHA_CORTE
                ,RUC
                ,RAZON_SOCIAL
                ,NUMERO_PERIODO
                ,case when  LINEA_BASE = 1
                    then 'SI' else 'NO' end as LINEA_BASE
                ,case when VALIDACION_SERVICIOS = 1
                    then 'SI' else 'NO' end as VALIDACION_SERVICIOS
                ,ACTIVO
                ,PASIVO
                ,PATRIMONIO_NETO
                ,RESULTADOS_DEL_EJERCICIO
                ,INGRESOS_DE_ACTIVIDADES_ORDINARIAS
                ,GASTOS
                ,COALESCE(SEGMENTO_1,0) + COALESCE(SEGMENTO_2,0) + COALESCE(SEGMENTO_3,0) + COALESCE(SEGMENTO_4,0) + COALESCE(SEGMENTO_5,0) AS NUM_ENTIDADES
            from vw_full_entidad_data;";    
    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}


/* function obtenerEmpEEFFPorUsuario($nickname)
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
} */

/* if (isset($_POST['categoria_id'])) {
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
} */
