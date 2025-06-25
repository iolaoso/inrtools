<?php

include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
require_once BASE_PATH . '/assets/adodb5/adodb.inc.php'; // Ajusta la ruta según tu estructura
require_once BASE_PATH . 'backend/conexiones/conexionSSAS.php'; // archivo que contiene la conexion OLAP

echo "Prueba de conexión OLAP <br>";

// Ejemplo de uso
$servidorSSAS = 'frigga';
$cuboOLAP = 'SEPS_NET_SSI';


$conexion = conectarSSAS($servidorSSAS, $cuboOLAP);

if ($conexion) {
    echo "Existe la conexion.<br>";
    $mdx = "SELECT NON EMPTY { [Measures].[CONTEO REGISTROS] } ON COLUMNS FROM [ACTIVIDADES PLAN DE TRABAJO] CELL PROPERTIES VALUE, BACK_COLOR, FORE_COLOR, FORMATTED_VALUE, FORMAT_STRING, FONT_NAME, FONT_SIZE, FONT_FLAGS"; // Tu consulta MDX aquí
    echo "Ejecutando MDX: $mdx <br>";
    $resultado = ejecutarMDX($conexion, $mdx);
    if (!$resultado) {
        echo "Error al ejecutar MDX: " . $conexion->ErrorMsg() . "<br>";
    } else {
        echo "Consulta MDX ejecutada correctamente.<br>";
        // Procesar resultados
        while (!$resultado->EOF) {
            $fila = $resultado->FetchRow();
            // ... trabajar con los datos
            echo "Resultado: " . implode(", ", $fila) . "<br>";
            $resultado->MoveNext();
        }
    }

    cerrarConexionSSAS($conexion);
} else {
    echo "Error al conectar a SSAS.";
}
//         $conn->Close();
//         $conn = null;