<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/direcciones/direccionesList.php';

// Obtener direcciones desde la base de datos
$dirToSelect = obtenerDireccionesFull(); // Asegúrate de implementar esta función

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
                <h1 class="display-6 tituloPagina">Agregar Persona</h1>
                <p>Registro de personas dentro del sistema</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Datos de la Persona</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">Persona agregada exitosamente.</div>
                            <?php endif; ?>

                            <form id="formPersona" method="POST">
                                <div class="mb-3">
                                    <label for="identificacion" class="form-label">Identificación</label>
                                    <input type="text" class="form-control" id="identificacion" name="identificacion"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        style="text-transform: uppercase;" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="inrdireccion_id" class="form-label">Dirección INR</label>
                                    <select class="form-control" id="inrdireccion_id" name="inrdireccion_id" required>
                                        <option value="">-- Selecciona una dirección --</option>
                                        <?php foreach ($dirToSelect as $dselect): ?>
                                        <option value="<?= $dselect['idDirSelect'] ?>">
                                            <?= htmlspecialchars($dselect['dirSelect']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Guardar
                                        Registro</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <script src="<?= $base_url; ?>/assets/js/users/persona.js"></script>
</body>

</html>