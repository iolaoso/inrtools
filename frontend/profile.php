<?php
include_once __DIR__ . '/../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once __DIR__ . '/partials/head.php'; ?>

<body>

    <!-- Incluir el Header -->
    <?php include_once __DIR__ . '/partials/header.php'; ?>

    <div class="d-flex">
        <!-- Incluir el Sidebar -->
        <?php include_once __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Perfil de Usuario</h1>
                <p>Ajustes principales del usuario</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Cambio de Contraseña</h4>
                        </div>
                        <div class="card-body">
                            <form id="formChangePwd" method="POST">
                                <div class="form-group">
                                    <label for="username">Nombre de Usuario</label>
                                    <input type="hidden" class="form-control" id="userid" name="userid"
                                        value="<?= htmlspecialchars($user_id) ?>" readonly>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="<?= htmlspecialchars($nickname) ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($email_persona) ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="currentPassword">Contraseña Actual</label>
                                    <input type="password" class="form-control" id="currentPassword"
                                        name="currentPassword" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">Nueva Contraseña</label>
                                    <input type="text" class="form-control" id="newPassword" name="newPassword"
                                        required>
                                    <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar tu
                                        contraseña.</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                            </form>
                            <div id="responseMessage" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Incluir el Footer -->
    <?php include_once __DIR__ . '/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once __DIR__ . '/partials/scripts.php'; ?>

    <!-- Incluir los archivo js -->
    <script src="<?php echo $base_url; ?>/assets/js/users/profile.js"></script>

</body>

</html>