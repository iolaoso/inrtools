<div class="modal fade" id="catastroModal" tabindex="-1" aria-labelledby="catastroModal" aria-hidden="true">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="catastroModal">Seleccionar Entidad del SF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="busquedaEntidad" style="text-transform: uppercase;"
                        onkeyup="filtrarEntidades()" placeholder="Filtrar Entidad...">
                </div>
                <div class="mb-3">
                    <label for="entidadesList" class="form-label">Entidades</label>
                    <ul class="list-group scrollable-list" id="entidadesList">
                        <?php foreach ($entidadesActSf as $entidadSF): ?>
                        <li class="list-group-item list-group-item-action"
                            onclick="seleccionarEntidad('<?= htmlspecialchars($entidadSF['RUC_ENTIDAD']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['RAZON_SOCIAL']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['SEGMENTO']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['ESTADO_JURIDICO']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['TIPO_ORGANIZACION']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['ZONAL']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['FEC_ULT_BALANCE']) ?>',
                                                        '<?= htmlspecialchars($entidadSF['NVL_RIESGO']) ?>')">
                            <?= htmlspecialchars($entidadSF['RAZON_SOCIAL']) ?> <!-- AQUI LO QUE SE MUESTRA EN CADA LINEA -->
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div> -->
        </div>
    </div>
</div>