<?php
include_once __DIR__ . '/../../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/indRanking/indrankingList.php'; // consulta el reporte de calificación 

$datosRiesgo = obtenerRiesgoEntidad();
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
            <div class="row align-items-start mb-1">
                <h1 class="display-6 tituloPagina">Reporte de Calificación de Riesgo</h1>
                <!-- Columna izquierda -->
                <div class="col-md-12">
                    <p>El reporte de la calificación de riesgo de coacs y mutualistas calculado según la "Guía metodológica de la calificación de riesgo para ESFPS" v3.0 de ago2026 contiene:</p>
                    <ol>
                        <li>Vista rápida de la calificación: última calificación de riesgo disponible.</li>
                        <li>Análisis de la calificación: comparación de la calificación, gráficos de la evolución de la calificación, desglose de la calificación por indicadores según sus umbrales, gráficos de la evolución de los indicadores y reporte de resultados. </li>
                        <li>Indicadores a nivel de cuentas de la calificación: Desglose de los indicadores a nivel de cuentas del CUC de la calificación de riesgo.</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-start mb-3">
                <div class="col-md-12">
                    <!-- Nota de confidencialidad de la información -->
                    <?php include BASE_PATH . 'frontend/partials/confidentiality.php'; ?>
                </div>
            </div>

            <!-- Visualizacion de la calificacion  -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>1. Vista rápida de la calificación</h4>
                            <p>Vista rápida de la calificación de riesgo a la última fecha de corte disponible.</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 600px;">
                                <table id="tablaRankingCalf" class="table table-sm table-bordered table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>RUC</th>
                                            <th>Entidad</th>
                                            <th>Fecha de Corte</th>
                                            <th>Segmento</th>
                                            <th>Estado actual</th>
                                            <th>1.1. Nivel de riesgo</th>
                                            <th>1.2. Administración de riesgo</th>
                                            <th>1.3. Causales normativas</th>
                                            <th>1.4. Criterio de Solvencia</th>
                                            <th>1. Calificación de riesgo</th>
                                            <th>Metodología</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyRankingCalf">
                                        <?php foreach ($datosRiesgo['data'] as $riesgoEntidad): ?>
                                            <tr>
                                                <td><?= h($riesgoEntidad['ruc']) ?></td>
                                                <td><?= h($riesgoEntidad['nom_inst']) ?></td>
                                                <td><?= h($riesgoEntidad['fecha_balance']) ?></td>
                                                <td><?= h($riesgoEntidad['seg_comyf']) ?></td>
                                                <td><?= h($riesgoEntidad['estado_actual']) ?></td>
                                                <td><?= h($riesgoEntidad['c_n_cr_nivel']) ?></td>
                                                <td><?= h($riesgoEntidad['c_calidad_adm_riesgo']) ?></td>
                                                <td><?= h($riesgoEntidad['causal_liquidacion_cat']) ?></td>
                                                <td><?= h($riesgoEntidad['criterio_solv_seps_cat']) ?></td>
                                                <td style="<?= claseNivelRiesgo($riesgoEntidad['calif_riesgo_def_categ_unif']) ?>" class="text-center fw-bold">
                                                    <?= h($riesgoEntidad['calif_riesgo_def_categ_unif']) ?></td>
                                                <td><?= h($riesgoEntidad['metodologia_unif']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                    </div>
                </div>
            </section>

            <!-- 2. Análisis de la calificación -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>2. Análisis de la calificación</h4>
                            <p>Reporte histórico del análisis de la calificación de riesgo de coacs y mutualistas que contiene: comparación de la calificación, gráficos de la evolución de la calificación, desglose de la calificación por indicadores según sus umbrales, gráficos de la evolución de los indicadores y reporte de resultados.</p>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaRepCalfRiegso">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Fecha Corte</th>
                                                <th>Fecha Carga</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyRepCalfRiegso">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Indicadores a nivel de cuentas de la calificación -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>3. Indicadores a nivel de cuentas de la calificación</h4>
                            <p>Reporte histórico de los indicadores a nivel de cuentas de la calificación de riesgo de coacs que contiene: nombre, definición, fórmula de cálculo, estructura del indicador: numerador y denominador por: desglose de cuentas del CUC, aplicabilidad y saldo.</p>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaRepIndCalfRiesgo">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Fecha Corte</th>
                                                <th>Fecha Carga</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyRepIndCalfRiesgo">
                                        </tbody>
                                    </table>
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
    const carpetaReportes = 'assets/files/reportes/indRanking/05 Reporte de calificación';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/indRanking/listFilesIndRanking.js"></script>
</body>
</html>