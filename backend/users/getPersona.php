<?php

// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';
include_once '../session.php';

$fecha_actual = date('Y-m-d H:i:s');

header('Content-Type: application/json'); 

$idPersona = $_GET['idPersona'] ?? 0;

if(empty($idPersona)){
    echo json_encode([
        'success' => false,
        'message' => 'ID no recibido'
    ]);
    exit;
}

$sql = "SELECT
            id,
            identificacion,
            nombre,
            email,
            inrdireccion_id
        FROM personas
        WHERE id = ?";

$stmt = $conn->prepare($sql); 

if ($stmt === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Error en la preparación de la consulta: '  . $conn->error 
    ]);
    exit();
}

$stmt->bind_param("i",$idPersona);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){

    echo json_encode([
        'sql' => $stmt,
        'success' => false,
        'message' => 'Persona no encontrada'
    ]);

}else{

    echo json_encode([
        'success' => true,
        'data' => $result->fetch_assoc()
    ]);

}

$stmt->close();
$conn->close(); 