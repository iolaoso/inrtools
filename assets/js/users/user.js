console.log("user.js Funcionando");

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addUserForm');
    const baseurl = document.getElementById('base_url').getAttribute('data-base-url');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(form);
        const url = `${baseurl}/backend/users/addUser.php`; // Usar template literals para mayor claridad

        // Realizar la llamada AJAX
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            // Limpiar alertas anteriores
            clearAlerts(form);

            const alertContainer = createAlertContainer(data);
            form.prepend(alertContainer); // Mostrar el mensaje en el formulario

            if (data.success) {
                form.reset(); // Reiniciar el formulario
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error en la conexión:', error);
            clearAlerts(form);
            const alertContainer = createErrorAlert('Error en la conexión. Intenta de nuevo.');
            form.prepend(alertContainer); // Mostrar el mensaje en el formulario
        });
    });
});

// Función para limpiar alertas anteriores
function clearAlerts(form) {
    const existingAlert = form.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
}

// Función para crear un contenedor de alerta
function createAlertContainer(data) {
    const alertContainer = document.createElement('div');
    alertContainer.className = 'alert';

    if (data.success) {
        alertContainer.classList.add('alert-success');
        alertContainer.textContent = 'Usuario agregado exitosamente.';
    } else {
        alertContainer.classList.add('alert-danger');
        alertContainer.textContent = 'Error: ' + (data.error || 'Ocurrió un error desconocido.');
    }

    return alertContainer;
}

// Función para crear una alerta de error
function createErrorAlert(message) {
    const alertContainer = document.createElement('div');
    alertContainer.className = 'alert alert-danger';
    alertContainer.textContent = message;
    return alertContainer;
}