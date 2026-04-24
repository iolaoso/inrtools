<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/infinr_connection.php';

// Verificar si se ha proporcionado un ID
if (isset($_GET['id']) && isset($_GET['delUser'])) {
    $id = (int)$_GET['id'];
    $delUser = $_GET['delUser'];
    // Recibir el ID de la estructura a eliminar
    if ($id) {
        // Preparar la consulta de eliminación (marcando como eliminado)
        $sql = "UPDATE T_INFORMES SET EST_REGISTRO = 'INA', deletedAt = NOW(), deletedUser = ? WHERE COD_INFORME = ?";
        $stmt = $connInf->prepare($sql);
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

    $connInf->close();
} else {
    echo "Método no permitido.";
}
