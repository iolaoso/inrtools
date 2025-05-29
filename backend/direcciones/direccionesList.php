<?php
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

function obtenerDireccionesFull()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT id AS idDirSelect
                ,direccion AS dirSelect
                ,dirNombre as dirNombreSelect
            FROM inrdireccion
            where estRegistro = 1;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}
