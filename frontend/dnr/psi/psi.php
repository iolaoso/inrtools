<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/psi/psiList.php'; // Archivo con funciones para PSI
//include BASE_PATH . 'backend/catastroList.php'; // consulta catastro activas
//include BASE_PATH . 'backend/analistasList.php'; // consulta analistas

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
    $result = obtenerPsiActivos($nickname, $rol_nombre); // Implementa esta función si quieres filtrar por usuario
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
                            <div class="container">
                                <form id="formPsi" method="POST" action="#">
                                    <input type="hidden" id="id" name="id" value="0" />

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="NUMERO" class="form-label">NÚMERO</label>
                                            <input type="text" class="form-control" id="NUMERO" name="NUMERO" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="COD_UNICO" class="form-label">CÓDIGO ÚNICO</label>
                                            <input type="text" class="form-control" id="COD_UNICO" name="COD_UNICO"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="RUC" class="form-label">RUC</label>
                                            <input type="text" class="form-control" id="RUC" name="RUC" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="RAZON_SOCIAL" class="form-label">RAZÓN SOCIAL</label>
                                            <input type="text" class="form-control" id="RAZON_SOCIAL"
                                                name="RAZON_SOCIAL" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-4">
                                            <label for="SEGMENTO" class="form-label">SEGMENTO</label>
                                            <input type="text" class="form-control" id="SEGMENTO" name="SEGMENTO">
                                        </div>
                                        <div class="col-4">
                                            <label for="ZONAL" class="form-label">ZONAL</label>
                                            <input type="text" class="form-control" id="ZONAL" name="ZONAL">
                                        </div>
                                        <div class="col-4">
                                            <label for="ESTADO_JURIDICO" class="form-label">ESTADO JURÍDICO</label>
                                            <input type="text" class="form-control" id="ESTADO_JURIDICO"
                                                name="ESTADO_JURIDICO">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="TIPO_SUPERVISION" class="form-label">TIPO SUPERVISIÓN</label>
                                            <input type="text" class="form-control" id="TIPO_SUPERVISION"
                                                name="TIPO_SUPERVISION">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="FECHA_INICIO" class="form-label">FECHA INICIO</label>
                                            <input type="date" class="form-control" id="FECHA_INICIO"
                                                name="FECHA_INICIO">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="FECHA_FIN" class="form-label">FECHA FIN</label>
                                            <input type="date" class="form-control" id="FECHA_FIN" name="FECHA_FIN">
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col">
                                            <label for="ULTIMO_CORTE" class="form-label">ÚLTIMO CORTE</label>
                                            <input type="number" class="form-control" id="ULTIMO_CORTE"
                                                name="ULTIMO_CORTE" value="2" required>
                                        </div>
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
                                        <button class="btn btn-primary btn-sm btn-block save-btn" type="submit"
                                            title="Save" onclick="asignarActions(this, 'save');">
                                            Guardar Registro
                                        </button>
                                        <button type="button" id="btnLimpiar"
                                            class="btn btn-secondary btn-sm btn-block">Limpiar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Registros PSI (Último corte = 2)</h4>
                            <ul class="list-unstyled d-flex mb-0">
                                <li class="mx-2">
                                    <button id="exportButton" class="btn btn-primary btn-sm"
                                        onclick="exportTableToExcel('tablaPsi', 'ReportePSI')">Exportar</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body" style="overflow-x: auto;">
                            <input class="form-control" type="text" id="searchInput" onkeyup="filterTable('tablaPsi')"
                                placeholder="Buscar...">
                            <div class="table-container" style="max-height: 700px; overflow-y: auto;">
                                <table class="table table-striped table-sm" style="font-size: 12px;" id="tablaPsi">
                                    <thead>
                                        <tr>
                                            <th>RUC</th>
                                            <th>RAZON SOCIAL</th>
                                            <th>INFORME</th>
                                            <th>RESOLUCION</th>
                                            <th>ESTADO</th>
                                            <th>VIGENCIA</th>
                                            <th>VENCIMIENTO</th>
                                            <th>DETALLE</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $psi): ?>
                                            <tr data-id="<?= htmlspecialchars($psi['id']) ?>">
                                                <td><?= htmlspecialchars($psi['RUC'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($psi['RAZON_SOCIAL'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($psi['NUM_INFORME'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($psi['NUM_RESOLUCION'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($psi['ESTADO_PSI'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($psi['VIGENCIA_PSI'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($psi['ANIO_VENCIMIENTO'] ?? 'N/A') ?></td>
                                                <td class="text-center button-cell">
                                                    <button class="btn btn-primary detalle-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($psi['id'] ?? '') ?>"
                                                        title="Detalle PSI" data-bs-toggle="modal"
                                                        data-bs-target="#detalleModalPsi" onclick="cargarDatosPsi(this)">
                                                        <i class="fa-solid fa-comment"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info edit-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($psi['id']) ?>" title="Editar"
                                                        onclick="asignarActions(this, 'edit');">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger delete-btn btn-sm"
                                                        data-id="<?= htmlspecialchars($psi['id']) ?>" title="Eliminar"
                                                        onclick="asignarActions(this, 'delete');">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
                </div>

            </section>
        </main>
    </div>

    <!-- Modal para Detalle PSI -->
    <div class="modal fade" id="detalleModalPsi" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">Detalle PSI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="detalleid" class="form-label">ID</label>
                            <input type="text" class="form-control" id="detalleid" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleFecCorte" class="form-label">Fecha Corte: </label>
                            <input type="text" class="form-control" id="detalleFecCorte" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleRUC" class="form-label">RUC</label>
                            <input type="text" class="form-control" id="detalleRUC" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleSegmento" class="form-label">Segmento</label>
                            <input type="text" class="form-control" id="detalleSegmento" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleZonal" class="form-label">Zonal</label>
                            <input type="text" class="form-control" id="detalleZonal" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleEstadoJuridico" class="form-label">Estado Jurídico</label>
                            <input type="text" class="form-control" id="detalleEstadoJuridico" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleTipoSupervision" class="form-label">Tipo Supervision</label>
                            <input type="text" class="form-control" id="detalleTipoSupervision" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="detalleRazonSocial" class="form-label">Razón Social</label>
                            <input type="text" class="form-control" id="detalleRazonSocial" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detallefechaIni" class="form-label">Fecha Inicio</label>
                            <input type="text" class="form-control" id="detallefechaIni" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detallefechaFin" class="form-label">Fecha Fin</label>
                            <input type="text" class="form-control" id="detallefechaFin" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleMesVenc" class="form-label">Mes de Vencimiento</label>
                            <input type="text" class="form-control" id="detalleMesVenc" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleAnioVenc" class="form-label">Año de Vencimiento</label>
                            <input type="text" class="form-control" id="detalleAnioVenc" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleTrime" class="form-label">Trimestre</label>
                            <input type="text" class="form-control" id="detalleTrime" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleEstado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="detalleEstado" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleVigencia" class="form-label">Vigencia</label>
                            <input type="text" class="form-control" id="detalleVigencia" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleFecAproPF" class="form-label">Fec. Plan Fisico</label>
                            <input type="text" class="form-control" id="detalleFecAproPF" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleNumInf" class="form-label">Número de Informe</label>
                            <input type="text" class="form-control" id="detalleNumInf" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleFecInf" class="form-label">Fecha de Informe</label>
                            <input type="text" class="form-control" id="detalleFecInf" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleNumRes" class="form-label">Resolución</label>
                            <input type="text" class="form-control" id="detalleNumRes" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="detalleFecRes" class="form-label">Fecha de Resolución</label>
                            <input type="text" class="form-control" id="detalleFecRes" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="detalleResAmp" class="form-label">Res. de Ampliación</label>
                            <input type="text" class="form-control" id="detalleResAmp" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="detalleFecResAmp" class="form-label">Fec. Res. Ampliación</label>
                            <input type="text" class="form-control" id="detalleFecResAmp" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleUltBal" class="form-label">Fecha Último Balance</label>
                            <input type="text" class="form-control" id="detalleUltBal" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleActivos" class="form-label">Activos</label>
                            <input type="text" class="form-control" id="detalleActivos" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleUltRiesgo" class="form-label">Último Riesgo</label>
                            <input type="text" class="form-control" id="detalleUltRiesgo" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleResFinPSI" class="form-label">Resolución Fin PSI</label>
                            <input type="text" class="form-control" id="detalleResFinPSI" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleFecResFinPSI" class="form-label">Fecha de Res. Fin PSI</label>
                            <input type="text" class="form-control" id="detalleFecResFinPSI" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="detalleMotCierre" class="form-label">Motvo de Cierre</label>
                            <input type="text" class="form-control" id="detalleMotCierre" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="detalleEstSup" class="form-label">Estrategia de Supervisión</label>
                            <input type="text" class="form-control" id="detalleEstSup" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div id="base_url" data-base-url="<?= htmlspecialchars($base_url) ?>"></div>

    <!-- Incluir el Footer -->
    <?php include_once BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/psi/psi.js"></script>


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