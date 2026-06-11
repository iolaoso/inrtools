<script>
    // Variables globales del sistema
    const currentPage = '<?php echo $current_page; ?>';
    const baseurl = '<?php echo $base_url; ?>';
    const nickname = '<?php echo $nickname; ?>';
    const usrDir = '<?php echo $direccion; ?>';
    const usrRol = '<?php echo $rol_nombre; ?>';
    const usrName = '<?php echo $nickname; ?>';
</script>

<!-- Scripts propios -->
<script src="<?php echo $base_url; ?>/assets/js/menuItems.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/sidebarFunctions.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/selectOptions.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/exportarTabla.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/filtrarTablas.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/entidades.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/analistas.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/alertas.js?v=<?= time(); ?>"></script>

<!-- Control de sesión -->
<script src="<?php echo $base_url; ?>/assets/js/session_timeout.js?v=<?= time(); ?>"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- XLSX -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<!-- Exportación -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>