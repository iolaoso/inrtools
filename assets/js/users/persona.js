document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formPersona');
    //console.log(form);
    // Asegúrate de que baseurl esté definido
    const baseurl = document.getElementById('base_url').getAttribute('data-base-url');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(form);
        const url = baseurl + "/backend/users/addPerson.php";
        
        // Mostrar el contenido de formData en la consola
        //for (let [key, value] of formData.entries()) {
        //    console.log(key, value);
        //}

        // Realizar la llamada AJAX
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Limpiar alertas anteriores
            const existingAlert = form.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alertContainer = document.createElement('div');
            alertContainer.className = 'alert';

            if (data.success) {
                alertContainer.classList.add('alert-success');
                alertContainer.textContent = 'Persona agregada exitosamente.';
                form.reset(); // Reiniciar el formulario
            } else {
                alertContainer.classList.add('alert-danger');
                alertContainer.textContent = 'Error: ' + (data.error || 'Ocurrió un error desconocido.'); // Mostrar error específico
            }

            form.prepend(alertContainer); // Mostrar el mensaje en el formulario
        })
        .catch(() => {
            // Limpiar alertas anteriores
            const existingAlert = form.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alertContainer = document.createElement('div');
            alertContainer.className = 'alert alert-danger';
            alertContainer.textContent = 'Error en la conexión. Intenta de nuevo.'  + (data.error || 'Ocurrió un error desconocido.');
            form.prepend(alertContainer); // Mostrar el mensaje en el formulario
        });
    });
});