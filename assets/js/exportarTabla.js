function exportTableToExcel(tableID, filename = '') {
    const tableSelect = document.getElementById(tableID);
    const workbook = XLSX.utils.table_to_book(tableSelect, { sheet: "Sheet1" });
    const wbout = XLSX.writeFile(workbook, filename ? filename + '.xlsx' : 'reporte.xlsx');
}