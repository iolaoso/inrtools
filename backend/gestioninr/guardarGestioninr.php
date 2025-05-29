<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';

header('Content-Type: application/json');

// Verificar si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesa la solicitud // Recibir los datos del formulario
    $idGestion = isset($_POST['codGestion']) ? $_POST['codGestion'] : null;
    $direccionId = isset($_POST['direccionid']) ? $_POST['direccionid'] : null;
    $ruc = isset($_POST['ruc']) ? $_POST['ruc'] : null;
    $categoria = isset($_POST['cbCategoria']) ? $_POST['cbCategoria'] : null;
    $subCategoria = isset($_POST['cbSubCategoria']) ? $_POST['cbSubCategoria'] : null;
    $fechaOficio = isset($_POST['fechaOficio']) ? $_POST['fechaOficio'] : null;
    $oficio = isset($_POST['oficio']) ? $_POST['oficio'] : null;
    $analista = isset($_POST['analista']) ? $_POST['analista'] : null;
    $comentario = isset($_POST['tbcomentario']) ? $_POST['tbcomentario'] : null;
    // Obtener la fecha y hora actual
    $fecha_actual = date('Y-m-d H:i:s');
    $transaccion = 'SIN TRANSACCION';
    // Verificar si es un insert o un update
    global $conn; // Usar la conexión global
    if ($idGestion) {
        // Es un update
        $sql = "UPDATE gestioninr SET COD_CATEGORIA = ?, COD_SUBCATEGORIA = ?, FECHA_OFIC_TRAM = ?, OFICIO_TRAMITE = ?, ANALISTA = ?, COMENTARIO = ?, FECHA_ACTUALIZACION = ? WHERE COD_GESTION = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $categoria,
            $subCategoria,
            $fechaOficio,
            $oficio,
            $analista,
            $comentario,
            $fecha_actual,
            $idGestion
        );
        $transaccion = 'Update';
    } else {
        // Es un insert
        $estRegistro = 1;
        $sql = "INSERT INTO gestioninr (COD_CATEGORIA, COD_SUBCATEGORIA, FECHA_REGISTRO, ANALISTA, RUC_ENTIDAD, FECHA_OFIC_TRAM, OFICIO_TRAMITE, COMENTARIO, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssss", $categoria, $subCategoria, $fecha_actual, $analista, $ruc, $fechaOficio, $oficio,  $comentario, $estRegistro, $analista, $fecha_actual, $fecha_actual);
        $transaccion = 'Insert';
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response = [
            'status' => 'Success',
            'transaccion' => $transaccion,
            'message' => 'Datos guardados correctamente',
        ];
    } else {
        $response = [
            'status' => 'Error',
            'transaccion' => $transaccion,
            'message' => 'Error al Guardar los datos',
        ];
    }
    // Cerrar la conexión
    $stmt->close();
    $conn->close();
    echo json_encode($response);
} else {
    echo "Método no permitido.";
}
