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
            <div class="row align-items-start mb-4">
                <h1 class="display-6 tituloPagina">Reporte de Calificación de Riesgo</h1>
                <!-- Columna izquierda -->
                <div class="col-md-6">
                    <h5>1. Calificación de Riesgo</h5>
                    <p>El Reporte de la calificación de riesgo de coacs y mutualistas contiene:</p>
                    <ol>
                        <li>Comparación de la calificación de riesgo: mes anterior vs. mes actual.</li>
                        <li>Evolución de la calificación de riesgo: diciembres-actual y últimos meses-actual.</li>
                        <li>Desglose de la calificación de riesgo: por indicadores según sus umbrales y metodología utilizada.</li>
                        <li>Evolución de los indicadores del modelo de calificación de riesgo.</li>
                        <li>Reporte de resultados:
                            <ol>
                                <li>Comparación en la calificación de riesgo por categorías: mes anterior vs. mes actual.</li>
                                <li>Evolución histórica de la calificación de riesgo por categorías.</li>
                                <li>Variación de la calificación de riesgo: mes anterior vs. mes actual.</li>
                                <li>Evolución histórica de la variación de la calificación de riesgo.</li>
                                <li>Detalle de la calificación de riesgo por categorías y segmentos.</li>
                            </ol>
                        </li>
                    </ol>
                </div>

                <!-- Columna derecha -->
                <div class="col-md-6">
                    <h5>2. Indicadores de la calificación de riesgo</h5>
                    <p>El reporte de los 11 indicadores de la calificación de riesgo de coacs se desglosa en:</p>
                    <ol>
                        <li>Resoluciones del Catálogo Único de Cuentas (CUC)</li>
                        <li>Nombre del indicador</li>
                        <li>Definición</li>
                        <li>Componente</li>
                        <li>Dirección (sentido)</li>
                        <li>Tendencia</li>
                        <li>Ponderación</li>
                        <li>Resolución del CUC</li>
                        <li>Fórmula de cálculo</li>
                        <li>Datos de la entidad</li>
                        <li>Estructura del indicador: numerador y denominador por: desglose de cuentas, aplicabilidad y saldo de los 5 últimos periodos (anuales)</li>
                    </ol>
                </div>
            </div>


            <div class="row align-items-start mb-3">
                <div class="col-md-12">
                    <!-- Nota de confidencialidad de la información -->
                    <?php include BASE_PATH . 'frontend/partials/confidentiality.php'; ?>
                </div>
            </div>

            <!-- 1. Calificación de riesgo -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>1. Calificación de riesgo</h4>
                            <p>Reporte histórico de la calificación de riesgo de coacs y mutualistas (nueva 1 y nueva 2) que contiene: comparación, evolución, desglose y reporte de resultados.</p>
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

            <!-- 2. Indicadores de la calificación de riesgo -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>2. Indicadores de la calificación de riesgo</h4>
                            <p>Reporte histórico de los 11 indicadores de la calificación de riesgo de coacs (nueva 2) que contiene: desglose a nivel de cuentas del CUC.</p>
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