<?php
require_once '../backend/config.php';

// Recibe mensaje
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    // Muestra el mensaje en un div
    echo "<div class='alert alert-info'>$msg</div>";
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login_style.css"> <!-- Enlace al archivo CSS -->
</head>

<body>
    <div class="login-container">
        <!-- agrega el logo -->
        <div class="text-center mb-2">
            <img src="../assets/images/seps.jpg" alt="SEPS" class="login-logo">
        </div>
        <h1 class="text-center login-title">SEPS <span style="color: #111056;">INRTools</span></h1>
        <h2 class="text-center login-title">Iniciar Sesión</h2>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message text-center'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="../backend/login_backend.php" method="post">
            <div class="mb-3">
                <label for="nickname" class="form-label"><b>Nickname</b></label>
                <input type="text" class="form-control" id="nickname" name="nickname" style="text-transform: uppercase;"
                    required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><b>Contraseña</b></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
        <p class="text-center mt-3">¿Olvidaste tu contraseña? <a href="recuperarPdw.php">Recuperala aquí</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>