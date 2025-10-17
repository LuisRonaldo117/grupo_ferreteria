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
                <h1>Reportes / Asistencia</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes / Asistencia</li>
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
                            <th>Empleado</th>
                            <th>Usuario</th>
                            <th>Telefono</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                    a.id_asistencia,
                                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_empleado,
                                    e.usuario,
                                    p.telefono,
                                    a.fecha,
                                    a.hora_entrada,
                                    a.hora_salida
                                FROM asistencia a
                                JOIN empleado e ON a.id_empleado = e.id_empleado
                                JOIN persona p ON e.id_persona = p.id_persona
                                ORDER BY a.fecha DESC, a.hora_entrada DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($asistencias as $row) {
                            echo "<tr>
                                    <td>{$row['id_asistencia']}</td>
                                    <td>{$row['nombre_empleado']}</td>
                                    <td>{$row['usuario']}</td>
                                    <td>{$row['telefono']}</td>
                                    <td>{$row['fecha']}</td>
                                    <td>{$row['hora_entrada']}</td>
                                    <td>{$row['hora_salida']}</td>
                                </tr>";
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

<script>
    $(document).ready(function () {
        let tabla = $('#listado').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'Reporte de Asistencia',
                    
                    exportOptions: { columns: ':visible' }
                },
               {
    extend: 'pdfHtml5',
    text: 'PDF',
    title: 'Reporte de Asistencia',
    exportOptions: { columns: ':visible' },
    customize: function (doc) {
        // Ajustar la tabla al ancho completo
        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

        // Opcional: estilo del título
        doc.content.unshift({
            text: '',
            style: 'header',
            alignment: 'center',
            margin: [0, 0, 0, 20]
        });

        // Estilos personalizados
        doc.styles.tableHeader = {
            fillColor: '#ffd966',
            color: '#000',
            alignment: 'center',
            bold: true,
            fontSize: 11
        };
        doc.styles.header = {
            fontSize: 18,
            bold: true
        };
    }
}
,
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
                                '<h2 style="text-align:center; margin-bottom:20px;">Reporte de Asistencia</h2>'
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
                {
                    extend: 'colvis',
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
