<?php
// Este es un ejemplo completo adaptado con filtros con select y filtrado por DataTables en el cliente
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalle Productos - Proveedores</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Producto-Proveedor</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Filtros -->
<section id="filtrosPP" class="mb-4">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Proveedor:</label>
                <select id="filtroProveedor" class="form-control">
                    <option value="">Todos</option>
                    <?php
                    include("../modelos/conexion.php");
                    $conn = Conexion::conectar();
                    $proveedores = $conn->query("SELECT id_proveedor, nombre FROM proveedores")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($proveedores as $prov) {
                        echo "<option value='{$prov['nombre']}'>{$prov['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
    <label>Mes:</label>
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

            <div class="col-md-2">
                <label>Estado:</label>
                <select id="filtroEstado" class="form-control">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<section class="content">
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaProductoProveedor" class="table table-hover table-bordered text-center display nowrap" style="width:100%; background-color: #fff;">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha Suministro</th>
                            <th>Días</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT pp.id, p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor, 
                                       pp.precio_compra, p.precio_unitario, pp.cantidad, 
                                       pp.fecha_suministro, pp.tiempo_entrega_dias, pp.estado
                                FROM producto_proveedor pp
                                JOIN producto p ON pp.id_producto = p.id_producto
                                JOIN proveedores pr ON pp.id_proveedor = pr.id_proveedor
                                ORDER BY pp.id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($datos) {
                            foreach ($datos as $row) {
                                $total = $row['precio_compra'] * $row['cantidad'];
                                $mes = date('m', strtotime($row['fecha_suministro']));
                                $anio = date('Y', strtotime($row['fecha_suministro']));
                                echo "<tr data-proveedor='{$row['nombre_proveedor']}' data-mes='{$mes}' data-anio='{$anio}' data-estado='{$row['estado']}'>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nombre_producto']}</td>
                                    <td>{$row['nombre_proveedor']}</td>
                                    <td>$" . number_format($row['precio_compra'], 2, '.', ',') . "</td>
                                    <td>$" . number_format($row['precio_unitario'], 2, '.', ',') . "</td>
                                    <td>{$row['cantidad']}</td>
                                    <td>$" . number_format($total, 2, '.', ',') . "</td>
                                    <td>{$row['fecha_suministro']}</td>
                                    <td>{$row['tiempo_entrega_dias']}</td>
                                    <td>" . ucfirst($row['estado']) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center'>No hay registros</td></tr>";
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

<script>
$(document).ready(function() {
    var tabla = $('#tablaProductoProveedor').DataTable({
        responsive: false,
        scrollX: true,
        lengthChange: true,
        pageLength: 15,
        autoWidth: false,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var proveedorFiltro = $('#filtroProveedor').val().toLowerCase();
            var mesFiltro = $('#filtroMes').val();
            var anioFiltro = $('#filtroAnio').val();
            var estadoFiltro = $('#filtroEstado').val().toLowerCase();

            var fila = tabla.row(dataIndex).node();
            var proveedor = $(fila).data('proveedor').toLowerCase();
            var mes = $(fila).data('mes');
            var anio = $(fila).data('anio');
            var estado = $(fila).data('estado').toLowerCase();

            if (proveedorFiltro && proveedorFiltro !== proveedor) return false;
            if (mesFiltro && mesFiltro !== mes) return false;
            if (anioFiltro && anioFiltro !== anio) return false;
            if (estadoFiltro && estadoFiltro !== estado) return false;

            return true;
        }
    );

    $('#filtroProveedor, #filtroMes, #filtroAnio, #filtroEstado').on('change', function() {
        tabla.draw();
    });
});
</script>

<style>
form .form-control {
    font-size: 14px;
}
</style>
