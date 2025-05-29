function filterTable(idTabla) {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById(idTabla);
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) { // Comenzar desde 1 para omitir el encabezado
        let showRow = false;
        const td = tr[i].getElementsByTagName('td');
        
        for (let j = 0; j < td.length - 1; j++) { // Excluir la columna de acciones
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    showRow = true;
                    break; // Si hay un match, no es necesario seguir buscando en esta fila
                }
            }
        }
        tr[i].style.display = showRow ? "" : "none"; // Mostrar u ocultar la fila
    }
}