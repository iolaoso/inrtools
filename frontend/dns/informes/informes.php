<?php

include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas
include BASE_PATH . 'backend/informesinr/informesinrList.php'; // Incluir el archivo de consultas

$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);
$tiposInf = obtenerTiposInforme();
$areasReq = obtenerAreasRequirientes();

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
    $result = obtenerInformesInrFull();
} else {
    $result = obtInformesInrUsr($nickname);
}

?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>

    <!-- Incluir el Header -->
    <?php include_once BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <!-- Incluir el Sidebar -->
        <?php include_once BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Informes INR</h1>
                <p>Registros de los Informes realizados</p>
            </div>
            <section class="row align-items-stretch">
                <!-- FORMULARIO INGRESO -->
                <div class="col-md-4 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Informe</h4>
                        </div>
                        <div class="card-body">
                            <form id="frmInformesInr" method="post" autocomplete="off"
                                onsubmit="guardarForm('frmInformesInr',event)">
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="codInforme" name="codInforme" value=""
                                        readonly>
                                    <input type="hidden" class="form-control" id="direccionid" name="direccionid"
                                        value="<?= htmlspecialchars($inrdireccion_id)   ?>" readonly>
                                    <input type="hidden" class="form-control" id="direccion" name="direccion"
                                        value="<?= htmlspecialchars($direccion)   ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <button id="btCrearTinf" type="button" class="btn btn-outline-success btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#newTipoInfModal">Nuevo Tipo de
                                        Informe</button>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RUC</span>
                                    </div>
                                    <input type="text" class="form-control" id="ruc" name="ruc"
                                        oninput="buscarEntidad()" data-page="informes.php" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#catastroModal">Buscar</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="tbrazonSocial" class="form-label">Razón Social</label>
                                    <textarea id="tbrazonSocial" name="tbrazonSocial"
                                        class="form-control textarea small" disabled></textarea>
                                </div>
                                <div class="mb-3">
                                    <label id="lbcbTipoInforme" for="cbTipoInforme" class="form-label">Tipo de
                                        Informe</label><br>
                                    <select class="form-control" id="cbTipoInforme" name="cbTipoInforme">
                                        <option areaReq="SIN SELECCION" value="0">--- Seleccione ---
                                        </option>
                                        <?php foreach ($tiposInf as $tInf): ?>
                                            <option areaReq="<?= htmlspecialchars($tInf['AREA_REQUIRIENTE']) ?>"
                                                value="<?= htmlspecialchars($tInf['COD_TIPO_INF']) ?>">
                                                <?= htmlspecialchars($tInf['TIPO_INFORME']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="areaRequiriente" class="form-label">Área Requiriente</label>
                                    <textarea class="form-control textarea small" id="areaRequiriente"
                                        name="areaRequiriente" disabled></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fasignacion" class="form-label">F. Asignación</label>
                                        <input type="date" class="form-control" id="fasignacion" name="fasignacion"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fsolicitaRev" class="form-label">F. Solicita Revisión</label>
                                        <input type="date" class="form-control" id="fsolicitaRev" name="fsolicitaRev"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="informe" class="form-label">Informe</label><br>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="informe" name="informe"
                                                style="text-transform: uppercase;"></span>
                                        </div>
                                        <input type="number" class="form-control" id="numinforme" name="numinforme"
                                            oninput="unirInforme()">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="numinformeFull" class="form-label">Número Informe</label><br>
                                    <input type="text" class="form-control" id="numinformeFull" name="numinformeFull"
                                        style="text-transform: uppercase; background-color: #EBF7F5;" redonly>
                                </div>
                                <div class="mb-3">
                                    <label for="fInforme" class="form-label">F. Informe</label>
                                    <input type="date" class="form-control" id="fInforme" name="fInforme" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 mb-3">
                                        <label for="nummemorando" class="form-label">Memorando</label>
                                        <input type="text" class="form-control" id="nummemorando" name="nummemorando"
                                            style="text-transform: uppercase;">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="fmemorando" class="form-label">F. Memorando</label>
                                        <input type="date" class="form-control" id="fmemorando" name="fmemorando">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="fCargaCompartida" class="form-label">F. Carga en Compartida</label>
                                    <input type="date" class="form-control" id="fCargaCompartida"
                                        name="fCargaCompartida">
                                </div>
                                <div class="mb-3">
                                    <label for="tbObs" class="form-label">Observaciones</label>
                                    <textarea id="tbObs" name="tbObs" class="form-control textarea small"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label id="lbcbEstado" for="cbEstado" class="form-label">Estado del
                                        Informe</label><br>
                                    <select class="form-control" id="cbEstado" name="cbEstado">
                                        <option value="0">--- Seleccione ---</option>
                                        <option value="65">ENVIADO</option>
                                        <option value="66">PENDIENTE</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label id="lbanalistaSelect" style="display: none;" for="analistaSelect"
                                        class="form-label">Asignar
                                        Analista</label>
                                    <select class="form-control" id="analistaSelect" name="analistaSelect"
                                        style="display: none;" onchange="actualizarAnalista()">
                                        <option value="">Analista</option>
                                        <?php foreach ($analistas as $analista): ?>
                                            <option value="<?= htmlspecialchars($analista['NICKNAME']) ?>">
                                                <?= htmlspecialchars($analista['NOMBRE']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="analista" class="form-label">Analista</label>
                                    <input class="form-control" id="analista" name="analista"
                                        value="<?= htmlspecialchars($nickname) ?>" readonly>
                                </div>
                                <div class="text-center mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-save me-2"></i>Guardar Registro
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm btn-block"
                                        onclick="limpiarForm('frmInformesInr')">
                                        <i class="fas fa-broom me-2"></i>Limpiar Formulario
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- TABLA DATOS -->
                <div class="col-md-8 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div
                            class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Informes Analista</h4>
                            <button id="btRepInformes" class="btn btn-warning btn-sm">Reporte General Informes</button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaInformes">
                                        <thead>
                                            <tr>
                                                <th>COD</th>
                                                <th>RUC</th>
                                                <th>INFORME</th>
                                                <th>MEMORANDO</th>
                                                <th>DETALLE</th>
                                                <th>ESTADO</th>
                                                <th>ANALISTA</th>
                                                <th>ACCION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $informeinr): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($informeinr['COD_INFORME'] ?? '') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($informeinr['RUC_ENTIDAD'] ?? '') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($informeinr['NUM_INFORME'] ?? '') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($informeinr['NUM_MEMORANDO'] ?? '') ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary detalle-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($informeinr['COD_INFORME'] ?? '') ?>"
                                                            title="Detalle" data-bs-toggle="modal"
                                                            data-bs-target="#detalleModal" onclick="cargarDatos(this)">
                                                            <i class="fa-solid fa-comment"></i>
                                                        </button>
                                                    </td>
                                                    <td
                                                        class="<?= htmlspecialchars($informeinr['ESTADO'] ?? '') == 'PENDIENTE' ? 'text-danger' : 'text-success' ?>">
                                                        <?= htmlspecialchars($informeinr['ESTADO'] ?? '') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($informeinr['ANALISTA'] ?? '') ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-info edit-btn btn-sm"
                                                                data-id="<?= htmlspecialchars($informeinr['COD_INFORME'] ?? '') ?>"
                                                                title="Editar" onclick="editarInforme(this)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-danger delete-btn btn-sm"
                                                                data-id="<?= htmlspecialchars($informeinr['COD_INFORME'] ?? '') ?>"
                                                                logUser="<?= htmlspecialchars($nickname ?? '') ?>"
                                                                title="Eliminar" onclick="eliminarInforme(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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


    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <!-- Modal detalleModal -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModal" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-lg bg-primary" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Detalle del informe Seleccionado</h5>
                    <div class="input-group ms-auto" style="width: 30%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">ID</span>
                        </div>
                        <input type="text" class="form-control" id="detalleId" disabled>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="detalleForm">
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="mRucEntidad">RUC Entidad</label>
                                <input type="text" class="form-control" id="mRucEntidad" readonly>
                            </div>
                            <div class="col-8">
                                <label for="mRazonSocial">Razón Social</label>
                                <input type="text" class="form-control" id="mRazonSocial" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="mtipoInforme">Tipo de Informe</label>
                                <input type="text" class="form-control" id="mtipoInforme" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mareaRequiriente">Area Requiriente</label>
                                <input type="text" class="form-control" id="mareaRequiriente" readonly>
                            </div>
                        </div>
                        <div class="row mb3">
                            <div class="col-6">
                                <label for="mFechaAsignacion">Fecha Asignación</label>
                                <input type="date" class="form-control" id="mFechaAsignacion" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mfechaSolicitud">Fecha Solicitud Revisión</label>
                                <input type="date" class="form-control" id="mfechaSolicitud" readonly>
                            </div>
                        </div>
                        <div class="row mb3">
                            <div class="col-6">
                                <label for="mInforme">Informe</label>
                                <input type="text" class="form-control" id="mInforme" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mfechaInforme">Fecha de Informe</label>
                                <input type="date" class="form-control" id="mfechaInforme" readonly>
                            </div>
                        </div>
                        <div class="row mb3">
                            <div class="col-6">
                                <label for="mMemorando">Memorando</label>
                                <input type="text" class="form-control" id="mMemorando" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mfechaMemorando">Fecha de Memorando</label>
                                <input type="date" class="form-control" id="mfechaMemorando" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-12">
                                <label for="mtbObservaciones" class="form-label">Observaciones</label>
                                <textarea id="mtbObservaciones" name="mtbObservaciones"
                                    class="form-control textarea small" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="mfechaCargaCompartida">Fecha de Carga en compartida</label>
                                <input type="date" class="form-control" id="mfechaCargaCompartida" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="mEstado">Estado</label>
                                <input type="text" class="form-control" id="mEstado" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mAnalista">Analista</label>
                                <input type="text" class="form-control" id="mAnalista" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Tipo de Informe -->
    <div class="modal fade" id="newTipoInfModal" tabindex="-1" aria-labelledby="newTipoInfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newTipoInfModalLabel">Nuevo Tipo de Informe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="selectAreaRequiriente" class="form-label">Área Requiriente Existente</label>
                        <select class="form-control" id="selectAreaRequiriente" name="selectAreaRequiriente"
                            onclick="selectAreaExistente()">
                            <!-- AREAS VIENEN DESDE LA BASE -->
                            <option value="0">--- Seleccione ---</option>
                            <?php foreach ($areasReq as $areaR): ?>
                                    <option value="<?= htmlspecialchars($areaR['AREA_REQUIRIENTE']) ?>"
                                        data-text="<?= htmlspecialchars($areaR['AREA_REQUIRIENTE']) ?>">
                                        <?= htmlspecialchars($areaR['AREA_REQUIRIENTE']) ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <form id="frmNewTipoInf" method="POST" autocomplete="off"
                        onsubmit="guardarNewTipoInf('frmNewTipoInf', event)">
                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="usrCreacion" name="usrCreacion"
                                value="<?= htmlspecialchars($nickname) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="newAreaRequiriente" class="form-label">Área Requiriente</label>
                            <input type="text" class="form-control" id="newAreaRequiriente" name="newAreaRequiriente"
                                style="text-transform: uppercase;" require>
                        </div>
                        <div class="mb-3">
                            <label for="newTipoInforme" class="form-label">Tipo de Informe</label>
                            <input type="text" class="form-control" id="newTipoInforme" name="newTipoInforme"
                                style="text-transform: uppercase;" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/informesinr/informesinr.js"></script>
</body>

</html>