<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';
include_once '../session.php'; // Asegúrate de que la sesión esté iniciada

// Verificar que la sesión está activa y que el usuario está autenticado
// if (!isset($nickname)) {
//     echo json_encode(['success' => false, 'error' => 'Usuario no autenticado.']);
//     exit();
// }

$fecha_actual = date('Y-m-d H:i:s');

// Manejo de solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificacion = $_POST['identificacion'] ?? '';
    $nombre = mb_strtoupper($_POST['nombre']) ?? '';
    $email = $_POST['email'] ?? '';
    $inrdireccion_id = $_POST['inrdireccion_id'] ?? '';
    $usrCreacion = mb_strtoupper($_SESSION['nickname']); // Obtener el usuario de la sesión

    if (!empty($identificacion) && !empty($nombre) && !empty($email) && !empty($inrdireccion_id)) {
        // Es un insert
        $sql = "INSERT INTO personas (identificacion, nombre, email, inrdireccion_id, estRegistro, UsrCreacion, createdAt, updatedAt) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta: ' . $conn->error]);
            exit();
        }

        $estRegistro = 1; // Valor fijo para el estado de registro
        $stmt->bind_param("ssssssss", $identificacion, $nombre, $email, $inrdireccion_id, $estRegistro, $usrCreacion, $fecha_actual, $fecha_actual);

        // Ejecutar la consulta y manejar posibles excepciones
        try {
            $stmt->execute();
            echo json_encode(['success' => true]); // Respuesta de éxito
        } catch (mysqli_sql_exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al insertar: ' . $e->getMessage()]); // Mostrar error específico
        }

        $stmt->close(); // Cerrar el statement
    } else {
        echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios.']); // Respuesta de error
    }
    exit();
}
