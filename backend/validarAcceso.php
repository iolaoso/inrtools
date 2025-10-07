<?php
// validar_acceso.php

/**
 * Valida que el usuario actual esté autorizado para acceder a la página.
 * Si no está autorizado, muestra alerta y redirige al main.
 *
 * @param string $nickname Nombre de usuario actual (de sesión)
 * @param array $usuarios_autorizados Array con nicknames permitidos
 * @param string $base_url URL base para redirección
 */
function validarAccesoUsuario(string $nickname, array $usuarios_autorizados, string $base_url)
{
    if (!in_array(strtoupper($nickname), array_map('strtoupper', $usuarios_autorizados))) {
        header('HTTP/1.1 403 Forbidden');
?>
<script>
alert('Acceso denegado. Serás redirigido al inicio.');
window.location.href = '<?= rtrim($base_url, '/') ?>/frontend/main.php';
</script>
<noscript>
    <h1>Acceso Denegado</h1>
    <p>No tienes permisos para acceder a esta página.</p>
    <p><a href="<?= rtrim($base_url, '/') ?>/frontend/main.php">Ir al inicio</a></p>
</noscript>
<?php
        exit;
    }
}