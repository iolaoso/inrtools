<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';
include_once '../session.php'; // Asegúrate de que la sesión esté iniciada

// Asegúrate de que este bloque esté al inicio del script.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configura el encabezado para la respuesta JSON
header('Content-Type: application/json');

$fecha_actual = date('Y-m-d H:i:s');

// Manejo de solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $persona_id = $_POST['persona_id'] ?? ''; // ID de la persona a la que se asignará el usuario
    $username = mb_strtoupper($_POST['nickname']) ?? ''; // Nombre de usuario
    $password = $_POST['password'] ?? ''; // Contraseña
    $rol_id = $_POST['rol_id'] ?? ''; // Rol del nuevo usuario
    $usrCreacion = $_SESSION['nickname'] ?? ''; // Obtener el usuario de la sesión, asegurando que esté definido

    // Validación de datos
    if (!empty($persona_id) && !empty($username) && !empty($password) && !empty($rol_id) && !empty($usrCreacion)) {
        // Verificar si el nombre de usuario ya existe
        $check_sql = "SELECT COUNT(*) FROM users WHERE nickname = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo json_encode(['success' => false, 'error' => 'El nombre de usuario ya está en uso.']);
            exit();
        }

        // Insertar nuevo usuario
        $sql = "INSERT INTO users (persona_id, nickname, password, UsrCreacion, createdAt, updatedAt) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta: ' . $conn->error]);
            exit();
        }

        // Asegúrate de hashear la contraseña antes de guardarla
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("isssss", $persona_id, $username, $hashed_password, $usrCreacion, $fecha_actual, $fecha_actual);

        // Ejecutar la consulta y manejar posibles excepciones
        try {
            $stmt->execute();
            $user_id = $stmt->insert_id; // Obtener el ID del nuevo usuario
            $stmt->close(); // Cerrar el statement

            // Insertar en user_roles
            $sql_role = "INSERT INTO user_roles (user_id, rol_id, createdAt) VALUES (?, ?, ?)";
            $role_stmt = $conn->prepare($sql_role);
            if ($role_stmt === false) {
                echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta de roles: ' . $conn->error]);
                exit();
            }

            $role_stmt->bind_param("iis", $user_id, $rol_id, $fecha_actual);

            try {
                $role_stmt->execute();
                echo json_encode(['success' => true]); // Respuesta de éxito
            } catch (mysqli_sql_exception $e) {
                echo json_encode(['success' => false, 'error' => 'Error al insertar en user_roles: ' . $e->getMessage()]);
            } finally {
                $role_stmt->close(); // Cerrar el statement de roles
            }
        } catch (mysqli_sql_exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error al insertar en users: ' . $e->getMessage()]); // Mostrar error específico
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios.']); // Respuesta de error
    }
    exit();
}
