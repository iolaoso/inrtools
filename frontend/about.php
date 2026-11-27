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
        <!-- ENCABEZADO -->
        <!-- ========================================= -->

        <div class="row mb-3">

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

                                <h1 class="display-6 tituloPagina mb-1">
                                    INRTools
                                </h1>

                                <p class="text-muted mb-2">
                                    Plataforma institucional para la gestión, monitoreo, análisis y automatización de procesos de la Intendencia Nacional de Riesgos.
                                </p>

                                <span class="badge bg-primary">
                                    Versión 2.0
                                </span>

                                <span class="badge bg-secondary ms-1">
                                    <?= $ambiente ?>
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- KPI -->
        <!-- ========================================= -->

        <div class="row g-3 mb-4">

            <div class="col-md-3">

                <div class="card border-primary shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Menús
                                </small>

                                <h2 class="mb-0 fw-bold">
                                    64+
                                </h2>

                            </div>

                            <div class="fs-1">
                                📁
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-success shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Roles
                                </small>

                                <h2 class="mb-0 fw-bold text-success">
                                    11
                                </h2>

                            </div>

                            <div class="fs-1">
                                👤
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-warning shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Direcciones
                                </small>

                                <h2 class="mb-0 fw-bold text-warning">
                                    11
                                </h2>

                            </div>

                            <div class="fs-1">
                                🏢
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-danger shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Usuarios
                                </small>

                                <h2 class="mb-0 fw-bold text-danger">
                                    80+
                                </h2>

                            </div>

                            <div class="fs-1">
                                🔐
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- INFORMACION GENERAL -->
        <!-- ========================================= -->

        <div class="row g-3 mb-4">

            <div class="col-12">

                <div class="card border-info shadow-sm">

                    <div class="card-header bg-info text-white">
                        Acerca de INRTools
                    </div>

                    <div class="card-body">

                        <p class="mb-3">
                            INRTools es una plataforma tecnológica institucional desarrollada para apoyar los procesos de análisis, supervisión, monitoreo y administración de información de la Intendencia Nacional de Riesgos.
                        </p>

                        <p class="mb-0">
                            La solución integra herramientas especializadas para el seguimiento de riesgos, generación de reportes, administración de usuarios, control de permisos, monitoreo de respaldos y automatización de procesos internos.
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- CAPACIDADES -->
        <!-- ========================================= -->

        <div class="row g-3 mb-4">

            <div class="col-md-3">

                <div class="card border-primary h-100">

                    <div class="card-header bg-primary text-white">
                        Riesgos
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">
                            <li>Ind Ranking</li>
                            <li>Calificación de Riesgo</li>
                            <li>Diagnósticos</li>
                            <li>Monitoreo</li>
                        </ul>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-success h-100">

                    <div class="card-header bg-success text-white">
                        Supervisión
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">
                            <li>DNS</li>
                            <li>DNSES</li>
                            <li>Empresas Auxiliares</li>
                            <li>Hallazgos</li>
                        </ul>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-warning h-100">

                    <div class="card-header bg-warning">
                        Administración
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">
                            <li>Usuarios</li>
                            <li>Roles</li>
                            <li>Permisos</li>
                            <li>Bitácoras</li>
                        </ul>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-danger h-100">

                    <div class="card-header bg-danger text-white">
                        Servicios
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">
                            <li>Backups</li>
                            <li>Validaciones</li>
                            <li>Automatización</li>
                            <li>Monitoreo</li>
                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================================= -->
        <!-- ARQUITECTURA -->
        <!-- ========================================= -->

        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="card border-secondary h-100">

                    <div class="card-header bg-secondary text-white">
                        Arquitectura Tecnológica
                    </div>

                    <div class="card-body">

                        <table class="table table-sm">

                            <tr>
                                <th width="40%">Backend</th>
                                <td>PHP</td>
                            </tr>

                            <tr>
                                <th>Base de Datos</th>
                                <td>MariaDB / MySQL</td>
                            </tr>

                            <tr>
                                <th>Frontend</th>
                                <td>Bootstrap + JavaScript</td>
                            </tr>

                            <tr>
                                <th>Autenticación</th>
                                <td>Usuarios y Roles</td>
                            </tr>

                        </table>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card border-secondary h-100">

                    <div class="card-header bg-secondary text-white">
                        Componentes Principales
                    </div>

                    <div class="card-body">

                        <ul class="mb-0">

                            <li>Gestión dinámica de menús</li>
                            <li>Control de accesos por roles</li>
                            <li>Control por direcciones</li>
                            <li>Permisos por usuario</li>
                            <li>Bitácora institucional</li>
                            <li>Monitoreo de respaldos</li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>
        <!-- ========================================= -->
        <!-- CREDITOS -->
        <!-- ========================================= -->
        <div class="row">
            <div class="col-12">
                <div class="card border-dark shadow-sm">
                    <div class="card-header bg-dark text-white">
                        Créditos Institucionales
                    </div>
                    <div class="card-body text-center">
                        <p class="mb-1">Superintendencia de Economía Popular y Solidaria</p>
                        <p class="mb-1">Intendencia Nacional de Riesgos</p>
                        <p class="mb-1">Dirección Nacional de Riesgos</p>
                        <p class="mb-1">SEPS-INR-DNR-IL</p>
                        <small class="text-muted">2025 - <?= date('Y') ?></small>
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