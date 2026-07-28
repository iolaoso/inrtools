<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/backups/parametrosBackups.php';

$diasOk = defined('DIAS_OK') ? DIAS_OK : 0;
$diasAdvertencia = defined('DIAS_ADVERTENCIA') ? DIAS_ADVERTENCIA : 0;
$diasCritico = defined('DIAS_CRITICO') ? DIAS_CRITICO : 0;

?>

<!DOCTYPE html>
<html lang="es">

<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>

    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>
        <main class="content p-3" id="main-content">

            <!-- ========================================================= -->
            <!-- ENCABEZADO DEL MODULO                                    -->
            <!-- ========================================================= -->
            <div class="row g-3 mb-3">
                <div class="col-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-body">
                            <!-- Titulo -->
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="display-6 tituloPagina mb-1">
                                        CENTRO DE MONITOREO DE BACKUPS ILITIA
                                    </h1>
                                    <p class="text-muted mb-0">
                                        Monitoreo automático del estado de los respaldos
                                        almacenados en el servidor
                                        <strong>ilitia.seps.local</strong>.
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <!-- Barra de estado -->
                            <div class="row align-items-center">
                                <!-- Estado -->
                                <div class="col-md-6">
                                    <span class="fw-bold">
                                        Estado General:
                                    </span>
                                    <span
                                        id="estadoGeneral"
                                        class="badge bg-success ms-2">
                                        OPERATIVO
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        Última actualización: <span id="lblUltimaActualizacion">--</span>
                                    </small>
                                </div>
                                <!-- Botones -->
                                <div class="col-md-6 text-end">
                                    <button
                                        class="btn btn-primary"
                                        id="btnActualizar">
                                        <i class="fas fa-refresh"></i>
                                        Actualizar
                                    </button>
                                    <div
                                        id="spinnerCarga"
                                        class="spinner-border text-primary ms-3"
                                        role="status"
                                        style="display:none; width:1.5rem; height:1.5rem;">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- KPI PRINCIPALES                                           -->
            <!-- ========================================================= -->
            <div class="row g-3 mb-4">
                <!-- Total Bases -->
                <div class="col-md-3">
                    <div class="card border-primary shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Total Bases</small>
                                    <h2 class="mb-0 fw-bold" id="kpiTotalBases">0</h2>
                                </div>
                                <!-- Icono -->
                                <div class="fs-1 text-primary">
                                    <!-- <i class="bi bi-database"></i> -->🗄️
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">Bases monitoreadas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- OK -->
                <div class="col-md-2">
                    <div class="card border-success shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Respaldos OK (<?= $diasOk ?>d)</small>
                                    <h2 class="mb-0 fw-bold text-success" id="kpiOK">0</h2>
                                </div>
                                <div class="fs-1 text-success">
                                    </h2>
                                    <div class="fs-1 text-success">🟢</div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">Backup actualizado</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Advertencias -->
                <div class="col-md-2">
                    <div class="card border-warning shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Advertencias (<?= $diasAdvertencia ?>d)</small>
                                    <h2 class="mb-0 fw-bold text-warning" id="kpiAdvertencias">0</h2>
                                </div>
                                <div class="fs-1">🟡</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Requieren revisión</small>
                        </div>
                    </div>
                </div>
                <!-- Críticas -->
                <div class="col-md-2">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Críticas (<?= $diasCritico ?>d)</small>
                                    <h2 class="mb-0 fw-bold text-danger" id="kpiCriticas">0</h2>
                                </div>
                                <div class="fs-1">🔴</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Atención inmediata</small>
                        </div>
                    </div>
                </div>
                <!-- Espacio -->
                <div class="col-md-3">
                    <div class="card border-secondary shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Espacio Utilizado</small>
                                    <h2 class="mb-0 fw-bold" id="kpiEspacio">0 GB</h2>
                                </div>
                                <div class="fs-1">💾</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">Respaldos almacenados</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- ALERTAS + GRAFICO -->
            <!-- ===================================================== -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="card border-danger h-100">
                        <div class="card-header bg-danger text-white">Alertas</div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <div id="panelAlertas">
                                <div class="text-muted text-center">No existen alertas.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info h-100">
                        <div class="card-header bg-info text-white">Estado General</div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                             <div id="graficoEstado" style="height:250px; width:100%;">
                                <canvas id="chartEstadoBackups"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- TABLA -->
            <!-- ===================================================== -->
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <div class="card border-secondary h-100">
                        <div class="card-header bg-secondary text-white">
                            Bases de Datos
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped table-sm align-middle" id="tablaBackups">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>Base de Datos</th>    
                                            <th>Estado</th>
                                            <th>Último Backup</th>
                                            <th>Antigüedad</th>
                                            <th>Tamaño</th>
                                            <th>Tamaño Respaldos</th>
                                            <th>Número de Respaldos</th>
                                            <th>Último Archivo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyBackups">
                                        <!-- AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <!-- ===================================================== -->
    <!-- MODAL HISTORIAL -->
    <!-- ===================================================== -->
    <div class="modal fade" id="modalHistorial" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Historial de Backups</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"> </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Archivo</th>
                                    <th>Tamaño</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyHistorial">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>
    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <link rel="stylesheet" href="<?= $base_url; ?>/assets/css/backups.css">

    <script>
        const base_url = document.getElementById('base_url').dataset.baseUrl;
    </script>

    <script src="<?= $base_url; ?>/assets/js/backups/backups.js"></script>

</body>

</html>