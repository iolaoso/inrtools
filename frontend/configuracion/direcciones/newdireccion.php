<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/direcciones/direccionesList.php';

// Obtener direcciones para mostrar si es necesario
$direcciones = obtenerDireccionesFull(); // Asegúrate de implementar esta función

echo "<script>console.log('Direcciones cargadas: " . json_encode($direcciones) . "');</script>";

?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>
    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Gestión de Direcciones</h1>
                <p>Administración de direcciones dentro del sistema</p>
            </div>

            <!-- Botón para nueva dirección -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDireccion">
                        <i class="fas fa-plus"></i> Nueva Dirección
                    </button>
                </div>
            </div>

            <!-- Tabla de direcciones -->
            <section class="row align-items-stretch">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Listado de Direcciones</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">Operación realizada exitosamente.</div>
                            <?php endif; ?>

                            <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">Error al procesar la solicitud.</div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Dirección</th>
                                            <th>Nombre Dirección</th>
                                            <th>Estado</th>
                                            <th>Usuario Creación</th>
                                            <th>Fecha Creación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($direcciones as $inrDireccion): ?>
                                        <tr>
                                            <td><?= $inrDireccion['idDirSelect'] ?></td>
                                            <td><?= htmlspecialchars($inrDireccion['dirSelect']) ?></td>
                                            <td><?= htmlspecialchars($inrDireccion['dirNombreSelect']) ?></td>
                                            <td>
                                                <?php if ($inrDireccion['estRegistro'] == 1): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($inrDireccion['UsrCreacion']) ?></td>
                                            <td><?= $inrDireccion['createdAt'] ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning btn-editar" 
                                                        data-id="<?= $inrDireccion['idDirSelect'] ?>"
                                                        data-direccion="<?= htmlspecialchars($inrDireccion['dirSelect']) ?>"
                                                        data-dirnombre="<?= htmlspecialchars($inrDireccion['dirNombreSelect']) ?>"
                                                        data-estado="<?= $inrDireccion['estRegistro'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-eliminar" 
                                                        data-id="<?= $inrDireccion['idDirSelect'] ?>">
                                                    <i class="fas fa-trash"></i>
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
            </section>
        </main>
    </div>

    <!-- Modal para crear/editar dirección -->
    <div class="modal fade" id="modalDireccion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formDireccion" method="POST" action="<?= $base_url; ?>/backend/direcciones/guardarDireccion.php">
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   style="text-transform: uppercase;" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dirNombre" class="form-label">Nombre de la Dirección</label>
                            <input type="text" class="form-control" id="dirNombre" name="dirNombre" 
                                   style="text-transform: uppercase;" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estRegistro" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="estRegistro" name="estRegistro" 
                                value="Activo" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar esta dirección?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <script src="<?= $base_url; ?>/assets/js/direcciones/direcciones.js"></script>
</body>

</html>