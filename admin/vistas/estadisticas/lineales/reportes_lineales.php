<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- Estilos -->
<style>
.dashboard-container {
    max-width: 1100px;
    margin: 0 auto;
}

.chart-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 20px;
    transition: transform 0.2s;
}

.chart-card:hover {
    transform: translateY(-2px);
}

h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 10px;
    font-weight: 600;
}

select {
    display: block;             /* lo hace bloque para centrarlo */
    margin: 15px auto 25px auto; /* centrado horizontalmente */
    padding: 10px 16px;         /* un poco más grande */
    font-size: 16px;            /* letra más grande */
    font-weight: 600;           /* más visible */
    color: #000000ff;                /* letra oscura */
    border-radius: 8px;
    border: 1px solid #ced4da;
    background: #f8f9fa;
    transition: all 0.3s;
}

select:hover {
    border-color: #007bff;
    background: #e9f3ff;
}


#container {
    width: 100%;
    height: 500px;
    margin-bottom: 20px;
}

.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    padding: 20px;
}

.table-container h3 {
    color: #007bff;
    font-weight: 600;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 14px;
}

table thead {
    background: #007bff;
    color: white;
}

table th, table td {
    padding: 10px;
    border-bottom: 1px solid #dee2e6;
    text-align: left;
}

.no-data {
    text-align: center;
    color: #6c757d;
    font-style: italic;
    margin-top: 20px;
}
</style>


<section class="content">
    <div class="card shadow">
        <div class="card-body">
            <div class="dashboard-container">
                <div class="chart-card">
                    <h2>📈 Reportes Lineales Dinámicos</h2>

 <select id="tipo_reporte" class="reporte-select">
  <option value="" selected>-----Seleccionar estadística------</option>

        <!-- 📦 PRODUCTOS -->
        <optgroup label="📦 Productos">
            <option value="productos">Top 5 Productos Más Vendidos</option>
        </optgroup>

        <!-- 👥 CLIENTES -->
        <optgroup label="👥 Clientes">
            <option value="clientes_mensual">Top 5 Clientes con Más Compras por Mes</option>
            <option value="clientes">Top 5 Clientes con Más Compras</option>
        </optgroup>

        <!-- 👔 EMPLEADOS -->
        <optgroup label="👔 Empleados">
            <option value="empleados_ingresos">Top 5 Empleados con Más Ventas</option>
        </optgroup>

        <!-- 🛒 VENTAS -->
        <optgroup label="📊 Ventas">
            <option value="mensual">Ventas Totales por Mes</option>
            <option value="ingresos">Ingresos Totales por Mes</option>
        </optgroup>
           </select>

                    <div id="container"></div>

                    <div class="table-container mt-4">
                        <h3 id="titulo-tabla" class="text-center text-primary">📋 Detalles del Reporte</h3>
                        <div id="tabla-detalle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/themes/sand-signika.js"></script>

<script>
(function(){
    function initReportesLineales() {
        const select = document.getElementById('tipo_reporte');
        const container = document.getElementById('container');
        const tablaDetalle = document.getElementById('tabla-detalle');
        const tituloTabla = document.getElementById('titulo-tabla');
let currentReport = ''; // vacío al inicio
        function cargarGrafico(tipo) {
            fetch('vistas/estadisticas/lineales/datos_reporte.php?tipo=' + encodeURIComponent(tipo))
                .then(res => res.json())
                .then(data => {
                    // ==== GRAFICO ====
                    Highcharts.chart('container', {
                        chart: { type: 'line', backgroundColor: '#fff', borderRadius: 10 },
                        title: { text: data.titulo },
                        xAxis: { categories: data.meses, title: { text: 'Periodo' } },
                        yAxis: { title: { text: data.etiquetaY }, allowDecimals: false },
                        tooltip: { shared: true },
                        legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' },
                        series: data.series,
                        plotOptions: {
                            line: {
                                dataLabels: { enabled: true },
                                enableMouseTracking: true
                            }
                        }
                    });

                    // ==== TABLA DETALLE ====
                    tituloTabla.textContent = "📋 " + data.titulo;

                    if (data.detalle && data.detalle.length > 0) {
                        let headers = Object.keys(data.detalle[0]);
                        let html = "<table class='table table-striped table-bordered'><thead><tr>";
                        headers.forEach(h => html += `<th>${h}</th>`);
                        html += "</tr></thead><tbody>";
                        data.detalle.forEach(row => {
                            html += "<tr>";
                            headers.forEach(h => html += `<td>${row[h]}</td>`);
                            html += "</tr>";
                        });
                        html += "</tbody></table>";
                        tablaDetalle.innerHTML = html;
                    } else {
                        tablaDetalle.innerHTML = '<p class="no-data">No hay datos disponibles para este reporte.</p>';
                    }
                });
        }

        cargarGrafico(''); // Carga inicial
        select.addEventListener('change', e => cargarGrafico(e.target.value));
    }

    document.addEventListener('DOMContentLoaded', initReportesLineales);
    setTimeout(initReportesLineales, 500); // fallback si AdminLTE tarda en montar el DOM
})();
</script>
