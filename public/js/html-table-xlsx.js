function exportTableToXLSX(tableId) {
    let table = document.getElementById(tableId);
    if (!table) {
    console.error('Table with ID ' + tableId + ' not found');
    return;
}
    let tableHTML = table.outerHTML;

    fetch('http://phptools.dynamicpsl.com/php_html_2_xlsx/index.php', {
    method: 'POST',
    headers: {
    'Content-Type': 'application/json'
},
    body: JSON.stringify({ html: tableHTML })
})
    .then(response => response.blob())
    .then(blob => {
    let url = window.URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'table.xlsx';
    document.body.appendChild(a);
    a.click();
    a.remove();
})
    .catch(error => console.error('Error:', error));
}
