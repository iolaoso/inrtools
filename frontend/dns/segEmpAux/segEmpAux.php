<?php

use PhpParser\Node\Expr\Include_;

include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/empAux/empAuxList.php'; // Incluir el archivo de consultas

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
    'SUPERUSER',
    'ADMINISTRADOR',
    'DIRECTOR',
    'DIRADMINDR',
    'DIRADMINDNS',
    'DIRADMINDNSES',
    'DIRADMINPLA'
];

if (in_array($rol_nombre, $rolesDireccion)) {
    $corteSeguimiento = corte_seguimiento();
    $bitacorasEnvio = bitacora_entrega_full();
} else {
    $corteSeguimiento = corte_seguimiento_usr($nickname);
    $bitacorasEnvio = bitacora_entrega_full_usr($nickname);
}

$catEstSeguimiento = cat_est_seguimiento();
$catEstCumplimiento = cat_est_cumplimiento();
$catMotCumplimiento = cat_mot_cumplimiento(); 
// para revisar que llega en la variable 
//var_dump($corteSeguimiento); 


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
            <!-- seguimiento de empresas auxiliares  -->
            <section class="row align-items-stretch">
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
                                                <th class="text-center">PROXIMO ENVIO</th>  
                                                <th class="text-center">PERÍODO</th>
                                                <th class="text-center">CORTE</th>
                                                <th class="text-center">ESTADO</th>
                                                <th class="text-center">ESTADO PROCESO</th>
                                                <th class="text-center">BITACORA</th>
                                                <th class="text-center">DNR</th>
                                                <th class="text-center">SEGUIMIENTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($corteSeguimiento as $cSeg): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($cSeg['RUC'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($cSeg['RAZON_SOCIAL'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($cSeg['SERVICIO_PRESTADO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($cSeg['NUM_RESOLUCION_CALIFICACION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($cSeg['FECHA_RESOLUCION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($cSeg['FEC_PROX_ENVIO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($cSeg['NUM_PERIODO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($cSeg['FECHA_CORTE'] ?? '') ?></td>
                                                <?php
                                                    $estado = htmlspecialchars($cSeg['ESTADO'] ?? '');
                                                    if ($estado == 'ENVIADO') {
                                                        $clase = 'badge rounded-pill bg-success'; // Celeste
                                                    } elseif ($estado == 'NO ENVIADO') {
                                                        $clase = 'badge rounded-pill bg-danger'; 
                                                    } elseif ($estado == 'PENDIENTE') {
                                                        $clase = 'badge rounded-pill bg-primary'; 
                                                    } else {
                                                        $clase = 'badge rounded-pill bg-secondary'; 
                                                    }
                                                ?>
                                                <td class="text-center">
                                                    <span class="<?= $clase ?>"><?= htmlspecialchars($estado) ?></span>
                                                </td>
                                                <?php
                                                $estadoProceso = strtoupper(trim(htmlspecialchars($cSeg['ESTADO_PROCESO'] ?? '')));
                                                switch ($estadoProceso) {
                                                    case 'SEGUIMIENTO':
                                                        $clase = 'badge bg-success';
                                                        break;
                                                    case 'OFICIO':
                                                        $clase = 'badge bg-warning text-dark';
                                                        break;
                                                    case 'REUNION':
                                                        $clase = 'badge bg-danger';
                                                        break;
                                                    case 'PENDIENTE':
                                                        $clase = 'badge bg-info';
                                                        break;
                                                    case '':
                                                    case 'SIN ESTADO':
                                                    default:
                                                        $clase = 'badge bg-secondary';
                                                        break;
                                                }
                                                ?>
                                                <td class="text-center">
                                                    <span class="<?= $clase ?>"><?= htmlspecialchars($cSeg['ESTADO_PROCESO'] ?? '') ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary detalle-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($cSeg['ID_ENTIDAD'] ?? '') ?>"
                                                        data-ruc="<?= htmlspecialchars($cSeg['RUC'] ?? '') ?>"
                                                        data-periodo="<?= htmlspecialchars($cSeg['PERIODO'] ?? '') ?>"
                                                        data-fecRes="<?= htmlspecialchars($cSeg['FECHA_RESOLUCION'] ?? '') ?>"
                                                        data-periodo="<?= htmlspecialchars($cSeg['NUM_PERIODO'] ?? '') ?>"
                                                        data-fCorte="<?= htmlspecialchars($cSeg['FECHA_CORTE'] ?? '') ?>"
                                                        title="Bitácora de Envio" data-bs-toggle="modal"
                                                        data-bs-target="#detalleBitacoraEnvio"
                                                        onclick="cDatSegEmpAux(this)">
                                                        <i class="fa-solid fa-comment"></i>
                                                    </button>
                                                </td>
                                                <td class="text-center p-1" >
                                                    <div class="btn-group">
                                                        <button class="btn btn-info btn-sm" disabled>
                                                            R. <?= $cSeg['REGULARIZACIONES'] ?>
                                                        </button>
                                                        <button class="btn btn-primary btn-sm" disabled>
                                                            P. <?= $cSeg['PRORROGAS'] ?>
                                                        </button>
                                                        <button class="btn btn-secondary btn-sm" disabled>
                                                            O. <?= $cSeg['OBSERVACIONES_DNR'] ?>
                                                        </button>
                                                        <button class="btn btn-info btn-sm"
                                                            data-id="<?= htmlspecialchars($cSeg['ID_ENTIDAD'] ?? '') ?>"
                                                            data-ruc="<?= htmlspecialchars($cSeg['RUC'] ?? '') ?>"
                                                            data-fecRes="<?= htmlspecialchars($cSeg['FECHA_RESOLUCION'] ?? '') ?>"
                                                            data-periodo="<?= htmlspecialchars($cSeg['NUM_PERIODO'] ?? '') ?>"
                                                            data-fCorte="<?= htmlspecialchars($cSeg['FECHA_CORTE'] ?? '') ?>"
                                                            title="Agregar observación DNR"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#agregarProcesoDNR"
                                                            onclick="cDatAgregarProcDNR(this)">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php
                                                            $seguimientos = (int)($cSeg['NUM_SEGUIMIENTOS'] ?? 0);
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
                                                        <button
                                                            class="btn btn-success detalle-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($cSeg['ID_ENTIDAD'] ?? '') ?>"
                                                            data-ruc="<?= htmlspecialchars($cSeg['RUC'] ?? '') ?>"
                                                            data-fecres="<?= htmlspecialchars($cSeg['FECHA_RESOLUCION'] ?? '') ?>"
                                                            data-periodo="<?= htmlspecialchars($cSeg['NUM_PERIODO'] ?? '') ?>"
                                                            data-fcorte="<?= htmlspecialchars($cSeg['FECHA_CORTE'] ?? '') ?>"
                                                            data-razonsocial="<?= htmlspecialchars($cSeg['RAZON_SOCIAL'] ?? '') ?>"
                                                            title="Agregar / Editar Seguimiento"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#agregarSeguimiento"
                                                            onclick="cDatAgregarSeg(this)">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-dark detalle-btn btn-sm"
                                                            title="Generar Oficio"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#generarOficio"
                                                            onclick="">
                                                            
                                                            <i class="fa-regular fa-file-word"></i> 
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
            <!-- full cargas empresas  -->
            <section class="row align-items-stretch">
                <div class="col-md-12 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header card-header bg-black text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Bitacora Empresas Auxiliares</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaBitacoraEnvio">
                                        <thead>
                                            <tr>
                                                <th class="text-center">RUC</th>
                                                <th class="text-center">RAZÓN SOCIAL</th>
                                                <th class="text-center">SERVICIO</th>
                                                <th class="text-center">RES. CALIFICACIÓN</th>
                                                <th class="text-center">FEC. RESOLUCIÓN</th>  
                                                <th class="text-center">PROXIMO ENVIO</th>  
                                                <th class="text-center">PERÍODO</th>
                                                <th class="text-center">CORTE</th>
                                                <th class="text-center">ESTADO</th>
                                                <th class="text-center">ESTADO PROCESO</th>
                                                <th class="text-center">BITACORA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bitacorasEnvio as $bitacoraEnvio): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($bitacoraEnvio['RUC'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bitacoraEnvio['RAZON_SOCIAL'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($bitacoraEnvio['SERVICIO_PRESTADO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEnvio['NUM_RESOLUCION_CALIFICACION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEnvio['FECHA_RESOLUCION'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEnvio['FEC_PROX_ENVIO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEnvio['NUM_PERIODO'] ?? '') ?></td>
                                                <td class="text-center"><?= htmlspecialchars($bitacoraEnvio['FECHA_CORTE'] ?? '') ?></td>
                                                <?php
                                                    $estado = htmlspecialchars($bitacoraEnvio['ESTADO'] ?? '');
                                                    if ($estado == 'ENVIADO') {
                                                        $clase = 'badge rounded-pill bg-success'; // Celeste
                                                    } elseif ($estado == 'NO ENVIADO') {
                                                        $clase = 'badge rounded-pill bg-danger'; 
                                                    } elseif ($estado == 'PENDIENTE') {
                                                        $clase = 'badge rounded-pill bg-primary'; 
                                                    } else {
                                                        $clase = 'badge rounded-pill bg-secondary'; 
                                                    }
                                                ?>
                                                <td class="text-center">
                                                    <span class="<?= $clase ?>"><?= htmlspecialchars($estado) ?></span>
                                                </td>
                                                <?php
                                                $estadoProceso = strtoupper(trim(htmlspecialchars($bitacoraEnvio['ESTADO_PROCESO'] ?? '')));
                                                switch ($estadoProceso) {
                                                    case 'SEGUIMIENTO':
                                                        $clase = 'badge bg-info';
                                                        break;
                                                    case 'OFICIO':
                                                        $clase = 'badge bg-warning text-dark';
                                                        break;
                                                    case 'REUNION':
                                                        $clase = 'badge bg-danger';
                                                        break;
                                                    case 'REGULARIZADO':
                                                        $clase = 'badge bg-success';
                                                        break;
                                                    case '':
                                                    case 'SIN ESTADO':
                                                    default:
                                                        $clase = 'badge bg-secondary';
                                                        break;
                                                }
                                                ?>
                                                <td class="text-center">
                                                    <span class="<?= $clase ?>"><?= htmlspecialchars($bitacoraEnvio['ESTADO_PROCESO'] ?? '') ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary detalle-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($bitacoraEnvio['ID_ENTIDAD'] ?? '') ?>"
                                                        data-ruc="<?= htmlspecialchars($bitacoraEnvio['RUC'] ?? '') ?>"
                                                        data-periodo="<?= htmlspecialchars($bitacoraEnvio['PERIODO'] ?? '') ?>"
                                                        data-fecRes="<?= htmlspecialchars($bitacoraEnvio['FECHA_RESOLUCION'] ?? '') ?>"
                                                        data-periodo="<?= htmlspecialchars($bitacoraEnvio['NUM_PERIODO'] ?? '') ?>"
                                                        data-fCorte="<?= htmlspecialchars($bitacoraEnvio['FECHA_CORTE'] ?? '') ?>"
                                                        title="Bitácora de Envio" data-bs-toggle="modal"
                                                        data-bs-target="#detalleBitacoraEnvio"
                                                        onclick="cDatSegEmpAux(this)">
                                                        <i class="fa-solid fa-comment"></i>
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
                                REGULARIZACIONES
                            </label>
                            <input type="text" class="form-control" id="mRegularizaciones" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="mProrrogas" class="form-label">
                                PRORR
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
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="tituloSeguimiento">
                        Registro de Seguimiento
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col col-md-6">
                            <!-- HISTORIAL -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Historial de Seguimientos
                                    </div>
                                    <div class="card-body p-2">
                                        <table class="table table-sm table-bordered table-hover mb-0"
                                            id="tablaHistorialSeguimientos">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%">#</th>
                                                    <th style="width:10%">Fecha</th>
                                                    <th style="width:20%">Oficio</th>
                                                    <th style="width:10%">Est</th>
                                                    <th style="width:20%">Cump</th>
                                                    <th style="width:20%">Reg</th>
                                                    <th style="width:10%">Act</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Nuevo Seguimiento 
                                </div>
                                <div class="card-body p-2">                                    
                                    <form id="seguimientoForm">
                                        <!-- ID oculto para edición -->
                                        <input type="hidden" id="mAsIdSeguimiento">
                                        <!-- DATOS ENTIDAD -->
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <label for="mAsIdEntidad" class="form-label">ID</label>
                                                <input type="text" class="form-control" id="mAsIdEntidad" disabled>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="mAsPeriodo" class="form-label">Periodo</label>
                                                <input type="text" class="form-control" id="mAsPeriodo" disabled>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="mAsRucCatastro" class="form-label">RUC</label>
                                                <input type="text" class="form-control" id="mAsRucCatastro" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="mAsFechaCorte" class="form-label">Fecha Corte</label>
                                                <input type="text" class="form-control" id="mAsFechaCorte" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb3">
                                            <div class="col-md-12">
                                                <label for="mAsRazonSocial" class="form-label">Razón Social</label>
                                                <input type="text" class="form-control" id="mAsRazonSocial" disabled>
                                            </div>
                                        </div>
                                        <!-- SELECCIÓN DE SEGUIMIENTO -->
                                        <!-- <div class="row mb-3">
                                            <div class="col-md-8">
                                                <label for="mAsSeguimientoExistente" class="form-label">
                                                    Seguimiento Registrado
                                                </label>
                                                <select class="form-select" id="mAsSeguimientoExistente">
                                                    <option value="">Nuevo seguimiento...</option>
                                                    <?php $seguimientos = $cSeg['NUM_SEGUIMIENTOS']; ?>
                                                    <?php foreach ($seguimientos as $seg): ?>
                                                        <option value="<?= $seg['ID_SEGUIMIENTO'] ?>">
                                                            #<?= $seg['NUMERO_SEGUIMIENTO'] ?>
                                                            - <?= $seg['OFICIO_SEGUIMIENTO'] ?>
                                                            - <?= $seg['FECHA_OF_SEGUIMIENTO'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                
                                            </div>
                                        </div> -->
                                        <!-- DATOS DEL SEGUIMIENTO -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="mAsEstadoSeguimiento" class="form-label">
                                                    Estado Seguimiento
                                                </label>
                                                <input type="text" class="form-control" id="mAsEstadoSeguimiento" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="mAsEstadoCumplimiento" class="form-label">
                                                    Estado Cumplimiento
                                                </label>
                                                <input type="text" class="form-control" id="mAsEstadoCumplimiento" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-8">
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
                                                <label for="mAsMotivoIncumplimiento" class="form-label">
                                                    Motivo Incumplimiento
                                                </label>
                                                <select class="form-control"
                                                    id="mAsMotivoIncumplimiento">
                                                    <option value="">
                                                        Seleccione...
                                                    </option>
                                                    <?php foreach ($catMotCumplimiento as $mot): ?>
                                                    <option value="<?= htmlspecialchars($mot['ID']) ?>">
                                                        <?= htmlspecialchars($mot['DESCRIPCION']) ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">                                            
                                            <div class="col-md-9">
                                                <label for="mAsObservacionDns" class="form-label">
                                                    Observación DNS
                                                </label>
                                                <textarea class="form-control" id="mAsObservacionDns" rows="1" maxlength="500" required></textarea>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Regularizado</label>
                                                <div class="form-check mt-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="mAsIndRegularizado">
                                                    <label
                                                        class="form-check-label"
                                                        for="mAsIndRegularizado">
                                                        Sí
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-primary"
                        id="btnNuevoSeguimiento">
                        <i class="fas fa-plus"></i>
                        Nuevo Seguimiento
                    </button>
                    <button
                        type="submit"
                        form="seguimientoForm"
                        class="btn btn-success"
                        id="btnGuardarSeguimiento">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal 3: agregarProcesoDNR -->
    <div class="modal fade" id="agregarProcesoDNR" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Registro de Proceso DNR</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="procesoDNRForm">
                        <!-- Datos de la entidad -->
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="mApIdEntidad" class="form-label">ID Entidad</label>
                                <input type="text" class="form-control" id="mApIdEntidad" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="mApRucCatastro" class="form-label">RUC</label>
                                <input type="text" class="form-control" id="mApRucCatastro" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="mApFechaRes" class="form-label">Fecha Resolución</label>
                                <input type="date" class="form-control" id="mApFechaRes" disabled>
                            </div>
                            <div class="col-md-1">
                                <label for="mApPeriodo" class="form-label">Periodo</label>
                                <input type="text" class="form-control" id="mApPeriodo" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="mApFechaCorte" class="form-label">Fecha Corte</label>
                                <input type="date" class="form-control" id="mApFechaCorte" disabled>
                            </div>
                        </div>
                        <!-- Datos del catastro-->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mApOfComunicacionRes" class="form-label">Oficio Comunicación Resolución</label>
                                <input type="text" class="form-control" id="mApOfComunicacionRes">
                            </div>
                            <div class="col-md-6">
                                <label for="mApFecOfComunicacionRes" class="form-label">Fecha de Comunicación Resolución</label>
                                <input type="date" class="form-control" id="mApFecOfComunicacionRes">
                            </div>
                        </div>
                        <!-- Datos del proceso -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mApFecRegularizacion" class="form-label">Fecha de Regularización</label>
                                <input type="date" class="form-control" id="mApFecRegularizacion">
                            </div>
                            <div class="col-md-6">
                                <label for="mApFecProrroga" class="form-label">Fecha de Prórroga</label>
                                <input type="date" class="form-control" id="mApFecProrroga">
                            </div>
                        </div>
                        <!-- Observación -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="mApObservacionDNR" class="form-label">Observación DNR</label>
                                <textarea class="form-control" id="mApObservacionDNR" rows="5" maxlength="500" required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="procesoDNRForm" class="btn btn-success" id="btnGuardarProcesoDNR">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 4: generarOficio -->
    <div class="modal fade" id="generarOficio" tabindex="-1">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        Formato: Oficio de Incumplimiento
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
                        <div class="col-md-12">
                            <label class="form-label">Oficio de Seguimiento</label>
                            <select class="form-select" id="mOficioSeguimiento">
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Período</th>
                                <th>Fecha Corte</th>
                                <th>Fecha Límite</th>
                                <th>Días de Atraso</th>
                            </tr>
                        </thead>
                        <tbody id="tblPendientes">
                        </tbody>
                    </table>
                    <!-- INFORMACION DE CALIFICACION -->
                    <div class="row mb-3">
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
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">
                                Vista previa del oficio
                            </label>

                            <textarea
                                id="mTextoOficio"
                                class="form-control"
                                rows="15">
                            </textarea>
                        </div>
                    </div>

                   
                </div>
                <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>

                <button id="btnPrevisualizar" class="btn btn-info">
                    Previsualizar
                </button>

                <button id="btnGenerarWord" class="btn btn-primary">
                    Generar Oficio
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
     <script src="<?php echo $base_url; ?>/assets/js/empAux/funcEmpAux.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/empAux/segEmpAux.js"></script>
</body>

</html>