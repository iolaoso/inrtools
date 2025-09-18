<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/tareas/gestionTareasList.php'; // Incluir el archivo de consultas
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas

$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);
$tareas = getTareasPendientes($nickname);
$tareasCompletas = getTareasCompletas($nickname);
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
            <section class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Gestión Tareas</h1>
                <p>Actividades recurrentes definidas por los Analistas</p>
            </section>
            <section class="row align-items-stretch">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Crear Tarea</h4>
                        </div>
                        <div class="card-body">
                            <form id="frmTareas" method="POST" autocomplete="off">
                                <div class="mb-3">
                                    <input type="text" id="taskId" name="taskId" value="">
                                </div>
                                <div class="mb-3">
                                    <label for="taskName" class="form-label">Tarea</label>
                                    <input type="text" class="form-control" id="taskName" name="taskName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="processType" class="form-label">Tipo de Proceso</label>
                                    <select class="form-control" id="processType" name="processType" required>
                                        <option value="">Seleccione el Tipo</option>
                                        <option value="ACTUALIZACION">ACTUALIZACION</option>
                                        <option value="BACKUP">BACKUP</option>
                                        <option value="CREACION">CREACION</option>
                                        <option value="GESTION">GESTION</option>
                                        <option value="LLAMADA">LLAMADA</option>
                                        <option value="REUNION">REUNION</option>
                                        <option value="OTRO">OTRO</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="frequency" class="form-label">Frecuencia</label>
                                    <select class="form-control" id="frequency" name="frequency" required>
                                        <option value="">Seleccione la Frecuencia</option>
                                        <option value="DIARIA">DIARIA</option>
                                        <option value="SEMANAL">SEMANAL</option>
                                        <option value="MENSUAL">MENSUAL</option>
                                        <option value="TRIMESTRAL">TRIMESTRAL</option>
                                        <option value="SEMESTRAL">SEMESTRAL</option>
                                        <option value="ANUAL">ANUAL</option>
                                        <option value="UNICO">UNICO</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ruc" class="form-label">RUC (Opcional)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="ruc" name="ruc">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#catastroModal">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="nextExecutionDate" class="form-label">Próxima Fecha de Ejecución</label>
                                    <input type="date" class="form-control" id="nextExecutionDate"
                                        name="nextExecutionDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nextExecutionTime" class="form-label">Próxima Hora de Ejecución</label>
                                    <input type="time" class="form-control" id="nextExecutionTime"
                                        name="nextExecutionTime" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lastExecution" class="form-label">Última Fecha y Hora de
                                        Ejecución</label>
                                    <input type="datetime-local" class="form-control" id="lastExecution"
                                        name="lastExecution" readonly>
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
                                <!-- assignAnalyst -->
                                <div class="mb-3">
                                    <label for="analistaEjecutante" class="form-label">Analista Ejecutante</label>
                                    <input class="form-control" id="analistaEjecutante" name="analistaEjecutante"
                                        value="<?= htmlspecialchars($nickname)   ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="taskStatus" class="form-label">Estado de Tarea</label>
                                    <select class="form-control" id="taskStatus" name="taskStatus" required>
                                        <option value="">Seleccione Estado</option>
                                        <option value="PENDIENTE">PENDIENTE</option>
                                        <option value="COMPLETADA">COMPLETADA</option>
                                        <option value="CANCELADA">CANCELADA</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary btn-sm btn-block save-btn">Guardar
                                        Tarea</button>
                                    <button type="button" class="btn btn-secondary btn-sm btn-block"
                                        onclick="limpiarForm('frmTareas')">Limpiar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 mb-3">
                    <!-- tabla pendientes -->
                    <div class="card d-flex flex-column border-secondary mb-3" style="height: 700px; overflow-y: auto;">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Tareas Pendientes</h4>
                            <button id="verTablaPendientes" class="btn btn-primary">Reporte Completo</button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 300px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaActividades">
                                        <thead>
                                            <tr>
                                                <th>Tarea</th>
                                                <th>Tipo de Proceso</th>
                                                <th>Frecuencia</th>
                                                <th>RUC</th>
                                                <th>Descripción</th>
                                                <th>Próxima Fecha</th>
                                                <th>Próxima Hora</th>
                                                <th>Última Ejecución</th>
                                                <th>Analista Asignado</th>
                                                <th>Estado</th>
                                                <th>Hecho</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyActividades">
                                            <?php foreach ($tareas as $tarea): ?>
                                                <tr>
                                                    <td>
                                                        <?= isset($tarea['TAREA']) ? htmlspecialchars($tarea['TAREA']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['TIPO_PROCESO']) ? htmlspecialchars($tarea['TIPO_PROCESO']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['FRECUENCIA']) ? htmlspecialchars($tarea['FRECUENCIA']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['RUC']) ? htmlspecialchars($tarea['RUC']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['DESCRIPCION']) ? htmlspecialchars($tarea['DESCRIPCION']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['PROXIMA_FECHA']) ? htmlspecialchars($tarea['PROXIMA_FECHA']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['PROXIMA_HORA']) ? htmlspecialchars($tarea['PROXIMA_HORA']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['ULTIMA_EJECUCION']) ? htmlspecialchars($tarea['ULTIMA_EJECUCION']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['ANALISTA_ASIGNADO']) ? htmlspecialchars($tarea['ANALISTA_ASIGNADO']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?= isset($tarea['ESTADO_TAREA']) ? htmlspecialchars($tarea['ESTADO_TAREA']) : '' ?>
                                                    </td>
                                                    <td>
                                                        <?php if (($tarea['ESTADO_TAREA'] ?? '') === "COMPLETADA"): ?>
                                                            <button class="btn btn-success complete-btn btn-sm"
                                                                data-id="<?= htmlspecialchars($tarea['id'] ?? '', ENT_QUOTES) ?>"
                                                                title="Marcar como completada" disabled>
                                                                <i class="fa-solid fa-check-double"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-warning complete-btn "
                                                                data-id="<?= htmlspecialchars($tarea['id'] ?? '', ENT_QUOTES) ?>"
                                                                title="Marcar como completada">
                                                                <i class="fa-solid fa-marker"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info edit-btn"
                                                                data-id="<?= $tarea['id'] ?>" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger delete-btn"
                                                                data-id="<?= $tarea['id'] ?>" title="Eliminar">
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
                    <!-- tabla completadas  -->
                    <div class="card d-flex flex-column border-secondary mb-3" style="height: 400px; overflow-y: auto;">
                        <div
                            class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Tareas Completadas</h4>
                            <button id="verTablaCompletadas" class="btn btn-primary">Reporte Completo</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" id="tablaCompletadas">
                                    <thead>
                                        <tr>
                                            <th>Tarea</th>
                                            <th>Tipo de Proceso</th>
                                            <th>Frecuencia</th>
                                            <th>RUC</th>
                                            <th>Descripción</th>
                                            <th>Próxima Fecha</th>
                                            <th>Próxima Hora</th>
                                            <th>Última Ejecución</th>
                                            <th>Analista Asignado</th>
                                            <th>Estado</th>
                                            <th>Hecho</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodycompletadas">
                                        <?php foreach ($tareasCompletas as $tareaC): ?>
                                            <tr>
                                                <td>
                                                    <?= isset($tareaC['TAREA']) ? htmlspecialchars($tareaC['TAREA']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['TIPO_PROCESO']) ? htmlspecialchars($tareaC['TIPO_PROCESO']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['FRECUENCIA']) ? htmlspecialchars($tareaC['FRECUENCIA']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['RUC']) ? htmlspecialchars($tareaC['RUC']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['DESCRIPCION']) ? htmlspecialchars($tareaC['DESCRIPCION']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['PROXIMA_FECHA']) ? htmlspecialchars($tareaC['PROXIMA_FECHA']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['PROXIMA_HORA']) ? htmlspecialchars($tareaC['PROXIMA_HORA']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['ULTIMA_EJECUCION']) ? htmlspecialchars($tareaC['ULTIMA_EJECUCION']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['ANALISTA_ASIGNADO']) ? htmlspecialchars($tareaC['ANALISTA_ASIGNADO']) : '' ?>
                                                </td>
                                                <td>
                                                    <?= isset($tareaC['ESTADO_TAREA']) ? htmlspecialchars($tareaC['ESTADO_TAREA']) : '' ?>
                                                </td>
                                                <td>
                                                    <?php if (($tareaC['ESTADO_TAREA'] ?? '') === "COMPLETADA"): ?>
                                                        <button class="btn btn-success complete-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($tareaC['id'] ?? '', ENT_QUOTES) ?>"
                                                            title="Marcar como completada" disabled>
                                                            <i class="fa-solid fa-check-double"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-warning complete-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($tareaC['id'] ?? '', ENT_QUOTES) ?>"
                                                            title="Marcar como completada">
                                                            <i class="fa-solid fa-marker"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-info edit-btn"
                                                            data-id="<?= $tareaC['id'] ?>" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-btn"
                                                            data-id="<?= $tareaC['id'] ?>" title="Eliminar">
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
            </section>
        </main>
    </div>

    <!-- Modal Buscar Catastro-->
    <?php include BASE_PATH . 'frontend/partials/modalCatastro.php'; ?>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/tareas/gestionTareas.js"></script>
</body>

</html>