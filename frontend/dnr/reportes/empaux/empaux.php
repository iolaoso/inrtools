<?php
include_once __DIR__ . '/../../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/empAux/empAuxList.php'; // consulta catastro activas
//include BASE_PATH . 'backend/analistasList.php'; // consulta analistas

$rolesDireccion = [
    'SUPERUSER',
    'ADMINISTRADOR',
    'DIRECTOR',
    'DIRADMINDR',
    'DIRADMINDNS',
    'DIRADMINDNSES',
    'DIRADMINPLA'
];

// Obtener registros filtrados por usuario
if (in_array($rol_nombre, $rolesDireccion)) {
    $catastroEmpAux = obtenerCatastroEmpAux();
    
} else {
    $catastroEmpAux = obtenerCatastroEmpAux();
    /* $catEstructuras = obtenerCatalogoEstructuras(); */
}

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

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">EMPRESAS AUXILIARES</h1>
                <p>Reportes relacionados con las Empresas Auxiliares del SFPS</p>
            </div>
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <!-- Catastro Empresa Auxiliares -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Catastro</h4>
                            <ul class="list-unstyled d-flex mb-0">
                                <li class="mx-2">
                                    <button id="exportButton" class="btn btn-warning btn-sm"
                                        onclick="exportTableToExcel('tablaReportes', 'ReporteEstructuras')">Reporte
                                        General</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaCatastroEmpAux">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCatastroEmpAux">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <!-- Reporte de Cooperativas y Emp Aux -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Cooperativas y Empresas Auxiliares</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaCoopEmpAux">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Versión</th>
                                                <th>Archivo</th>
                                                <th>Tamaño</th>
                                                <th>Fecha Modificación</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCoopEmpAux">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <!-- Reporte de Empresas que Presentan formulario -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Empresas con Estados Financieros</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaEmpEEFF">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Fecha Corte</th>
                                                <th>Ruc</th>
                                                <th>Razon Social</th>
                                                <th>Periodo</th>
                                                <th>Es Linea Base</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyEmpEEFF">
                                        </tbody>
                                    </table>
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
    const carpetaReportes = 'assets/files/reportes/empaux';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/listFilesTbody_VATFA.js"></script>
</body>

</html>