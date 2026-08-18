<?php
include_once __DIR__ . '/../../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>
    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">SSI - Seguimiento del SFPS</h1>
                <p>Reportes relacionados con el Sistema de seguimiento integral</p>
            </div>
            <!-- Reportes Cubos SSI -->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Reportes Cubos SSI</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaOtrosSsi">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyOtrosSsi">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Plan de Trabajo -->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #059b87ff;">
                            <h4>Plan de Trabajo</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaPlanTrabajoSsi">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyPlanTrabajoSsi">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- plan de Acción -->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>Plan de Acción</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaPlanAccionSsi">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyPlanAccionSsi">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </div>
    </section>
    </main>
    </div>

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script>
    // Define la carpeta que deseas usar
    const carpetaReportes = 'assets/files/reportes/ssi';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/listFilesSSI.js"></script>
</body>

</html>