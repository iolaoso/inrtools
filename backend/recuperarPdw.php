<?php
include_once __DIR__ . '/config.php';
// Incluir archivo de conexión a la base de datos
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; 
// Incluir configuración de correo
include_once BASE_PATH . 'backend/conexiones/confMail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $nickname = $_POST['nickname'];

    // Validar correo y nickname
    $sql = "SELECT p.id
                ,p.identificacion
                ,p.nombre
                ,p.email
                ,p.inrdireccion_id
                ,u.nickname
                ,u.id as user_id
            FROM personas p
            INNER JOIN users u on p.id =  u.persona_id
            WHERE p.estRegistro = 1 AND u.estRegistro = 1 
            AND p.email = ?
            AND u.nickname = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $nickname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $user_id = $usuario['user_id'];
        
        // Generar token de restablecimiento
        $token = bin2hex(random_bytes(50));
        $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora
        
        // Guardar token en la base de datos
        $sql_token = "INSERT INTO reset_tokens (user_id, token, expiracion, usado) VALUES (?, ?, ?, 0)";
        $stmt_token = $conn->prepare($sql_token);
        $stmt_token->bind_param("iss", $user_id, $token, $expiracion);
        
        if ($stmt_token->execute()) {
            // Generar URL con token
            $url = BASE_URL . "/frontend/restablecerPwd.php?token=$token";

            // Configuración del destinatario
            $mail->addAddress($correo, $nickname); // Destinatario principal
                    
            // Contenido del email
            $mail->isHTML(true); // Establecer formato de email a HTML
            $mail->Subject = "Recuperación de Contraseña - SEPS INRTools";
            $mail->Body = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                    <h1 style="color: #2c3e50;">Recuperación de Contraseña - SEPS INRTools</h1>
                    <p>Estimado/a <strong>' . htmlspecialchars($nickname) . '</strong>,</p>
                    <p>Se ha solicitado el restablecimiento de su contraseña para la cuenta asociada al correo: <strong>' . htmlspecialchars($correo) . '</strong></p>
                    <p>Para establecer una nueva contraseña, haga clic en el siguiente enlace:</p>
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $url . '" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                            Restablecer Contraseña
                        </a>
                    </div>
                    <p><strong>Este enlace expirará en 1 hora.</strong></p>
                    <p>Si no solicitó este cambio, por favor ignore este correo.</p>
                    <hr style="margin: 20px 0;">
                    <p style="font-size: 12px; color: #666;">
                        Si el botón no funciona, copie y pegue la siguiente URL en su navegador:<br>
                        ' . $url . '
                    </p>
                </div>';
            
            $mail->AltBody = "Recuperación de Contraseña - INRTools SEPS\n\n" .
                            "El usuario: " . $nickname . " ha solicitado el restablecimiento de la contraseña.\n\n" .
                            "Para restablecer su contraseña, visite el siguiente enlace:\n" .
                            $url . "\n\n" .
                            "Este enlace expirará en 1 hora.\n\n" .
                            "Si no solicitó este cambio, ignore este correo.";

            if($mail->send()) {
                //redirigir al login
                header("Location: " . BASE_URL . "/frontend/login.php?msg=Se ha enviado un correo para restablecer tu password.");
                exit();
                
            } else {
                // Si falla el envío, eliminar el token creado
                $sql_cleanup = "DELETE FROM reset_tokens WHERE token = ?";
                $stmt_cleanup = $conn->prepare($sql_cleanup);
                $stmt_cleanup->bind_param("s", $token);
                $stmt_cleanup->execute();
                $stmt_cleanup->close();
                
                //redirigir al login con error
                header("Location: ../frontend/login.php?msg=No se pudo enviar el correo. Inténtalo de nuevo más tarde.");
                exit();
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al generar el token de seguridad."
            ]);

            //redirigir al login con error
            header("Location: ../frontend/login.php?msg=Error al generar el token de seguridad. Inténtalo de nuevo más tarde.");
            exit();
        }
        
        $stmt_token->close();
    } else {
        //redirigir al login con error
        header("Location: ../frontend/login.php?msg=No se encontró una cuenta con ese correo y nickname.");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    //redirigir al login con error
    header("Location: ../frontend/login.php?msg=Método no permitido.");
    exit();
}
?>