<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/estructuras/estructurasList.php'; // Incluir el archivo de consultas
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas



// Obtener registros filtrados por usuario
if ($rol_nombre == 'ADMINISTRADOR' || $rol_nombre == 'SUPERUSER' || $rol_nombre == 'DIRECTOR') {
    $result = obtenerEstructurasFull();
    $catEstructuras = obtenerCatalogoEstructuras();
} else {
    $result = obtenerEstructurasPorUsuario($nickname);
    $catEstructuras = obtenerCatalogoEstructuras();
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
                <h1 class="display-6 tituloPagina">Estructuras Generadas</h1>
                <p>Registro las estructuras que fueron generadas por los Analistas</p>
            </div>
            <section class="row align-items-stretch">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Datos de la Solicitud</h4>
                        </div>
                        <div class="card-body">
                            <form id="formReporte" method="POST">
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="idEstructura" name="idEstructura"
                                        readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="analistaSolicitante" class="form-label">Analista Solicitante</label>
                                    <input type="text" class="form-control" id="analistaSolicitante"
                                        name="analistaSolicitante" style="text-transform: uppercase;" required>
                                </div>
                                <div class="mb-3">
                                    <label for="direccionSolicitante" class="form-label">Dirección</label>
                                    <input class="form-control" id="direccionSolicitante" name="direccionSolicitante"
                                        style="text-transform: uppercase;" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RUC</span>
                                    </div>
                                    <input type="text" class="form-control" id="ruc" name="ruc" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#catastroModal">Buscar</button>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Estructura</span>
                                    </div>
                                    <input type="text" class="form-control" id="nombreReporte" name="nombreReporte"
                                        style="text-transform: uppercase;" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#estucturasModal">Buscar</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="fechaCorte" class="form-label">Fecha de Corte</label>
                                    <input type="date" class="form-control" id="fechaCorte" name="fechaCorte" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fechaSolicitud" class="form-label">Fecha de Solicitud</label>
                                    <input type="date" class="form-control" id="fechaSolicitud" name="fechaSolicitud"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label id="lbAnalistaEjecutanteSelect" for="analistaEjecutanteSelect"
                                        class="form-label">Asignar Analista</label>
                                    <select class="form-control" id="analistaEjecutanteSelect"
                                        name="analistaEjecutanteSelect" style="display: none;"
                                        onchange="actualizarInputAnalista()">
                                        <option value="">Seleccione un analista</option>
                                        <?php foreach ($analistas as $analista): ?>
                                            <option value="<?= htmlspecialchars($analista['NICKNAME']) ?>">
                                                <?= htmlspecialchars($analista['NOMBRE']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="analistaEjecutante" class="form-label">Analista Ejecutante</label>
                                    <input class="form-control" id="analistaEjecutante" name="analistaEjecutante"
                                        value="<?= htmlspecialchars($nickname)   ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="estadoSolicitud" class="form-label">Estado de Solicitud</label>
                                    <select class="form-control" id="estadoSolicitud" name="estadoSolicitud" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="PENDIENTE">PENDIENTE</option>
                                        <option value="ENVIADO">ENVIADO</option>
                                        <option value="GENERADA">GENERADA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="fechaRegistro" name="fechaRegistro"
                                        readonly>
                                    <input type="hidden" class="form-control" id="fechaActualizacion"
                                        name="fechaActualizacion" readonly>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Guardar
                                        Registro</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
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
                            <div class="table-container" style="max-height: 700px; overflow-y: auto;">
                                <table class="table table-striped table-sm" style="font-size: 12px;" id="tablaReportes">
                                    <thead>
                                        <tr>
                                            <th>Solicitante</th>
                                            <th>Dirección</th>
                                            <th>Ruc</th>
                                            <th>Estructura</th>
                                            <th>Fecha Corte</th>
                                            <th>Fecha de Solicitud</th>
                                            <th>Estado</th>
                                            <th>Ejecutante</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $estructura): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($estructura['solicitante']) ?></td>
                                                <td><?= htmlspecialchars($estructura['direccion_solicitante']) ?></td>
                                                <td><?= htmlspecialchars($estructura['ruc']) ?></td>
                                                <td><?= htmlspecialchars($estructura['estructura']) ?></td>
                                                <td><?= htmlspecialchars($estructura['fechaCorte']) ?></td>
                                                <td><?= htmlspecialchars($estructura['fecha_solicitud']) ?></td>
                                                <td
                                                    class="<?= htmlspecialchars($estructura['estado']) === 'PENDIENTE' ? 'pendiente' : '' ?>">
                                                    <?= htmlspecialchars($estructura['estado']) ?></td>
                                                <td><?= htmlspecialchars($estructura['analista_ejecutante']) ?></td>
                                                <td>
                                                    <button class="btn btn-info edit-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($estructura['id']) ?>" title="Editar"
                                                        onclick="asignarEventosBotones();">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger delete-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($estructura['id']) ?>"
                                                        title="Eliminar" onclick="asignarEventosBotones();">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
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

    <!-- Modal Buscar estructura-->
    <div class="modal fade" id="estucturasModal" tabindex="-1" aria-labelledby="estucturasModal" aria-hidden="true">
        <div class="modal-dialog modal-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="estucturasModal">Seleccionar Estructura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <ul class="list-group scrollable-list" id="categoriaList">
                            <?php foreach ($catEstructuras as $catEst): ?>
                                <li class="list-group-item list-group-item-action"
                                    onclick="seleccionarCategoria('<?= htmlspecialchars($catEst['estNombre']) ?>', '<?= htmlspecialchars($catEst['estNombre']) ?>')">
                                    <?= htmlspecialchars($catEst['estNombre']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/estructuras/estructuras.js"></script>

</body>

</html>