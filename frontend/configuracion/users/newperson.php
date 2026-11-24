<?php
include_once __DIR__ . '/../../../backend/config.php';
include BASE_PATH . 'backend/session.php';
include BASE_PATH . 'backend/users/personasList.php';
include BASE_PATH . 'backend/direcciones/direccionesList.php';

// Obtener direcciones desde la base de datos
$dirToSelect = obtenerDireccionesFull(); // Asegúrate de implementar esta función
// obtener personas regstradas
$personas = obtenerPersonasFull();


?>

<!DOCTYPE html>
<html lang="es">

<!-- Incluir el head -->
<?php include_once BASE_PATH . 'frontend/partials/head.php'; ?>

<body>
    <?php include BASE_PATH . 'frontend/partials/header.php'; ?>

    <div class="d-flex">
        <?php include BASE_PATH . 'frontend/partials/sidebar.php'; ?>

        <!-- Contenido principal -->
        <main class="content p-3" id="main-content">
            <div class="row align-items-center mb-1">
                <h1 class="display-6 tituloPagina">Agregar Persona</h1>
                <p>Registro de personas dentro del sistema</p>
            </div>
            <section class="row align-items-stretch">
                <div class="col-md-5">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-info text-white">
                            <h4>Datos de la Persona</h4>
                        </div>
                        <div class="card-body">                           
                            <form id="formPersona" method="POST">
                                <div class="mb-3">
                                    <label for="identificacion" class="form-label">Identificación</label>  
                                    <input type="hidden" 
                                            id="idPersona" 
                                            name="idPersona"
                                            class="form-control"
                                            readonly>
                                    <input type="text" 
                                            id="identificacion" 
                                            name="identificacion"
                                            class="form-control" 
                                            required>                                                                                             
                                </div>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        style="text-transform: uppercase;" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="inrdireccion_id" class="form-label">Dirección INR</label>
                                    <select class="form-control" id="inrdireccion_id" name="inrdireccion_id" required>
                                        <option value="">-- Selecciona una dirección --</option>
                                        <?php foreach ($dirToSelect as $dselect): ?>
                                        <option value="<?= $dselect['idDirSelect'] ?>">
                                            <?= htmlspecialchars($dselect['dirSelect']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button id="btnGuardarPersona" type="submit" class="btn btn-primary btn-sm btn-block">Guardar
                                        Registro</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- TABLA DE PERSONAS -->
                <div class="col-md-7">
                    <div class="card h-100 d-flex flex-column border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h4>Personas Registradas</h4>
                        </div>
                        <div class="card-body">
                            <input class="form-control" type="text" id="searchInput"
                                onkeyup="filterTable('tablaPersonas')" placeholder="Buscar...">
                            <div class="d-flex justify-content-center">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-bordered table-striped table-hover table-sm"
                                        id="tablaPersonas">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" style="width:5%">#</th>
                                            <th class="text-center align-middle" style="width:15%">Identificación</th>
                                            <th class="text-center align-middle" style="width:30%">Nombre</th>
                                            <th class="text-center align-middle" style="width:20%">Email</th>
                                            <th class="text-center align-middle" style="width:15%">Dirección</th>
                                            <th class="text-center align-middle" style="width:15%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 1;
                                        foreach($personas as $persona):
                                        ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $contador++ ?></td>
                                            <td><?= htmlspecialchars($persona['identificacion']) ?></td>
                                            <td><?= htmlspecialchars($persona['nombre']) ?></td>
                                            <td><?= htmlspecialchars($persona['email']) ?></td>
                                            <td><?= htmlspecialchars($persona['direccion']) ?></td>
                                            <td class="text-center button-cell">
                                                <button
                                                    type="button"
                                                    class="btn btn-warning btn-sm btnEditarPersona"
                                                    data-id="<?= htmlspecialchars($persona['id']) ?>"
                                                    title="Editar" onclick="consultarPersona(<?= $persona['id'] ?>);">
                                                    <i class="fas fa-pencil"></i>
                                                </button>                                                
                                                <button class="btn btn-danger delete-btn btn-sm"
                                                    data-id="<?= htmlspecialchars($persona['id']) ?>"
                                                    title="Eliminar" onclick="delPersona(<?= $persona['id'] ?>);">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

    <div id="base_url" data-base-url="<?= $base_url; ?>"></div>

    <?php include BASE_PATH . 'frontend/partials/footer.php'; ?>

    <!-- Incluir los scripts -->
    <?php include_once BASE_PATH . 'frontend/partials/scripts.php'; ?>

    <script src="<?= $base_url; ?>/assets/js/users/persona.js"></script>
</body>

</html>