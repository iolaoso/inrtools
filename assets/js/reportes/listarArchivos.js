console.log("listarArchivos.js funcionando");

////archivo para listar los reportes 
function fetchReports(carpetaReportes) {
    const url = `${baseurl}/backend/reportes/listFilesReportes.php?carpeta=${encodeURIComponent(carpetaReportes)}`; // Asegúrate de definir baseurl
    console.log(url);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('reportTableBody');
            tbody.innerHTML = ''; // Limpiar el cuerpo de la tabla
            
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-center">${item.monthId}</td>
                    <td class="text-center">${item.year}</td>
                    <td class="text-center">${item.month.toUpperCase()}</td>
                    <td>${item.file}</td>
                    <td class="text-center">
                        <a href="${baseurl}/${item.repPath}/${item.file}" class="btn btn-primary btn-sm" target="_blank">Descargar</a>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching reports:', error));
}


document.addEventListener("DOMContentLoaded", function() {
    fetchReports(carpetaReportes); // Llama a la función con la carpeta como parámetro
});
