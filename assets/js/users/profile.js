document.getElementById('formChangePwd').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar la recarga de la página
    
    const formData = new FormData(this); // Crear FormData directamente desde el formulario
    const url = baseurl + '/backend/users/changePwd.php';

    // Usar fetch para enviar la solicitud
    fetch(url, {
        method: 'POST',
        body: formData, // Enviar formData directamente
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json(); // Parsear la respuesta como JSON
    })
    .then(data => {
        document.getElementById('responseMessage').innerText = data.message; // Mostrar el mensaje de éxito
        document.getElementById('currentPassword').value ="";
        document.getElementById('newPassword').value ="";
    })
    .catch(error => {
        document.getElementById('responseMessage').innerText = error.message; // Mostrar mensaje de error
        document.getElementById('currentPassword').value ="";
        document.getElementById('newPassword').value ="";
    });
});