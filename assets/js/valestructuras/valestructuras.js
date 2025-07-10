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
            //console.log("Datos a mostrar:", data.datos);
            // Convertimos el objeto en un array de sus valores
            const datosObj = data.datos.datos; 
            const datosArray = Object.values(datosObj);
            console.log("Datos:", datosArray);
            //console.log(typeof datosArray); // Ver qué tipo es realmente
            //console.log(Array.isArray(datosArray)); // Confirmar si es array
            // Iterar sobre los datos y crear filas de tabla
            const tableBody = document.getElementById('resultBody');
            tableBody.innerHTML = ''; // Limpiar la tabla antes de llenarla
            datosArray.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.RUC_ENTIDAD || ''}</td>
                    <td>${item.RAZON_SOCIAL || ''}</td>
                    <td>${item.SEGMENTO || ''}</td>
                    <td>${item.NVL_RIESGO || ''}</td>
                    <td>${item.ESTRUCTURA || ''}</td>
                    <td>${item.NOM_ESTRUCTURA || ''}</td>
                    <td>${item.CUMPLE || ''}</td>
                    <td>${item.MAX_FECHA_CORTE || ''}</td>
                    <td>${item.FECHA_ENTREGA_ACTUAL || ''}</td>
                    <td>${item.MAX_FECHA_VALIDACION || ''}</td> `
                tableBody.appendChild(row);
                }); 
            // Ocultar el indicador de carga
            loadingIndicator.style.display = 'none';
            document.getElementById('ruc').values = ''; // Limpiar el campo RUC
        } else {
            throw new Error(data.message || "Error en la respuesta.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});