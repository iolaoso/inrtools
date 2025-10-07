<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas
include BASE_PATH . 'backend/gestioninr/gestioninrList.php'; // Incluir el archivo de consultas


$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);
$categorias = obtenerCategorias($inrdireccion_id, $rol_id);

// Definir roles con acceso a nivel de dirección
$rolesDireccion = [
    'ADMINISTRADOR',
    'DIRECTOR',
    'DIRADMINDR',
    'DIRADMINDNS',
    'DIRADMINDNSES',
    'DIRADMINPLA'
];

// Optimización usando array y condicionales simplificadas
if ($rol_nombre == 'SUPERUSER') {
    $result = obtenerGestionInrFull();
} elseif (in_array($rol_nombre, $rolesDireccion)) {
    $result = obtenerGestionInrDireccion($inrdireccion_id);
} else {
    $result = obtenerGestionInrPorUsuario($nickname);
}
?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . '/frontend/partials/head.php'; ?>

<body>

    <!-- Incluir el Header -->
    <?php include_once BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <!-- Incluir el Sidebar -->
        <?php include_once BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Gestiones INR</h1>
                <p>Registros de las actividades realizadas por los Analistas</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Actividades</h4>
                        </div>
                        <div class="card-body">
                            <form id="frmGestionesInr" method="post" autocomplete="off"
                                onsubmit="guardarForm('frmGestionesInr',event)">
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" id="codGestion" name="codGestion" value=""
                                        readonly>
                                    <input type="hidden" class="form-control" id="direccionid" name="direccionid"
                                        value="<?= htmlspecialchars($inrdireccion_id)   ?>" readonly>
                                    <input type="hidden" class="form-control" id="direccion" name="direccion"
                                        value="<?= htmlspecialchars($direccion)   ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label id="lbcbCategoria" for="cbCategoria" class="form-label">Categoría
                                        <span style="color: red; font-size: smaller">*</span>
                                    </label><br>
                                    <button id="btCrearCat" type="button" class="btn btn-outline-success btn-sm mb-3"
                                        data-bs-toggle="modal" data-bs-target="#newCatModal">Crear
                                        Categoria</button>
                                    <select class="form-control" id="cbCategoria" name="cbCategoria">
                                        <option value="">Seleccione la Categoria</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?= htmlspecialchars($categoria['COD_CATEGORIA']) ?>">
                                                <?= htmlspecialchars($categoria['CATEGORIA']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select required>
                                </div>
                                <div class="mb-3">
                                    <label id="lbcbSubCategoria" for="cbSubCategoria" class="form-label">SubCategoría
                                        <span style="color: red; font-size: smaller">*</span>
                                    </label>
                                    <select class="form-control" id="cbSubCategoria" name="cbSubCategoria"></select
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="tbGestion" class="form-label">Gestión/Produto</label>
                                    <input type="text" class="form-control" id="tbGestion" name="tbGestion"
                                        style="text-transform: uppercase;">
                                </div>
                                <div class="form-container">
                                    <div class="mb-3">
                                        <label for="fechaInicio" class="form-label">F. Inicio Gestión</label>
                                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fechaFin" class="form-label">F. Fin Gestión</label>
                                        <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado de la Gestión
                                        <span style="color: red; font-size: smaller">*</span>
                                    </label>
                                    <select class="form-control" id="estado" name="estado" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="PENDIENTE">PENDIENTE</option>
                                        <option value="COMPLETADA">COMPLETADA</option>
                                        <option value="ELIMINADA">ELIMINADA</option>
                                        <option value="SUSPENDIDA">SUSPENDIDA</option>
                                    </select required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RUC</span>
                                    </div>
                                    <input type="text" class="form-control" id="ruc" name="ruc"
                                        oninput="buscarEntidad()" data-page="gestioninr.php">
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
                                    <label for="fechaOficio" class="form-label">Fecha
                                        <span style="font-size: 11px; color: blue;">
                                            Oficio/Trámite/Memorando/Correo
                                        </span>
                                    </label>
                                    <input type="date" class="form-control" id="fechaOficio" name="fechaOficio">
                                </div>
                                <div class="mb-3">
                                    <label for="oficio" class="form-label">Oficio/Trámite/Memorando/Correo</label>
                                    <input type="text" class="form-control" id="oficio" name="oficio"
                                        style="text-transform: uppercase;">
                                </div>
                                <div class="mb-3">
                                    <label for="tbcomentario" class="form-label">Comentario</label>
                                    <textarea id="tbcomentario" name="tbcomentario" class="form-control textarea small"
                                        required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label id="lbanalistaSelect" style="display: none;" for="analistaSelect"
                                        class="form-label">Asignar
                                        Analista</label>
                                    <select class="form-control" id="analistaSelect" name="analistaSelect"
                                        style="display: none;" onchange="actualizarAnalista()">
                                        <option value="">Seleccione un analista</option>
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
                                        value="<?= htmlspecialchars($nickname)   ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                                        Guardar Registro
                                    </button>
                                    <button type="submit" class="btn btn-secondary btn-sm btn-block"
                                        onclick="limpiarForm('frmGestionesInr')">Limpiar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div
                            class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Registros Analista</h4>
                            <button id="verTablaCompleta" class="btn btn-warning btn-sm">Reporte Completo</button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaActividades">
                                        <thead>
                                            <tr>
                                                <th>COD</th>
                                                <th>F. REGISTRO</th>
                                                <th>GESTION/PRODUCTO</th>
                                                <th>ESTADO</th>
                                                <th>RUC</th>
                                                <th>CATEGORIA</th>
                                                <th>OFICIO</th>
                                                <th>DETALLE</th>
                                                <th>ANALISTA</th>
                                                <th>ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $gestioninr): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($gestioninr['COD_GESTION'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($gestioninr['FECHA_REGISTRO'] ?? '') ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($gestioninr['GESTION'] ?? '') ?></td>
                                                    <td
                                                        class="<?= htmlspecialchars($gestioninr['ESTADO']) === 'PENDIENTE' ? 'pendiente' : (htmlspecialchars($gestioninr['ESTADO']) === 'COMPLETADA' ? 'completada' : '') ?>">
                                                        <?= htmlspecialchars($gestioninr['ESTADO'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($gestioninr['RUC_ENTIDAD'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($gestioninr['CATEGORIA'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($gestioninr['OFICIO_TRAMITE'] ?? '') ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary detalle-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($gestioninr['COD_GESTION'] ?? '') ?>"
                                                            title="Detalle" data-bs-toggle="modal"
                                                            data-bs-target="#detalleModal" onclick="cargarDatos(this)">
                                                            <i class="fa-solid fa-comment"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <?= htmlspecialchars($gestioninr['ANALISTA'] ?? '') ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button class="btn btn-info edit-btn btn-sm"
                                                                data-id="<?= htmlspecialchars($gestioninr['COD_GESTION'] ?? '') ?>"
                                                                title="Editar" onclick="cargarDatosForm(this)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-danger delete-btn btn-sm"
                                                                data-id="<?= htmlspecialchars($gestioninr['COD_GESTION'] ?? '') ?>"
                                                                logUser="<?= htmlspecialchars($nickname ?? '') ?>"
                                                                title="Eliminar" onclick="eliminarRegistro(this)">
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

    <!-- Modal detalleModal -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModal" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-lg bg-primary" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Detalle de la Gestión Realizada</h5>
                    <div class="input-group">
                        <span class="input-group-text">ID</span>
                        <input type="text" class="form-control text-center" id="detalleId" disabled>
                        <span class="input-group-text">Dirección</span>
                        <input type="text" class="form-control text-center" id="mDireccion" disabled>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="detalleForm">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="mCategoria" class="form-label">Categoría</label>
                                <input type="text" class="form-control" id="mCategoria" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mSubCategoria" class="form-label">SubCategoría</label>
                                <input type="text" class="form-control" id="mSubCategoria" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="mGestion" class="form-label">Gestión/Produto</label>
                                <input type="text" class="form-control" id="mGestion" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mEstado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="mEstado" readonly>
                            </div>
                        </div>
                        <div class="row mb3">
                            <div class="col-6">
                                <label for="mfecIncio" class="form-label">F. Inicio Gestión</label>
                                <input type="date" class="form-control" id="mfecIncio" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mfecFin" class="form-label">F. Fin Gestión</label>
                                <input type="date" class="form-control" id="mfecFin" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="mRucEntidad" class="form-label">RUC</label>
                                <input type="text" class="form-control" id="mRucEntidad" readonly>
                            </div>
                            <div class="col-8">
                                <label for="mRazonSocial" class="form-label">Razón Social</label>
                                <input type="text" class="form-control" id="mRazonSocial" readonly>
                            </div>
                        </div>
                        <div class="row mb3">
                            <div class="col-6">
                                <label for="mFechaOficTram" class="form-label">Fecha
                                    <span style="font-size: 11px; color: blue;">
                                        Oficio/Trámite/Memorando/Correo
                                    </span>
                                </label>
                                <input type="date" class="form-control" id="mFechaOficTram" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mOficioTramite" class="form-label">Oficio/Trámite/Memorando/Correo</label>
                                <input type="text" class="form-control" id="mOficioTramite" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="mComentario" class="form-label">Comentario</label>
                                <textarea class="form-control" id="mComentario" rows="3" readonly></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="mFechaRegistro" class="form-label">Fecha Registro</label>
                                <input type="date" class="form-control" id="mFechaRegistro" readonly>
                            </div>
                            <div class="col-6">
                                <label for="mAnalista" class="form-label">Analista</label>
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

    <!-- Modal newCatModal -->
    <div class="modal fade" id="newCatModal" tabindex="-1" aria-labelledby="newCatModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="ModalLabel">Nueva Categoría/SubCategoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="frmAddCat" name="frmAddCat" method="post" onsubmit="addCategoria('frmAddCat',event)">
                        <div class="row mb-3">
                            <input type="hidden" class="form-control" id="user" name="user"
                                value="<?= htmlspecialchars($nickname) ?>" readonly>
                            <input type="hidden" class="form-control" id="dir" name="dir"
                                value="<?= htmlspecialchars($direccion) ?>" readonly>
                            <input type="hidden" class="form-control" id="dirid" name="dirid"
                                value="<?= htmlspecialchars($inrdireccion_id) ?>" readonly>
                        </div>
                        <div class="row mb-3">
                            <label for="cbCat" class="form-label">Categoria Existente</label>
                            <select class="form-control" id="cbCat" name="cbCat" onchange="selecCatExistente()">
                                <option value="0">Seleccione la Categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= htmlspecialchars($categoria['COD_CATEGORIA']) ?>"
                                        data-text="<?= htmlspecialchars($categoria['CATEGORIA']) ?>">
                                        <?= htmlspecialchars($categoria['CATEGORIA']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <input type="hidden" class="form-control" id="tbnCatid" name="tbnCatid" value="0" readonly>
                            <label class="form-label" id="lbnewCat" for="tbnewCat">Nueva Catagoría</label>
                            <input type="text" class="form-control" id="tbnewCat" name="tbnewCat"
                                style="text-transform: uppercase;" required>
                        </div>
                        <div class="row mb-3">
                            <label for="tbnewSubCat" class="form-label">Nueva SubCategoría</label>
                            <input type="text" class="form-control" id="tbnewSubCat" name="tbnewSubCat"
                                style="text-transform: uppercase;" required>
                        </div>
                        <div class="row mb-3">
                            <div class="form-check m-4">
                                <input class="form-check-input" type="checkbox" id="choficio" name="choficio">
                                <label class="form-check-label" for="choficio">Requiere Oficio</label><br>
                                <input class="form-check-input" type="checkbox" id="chcomentario" name="chcomentario">
                                <label class="form-check-label" for="chcomentario">Requiere Comentario</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label" for="cbComplejidad">Complejidad</label>
                            <select class="form-control" id="cbComplejidad" name="cbComplejidad" required>
                                <option value="">Seleccione</option>
                                <option value="ALTA">ALTA</option>
                                <option value="MEDIA">MEDIA</option>
                                <option value="BAJA">BAJA</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/gestioninr/gestioninr.js"></script>
</body>

</html>