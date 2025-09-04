function exportTableToExcel(tableID, filename = '', sheetname = 'Datos') {
    // Obtener la tabla
    const table = document.getElementById(tableID);
    if (!table) {
        alert('No se encontró la tabla con el ID especificado');
        return;
    }
    
    // Crear un libro de trabajo y una hoja
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(table);
    
    // Añadir la hoja al libro
    XLSX.utils.book_append_sheet(wb, ws, sheetname);
    
    // Obtener el rango de datos
    const range = XLSX.utils.decode_range(ws['!ref']);
    const columnCount = range.e.c - range.s.c + 1;
    
    // Ajustar automáticamente el ancho de las columnas
    const colWidths = [];
    for (let c = range.s.c; c <= range.e.c; c++) {
        let maxLength = 0;
        for (let r = range.s.r; r <= range.e.r; r++) {
            const cell = XLSX.utils.encode_cell({ r, c });
            if (ws[cell] && ws[cell].v) {
                const cellLength = ws[cell].v.toString().length;
                if (cellLength > maxLength) maxLength = cellLength;
            }
        }
        // Ajustar el ancho basado en el contenido (mínimo 10, máximo 50)
        colWidths.push({ wch: Math.min(Math.max(maxLength + 2, 10), 50) });
    }
    ws['!cols'] = colWidths;
    
    // Agregar autofiltro (esto funciona en SheetJS)
    ws['!autofilter'] = {
        ref: XLSX.utils.encode_range(range)
    };
    
    // Guardar el archivo
    XLSX.writeFile(wb, filename ? `${filename}.xlsx` : 'reporte.xlsx');
    
    // Mostrar notificación
    showNotification('¡Tabla exportada exitosamente con formato!');
}

// Función para mostrar notificación
function showNotification(message) {
    // Crear notificación si no existe
    let notification = document.getElementById('export-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'export-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #27ae60;
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: opacity 0.5s;
        `;
        document.body.appendChild(notification);
    }
    
    notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    notification.style.display = 'block';
    notification.style.opacity = '1';
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 500);
    }, 3000);
}