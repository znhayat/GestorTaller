/**
 * table-to-csv.js
 * Utilitzarià genèrica pel Zana Taller.
 * Exporta taules HTML a CSV compatibles amb MS Excel.
 */
function downloadCSV(csv, filename) {
    let csvFile;
    let downloadLink;

    // Fix pel suport Unicode UTF-8 a MS Excel
    let BOM = "\uFEFF"; 
    
    // Creem el blob per la descarrega
    csvFile = new Blob([BOM + csv], {type: "text/csv;charset=utf-8;"});

    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

function exportTableToCSV(tableSelector, filename) {
    let csv = [];
    let table = document.querySelector(tableSelector);
    if (!table) return;

    let rows = table.querySelectorAll("tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (let j = 0; j < cols.length; j++) {
            // Netejem el text i mirem si hem d'escapar comes per l'estructura CSV
            let cellText = cols[j].innerText.trim();
            // Ignorem cel·les d'accions normals
            if (cellText.toLowerCase() === 'acciones' || cellText.includes('Editar Eliminar')) {
                continue; 
            }
            // Escapem dades per CSV segur
            let data = cellText.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        // No exportar files buides
        if(row.length > 0 && row.join("").replace(/"/g, "").trim() !== "") {
            csv.push(row.join(","));
        }
    }

    // Fem la descàrrega
    downloadCSV(csv.join("\n"), filename);
}

// Inicialitzador genèric, s'aplica on vegi el botó .btn-export-csv
document.addEventListener('DOMContentLoaded', function() {
    let exportBtns = document.querySelectorAll('.btn-export-csv');
    exportBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let tableSelector = this.getAttribute('data-table') || '.table';
            let fileName = (this.getAttribute('data-filename') || 'exportacion-zana') + '.csv';
            exportTableToCSV(tableSelector, fileName);
        });
    });
});
