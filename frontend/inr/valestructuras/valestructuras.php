<?php
// acivar la extension ZIP en el archivo php.ini

include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas
include BASE_PATH . 'backend/valEstructuras/listValestructuras.php'; // Incluir el archivo de consultas

$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);

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
                <div class="col-md-4 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Datos de la Solicitud</h4>
                        </div>
                        <div class="card-body">
                            <div class="container border mb-3 p-3">
                                <div class="mb-3 row ">
                                    <h2>Reporte ValEst</h2>
                                </div>
                                <div class="mb-3 row">
                                    <a type="button" id="btValEstExcel" class="btn btn-warning"
                                        href="<?php echo $base_url; ?>/assets/files/reportes/valestructuras/VALEST_v2_2.xlsm">
                                        <i class="fas fa-download"></i>
                                        Descargar Archivo de Excel
                                    </a>
                                </div>
                            </div>
                            <div class="container border mb-3 p-3">
                                <form id="formEstructuras" method="POST">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">RUC</span>
                                        </div>
                                        <input type="text" class="form-control" id="ruc" name="ruc" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#catastroModal">Buscar</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-search"></i>
                                                Consultar Datos de la Entidad
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mb-3">
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
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" id="tablaReportes">
                                    <thead>
                                        <tr>
                                            <th>RUC_ENTIDAD</th>
                                            <th>RAZON_SOCIAL</th>
                                            <th>SEGMENTO</th>
                                            <th>NVL_RIESGO</th>
                                            <th>ESTRUCTURA</th>
                                            <th>NOM_ESTRUCTURA</th>
                                            <th>CUMPLE</th>
                                            <th>MAX_FECHA_CORTE</th>
                                            <th>MAX_FECHA_VALIDACION</th>

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

    <!-- ventana de carga de datos -->
    <?php include BASE_PATH . 'frontend/partials/loadingDiv.php'; ?>

    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/valestructuras/valestructuras.js"></script>
</body>

</html>