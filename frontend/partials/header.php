<header class="header <?= ($ambiente === 'Desarrollo') ? 'header-dev' : 'header-prod' ?>">

    <div id="alert-container"></div>

    <div class="container-fluid">

        <div class="row align-items-center py-2">

            <!-- LOGO + SISTEMA -->

            <div class="col-md-3">

                <div class="d-flex align-items-center">

                    <img
                        src="<?= $base_url ?>/assets/images/seps_large.jpg"
                        alt="INRTools"
                        class="rounded me-3"
                        style="height:50px;">

                    <div>

                        <h4 class="mb-0 fw-bold text-primary">
                            INRTools
                        </h4>

                        <small class="text-muted">
                            Plataforma Institucional de Riesgos
                        </small>

                    </div>

                </div>

            </div>

            <!-- USUARIO -->

            <div class="col-md-6">

                <div class="d-flex flex-wrap gap-2 justify-content-center">

                    <span class="badge bg-light text-dark border px-3 py-2">
                        👤 <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <span class="badge bg-success px-3 py-2">
                        🛡 <?= htmlspecialchars($rol_nombre, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <span class="badge bg-primary px-3 py-2">
                        🏢 <?= htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <span class="badge bg-secondary px-3 py-2">
                        🔑 <?= htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <!-- <span class="badge <?= ($ambiente === 'Desarrollo') ? 'bg-warning text-dark' : 'bg-dark' ?>">
                        <?= htmlspecialchars($ambiente, ENT_QUOTES, 'UTF-8') ?>
                    </span> -->

                </div>

            </div>

            <!-- ACCIONES -->

            <div class="col-md-3 text-end">

                <a
                    href="<?= $base_url ?>/frontend/enConstruccion.php"
                    class="btn btn-outline-primary btn-sm">

                    <i class="fa-solid fa-list-check"></i>
                    Pendientes

                </a>

                <a
                    href="<?= $base_url ?>/backend/logout.php"
                    class="btn btn-danger btn-sm">

                    <i class="fas fa-sign-out-alt"></i>
                    Salir

                </a>

            </div>

        </div>

    </div>

</header>