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
                <h1 class="display-6 tituloPagina">RIESGO DE INTERCONEXIÓN</h1>
                <p>Alertas relacionados con la interconexión de las Entidades</p>
            </div>
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <!-- Reportes de Diagnóstico -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Base Cálculo Ranking Total</h4>
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
        const folderBaseCalRankTot = 'assets/files/reportes/alertas/riesgoInterconexion/BaseCalRankTot';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/listFilesDiagnostico.js"></script>
</body>

</html>