console.log("empAuxReportes.js Funcionando");

document.addEventListener('DOMContentLoaded', function () {

    /**
     * ============================================
     * GENERAR RIL
     * ============================================
     */
    const btnGenerarRIL = document.getElementById('btnGenerarRIL');

    if (btnGenerarRIL) {

        btnGenerarRIL.addEventListener('click', function () {

            Swal.fire({
                title: 'Generando reporte',
                text: 'Por favor espere...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const iframe = document.createElement('iframe');

            iframe.style.display = 'none';

            iframe.src =
                `${baseurl}/backend/empAux/generarRIL.php`;

            document.body.appendChild(iframe);

            setTimeout(() => {
                Swal.close();
            }, 3000);

        });

    }

    /**
     * ============================================
     * DESCARGAR ANEXO 2
     * ============================================
     */
    const btnDescargarAnexo2 =
        document.getElementById('btnDescargarAnexo2');

    if (btnDescargarAnexo2) {

        btnDescargarAnexo2.addEventListener('click', function () {

            Swal.fire({
                title: 'Descargando archivo',
                text: 'Por favor espere...',
                timer: 1500,
                showConfirmButton: false,
                icon: 'info'
            });

            window.location.href =
                `${baseurl}/backend/empAux/descargarAnexo2.php`;

        });

    }

});