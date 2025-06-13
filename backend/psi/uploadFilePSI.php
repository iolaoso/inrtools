<?php
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/psidb_connection.php';

// Asegúrate de que el método de la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica si los datos fueron recibidos correctamente
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['message' => 'Datos inválidos']);
        exit;
    }

    try {

        //VERIFICA SI LA FECHA DE CORTE DE INFORMACIÓN EXISTE EN LA BASE DE DATOS
        $ultFechaCorte = $data['records'][0]['FECHA_CORTE_INFORMACION'] ?? null;
        if (!$ultFechaCorte) {
            http_response_code(400);
            echo json_encode(['message' => 'Fecha de corte de información no proporcionada']);
            exit;
        }
        // Verifica si la fecha de corte ya existe
        $checkStmt = $connPsi->prepare("SELECT COUNT(*) FROM psi WHERE FECHA_CORTE_INFORMACION = ?");
        $checkStmt->bind_param("s", $ultFechaCorte);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();
        if ($count > 0) {
            // Si la fecha de corte ya existe, devuelve un error
            http_response_code(409);
            echo json_encode([
                'message' => 'Fecha de corte: ' . $ultFechaCorte . ' ya existe. <br>' . ' No se pueden insertar datos duplicados.',
            ]);
            exit;
        } else {
            // Actualiza el campo ULTIMO_CORTE a 0
            $updateStmt = $connPsi->prepare("UPDATE psi SET ULTIMO_CORTE = 0");
            $updateStmt->execute();

            // Prepara la consulta de inserción
            $stmt = $connPsi->prepare("INSERT INTO psi (NUMERO, COD_UNICO, RUC, RAZON_SOCIAL, SEGMENTO, ZONAL, ESTADO_JURIDICO, TIPO_SUPERVISION, FECHA_INICIO, FECHA_FIN, ANIO_INICIO, MES_INICIO, ANIO_VENCIMIENTO, MES_VENCIMIENTO, TRIMESTRE, ESTADO_PSI, VIGENCIA_PSI, FECHA_APROBACION_PLAN_FISICO, NUM_INFORME, FECHA_INFORME, NUM_RESOLUCION, FECHA_RESOLUCION, NUM_RESOLUCION_AMPLIACION, FECHA_RESOLUCION_AMPLIACION, FECHA_ULTIMO_BALANCE, ACTIVOS, ULTIMO_RIESGO, NUM_RESOLUCION_FIN_PSI, FECHA_RESOLUCION_FIN_PSI, MOTIVO_CIERRE, ESTRATEGIA_SUPERVISION, FECHA_CORTE_INFORMACION, ULTIMO_CORTE, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new mysqli_sql_exception("Error al preparar la consulta: " . $connPsi->error);
            }

            // Obtener la fecha actual
            $fechaActual = date('Y-m-d H:i:s');
            $numRecord = 0;

            // Itera sobre cada registro en $data
            foreach ($data['records'] as $record) {
                // Vincula y ejecuta la consulta de inserción
                $stmt->bind_param(
                    "sssssssssssssssssssssssssssssssssssss",
                    $record['NUMERO'],
                    $record['COD_UNICO'],
                    $record['RUC'],
                    $record['RAZON_SOCIAL'],
                    $record['SEGMENTO'],
                    $record['ZONAL'],
                    $record['ESTADO_JURIDICO'],
                    $record['TIPO_SUPERVISION'],
                    $record['FECHA_INICIO'],
                    $record['FECHA_FIN'],
                    $record['ANIO_INICIO'],
                    $record['MES_INICIO'],
                    $record['ANIO_VENCIMIENTO'],
                    $record['MES_VENCIMIENTO'],
                    $record['TRIMESTRE'],
                    $record['ESTADO_PSI'],
                    $record['VIGENCIA_PSI'],
                    $record['FECHA_APROBACION_PLAN_FISICO'],
                    $record['NUM_INFORME'],
                    $record['FECHA_INFORME'],
                    $record['NUM_RESOLUCION'],
                    $record['FECHA_RESOLUCION'],
                    $record['NUM_RESOLUCION_AMPLIACION'],
                    $record['FECHA_RESOLUCION_AMPLIACION'],
                    $record['FECHA_ULTIMO_BALANCE'],
                    $record['ACTIVOS'],
                    $record['ULTIMO_RIESGO'],
                    $record['NUM_RESOLUCION_FIN_PSI'],
                    $record['FECHA_RESOLUCION_FIN_PSI'],
                    $record['MOTIVO_CIERRE'],
                    $record['ESTRATEGIA_SUPERVISION'],
                    $record['FECHA_CORTE_INFORMACION'],
                    $record['ULTIMO_CORTE'],
                    $record['EST_REGISTRO'],
                    $record['USR_CREACION'],
                    $fechaActual, // Fecha de creación actual
                    $fechaActual // Fecha de actualización actual,
                );

                if (!$stmt->execute()) {
                    // Manejo de errores en la inserción
                    http_response_code(500);
                    echo json_encode([
                        'message' => 'Error al insertar datos: ' . $stmt->error,
                        'record' => $record,
                        'numRecord' => $numRecord
                    ]);
                    exit;
                }
                $numRecord++;
            }
        }

        // Respuesta exitosa
        http_response_code(201);
        echo json_encode([
            'message' => 'Datos subidos correctamente',
            'numRecord' => $numRecord
        ]);
    } catch (mysqli_sql_exception $e) {
        // Manejo de errores de la base de datos
        http_response_code(500);
        echo json_encode(['message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Respuesta para métodos no permitidos
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
}