<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas
include BASE_PATH . 'backend/informesinr/informesinrList.php'; // Incluir el archivo de consultas

$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);
$tiposInf = obtenerTiposInforme();

$rolesDireccion = [
    'SUPERUSER',
    'ADMINISTRADOR',
    'DIRECTOR',
    'DIRADMINDR',
    'DIRADMINDNS',
    'DIRADMINDNSES',
    'DIRADMINPLA'
];

// Obtener registros filtrados por usuario
if (in_array($rol_nombre, $rolesDireccion)) {
    $result = obtenerInformesInrFull();
} else {
    $result = obtInformesInrUsr($nickname);
}

?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>

    <!-- Incluir el Header -->
    <?php include_once BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <!-- Incluir el Sidebar -->
        <?php include_once BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Informes INR - Reporte General</h1>
                <p>Reporte de los informes realizadas por los Analistas</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div
                            class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Registros Analista</h4>
                            <button id="btnInfFormBack" class="btn btn-warning btn-sm">Regresar al Formulario</button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm" style="font-size: 6px;"
                                        id="tablaInfRepGen">
                                        <thead>
                                            <tr>
                                                <th>COD_INFORME</th>
                                                <th>RUC_ENTIDAD</th>
                                                <th>RAZON_SOCIAL</th>
                                                <th>COD_TIPO_INFORME</th>
                                                <th>TIPO_INFORME</th>
                                                <th>AREA_REQUIRIENTE</th>
                                                <th>FECHA_ASIGNACION</th>
                                                <th>ANIO_ASIGNACION</th>
                                                <th>FECHA_SOLICITUD_REVISION</th>
                                                <th>ANIO_SOLREV</th>
                                                <th>COD_ESTADO</th>
                                                <th>ESTADO</th>
                                                <th>NUM_INFORME</th>
                                                <th>FECHA_INFORME</th>
                                                <th>ANIO_INFORME</th>
                                                <th>NUM_MEMORANDO</th>
                                                <th>FECHA_MEMORANDO</th>
                                                <th>ANIO_MEMO</th>
                                                <th>FECHA_CARGA_COMPARTIDA</th>
                                                <th>ANIO_CARGACOMP</th>
                                                <th>OBSERVACIONES</th>
                                                <th>EST_REGISTRO</th>
                                                <th>ANALISTA</th>
                                                <th>FECHA_CREACION</th>
                                                <th>FECHA_ACTUALIZACION</th>
                                                <th>LINEA_BASE</th>
                                                <th>USR_IDEN</th>
                                                <th>USR_DIRECCION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $informeinr): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['COD_INFORME'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($informeinr['RUC_ENTIDAD'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($informeinr['NOMBRE_CORTO'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['COD_TIPO_INFORME'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($informeinr['TIPO_INFORME'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($informeinr['AREA_REQUIRIENTE'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_ASIGNACION'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['ANIO_ASIGNACION'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_SOLICITUD_REVISION'] ?? '') ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['ANIO_SOLREV'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['COD_ESTADO'] ?? '') ?></td>
                                                    <td
                                                        class="<?= htmlspecialchars($informeinr['ESTADO'] ?? '') == 'PENDIENTE' ? 'text-danger' : 'text-success' ?>">
                                                        <?= htmlspecialchars($informeinr['ESTADO'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['NUM_INFORME'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_INFORME'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['ANIO_INFORME'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['NUM_MEMORANDO'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_MEMORANDO'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['ANIO_MEMO'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_CARGA_COMPARTIDA'] ?? '') ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['ANIO_CARGACOMP'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($informeinr['OBSERVACIONES'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['EST_REGISTRO'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($informeinr['ANALISTA'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_CREACION'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['FECHA_ACTUALIZACION'] ?? '') ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['LINEA_BASE'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['USR_IDEN'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['USR_DIRECCION'] ?? '') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
                                </div>
                            </div>
                        </div>
            </section>

        </main>


    </div>


    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/informesinr/infRepGeneral.js"></script>
</body>

</html>