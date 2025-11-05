<script>
// Obtener la dirección y rol del usuario (esto debería venir del servidor)
const currentPage = '<?php echo $current_page; ?>'; // Obtener la página actual
const baseurl = '<?php echo $base_url; ?>';
const nickname = '<?php echo $nickname; ?>';
const usrDir = '<?php echo $direccion; ?>';
const usrRol = '<?php echo $rol_nombre; ?>';
const usrName = '<?php echo $nickname; ?>';
</script>
<script src="<?php echo $base_url; ?>/assets/js/menuItems.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/sidebarFunctions.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/exportarTabla.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/filtrarTablas.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/entidades.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/analistas.js?v=<?= time(); ?>"></script>
<script src="<?php echo $base_url; ?>/assets/js/alertas.js?v=<?= time(); ?>"></script>
<!-- Enlazar JavaScript para la caducidad de sesión -->
<script src="<?php echo $base_url; ?>/assets/js/session_timeout.js?v=<?= time(); ?>"></script>
<!-- Icons -->
<script src="https://kit.fontawesome.com/1d3a0435ab.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<!-- JSZip (para Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- pdfmake (para PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<!-- Botones HTML5 (Excel, PDF, etc.) -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<!-- Botón de impresión -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>