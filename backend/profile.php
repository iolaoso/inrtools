<?php
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

function getprofile($idpersona)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT *
            FROM PERSONAS 
            WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $idpersona);
    $stmt->execute();

    return $stmt->get_result();
}
