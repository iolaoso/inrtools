<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';  // Incluye la sesión
include BASE_PATH . 'backend/gestioninr/gestioninrList.php'; // Incluir el archivo de consultas

// Obtener registros filtrados por usuario
if ($rol_nombre == 'SUPERUSER') {
    $result = obtenerGestionInrFull();
} else {
    if ($rol_nombre == 'ADMINISTRADOR' || $rol_nombre == 'DIRECTOR') {
        $result = obtenerGestionInrDireccion($inrdireccion_id);
    } else {
        $result = obtenerGestionInrPorUsuario($nickname);
    }
}

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


        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Gestiones INR - Reporte General</h1>
                <p>Reporte de las Gestiones realizadas por los Analistas</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-12">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div
                            class="card-header card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Registros Analista</h4>
                            <button id="btnformBack" class="btn btn-primary">Reporte Completo</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm display" style="font-size: 12px;"
                                    id="tablaActividadesFull">
                                    <thead>
                                        <tr>
                                            <th>COD</th>
                                            <th>SEGMENTO</th>
                                            <th>RUC</th>
                                            <th>RAZON SOCIAL</th>
                                            <th>DIRECCION</th>
                                            <th>COD_CAT</th>
                                            <th>CATEGORIA</th>
                                            <th>COD_SUBCAT</th>
                                            <th>SUBCATEGORIA</th>
                                            <th>COMPLEJIDAD</th>
                                            <th>FEC. REGISTRO</th>
                                            <th>FEC. OFICIO</th>
                                            <th>OFICIO</th>
                                            <th>COMENTARIO</th>
                                            <th>ANALISTA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result as $gestioninr): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($gestioninr['COD_GESTION'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['SEGMENTO'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['RUC_ENTIDAD'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['RAZON_SOCIAL'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['DIRECCION'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['COD_CATEGORIA'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['CATEGORIA'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['COD_SUBCATEGORIA'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['SUBCATEGORIA'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['COMPLEJIDAD'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['FECHA_REGISTRO'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['FECHA_OFIC_TRAM'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['OFICIO_TRAMITE'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($gestioninr['COMENTARIO'] ?? '') ?></td>
                                            <td class="text-center button-cell">
                                                <?= htmlspecialchars($gestioninr['ANALISTA'] ?? '') ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

    <!-- Incluir el archivo AJAX -->
    <script src="<?php echo $base_url; ?>/assets/js/gestioninr/tablaCompleta.js"></script>
</body>

</html>