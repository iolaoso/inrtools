<?php
include_once __DIR__ . '/../../../backend/config.php';
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

        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Validación de Estructuras</h1>
                <p>Validación de Fechas de envío de Estructuras</p>
            </div>
            <section class="row align-items-stretch">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Datos de la Solicitud</h4>
                        </div>
                        <div class="card-body">
                            <form id="formEstructuras" method="POST">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RUC</span>
                                    </div>
                                    <input type="text" class="form-control" id="ruc" name="ruc" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#catastroModal">Buscar</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Consultar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div
                            class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Reportes del Analista</h4>
                            <ul class="list-unstyled d-flex mb-0">
                                <li class="mx-2">
                                    <button id="exportButton" class="btn btn-primary btn-sm"
                                        onclick="exportTableToExcel('tablaReportes', 'ReporteEstructuras')">Exportar</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <input class="form-control" type="text" id="searchInput"
                                onkeyup="filterTable('tablaReportes')" placeholder="Buscar...">
                            <div class="table-container" style="max-height: 700px; overflow-y: auto;">
                                <table class="table mt-4" id="resultTable">
                                    <thead>
                                        <tr>
                                            <th>COD. ESTRUCTURA</th>
                                            <th>NOMBRE ESTRUCTURA</th>
                                            <th>FECHA CORTE</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultBody">
                                        <!-- Los resultados se insertarán aquí -->
                                    </tbody>
                                </table>
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
    <script src="<?php echo $base_url; ?>/assets/js/valestructuras/valestructuras.js"></script>
</body>

</html>