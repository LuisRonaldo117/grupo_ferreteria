<?php
include("../modelos/conexion.php");
$conn = Conexion::conectar();
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    #listado thead th {
        background-color: #ffd966 !important;
        color: #000;
        text-align: center;
    }
    .dt-button {
        background-color: #cce5ff !important;
        color: #004085 !important;
        border: 1px solid #b8daff !important;
        border-radius: 4px;
        padding: 6px 12px;
        margin-right: 5px;
        font-weight: bold;
    }
    .dataTables_paginate .paginate_button {
        background-color: #cce5ff !important;
        color: #004085 !important;
        border: 1px solid #b8daff !important;
        margin: 2px;
        border-radius: 4px;
    }
    .dataTables_paginate .paginate_button.current {
        background-color: #80bdff !important;
        color: white !important;
        border: 1px solid #5dade2 !important;
    }
    .dataTables_paginate .paginate_button:hover {
        background-color: #99ccff !important;
        color: #003366 !important;
    }
</style>

<!-- Filtro de Mes -->
<section class="content-header mb-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <label for="filtroMes">Filtrar por Mes:</label>
                <select id="filtroMes" class="form-control">
                    <option value="">Todos</option>
                    <?php
                    $meses_es = [
                        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                    ];
                    foreach ($meses_es as $num => $nombre) {
                        echo "<option value='$num'>$nombre</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card shadow">
        <div class="card-body">
            <div id="botones" class="mb-3"></div>
            <div style="overflow-x:auto;">
                <table id="listado" class="table table-bordered table-striped display nowrap text-center" style="width:100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Venta</th>
                            <th>Cliente</th>
                            <th>Responsable</th>
                            <th>Método</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT f.id_factura, f.tipo_venta, f.total, f.fecha, f.estado,
                                       m.nombre_metodo,
                                       CONCAT(pc.nombres, ' ', pc.apellidos) AS nombre_cliente,
                                       CONCAT(pe.nombres, ' ', pe.apellidos) AS nombre_empleado
                                FROM factura f
                                JOIN metodo_pago m ON f.id_metodo = m.id_metodo
                                LEFT JOIN cliente c ON f.id_cliente = c.id_cliente
                                LEFT JOIN persona pc ON c.id_persona = pc.id_persona
                                LEFT JOIN empleado e ON f.id_empleado = e.id_empleado
                                LEFT JOIN persona pe ON e.id_persona = pe.id_persona
                                ORDER BY f.id_factura DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($facturas) {
                            foreach ($facturas as $row) {
                                $mesFecha = date('m', strtotime($row['fecha']));
                                $id_factura = str_pad($row['id_factura'], 6, '0', STR_PAD_LEFT);
                                echo "<tr data-mes='{$mesFecha}'>
                                        <td>{$id_factura}</td>
                                        <td>{$row['tipo_venta']}</td>
                                        <td>" . ($row['nombre_cliente'] ?? 'No registrado') . "</td>
                                        <td>" . ($row['nombre_empleado'] ?? 'No asignado') . "</td>
                                        <td>{$row['nombre_metodo']}</td>
                                        <td>$" . number_format($row['total'], 2, '.', ',') . "</td>
                                        <td>{$row['fecha']}</td>
                                        <td>{$row['estado']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay registros</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- JS jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var tabla = $('#listado').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', text: 'Excel', title: 'Reporte de Facturas', exportOptions: { columns: ':visible' } },
            { 
                extend: 'pdfHtml5', 
                text: 'PDF', 
                title: 'Reporte de Facturas', 
                orientation: 'portrait',  // Mantener vertical
    pageSize: 'A3',           
                exportOptions: { columns: ':visible' }, 
                customize: function (doc) {
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    doc.styles.tableHeader = {
                        fillColor: '#ffd966',
                        color: '#000',
                        alignment: 'center',
                        bold: true,
                        fontSize: 11
                    };
                    doc.styles.header = { fontSize: 18, bold: true };
                } 
            },
            { 
                extend: 'print', 
                text: 'Imprimir', 
                title: '', 
                exportOptions: { columns: ':visible' },
                customize: function(win) {
                    $(win.document.body).css('font-size', '10pt')
                        .prepend('<h2 style="text-align:center; margin-bottom:20px;">Reporte de Facturas</h2>');
                    $(win.document.body).find('table').addClass('compact').css({'font-size':'inherit','border-collapse':'collapse','width':'100%'});
                    $(win.document.body).find('table thead th').css({'background-color':'#ffd966','color':'#000','text-align':'center','border':'1px solid #999'});
                    $(win.document.body).find('table tbody td').css({'border':'1px solid #ccc','padding':'4px'});
                }
            },
            { extend: 'colvis', text: 'Mostrar/Ocultar columnas' }
        ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

// Filtro personalizado para el mes
$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    var filtroMes = $('#filtroMes').val();          // valor del select
    var fila = tabla.row(dataIndex).node();
    var mesFila = $(fila).data('mes').toString().padStart(2, '0'); // forzamos formato "01"

    if(filtroMes === "" || filtroMes === mesFila) {
        return true;
    }
    return false;
});


    // Redibujar tabla al cambiar filtro
    $('#filtroMes').on('change', function() {
        tabla.draw();
    });

    // Mostrar todos inicialmente
    tabla.draw();
});
</script>
