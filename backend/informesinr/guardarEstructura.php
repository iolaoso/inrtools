<?php
// Incluir el archivo de conexión a la base de datos
include '../conexiones/db_connection.php';

// Verificar si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $id = isset($_POST['codInforme']) ? $_POST['codInforme'] : null;
    $dirId = isset($_POST['direccionid']) ? $_POST['coddireccionidnforme'] : null;
    $direccion = isset($_POST['direccion']) ? mb_strtoupper($_POST['coddireccionidnforme'], 'UTF-8') : null;
    $ruc = isset($_POST['ruc']) ? $_POST['ruc'] : null;
    $razonSocial = isset($_POST['tbrazonSocial']) ? mb_strtoupper($_POST['tbrazonSocial'], 'UTF-8') : null;
    $codTipoInf = isset($_POST['cbTipoInforme']) ? $_POST['cbTipoInforme'] : null;
    $fasignacion = isset($_POST['fasignacion']) ? $_POST['fasignacion'] : null;
    $fsolicitaRev = isset($_POST['fsolicitaRev']) ? $_POST['fsolicitaRev'] : null;
    $numinformeFull = isset($_POST['numinformeFull']) ?  mb_strtoupper($_POST['numinformeFull'], 'UTF-8') : null;
    $fInforme = isset($_POST['fInforme']) ? $_POST['fInforme'] : null;
    $nummemorando = isset($_POST['nummemorando']) ?  mb_strtoupper($_POST['nummemorando'], 'UTF-8') : null;
    $fmemorando = isset($_POST['fmemorando']) ? $_POST['fmemorando'] : null;
    $fCargaCompartida = isset($_POST['fCargaCompartida']) ? $_POST['fCargaCompartida'] : null;
    $tbObs = isset($_POST['tbObs']) ? $_POST['tbObs'] : null;
    $cbEstado = isset($_POST['cbEstado']) ? $_POST['cbEstado'] : null;
    $analista = isset($_POST['analista']) ? mb_strtoupper($$_POST['analista'], 'UTF-8') : null;
    // Obtener la fecha y hora actual
    // configurar php.ini date.timezone = America/Guayaquil
    $fecha_actual = date('Y-m-d H:i:s');
    // linea base
    $lineaBase = 0;
    // estado Registro
    $estRegistro = 1;

    // Verificar si es un insert o un update
    if ($id) {
        // Es un update
        $sql = "UPDATE informesinr 
                SET ,COD_TIPO_INFORME=?
                    ,FECHA_SOLICITUD_REVISION=?
                    ,COD_ESTADO=?
                    ,NUM_INFORME=?
                    ,FECHA_INFORME=?
                    ,NUM_MEMORANDO=?
                    ,FECHA_MEMORANDO=?
                    ,FECHA_CARGA_COMPARTIDA=?
                    ,OBSERVACIONES=?
                    ,USR_CREACION=?
                    ,FECHA_ACTUALIZACION=?
                WHERE COD_INFORME=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssi", $codTipoInf, $fsolicitaRev, $cbEstado, $numinformeFull, $fInforme, $nummemorando, $fmemorando, $fCargaCompartida, $tbObs, $analista, $fecha_actual, $id);
    } else {
        // Es un insert

        $sql = "INSERT INTO informesinr(RUC_ENTIDAD, COD_TIPO_INFORME, FECHA_ASIGNACION, FECHA_SOLICITUD_REVISION, COD_ESTADO, NUM_INFORME, FECHA_INFORME, NUM_MEMORANDO, FECHA_MEMORANDO, FECHA_CARGA_COMPARTIDA, OBSERVACIONES, LINEA_BASE, EST_REGISTRO, USR_CREACION, FECHA_CREACION, FECHA_ACTUALIZACION) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssss", $ruc, $codTipoInf, $fasignacion, $fsolicitaRev, $cbEstado, $numinformeFull, $fInforme, $nummemorando, $fmemorando, $fCargaCompartida, $tbObs, $lineaBase, $estRegistro, $analista, $fecha_actual, $fecha_actual);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro guardado exitosamente.";
    } else {
        echo "Error al guardar el registro: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}