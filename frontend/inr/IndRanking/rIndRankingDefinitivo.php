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

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Ind Ranking Definitivo  <span style="color: #16c224; font-size: 0.8em">(incluye calificación de riesgo)</span></h1>
                <p>Bases de datos de cuentas, indicadores financieros, promedios y calificación de riesgo que se generan en la INR.</p>
            </div>
            
            <!-- Nota de confidencialidad de la información -->
            <?php include BASE_PATH . 'frontend/partials/confidentiality.php'; ?>            
            
            <!-- 1. Ind Ranking COACS ACTIVAS Definitivo XLSX -->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>1. Ind Ranking COACS ACTIVAS Definitivo (EXCEL)</h4>
                            <em>Base de datos del ind ranking histórico de coacs definitivo.</em>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="ttIndRankingCOACSPreXLSX">
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
                                        <tbody id="rTBodyIndRankingCoacsPreXLSX">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Ind Ranking MUTUALISTAS Definitivo XLSX -->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>2. Ind Ranking MUTUALISTAS Definitivo (EXCEL)</h4>
                            <em>Base de datos del ind ranking histórico de mutualistas definitivo.</em>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tIndRankingMutualistasPreXLSX">
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
                                        <tbody id="rTBodyIndRankingMutualistasPreXLSX">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
           
            <!-- 3. Ind Ranking ULTIMO BALANCE COACS Y MUTUALISTAS Definitivo XLSX-->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header text-white" style="background-color: #05829bff;">
                            <h4>3. Ind Ranking ULTIMO BALANCE COACS Y MUTUALISTAS Definitivo (EXCEL)</h4>
                            <em>Base de datos del ind ranking histórico del último balance de coacs y mutualistas definitivo.</em>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tIndRankingUltBalCoacsMutPreXLSX">
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
                                        <tbody id="rTBodyIndRankingUltBalCoacsMutPreXLSX">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Reportes de Ind Ranking COACS Definitivo DTA -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>4. Ind Ranking COACS Definitivo (STATA)</h4>
                            <em>Base de datos del ind ranking histórico de coacs definitivo.</em>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tIndRankingCOACSPreDTA">
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
                                        <tbody id="rTBodyIndRankingCoacsPreDTA">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- 5. Ind Ranking MUTUALISTAS Definitivo DTA -->
            <section class="row align-items-stretch mb-4">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>5. Ind Ranking MUTUALISTAS Definitivo (STATA)</h4>
                            <em>Base de datos del ind ranking histórico de mutualistas definitivo.</em>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tIndRankingMutualistasPreDTA">
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
                                        <tbody id="rTBodyIndRankingMutualistasPreDTA">
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
    //const carpetaReportes = '//Seps-mv-fileser/inr/Gestión de IR/DIR-NAC-RPLA/5. Productos/Reporte de Diagnostico';
    const carpetaReportes = 'assets/files/reportes/indRanking/02 Ind ranking definitivo';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/indRanking/listFilesIndRanking.js"></script>
</body>

</html>