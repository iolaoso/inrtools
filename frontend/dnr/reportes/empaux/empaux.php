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
    $coopEmpAux = obtenerCoopEmpAux();
    $formEmpAux = obtenerFormEmpAux();
    
} else {
    $catastroEmpAux = obtenerCatastroEmpAux();
    $coopEmpAux = obtenerCoopEmpAux();
    $formEmpAux = obtenerFormEmpAux();
    /* $catc_empAuxs = obtenerCatalogoEstructuras(); */
}

//$entidadesActSf = entidadesActivasSf();
//$analistas = obtenerAnalistas($direccion);


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
            <!-- Reporte de Empresas que Presentan formulario -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4>Reporte de Empresas que Presentan formulario</h4>
                            <div>
                                <button id="btnDescargarAnexo2" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i>
                                    Descargar Anexo 2
                                </button>
                                <button id="btnGenerarRIL" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-excel"></i>
                                    Generar RIL Empresas Auxiliares
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 800px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaFormEmpAux">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha Corte</th>
                                                <th>RUC</th>
                                                <th>Razón Social</th>
                                                <th>Número Período</th>
                                                <th>Línea Base</th>
                                                <th>Validación Servicios</th>
                                                <th>Activo</th>
                                                <th>Pasivo</th>
                                                <th>Patrimonio Neto</th>
                                                <th>Resultados del Ejercicio</th>
                                                <th>Ingresos de Actividades Ordinarias</th>
                                                <th>Gastos</th>
                                                <th>Núm. Entidades</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyFormEmpAux">
                                        <?php foreach ($formEmpAux as $entidad): ?>
                                            <tr>
                                                <td><?= h($entidad['ID']) ?></td>
                                                <td><?= h($entidad['FECHA_CORTE']) ?></td>
                                                <td><?= h($entidad['RUC']) ?></td>
                                                <td><?= h($entidad['RAZON_SOCIAL']) ?></td>
                                                <td><?= h($entidad['NUMERO_PERIODO']) ?></td>
                                                <td><?= h($entidad['LINEA_BASE']) ?></td>
                                                <td><?= h($entidad['VALIDACION_SERVICIOS']) ?></td>
                                                <td><?= h($entidad['ACTIVO']) ?></td>
                                                <td><?= h($entidad['PASIVO']) ?></td>
                                                <td><?= h($entidad['PATRIMONIO_NETO']) ?></td>
                                                <td><?= h($entidad['RESULTADOS_DEL_EJERCICIO']) ?></td>
                                                <td><?= h($entidad['INGRESOS_DE_ACTIVIDADES_ORDINARIAS']) ?></td>
                                                <td><?= h($entidad['GASTOS']) ?></td>
                                                <td><?= h($entidad['NUM_ENTIDADES']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- Catastro Empresa Auxiliares -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header card-header text-white d-flex justify-content-between align-items-center" style="background-color: #059b87ff;">
                            <h4>Catastro</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 800px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaCatastroEmpAux">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>RUC</th>
                                                <th>RAZON SOCIAL</th>
                                                <th>TIPO ORGANIZACION</th>
                                                <th>NUM RESOLUCION CALIFICACION</th>
                                                <th>FECHA RESOLUCION</th>
                                                <th>SERVICIO PRESTADO</th>
                                                <th>REPRESENTANTE LEGAL</th>
                                                <th>CORREO ELECTRONICO</th>
                                                <th>NUEVA</th>
                                                <th>CON CONDICION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCatastroEmpAux">
                                            <?php foreach ($catastroEmpAux as $c_empAux): ?>
                                                <tr>
                                                    <td style="width: 5%"><?= htmlspecialchars($c_empAux['ID']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['RUC']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['RAZON_SOCIAL']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['TIPO_ORGANIZACION']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['NUM_RESOLUCION_CALIFICACION']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['FECHA_RESOLUCION']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['SERVICIO_PRESTADO']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['REPRESENTANTE_LEGAL']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['CORREO_ELECTRONICO']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['NUEVA']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['CON_CONDICION']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- Reporte de Cooperativas y Emp Aux -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4>Reporte de Cooperativas y Empresas Auxiliares</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 800px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaCoopEmpAux">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>RUC COOPERATIVA</th>
                                                <!-- <th>RZ_COOP</th> -->
                                                <th>SEGMENTO</th>
                                                <th>NUM EMP AUX</th>
                                                <th>RUC EMPRESA</th>
                                                <th>RUC_EMP_CATASTRO</th>
                                                <th>RAZON_SOCIAL_EMPRESA</th>
                                                <th>RZ_EMP_CATASTRO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCoopEmpAux">
                                            <?php foreach ($coopEmpAux as $coop_EmpAux): ?>
                                                <tr>
                                                    <td style="width: 5%"><?= htmlspecialchars($coop_EmpAux['ID']) ?></td>
                                                    <td><?= h($coop_EmpAux['RUC_COOP']) ?></td>
                                                    <td><?= h($coop_EmpAux['SEGMENTO']) ?></td>
                                                    <td><?= h($coop_EmpAux['NUM_EMP_AUX']) ?></td>
                                                    <td><?= h($coop_EmpAux['RUC_EMPRESA']) ?></td>
                                                    <td><?= h($coop_EmpAux['RUC_EMP_CATASTRO']) ?></td>
                                                    <td><?= h($coop_EmpAux['RAZON_SOCIAL_EMPRESA']) ?></td>
                                                    <td><?= h($coop_EmpAux['RZ_EMP_CATASTRO']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
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
    <script>
    // Define la carpeta que deseas usar
    const carpetaReportes = 'assets/files/reportes/empaux';
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/empAux/empAuxTables.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/empAux/empAuxReportes.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/reportes/listFilesTbody_VATFA.js"></script>
</body>

</html>