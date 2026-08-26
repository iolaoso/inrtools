<?php
session_start();
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

/**
 * Obtiene la IP real del cliente.
 * Considera servidores proxy y balanceadores.
 */
function obtenerIPCliente(): string
{
    $headers = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR'
    ];
    foreach ($headers as $header) {
        if (empty($_SERVER[$header])) {
            continue;
        }
        $ips = explode(',', $_SERVER[$header]);
        foreach ($ips as $ip) {
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return 'DESCONOCIDA';
}


/**
 * Obtiene el nombre del equipo cliente a partir de su IP.
 */
function obtenerHostCliente(string $ip): string
{
    if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
        return 'DESCONOCIDO';
    }

    $host = gethostbyaddr($ip);

    // Si no existe resolución DNS, gethostbyaddr devuelve la misma IP.
    if ($host === $ip) {
        return 'DESCONOCIDO';
    }

    return $host;
}


/**
 * Genera un UUID v4 para identificar una operación.
 */
function generarUuid(): string
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        random_int(0, 0xffff),
        random_int(0, 0xffff),
        random_int(0, 0xffff),
        random_int(0, 0x0fff) | 0x4000,
        random_int(0, 0x3fff) | 0x8000,
        random_int(0, 0xffff),
        random_int(0, 0xffff),
        random_int(0, 0xffff)
    );
}

/**
 * Registra una operación en la bitácora de INRTools.
 * Si $requestId no se recibe, genera uno automáticamente.
 */
function insertarBitacora(
    string $accion,
    string $modulo,
    string $descripcion = '',
    ?string $nombreArchivo = null,
    ?string $rutaArchivo = null,
    ?string $tipoArchivo = null,
    ?int $tamanioArchivo = null,
    string $estado = 'OK',
    string $mensaje = '',
    ?array $datosExtra = null,
    ?string $requestId = null
): bool {
    // Datos del usuario almacenados en la sesión.
    $userId         = $_SESSION['user_id'] ?? null;
    $nickname       = $_SESSION['nickname'] ?? null;
    $email_persona  = $_SESSION['email_persona'] ?? null;
    $direccion      = $_SESSION['direccion'] ?? null;
    $rol_nombre     = $_SESSION['rol_nombre'] ?? null;
    // Información del cliente y sesión.
    $ip = obtenerIPCliente();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $hostCliente = obtenerHostCliente($ip);
    $sessionId = session_id() ?: null;
    // Reutiliza el UUID recibido o genera uno nuevo.
    $requestId = $requestId ?: generarUuid();
    // Convierte información adicional a JSON.
    $datosExtraJson = $datosExtra !== null
        ? json_encode($datosExtra, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        : null;
    global $conn; // Usar la conexión global
    $sql = "INSERT INTO bitacora_usuarios (
                request_id, usuario_id, nickname, correo, direccion, rol,
                accion, modulo, descripcion, nombre_archivo, ruta_archivo,
                tipo_archivo, tamanio_archivo, ip, user_agent, host_cliente,
                session_id, estado, mensaje, datos_extra, fecha_evento, createdAt
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
            )";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('[BITACORA] Error prepare: ' . $conn->error);
        return false;
    }
    $stmt->bind_param(
        "sissssssssssisssssss",
        $requestId,
        $userId,
        $nickname,
        $email_persona,
        $direccion,
        $rol_nombre,
        $accion,
        $modulo,
        $descripcion,
        $nombreArchivo,
        $rutaArchivo,
        $tipoArchivo,
        $tamanioArchivo,
        $ip,
        $userAgent,
        $hostCliente,
        $sessionId,
        $estado,
        $mensaje,
        $datosExtraJson
    );
    $resultado = $stmt->execute();
    if (!$resultado) {
        error_log('[BITACORA] Error execute: ' . $stmt->error);
    }
    $stmt->close();
    return $resultado;
}




