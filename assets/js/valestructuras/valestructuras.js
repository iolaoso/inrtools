console.log("valestructuras.js Funcionando");

const form = document.getElementById('formEstructuras');
const loadingIndicator = document.getElementById('loading');
const tableBody = document.getElementById('resultBody');
const inputRuc = document.getElementById('ruc');
const url = `${baseurl}/backend/valestructuras/listValestructuras.php`;

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    loadingIndicator.style.display = 'block';
    tableBody.innerHTML = '';

    try {
        const formData = Object.fromEntries(new FormData(form));
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Error en la respuesta.");
        }

        console.log('Consulta Exitosa', data);

         // Convierte el objeto en array para poder iterar
        const datosArray = Object.values(data.datos);

        datosArray.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.RUC_ENTIDAD || ''}</td>
                <td>${item.RAZON_SOCIAL || ''}</td>
                <td>${item.SEGMENTO || ''}</td>
                <td>${item.NVL_RIESGO || ''}</td>
                <td>${item.ESTRUCTURA || ''}</td>
                <td>${item.NOM_ESTRUCTURA || ''}</td>
                <td class='text-center ${item.CUMPLE === 'CUMPLE' ? 'cumple' : 
                 item.CUMPLE === 'INCUMPLIDO' ? 'incumplido' : 
                 item.CUMPLE === 'A TIEMPO' ? 'a-tiempo' : 
                 item.CUMPLE === 'NO APLICA' ? 'no-aplica' : ''}'>
                    ${item.CUMPLE || ''}
                </td>
                <td>${item.MAX_FECHA_CORTE || ''}</td>
                <td>${item.MAX_FECHA_VALIDACION || ''}</td>
            `;
            tableBody.appendChild(row);
        });

        inputRuc.value = '';
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingIndicator.style.display = 'none';
    }
});