<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

// Función para obtener las estrategias según la dirección y el rol del usuario
function obtenerEstrategias($dirInrId, $rolId) {
    global $conn; // Usar la conexión global
    // Consulta para obtener las categorías
    if ($rolId == 1) {
        $query = "SELECT ID
                        ,ESTRATEGIA
                  FROM as_catalogo_supervision
                  WHERE EST_REGISTRO = 'ACT'
                  GROUP BY ESTRATEGIA
                  ORDER BY ID;"; // Cambia el nombre de la tabla según tu base de datos
        $stmt = $conn->prepare($query);
        $stmt->execute();
    } else {
        $query = "SELECT ID
                    ,ESTRATEGIA
                  FROM as_catalogo_supervision
                  WHERE EST_REGISTRO = 'ACT'
                  AND DIRECCION=?
                  GROUP BY ESTRATEGIA
                  ORDER BY ID;"; // Cambia el nombre de la tabla según tu base de datos
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $dirInrId);
        $stmt->execute();
    }
    return $stmt->get_result();
}

//FUNCIÓN PARA OBTENER FASES
function obtenerFases() {
    global $conn; // Usar la conexión global
    // Consulta para obtener las categorías
    $query = "SELECT ID
                    ,FASE
              FROM as_fases_supervision
              WHERE EST_REGISTRO = 'ACT'
              AND ID=?
              ORDER BY ID;"; // Cambia el nombre de la tabla según tu base de datos
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    return $stmt->get_result();
}

