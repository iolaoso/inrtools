<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraeña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login_style.css"> <!-- Enlace al archivo CSS -->
</head>
<<body>
    <div class="login-container">
        <h1 class="text-center login-title">SEPS <span style="color: #111056;">INRTools</span></h1>
        <h2 class="text-center login-title">Recuperar Contraseña</h2>
        <form action="../backend/recuperarPdw.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="nickname"><b>Usuario:</b></label>
                <input type="text" class="form-control" id="nickname" name="nickname" style="text-transform: uppercase;" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label"><b>Correo Electrónico:</b></label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Enviar Correo de Recuperación</button>
            </div>
        </form>
    </div>
    </body>

</html>