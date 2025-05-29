<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/psi/psiList.php'; // Archivo con funciones para PSI

// Incluye la función de validación
include BASE_PATH . 'backend/validarAcceso.php';

// Define usuarios permitidos (variable configurable)
$usuarios_permitidos = ['DPAGUAY', 'ILOPEZ'];

// Valida acceso
validarAccesoUsuario($nickname, $usuarios_permitidos, $base_url);


// Obtener registros PSI activos con ULTIMO_CORTE = 2
if ($rol_nombre == 'ADMINISTRADOR' || $rol_nombre == 'SUPERUSER' || $rol_nombre == 'DIRECTOR') {
    $result = obtenerPsiActivos();
} else {
    $result = obtenerPsiActivos($nickname); // Implementa esta función si quieres filtrar por usuario
}

// Si usas alguna lista de analistas o catálogos, también los puedes incluir aquí
//$analistas = obtenerAnalistas($direccion);

?>

<!DOCTYPE html>
<html lang="es">

<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>
    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">

            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Gestión PSI</h1>
                <p>Registro y edición de datos PSI</p>
            </div>

            <section class="row align-items-stretch">

                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Datos PSI</h4>
                        </div>
                        <div class="card-body">
                            <form id="formPsi" method="POST" action="<?= $base_url ?>backend/psiActions.php">
                                <input type="hidden" id="id" name="id" value="0" />

                                <div class="mb-3">
                                    <label for="NUMERO" class="form-label">NUMERO</label>
                                    <input type="text" class="form-control" id="NUMERO" name="NUMERO" required>
                                </div>
                                <div class="mb-3">
                                    <label for="COD_UNICO" class="form-label">COD_UNICO</label>
                                    <input type="text" class="form-control" id="COD_UNICO" name="COD_UNICO" required>
                                </div>
                                <div class="mb-3">
                                    <label for="RUC" class="form-label">RUC</label>
                                    <input type="text" class="form-control" id="RUC" name="RUC" required>
                                </div>
                                <div class="mb-3">
                                    <label for="RAZON_SOCIAL" class="form-label">RAZÓN SOCIAL</label>
                                    <input type="text" class="form-control" id="RAZON_SOCIAL" name="RAZON_SOCIAL"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="SEGMENTO" class="form-label">SEGMENTO</label>
                                    <input type="text" class="form-control" id="SEGMENTO" name="SEGMENTO">
                                </div>
                                <div class="mb-3">
                                    <label for="ZONAL" class="form-label">ZONAL</label>
                                    <input type="text" class="form-control" id="ZONAL" name="ZONAL">
                                </div>
                                <div class="mb-3">
                                    <label for="ESTADO_JURIDICO" class="form-label">ESTADO JURÍDICO</label>
                                    <input type="text" class="form-control" id="ESTADO_JURIDICO" name="ESTADO_JURIDICO">
                                </div>
                                <div class="mb-3">
                                    <label for="TIPO_SUPERVISION" class="form-label">TIPO SUPERVISIÓN</label>
                                    <input type="text" class="form-control" id="TIPO_SUPERVISION"
                                        name="TIPO_SUPERVISION">
                                </div>
                                <div class="mb-3">
                                    <label for="FECHA_INICIO" class="form-label">FECHA INICIO</label>
                                    <input type="date" class="form-control" id="FECHA_INICIO" name="FECHA_INICIO">
                                </div>
                                <div class="mb-3">
                                    <label for="FECHA_FIN" class="form-label">FECHA FIN</label>
                                    <input type="date" class="form-control" id="FECHA_FIN" name="FECHA_FIN">
                                </div>

                                <!-- Puedes continuar agregando más campos aquí igual -->

                                <div class="mb-3">
                                    <label for="ULTIMO_CORTE" class="form-label">ULTIMO CORTE</label>
                                    <input type="number" class="form-control" id="ULTIMO_CORTE" name="ULTIMO_CORTE"
                                        value="2" required>
                                </div>

                                <div class="mb-3">
                                    <label for="EST_REGISTRO" class="form-label">ESTADO REGISTRO</label>
                                    <select class="form-control" id="EST_REGISTRO" name="EST_REGISTRO" required>
                                        <option value="ACT">ACTIVO</option>
                                        <option value="DEL">ELIMINADO</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="USR_CREACION" class="form-label">Usuario Creación</label>
                                    <input type="text" readonly class="form-control" id="USR_CREACION"
                                        name="USR_CREACION" value="<?= htmlspecialchars($nickname) ?>">
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Guardar
                                        Registro</button>
                                    <button type="button" id="btnLimpiar"
                                        class="btn btn-secondary btn-sm btn-block mt-2">Limpiar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Registros PSI (Último corte = 2)</h4>
                            <input type="text" id="searchInput" class="form-control form-control-sm w-50"
                                placeholder="Buscar..." onkeyup="filterTable()" />
                        </div>
                        <div class="card-body" style="overflow-x: auto;">
                            <table class="table table-striped table-sm" style="font-size: 12px;" id="tablaPsi">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NUMERO</th>
                                        <th>COD_UNICO</th>
                                        <th>RUC</th>
                                        <th>RAZÓN SOCIAL</th>
                                        <th>ULTIMO CORTE</th>
                                        <th>ESTADO</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $psi): ?>
                                    <tr data-id="<?= htmlspecialchars($psi['id']) ?>">
                                        <td><?= htmlspecialchars($psi['id']) ?></td>
                                        <td><?= htmlspecialchars($psi['NUMERO']) ?></td>
                                        <td><?= htmlspecialchars($psi['COD_UNICO']) ?></td>
                                        <td><?= htmlspecialchars($psi['RUC']) ?></td>
                                        <td><?= htmlspecialchars($psi['RAZON_SOCIAL']) ?></td>
                                        <td><?= htmlspecialchars($psi['ULTIMO_CORTE']) ?></td>
                                        <td><?= htmlspecialchars($psi['EST_REGISTRO']) ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm btn-editar" type="button">Editar</button>
                                            <button class="btn btn-danger btn-sm btn-eliminar"
                                                type="button">Eliminar</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($result)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No hay registros para mostrar.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </section>
        </main>
    </div>

    <div id="base_url" data-base-url="<?= htmlspecialchars($base_url) ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <script>
    // Filtrar tabla por búsqueda
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#tablaPsi tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
        });
    }

    // Cargar datos del registro en el formulario para editar
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', () => {
            const tr = button.closest('tr');
            const id = tr.getAttribute('data-id');

            // Recorremos las celdas para llenar el formulario según los campos
            document.getElementById('id').value = id;
            document.getElementById('NUMERO').value = tr.children[1].textContent.trim();
            document.getElementById('COD_UNICO').value = tr.children[2].textContent.trim();
            document.getElementById('RUC').value = tr.children[3].textContent.trim();
            document.getElementById('RAZON_SOCIAL').value = tr.children[4].textContent.trim();
            document.getElementById('ULTIMO_CORTE').value = tr.children[5].textContent.trim();
            document.getElementById('EST_REGISTRO').value = tr.children[6].textContent.trim();

            // Aquí puedes cargar más campos si los agregas al formulario
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Eliminar registro (soft delete)
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', () => {
            if (!confirm('¿Seguro que deseas eliminar este registro?')) return;
            const tr = button.closest('tr');
            const id = tr.getAttribute('data-id');

            fetch('<?= $base_url ?>backend/psiActions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        accion: 'eliminar',
                        id: id
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Registro eliminado correctamente');
                        tr.remove();
                        document.getElementById('formPsi').reset();
                    } else {
                        alert('Error al eliminar');
                    }
                });
        });
    });

    // Limpiar formulario
    document.getElementById('btnLimpiar').addEventListener('click', () => {
        document.getElementById('formPsi').reset();
        document.getElementById('id').value = 0;
    });
    </script>

</body>

</html>