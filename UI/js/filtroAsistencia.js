// components/filtroAsistencias.js
class FiltroAsistencias {
    constructor(tableId) {
        this.tableId = tableId;
        this.table = document.getElementById(tableId);
        this.rows = this.table.querySelectorAll('tbody tr');
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Evento para el botón filtrar
        document.getElementById('btnFiltrar').addEventListener('click', () => {
            this.filtrar();
        });

        // Evento para el botón limpiar
        document.getElementById('btnLimpiar').addEventListener('click', () => {
            this.limpiarFiltros();
        });

        // Evento para Enter en los inputs
        document.getElementById('fechaDesde').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.filtrar();
        });
        document.getElementById('fechaHasta').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.filtrar();
        });
    }

    filtrar() {
        const fechaDesde = document.getElementById('fechaDesde').value;
        const fechaHasta = document.getElementById('fechaHasta').value;
        const tipoClase = document.getElementById('tipoClase').value;

        this.rows.forEach(row => {
            let mostrarFila = true;
            const fechaRow = row.getAttribute('data-fecha');
            const tipoClaseRow = row.cells[1].textContent.trim();

            // Filtro por fecha
            if (fechaDesde && fechaRow < fechaDesde) {
                mostrarFila = false;
            }
            if (fechaHasta && fechaRow > fechaHasta) {
                mostrarFila = false;
            }

            // Filtro por tipo de clase
            if (tipoClase && tipoClaseRow !== tipoClase) {
                mostrarFila = false;
            }

            // Mostrar/ocultar fila
            row.style.display = mostrarFila ? '' : 'none';
        });

        this.actualizarContador();
    }

    limpiarFiltros() {
        // Limpiar inputs
        document.getElementById('fechaDesde').value = '';
        document.getElementById('fechaHasta').value = '';
        document.getElementById('tipoClase').value = '';

        // Mostrar todas las filas
        this.rows.forEach(row => {
            row.style.display = '';
        });

        this.actualizarContador();
    }

    actualizarContador() {
        const filasVisibles = Array.from(this.rows).filter(row => 
            row.style.display !== 'none'
        ).length;
        
        console.log(`Mostrando ${filasVisibles} de ${this.rows.length} registros`);
        
        // Opcional: puedes mostrar el contador en la interfaz
        const contador = document.getElementById('contadorRegistros');
        if (!contador) {
            const nuevoContador = document.createElement('div');
            nuevoContador.id = 'contadorRegistros';
            nuevoContador.className = 'text-muted small mt-2';
            this.table.parentNode.insertBefore(nuevoContador, this.table.nextSibling);
        }
        document.getElementById('contadorRegistros').textContent = 
            `Mostrando ${filasVisibles} de ${this.rows.length} registros`;
    }
}

// Función para inicializar todos los filtros en la página
function inicializarFiltrosAsistencias() {
    const tables = document.querySelectorAll('table[id^="InformeIndividual"]');
    tables.forEach(table => {
        new FiltroAsistencias(table.id);
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    inicializarFiltrosAsistencias();
});