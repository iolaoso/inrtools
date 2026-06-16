<?php

/**
 * ==========================================================
 * RECUPERARPWD.PHP
 * ==========================================================
 * Proceso de recuperación de contraseña.
 *
 * Flujo:
 *
 * 1. Usuario ingresa:
 *      - correo
 *      - nickname
 *
 * 2. Se valida que ambos pertenezcan al mismo usuario.
 *
 * 3. Se genera un token seguro.
 *
 * 4. El token se almacena en la tabla reset_tokens.
 *
 * 5. Se envía un correo con el enlace de recuperación.
 *
 * 6. El usuario accede al enlace para definir
 *    una nueva contraseña.
 * ==========================================================
 */


/**
 * ==========================================================
 * CONFIGURACIÓN GENERAL
 * ==========================================================
 */

include_once __DIR__ . '/config.php';

/**
 * Conexión MySQL
 */
include_once BASE_PATH . 'backend/conexiones/db_connection.php';

/**
 * Configuración PHPMailer
 */
include_once BASE_PATH . 'backend/conexiones/confMail.php';


/**
 * ==========================================================
 * VALIDAR MÉTODO HTTP
 * ==========================================================
 */

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    header(
        'Location: ' .
        BASE_URL .
        '/frontend/login.php?msg=Método no permitido.'
    );

    exit();
}


/**
 * ==========================================================
 * OBTENER DATOS DEL FORMULARIO
 * ==========================================================
 */

$correo =
    trim($_GET['correo'] ?? '');

$nickname =
    mb_strtoupper(
        trim($_GET['nickname'] ?? '')
    );


/**
 * ==========================================================
 * VALIDAR DATOS OBLIGATORIOS
 * ==========================================================
 */

if (
    empty($correo) ||
    empty($nickname)
) {

    header(
        'Location: ' .
        BASE_URL .
        '/frontend/login.php?msg=Debe ingresar correo y usuario.'
    );

    exit();
}


try {

    /**
     * ======================================================
     * VALIDAR USUARIO
     * ======================================================
     *
     * Verifica que:
     *   - El correo exista.
     *   - El nickname exista.
     *   - Ambos correspondan a la misma persona.
     */

    $sql = "
        SELECT
            CASE
                WHEN u.persona_id IS NOT NULL
                THEN 'SI'
                ELSE 'NO'
            END AS EXISTE,
            u.persona_id,
            u.id AS USUARIO_ID
        FROM
        (
            SELECT id
            FROM personas
            WHERE email = ?
        ) p
        LEFT JOIN
        (
            SELECT
                id,
                persona_id,
                nickname
            FROM users
            WHERE nickname = ?
        ) u
        ON p.id = u.persona_id";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ss",
        $correo,
        $nickname
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    $stmt->close();


    /**
     * ======================================================
     * VALIDAR EXISTENCIA
     * ======================================================
     */

    if (
        !$row ||
        ($row['EXISTE'] ?? 'NO') !== 'SI'
    ) {

        header(
            'Location: ' .
            BASE_URL .
            '/frontend/login.php?msg=No existe una cuenta asociada al correo y usuario ingresados.'
        );

        exit();
    }

    /**
     * ======================================================
     * GENERAR TOKEN DE SEGURIDAD
     * ======================================================
     *
     * random_bytes():
     * genera valores criptográficamente seguros.
     */

    $token =
        bin2hex(
            random_bytes(50)
        );

    /**
     * Vigencia del token:
     * 1 hora.
     */

    $expiracion =
        date(
            'Y-m-d H:i:s',
            strtotime('+1 hour')
        );

    $user_id = $row['USUARIO_ID'];


    /**
     * ======================================================
     * GUARDAR TOKEN
     * ======================================================
     */

    $sqlToken = "INSERT INTO reset_tokens(user_id,token,expiracion,usado,fecha_creacion)
                    VALUES (?,?,?,0,NOW())";

    $stmtToken = $conn->prepare($sqlToken);

    $stmtToken -> bind_param("iss",
                             $user_id,
                             $token,
                             $expiracion);

    // Ejecutar la consulta
    if ($stmtToken->execute()) {
        echo "Registro guardado exitosamente.";
    } else {
        echo "Error al guardar el registro: " . $stmtToken->error;
    }

    // Cerrar la conexión
    $stmtToken->close();

    /**
     * ======================================================
     * CONSTRUIR URL DE RECUPERACIÓN
     * ======================================================
     */

    $url = BASE_URL . '/frontend/restablecerPwd.php?token=' . $token;

    /**
     * ======================================================
     * PREPARAR CORREO
     * ======================================================
     */

    $mail->clearAddresses();

    $mail->addAddress(
        $correo,
        $nickname
    );

    // Copia a ...
    $mail->addCC(
        'israel.lopez@seps.gob.ec',
        'Israel Lopez'
    );

    $mail->Subject =
        'Recuperación de Contraseña - INRTools';


    /**
     * ======================================================
     * CUERPO HTML
     * ======================================================
     */

    $mail->Body = '
       <div style="
            font-family: Arial, Helvetica, sans-serif;
            background-color:#f4f6f9;
            padding:30px;
        ">
        <table width="100%" cellpadding="0" cellspacing="0" style="
            max-width:700px;
            margin:auto;
            background:#ffffff;
            border-radius:10px;
            overflow:hidden;
            border:1px solid #dcdcdc;">
            <!-- CABECERA -->
            <tr>
                <td style="
                    background:#003366;
                    color:#ffffff;
                    padding:25px;
                    text-align:center;">
                    <h1 style="
                        margin:0;
                        font-size:24px;">
                        INRTools
                    </h1>
                    <p style="
                        margin-top:8px;
                        font-size:14px;
                        opacity:0.9;">
                        Sistema de Herramientas de Inteligencia de Negocio
                    </p>
                </td>
            </tr>
            <!-- CONTENIDO -->
            <tr>
                <td style="padding:35px;">
                    <h2 style="
                        color:#003366;
                        margin-top:0;">
                        Recuperación de Contraseña
                    </h2>
                    <p>
                        Estimado/a
                        <strong>' . htmlspecialchars($nickname) . '</strong>,
                    </p>
                    <p>
                        Hemos recibido una solicitud para restablecer la contraseña asociada a su cuenta de acceso en
                        <strong>INRTools</strong>.
                    </p>
                    <div style="
                        background:#eef5ff;
                        border-left:5px solid #0d6efd;
                        padding:15px;
                        margin:20px 0;">
                        <strong>Información importante:</strong>
                        <ul style="margin-top:10px;padding-left:20px;">
                            <li>El enlace es de uso único.</li>
                            <li>Caducará automáticamente en 1 hora.</li>
                            <li>No comparta este enlace con terceros.</li>
                        </ul>
                    </div>
                    <p style="text-align:center;margin:35px 0;">
                        <a href="' . $url . '" style="
                            background:#0d6efd;
                            color:#ffffff;
                            text-decoration:none;
                            padding:15px 30px;
                            border-radius:6px;
                            font-weight:bold;
                            display:inline-block;">
                            Restablecer Contraseña
                        </a>
                    </p>
                    <p>
                        Si el botón anterior no funciona, copie y pegue el siguiente enlace en su navegador:
                    </p>
                    <div style="
                        background:#f8f9fa;
                        border:1px solid #dee2e6;
                        padding:10px;
                        word-break:break-all;
                        font-size:12px;">
                        ' . $url . '
                    </div>
                    <br>
                    <p>
                        Si usted no realizó esta solicitud, puede ignorar este mensaje.
                        Su contraseña actual continuará siendo válida.
                    </p>
                </td>
            </tr>
            <!-- PIE -->
            <tr>
                <td style="
                    background:#f8f9fa;
                    padding:20px;
                    font-size:12px;
                    color:#666666;
                    text-align:center;
                    border-top:1px solid #e0e0e0;">
                    <strong>Superintendencia de Economía Popular y Solidaria</strong><br>
                    Dirección Nacional de Riesgos<br>
                    Plataforma INRTools
                    <br><br>
                    Este correo fue generado automáticamente.
                    Por favor no responda a este mensaje.
                </td>
            </tr>
        </table>
        </div>';     
    
    /**
     * ======================================================
     * CUERPO TEXTO PLANO
     * ======================================================
     *
     * Utilizado por clientes de correo que no
     * soportan HTML.
     */

    $mail->AltBody =
        "Recuperación de contraseña\n\n" .
        "Acceda al siguiente enlace:\n\n" .
        $url .
        "\n\n" .
        "Este enlace expirará en 1 hora.";
    
    /**
     * ======================================================
     * ENVIAR CORREO
     * ======================================================
     */

    try {
        $mail->send();
        $estadoEnvio = [
            'enviado' => true,
            'mensaje' => 'Mail enviado correctamente'
        ];
    } catch (Exception $e) {
        $estadoEnvio = [
            'enviado' => false,
            'mensaje' => $mail->ErrorInfo,
            'exception' => $e->getMessage()
        ];
    }

    $datosFull = [
        'success'         => true,
        'correo'          => $correo,
        'nickname'        => $nickname,
        'existe'          => $row['EXISTE'],
        'user_id'         => $user_id,
        'token'           => $token,
        'expiracion'      => $expiracion,
        // Estado del envío
        'estado_envio'    => $estadoEnvio,
        // Configuración utilizada
        'mail_host'       => $mail->Host,
        'mail_port'       => $mail->Port,
        'mail_user'       => $mail->Username,
        'mail_secure'     => $mail->SMTPSecure,
        'mail_auth'       => $mail->SMTPAuth,
        'mail_from'       => $mail->From,
        'mail_from_name'  => $mail->FromName,
        'mail_subject'    => $mail->Subject,
        'mail_to'         => $mail->getToAddresses()];
  
    /**
     * ======================================================
     * REDIRECCIÓN EXITOSA
     * ======================================================
     */

    header(
        'Location: ' .
        BASE_URL .
        '/frontend/login.php?msg=Se ha enviado un correo con las instrucciones para restablecer su contraseña.'
    );

    exit();

} catch (Exception $e) {

    /**
     * ======================================================
     * REGISTRAR ERROR
     * ======================================================
     */

    error_log(
        '[RECUPERAR PASSWORD] ' .
        $e->getMessage()
    );


    /**
     * ======================================================
     * REDIRECCIÓN CON ERROR
     * ======================================================
     */

    header(
        'Location: ' .
        BASE_URL .
        '/frontend/login.php?msg=No fue posible procesar la solicitud.'
    );

    exit();
}

/**
 * ==========================================================
 * CERRAR CONEXIÓN
 * ==========================================================
 */

$conn->close();

?>

