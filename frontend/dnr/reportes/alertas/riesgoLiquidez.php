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
                <h1 class="display-6 tituloPagina">RIESGO DE LIQUIDEZ</h1>
                <p>Alertas relacionados con Liquidez</p>
            </div>
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <!-- Reportes de Diagnóstico -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Cumplimiento Liquidez Estructural/h4>
                        </div>
                        <div class="card-body">
                            <div class="table-container" style="max-height: 300px; overflow-y: auto;">
                                <div class="table table-striped table-sm" style="font-size: 12px;"
                                    id="tablaRepDiagnostico">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyDiag">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Reportes de Diagnóstico Simplificado  -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>Reporte Liquidez Diaria</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-container" style="max-height: 300px; overflow-y: auto;">
                                <div class="table table-striped table-sm" style="font-size: 12px;"
                                    id="tablaRepDiagSimplificado">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyDiagSimpli">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Informe de Diagnóstico SNF -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Reporte Liquidez</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-container" style="max-height: 300px; overflow-y: auto;">
                                <div class="table table-striped table-sm" style="font-size: 12px;" id="tablaRepDiagSNF">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyDiagSNF">
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
        const folderCumpLiquidez = 'assets/files/reportes/alertas/riesgoLiquidez/cumpLiquidez';
        const folderLiquidezDiaria = 'assets/files/reportes/alertas/riesgoLiquidez/liquidezDiaria';
        const folderLiquidez = 'assets/files/reportes/alertas/riesgoLiquidez/liquidez';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/listFilesDiagnostico.js"></script>
</body>

</html>