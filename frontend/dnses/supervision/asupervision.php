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
            
            <!-- CUERPO DE LA PAGINA -->

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
                            <form id="frmAvancesSupervision" method="post" autocomplete="off" onsubmit="guardarForm('frmAvancesSupervision', event)">
                                <!-- Datos Entidad -->
                                <div class="seccion-formulario mb-4">
                                    <!-- datos ocultos -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="id_avances" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_avances" name="id_avances" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cod_unico_avances" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_avances" name="cod_unico_avances" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">RUC</span>
                                                </div>
                                                <input type="text" class="form-control" id="ruc" name="ruc"
                                                    oninput="buscarEntidad()" data-page="asupervision.php">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                        data-bs-target="#catastroModal">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Segmento</span>
                                                </div>
                                                <input type="text" class="form-control" id="tbsegmento" name="tbsegmento" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Razón Social</span>
                                                </div>
                                                <input type="text" class="form-control" id="tbrazonSocial" name="tbrazonSocial" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Botones de acción -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-center mt-4">
                                                <button type="button" class="btn btn-primary me-3" onclick="abrirBuscarSupervisionModal()">
                                                    <i class="fas fa-search me-1"></i> Buscar Supervisiones
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="nuevaSupervision()">
                                                    <i class="fas fa-plus me-1"></i> Nueva Supervisión
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                            <input type="text" class="form-control form-control-sm w-auto text-center" id="ID" placeholder="ID" name="ID" disabled>
                            <button type="button" class="btn btn-sm btn-light toggle-formulario" data-target="sAvancesSupervision">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <form id="frmAvancesSupervision" method="post" autocomplete="off" onsubmit="guardarForm('frmAvancesSupervision', event)">                              
                                <!-- Información General -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Información General</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="estrategia" class="form-label">Estrategia</label>
                                            <select class="form-control" id="estrategia" name="estrategia" required>
                                                <option value="">Seleccione una Estrategia</option>
                                                <?php foreach ($estrategias as $estrategia): ?>
                                                <option value="<?= htmlspecialchars($estrategia['ID']) ?>">
                                                <?= htmlspecialchars($estrategia['ESTRATEGIA']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div><div class="col-md-6 mb-3">
                                            <label for="fase" class="form-label">Fase</label>
                                            <select class="form-control" id="fase" name="fase" required>
                                                <option value="0">Seleccione...</option>
                                                <!-- datos dinamicos desde php -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="analistaSelect" class="form-label">Responsable</label>
                                            <select class="form-control" id="analistaSelect" name="analistaSelect" 
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
                                            <input type="text" class="form-control" id="analista" name="analista" value="<?= htmlspecialchars($nickname)   ?>" style="text-transform: uppercase;" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="estadoSupervision" class="form-label">Estado Supervisión</label>
                                            <input type="text" class="form-control" id="estadoSupervision" name="estadoSupervision" required style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Planificación y Avance -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Planificación y Avance</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="fec_asig" class="form-label">Fecha Asignación</label>
                                            <input type="date" class="form-control" id="fec_asig" name="fec_asig" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="anio_plan" class="form-label">Año Planificado</label>
                                            <input type="number" class="form-control" id="anio_plan" name="anio_plan" min="2020" max="2030" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="trim_plan" class="form-label">Trimestre Planificado</label>
                                            <select class="form-control" id="trim_plan" name="trim_plan" required>
                                                <option value="">Seleccione...</option>
                                                <option value="1">I Trimestre</option>
                                                <option value="2">II Trimestre</option>
                                                <option value="3">III Trimestre</option>
                                                <option value="4">IV Trimestre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="porc_avance" class="form-label">Porcentaje de Avance</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="porc_avance" name="porc_avance" min="0" max="100" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                    </div>
                                </div>
                            </form>
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
                            <form id="frmSupervisiones" method="post" autocomplete="off" onsubmit="guardarForm('frmSupervisiones', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_supervision" name="id_supervision" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_avances_supervision_sup" class="form-label">ID Avances Supervisión</label>
                                            <input type="text" class="form-control" id="id_avances_supervision_sup" name="id_avances_supervision_sup" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_supervision" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_supervision" name="ruc_supervision" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cod_unico_supervision" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_supervision" name="cod_unico_supervision" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Proceso de Solicitud -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Proceso de Solicitud</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_solicitud" class="form-label">Fecha Solicitud</label>
                                            <input type="date" class="form-control" id="fec_solicitud" name="fec_solicitud">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_solicitud" class="form-label">Oficio de Solicitud</label>
                                            <input type="text" class="form-control" id="num_oficio_solicitud" name="num_oficio_solicitud" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_insistencia" class="form-label">Fecha Insistencia</label>
                                            <input type="date" class="form-control" id="fec_insistencia" name="fec_insistencia">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_insistencia" class="form-label">Oficio de Insistencia</label>
                                            <input type="text" class="form-control" id="num_oficio_insistencia" name="num_oficio_insistencia" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación y Respuesta -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación y Respuesta</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_comunicacion" class="form-label">Fecha Comunicación</label>
                                            <input type="date" class="form-control" id="fec_comunicacion" name="fec_comunicacion">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_oficio_resultados" class="form-label">Oficio de Resultados</label>
                                            <input type="text" class="form-control" id="num_oficio_resultados" name="num_oficio_resultados" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_limite_entrega" class="form-label">Fecha Límite Entrega</label>
                                            <input type="date" class="form-control" id="fec_limite_entrega" name="fec_limite_entrega">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_respuesta" class="form-label">Fecha Respuesta</label>
                                            <input type="date" class="form-control" id="fec_respuesta" name="fec_respuesta">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_respuesta" class="form-label">Oficio de Respuesta</label>
                                            <input type="text" class="form-control" id="num_oficio_respuesta" name="num_oficio_respuesta" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Informe Final y Comunicación -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe Final y Comunicación</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_final" class="form-label">Fecha Informe Final</label>
                                            <input type="date" class="form-control" id="fec_informe_final" name="fec_informe_final">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_informe_final" class="form-label">Número Informe Final</label>
                                            <input type="text" class="form-control" id="num_informe_final" name="num_informe_final" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_comunicacion_final" class="form-label">Fecha Comunicación Final</label>
                                            <input type="date" class="form-control" id="fec_comunicacion_final" name="fec_comunicacion_final">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_comunicacion_final" class="form-label">Comunicación Final</label>
                                            <input type="text" class="form-control" id="num_comunicacion_final" name="num_comunicacion_final" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Plan de Acción -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Plan de Acción</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_limite_plan_accion" class="form-label">Fecha Límite PA</label>
                                            <input type="date" class="form-control" id="fec_limite_plan_accion" name="fec_limite_plan_accion">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_insistencia_plan_accion" class="form-label">Fecha Insistencia PA</label>
                                            <input type="date" class="form-control" id="fec_insistencia_plan_accion" name="fec_insistencia_plan_accion">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_insistencia_plan_accion" class="form-label">Insistencia PA</label>
                                            <input type="text" class="form-control" id="num_insistencia_plan_accion" name="num_insistencia_plan_accion" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_aprobacion_plan_accion" class="form-label">Fecha Aprobación PA</label>
                                            <input type="date" class="form-control" id="fec_aprobacion_plan_accion" name="fec_aprobacion_plan_accion">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sanción -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Sanción</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="sancion" class="form-label">Sanción</label>
                                            <input type="text" class="form-control" id="sancion" name="sancion" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmSupervisiones')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
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
                            <form id="frmCorrectivas" method="post" autocomplete="off" onsubmit="guardarForm('frmCorrectivas', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_correctiva" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_correctiva" name="id_correctiva" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision_corr" class="form-label">ID Supervisión</label>
                                            <input type="text" class="form-control" id="id_supervision_corr" name="id_supervision_corr" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_correctiva" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_correctiva" name="ruc_correctiva" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cod_unico_correctiva" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_correctiva" name="cod_unico_correctiva" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reunión y Comunicación -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Reunión y Comunicación</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_reunion_comunicacion_resultados" class="form-label">Fecha Reunión Comunicación Resultados</label>
                                            <input type="date" class="form-control" id="fec_reunion_comunicacion_resultados" name="fec_reunion_comunicacion_resultados">
                                        </div>
                                    </div>
                                </div>

                                <!-- Aprobación Plan de Acción -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Aprobación Plan de Acción</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_pa_fisico" class="form-label">Fecha Aprobación PA Físico</label>
                                            <input type="date" class="form-control" id="fec_aprobacion_pa_fisico" name="fec_aprobacion_pa_fisico">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_aprobacion_pa_fisico" class="form-label">Aprobación PA Físico</label>
                                            <input type="text" class="form-control" id="num_aprobacion_pa_fisico" name="num_aprobacion_pa_fisico" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_pa_sistema" class="form-label">Fecha Aprobación PA Sistema</label>
                                            <input type="date" class="form-control" id="fec_aprobacion_pa_sistema" name="fec_aprobacion_pa_sistema">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmCorrectivas')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
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
                            <form id="frmSupervisionPsi" method="post" autocomplete="off" onsubmit="guardarForm('frmSupervisionPsi', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision_psi" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_supervision_psi" name="id_supervision_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_correctiva_psi" class="form-label">ID Correctiva</label>
                                            <input type="text" class="form-control" id="id_correctiva_psi" name="id_correctiva_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_supervision_psi" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_supervision_psi" name="ruc_supervision_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cod_unico_supervision_psi" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_supervision_psi" name="cod_unico_supervision_psi" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Resolución PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Resolución PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_resolucion_psi" class="form-label">Fecha Resolución PSI</label>
                                            <input type="date" class="form-control" id="fec_resolucion_psi" name="fec_resolucion_psi">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_resolucion_psi" class="form-label">Número Resolución PSI</label>
                                            <input type="text" class="form-control" id="num_resolucion_psi" name="num_resolucion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Imposición PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Imposición PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_imposicion_psi" class="form-label">Fecha Imposición PSI</label>
                                            <input type="date" class="form-control" id="fec_imposicion_psi" name="fec_imposicion_psi">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_imposicion_psi" class="form-label">Oficio Imposición PSI</label>
                                            <input type="text" class="form-control" id="num_oficio_imposicion_psi" name="num_oficio_imposicion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Fin PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Fin PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_fin_psi" class="form-label">Fecha Fin PSI</label>
                                            <input type="date" class="form-control" id="fec_fin_psi" name="fec_fin_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_memorando_comunicacion_psi" class="form-label">Fecha Memorando Comunicación</label>
                                            <input type="date" class="form-control" id="fec_memorando_comunicacion_psi" name="fec_memorando_comunicacion_psi">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_memorando_comunicacion_psi" class="form-label">Memorando Comunicación</label>
                                            <input type="text" class="form-control" id="num_memorando_comunicacion_psi" name="num_memorando_comunicacion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Ampliación PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Ampliación PSI</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_ampliacion_psi" class="form-label">Fecha Ampliación PSI</label>
                                            <input type="date" class="form-control" id="fec_ampliacion_psi" name="fec_ampliacion_psi">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="num_ampliacion_psi" class="form-label">Ampliación PSI</label>
                                            <input type="text" class="form-control" id="num_ampliacion_psi" name="num_ampliacion_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_informe_ampliacion_psi" class="form-label">Fecha Informe Ampliación</label>
                                            <input type="date" class="form-control" id="fec_informe_ampliacion_psi" name="fec_informe_ampliacion_psi">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="num_informe_ampliacion_psi" class="form-label">Informe Ampliación PSI</label>
                                            <input type="text" class="form-control" id="num_informe_ampliacion_psi" name="num_informe_ampliacion_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmSupervisionPsi')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
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
                            <form id="frmSeguimientoPsi" method="post" autocomplete="off" onsubmit="guardarForm('frmSeguimientoPsi', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_seguimiento_psi" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_seguimiento_psi" name="id_seguimiento_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision_psi_seg" class="form-label">ID Supervisión PSI</label>
                                            <input type="text" class="form-control" id="id_supervision_psi_seg" name="id_supervision_psi_seg" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_seguimiento_psi" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_seguimiento_psi" name="ruc_seguimiento_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cod_unico_seguimiento_psi" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_seguimiento_psi" name="cod_unico_seguimiento_psi" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informe de Seguimiento -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe de Seguimiento</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="num_informe_seguimiento" class="form-label">Número Informe Seguimiento</label>
                                            <input type="text" class="form-control" id="num_informe_seguimiento" name="num_informe_seguimiento" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe" class="form-label">Fecha Informe</label>
                                            <input type="date" class="form-control" id="fec_informe" name="fec_informe">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación Seguimiento -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación Seguimiento</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="num_oficio_comunicacion_seg_psi" class="form-label">Oficio Comunicación</label>
                                            <input type="text" class="form-control" id="num_oficio_comunicacion_seg_psi" name="num_oficio_comunicacion_seg_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_oficio_comunicacion_seg_psi" class="form-label">Fecha Oficio Comunicación</label>
                                            <input type="date" class="form-control" id="fec_oficio_comunicacion_seg_psi" name="fec_oficio_comunicacion_seg_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Aprobación PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Aprobación PSI</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="num_of_aprobacion_psi_fisico" class="form-label">Aprobación PSI Físico</label>
                                            <input type="text" class="form-control" id="num_of_aprobacion_psi_fisico" name="num_of_aprobacion_psi_fisico" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_psi_fisico" class="form-label">Fecha Aprobación Físico</label>
                                            <input type="date" class="form-control" id="fec_aprobacion_psi_fisico" name="fec_aprobacion_psi_fisico">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_aprobacion_psi_sistema" class="form-label">Fecha Aprobación Sistema</label>
                                            <input type="date" class="form-control" id="fec_aprobacion_psi_sistema" name="fec_aprobacion_psi_sistema">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmSeguimientoPsi')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
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
                            <form id="frmLevantamientoPsi" method="post" autocomplete="off" onsubmit="guardarForm('frmLevantamientoPsi', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_levantamiento_psi" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_levantamiento_psi" name="id_levantamiento_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision_psi_lev" class="form-label">ID Supervisión PSI</label>
                                            <input type="text" class="form-control" id="id_supervision_psi_lev" name="id_supervision_psi_lev" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_levantamiento_psi" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_levantamiento_psi" name="ruc_levantamiento_psi" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cod_unico_levantamiento_psi" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_levantamiento_psi" name="cod_unico_levantamiento_psi" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Solicitud de Cierre PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Solicitud de Cierre PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="mem_solicitud_cierre_psi" class="form-label">Memorando Solicitud Cierre</label>
                                            <input type="text" class="form-control" id="mem_solicitud_cierre_psi" name="mem_solicitud_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_mem_solicitud_cierre_psi" class="form-label">Fecha Memorando Solicitud</label>
                                            <input type="date" class="form-control" id="fec_mem_solicitud_cierre_psi" name="fec_mem_solicitud_cierre_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Entrega Informe Cierre -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Entrega Informe Cierre</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="mem_entrega_informe_cierre_psi" class="form-label">Memorando Entrega Informe</label>
                                            <input type="text" class="form-control" id="mem_entrega_informe_cierre_psi" name="mem_entrega_informe_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_mem_entrega_informe_cierre_psi" class="form-label">Fecha Memorando Entrega</label>
                                            <input type="date" class="form-control" id="fec_mem_entrega_informe_cierre_psi" name="fec_mem_entrega_informe_cierre_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Informe Cierre PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe Cierre PSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="informe_cierre_psi" class="form-label">Informe Cierre PSI</label>
                                            <input type="text" class="form-control" id="informe_cierre_psi" name="informe_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_cierre_psi" class="form-label">Fecha Informe Cierre</label>
                                            <input type="date" class="form-control" id="fec_informe_cierre_psi" name="fec_informe_cierre_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Resolución Terminación -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Resolución Terminación</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="resolucion_terminacion_psi" class="form-label">Resolución Terminación PSI</label>
                                            <input type="text" class="form-control" id="resolucion_terminacion_psi" name="resolucion_terminacion_psi" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_resolucion_terminacion_psi" class="form-label">Fecha Resolución Terminación</label>
                                            <input type="date" class="form-control" id="fec_resolucion_terminacion_psi" name="fec_resolucion_terminacion_psi">
                                        </div>
                                    </div>
                                </div>

                                <!-- Cierre PSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Cierre PSI</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_reunion_cierre_psi" class="form-label">Fecha Reunión Cierre</label>
                                            <input type="date" class="form-control" id="fec_reunion_cierre_psi" name="fec_reunion_cierre_psi">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fec_oficio_envio_cierre_psi" class="form-label">Fecha Oficio Envío</label>
                                            <input type="date" class="form-control" id="fec_oficio_envio_cierre_psi" name="fec_oficio_envio_cierre_psi">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="of_envio_doc_cierre_psi" class="form-label">Oficio Envío Documentación</label>
                                            <input type="text" class="form-control" id="of_envio_doc_cierre_psi" name="of_envio_doc_cierre_psi" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Entrega Informe -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Entrega Informe</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_entrega_infmr" class="form-label">Fecha Entrega Informe</label>
                                            <input type="date" class="form-control" id="fec_entrega_infmr" name="fec_entrega_infmr">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmLevantamientoPsi')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
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
                            <form id="frmLiquidacion" method="post" autocomplete="off" onsubmit="guardarForm('frmLiquidacion', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_liquidacion" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_liquidacion" name="id_liquidacion" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_avances_supervision_liq" class="form-label">ID Avances Supervisión</label>
                                            <input type="text" class="form-control" id="id_avances_supervision_liq" name="id_avances_supervision_liq" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_supervision_liq" class="form-label">ID Supervisión</label>
                                            <input type="text" class="form-control" id="id_supervision_liq" name="id_supervision_liq" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_liquidacion" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_liquidacion" name="ruc_liquidacion" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cod_unico_liquidacion" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_liquidacion" name="cod_unico_liquidacion" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informe Final -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe Final</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="num_informe_final" class="form-label">Número Informe Final</label>
                                            <input type="text" class="form-control" id="num_informe_final" name="num_informe_final" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_final" class="form-label">Fecha Informe Final</label>
                                            <input type="date" class="form-control" id="fec_informe_final" name="fec_informe_final">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación IGT -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación IGT</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="memo_comunicacion_igt" class="form-label">Memorando Comunicación IGT</label>
                                            <input type="text" class="form-control" id="memo_comunicacion_igt" name="memo_comunicacion_igt" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_comunicacion_igt" class="form-label">Fecha Comunicación IGT</label>
                                            <input type="date" class="form-control" id="fec_comunicacion_igt" name="fec_comunicacion_igt">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación IGJ -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación IGJ</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="memo_comunicacion_igj" class="form-label">Memorando Comunicación IGJ</label>
                                            <input type="text" class="form-control" id="memo_comunicacion_igj" name="memo_comunicacion_igj" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_comunicacion_igj" class="form-label">Fecha Comunicación IGJ</label>
                                            <input type="date" class="form-control" id="fec_comunicacion_igj" name="fec_comunicacion_igj">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmLiquidacion')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
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
                            <form id="frmAlertas" method="post" autocomplete="off" onsubmit="guardarForm('frmAlertas', event)">
                                <!-- Campos técnicos -->
                                <div class="campos-tecnicos mb-3 px-3 border rounded bg-light hidden">
                                    <h5 class="text-secondary mb-3">Campos Técnicos</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="id_alerta" class="form-label">ID</label>
                                            <input type="text" class="form-control" id="id_alerta" name="id_alerta" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="id_avances_supervision_alert" class="form-label">ID Avances Supervisión</label>
                                            <input type="text" class="form-control" id="id_avances_supervision_alert" name="id_avances_supervision_alert" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ruc_alerta" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="ruc_alerta" name="ruc_alerta" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cod_unico_alerta" class="form-label">Código Único</label>
                                            <input type="text" class="form-control" id="cod_unico_alerta" name="cod_unico_alerta" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Información de Alerta -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Información de Alerta</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="tipo_alerta" class="form-label">Tipo de Alerta</label>
                                            <select class="form-control" id="tipo_alerta" name="tipo_alerta">
                                                <option value="">Seleccione...</option>
                                                <option value="PREVENTIVA">Preventiva</option>
                                                <option value="CORRECTIVA">Correctiva</option>
                                                <option value="INFORMATIVA">Informativa</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="tipo_supervision" class="form-label">Tipo de Supervisión</label>
                                            <select class="form-control" id="tipo_supervision" name="tipo_supervision">
                                                <option value="">Seleccione...</option>
                                                <option value="DOCUMENTARIA">Documentaria</option>
                                                <option value="IN SITU">In Situ</option>
                                                <option value="ESPECIAL">Especial</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="estado_proceso" class="form-label">Estado del Proceso</label>
                                            <select class="form-control" id="estado_proceso" name="estado_proceso">
                                                <option value="">Seleccione...</option>
                                                <option value="INICIADO">Iniciado</option>
                                                <option value="EN PROCESO">En Proceso</option>
                                                <option value="FINALIZADO">Finalizado</option>
                                                <option value="SUSPENDIDO">Suspendido</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="observacion_estado" class="form-label">Observación del Estado</label>
                                            <textarea class="form-control" id="observacion_estado" name="observacion_estado" rows="2" style="text-transform: uppercase;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informe de Alerta -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Informe de Alerta</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_informe_alerta" class="form-label">Fecha Informe Alerta</label>
                                            <input type="date" class="form-control" id="fec_informe_alerta" name="fec_informe_alerta">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_informe_alerta" class="form-label">Número Informe Alerta</label>
                                            <input type="text" class="form-control" id="num_informe_alerta" name="num_informe_alerta" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Comunicación de Alerta -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Comunicación de Alerta</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_of_comunicacion_alerta" class="form-label">Fecha Oficio Comunicación</label>
                                            <input type="date" class="form-control" id="fec_of_comunicacion_alerta" name="fec_of_comunicacion_alerta">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="num_of_comunicacion_alerta" class="form-label">Número Oficio Comunicación</label>
                                            <input type="text" class="form-control" id="num_of_comunicacion_alerta" name="num_of_comunicacion_alerta" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Aprobación SSI -->
                                <div class="seccion-formulario mb-4">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Aprobación SSI</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fec_aprobacion_ssi" class="form-label">Fecha Aprobación SSI</label>
                                            <input type="date" class="form-control" id="fec_aprobacion_ssi" name="fec_aprobacion_ssi">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarForm('frmAlertas')">
                                        <i class="fas fa-eraser me-1"></i> Limpiar Formulario
                                    </button>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
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
    <script src="<?php echo $base_url; ?>/assets/js/supervision/bucarSupervisiones.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/supervision/toggleFormularios.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/supervision/asupervision.js"></script>
</body>
</html>