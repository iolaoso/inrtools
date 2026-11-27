<?php

include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';

?>

<!DOCTYPE html>
<html lang="es">

<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>

<?php include BASE_PATH . 'frontend/partials/header.php'; ?>

<div class="d-flex">

    <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>

    <main class="content p-3" id="main-content">

        <!-- ENCABEZADO -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-primary shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h1 class="display-6 tituloPagina mb-1">
                                    ADMINISTRACIÓN DE PERMISOS
                                </h1>

                                <p class="text-muted mb-0">
                                    Gestión visual de roles, direcciones y usuarios asignados a los menús del sistema.
                                </p>
                            </div>

                            <div>
                                <button class="btn btn-primary" id="btnActualizar">
                                    <i class="fas fa-sync-alt"></i>
                                    Actualizar
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- KPI -->
        <div class="row g-3 mb-3">

            <div class="col-md-3">
                <div class="card border-primary shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Menús</small>
                        <h2 id="kpiMenus">0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-success shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Roles</small>
                        <h2 class="text-success" id="kpiRoles">0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-warning shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Direcciones</small>
                        <h2 class="text-warning" id="kpiDirecciones">0</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-danger shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Usuarios</small>
                        <h2 class="text-danger" id="kpiUsuarios">0</h2>
                    </div>
                </div>
            </div>

        </div>

        <!-- TABLA -->
        <div class="row">
            <div class="col-12">

                <div class="card border-secondary shadow-sm">

                    <div class="card-header bg-secondary text-white">
                        Gestión de permisos
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table
                                id="tblPermisos"
                                class="table table-bordered table-hover table-striped table-sm align-middle">

                                <thead class="table-dark text-center">

                                    <tr>
                                        <th>ID</th>
                                        <th>Padre</th>
                                        <th>Menú</th>
                                        <th>Roles</th>
                                        <th>Direcciones</th>
                                        <th>Usuarios</th>
                                        <th width="120">Acciones</th>
                                    </tr>

                                </thead>

                                <tbody></tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </main>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalPermisos" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    Configuración de permisos
                </h5>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="menu_id">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Roles</span>
                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-success"
                                        id="btnSeleccionarTodosRoles">
                                        Todos
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        id="btnDeseleccionarTodosRoles">
                                        Ninguno
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="rolesContainer" class="row g-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Direcciones</span>
                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-success"
                                        id="btnSeleccionarTodasDirecciones">
                                        Todas
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        id="btnDeseleccionarTodasDirecciones">
                                        Ninguna
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="direccionesContainer" class="permisos-grid"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Usuarios</span>
                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-success"
                                        id="btnSeleccionarTodosUsuarios">
                                        Todos
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        id="btnDeseleccionarTodosUsuarios">
                                        Ninguno
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input
                                        type="text"
                                        id="txtBuscarUsuario"
                                        class="form-control"
                                        placeholder="Buscar usuario...">
                                </div>
                                <div
                                    id="usuariosContainer"
                                    class="permisos-grid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-success"
                    id="btnGuardar">
                    <i class="fas fa-save"></i>
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="base_url" data-base-url="<?= $base_url ?>"></div>

<?php include BASE_PATH . 'frontend/partials/footer.php'; ?>
<?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>



<script>
    const base_url =
        document.getElementById('base_url').dataset.baseUrl;
</script>

<script src="<?= $base_url ?>/assets/js/permisos_roles/permisos_roles.js"></script>

</body>
</html>