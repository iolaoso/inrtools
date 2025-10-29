<!-- Modal para Buscar Supervisión -->
<div class="modal fade" id="buscarSupervisionModal" tabindex="-1" aria-labelledby="buscarSupervisionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="buscarSupervisionModalLabel">
                    <i class="fas fa-search me-2"></i>Buscar Supervisiones por RUC
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Información de la entidad -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Información de la Entidad</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>RUC:</strong>
                                <span id="modalRuc" class="ms-2">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Razón Social:</strong>
                                <span id="modalRazonSocial" class="ms-2">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultados de búsqueda -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaSupervisiones">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Seleccionar</th>
                                        <th>ID</th>
                                        <th>Estrategia</th>
                                        <th>Fase</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodySupervisiones">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-search fa-2x mb-2"></i><br>
                                            Ingrese un RUC y haga clic en Buscar
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="buscarSupervisionesPorRuc()">
                    <i class="fas fa-search me-1"></i> Buscar Supervisiones
                </button>
            </div>
        </div>
    </div>
</div>