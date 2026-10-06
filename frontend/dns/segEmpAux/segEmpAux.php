<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/empAux/empAuxList.php'; // Incluir el archivo de consultas

$bitacorasEntrega = bitacora_entrega();

$servicios = [
    'GENERADORAS_DE_CARTERA',
    'SOFTWARE_FINANCIERO_Y_COMPUTACIONAL',
    'TRANSACCIONALES_Y_DE_PAGO',
    'TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES',
    'RED_Y_CAJEROS_AUTOMATICOS',
    'COBRANZAS',
    'SERVICIOS_CONTABLES',
    'ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS',
    'GIRO_INMOBILIARIO'];

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
/* if ($rol_nombre == 'SUPERUSER') {
    $result = obtenerGestionInrFull();
} elseif (in_array($rol_nombre, $rolesDireccion)) {
    $result = obtenerGestionInrDireccion($inrdireccion_id);
} else {
    $result = obtenerGestionInrPorUsuario($nickname);
} */
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
                <h1 class="display-6 tituloPagina">Seguimiento Empresas Auxiliares</h1>
                <p>Registro del proceso de seguiemto a las empresas auxiliares</p>
            </div>
            <section class="row align-items-stretch">
                <!-- <div class="col-md-4 mb-3">
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
                </div> -->
                <div class="col-md-12 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div
                            class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Seguimiento Pendiente</h4>
                            <button id="verTablaCompleta" class="btn btn-warning btn-sm">Reporte Completo</button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaSegEmpAux">
                                        <thead>
                                            <tr>
                                                <th class="text-center">RUC</th>
                                                <th class="text-center">RAZÓN SOCIAL</th>
                                                <th class="text-center">SERVICIO</th>
                                                <th class="text-center">RES. CALIFICACIÓN</th>
                                                <th class="text-center">FEC. RESOLUCIÓN</th>  
                                                <th class="text-center">PERÍODO</th>
                                                <th class="text-center">CORTE</th>
                                                <th class="text-center">ESTADO</th>
                                                <th class="text-center">MEDIO</th>
                                                <th class="text-center">SEGUIMIENTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bitacorasEntrega as $bitacoraEntrega): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bitacoraEntrega['RUC_CATASTRO'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bitacoraEntrega['RAZON_SOCIAL'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bitacoraEntrega['SERVICIO_PRESTADO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['NUM_RESOLUCION_CALIFICACION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['FECHA_RESOLUCION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['NUMERO_PERIODO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['FECHA_CORTE'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['ESTADO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['MEDIO_ENVIO'] ?? '') ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-info detalle-btn btn-sm" disabled>
                                                            N°. <?= htmlspecialchars($bitacoraEntrega['NSEGUIMIENTOS'] ?? '') ?>                                               
                                                        </button>
                                                        <button class="btn btn-primary detalle-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($bitacoraEntrega['ID_ENTIDAD'] ?? '') ?>"
                                                            title="Agregar Seguimiento" data-bs-toggle="modal"
                                                            data-bs-target="#agregarSeguimiento"
                                                            onclick="cDatSegEmpAux(this)">
                                                            <i class="fa fa-flag"></i>
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

    <!-- Modal agregarSeguimiento -->
    <div class="modal fade" id="agregarSeguimiento" tabindex="-1" aria-labelledby="agregarSeguimiento" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl bg-primary" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="ModalLabel">Agregar Seguimiento</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="detalleForm">
                        <!-- ENTIDAD CALIFICADA -->
                        <div class="row mb-2">
                            <div class="col-md-1">
                                <label for="mNumeroPeriodo" class="form-label">Período</label>
                                <input type="text" class="form-control" id="mNumeroPeriodo" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="mIdEntidad" class="form-label">ID Entidad</label>
                                <input type="text" class="form-control" id="mIdEntidad" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mRucCatastro" class="form-label">RUC</label>
                                <input type="text" class="form-control" id="mRucCatastro" readonly>
                            </div>
                            <div class="col-md-5">
                                <label for="mRazonSocial" class="form-label">Razón Social</label>
                                <input type="text" class="form-control" id="mRazonSocial" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Servicios Prestados</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mSoftwareFinanciero" disabled>
                                    <label class="form-check-label" for="mSoftwareFinanciero">
                                        Software Financiero y Computacional
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mTransaccionalesPago" disabled>
                                    <label class="form-check-label" for="mTransaccionalesPago">
                                        Transaccionales y de Pago
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mTransporteValores" disabled>
                                    <label class="form-check-label" for="mTransporteValores">
                                        Transporte de Especies Monetarias y Valores
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mRedCajeros" disabled>
                                    <label class="form-check-label" for="mRedCajeros">
                                        Red y Cajeros Automáticos
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mCobranzas" disabled>
                                    <label class="form-check-label" for="mCobranzas">
                                        Cobranzas
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mServiciosContables" disabled>
                                    <label class="form-check-label" for="mServiciosContables">
                                        Servicios Contables
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mGeneradorasCartera" disabled>
                                    <label class="form-check-label" for="mGeneradorasCartera">
                                        Generadoras de Cartera
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mOperadorasTarjetas" disabled>
                                    <label class="form-check-label" for="mOperadorasTarjetas">
                                        Administradoras y Operadoras de Tarjetas
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mGiroInmobiliario" disabled>
                                    <label class="form-check-label" for="mGiroInmobiliario">
                                        Giro Inmobiliario
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- INFORMACION DE CALIFICACION -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mOfComunicacionResolucion" class="form-label">
                                    Oficio Comunicación / Resolución
                                </label>
                                <input type="text" class="form-control" id="mOfComunicacionResolucion" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="mFechaOficioComRes" class="form-label">
                                    Fecha Oficio Comunicación
                                </label>
                                <input type="date" class="form-control" id="mFechaOficioComRes" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mNumResolucionCalificacion" class="form-label">
                                    Resolución de Calificación
                                </label>
                                <input type="text" class="form-control" id="mNumResolucionCalificacion" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mFechaResolucion" class="form-label">
                                    Fecha Resolución
                                </label>
                                <input type="date" class="form-control" id="mFechaResolucion" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mFechaVencimientoRes" class="form-label">
                                    Vencimiento
                                </label>
                                <input type="date" class="form-control" id="mFechaVencimientoRes" readonly>
                            </div>
                        </div>
                        <!-- INFORMACION DE LA ENTREGA -->
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label for="mEstado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="mEstado" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mMedioEnvio" class="form-label">Medio Envío</label>
                                <input type="text" class="form-control" id="mMedioEnvio" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="mCorreo" class="form-label">Documento</label>
                                <input type="text" class="form-control" id="mCorreo" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mFechaRegistro" class="form-label">Fecha Registro</label>
                                <input type="date" class="form-control" id="mFechaRegistro" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="mFechaCorte" class="form-label">Fecha Corte</label>
                                <input type="date" class="form-control" id="mFechaCorte" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mFechaLineaBase" class="form-label">Fecha Línea Base</label>
                                <input type="date" class="form-control" id="mFechaLineaBase" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mFechaRegularizacion" class="form-label">
                                    Fecha Regularización
                                </label>
                                <input type="date" class="form-control" id="mFechaRegularizacion" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="mFechaProrroga" class="form-label">
                                    Fecha Prórroga
                                </label>
                                <input type="date" class="form-control" id="mFechaProrroga" readonly>
                            </div>
                        </div>
                        <!-- OBSERVACIONES -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="mObservacionDnr" class="form-label">
                                    Observación DNR
                                </label>
                                <textarea class="form-control" id="mObservacionDnr" rows="4" readonly></textarea>
                            </div>
                        </div>
                        <!-- Campos para el seguimiento -->
                        <hr>
                        <h3 class="mb-3 text-primary">Seguimiento</h3>
                        <!-- IDENTIFICACIÓN DEL SEGUIMIENTO -->
                        <!-- DOCUMENTO DE SEGUIMIENTO -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="mEstadoSeguimiento" class="form-label">Estado Seguimiento</label>
                                <select class="form-control" id="mEstadoSeguimiento"></select>
                            </div>
                            <div class="col-md-3">
                                <label for="mOficioSeguimiento" class="form-label">
                                    Oficio de Seguimiento
                                </label>
                                <input type="text" class="form-control" id="mOficioSeguimiento" >
                            </div>
                            <div class="col-md-4">
                                <label for="mFechaOfSeguimiento" class="form-label">
                                    Fecha Oficio
                                </label>
                                <input type="date" class="form-control" id="mFechaOfSeguimiento" >
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Regularizado</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox"
                                        id="mIndRegularizado">
                                    <label class="form-check-label" for="mIndRegularizado">
                                        Sí
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- CUMPLIMIENTO -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mEstadoCumplimiento" class="form-label">
                                    Estado Cumplimiento
                                </label>
                                <select class="form-control" id="mEstadoCumplimiento"></select>
                            </div>
                            <div class="col-md-6">
                                <label for="mMotivoIncumplimiento" class="form-label">
                                    Motivo de Incumplimiento
                                </label>
                                <select class="form-control" id="mMotivoIncumplimiento"></select>
                            </div>
                        </div>
                        <!-- OBSERVACIONES -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="mObservacionDns" class="form-label">
                                    Observación DNS
                                </label>
                                <textarea class="form-control"
                                    id="mObservacionDns"
                                    rows="3">
                                </textarea>
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

    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/empAux/segEmpAux.js"></script>
</body>

</html>