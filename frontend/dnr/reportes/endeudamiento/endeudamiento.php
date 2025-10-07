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
                <h1 class="display-6 tituloPagina">Endeudamiento del SFPS</h1>
                <p>Reportes de Endeudamiento del Sector Financiero Popular y Solidario</p>
            </div>
            <section class="row align-items-stretch">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Reportes Disponibles</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaRepBrechas">
                                        <thead class="text-center">
                                            <tr>
                                                <th>N. Mes</th>
                                                <th>Año</th>
                                                <th>Mes</th>
                                                <th>Archivo</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reportTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    const carpetaReportes = 'assets/files/reportes/endeudamiento';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/listarArchivos.js"></script>

</body>

</html>