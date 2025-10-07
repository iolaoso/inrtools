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
        <main class="content p-4" id="main-content">
            <div class="row align-items-center mb-3">
                <div class="col-2">
                    <img src="<?php echo $base_url; ?>/assets/images/seps_large.jpg" alt="Reporte SEPS" class="rounded"
                        style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);" height="200px">
                </div>
                <div class="col-10">
                    <h1 class="display-3" style="text-shadow: 0px 0px 9px #7d8b9b;">Acerca de INR Tools</h1>
                    <p>INR Tools es una iniciativa desarrollada por la Dirección Nacional de Riesgos en el año 2025.</p>
                </div>
            </div>

            <section class="row align-items-center">
                <h2 class="display-5 mb-3" style="text-shadow: 0px 0px 9px #7d8b9b;">INR Tools</h2>

                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-body">
                            <p class="card-text">Este sistema ha sido creado con el propósito de ofrecer herramientas efectivas que faciliten la gestión de la información procesada por la INR, mejorando así la toma de decisiones y la eficiencia operativa.</p>
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
</body>

</html>