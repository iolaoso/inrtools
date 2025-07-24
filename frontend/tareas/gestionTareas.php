<?php
include_once __DIR__ . '/../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/tareas/gestionTareasList.php'; // Incluir el archivo de consultas
include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
include BASE_PATH . 'backend/analistasList.php'; // consulta analistas


$entidadesActSf = entidadesActivasSf();
$analistas = obtenerAnalistas($direccion);

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
                <h1 class="display-6 tituloPagina">Gestión Tareas</h1>
                <p>Actividades recurrentes definidas por los Analistas</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Crear Tarea</h4>
                        </div>
                        <div class="card-body">
                            <form id="frmTareas" method="post" autocomplete="off"
                                onsubmit="guardarForm('frmTareas', event)">
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
                                        name="nextExecutionTime" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="lastExecution" class="form-label">Última Fecha y Hora de
                                        Ejecución</label>
                                    <input type="datetime-local" class="form-control" id="lastExecution"
                                        name="lastExecution" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="assignAnalyst" class="form-label">Asignar Analista</label>
                                    <select class="form-control" id="assignAnalyst" name="assignAnalyst" required>
                                        <option value="">Seleccione un Analista</option>
                                        <?php foreach ($analistas as $analista): ?>
                                        <option value="<?= htmlspecialchars($analista['NICKNAME']) ?>">
                                            <?= htmlspecialchars($analista['NOMBRE']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Guardar
                                        Tarea</button>
                                    <button type="button" class="btn btn-secondary btn-sm btn-block"
                                        onclick="limpiarForm('frmTareas')">Limpiar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Tareas Pendientes</h4>
                            <button id="verTablaCompleta" class="btn btn-primary">Reporte Completo</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" style="font-size: 12px;"
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
                                    <tbody>
                                        <!-- <?php foreach ($result as $gestionTarea): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($gestionTarea['TAREA'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['TIPO_PROCESO'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['FRECUENCIA'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['RUC'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['DESCRIPCION'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['PROXIMA_FECHA'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['PROXIMA_HORA'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['ULTIMA_EJECUCION'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['ANALISTA_ASIGNADO'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($gestionTarea['ESTADO'] ?? '') ?></td>
                                                <td>
                                                    <button class="btn btn-primary doit-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($gestionTarea['id']) ?>" title="Hecho"
                                                        onclick="asignarEventosBotones();">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                <td>
                                                    <button class="btn btn-info edit-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($gestionTarea['id']) ?>"
                                                        title="Editar" onclick="asignarEventosBotones();">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger delete-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($gestionTarea['id']) ?>"
                                                        title="Eliminar" onclick="asignarEventosBotones();">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?> -->
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