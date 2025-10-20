function inicializarGraficos() {
    let rootCurrent = null;
    let currentType = 'pie';
    let currentReport = 'venta';

    const reporteSelect = document.getElementById('reporteSelect');
    const btnPie = document.getElementById('btnPie');
    const btnBar = document.getElementById('btnBar');
    const chartAreaId = 'chartArea';
    const summaryArea = document.getElementById('summaryArea');

    // Escuchar cambios de tipo o reporte
    reporteSelect.addEventListener('change', () => {
        currentReport = reporteSelect.value;
        cargarDatos(currentReport);
    });

    btnPie.addEventListener('click', () => setChartType('pie'));
    btnBar.addEventListener('click', () => setChartType('bar'));

    function setChartType(type){
        currentType = type;
        btnPie.classList.toggle('active', type === 'pie');
        btnBar.classList.toggle('active', type === 'bar');
        cargarDatos(currentReport);
    }

    // Cargar datos desde PHP
    function cargarDatos(reporte){
        fetch('/grupo_ferreteria/admin/vistas/estadisticas/normales/data.php?reporte=' + encodeURIComponent(reporte))
            .then(r => {
                if (!r.ok) throw new Error("Error HTTP: " + r.status);
                return r.json();
            })
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

    // Mostrar resumen
    function renderSummary(data){
        summaryArea.innerHTML = '';
        if(!data || data.length === 0) return;

        let total = data.reduce((s, it) => s + (parseFloat(it.value)||0), 0);
        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'item';
            const pct = total ? ((item.value/total)*100).toFixed(2) : '0.00';
            div.innerHTML = `<strong>${escapeHtml(item.category)}</strong><div>${item.value} (${pct}%)</div>`;
            summaryArea.appendChild(div);
        });
        const divT = document.createElement('div');
        divT.className = 'item';
        divT.innerHTML = `<strong>Total:</strong><div>${total}</div>`;
        summaryArea.appendChild(divT);
    }

    function escapeHtml(str){
        if(!str) return '';
        return str.replace(/[&<>"']/g, function(m){ 
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]; 
        });
    }

    // Renderizar gráfico
    function renderChart(data){
        try {
            if(rootCurrent){
                rootCurrent.dispose();
                rootCurrent = null;
            }
        } catch(e){ console.warn('Error dispose:', e); }

        rootCurrent = am5.Root.new(chartAreaId);
        rootCurrent.setThemes([ am5themes_Animated.new(rootCurrent) ]);

        if(currentType === 'pie') createPie(rootCurrent, data);
        else createBar(rootCurrent, data);
    }

    // Pie chart
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
        series.data.setAll(data);
        series.appear(1000, 100);
    }

    // Bar chart
    function createBar(root, data){
        const chart = root.container.children.push(
            am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true
            })
        );

        const xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
        const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            categoryField: "category",
            renderer: xRenderer
        }));
        const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {}));

        const series = chart.series.push(am5xy.ColumnSeries.new(root, {
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            categoryXField: "category",
            tooltip: am5.Tooltip.new(root, { labelText: "{valueY}" })
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5,
            strokeOpacity: 0
        });

        xAxis.data.setAll(data);
        series.data.setAll(data);
        chart.appear(1000, 100);
    }

    // Cargar por defecto
    cargarDatos(currentReport);
}
