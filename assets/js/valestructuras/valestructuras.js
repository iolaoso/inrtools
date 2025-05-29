console.log("valestructuras.js Funcionando");

document.getElementById('formEstructuras').addEventListener('submit', function(event) {
    event.preventDefault();
    const ruc = document.getElementById('ruc').value;

    const url = `${baseurl}/backend/valestructuras/listValestructuras.php`; // Asegúrate de definir baseurl
    console.log(url);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ ruc: ruc })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const resultBody = document.getElementById('resultBody');
        resultBody.innerHTML = ''; // Limpiar la tabla

        if (data.error) {
            console.error('Error:', data.error);
            return;
        }

        console.log({data});
        //data.data.forEach(item => { // Asegúrate de acceder a data.data
            // const row = document.createElement('tr');
            // row.innerHTML = `
            //     <td>${item.cod_estructura || 'N/A'}</td>
            //     <td>${item.nombre_estructura || 'N/A'}</td>
            //     <td>${item.fecha_corte || 'N/A'}</td>
            // `;
            // resultBody.appendChild(row);
        //});
    })
    .catch(error => console.error('Error:', error.message));
});