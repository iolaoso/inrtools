<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/psi/psiList.php'; // Archivo con funciones para PSI
//include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
//include BASE_PATH . 'backend/analistasList.php'; // consulta analistas

// Incluye la función de validación
include BASE_PATH . 'backend/validarAcceso.php';

// Define usuarios permitidos (variable configurable)
$usuarios_permitidos = ['DPAGUAY', 'ILOPEZ'];

// Valida acceso
validarAccesoUsuario($nickname, $usuarios_permitidos, $base_url);


// Obtener registros PSI activos con ULTIMO_CORTE = 2
if ($rol_nombre == 'ADMINISTRADOR' || $rol_nombre == 'SUPERUSER' || $rol_nombre == 'DIRECTOR') {
    $result = obtenerPsiActivos();
} else {
    $result = obtenerPsiActivos($nickname, $rol_nombre); // Implementa esta función si quieres filtrar por usuario
}

// Si usas alguna lista de analistas o catálogos, también los puedes incluir aquí
//$analistas = obtenerAnalistas($direccion);

?>

<!DOCTYPE html>
<html lang="es">

<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>
    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">

            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Gestión PSI</h1>
                <p>Registro y edición de datos PSI</p>
            </div>

            <section class="row align-items-stretch">

                <div class="col-md-4 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>PSI - <span style="font-size: medium; font-weight: bold;">Programa de Supervisión
                                    Intensiva</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="container border mb-3 p-3">
                                <div class="mb-3 row ">
                                    <h2>Cargar Archivo de Excel</h2>
                                </div>
                                <div class="mb-3 row">
                                    <input type="file" id="fileInput" accept=".xlsx, .xls" class="form-control mb-3">
                                    <!-- Button trigger modal -->
                                    <button type="button" id="previewBtn" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#previewModal">
                                        <i class="fas fa-eye"></i>
                                        Vista Previa
                                    </button>

                                </div>
                            </div>
                            <div class="container border mb-3 p-3">
                                <form id="formPsi" method="POST" action="#">
                                    <div class="mb-3 row">
                                        <h2>Editar registro PSI</h2>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="id" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id" name="id" readonly />
                                        </div>
                                        <div class="col-8">
                                            <label for="FECHA_CORTE_INFORMACION" class="form-label">FECHA CORTE</label>
                                            <input type="date" class="form-control" id="FECHA_CORTE_INFORMACION"
                                                name="FECHA_CORTE_INFORMACION" require>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="NUMERO" class="form-label">NÚMERO</label>
                                            <input type="text" class="form-control" id="NUMERO" name="NUMERO" require>
                                        </div>
                                        <div class="col-8">
                                            <label for="COD_UNICO" class="form-label">CÓDIGO ÚNICO</label>
                                            <input type="text" class="form-control" id="COD_UNICO" name="COD_UNICO"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">RUC</span>
                                                </div>
                                                <input type="text" class="form-control" id="ruc" name="ruc" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#catastroModal">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="RAZON_SOCIAL" class="form-label">RAZÓN SOCIAL</label>
                                            <input type="text" class="form-control" id="RAZON_SOCIAL"
                                                name="RAZON_SOCIAL" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="SEGMENTO" class="form-label">SEGMENTO</label>
                                            <input type="text" class="form-control" id="SEGMENTO" name="SEGMENTO">
                                        </div>
                                        <div class="col-4">
                                            <label for="ZONAL" class="form-label">ZONAL</label>
                                            <input type="text" class="form-control" id="ZONAL" name="ZONAL">
                                        </div>
                                        <div class="col-4">
                                            <label for="ESTADO_JURIDICO" class="form-label">EST. JURÍDICO</label>
                                            <input type="text" class="form-control" id="ESTADO_JURIDICO"
                                                name="ESTADO_JURIDICO">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-8">
                                            <label for="TIPO_SUPERVISION" class="form-label">TIPO SUPERVISIÓN</label>
                                            <input type="text" class="form-control" id="TIPO_SUPERVISION"
                                                name="TIPO_SUPERVISION">
                                        </div>
                                        <div class="col-4">
                                            <label for="FECHA_APROBACION_PLAN_FISICO" class="form-label">F.
                                                PLAN FÍSICO</label>
                                            <input type="date" class="form-control" id="FECHA_APROBACION_PLAN_FISICO"
                                                name="FECHA_APROBACION_PLAN_FISICO">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-6">
                                            <label for="FECHA_INICIO" class="form-label">FECHA INICIO</label>
                                            <input type="date" class="form-control" id="FECHA_INICIO"
                                                name="FECHA_INICIO">
                                        </div>
                                        <div class="col-6">
                                            <label for="FECHA_FIN" class="form-label">FECHA FIN</label>
                                            <input type="date" class="form-control" id="FECHA_FIN" name="FECHA_FIN">
                                        </div>
                                        <div class="col-3">
                                            <label for="ANIO_INICIO" class="form-label">AÑO INICIO</label>
                                            <input type="text" class="form-control" id="ANIO_INICIO" name="ANIO_INICIO"
                                                readonly>
                                        </div>
                                        <div class="col-3">
                                            <label for="MES_INICIO" class="form-label">MES INICIO</label>
                                            <input type="text" class="form-control" id="MES_INICIO" name="MES_INICIO"
                                                readonly>
                                        </div>
                                        <div class="col-3">
                                            <label for="ANIO_VENCIMIENTO" class="form-label">AÑO VENC.</label>
                                            <input type="text" class="form-control" id="ANIO_VENCIMIENTO"
                                                name="ANIO_VENCIMIENTO" readonly>
                                        </div>
                                        <div class="col-3">
                                            <label for="MES_VENCIMIENTO" class="form-label">MES VENC.</label>
                                            <input type="text" class="form-control" id="MES_VENCIMIENTO"
                                                name="MES_VENCIMIENTO" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="TRIMESTRE" class="form-label">TRIMESTRE</label>
                                            <input type="text" class="form-control" id="TRIMESTRE" name="TRIMESTRE"
                                                readonly>
                                        </div>
                                        <div class="col-4">
                                            <label for="ESTADO_PSI" class="form-label   ">ESTADO PSI</label>
                                            <input type="text" class="form-control" id="ESTADO_PSI" name="ESTADO_PSI"
                                                required>
                                        </div>
                                        <div class="col-4">
                                            <label for="VIGENCIA_PSI" class="form-label">VIGENCIA PSI</label>
                                            <input type="text" class="form-control" id="VIGENCIA_PSI"
                                                name="VIGENCIA_PSI" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="FECHA_INFORME" class="form-label">FEC. INFORME</label>
                                            <input type="date" class="form-control" id="FECHA_INFORME"
                                                name="FECHA_INFORME" required>
                                        </div>
                                        <div class="col-8">
                                            <label for="NUM_INFORME" class="form-label">NÚMERO INFORME</label>
                                            <input type="text" class="form-control" id="NUM_INFORME" name="NUM_INFORME"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="FECHA_RESOLUCION" class="form-label">FEC. RES</label>
                                            <input type="date" class="form-control" id="FECHA_RESOLUCION"
                                                name="FECHA_RESOLUCION" required>
                                        </div>
                                        <div class="col-8">
                                            <label for="NUM_RESOLUCION" class="form-label">NÚMERO RESOLUCIÓN</label>
                                            <input type="text" class="form-control" id="NUM_RESOLUCION"
                                                name="NUM_RESOLUCION" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="FECHA_RESOLUCION_AMPLIACION" class="form-label">FEC. RES.
                                                AMP</label>
                                            <input type="date" class="form-control" id="FECHA_RESOLUCION_AMPLIACION"
                                                name="FECHA_RESOLUCION_AMPLIACION">
                                        </div>
                                        <div class="col-8">
                                            <label for="NUM_RESOLUCION_AMPLIACION" class="form-label">NÚMERO RES.
                                                AMPLIACIÓN</label>
                                            <input type="text" class="form-control" id="NUM_RESOLUCION_AMPLIACION"
                                                name="NUM_RESOLUCION_AMPLIACION">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="FECHA_ULTIMO_BALANCE" class="form-label">FEC. ÚLT.
                                                BAL</label>
                                            <input type="date" class="form-control" id="FECHA_ULTIMO_BALANCE"
                                                name="FECHA_ULTIMO_BALANCE">
                                        </div>
                                        <div class="col-4">
                                            <label for="ACTIVOS" class="form-label">ACTIVOS</label>
                                            <input type="text" class="form-control" id="ACTIVOS" name="ACTIVOS">
                                        </div>
                                        <div class="col-4">
                                            <label for="ULTIMO_RIESGO" class="form-label">ÚLTIMO RIESGO</label>
                                            <input type="text" class="form-control" id="ULTIMO_RIESGO"
                                                name="ULTIMO_RIESGO">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="FECHA_RESOLUCION_FIN_PSI" class="form-label">FEC. RES.
                                                FIN PSI</label>
                                            <input type="date" class="form-control" id="FECHA_RESOLUCION_FIN_PSI"
                                                name="FECHA_RESOLUCION_FIN_PSI">
                                        </div>
                                        <div class="col-8">
                                            <label for="NUM_RESOLUCION_FIN_PSI" class="form-label">RESOLUCIÓN
                                                FIN PSI</label>
                                            <input type="text" class="form-control" id="NUM_RESOLUCION_FIN_PSI"
                                                name="NUM_RESOLUCION_FIN_PSI">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-12">
                                            <label for="MOTIVO_CIERRE" class="form-label">MOTIVO DE CIERRE</label>
                                            <input type="text" class="form-control" id="MOTIVO_CIERRE"
                                                name="MOTIVO_CIERRE">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-12">
                                            <label for="ESTRATEGIA_SUPERVISION" class="form-label">ESTRATEGIA DE
                                                SUPERVISIÓN</label>
                                            <input type="text" class="form-control" id="ESTRATEGIA_SUPERVISION"
                                                name="ESTRATEGIA_SUPERVISION">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-3">
                                            <label for="ULTIMO_CORTE" class="form-label">ULT. CORTE</label>
                                            <input type="number" class="form-control" id="ULTIMO_CORTE"
                                                name="ULTIMO_CORTE" value="2" required>
                                        </div>
                                        <div class="col-9">
                                            <label for="EST_REGISTRO" class="form-label">ESTADO REGISTRO</label>
                                            <select class="form-control" id="EST_REGISTRO" name="EST_REGISTRO" required>
                                                <option value="ACT">ACTIVO</option>
                                                <option value="DEL">ELIMINADO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-12">
                                            <label for="USR_CREACION" class="form-label">Usuario Creación</label>
                                            <input type="text" readonly class="form-control" id="USR_CREACION"
                                                name="USR_CREACION" value="<?= htmlspecialchars($nickname) ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="col-12">
                                            <button class="btn btn-primary btn-sm btn-block save-btn" type="submit"
                                                title="Save" onclick="asignarActions(this, 'save');">
                                                Guardar Registro
                                            </button>
                                            <!-- <button type="button" id="btnLimpiar"
                                                class="btn btn-secondary btn-sm btn-block">Limpiar</button> -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Registros PSI
                                <span style="font-size: medium; font-weight: bold;">(Último corte = 2)</span>
                            </h4>
                            <ul class="list-unstyled d-flex mb-0">
                                <li class="mx-2">
                                    <button id="exportButton" class="btn btn-primary btn-sm"
                                        onclick="exportTableToExcel('tablaPsi', 'ReportePSI')">Exportar</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body" style="overflow-x: auto;">
                            <input class="form-control" type="text" id="searchInput" onkeyup="filterTable('tablaPsi')"
                                placeholder="Buscar...">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaPsi">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>FEC. CORTE</th>
                                                <th>RUC</th>
                                                <th>RAZON SOCIAL</th>
                                                <th>INFORME</th>
                                                <th>RESOLUCION</th>
                                                <th>ESTADO</th>
                                                <th>VIGENCIA</th>
                                                <th>VENCIMIENTO</th>
                                                <th>DETALLE</th>
                                                <th>ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $psi): ?>
                                                <tr data-id="<?= htmlspecialchars($psi['id']) ?>">
                                                    <td><?= htmlspecialchars($psi['id']) ?></td>
                                                    <td>
                                                        <?= htmlspecialchars(isset($psi['FECHA_CORTE_INFORMACION']) ? date('F Y', strtotime($psi['FECHA_CORTE_INFORMACION'])) : 'N/A') ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($psi['RUC'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($psi['RAZON_SOCIAL'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($psi['NUM_INFORME'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($psi['NUM_RESOLUCION'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($psi['ESTADO_PSI'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($psi['VIGENCIA_PSI'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($psi['ANIO_VENCIMIENTO'] ?? 'N/A') ?></td>
                                                    <td class="text-center button-cell">
                                                        <button class="btn btn-primary detalle-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($psi['id'] ?? '') ?>"
                                                            title="Detalle PSI" data-bs-toggle="modal"
                                                            data-bs-target="#detalleModalPsi"
                                                            onclick="cargarDatosPsi(this)">
                                                            <i class="fa-solid fa-comment"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info edit-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($psi['id']) ?>" title="Editar"
                                                            onclick="asignarActions(this, 'edit');">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger delete-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($psi['id']) ?>" title="Eliminar"
                                                            onclick="asignarActions(this, 'delete');">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($result)): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No hay registros para mostrar.</td>
                                                </tr>
                                            <?php endif; ?>
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

    <!-- Modal para Detalle PSI -->
    <div class="modal fade" id="detalleModalPsi" tabindex="-1" aria-labelledby="detalleModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">Detalle PSI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="detalleid" class="form-label">ID</label>
                            <input type="text" class="form-control" id="detalleid" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleFecCorte" class="form-label">Fecha Corte: </label>
                            <input type="text" class="form-control" id="detalleFecCorte" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleRUC" class="form-label">RUC</label>
                            <input type="text" class="form-control" id="detalleRUC" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleSegmento" class="form-label">Segmento</label>
                            <input type="text" class="form-control" id="detalleSegmento" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleZonal" class="form-label">Zonal</label>
                            <input type="text" class="form-control" id="detalleZonal" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleEstadoJuridico" class="form-label">Estado Jurídico</label>
                            <input type="text" class="form-control" id="detalleEstadoJuridico" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleTipoSupervision" class="form-label">Tipo Supervision</label>
                            <input type="text" class="form-control" id="detalleTipoSupervision" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="detalleRazonSocial" class="form-label">Razón Social</label>
                            <input type="text" class="form-control" id="detalleRazonSocial" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detallefechaIni" class="form-label">Fecha Inicio</label>
                            <input type="text" class="form-control" id="detallefechaIni" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detallefechaFin" class="form-label">Fecha Fin</label>
                            <input type="text" class="form-control" id="detallefechaFin" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleMesVenc" class="form-label">Mes de Vencimiento</label>
                            <input type="text" class="form-control" id="detalleMesVenc" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleAnioVenc" class="form-label">Año de Vencimiento</label>
                            <input type="text" class="form-control" id="detalleAnioVenc" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleTrime" class="form-label">Trimestre</label>
                            <input type="text" class="form-control" id="detalleTrime" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleEstado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="detalleEstado" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleVigencia" class="form-label">Vigencia</label>
                            <input type="text" class="form-control" id="detalleVigencia" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleFecAproPF" class="form-label">Fec. Plan Fisico</label>
                            <input type="text" class="form-control" id="detalleFecAproPF" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleNumInf" class="form-label">Número de Informe</label>
                            <input type="text" class="form-control" id="detalleNumInf" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleFecInf" class="form-label">Fecha de Informe</label>
                            <input type="text" class="form-control" id="detalleFecInf" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleNumRes" class="form-label">Resolución</label>
                            <input type="text" class="form-control" id="detalleNumRes" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleFecRes" class="form-label">Fecha de Resolución</label>
                            <input type="text" class="form-control" id="detalleFecRes" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="detalleResAmp" class="form-label">Res. de Ampliación</label>
                            <input type="text" class="form-control" id="detalleResAmp" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="detalleFecResAmp" class="form-label">Fec. Res. Ampliación</label>
                            <input type="text" class="form-control" id="detalleFecResAmp" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleUltBal" class="form-label">Fecha Último Balance</label>
                            <input type="text" class="form-control" id="detalleUltBal" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleActivos" class="form-label">Activos</label>
                            <input type="text" class="form-control" id="detalleActivos" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleUltRiesgo" class="form-label">Último Riesgo</label>
                            <input type="text" class="form-control" id="detalleUltRiesgo" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleResFinPSI" class="form-label">Resolución Fin PSI</label>
                            <input type="text" class="form-control" id="detalleResFinPSI" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleFecResFinPSI" class="form-label">Fecha de Res. Fin PSI</label>
                            <input type="text" class="form-control" id="detalleFecResFinPSI" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleMotCierre" class="form-label">Motvo de Cierre</label>
                            <input type="text" class="form-control" id="detalleMotCierre" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="detalleEstSup" class="form-label">Estrategia de Supervisión</label>
                            <input type="text" class="form-control" id="detalleEstSup" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Vista Previa-->
    <div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="previewModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" role="document">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Vista Previa de los Datos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="table-responsive" style="max-height: 300px;">
                            <table class="table table-bordered table-striped table-hover table-sm" id="tablePreviewPsi">
                                <thead>
                                    <tr id="tableHeaders"></tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="confirmUploadBtn" class="btn btn-success">Cargar a BDD</button>
                </div>
            </div>
        </div>
    </div>


    <div id="base_url" data-base-url="<?= htmlspecialchars($base_url) ?>"></div>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/psi/psi.js"></script>


    <script>
        // Filtrar tabla por búsqueda
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('#tablaPsi tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        }

        // Cargar datos del registro en el formulario para editar
        document.querySelectorAll('.btn-editar').forEach(button => {
            button.addEventListener('click', () => {
                const tr = button.closest('tr');
                const id = tr.getAttribute('data-id');

                // Recorremos las celdas para llenar el formulario según los campos
                document.getElementById('id').value = id;
                document.getElementById('NUMERO').value = tr.children[1].textContent.trim();
                document.getElementById('COD_UNICO').value = tr.children[2].textContent.trim();
                document.getElementById('RUC').value = tr.children[3].textContent.trim();
                document.getElementById('RAZON_SOCIAL').value = tr.children[4].textContent.trim();
                document.getElementById('ULTIMO_CORTE').value = tr.children[5].textContent.trim();
                document.getElementById('EST_REGISTRO').value = tr.children[6].textContent.trim();

                // Aquí puedes cargar más campos si los agregas al formulario
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        // Eliminar registro (soft delete)
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', () => {
                if (!confirm('¿Seguro que deseas eliminar este registro?')) return;
                const tr = button.closest('tr');
                const id = tr.getAttribute('data-id');

                fetch('<?= $base_url ?>backend/psiActions.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            accion: 'eliminar',
                            id: id
                        })
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Registro eliminado correctamente');
                            tr.remove();
                            document.getElementById('formPsi').reset();
                        } else {
                            alert('Error al eliminar');
                        }
                    });
            });
        });

        // Limpiar formulario
        document.getElementById('btnLimpiar').addEventListener('click', () => {
            document.getElementById('formPsi').reset();
            document.getElementById('id').value = 0;
        });
    </script>

</body>

</html>