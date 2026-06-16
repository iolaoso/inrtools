<?php

/**
 * ==========================================================
 * CONFMAIL.PHP
 * ==========================================================
 * Configuración centralizada de PHPMailer para INRTools.
 *
 * Este archivo:
 *   - Carga PHPMailer
 *   - Configura SMTP Gmail
 *   - Crea el objeto $mail
 *
 * Este archivo NO envía correos.
 * El envío se realiza desde cada módulo:
 * ==========================================================
 */


/**
 * ==========================================================
 * CARGAR LIBRERÍAS PHPMailer
 * ==========================================================
 */

require_once BASE_PATH . 'assets/libs/PHPMailer/src/Exception.php';
require_once BASE_PATH . 'assets/libs/PHPMailer/src/PHPMailer.php';
require_once BASE_PATH . 'assets/libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
 * ==========================================================
 * CONFIGURACIÓN DE LA CUENTA
 * ==========================================================
 *
 * IMPORTANTE:
 * Utilizar una contraseña de aplicación de Gmail.
 */

$correoSistema = 'inrtools.support@gmail.com';

$passwordSistema = 'affulyzvqiahfyye';


/**
 * ==========================================================
 * CREAR OBJETO PHPMailer
 * ==========================================================
 */

$mail = new PHPMailer(true);

try {

    /**
     * ======================================================
     * CONFIGURACIÓN SMTP
     * ======================================================
     */

    $mail->isSMTP();

    /**
     * Servidor SMTP de Gmail
     */
    $mail->Host = 'smtp.gmail.com';

    /**
     * Puerto TLS
     */
    $mail->Port = 587;

    /**
     * Autenticación requerida
     */
    $mail->SMTPAuth = true;

    /**
     * Credenciales
     */
    $mail->Username = $correoSistema;
    $mail->Password = $passwordSistema;

    /**
     * Seguridad TLS
     */
    $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

    /**
     * Tiempo máximo de espera
     */
    $mail->Timeout = 60;

    /**
     * ======================================================
     * CONFIGURACIÓN GENERAL
     * ======================================================
     */

    $mail->CharSet = 'UTF-8';

    $mail->isHTML(true);

    /**
     * Nivel de depuración
     *
     * 0 = Producción
     * 2 = Diagnóstico SMTP
     */
    $mail->SMTPDebug = 0;

    /**
     * Remitente por defecto
     */
    $mail->setFrom(
        $correoSistema,
        'INRTools'
    );

} catch (Exception $e) {

    error_log(
        'Error configurando PHPMailer: '
        . $e->getMessage()
    );

    throw $e;
}
?>
