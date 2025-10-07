<?php
include_once __DIR__ . '/../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
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
                <h1 class="display-6 tituloPagina">Sección en Construcción</h1>
            </div>

            <section class="row align-items-stretch">
                <div class="col-12">
                    <div class="card border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Estamos trabajando en esta sección</h4>
                        </div>
                        <div class="card-body text-center py-5">
                            <div class="construction-content">
                                <i class="fas fa-hard-hat text-warning mb-4" style="font-size: 5rem;"></i>
                                <h3 class="mb-3">¡Próximamente disponible!</h3>
                                <p class="lead">Estamos desarrollando esta sección para ofrecerte una mejor experiencia.
                                </p>
                                <div class="progress mt-4 mb-4" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                        style="width: 75%"></div>
                                </div>
                                <p>Mientras tanto, puedes explorar otras secciones de la aplicación.</p>
                                <a href="<?php echo $base_url; ?>/frontend/main.php" class="btn btn-primary mt-3">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al inicio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <style>
        .construction-content {
            max-width: 100%;
            max-height: 320px;
            margin: 0 auto;
            padding: 20px;
        }

        .progress-bar {
            animation: progressAnimation 2s infinite;
        }

        @keyframes progressAnimation {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }

        .fa-hard-hat {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-20px);
            }

            60% {
                transform: translateY(-10px);
            }
        }
    </style>
</body>

</html>