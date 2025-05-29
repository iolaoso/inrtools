<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>

    <!-- Incluir el Header -->
    <?php include_once BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <!-- Incluir el Sidebar -->
        <?php include_once BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-4" id="main-content">
            <h1>reportes</h1>
            <p><?php echo BASE_PATH; ?></p>
        </main>
    </div>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <script>
        const currentPage = '<?php echo $current_page; ?>'; // Obtener la página actual
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>