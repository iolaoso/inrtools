console.log("valestructuras.js Funcionando");

document.getElementById('formEstructuras').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(document.getElementById('formEstructuras'));
    
    const url = `${baseurl}/backend/valestructuras/listValestructuras.php`;
    
    // Mostrar el indicador de carga
    const loadingIndicator = document.getElementById('loading');
    loadingIndicator.style.display = 'block';

    // hacer la peticion HTTP 
    fetch(url, {
        method: 'POST',
        body: JSON.stringify(Object.fromEntries(formData)) // Enviar el formulario como JSON
    })
    .then(response => {
       if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }
        return response.json(); // Convertir la respuesta a JSON
    })
    .then(data => {
        // Manejar la respuesta aquí
        if (data.success) {
            console.log('Consulta Exitosa');
            const tableBody = document.getElementById('resultBody');
            tableBody.innerHTML = ''; // Limpiar la tabla antes de llenarla
            console.log("Datos a mostrar:", data.datos);
            // Iterar sobre los datos y crear filas de tabla
            data.datos.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.NUM_RUC || ''}</td>
                    <td>${item.FECHA_CORTE || ''}</td>
                    <td>otro dato</td>`;
                tableBody.appendChild(row);
            }); 
            
            // Ocultar el indicador de carga
            loadingIndicator.style.display = 'none';
        } else {
            throw new Error(data.message || "Error en la respuesta.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});