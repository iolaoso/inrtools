<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';

header('Content-Type: application/json');

// Verificar si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesa la solicitud // Recibir los datos del formulario
    $user = isset($_POST['user']) ? $_POST['user'] : null;
    $dir = isset($_POST['dir']) ? $_POST['dir'] : null;
    $dirid = isset($_POST['dirid']) ? $_POST['dirid'] : null;
    $catid = isset($_POST['tbnCatid']) || $_POST['tbnCat'] == "" ? $_POST['tbnCatid'] : 0;
    $newcat = isset($_POST['tbnewCat']) ? mb_strtoupper($_POST['tbnewCat']) : null;
    $newsubCat = isset($_POST['tbnewSubCat']) ? mb_strtoupper($_POST['tbnewSubCat']) : null;
    $reqOficio = isset($_POST['choficio']) && $_POST['choficio'] === "on" ? 1 : 0;
    $reqcomentario = isset($_POST['chcomentario']) && $_POST['chcomentario'] === "on" ? 1 : 0;
    $complejidad = isset($_POST['cbComplejidad']) ? mb_strtoupper($_POST['cbComplejidad']) : null;
    // Obtener la fecha y hora actual
    $fecha_actual = date('Y-m-d H:i:s');
    $transaccion = 'Insert Cat';
    $estRegistro = 1;
    global $conn; // Usar la conexión global

    if ($catid == 0) {
        $sql = "INSERT INTO gestioninrcategoria (COD_DIRECCION, CATEGORIA, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) VALUES (?, ?, ?, ?, ?, ?)";
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        // Asumiendo que las variables están definidas
        $stmt->bind_param("isssss", $dirid, $newcat, $estRegistro, $user, $fecha_actual, $fecha_actual);
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Recuperar el ID insertado
            $codCategoriaInsertado = $conn->insert_id;
            $sql = "INSERT INTO gestioninrsubcategoria (COD_CATEGORIA, SUBCATEGORIA, OFICIO, COMENTARIO, COMPLEJIDAD, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) VALUES (?,?,?,?,?,?,?,?,?)";
            // Preparar la consulta
            $stmt = $conn->prepare($sql);
            // Asumiendo que las variables están definidas
            $stmt->bind_param("issssssss", $codCategoriaInsertado, $newsubCat, $reqOficio, $reqcomentario, $complejidad, $estRegistro, $user, $fecha_actual, $fecha_actual);
            if ($stmt->execute()) {
                $response = [
                    'status' => 'Success',
                    'message' => 'Categoria y Subcategoria Insertadas',
                    'data' => [
                        'user' => $user,
                        'category' => $newcat,
                        'subCategory' => $newsubCat
                    ]
                ];
            } else {
                $response = [
                    'status' => 'Error',
                    'message' => 'Error al Insertar Subcategoria'
                ];
            }
        } else {
            $response = [
                'status' => 'Error',
                'message' => 'Error al Insertar Categoria'
            ];
        }
        // Cerrar la declaración
        $stmt->close();
    } else {
        $sql = "INSERT INTO gestioninrsubcategoria (COD_CATEGORIA, SUBCATEGORIA, OFICIO, COMENTARIO, COMPLEJIDAD, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) VALUES (?,?,?,?,?,?,?,?,?)";
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        // Asumiendo que las variables están definidas
        $stmt->bind_param("issssssss", $catid, $newsubCat, $reqOficio, $reqcomentario, $complejidad, $estRegistro, $user, $fecha_actual, $fecha_actual);
        if ($stmt->execute()) {
            $response = [
                'status' => 'Success',
                'message' => 'SubCategoria Insertada Correctamente',
                'data' => [
                    'user' => $user,
                    'category' => $newcat,
                    'subCategory' => $newsubCat
                ]
            ];
        } else {
            $response = [
                'status' => 'Error',
                'message' => 'Error al Insertar Subcategoria'
            ];
        }
    }
} else {
    $response = [
        'status' => 'Error',
        'message' => 'Método no permitido.',
    ];
}
echo json_encode($response);