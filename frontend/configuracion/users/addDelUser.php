<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/direcciones/direccionesList.php';

// Verificar que la sesión está activa y que el usuario está autenticado
if (!isset($nickname)) {
    echo "Usuario no autenticado.";
    exit();
}

// Obtener la lista de personas
$personas_sql = "SELECT id, identificacion, nombre FROM personas ORDER BY 1 DESC";
$personas_result = $conn->query($personas_sql);

// Obtener la lista de roles
$rolesList_sql = "SELECT DISTINCT(nombre) AS rolName,id  FROM roles";
$rolesList_result = $conn->query($rolesList_sql);

// Obtener la lista de usuarios existentes
$usuarios_sql = "SELECT P.identificacion, P.nombre, D.direccion, U.id, U.nickname, idRol, rol 
                 FROM personas P 
                 LEFT JOIN inrdireccion D ON P.inrdireccion_id = D.id
                 INNER JOIN (SELECT id, persona_id, nickname FROM users WHERE estRegistro = 1) U
                 ON P.id = U.persona_id
                 LEFT JOIN (SELECT user_id, rol_id FROM user_roles) UR
                 ON U.id = UR.user_id
                 LEFT JOIN (SELECT id AS idRol, nombre AS rol FROM roles WHERE estRegistro = 1) R on UR.rol_id = R.idRol
                 ORDER BY U.id DESC";
$usuarios_result = $conn->query($usuarios_sql);
require_once BASE_PATH . 'frontend/partials/head.php';
?>

<body>
    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>
        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Agregar Usuario</h1>
                <p>Registro de usuarios dentro del sistema</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-5">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Agregar Nuevo Usuario</h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_GET['success'])): ?>
                                <div class="alert alert-success">Usuario agregado exitosamente.</div>
                            <?php endif; ?>

                            <form id="addUserForm" method="POST">
                                <!-- Cambia 'add_user.php' según tu lógica -->
                                <div class="mb-3">
                                    <label for="persona_id">Seleccionar Persona:</label>
                                    <select name="persona_id" class="form-control" required>
                                        <option value="">Seleccione una persona</option>
                                        <?php while ($persona = $personas_result->fetch_assoc()): ?>
                                            <option value="<?= $persona['id'] ?>">
                                                <?php echo $persona['identificacion'] . ' - ' . $persona['nombre'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nickname">Nombre de Usuario:</label>
                                    <input type="text" name="nickname" class="form-control"
                                        style="text-transform: uppercase;" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password">Contraseña:</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="rol">Rol:</label>
                                    <select name="rol_id" class="form-control" required>
                                        <option value="">Seleccione un Rol</option>
                                        <?php while ($rolItem = $rolesList_result->fetch_assoc()): ?>
                                            <option value="<?= $rolItem['id'] ?>"><?php echo $rolItem['rolName'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Usuarios Existentes</h4>
                        </div>
                        <div class="card-body">
                            <input class="form-control" type="text" id="searchInput"
                                onkeyup="filterTable('tablaUsuarios')" placeholder="Buscar...">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaUsuarios">
                                        <thead>
                                            <tr>
                                                <th>Identificación</th>
                                                <th>Nombre</th>
                                                <th>Dirección</th>
                                                <th>Nickname</th>
                                                <th>Rol</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($usuario = $usuarios_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $usuario['identificacion'] ?></td>
                                                    <td><?= $usuario['nombre'] ?></td>
                                                    <td><?= $usuario['direccion'] ?></td>
                                                    <td><?= $usuario['nickname'] ?></td>
                                                    <td><?= $usuario['rol'] ?></td>
                                                    <td>
                                                        <button class="btn btn-danger delete-btn btn-sm"
                                                            data-id="<?= htmlspecialchars($usuario['id']) ?>"
                                                            title="Eliminar" onclick="deleteUser($usuario['id']);">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>

            <script>
                function deleteUser(userId) {
                    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
                        window.location.href = "delete_user.php?id=" + userId; // Cambia 'delete_user.php' según tu lógica
                    }
                }
            </script>

        </main>
    </div>

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <script src="<?= $base_url; ?>/assets/js/users/user.js"></script>
</body>

</html>