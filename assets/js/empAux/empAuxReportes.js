console.log("empAuxReportes.js Funcionando");

document.addEventListener('DOMContentLoaded', function () {
    const btnGenerarRIL = document.getElementById('btnGenerarRIL');
    // Generar Reporte 
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
                baseurl + '/backend/empAux/generarRIL.php';
            document.body.appendChild(iframe);
            setTimeout(() => {
                Swal.close();
            }, 3000);
        });
    }
});

