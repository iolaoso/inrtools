<?php

include_once __DIR__ . '/../backend/config.php';
include BASE_PATH . 'backend/session.php';

?>

<!DOCTYPE html>
<html lang="es">

<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>

<?php include_once BASE_PATH . 'frontend/partials/header.php'; ?>

<div class="d-flex">

    <?php include_once BASE_PATH . 'frontend/partials/sidebar.php'; ?>

    <main class="content p-3" id="main-content">

        <!-- ========================================= -->
        <!-- BIENVENIDA -->
        <!-- ========================================= -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="card border-primary shadow-sm">
                    <div class="card-body py-2">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <img
                                    src="<?= $base_url ?>/assets/images/seps_large.jpg"
                                    alt="INRTools"
                                    class="img-fluid rounded"
                                    style="max-height:70px;">
                            </div>
                            <div class="col-md-8">
                                <h3 class="mb-1">
                                    Bienvenido a INRTools
                                </h3>
                                <div class="fw-semibold">
                                    <?= $nombre ?>
                                </div>
                                <small class="text-muted">
                                    Plataforma institucional para la gestión, monitoreo, análisis y supervisión basada en riesgos.
                                </small>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="small text-muted">
                                    <?= $rol_nombre ?> · <?= $direccion ?>
                                </div>
                                <div class="small text-muted">
                                    <?= $nickname ?>
                                </div>
                                <div class="mt-1">
                                    <span class="badge <?= ($ambiente === 'Desarrollo') ? 'bg-warning text-dark' : 'bg-success' ?>">
                                        <?= strtoupper($ambiente) ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        v2.0
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================= -->
        <!-- DIRECCIONES INR -->
        <!-- ========================================= -->

        <div class="row mb-2">
            <div class="col-12">
                <div class="card border-info shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        Direcciones de la Intendencia Nacional de Riesgos
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="card border-primary h-100">
                                    <div class="card-body py-2">
                                        <h5 class="mb-1">⚠️ DNR</h5>
                                        <h6 class="text-muted mb-2">
                                            Dirección Nacional de Riesgos
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Gestiona herramientas destinadas al monitoreo, evaluación y análisis de riesgos financieros y no financieros.
                                        </p>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-success h-100">
                                    <div class="card-body py-2">
                                        <h4 class="mb-1">📊 DNSES</h4>
                                        <h6 class="text-muted mb-2">
                                            Supervisión Extra Situ
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Administra herramientas de análisis institucional y monitoreo sectorial para la supervisión extra situ.
                                        </p>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning h-100">
                                    <div class="card-body py-2">
                                        <h4 class="mb-1">🔎 DNS</h4>
                                        <h6 class="text-muted mb-2">
                                            Dirección Nacional de Seguimiento
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Facilita el seguimiento de procesos, hallazgos, empresas auxiliares y actividades de supervisión.
                                        </p>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-danger h-100">
                                    <div class="card-body py-2">
                                        <h4 class="mb-1">🛡️ DNPLA</h4>
                                        <h6 class="text-muted mb-2">
                                            Prevención LA/FT
                                        </h6>
                                        <p class="card-text small mb-0">
                                            Apoya la gestión de controles preventivos relacionados con lavado de activos y financiamiento de delitos.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <!-- ========================================= -->
            <!-- SERVICIOS -->
            <!-- ========================================= -->
            <div class="col-md-6">
                <div class="card border-info shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        Servicios Disponibles
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li>📈 IndRanking y Calif. de Riesgo</li>
                            <li>⚠️ Análisis de Riesgos</li>
                            <li>🔎 Seguimiento y Hallazgos</li>
                            <li>📑 Reportes, Diagnósticos y Boletines INR</li>
                            <li>⚙️ Administración, Bitácoras y Permisos</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
               <div class="card border-secondary shadow-sm h-100">
                    <div class="card-header bg-warning text-white">
                        Novedades del Sistema
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li>✅ Menú dinámico basado en BDD</li>
                            <li>✅ Gestión de permisos</li>
                            <li>✅ Control de acceso</li>
                            <li>✅ Administración de usuarios</li>
                            <li>✅ Monitoreo de respaldos</li>
                        </ul>
                    </div>
                </div>
            </div> 
        </div>
    </main>
</div>

<?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>
<?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

</body>
</html>