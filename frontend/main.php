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

        <div class="row mb-4">

            <div class="col-12">

                <div class="card border-primary shadow-sm">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-md-2 text-center">

                                <img
                                    src="<?= $base_url ?>/assets/images/seps_large.jpg"
                                    alt="INRTools"
                                    class="img-fluid rounded"
                                    style="max-height:140px;box-shadow:0 4px 10px rgba(0,0,0,.25);">

                            </div>

                            <div class="col-md-10">

                                <h1 class="display-6 tituloPagina mb-2">
                                    Bienvenido a INRTools
                                </h1>

                                <p class="lead mb-3">
                                    <?= $nombre ?>
                                </p>

                                <p class="text-muted mb-3">
                                    Plataforma institucional para la gestión, monitoreo, análisis y supervisión basada en riesgos.
                                </p>

                                <div class="d-flex flex-wrap gap-2">

                                    <span class="badge bg-primary fs-6">
                                        Usuario: <?= $nickname ?>
                                    </span>

                                    <span class="badge bg-success fs-6">
                                        Rol: <?= $rol_nombre ?>
                                    </span>

                                    <span class="badge bg-warning text-dark fs-6">
                                        Dirección: <?= $direccion ?>
                                    </span>

                                    <span class="badge bg-secondary fs-6">
                                        <?= $ambiente ?>
                                    </span>
                                    
                                    <span class="badge bg-secondary fs-6">
                                        Versión 2.0
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

        <div class="row mb-4">

            <div class="col-12">

                <div class="card border-info shadow-sm">

                    <div class="card-header bg-info text-white">
                        Direcciones de la Intendencia Nacional de Riesgos
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">

                                <div class="card border-primary h-100">

                                    <div class="card-body">

                                        <h4>⚠️ DNR</h4>

                                        <h6 class="text-muted">
                                            Dirección Nacional de Riesgos
                                        </h6>

                                        <p class="mb-0">
                                            Gestiona herramientas destinadas al monitoreo, evaluación y análisis de riesgos financieros y no financieros.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="card border-success h-100">

                                    <div class="card-body">

                                        <h4>📊 DNSES</h4>

                                        <h6 class="text-muted">
                                            Supervisión Extra Situ
                                        </h6>

                                        <p class="mb-0">
                                            Administra herramientas de análisis institucional y monitoreo sectorial para la supervisión extra situ.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="card border-warning h-100">

                                    <div class="card-body">

                                        <h4>🔎 DNS</h4>

                                        <h6 class="text-muted">
                                            Dirección Nacional de Seguimiento
                                        </h6>

                                        <p class="mb-0">
                                            Facilita el seguimiento de procesos, hallazgos, empresas auxiliares y actividades de supervisión.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="card border-danger h-100">

                                    <div class="card-body">

                                        <h4>🛡️ DNPLA</h4>

                                        <h6 class="text-muted">
                                            Prevención LA/FT
                                        </h6>

                                        <p class="mb-0">
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

        <!-- ========================================= -->
        <!-- MÓDULOS DISPONIBLES -->
        <!-- ========================================= -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card border-secondary shadow-sm">

                    <div class="card-header bg-secondary text-white">
                        Módulos Disponibles
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <div class="fs-1">📈</div>
                                        <h5>INR</h5>
                                        <small class="text-muted">
                                            Indicadores, reportes y calificaciones.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <div class="fs-1">⚠️</div>
                                        <h5>DNR</h5>
                                        <small class="text-muted">
                                            Gestión integral de riesgos.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="fs-1">🔎</div>
                                        <h5>DNS</h5>
                                        <small class="text-muted">
                                            Seguimiento y control institucional.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="fs-1">📊</div>
                                        <h5>DNSES</h5>
                                        <small class="text-muted">
                                            Supervisión extra situ.
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- SERVICIOS -->
        <!-- ========================================= -->

        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="card border-info shadow-sm h-100">

                    <div class="card-header bg-info text-white">
                        Servicios Disponibles
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">
                            <li>Administración de Usuarios</li>
                            <li>Administración de Roles</li>
                            <li>Gestión de Permisos</li>
                            <li>Bitácora Institucional</li>
                            <li>Monitoreo de Backups</li>
                            <li>Validación de Estructuras</li>
                        </ul>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card border-secondary shadow-sm h-100">

                    <div class="card-header bg-secondary text-white">
                        Novedades del Sistema
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">
                            <li>✅ Menú dinámico basado en Base de Datos</li>
                            <li>✅ Gestión visual de permisos</li>
                            <li>✅ Control de acceso por roles y direcciones</li>
                            <li>✅ Administración centralizada de usuarios</li>
                            <li>✅ Monitoreo institucional de respaldos</li>
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