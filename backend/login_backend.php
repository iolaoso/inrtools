<?php
session_start();
include 'conexiones/db_connection.php'; // Incluir conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];

    // Consultar el usuario, verificando que esté activo
    $sql = "SELECT * FROM users WHERE nickname = ? AND estRegistro = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    $result = $stmt->get_result();
    $_SESSION['ambiente'] = ($dbname == 'INRtools') ? 'Producción' : 'Desarrollo';

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Guardar solo el ID y el nickname del usuario en sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nickname'] = $user['nickname'];

            // Obtener rol del usuario desde la tabla user_roles
            $sql_role = "SELECT rol_id FROM user_roles WHERE user_id = ?";
            $stmt_role = $conn->prepare($sql_role);
            $stmt_role->bind_param("i", $user['id']);
            $stmt_role->execute();
            $result_role = $stmt_role->get_result();

            if ($result_role->num_rows > 0) {
                $role = $result_role->fetch_assoc();
                $_SESSION['rol_id'] = $role['rol_id'];

                // Obtener el nombre del rol desde la tabla roles
                $sql_nombre_rol = "SELECT nombre FROM roles WHERE id = ?";
                $stmt_nombre_rol = $conn->prepare($sql_nombre_rol);
                $stmt_nombre_rol->bind_param("i", $role['rol_id']);
                $stmt_nombre_rol->execute();
                $result_nombre_rol = $stmt_nombre_rol->get_result();

                if ($result_nombre_rol->num_rows > 0) {
                    $nombre_rol = $result_nombre_rol->fetch_assoc();
                    $_SESSION['rol_nombre'] = $nombre_rol['nombre'];
                }
            }

            // Obtener datos de la persona
            $sql_persona = "SELECT * FROM personas WHERE id = ?";
            $stmt_persona = $conn->prepare($sql_persona);
            $stmt_persona->bind_param("i", $user['persona_id']);
            $stmt_persona->execute();
            $result_persona = $stmt_persona->get_result();

            if ($result_persona->num_rows > 0) {
                $persona = $result_persona->fetch_assoc();
                $_SESSION['persona_id'] = $persona['id'];
                $_SESSION['identificacion'] = $persona['identificacion'];
                $_SESSION['nombre'] = $persona['nombre'];
                $_SESSION['email_persona'] = $persona['email'];
                $_SESSION['inrdireccion_id'] = $persona['inrdireccion_id'];

                // Obtener la dirección de inrdireccion
                $sql_direccion = "SELECT direccion FROM inrdireccion WHERE id = ?";
                $stmt_direccion = $conn->prepare($sql_direccion);
                $stmt_direccion->bind_param("s", $persona['inrdireccion_id']);
                $stmt_direccion->execute();
                $result_direccion = $stmt_direccion->get_result();

                if ($result_direccion->num_rows > 0) {
                    $direccion = $result_direccion->fetch_assoc();
                    $_SESSION['direccion'] = $direccion['direccion'];
                }
            }

            header("Location: ../frontend/main.php"); // Redirigir a main.php
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: ../frontend/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado o inactivo.";
        header("Location: ../frontend/login.php");
        exit();
    }
}

// Cerrar conexión
$conn->close();
