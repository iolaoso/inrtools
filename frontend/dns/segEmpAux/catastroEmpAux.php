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

$catastroEmpAux = obtenerCatastroEmpAux();
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
                <h1 class="display-6 tituloPagina">Catastro Empresas Auxiliares</h1>
                <p>Empresas auxiliares Registradas y estado de las mismas</p>
            </div>
            <!-- Catastro Empresa Auxiliares -->
            <section class="row align-items-stretch mb-4">
                <!-- Cambiar align-items-center a align-items-stretch -->
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header card-header text-white d-flex justify-content-between align-items-center" style="background-color: #059b87ff;">
                            <h4>Catastro</h4>
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button class="btn btn-primary btn-sm"
                                    onclick="window.open(baseurl + '/backend/empAux/catastroPublicoXlsx.php','_blank')">
                                    <i class="fa-regular fa-file-lines"></i>
                                    Reporte Catastro Público
                                </button>
                                <button class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#nuevoCatastroEmpAux">
                                    <i class="fa fa-plus"></i>
                                    Nueva Empresa
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 800px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaCatastroEmpAux">
                                        <thead class="text-center">
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">RUC</th>
                                                <th class="text-center">RAZON SOCIAL</th>
                                                <th class="text-center">TIPO ORGANIZACION</th>
                                                <th class="text-center">NUM RESOLUCION CALIFICACION</th>
                                                <th class="text-center">FECHA RESOLUCION</th>
                                                <th class="text-center">SERVICIO PRESTADO</th>
                                                <th class="text-center not-export">DETALLE</th>
                                                <th class="text-center">NUEVA</th>
                                                <th class="text-center">CON CONDICION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rTBodyCatastroEmpAux">
                                            <?php foreach ($catastroEmpAux as $c_empAux): ?>
                                                <tr>
                                                    <td class="text-center"><?= htmlspecialchars($c_empAux['ID']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['RUC']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['RAZON_SOCIAL']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['TIPO_ORGANIZACION']) ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($c_empAux['NUM_RESOLUCION_CALIFICACION']) ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($c_empAux['FECHA_RESOLUCION']) ?></td>
                                                    <td><?= htmlspecialchars($c_empAux['SERVICIO_PRESTADO']) ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary detalle-btn btn-sm"
                                                            data-ruc="<?= htmlspecialchars($c_empAux['RUC'] ?? '') ?>"
                                                            title="Catastro Emp. Aux." data-bs-toggle="modal"
                                                            data-bs-target="#detalleCatastroEmpAux"
                                                            onclick="cDatCatastroEmpAux(this)">
                                                            <i class="fa-solid fa-comment"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center"><?= htmlspecialchars($c_empAux['NUEVA']) ?></td>
                                                    <?php
                                                    $estado = htmlspecialchars($c_empAux['CON_CONDICION'] ?? '');
                                                    if ($estado == "NO") {
                                                        $clase = 'btn btn-success detalle-btn btn-sm'; // Celeste
                                                    } else {
                                                        $clase = 'btn btn-danger detalle-btn btn-sm'; 
                                                    }                                               ?>
                                                <td class="text-center">
                                                    <span class="<?= $clase ?>"><?= $c_empAux['CON_CONDICION'] ?></span>
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

    <!-- Modal ingresar empresa Catastro  -->
    <div class="modal fade" id="nuevoCatastroEmpAux" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Empresa Auxiliar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="frmNuevaEmpAux">
                        <div class="row mb-2">
                            <div class="col-md-3">    
                                    <input class="form-check-input" type="hidden" id="nueva" checked>
                                <label class="form-label">RUC</label>
                                <input type="text" class="form-control" id="ruc" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Razón Social</label>
                                <input type="text" class="form-control" id="razonSocial" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipo Organización</label>
                                <input type="text" class="form-control" id="tipoOrganizacion">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Of. Comunicación Resolución</label>
                                <input type="text" class="form-control" id="ofComRes">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha Oficio</label>
                                <input type="date" class="form-control" id="fecOfComRes">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resolución Calificación</label>
                                <input type="text" class="form-control" id="numResolucion">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Fecha Resolución</label>
                                <input type="date" class="form-control" id="fecResolucion">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha Vencimiento</label>
                                <input type="date" class="form-control" id="fecVencimiento">
                            </div>
                        </div>
                        <hr>
                        <label class="form-label fw-bold">Servicios Prestados</label>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="software"><label class="form-check-label">Software Financiero</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="transaccionales"><label class="form-check-label">Transaccionales y Pago</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="transporte"><label class="form-check-label">Transporte de Valores</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="red"><label class="form-check-label">Red y Cajeros</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="cobranzas"><label class="form-check-label">Cobranzas</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="contables"><label class="form-check-label">Servicios Contables</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="cartera"><label class="form-check-label">Generadoras de Cartera</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="tarjetas"><label class="form-check-label">Operadoras de Tarjetas</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="inmobiliario"><label class="form-check-label">Giro Inmobiliario</label></div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Representante Legal</label>
                                <input type="text" class="form-control" id="representanteLegal">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correoElectronico">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Condición</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condicion" id="sinCondicion" value="0">
                                    <label class="form-check-label" for="sinCondicion">Sin Condición</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condicion" id="conCondicion" value="1">
                                    <label class="form-check-label" for="conCondicion"> Con Condición</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" form="frmNuevaEmpAux" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal: detalleCatastroEmpAux -->
    <div class="modal fade" id="detalleCatastroEmpAux" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Detalle Catastro Empresa Auxiliar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="frmDetalleCatastroEmpAux">

                        <input type="hidden" id="mIdEntidad">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">RUC</label>
                                <input type="text" class="form-control" id="mRuc">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Razón Social</label>
                                <input type="text" class="form-control" id="mRazonSocial">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tipo Organización</label>
                                <input type="text" class="form-control" id="mTipoOrganizacion">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Of. Comunicación Resolución</label>
                                <input type="text" class="form-control" id="mOfComunicacionResolucion">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Fecha Oficio Comunicación</label>
                                <input type="date" class="form-control" id="mFechaOfComRes">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Resolución Calificación</label>
                                <input type="text" class="form-control" id="mNumResolucionCalificacion">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha Resolución</label>
                                <input type="date" class="form-control" id="mFechaResolucion">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha Vencimiento Resolución</label>
                                <input type="date" class="form-control" id="mFechaVencimientoRes">
                            </div>
                        </div>

                        <hr>

                        <label class="form-label fw-bold">
                            Servicios Prestados
                        </label>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mSoftwareFinanciero">
                                    <label class="form-check-label">Software Financiero y Computacional</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mTransaccionalesPago">
                                    <label class="form-check-label">Transaccionales y de Pago</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mTransporteValores">
                                    <label class="form-check-label">Transporte de Especies Monetarias y Valores</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mRedCajeros">
                                    <label class="form-check-label">Red y Cajeros Automáticos</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mCobranzas">
                                    <label class="form-check-label">Cobranzas</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mServiciosContables">
                                    <label class="form-check-label">Servicios Contables</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mGeneradorasCartera">
                                    <label class="form-check-label">Generadoras de Cartera</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mOperadorasTarjetas">
                                    <label class="form-check-label">Administradoras y Operadoras de Tarjetas</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mGiroInmobiliario">
                                    <label class="form-check-label">Giro Inmobiliario</label>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Representante Legal</label>
                                <input type="text" class="form-control" id="mRepresentanteLegal">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="mCorreoElectronico">
                            </div>
                        </div>

                        <div class="row mb-2">

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mNueva">
                                    <label class="form-check-label">Nueva</label>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label d-block">Condición</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                        type="radio"
                                        name="mCondicion"
                                        id="mSinCondicion"
                                        value="0">

                                    <label class="form-check-label">
                                        Sin Condición
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                        type="radio"
                                        name="mCondicion"
                                        id="mConCondicion"
                                        value="1">

                                    <label class="form-check-label">
                                        Con Condición
                                    </label>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <!-- <button
                        class="btn btn-success"
                        id="btnGuardarCatastroEmpAux">
                        <i class="fa fa-save"></i>
                        Guardar Cambios
                    </button> -->
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
    <script src="<?php echo $base_url; ?>/assets/js/empAux/catastroEmpAux.js"></script>
    
</body>

</html>