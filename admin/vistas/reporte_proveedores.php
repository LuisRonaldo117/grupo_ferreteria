<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- Estilos personalizados -->
<style>
    #listado thead th {
        background-color: #ffd966 !important;
        /* Encabezado amarillo claro */
        color: #000;
        text-align: center;
    }

    /* Botones de exportación */
    .dt-button {
        background-color: #cce5ff !important;
        /* Azul claro */
        color: #004085 !important;
        border: 1px solid #b8daff !important;
        border-radius: 4px;
        padding: 6px 12px;
        margin-right: 5px;
        font-weight: bold;
    }

    /* Botones de paginación */
    .dataTables_paginate .paginate_button {
        background-color: #cce5ff !important;
        color: #004085 !important;
        border: 1px solid #b8daff !important;
        margin: 2px;
        border-radius: 4px;
    }

    /* Botón de paginación activo */
    .dataTables_paginate .paginate_button.current {
        background-color: #80bdff !important;
        color: white !important;
        border: 1px solid #5dade2 !important;
    }

    /* Botones de paginación al pasar el mouse */
    .dataTables_paginate .paginate_button:hover {
        background-color: #99ccff !important;
        color: #003366 !important;
    }

    /* Contenedor para scroll horizontal en tablas */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* suaviza el scroll en móviles */
        width: 100%;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reporte de Proveedores / Productos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte Proveedores / Productos</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <?php
        include("../modelos/conexion.php");
        $conn = Conexion::conectar();

        $sql = "SELECT pr.id_proveedor, pr.nombre AS nombre_proveedor, pr.direccion, pr.telefono, pr.email,
                       p.nombre AS nombre_producto, pp.precio_compra, pp.fecha_suministro, pp.estado, pp.cantidad
                FROM proveedores pr
                JOIN producto_proveedor pp ON pr.id_proveedor = pp.id_proveedor
                JOIN producto p ON pp.id_producto = p.id_producto
                ORDER BY pr.id_proveedor, p.nombre";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_registros = count($registros);
        ?>
        <div class="card-body">
            <div id="botones" class="mb-3"></div>

            <div class="table-responsive">
                <table id="listado" class="table table-bordered table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre Proveedor</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Fecha Suministro</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_registros > 0) {
                            foreach ($registros as $row) {
                                echo "<tr>
                                    <td>{$row['nombre_proveedor']}</td>
                                    <td>{$row['direccion']}</td>
                                    <td>{$row['telefono']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['nombre_producto']}</td>
                                    <td>{$row['cantidad']}</td>
                                    <td>" . number_format($row['precio_compra'], 2, '.', ',') . "</td>
                                    <td>{$row['fecha_suministro']}</td>
                                    <td>" . ucfirst($row['estado']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No existen registros</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- jQuery y DataTables JS -->
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
        let tabla = $('#listado').DataTable({
            dom: 'Bfrtip',
           buttons: [
    {
        extend: 'excelHtml5',
        text: 'Excel',
        title: 'Reporte de Proveedores y Productos',
        exportOptions: { columns: ':visible' },
        customize: function(xlsx) {
            var sheet = xlsx.xl.worksheets['sheet1.xml'];
            $('row:first c', sheet).attr('s', '22');
        }
    },
    {
    extend: 'pdfHtml5',
    text: 'PDF',
    title: '',
    orientation: 'landscape', // <-- AQUI SE CAMBIA A HOJA HORIZONTAL
    pageSize: 'A4', // Opcional, se puede dejar así o cambiar a 'LEGAL'
    exportOptions: {
        columns: ':visible'
    },
    customize: function (doc) {
        // Cambiar márgenes y tamaño fuente
        doc.pageMargins = [20, 30, 20, 30]; // izquierda, arriba, derecha, abajo
        doc.defaultStyle.fontSize = 8; // reducir el tamaño para que entre más
        doc.styles.tableHeader.fontSize = 9;

        // Agregar título personalizado
        doc.content.splice(0, 0, {
            text: 'Reporte de Proveedores y Productos',
            fontSize: 14,
            bold: true,
            alignment: 'center',
            margin: [0, 0, 0, 12]
        });

        // Ajustar ancho de columnas si se desea (opcional)
        // doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*'];
    }
}

,
    {
        extend: 'print',
        text: 'Imprimir',
        title: '',
        exportOptions: { columns: ':visible' },
        customize: function(win) {
            // Limpiamos cualquier título que venga por defecto
            $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<h2 style="text-align:center; margin-bottom:20px;">Reporte de Proveedores</h2>'
                            );
            // Ponemos un título personalizado o ninguno (comentado el título)
            // $(win.document.body).prepend('<h2 style="text-align:center; margin-bottom:20px;">Reporte de Proveedores y Productos</h2>');

            // Estilo tabla en la impresión
            $(win.document.body).find('table')
                .addClass('compact')
                .css({
                    'font-size': 'inherit',
                    'border-collapse': 'collapse',
                    'width': '100%'
                });

            $(win.document.body).find('table thead th').css({
                'background-color': '#4a90e2',
                'color': '#000',
                'text-align': 'center',
                'border': '1px solid #999'
            });

            $(win.document.body).find('table tbody td').css({
                'border': '1px solid #ccc',
                'padding': '4px'
            });
        }
    },
    'colvis'
]
,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        tabla.buttons().container().appendTo('#botones');
    });
</script>
