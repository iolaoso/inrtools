<?php
// Incluir los archivos de PHPMailer
require BASE_PATH . 'assets/libs/PHPMailer/src/Exception.php';
require BASE_PATH . 'assets/libs/PHPMailer/src/PHPMailer.php';
require BASE_PATH . 'assets/libs/PHPMailer/src/SMTP.php';


// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Definir variables
$userEmail = 'Israel.lopez@seps.gob.ec';
$userPassword = 'Iolaseps@2626'; 
$userName = 'Israel Lopez';

$mail = new PHPMailer(true);

try {
        // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'mail.seps.gob.ec'; // Servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = $userEmail;
    $mail->Password = $userPassword;
    
    // Configuración según los puertos especificados
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS para SMTP
    $mail->Port = 25; // Puerto 25 para SMTP con TLS
    
    // Configuración de timeout (1 minuto = 60 segundos)
    $mail->Timeout = 60;
    
    // Configuración del remitente 
    $mail->setFrom($userEmail, $userName);

    $mail->CharSet    = 'UTF-8'; // Establecer codificación UTF-8
    
}catch (Exception $e) {
    echo "Error al configurar el correo: {$mail->ErrorInfo}";
    exit;
}

?>