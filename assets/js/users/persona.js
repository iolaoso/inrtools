document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formPersona');
    const baseurl = document.getElementById('base_url').getAttribute('data-base-url');
    
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch(`${baseurl}/backend/users/addPersona.php`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                mostrarAlerta(
                    data.message /* || 'Persona guardada exitosamente.' */,
                    'success'
                );
                limpiarFormularioPersona();
            } else {
                mostrarAlerta(
                    data.error || 'Ocurrió un error.',
                    'danger'
                );
            }
        })
        .catch(error => {
            console.error(error);
            mostrarAlerta(
                'Error en la conexión. Intente nuevamente.',
                'danger'
            );
        });
    });
});

/**
 * Mostrar alertas en el formulario
 */
function mostrarAlerta(mensaje, tipo) {

    const form = document.getElementById('formPersona');

    const alertaAnterior = form.querySelector('.alert');

    if (alertaAnterior) {
        alertaAnterior.remove();
    }

    const alertContainer = document.createElement('div');

    alertContainer.className = `alert alert-${tipo}`;
    alertContainer.textContent = mensaje;

    form.prepend(alertContainer);
}

/**
 * Limpiar formulario y volver a modo inserción
 */
function limpiarFormularioPersona(delay = 2000) {
    setTimeout(() => {
        const form = document.getElementById('formPersona');
        form.reset();
        const idPersona = document.getElementById('idPersona');
        if (idPersona) {
            idPersona.value = '';
        }
        const btnGuardar = document.getElementById('btnGuardarPersona');
        if (btnGuardar) {
            btnGuardar.textContent = 'Guardar Registro';
            btnGuardar.classList.remove('btn-warning');
            btnGuardar.classList.add('btn-primary');
        }
        const alertaAnterior = form.querySelector('.alert');
        if (alertaAnterior) {
            alertaAnterior.remove();
        }
    }, delay);
}

/**
 * consultar Persona
 */
async function consultarPersona(idPersona) {
    const baseurl = document.getElementById('base_url').getAttribute('data-base-url');
    try {
        const response = await fetch(`${baseurl}/backend/users/getPersona.php?idPersona=${idPersona}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${encodeURIComponent(idPersona)}`
            }
        );
        const result = await response.json();
        //console.log(result.data);
        
        // llenamos el form
        document.getElementById('idPersona').value          = result.data.id;
        document.getElementById('identificacion').value     = result.data.identificacion;
        document.getElementById('nombre').value             = result.data.nombre;
        document.getElementById('email').value              = result.data.email;
        document.getElementById('inrdireccion_id').value    = result.data.inrdireccion_id;

        // modificamos el boton 
        const btnGuardar = document.getElementById('btnGuardarPersona');
        btnGuardar.classList.remove('btn-primary');
        btnGuardar.classList.add('btn-warning');
        btnGuardar.textContent = 'Actualizar Registro';

        /* document.getElementById('formPersona').scrollIntoView({behavior: 'smooth',block: 'start'}); */
        return result;
    } catch (error) {
        console.error('Error al consultar persona:', error);
        return {
            success: false,
            message: 'Error al consultar la persona.'
        };
    }
}


/**
 * Eliminar Persona
 */
async function delPersona(delPersonaId) {

    const baseurl = document
        .getElementById('base_url')
        .getAttribute('data-base-url');

    const confirmacion = await Swal.fire({
        title: 'Desactivar Persona',
        text: '¿Está seguro de desactivar este registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    });

    if (!confirmacion.isConfirmed) {
        return;
    }

    try {

        Swal.fire({
            title: 'Procesando...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch(
            `${baseurl}/backend/users/delPersona.php?delPersonaId=${encodeURIComponent(delPersonaId)}`
        );

        const result = await response.json();

        Swal.close();

        if (result.success) {

            Swal.fire({
                icon: 'success',
                title: 'Proceso exitoso',
                text: result.message || 'Persona desactivada correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            setTimeout(() => {
                location.reload();
            }, 2000);

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.error || 'No se pudo desactivar la persona.'
            });

        }

    } catch (error) {

        console.error(error);

        Swal.close();

        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'Ocurrió un error al procesar la solicitud.'
        });

    }
}