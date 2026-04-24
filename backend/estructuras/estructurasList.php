<?php
include_once BASE_PATH . 'backend/conexiones/db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

function obtenerEstructurasPorUsuario($nickname)
{
    global $conn; // Usar la conexión global
    $sql = "SELECT id
                ,solicitante
                ,direccion_solicitante
                ,ruc
                ,estructura
                ,DATE(fechaCorte) as fechaCorte
                ,DATE(fecha_solicitud) as fecha_solicitud
                ,estado
                ,DATE(fechaInicio) as fechaInicio
                ,DATE(fechaFin) as fechaFin
                ,detalle
                ,analista_ejecutante
                ,estRegistro
                ,UsrCreacion
                ,DATE(deletedAt) AS deletedAt
                ,DATE(createdAt) AS createdAt
                ,DATE(updatedAt) AS updatedAt
            FROM estructuras WHERE estRegistro = 1 AND analista_ejecutante = ?
            ORDER BY CASE 
                        WHEN estado = 'pendiente' THEN 0 
                        ELSE 1 
                    END, id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerEstructurasFull()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT id
                ,solicitante
                ,direccion_solicitante
                ,ruc
                ,estructura
                ,DATE(fechaCorte) as fechaCorte
                ,DATE(fecha_solicitud) as fecha_solicitud
                ,estado
                ,DATE(fechaInicio) as fechaInicio
                ,DATE(fechaFin) as fechaFin
                ,detalle
                ,analista_ejecutante
                ,estRegistro
                ,UsrCreacion
                ,DATE(deletedAt) AS deletedAt
                ,DATE(createdAt) AS createdAt
                ,DATE(updatedAt) AS updatedAt
            FROM estructuras WHERE estRegistro = 1
            ORDER BY CASE 
                        WHEN estado = 'pendiente' THEN 0 
                        ELSE 1 
                    END, id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerEstructuraPorId($id)
{
    global $conn; // Asegúrate de tener acceso a la conexión de la base de datos
    $stmt = $conn->prepare("SELECT id
                                ,solicitante
                                ,direccion_solicitante
                                ,ruc
                                ,estructura
                                ,DATE(fechaCorte) as fechaCorte
                                ,DATE(fecha_solicitud) as fecha_solicitud
                                ,estado
                                ,DATE(fechaInicio) as fechaInicio
                                ,DATE(fechaFin) as fechaFin
                                ,detalle
                                ,analista_ejecutante
                                ,estRegistro
                                ,UsrCreacion
                                ,DATE(deletedAt) AS deletedAt
                                ,DATE(createdAt) AS createdAt
                                ,DATE(updatedAt) AS updatedAt
                            FROM estructuras WHERE estRegistro = 1 AND id = ?
                            ORDER BY CASE 
                                        WHEN estado = 'pendiente' THEN 0 
                                        ELSE 1 
                                    END, id DESC");

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Extraer el registro como un array asociativo
        if ($estructuraData = $result->fetch_assoc()) {
            return $estructuraData; // Devuelve el array asociativo
        }
    }

    return null; // Devuelve null si no hay resultados o si hubo un error
}

function obtenerCatalogoEstructuras()
{
    global $conn; // Usar la conexión global
    $sql = "SELECT DISTINCT(estructura) AS estNombre
            FROM estructuras WHERE estRegistro = 1
            ORDER BY 1 ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}
