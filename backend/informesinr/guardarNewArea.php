<?php
// Incluir archivo de conexión específico
include '../conexiones/infinr_connection.php';

try {
    // Validar método de solicitud
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Obtener y validar datos del formulario
    $newTipoInforme = trim(mb_strtoupper($_POST['newTipoInforme']) ?? '');
    $newAreaRequiriente = trim(mb_strtoupper($_POST['newAreaRequiriente']) ?? '');
    $usrCreacion = trim(mb_strtoupper($_POST['usrCreacion']) ?? '');

    // Variables adicionales requeridas
    $fecha_actual = date('Y-m-d H:i:s');
    $estRegistro = 'ACT';

    // Validar datos requeridos
    $errores = [];
    if (empty($newTipoInforme)) $errores[] = 'El tipo de informe es requerido';
    if (empty($newAreaRequiriente)) $errores[] = 'El área requiriente es requerida';
    if (empty($usrCreacion)) $errores[] = 'El usuario de creación es requerido';

    if (!empty($errores)) {
        throw new Exception(implode(', ', $errores), 400);
    }

    // USAR LA CONEXION
    global $connInf;

    // Verificar si el tipo de informe ya existe
    $sqlCheck = "SELECT COUNT(*) FROM t_tipo_informe WHERE TIPO_INFORME = ? AND AREA_REQUIRIENTE = ? AND EST_REGISTRO='ACT'";
    $sqlCheck = $connInf->prepare($sqlCheck);
    $sqlCheck->bind_param("ss", $newTipoInforme, $newAreaRequiriente);
    $sqlCheck->execute();
    $sqlCheck->bind_result($count);
    $sqlCheck->fetch();
    $sqlCheck->close();
    if ($count > 0) {
        throw new Exception('El tipo de informe ya existe para el area requiriente especificada.', 409);
    }

    // Insertar nuevo tipo de informe
    $sqlInsert = "INSERT INTO t_tipo_informe (TIPO_INFORME, AREA_REQUIRIENTE, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $connInf->prepare($sqlInsert);
    $stmtInsert->bind_param("ssssss", $newTipoInforme, $newAreaRequiriente, $estRegistro, $usrCreacion, $fecha_actual, $fecha_actual);

    // Ejecutar la consulta y devolver el resultado
    if ($stmtInsert->execute()) {
        echo json_encode(["message" => "Registro guardado exitosamente."]);
    } else {
        echo json_encode(["message" => "Error al guardar el registro: " . $stmtInsert->error]);
    }

    // Cerrar la conexión
    $stmtInsert->close();
    $connInf->close();
} catch (Exception $e) {
    echo json_encode([
        "message" => "Errores al Insertar el Registro",
        "code" => $e->getCode(),
        "error" => $e->getMessage()
    ]);
}