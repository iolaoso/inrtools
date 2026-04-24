<?php
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

function obtenerAnalistas($direccion)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT U.PERSONA_ID
                ,U.NICKNAME
                ,P.NOMBRE
                ,D.DIRECCION
                ,UR.rol_id
            FROM USERS U
            LEFT JOIN PERSONAS P ON U.PERSONA_ID = P.ID
            LEFT JOIN INRDIRECCION D ON P.INRDIRECCION_ID = D.ID
            LEFT JOIN USER_ROLES UR ON U.ID = UR.USER_ID
            WHERE UR.ROL_ID=2 
            AND D.DIRECCION = ?";
    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Conexión fallida: ' . $conn->connect_error]));
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $direccion);
    $stmt->execute();
    return $stmt->get_result();
}