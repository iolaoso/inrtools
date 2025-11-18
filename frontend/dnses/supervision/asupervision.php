<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas
include BASE_PATH . 'backend/supervision/supervisionList.php'; // Incluir el archivo de consultas


$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);
$estrategias = obtenerEstrategias($inrdireccion_id, $rol_id);



// Definir roles con acceso a nivel de dirección
$rolesDireccion = [
    'ADMINISTRADOR',
    'DIRECTOR',
    'DIRADMINDR',
    'DIRADMINDNS',
    'DIRADMINDNSES',
    'DIRADMINPLA'
];

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
            <!-- TITULO DE LA PAGINA -->
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Avances de Supervisión</h1>
                <p>Estado de los procesos de supervisión implementados por la INR</p>
            </div>
            <!-- Formulario de datos -->
            <form id="frmDatosFull" method="POST" autocomplete="off" onsubmit="return guardarSupervision(event);">
                <!-- BOTONES FLOTANTES -->
                <div class="floating-buttons mb-3">
                    <!-- Botones de acción -->
                    <button type="button" class="btn btn-sm btn-primary mx-0" onclick="abrirBuscarSupervisionModal()">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                    <button type="button" class="btn btn-sm btn-danger mx-0" onclick="nuevoRegistroSupervision()">
                        <i class="fas fa-plus me-1"></i> Nuevo
                    </button>
                    <button type="submit" class="btn btn-sm btn-success mx-0">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-sm btn-warning mx-0" onclick="eliminarSupervision(idAvanceSupervision,codUnico)">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-sm btn-info mx-0" onclick="limpiarTodosLosFormularios()">
                        <i class="fas fa-eraser me-1"></i> Limpiar
                    </button>
                </div>

                <!-- ENTIDAD -->
                <section id="sDatosEntidad" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h4>Información de la Entidad</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sDatosEntidad">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Datos Entidad -->
                                <div class="seccion-formulario mb-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">RUC</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="ruc" name="ruc"
                                                    oninput="buscarEntidad()" data-page="asupervision.php" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                        data-bs-target="#catastroModal">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Segmento</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="tbsegmento" name="tbsegmento" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">Razón Social</span>
                                                <input type="text" class="form-control form-control-sm" id="tbrazonSocial" name="tbrazonSocial" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">ID</span>
                                                <input type="text" class="form-control form-control-sm" id="id_avances" name="id_avances" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">Código Único</span>
                                                <input type="text" class="form-control form-control-sm" id="cod_unico_avances" name="cod_unico_avances" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- AVANCES SUPERVISION -->
                <section id="sAvancesSupervision" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h4>Avances de Supervisión</h4>
                                <div class="div" style="margin-left: auto; margin-top:1%; width: 200px; text-align: center; padding-right: 50px;">
                                    <div class="input-group mb-3 flex-nowrap">
                                        <span class="input-group-text" id="basic-addon1">ID</span>
                                        <input type="text" class="form-control form-control-sm w-auto text-center" id="IDcentral" name="IDcentral" placeholder="ID" disabled>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sAvancesSupervision">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Información General -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Información General</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="estrategia" class="form-label">Estrategia</label>
                                            <select class="form-control form-control-sm" id="estrategia" name="estrategia" required>
                                                <option value="">Seleccione una Estrategia</option>
                                                <?php foreach ($estrategias as $estrategia): ?>
                                                    <option value="<?= htmlspecialchars($estrategia['ID']) ?>">
                                                        <?= htmlspecialchars($estrategia['ESTRATEGIA']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fase" class="form-label">Fase</label>
                                            <select class="form-control form-control-sm" id="fase" name="fase" required>
                                                <option value="0">Seleccione...</option>
                                                <!-- datos dinamicos desde php -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="analistaSelect" class="form-label">Responsable</label>
                                            <select class="form-control form-control-sm" id="analistaSelect" name="analistaSelect"
                                                onchange="actualizarAnalista()">
                                                <option value="">Seleccione un analista</option>
                                                <?php foreach ($analistas as $analista): ?>
                                                    <option value="<?= htmlspecialchars($analista['NICKNAME']) ?>">
                                                        <?= htmlspecialchars($analista['NOMBRE']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="analista" class="form-label">Analista</label>
                                            <input type="text" class="form-control form-control-sm" id="analista" name="analista" value="<?= htmlspecialchars($nickname)   ?>" style="text-transform: uppercase;" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="estadoSupervision" class="form-label">Estado Supervisión</label>
                                            <input type="text" class="form-control form-control-sm" id="estadoSupervision" name="estadoSupervision" style="text-transform: uppercase;" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Planificación y Avance -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Planificación y Avance</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_asig" class="form-label">Fecha Asignación</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_asig" name="fec_asig" redquired>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="anio_plan" class="form-label">Año Planificado</label>
                                            <input type="number" class="form-control form-control-sm" id="anio_plan" name="anio_plan" min="2020" max="2050" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="trim_plan" class="form-label">Trimestre Planificado</label>
                                            <select class="form-control form-control-sm" id="trim_plan" name="trim_plan" required>
                                                <option value="">Seleccione...</option>
                                                <option value="I">I</option>
                                                <option value="II">II</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="porc_avance" class="form-label">Porcentaje de Avance</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm" id="porc_avance" name="porc_avance" min="0" max="100" readonly>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>

                <!-- SUPERVISIONES -->
                <section id="sSupervisiones" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Supervisiones</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sSupervisiones">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Proceso de Solicitud -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Proceso de Solicitud</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision" class="form-label">ID Supervisión</label>
                                            <input type="text" class="form-control form-control-sm" id="id_supervision" name="id_supervision" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_solicitud" class="form-label">Fecha Solicitud</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_solicitud" name="fec_solicitud">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_solicitud" class="form-label">Oficio de Solicitud</label>
                                            <input type="text" class="form-control form-control-sm" id="num_oficio_solicitud" name="num_oficio_solicitud" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_insistencia" class="form-label">Fecha Insistencia</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_insistencia" name="fec_insistencia">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_insistencia" class="form-label">Oficio de Insistencia</label>
                                            <input type="text" class="form-control form-control-sm" id="num_oficio_insistencia" name="num_oficio_insistencia" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación y Respuesta -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación y Respuesta</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_comunicacion" class="form-label">Fecha Comunicación</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_comunicacion" name="fec_comunicacion">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_oficio_resultados" class="form-label">Oficio de Resultados</label>
                                            <input type="text" class="form-control form-control-sm" id="num_oficio_resultados" name="num_oficio_resultados" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_limite_entrega" class="form-label">Fecha Límite Entrega</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_limite_entrega" name="fec_limite_entrega">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_respuesta" class="form-label">Fecha Respuesta</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_respuesta" name="fec_respuesta">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_respuesta" class="form-label">Oficio de Respuesta</label>
                                            <input type="text" class="form-control form-control-sm" id="num_oficio_respuesta" name="num_oficio_respuesta" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Informe Final y Comunicación -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe Final y Comunicación</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_final" class="form-label">Fecha Informe Final</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_informe_final" name="fec_informe_final">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="informe_final" class="form-label">Número Informe Final</label>
                                            <input type="text" class="form-control form-control-sm" id="informe_final" name="informe_final" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_comunicacion_final" class="form-label">Fecha Comunicación Final</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_comunicacion_final" name="fec_comunicacion_final">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_comunicacion_final" class="form-label">Comunicación Final</label>
                                            <input type="text" class="form-control form-control-sm" id="num_comunicacion_final" name="num_comunicacion_final" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Plan de Acción -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Plan de Acción</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_limite_plan_accion" class="form-label">Fecha Límite PA</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_limite_plan_accion" name="fec_limite_plan_accion">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_insistencia_plan_accion" class="form-label">Fecha Insistencia PA</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_insistencia_plan_accion" name="fec_insistencia_plan_accion">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="num_insistencia_plan_accion" class="form-label">Insistencia PA</label>
                                            <input type="text" class="form-control form-control-sm" id="num_insistencia_plan_accion" name="num_insistencia_plan_accion" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_aprobacion_plan_accion" class="form-label">Fecha Aprobación PA</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_aprobacion_plan_accion" name="fec_aprobacion_plan_accion">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sanción -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Sanción</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="sancion" class="form-label">Sanción</label>
                                            <input type="text" class="form-control form-control-sm" id="sancion" name="sancion" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- CORRECTIVAS -->
                <section id="sCorrectivas" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h4>Correctivas</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sCorrectivas">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Reunión y Comunicación -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Reunión y Comunicación</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_correctiva" class="form-label">ID Correctivas</label>
                                            <input type="text" class="form-control form-control-sm" id="id_correctiva" name="id_correctiva" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_reunion_comunicacion_resultados" class="form-label">Fecha Reunión Comunicación Resultados</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_reunion_comunicacion_resultados" name="fec_reunion_comunicacion_resultados">
                                        </div>
                                    </div>
                                </div>

                                <!-- Aprobación Plan de Acción -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Aprobación Plan de Acción</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_pa_fisico" class="form-label">Fecha Aprobación PA Físico</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_aprobacion_pa_fisico" name="fec_aprobacion_pa_fisico">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_aprobacion_pa_fisico" class="form-label">Aprobación PA Físico</label>
                                            <input type="text" class="form-control form-control-sm" id="num_aprobacion_pa_fisico" name="num_aprobacion_pa_fisico" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_pa_sistema" class="form-label">Fecha Aprobación PA Sistema</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_aprobacion_pa_sistema" name="fec_aprobacion_pa_sistema">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SUPERVISION PSI -->
                <section id="sSupervisionPsi" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h4>Supervisión PSI</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sSupervisionPsi">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Resolución PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Resolución PSI</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision_psi" class="form-label">ID PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="id_supervision_psi" name="id_supervision_psi" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_resolucion_psi" class="form-label">Fecha Resolución PSI</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_resolucion_psi" name="fec_resolucion_psi">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="num_resolucion_psi" class="form-label">Número Resolución PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="num_resolucion_psi" name="num_resolucion_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_fin_psi" class="form-label">Fecha Fin PSI</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_fin_psi" name="fec_fin_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Imposición PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Imposición PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_imposicion_psi" class="form-label">Fecha Imposición PSI</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_imposicion_psi" name="fec_imposicion_psi">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_imposicion_psi" class="form-label">Oficio Imposición PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="num_oficio_imposicion_psi" name="num_oficio_imposicion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Comunicación PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_memorando_comunicacion_psi" class="form-label">Fecha Memorando Comunicación</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_memorando_comunicacion_psi" name="fec_memorando_comunicacion_psi">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_memorando_comunicacion_psi" class="form-label">Memorando Comunicación</label>
                                            <input type="text" class="form-control form-control-sm" id="num_memorando_comunicacion_psi" name="num_memorando_comunicacion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Ampliación PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Ampliación PSI</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_ampliacion_psi" class="form-label">Fecha Ampliación PSI</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_ampliacion_psi" name="fec_ampliacion_psi">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="num_ampliacion_psi" class="form-label">Ampliación PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="num_ampliacion_psi" name="num_ampliacion_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_informe_ampliacion_psi" class="form-label">Fecha Informe Ampliación</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_informe_ampliacion_psi" name="fec_informe_ampliacion_psi">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="num_informe_ampliacion_psi" class="form-label">Informe Ampliación PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="num_informe_ampliacion_psi" name="num_informe_ampliacion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SEGUIMIENTO PSI -->
                <section id="sSeguimientoPsi" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <h4>Seguimiento PSI</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sSeguimientoPsi">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Informe de Seguimiento -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe de Seguimiento</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_seguimiento_psi" class="form-label">ID Seguimiento PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="id_seguimiento_psi" name="id_seguimiento_psi" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="num_informe_seguimiento" class="form-label">Número Informe Seguimiento</label>
                                            <input type="text" class="form-control form-control-sm" id="num_informe_seguimiento" name="num_informe_seguimiento" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_seg" class="form-label">Fecha Informe</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_informe_seg" name="fec_informe_seg">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación Seguimiento -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación Seguimiento</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_comunicacion_seg_psi" class="form-label">Oficio Comunicación</label>
                                            <input type="text" class="form-control form-control-sm" id="num_oficio_comunicacion_seg_psi" name="num_oficio_comunicacion_seg_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_oficio_comunicacion_seg_psi" class="form-label">Fecha Oficio Comunicación</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_oficio_comunicacion_seg_psi" name="fec_oficio_comunicacion_seg_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Aprobación PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Aprobación PSI</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="num_of_aprobacion_psi_fisico" class="form-label">Aprobación PSI Físico</label>
                                            <input type="text" class="form-control form-control-sm" id="num_of_aprobacion_psi_fisico" name="num_of_aprobacion_psi_fisico" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_psi_fisico" class="form-label">Fecha Aprobación Físico</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_aprobacion_psi_fisico" name="fec_aprobacion_psi_fisico">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_psi_sistema" class="form-label">Fecha Aprobación Sistema</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_aprobacion_psi_sistema" name="fec_aprobacion_psi_sistema">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- LEVANTAMIENTO PSI -->
                <section id="sLevantamientoPsi" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                <h4>Levantamiento PSI</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sLevantamientoPsi">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Solicitud de Cierre PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Solicitud de Cierre PSI</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_levantamiento_psi" class="form-label">ID Levantamiente PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="id_levantamiento_psi" name="id_levantamiento_psi" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="mem_solicitud_cierre_psi" class="form-label">Memorando Solicitud Cierre</label>
                                            <input type="text" class="form-control form-control-sm" id="mem_solicitud_cierre_psi" name="mem_solicitud_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_mem_solicitud_cierre_psi" class="form-label">Fecha Memorando Solicitud</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_mem_solicitud_cierre_psi" name="fec_mem_solicitud_cierre_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Entrega Informe Cierre -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe de Cierre</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="mem_entrega_informe_cierre_psi" class="form-label">Memorando Entrega Informe</label>
                                            <input type="text" class="form-control form-control-sm" id="mem_entrega_informe_cierre_psi" name="mem_entrega_informe_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_mem_entrega_informe_cierre_psi" class="form-label">Fecha Memorando Entrega</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_mem_entrega_informe_cierre_psi" name="fec_mem_entrega_informe_cierre_psi">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="informe_cierre_psi" class="form-label">Informe Cierre PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="informe_cierre_psi" name="informe_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_cierre_psi" class="form-label">Fecha Informe Cierre</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_informe_cierre_psi" name="fec_informe_cierre_psi">
                                        </div>
                                    </div>
                                </div>
                                <!-- Resolución Terminación -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Resolución y Terminación</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="resolucion_terminacion_psi" class="form-label">Resolución Terminación PSI</label>
                                            <input type="text" class="form-control form-control-sm" id="resolucion_terminacion_psi" name="resolucion_terminacion_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_resolucion_terminacion_psi" class="form-label">Fecha Resolución Terminación</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_resolucion_terminacion_psi" name="fec_resolucion_terminacion_psi">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_reunion_cierre_psi" class="form-label">Fecha Reunión Cierre</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_reunion_cierre_psi" name="fec_reunion_cierre_psi">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_oficio_envio_cierre_psi" class="form-label">Fecha Oficio Envío</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_oficio_envio_cierre_psi" name="fec_oficio_envio_cierre_psi">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="of_envio_doc_cierre_psi" class="form-label">Oficio Envío Documentación</label>
                                            <input type="text" class="form-control form-control-sm" id="of_envio_doc_cierre_psi" name="of_envio_doc_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Entrega Informe -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe INFMR</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_entrega_infmr" class="form-label">Fecha Entrega Informe INFMR</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_entrega_infmr" name="fec_entrega_infmr">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- LIQUIDACIÓN -->
                <section id="sLiquidacion" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                <h4>Liquidación</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sLiquidacion">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Informe Final -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe Final</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="id_liquidacion" class="form-label">ID Liquidación</label>
                                            <input type="text" class="form-control form-control-sm" id="id_liquidacion" name="id_liquidacion" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_informe_final_liq" class="form-label">Número Informe Final</label>
                                            <input type="text" class="form-control form-control-sm" id="num_informe_final_liq" name="num_informe_final_liq" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_informe_final_liq" class="form-label">Fecha Informe Final</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_informe_final_liq" name="fec_informe_final_liq">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación IGT e IGJ -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación IGT</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="memo_comunicacion_igt" class="form-label">Memorando Comunicación IGT</label>
                                            <input type="text" class="form-control form-control-sm" id="memo_comunicacion_igt" name="memo_comunicacion_igt" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_comunicacion_igt" class="form-label">Fecha Comunicación IGT</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_comunicacion_igt" name="fec_comunicacion_igt">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="memo_comunicacion_igj" class="form-label">Memorando Comunicación IGJ</label>
                                            <input type="text" class="form-control form-control-sm" id="memo_comunicacion_igj" name="memo_comunicacion_igj" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_comunicacion_igj" class="form-label">Fecha Comunicación IGJ</label>
                                            <input type="date" class="form-control form-control-sm" id="fec_comunicacion_igj" name="fec_comunicacion_igj">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ALERTAS -->
                <section id="sAlertas" class="row align-items-stretch">
                    <div class="col-md-12 mb-3">
                        <div class="card h-100 d-flex flex-column border-secondary">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h4>Alertas</h4>
                                <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sAlertas">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Alerta</h5>
                                    <div class="row">
                                        <div class="col-md-1 mb-3">
                                            <label for="alertaId" class="form-label">ID Alerta</label>
                                            <input type="text" class="form-control form-control-sm" id="alertaId" name="alertaId">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="tipo_alerta" class="form-label">Tipo de Alerta</label>
                                            <input type="text" class="form-control form-control-sm" id="tipo_alerta" name="tipo_alerta" placeholder="Ingrese tipo de alerta" style="text-transform: uppercase;" readonly>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_inicio_supervision_alerta" class="form-label">Fec. Inicio Supervisión</label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="fec_inicio_supervision_alerta" name="fec_inicio_supervision_alerta">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_informe_alerta" class="form-label">Fec. Informe Alerta</label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="fec_informe_alerta" name="fec_informe_alerta">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="num_informe_alerta" class="form-label">Número Informe Alerta</label>
                                            <input type="text" class="form-control form-control-sm" id="num_informe_alerta" name="num_informe_alerta" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="descripcion_alerta" class="form-label">Descripción de la Alerta</label>
                                            <textarea class="form-control form-control-sm" id="descripcion_alerta" name="descripcion_alerta" rows="3" placeholder="Ingrese la descripción de la alerta" style="text-transform: uppercase;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                 <!-- Comunicación de Alerta -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación de Alerta</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="num_of_comunicacion_alerta" class="form-label">Número Oficio Comunicación</label>
                                            <input type="text" class="form-control form-control-sm" id="num_of_comunicacion_alerta" name="num_of_comunicacion_alerta" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_of_comunicacion_alerta" class="form-label">Fec. Oficio Comunicación</label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="fec_of_comunicacion_alerta" name="fec_of_comunicacion_alerta">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_ssi" class="form-label">Fec. Aprobación SSI</label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="fec_aprobacion_ssi" name="fec_aprobacion_ssi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </main>
    </div>
    <!-- -->

    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <!-- Modal Buscar supervision -->
    <?php include BASE_PATH . 'frontend/partials/modalSupervision.php'; ?>


    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/supervision/requiredSupervision.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/supervision/crudSupervision.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/supervision/bucarSupervisiones.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/supervision/toggleFormularios.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/supervision/asupervision.js"></script>
</body>

</html>