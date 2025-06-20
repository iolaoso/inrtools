console.log("valestructuras.js Funcionando");

document.getElementById('formEstructuras').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const dataForm = new FormData(this);
    const ruc = dataForm.get('ruc'); // Asegúrate de que el campo 'ruc' esté presente

    const url = `${baseurl}/backend/valestructuras/listValestructuras.php`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ruc }) // Enviar el RUC como JSON
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        // Manejar la respuesta aquí
        if (data.success) {
            // Aquí puedes manejar el caso de éxito, por ejemplo, mostrar un mensaje o redirigir
            alert('Validación exitosa');
            console.log(data);
            //llenar la tabla con los datos recibidos
            const tableBody = document.getElementById('resultBody');
            tableBody.innerHTML = ''; // Limpiar la tabla antes de llenarla
            // Asegúrate de que data.data sea un array
        if (Array.isArray(data.data)) {
            data.data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.cod_estructura || 'N/A'}</td>
                    <td>${item.nombre_estructura || 'N/A'}</td>
                    <td>${item.fecha_corte || 'N/A'}</td>`;
                resultBody.appendChild(row);
            });
        } else {
            throw new Error("La respuesta no contiene un array de datos.");
        }

        } else {
            // Manejar el caso de error
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});