<?php

/**
 * Conexión a SQL Server Analysis Services (SSAS) desde PHP usando ADODB
 * 
 * Requiere:
 * - ADODB instalado (versión 5 o superior)
 * - Extensión COM habilitada en PHP (php_com_dotnet.dll)
 * - Servidor SSAS accesible desde el servidor web
 */

// Verificación de constantes esenciales
if (!defined('BASE_PATH')) {
    die('Configuración esencial no definida (BASE_PATH)');
}

$adodbPath = BASE_PATH . '/assets/adodb5/adodb.inc.php';
if (!file_exists($adodbPath)) {
    throw new RuntimeException("ADODB no encontrado en: $adodbPath");
}
require_once $adodbPath;

/**
 * Establece conexión con SSAS usando múltiples proveedores OLAP
 * 
 * @param string $server Nombre/IP del servidor SSAS
 * @param string $catalog Nombre del catálogo/cubo OLAP
 * @param array $options Opciones adicionales:
 *      - timeout: Tiempo de espera de conexión (default: 10)
 *      - providers: Array personalizado de proveedores
 * @return ADOConnection|false Objeto de conexión o false en error
 */
function conectarSSAS($server, $catalog, $options = [])
{
    global $ADODB_DEBUG;

    // Configuración de depuración - manejo seguro cuando ENVIRONMENT no está definido
    $ADODB_DEBUG = (defined('ENVIRONMENT') && constant('ENVIRONMENT') === 'development');

    // Configuración por defecto
    $defaultOptions = [
        'timeout' => 10,
        'providers' => [
            //"MSOLAP.8" => "SQL Server 2019+",
            //"MSOLAP.7" => "SQL Server 2017",
            //"MSOLAP.6" => "SQL Server 2016",
            //"MSOLAP.5" => "SQL Server 2014",
            //"MSOLAP.4" => "SQL Server 2012",
            "MSOLAP"   => "Versión genérica"
        ]
    ];

    $options = array_merge($defaultOptions, $options);

    $connOLAP = ADONewConnection('ado');
    if (!$connOLAP) {
        error_log('Error al inicializar ADODB Connection');
        return false;
    }

    // Intentar conexión con cada proveedor
    foreach ($options['providers'] as $provider => $description) {
        echo "Intentando conexión: $provider ($description) <br>";
        try {
            $connStr = sprintf(
                "Provider=%s;Data Source=%s;Initial Catalog=%s;Connect Timeout=%d;",
                $provider,
                $server,
                $catalog,
                $options['timeout']
            );

            if ($ADODB_DEBUG) {
                error_log("Intentando conexión SSAS con: $provider ($description) <br>");
            }

            if ($connOLAP->Connect($connStr)) {
                // Configuración post-conexión
                $connOLAP->SetFetchMode(ADODB_FETCH_ASSOC);
                $connOLAP->Execute('SET QUOTED_IDENTIFIER ON');
                echo "<strong>Conexión exitosa con $provider ($description)</strong> <br>";


                if ($ADODB_DEBUG) {
                    error_log("Conexión exitosa con $provider");
                }

                return $connOLAP;
            }
        } catch (Exception $e) {
            $errorMsg = "Error con proveedor $provider: " . $e->getMessage();
            error_log($errorMsg);

            if ($connOLAP->IsConnected()) {
                $connOLAP->Close();
            }
            continue;
        }
    }

    error_log("Todos los proveedores OLAP fallaron para $server/$catalog");
    return false;
}

/**
 * Ejecuta consulta MDX en conexión SSAS
 * 
 * @param ADOConnection $conn Conexión establecida
 * @param string $mdx Consulta MDX
 * @return ADORecordSet|false Resultado o false en error
 */
function ejecutarMDX($conn, $mdx)
{
    if (!$conn || !$conn->IsConnected()) {
        error_log("Intento de ejecutar MDX en conexión no válida");
        return false;
    }

    try {
        // También puedes usar Query() en lugar de Execute(), aunque internamente hacen lo mismo en ADODB
        $rs = $conn->Query($mdx);
        if (!$rs) {
            // Manejo de error específico de ADODB
            if ($conn->ErrorNo() == 0) {
                error_log("Consulta MDX fallida sin error específico: " . $mdx);
            } else {
                error_log("Error en consulta MDX: " . $conn->ErrorMsg());
            }
            return false;
        }

        return $rs;
    } catch (Exception $e) {
        error_log("Excepción en MDX: " . $e->getMessage());
        return false;
    }
}

/**
 * Cierra conexión SSAS de forma segura
 * 
 * @param ADOConnection $conn Conexión a cerrar
 */
function cerrarConexionSSAS(&$conn)
{
    if ($conn && $conn->IsConnected()) {
        $conn->Close();
    }
    $conn = null;
}

/**
 * Verifica si el proveedor MSOLAP.7 está instalado y retorna información
 * 
 * @return array|false Información del proveedor o false si no está instalado
 */
// Verificar proveedor MSOLAP.7 específicamente
function verificarMSOLAP7()
{
    try {
        $com = new COM("WbemScripting.SWbemLocator");
        $wmi = $com->ConnectServer();
        $provider = $wmi->Get("Win32_Provider.Name='MSOLAP.7'");

        if ($provider) {
            $info = [
                'Name' => $provider->Name,
                'Version' => $provider->Version,
                'InstallDate' => $provider->InstallDate
            ];
            return $info;
        }
        return false;
    } catch (Exception $e) {
        error_log("Error al verificar MSOLAP.7: " . $e->getMessage());
        return false;
    }
}
