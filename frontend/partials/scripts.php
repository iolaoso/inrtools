<!-- ========================================================= -->
<!-- VARIABLES GLOBALES DEL SISTEMA -->
<!-- ========================================================= -->
<script>
    // Información de la sesión del usuario
    const currentPage = '<?php echo $current_page; ?>';
    const baseurl = '<?php echo $base_url; ?>';
    const nickname = '<?php echo $nickname; ?>';
    const usrDir = '<?php echo $direccion; ?>';
    const usrRol = '<?php echo $rol_nombre; ?>';
    const usrName = '<?php echo $nickname; ?>';
</script>

<!-- ========================================================= -->
<!-- JAVASCRIPT PROPIO DEL SISTEMA -->
<!-- ========================================================= -->

<!-- Menú lateral dinámico -->
<script src="<?php echo $base_url; ?>/assets/js/menuItems.js?v=<?= time(); ?>"></script>

<!-- Funciones del sidebar -->
<script src="<?php echo $base_url; ?>/assets/js/sidebarFunctions.js?v=<?= time(); ?>"></script>

<!-- Carga de combos y selects -->
<script src="<?php echo $base_url; ?>/assets/js/selectOptions.js?v=<?= time(); ?>"></script>

<!-- Exportación de tablas -->
<script src="<?php echo $base_url; ?>/assets/js/exportarTabla.js?v=<?= time(); ?>"></script>

<!-- Filtros personalizados para tablas -->
<script src="<?php echo $base_url; ?>/assets/js/filtrarTablas.js?v=<?= time(); ?>"></script>

<!-- Funciones relacionadas con entidades -->
<script src="<?php echo $base_url; ?>/assets/js/entidades.js?v=<?= time(); ?>"></script>

<!-- Funciones relacionadas con analistas -->
<script src="<?php echo $base_url; ?>/assets/js/analistas.js?v=<?= time(); ?>"></script>

<!-- Sistema de alertas -->
<script src="<?php echo $base_url; ?>/assets/js/alertas.js?v=<?= time(); ?>"></script>

<!-- Control de expiración de sesión -->
<script src="<?php echo $base_url; ?>/assets/js/session_timeout.js?v=<?= time(); ?>"></script>

<!-- ========================================================= -->
<!-- LIBRERÍAS EXTERNAS -->
<!-- ========================================================= -->

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- XLSX (Exportación Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<!-- ========================================================= -->
<!-- JQUERY -->
<!-- ========================================================= -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ========================================================= -->
<!-- DATATABLES -->
<!-- ========================================================= -->

<!-- Núcleo DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Integración visual Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- ========================================================= -->
<!-- EXTENSIONES DATATABLES -->
<!-- ========================================================= -->

<!-- Botones DataTables -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<!-- JSZip (Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- PDFMake (PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<!-- Exportación HTML5 -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<!-- Impresión -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- ========================================================= -->
<!-- ALERTAS -->
<!-- ========================================================= -->

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>