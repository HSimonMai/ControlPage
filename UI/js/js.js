function exportToExcel() {
    const workbook = XLSX.utils.book_new();
    const table = document.querySelector('#mytable');

    if (!table) {
        console.error('No se pudo encontrar la tabla');
        return;
    }

    const data = [];
    const headers = [];

    // Obtener encabezados
    for (let i = 0; i < table.rows[0].cells.length; i++) {
        headers.push(table.rows[0].cells[i].innerText);
    }
    data.push(headers);

    // Obtener filas de datos
    for (let i = 1; i < table.rows.length; i++) {
        const rowData = [];
        for (let j = 0; j < table.rows[i].cells.length; j++) {
            rowData.push(table.rows[i].cells[j].innerText);
        }
        data.push(rowData);
    }

    const worksheet = XLSX.utils.aoa_to_sheet(data);
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Horarios');
    XLSX.writeFile(workbook, 'horarios.xlsx');
}
