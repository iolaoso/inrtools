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

            <!-- ===================================================== -->
            <!-- ENCABEZADO                                            -->
            <!-- ===================================================== -->

            <div class="row g-3 mb-3">

                <div class="col-12">

                    <div class="card border-primary shadow-sm">

                        <div class="card-body">

                            <div class="row align-items-center">

                                <div class="col-md-8">

                                    <h1 class="display-6 tituloPagina mb-1">
                                        BITÁCORA DE USUARIOS
                                    </h1>

                                    <p class="text-muted mb-0">
                                        Registro y seguimiento de las actividades
                                        realizadas por los usuarios en INRtools.
                                    </p>

                                </div>

                                <div class="col-md-4 text-end">
                                    <span
                                        id="spinnerCarga"
                                        class="spinner-border text-primary ms-2"
                                        role="status"
                                        style="display:none; width:1.5rem; height:1.5rem;">

                                        <span class="visually-hidden">
                                            Cargando...
                                        </span>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- KPI                                                     -->
            <!-- ===================================================== -->

            <div class="row g-3 mb-4">

                <div class="col-md-3">

                    <div class="card border-primary shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted">
                                        Total Eventos
                                    </small>

                                    <h2
                                        class="mb-0 fw-bold"
                                        id="kpiTotal">
                                        0
                                    </h2>

                                </div>

                                <div class="fs-1 text-primary">
                                    <i class="fas fa-list"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="card border-success shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted">
                                        Eventos OK
                                    </small>

                                    <h2
                                        class="mb-0 fw-bold text-success"
                                        id="kpiOK">
                                        0
                                    </h2>

                                </div>

                                <div class="fs-1 text-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="card border-primary shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted">
                                        Descargas
                                    </small>

                                    <h2
                                        class="mb-0 fw-bold text-primary"
                                        id="kpiDescargas">
                                        0
                                    </h2>

                                </div>

                                <div class="fs-1 text-primary">
                                    <i class="fas fa-download"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="card border-danger shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small class="text-muted">
                                        Errores
                                    </small>

                                    <h2
                                        class="mb-0 fw-bold text-danger"
                                        id="kpiErrores">
                                        0
                                    </h2>

                                </div>

                                <div class="fs-1 text-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- FILTROS                                                 -->
            <!-- ===================================================== -->

            <div class="card border-secondary shadow-sm mb-3">

                <div class="card-header bg-secondary text-white">

                    <i class="fas fa-filter"></i>
                    Filtros de búsqueda

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-2">

                            <label class="form-label">
                                Fecha desde
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="fechaDesde">

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Fecha hasta
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="fechaHasta">

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Usuario
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="usuario"
                                placeholder="Nickname">

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Acción
                            </label>

                            <select
                                class="form-select"
                                id="accion">

                                <option value="">
                                    Todas
                                </option>

                                <option value="LOGIN">
                                    LOGIN
                                </option>

                                <option value="LOGOUT">
                                    LOGOUT
                                </option>

                                <option value="DESCARGA">
                                    DESCARGA
                                </option>

                                <option value="CONSULTA">
                                    CONSULTA
                                </option>

                                <option value="ALTA">
                                    ALTA
                                </option>

                                <option value="MODIFICACION">
                                    MODIFICACIÓN
                                </option>

                                <option value="ELIMINACION">
                                    ELIMINACIÓN
                                </option>

                            </select>

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Módulo
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="modulo"
                                placeholder="Módulo">

                        </div>


                        <div class="col-md-2 d-flex align-items-end">

                            <div class="w-100">

                                <button
                                    type="button"
                                    class="btn btn-primary w-100"
                                    id="btnBuscar">

                                    <i class="fas fa-search"></i>
                                    Buscar

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- TABLA                                                   -->
            <!-- ===================================================== -->

            <div class="card border-secondary shadow-sm">

                <div class="card-header bg-secondary text-white">

                    <i class="fas fa-history"></i>
                    Registro de actividades

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-hover table-striped table-sm align-middle"
                            id="tablaBitacora">

                            <thead class="table-dark text-center">

                                <tr>

                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Módulo</th>
                                    <th>Descripción</th>
                                    <th>Archivo</th>
                                    <th>Tipo</th>
                                    <th>Tamaño</th>
                                    <th>IP</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>

                                </tr>

                            </thead>

                            <tbody id="tbodyBitacora">

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>


    <!-- ===================================================== -->
    <!-- MODAL DETALLE                                         -->
    <!-- ===================================================== -->

    <div
        class="modal fade"
        id="modalDetalle"
        tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-info-circle"></i>
                        Detalle del evento

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <strong>Request ID:</strong>
                            <div id="detRequestId">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Usuario:</strong>
                            <div id="detUsuario">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Correo:</strong>
                            <div id="detCorreo">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Dirección:</strong>
                            <div id="detDireccion">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Rol:</strong>
                            <div id="detRol">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>IP:</strong>
                            <div id="detIP">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Host:</strong>
                            <div id="detHost">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Session ID:</strong>
                            <div id="detSession">-</div>
                        </div>

                        <div class="col-md-4">
                            <strong>Fecha evento:</strong>
                            <div id="detFecha">-</div>
                        </div>

                        <div class="col-md-12">
                            <strong>Descripción:</strong>
                            <div id="detDescripcion">-</div>
                        </div>

                        <div class="col-md-12">
                            <strong>Mensaje:</strong>
                            <div id="detMensaje">-</div>
                        </div>

                        <div class="col-md-12">

                            <strong>Datos adicionales:</strong>

                            <pre
                                id="detDatosExtra"
                                class="bg-light border rounded p-3 mt-2">
                        </pre>

                        </div>

                        <div class="col-md-12">

                            <strong>User Agent:</strong>

                            <div
                                id="detUserAgent"
                                class="bg-light border rounded p-2 mt-2 small">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div
        id="base_url"
        data-base-url="<?= $base_url; ?>">
    </div>


    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <script>
        const base_url =
            document.getElementById('base_url').dataset.baseUrl;
    </script>


    <script
        src="<?= $base_url; ?>/assets/js/bitacora/bitacoraUsuarios.js">
    </script>


</body>

</html>