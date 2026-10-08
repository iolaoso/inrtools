<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/empAux/empAuxList.php'; // Incluir el archivo de consultas

$bitacorasEntrega = bitacora_entrega();
$catEstSeguimiento = cat_est_seguimiento();
$catEstCumplimiento = cat_est_cumplimiento();
$catMotCumplimiento = cat_mot_cumplimiento(); 
// para revisar que llega en la variable 
//var_dump($catEstSeguimiento); 

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
                            <h4 class="mb-0">Bitácora de Seguimiento</h4>
                            <!-- <button id="verTablaCompleta" class="btn btn-warning btn-sm">Reporte Completo</button> -->
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
                                                <th class="text-center">BITACORA</th>
                                                <th class="text-center">DNR</th>
                                                <th class="text-center">SEGUIMIENTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bitacorasEntrega as $bitacoraEntrega): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bitacoraEntrega['RUC'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bitacoraEntrega['RAZON_SOCIAL'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bitacoraEntrega['SERVICIO_PRESTADO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['NUM_RESOLUCION_CALIFICACION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['FECHA_RESOLUCION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['NUM_PERIODO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['FECHA_CORTE'] ?? '') ?></td>
                                                <?php
                                                    $estado = htmlspecialchars($bitacoraEntrega['ESTADO'] ?? '');
                                                    if ($estado == 'ENVIADO') {$clase = 'badge rounded-pill bg-success'; // Celeste
                                                    } elseif ($estado == 'NO ENVIADO') {
                                                        $clase = 'badge rounded-pill bg-danger'; 
                                                    } else {
                                                        $clase = 'badge rounded-pill bg-secondary'; 
                                                    }
                                                ?>
                                                <td class="text-center">
                                                    <span class="<?= $clase ?>"><?= htmlspecialchars($estado) ?></span>
                                                </td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEntrega['MEDIO_ENVIO'] ?? '') ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary detalle-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($bitacoraEntrega['ID_ENTIDAD'] ?? '') ?>"
                                                        title="Bitácora de Envio" data-bs-toggle="modal"
                                                        data-bs-target="#detalleBitacoraEnvio"
                                                        onclick="cDatSegEmpAux(this)"
                                                        <?= empty($bitacoraEntrega['ID_ENTIDAD']) ? ' disabled' : '' ?>>
                                                        <i class="fa-solid fa-comment"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center p-1" >
                                                    <div class="d-flex center-content-between align-items-center">
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <span class="badge bg-primary">
                                                                Regularizaciones: <?= $bitacoraEntrega['REGULARIZACIONES'] ?>
                                                            </span>

                                                            <span class="badge bg-warning text-dark">
                                                                Prórrogas: <?= $bitacoraEntrega['PRORROGAS'] ?>
                                                            </span>

                                                            <span class="badge bg-secondary">
                                                                Obs. DNR: <?= $bitacoraEntrega['OBSERVACIONES_DNR'] ?>
                                                            </span>
                                                        </div>

                                                        <div>
                                                            <button class="btn btn-info btn-sm"
                                                                data-id="<?= htmlspecialchars($bitacoraEntrega['ID_ENTIDAD'] ?? '') ?>"
                                                                title="Agregar observación DNR"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#agregarProcesoDNR"
                                                                onclick="cDatAgreProcesoDNR(this)">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php
                                                            $seguimientos = (int)($bitacoraEntrega['NUM_SEGUIMIENTOS'] ?? 0);
                                                            if ($seguimientos <= 0) {
                                                                $clase = 'btn-secondary'; // PLOMO
                                                            } elseif ($seguimientos <= 1) {
                                                                $clase = 'btn-warning'; // AMARILLO
                                                            } else {
                                                                $clase = 'btn-danger'; // ROJO
                                                            }
                                                        ?>
                                                        <button class="btn <?= $clase ?> detalle-btn btn-sm" disabled>
                                                            <?= htmlspecialchars($seguimientos) ?>
                                                        </button>
                                                        <button class="btn btn-success detalle-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($bitacoraEntrega['ID_ENTIDAD'] ?? '') ?>"
                                                            title="Agregar Seguimiento" data-bs-toggle="modal"
                                                            data-bs-target="#agregarSeguimiento"
                                                            onclick="cDatAgregarSeg(this)">
                                                            <i class="fa fa-plus"></i>
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

    <!-- Modal 1: detalleBitacoraEnvio -->
    <div class="modal fade" id="detalleBitacoraEnvio" tabindex="-1">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        Detalle Bitácora de Envío
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- ENTIDAD CALIFICADA -->
                    <div class="row mb-2">
                        <div class="col-md-2">
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
                        <div class="col-md-3">
                            <label for="mOfComunicacionResolucion" class="form-label">
                                Oficio Comunicación de Resolución
                            </label>
                            <input type="text" class="form-control" id="mOfComunicacionResolucion" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="mFechaOficioComRes" class="form-label">
                                Fecha Oficio Comunicación
                            </label>
                            <input type="date" class="form-control" id="mFechaOficioComRes" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="mNumResolucionCalificacion" class="form-label">
                                Resolución de Calificación
                            </label>
                            <input type="text" class="form-control" id="mNumResolucionCalificacion" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="mFechaResolucion" class="form-label">
                                Fecha Resolución
                            </label>
                            <input type="date" class="form-control" id="mFechaResolucion" readonly>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-4">
                            <label for="mRegularizaciones" class="form-label">
                                N. REGULARIZACIONES
                            </label>
                            <input type="text" class="form-control" id="mRegularizaciones" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="mProrrogas" class="form-label">
                                N. PRORROGAS
                            </label>
                            <input type="text" class="form-control" id="mProrrogas" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="mObservacionesDNR" class="form-label">
                                N. Observaciones DNR
                            </label>
                            <input type="text" class="form-control" id="mObservacionesDNR" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 p-2">
                        <div class="col-md-12" style="border: 1px solid blue;">
                            <table class="table table-bordered table-striped table-hover table-sm" id="tablaObsDNR">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 15%;">FECHA</th>
                                        <th class="text-center" style="width: 15%;">REGULARIZACIÓN</th>
                                        <th class="text-center" style="width: 15%;">PRÓRROGA</th>
                                        <th class="text-center" style="width: 55%;">OBSERVACIÓN DNR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
 
    <!-- Modal 2: agregarSeguimiento -->
    <div class="modal fade" id="agregarSeguimiento" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        Registro de Seguimiento
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="seguimientoForm">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label for="mAsIdEntidad" class="form-label">ID Entidad</label>
                                <input type="text" class="form-control" id="mAsIdEntidad" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="mAsRucCatastro" class="form-label">RUC</label>
                                <input type="text" class="form-control" id="mAsRucCatastro" disabled>
                            </div>
                            <div class="col-md-5">
                                <label for="mAsRazonSocial" class="form-label">Razón Social</label>
                                <input type="text" class="form-control" id="mAsRazonSocial" disabled>
                            </div>
                            <div class="col-md-2">
                                <label for="mAsFechaCorte" class="form-label">Fecha Corte</label>
                                <input type="text" class="form-control" id="mAsFechaCorte" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="mAsEstadoSeguimiento" class="form-label">
                                    Estado Seguimiento
                                </label>
                                <select class="form-control" id="mAsEstadoSeguimiento" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($catEstSeguimiento as $item): ?>
                                    <option value="<?= htmlspecialchars($item['ID']) ?>">
                                        <?= htmlspecialchars($item['DESCRIPCION']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="mAsOficioSeguimiento" class="form-label">
                                    Oficio Seguimiento
                                </label>
                                <input type="text" class="form-control" id="mAsOficioSeguimiento" required>
                            </div>
                            <div class="col-md-4">
                                <label for="mAsFechaOfSeguimiento" class="form-label">
                                    Fecha Oficio
                                </label>
                                <input type="date" class="form-control" id="mAsFechaOfSeguimiento" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mAsEstadoCumplimiento" class="form-label">
                                    Estado Cumplimiento
                                </label>
                                <select class="form-control" id="mAsEstadoCumplimiento" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($catEstCumplimiento as $cump): ?>
                                    <option value="<?= htmlspecialchars($cump['ID']) ?>">
                                        <?= htmlspecialchars($cump['DESCRIPCION']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="mAsMotivoIncumplimiento" class="form-label">
                                    Motivo Incumplimiento
                                </label>
                                <select class="form-control" id="mAsMotivoIncumplimiento">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($catMotCumplimiento as $mot): ?>
                                    <option value="<?= htmlspecialchars($mot['ID']) ?>">
                                        <?= htmlspecialchars($mot['DESCRIPCION']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">
                                    Regularizado
                                </label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="mAsIndRegularizado">
                                    <label class="form-check-label"
                                        for="mAsIndRegularizado">
                                        Sí
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <label for="mAsObservacionDns" class="form-label">
                                    Observación DNS
                                </label>
                                <textarea class="form-control"
                                    id="mAsObservacionDns"
                                    rows="4"
                                    required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" form="seguimientoForm" class="btn btn-success" id="btnGuardarSeguimiento">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
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