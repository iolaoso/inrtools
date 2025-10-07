/**
    * Función para exportar una tabla HTML a Excel con todas las celdas como texto
    * @param {string} tableID - ID de la tabla a exportar
    * @param {string} filename - Nombre del archivo (sin extensión)
    * @param {string} sheetname - Nombre de la hoja de cálculo
    */
function exportTableToExcel(tableID, filename = '', sheetname = 'Datos') {
    // Obtener la tabla
    const table = document.getElementById(tableID);
    if (!table) {
        alert('No se encontró la tabla con el ID especificado');
        return;
    }
    
    // Crear un libro de trabajo
    const wb = XLSX.utils.book_new();
    
    // Crear una hoja de cálculo vacía
    const ws = XLSX.utils.aoa_to_sheet([]);
    
    // Obtener todas las filas de la tabla
    const rows = table.querySelectorAll('tr');
    
    // Procesar cada fila y celda
    rows.forEach((row, rowIndex) => {
        const cells = row.querySelectorAll('th, td');
        cells.forEach((cell, colIndex) => {
            const cellRef = XLSX.utils.encode_cell({r: rowIndex, c: colIndex});
            
            // Crear objeto de celda con el valor como texto
            ws[cellRef] = {
                v: cell.textContent,
                t: 's', // 's' para string (texto)
                s: {
                    // Aplicar formato de texto explícito
                    numFmt: '@'
                }
            };
            
            /* // Aplicar formato especial a los encabezados
            if (rowIndex === 0) {
                ws[cellRef].s = {
                    ...ws[cellRef].s,
                    font: { bold: true, color: { rgb: "FFFFFF" } },
                    fill: { fgColor: { rgb: "3498DB" } },
                    border: {
                        top: { style: "thin", color: { rgb: "000000" } },
                        bottom: { style: "thin", color: { rgb: "000000" } },
                        left: { style: "thin", color: { rgb: "000000" } },
                        right: { style: "thin", color: { rgb: "000000" } }
                    }
                };
            } else {
                // Aplicar bordes a las celdas de datos
                ws[cellRef].s = {
                    ...ws[cellRef].s,
                    border: {
                        top: { style: "thin", color: { rgb: "D9D9D9" } },
                        bottom: { style: "thin", color: { rgb: "D9D9D9" } },
                        left: { style: "thin", color: { rgb: "D9D9D9" } },
                        right: { style: "thin", color: { rgb: "D9D9D9" } }
                    }
                };
                
                // Filas alternas con color de fondo
                if (rowIndex % 2 === 0) {
                    ws[cellRef].s.fill = { fgColor: { rgb: "F2F2F2" } };
                }
            } */
        });
    });
    
    // Definir el rango de la hoja
    const range = {s: {r: 0, c: 0}, e: {r: rows.length - 1, c: rows[0].cells.length - 1}};
    ws['!ref'] = XLSX.utils.encode_range(range);
    
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
    
    // Añadir la hoja al libro
    XLSX.utils.book_append_sheet(wb, ws, sheetname);
    
    // Guardar el archivo
    XLSX.writeFile(wb, filename ? `${filename}.xlsx` : 'reporte.xlsx');
    
    // Mostrar notificación
    showNotification('¡Tabla exportada exitosamente con formato de texto!');
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