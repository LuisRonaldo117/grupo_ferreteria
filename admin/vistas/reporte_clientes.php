<?php
include("../modelos/conexion.php");
$conn = Conexion::conectar();
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- Estilos personalizados -->
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

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Reportes / Clientes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes / Clientes</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-body">
            <div id="botones" class="mb-3"></div>
            <div style="overflow-x:auto;">
                <table id="listado" class="table table-bordered table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Usuario</th>
                            <th>Fecha de Creacion</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Departamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                    c.id_cliente,
                                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
                                    c.usuario,
                                    c.fecha_creacion,
                                    p.direccion,
                                    p.telefono,
                                    d.nom_departamento
                                FROM cliente c
                                INNER JOIN persona p ON c.id_persona = p.id_persona
                                INNER JOIN departamento d ON p.id_departamento = d.id_departamento
                                ORDER BY c.id_cliente";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($clientes) {
                            foreach ($clientes as $row) {
                                echo "<tr>
                                        <td>{$row["id_cliente"]}</td>
                                        <td>{$row["nombre_completo"]}</td>
                                        <td>{$row["usuario"]}</td>
                                        <td>{$row["fecha_creacion"]}</td>
                                        <td>{$row["direccion"]}</td>
                                        <td>{$row["telefono"]}</td>
                                        <td>{$row["nom_departamento"]}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No existen registros</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- jQuery y DataTables -->
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
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'Reporte de Clientes',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row:first c', sheet).attr('s', '22');
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                     title: 'Reporte de Clientes',
                    exportOptions: {
                        columns: ':visible'
                    },
                },
                {
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
                                '<h2 style="text-align:center; margin-bottom:20px;">Reporte de Clientes</h2>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit')
                            .css('border-collapse', 'collapse')
                            .css('width', '100%');

                        $(win.document.body).find('table thead th')
                            .css('background-color', '#4a90e2')
                            .css('color', '#000')
                            .css('text-align', 'center')
                            .css('border', '1px solid #999');

                        $(win.document.body).find('table tbody td')
                            .css('border', '1px solid #ccc')
                            .css('padding', '4px');
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed one-column',
                    text: 'Mostrar/Ocultar columnas'
                }
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
