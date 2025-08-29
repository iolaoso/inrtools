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
                <h1 class="display-6 tituloPagina">RIESGO DE CRÉDITO</h1>
                <p>Alertas relacionados con el Riesgo de Crédito</p>
            </div>
            <section class="row align-items-stretch mb-4">
                <!-- Perdidas Esperadas -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Perdidas Esperadas</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaPerdidasEsperadas">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyPerdidasEsperadas">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Perdidas Esperadas Matriz de trasnsicion -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>Perdidas Esperadas - Matriz de transición</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaPerdidasEsperadas">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyPerdidasEsperadasMT">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Monitoreo Mora  -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Monitoreo Mora</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaMonitoreoMora">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyMonitoreoMora">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Reporte Cosechas Cubo -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>Reporte Cosechas Cubo</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaReporteCosechasCubo">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCosechas">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Cartera, Socios y Clientes -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header  bg-info  text-white">
                            <h4>Cartera, Socios y Clientes</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaCarteraSociosClientes">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCartSociosCli">
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
    const carpetaReportes = 'assets/files/reportes/alertas/riesgoCredito';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/alertas/listFilesRiesgoCredito.js"></script>
</body>

</html>