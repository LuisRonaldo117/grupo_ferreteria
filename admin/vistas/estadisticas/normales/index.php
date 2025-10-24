<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
:root {
    --bg: #eef2f9;
    --card: #fff;
    --accent: #2b7be4;
    --accent2: #5bc0de;
    --text-dark: #222;
    --text-light: #555;
    --shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    --border: #e0e6f0;
}

/* Contenedor principal del tablero */
.dashboard-wrapper {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 15px;
    box-sizing: border-box;
}

/* Tarjeta que contiene todo: título, controles, gráfico y resumen */
.dashboard-card {
    background: var(--card);
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 20px;
    display: flex;
    flex-direction: column;
    min-height: 600px; /* altura mínima total */
}

/* Título */
.dashboard-card h2 {
    text-align: center;
    color: var(--accent);
    margin-bottom: 20px;
    font-weight: 600;
}

/* Controles de selección y tipo de gráfico */
.controls {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 20px;
}

.controls label {
    font-weight: bold;
    color: var(--text-dark);
}

.controls select {
    display: block;            /* lo hace un bloque centrable */
    margin: 0 auto;            /* centra horizontalmente */
    padding: 10px 16px;        /* más grande para comodidad */
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--card);
    font-weight: 600;           /* un poco más gruesa */
    font-size: 16px;            /* letra más grande */
    color: var(--text-dark);    /* color oscuro */
    transition: all 0.3s;
}

.controls select:hover {
    border-color: var(--accent);
    background: #f1f4fa;       /* ligero cambio al pasar mouse */
}

.chart-type button {
    background: #f1f4fa;
    border: none;
    padding: 8px 14px;
    margin-left: 5px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-dark);
    transition: all 0.3s;
}

.chart-type button:hover {
    background: var(--accent);
    color: #fff;
}

.chart-type button.active {
    background: var(--accent);
    color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

/* Contenedor del gráfico + resumen */
.chart-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    flex: 1; /* para que ocupe todo el espacio disponible */
}

/* Área del gráfico */
#chartArea {
    width: 100%;
    flex: 1;
    min-height: 450px;
    border-radius: 12px;
    background: transparent;
    overflow: visible !important;
}

/* Resumen debajo del gráfico */
.summary {
    margin-top: 20px;
    background: var(--card);
    border: 1px solid var(--border);
    padding: 12px 18px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(12, 24, 48, 0.06);
    font-size: 14px;
    flex-shrink: 0;
}

.summary .item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed var(--border);
    color: var(--text-light);
}

.summary .item strong {
    color: var(--text-dark);
}

.summary .item:last-child {
    border-bottom: none;
    font-weight: 600;
    color: var(--accent);
}

/* Oculta logo AmCharts */
.amcharts-logo {
    display: none !important;
}

/* === Responsive === */
@media (max-width: 768px) {
    .controls {
        flex-direction: column;
        gap: 10px;
    }

    .controls label, .controls select {
        width: 100%;
    }

    #chartArea {
        min-height: 300px;
    }
}

@media (max-width: 480px) {
    .dashboard-card h2 {
        font-size: 18px;
    }

    .chart-type button {
        font-size: 13px;
        padding: 6px 10px;
    }

    #chartArea {
        min-height: 250px;
    }
}
</style>

<section class="content">
    <div class="dashboard-wrapper">
        <div class="dashboard-card">
            <h2>📊 Tablero de Reportes</h2>

            <div class="controls">
                <label>
                    Reporte:
<select id="reporteSelect">
    <option value="" selected>-----Seleccionar la consulta-----</option>

    <!-- 🛒 VENTAS -->
    <optgroup label="📊 Ventas">
        <option value="venta">Tipo de Venta</option>
        <option value="ingresos_mes">Ingresos por Mes</option>
        <option value="ventas_cliente">Ventas por Cliente</option>
        <option value="ventas_empleado">Ventas por Empleado</option>
        <option value="metodo_pago">Ventas por Método de Pago</option>
    </optgroup>

    <!-- 👥 CLIENTES -->
    <optgroup label="👥 Clientes">
        <option value="clientes">Clientes por Departamento</option>
        <option value="genero">Clientes por Género</option>
    </optgroup>

    <!-- 📦 PRODUCTOS -->
    <optgroup label="📦 Productos">
        <option value="productos">Productos más Vendidos</option>
        <option value="productos_categoria">Productos por Categoría</option>
        <option value="productos_stok">Productos con poco Stokc</option>
    </optgroup>

    <!-- 🧾 RECLAMOS -->
    <optgroup label="🧾 Reclamos">
        <option value="reclamos">Reclamos por Cliente</option>
        <option value="reclamos_mes">Reclamos por Mes</option>
    </optgroup>

    <!-- 👔 EMPLEADOS -->
    <optgroup label="👔 Empleados">
        <option value="cargo">Cargos de Empleados</option>
        <option value="empleado_sucursal">Empleados por Sucursal</option>
    </optgroup>

    <!-- 🚚 PEDIDOS -->
    <optgroup label="🚚 Pedidos">
        <option value="pedido_estado">Pedidos por Estado</option>
    </optgroup>

    <!-- 🏭 PROVEEDORES -->
    <optgroup label="🏭 Proveedores">
        <option value="proveedor_mas_productos">Proveedores con productos comprados</option>
    </optgroup>
</select>



                </label>

                <div class="chart-type">
                    <button id="btnPie" class="active" data-type="pie">Gráfico Pastel</button>
                    <button id="btnBar" data-type="bar">Gráfico Barra</button>
                </div>
            </div>

            <div class="chart-container">
                <div id="chartArea"></div>
                <div class="summary" id="summaryArea"></div>
            </div>
        </div>
    </div>
</section>


<!-- AmCharts -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
(function () {
    let rootCurrent = null;
    let currentType = 'pie';
    let currentReport = 'venta';
    let isInitialized = false;

    function initGrafico() {
        if (isInitialized) return;
        isInitialized = true;

        const reporteSelect = document.getElementById('reporteSelect');
        const btnPie = document.getElementById('btnPie');
        const btnBar = document.getElementById('btnBar');
        const summaryArea = document.getElementById('summaryArea');

        reporteSelect.addEventListener('change', () => {
            currentReport = reporteSelect.value;
            cargarDatos(currentReport);
        });

        btnPie.addEventListener('click', () => setChartType('pie'));
        btnBar.addEventListener('click', () => setChartType('bar'));

        function setChartType(type) {
            currentType = type;
            btnPie.classList.toggle('active', type === 'pie');
            btnBar.classList.toggle('active', type === 'bar');
            cargarDatos(currentReport);
        }
let currentReport = ''; // vacío al inicio
        function cargarDatos(reporte) {
            fetch('vistas/estadisticas/normales/data.php?reporte=' + encodeURIComponent(reporte))
                .then(r => r.json())
                .then(data => {
                    renderChart(data || []);
                    renderSummary(data || []);
                })
                .catch(err => {
                    console.error('Error al cargar datos:', err);
                    renderChart([]);
                    renderSummary([]);
                });
        }

        function renderSummary(data) {
            summaryArea.innerHTML = '';
            if (!data || data.length === 0) return;
            let total = data.reduce((s, it) => s + (parseFloat(it.value) || 0), 0);

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'item';
                const pct = total ? ((item.value / total) * 100).toFixed(2) : '0.00';
                div.innerHTML = `<strong>${escapeHtml(item.category)}</strong><div>${item.value} (${pct}%)</div>`;
                summaryArea.appendChild(div);
            });

            const divT = document.createElement('div');
            divT.className = 'item';
            divT.innerHTML = `<strong>Total:</strong><div>${total}</div>`;
            summaryArea.appendChild(divT);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>"']/g, m => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[m]));
        }

        // =====================
        // GESTIÓN DE GRÁFICOS
        // =====================
        function renderChart(data) {
            try {
                if (rootCurrent) {
                    rootCurrent.dispose();
                    rootCurrent = null;
                }
            } catch (e) {
                console.warn('Error dispose:', e);
            }

            const chartDiv = document.getElementById('chartArea');
            if (chartDiv) chartDiv.innerHTML = '';

            rootCurrent = am5.Root.new('chartArea');
            rootCurrent.setThemes([am5themes_Animated.new(rootCurrent)]);

            if (currentType === 'pie') {
                createPie(rootCurrent, data);
            } else {
                setTimeout(() => createBar(rootCurrent, data), 50);
            }
        }

        // =====================
        // GRÁFICO DE TORTA
        // =====================
 function createPie(root, data){
    const chart = root.container.children.push(
        am5percent.PieChart.new(root, { endAngle: 270 })
    );

    const series = chart.series.push(
        am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category",
            endAngle: 270
        })
    );

    series.slices.template.setAll({
        stroke: am5.color(0xffffff),
        strokeWidth: 2,
        strokeOpacity: 1
    });

    series.states.create("hidden", { endAngle: -90 });

    // set data
    series.data.setAll(data);
    series.appear(1000, 100);
}

        // =====================
        // GRÁFICO DE BARRAS
        // =====================
        function createBar(root, data) {
            const chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft: 0,
                paddingRight: 5
            }));

            const cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

            const xRenderer = am5xy.AxisRendererX.new(root, {
                minGridDistance: 30,
                minorGridEnabled: true
            });

            xRenderer.labels.template.setAll({
                rotation: -60,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 10,
                fontSize: window.innerWidth < 768 ? 10 : 12
            });

            const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                maxDeviation: 0.3,
                categoryField: "category",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));

            const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0.3,
                renderer: am5xy.AxisRendererY.new(root, { strokeOpacity: 0.1 })
            }));

            const series = chart.series.push(am5xy.ColumnSeries.new(root, {
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                sequencedInterpolation: true,
                categoryXField: "category",
                tooltip: am5.Tooltip.new(root, { labelText: "{category}: {valueY}" })
            }));

            series.columns.template.setAll({
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                strokeOpacity: 0,
                tooltipText: "{category}: {valueY}"
            });

            series.columns.template.adapters.add("fill", (fill, target) =>
                chart.get("colors").getIndex(series.columns.indexOf(target))
            );

            xAxis.data.setAll(data);
            series.data.setAll(data);
            series.appear(1000);
            chart.appear(1000, 100);
        }

        // Cargar el gráfico inicial
        cargarDatos(currentReport);
    }

    document.addEventListener("DOMContentLoaded", initGrafico);
    setTimeout(initGrafico, 500);
})();

window.addEventListener('resize', () => {
    if (rootCurrent) rootCurrent.resize();
});
</script>

