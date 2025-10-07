<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';

// Verificar si se ha proporcionado un ID
if (isset($_GET['id']) && isset($_GET['delUser'])) {
    $id = (int)$_GET['id'];
    $delUser = $_GET['delUser'];
    // Recibir el ID de la estructura a eliminar
    if ($id) {
        // Preparar la consulta de eliminación (marcando como eliminado)
        $sql = "UPDATE GESTIONINR SET EST_REGISTRO = 0, ESTADO = 'ELIMINADA', deletedAt = NOW(), deletedUsr = ? WHERE COD_GESTION = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $delUser, $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Registro eliminado exitosamente.";
            } else {
                echo "No se encontró el registro con el ID proporcionado.";
            }
        } else {
            echo "Error al eliminar el registro: " . $stmt->error;
        }

        // Cerrar la conexión
        $stmt->close();
    } else {
        echo "ID no proporcionado.";
    }

    $conn->close();
} else {
    echo "Método no permitido.";
}
