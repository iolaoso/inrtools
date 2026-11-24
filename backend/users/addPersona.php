<?php

// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';
include_once '../session.php';

$fecha_actual = date('Y-m-d H:i:s');

// Manejo de solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPersona      = $_POST['idPersona'] ?? '';
    $identificacion = trim($_POST['identificacion'] ?? '');
    $nombre         = mb_strtoupper(trim($_POST['nombre'] ?? ''));
    $email          = trim($_POST['email'] ?? '');
    $inrdireccion_id = $_POST['inrdireccion_id'] ?? '';
    $usrCreacion = mb_strtoupper($_SESSION['nickname']);

    if (
        !empty($identificacion) &&
        !empty($nombre) &&
        !empty($email) &&
        !empty($inrdireccion_id)
    ) {

        if (!empty($idPersona)) {

            // UPDATE
            $sql = "UPDATE personas
                    SET identificacion = ?,
                        nombre = ?,
                        email = ?,
                        inrdireccion_id = ?,
                        updatedAt = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Error en la preparación de la consulta: ' . $conn->error
                ]);
                exit();
            }

            $stmt->bind_param(
                "sssssi",
                $identificacion,
                $nombre,
                $email,
                $inrdireccion_id,
                $fecha_actual,
                $idPersona
            );

        } else {

            // INSERT
            $sql = "INSERT INTO personas (
                        identificacion,
                        nombre,
                        email,
                        inrdireccion_id,
                        estRegistro,
                        UsrCreacion,
                        createdAt,
                        updatedAt
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Error en la preparación de la consulta: ' . $conn->error
                ]);
                exit();
            }

            $estRegistro = 1;

            $stmt->bind_param(
                "ssssssss",
                $identificacion,
                $nombre,
                $email,
                $inrdireccion_id,
                $estRegistro,
                $usrCreacion,
                $fecha_actual,
                $fecha_actual
            );
        }

        try {

            if ($stmt->execute()) {

                echo json_encode([
                    'success' => true,
                    'message' => !empty($idPersona) ? 'Persona actualizada correctamente.': 'Persona registrada correctamente.'
                ]);

            } else {

                echo json_encode([
                    'success' => false,
                    'error' => $stmt->error
                ]);

            }

        } catch (mysqli_sql_exception $e) {

            echo json_encode([
                'success' => false,
                'error' => 'Error al guardar la información: ' . $e->getMessage()
            ]);

        }

        $stmt->close();

    } else {

        echo json_encode([
            'success' => false,
            'error' => 'Faltan datos necesarios.'
        ]);

    } 

    $conn->close();
    exit();
}
?>