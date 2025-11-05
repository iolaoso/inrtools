<header class="header bg-dark text-white d-flex justify-content-between align-items-center p-2">
    <!-- div para mensajes de alerta  -->
    <div id="alert-container"></div>    
    <!-- TITULO ENCABEZADO -->
    <div class="div title">INRtools</div>
    <!-- INFORMACION DE USUARIO -->
    <div class="user-info">
        <img src="<?php echo $base_url; ?>/assets/images/profiles/user.png" width="30" height="30" alt="Usuario"
            style="margin-right: 20px;">
        <span><strong>USR:</strong> <?php echo htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8'); ?></span>
        <!-- <span class="ms-3">Id. Dirección: <?php echo htmlspecialchars($inrdireccion_id, ENT_QUOTES, 'UTF-8'); ?></span> -->
        <span class="ms-3"><strong>Dirección:</strong>
            <?php echo htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="ms-3"><strong>Rol:
            </strong><?php echo htmlspecialchars($rol_nombre, ENT_QUOTES, 'UTF-8'); ?>
        </span>
    </div>
    <!-- BOTONES DE ACCION -->
    <div>
        <a href="<?php echo $base_url; ?>/frontend/enConstruccion.php" class="btn btn-info btn-sm"
            style="margin-right: 20px;">
            <i class="fa-solid fa-clock-rotate-left"></i> Mis Pendientes
        </a>
        <!-- <a href="<?php echo $base_url; ?>/frontend/tareas/gestionTareas.php" class="btn btn-info btn-sm"
            style="margin-right: 20px;">
            <i class="fa-solid fa-list-check"></i> Gestor de Tareas
        </a> -->
        <a href="<?php echo $base_url; ?>/backend/logout.php" class="btn btn-danger btn-sm">
            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
        </a>
    </div>
</header>