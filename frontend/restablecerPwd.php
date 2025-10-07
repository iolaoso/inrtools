<?php
include_once __DIR__ . '/../backend/config.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

// Obtener token de la URL
$token = $_GET['token'] ?? '';
$token = trim($token);

if (empty($token)) {
    // Mostrar página de error en lugar de redirigir
    mostrarPaginaError("Enlace inválido", "El enlace de recuperación no es válido o ha expirado.");
    exit;
}

// Validar token
$stmt = $conn->prepare("SELECT id, user_id 
                        FROM reset_tokens 
                        WHERE token = ? 
                        AND usado = 0 
                        AND expiracion >= NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $token_data = $result->fetch_assoc();
    $user_id = $token_data['user_id'];
    
    // Actualizar contraseña
    $nueva_password = '123456789'; // Contraseña temporal
    $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
    
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $password_hash, $user_id);
    
    if ($update_stmt->execute()) {
        // Marcar token como usado
        $mark_stmt = $conn->prepare("UPDATE reset_tokens SET usado = 1 WHERE id = ?");
        $mark_stmt->bind_param("i", $token_data['id']);
        $mark_stmt->execute();
        
        // Mostrar página de éxito
        mostrarPaginaExito();
        exit;
    } else {
        mostrarPaginaError("Error", "No se pudo actualizar la contraseña. Por favor, intenta nuevamente.");
        exit;
    }
} else {
    mostrarPaginaError("Enlace inválido", "El enlace de recuperación no es válido o ha expirado.");
    exit;
}

$conn->close();

// Función para mostrar página de éxito
function mostrarPaginaExito() {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contraseña Restablecida - Éxito</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/login_style.css"> <!-- Enlace al archivo CSS -->
    </head>
    <body>
        <div class="login-container">
            <!-- logo -->
            <img src="../assets/images/seps.jpg" alt="SEPS" class="logo" onerror="this.style.display='none'">
            
            <div class="success-icon text-center">✓</div>
            
            <h1 class="text-center">¡Contraseña Restablecida!</h1>
            
            <p>Tu contraseña ha sido actualizada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.</p>
            
            <div class="info-box">
                <!-- <p><strong>Contraseña temporal:</strong></p>
                <div class="password-display">2222</div> -->
                <p><small style="color: red;">Por seguridad, cambia la contraseña después de iniciar sesión.</small></p>
            </div>
            
            <a href="login.php" class="btn">
                Iniciar Sesión
            </a>
        </div>
        
        <script>
            // Redirección automática después de 10 segundos
            setTimeout(function() {
                window.location.href = "login.php";
            }, 10000);
        </script>
    </body>
    </html>
    <?php
}

// Función para mostrar página de error
function mostrarPaginaError($titulo, $mensaje) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - Restablecer Contraseña</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/login_style.css"> <!-- Enlace al archivo CSS -->
    </head>
    <body>
        <div class="login-container">
            <!-- logo -->
            <img src="../assets/images/seps.jpg" alt="SEPS" class="logo" onerror="this.style.display='none'">
            
            <div class="error-icon text-center">✗</div>
            
            <h1 class="text-center"><?php echo htmlspecialchars($titulo); ?></h1>
            
            <p><?php echo htmlspecialchars($mensaje); ?></p>
            
            <div>
                <a href="recuperarPdw.php" class="btn">
                    Intentar Nuevamente
                </a>
                <a href="login.php" class="btn btn-primary">
                    Ir al Login
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>