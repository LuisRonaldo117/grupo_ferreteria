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
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Inventario / Productos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Inventario / Productos</li>
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

        $sql = "SELECT p.id_producto, p.imagen, 
                         p.nombre, p.descripcion, p.precio_unitario, p.stock, c.nombre_categoria
                         FROM 
                        producto p
                        JOIN 
                        categoria c ON p.id_categoria = c.id_categoria";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_registrado = count($productos);
        ?>
        <div class="card-body">
            <div id="botones" class="mb-3"></div>

            <table id="listado" class="table table-bordered table-striped display nowrap" style="width:100%">
                <thead>
                    <tr>
                         <th>Id</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Categoria</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($total_registrado > 0) {
                        foreach ($productos as $row) {
                            echo "<tr>
                                <td>{$row['id_producto']}</td>
         
            <td>{$row['nombre']}</td>
            <td>{$row['descripcion']}</td>
                                <td>" . number_format($row["precio_unitario"], 2, '.', ',') . "</td>
                            <td>{$row['stock']}</td>
            <td>{$row['nombre_categoria']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No existe registro</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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

<!-- Inicialización del DataTable -->
<script>
    $(document).ready(function() {
        let tabla = $('#listado').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'Inventario de Productos',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row:first c', sheet).attr('s', '22'); // estilo encabezado Excel
                    }
                },
{
    extend: 'pdfHtml5',
    text: 'PDF',
    title: '',
    exportOptions: {
        columns: ':visible'
    },
    customize: async function(doc) {
        // Eliminar footer predeterminado para que no salga texto extra abajo

        // Insertar título al principio del documento PDF
        doc.content.splice(0, 0, {
            text: 'Reporte de productos',
            fontSize: 18,
            bold: true,
            alignment: 'center',
            margin: [0, 0, 0, 12]
        });

        // Función para convertir URL a Base64 (debes tenerla definida)
        // Aquí modificamos las celdas con imágenes a base64 si hay imágenes
        let rows = $('#listado tbody tr').toArray();

        for (let i = 0; i < rows.length; i++) {
            let imgTag = $(rows[i]).find('td:eq(1) img'); // columna imagen (índice 1)
            if (imgTag.length) {
                let imgUrl = imgTag.attr('src');
                let base64 = await toBase64(imgUrl);
                doc.content[1].table.body[i + 1][1] = {
                    image: base64,
                    width: 40
                };
            }
        }
    }
}

,        {
                    extend: 'print',
                    text: 'Imprimir',
                    title: '',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend(
                                '<h2 style="text-align:center; margin-bottom:20px;">Inventario de productos</h2>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit')
                            .css('border-collapse', 'collapse')
                            .css('width', '100%');

                        $(win.document.body).find('table thead th')
                            .css('background-color', '#4a90e2') // Celeste claro
                            .css('color', '#000') // Texto negro
                            .css('text-align', 'center')
                            .css('border', '1px solid #999');

                        $(win.document.body).find('table tbody td')
                            .css('border', '1px solid #ccc')
                            .css('padding', '4px');
                    }
                },
                'colvis'
            ],

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