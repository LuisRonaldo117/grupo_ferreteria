<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Ventas Realizadas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Ventas Realizadas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<!-- Filtros fuera del section.content -->
<section id="filtrosVentas" class="mb-4">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filtroTipoVenta">Filtrar por Tipo de Venta:</label>
                <select id="filtroTipoVenta" class="form-control">
                    <option value="">Todos</option>
                    <option value="virtual">Venta Online</option>
                    <option value="presencial">Venta Física</option>
                    <!-- Más opciones si tienes -->
                </select>
            </div>
            <div class="col-md-4">
                <label for="filtroMes">Filtrar por Mes:</label>
                <select id="filtroMes" class="form-control">
                    <option value="">Todos</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
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

           
            <div id="formularioDetallesFactura" style="display:none; margin-bottom: 35px;">
                <!-- Aquí se cargan los detalles -->
            </div>

            <div id="contenedorTablaFactura" class="table-responsive">
                <table id="tablaFactura"
                    class="table table-hover table-bordered text-center display nowrap"
                    style="width:100%; background-color: #fff;">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Tipo de Venta</th>
                            <th>Cliente</th>
                            <th>Empleado</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../modelos/conexion.php");
                        $conn = Conexion::conectar();

                        $sql = "SELECT f.id_factura, f.tipo_venta, f.total, f.fecha, 
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
                                echo "<tr data-tipo='{$row['tipo_venta']}' data-mes='{$mesFecha}'>
                                    <td>{$row['id_factura']}</td>
                                    <td>{$row['tipo_venta']}</td>
                                    <td>" . ($row['nombre_cliente'] ?? 'no registrado') . "</td>
                                    <td>" . ($row['nombre_empleado'] ?? 'N/A') . "</td>
                                    <td>" . number_format($row['total'], 2, '.', ',') . "</td>
                                    <td>{$row['nombre_metodo']}</td>
                                    <td>{$row['fecha']}</td>
                                    <td>
                                        <button class='btn btn-sm btn-primary' onclick='verDetallesFactura({$row["id_factura"]})'>Ver Detalles</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No hay registros</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>

<!-- Modal para Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetallesLabel">Detalle de Factura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="contenido-detalles">
        <!-- Contenido dinámico de detalles -->
      </div>
    </div>
  </div>
</div>

<!-- jQuery y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    var tabla = $('#tablaFactura').DataTable({
    responsive: false,
    scrollX: true,
    lengthChange: true,
    pageLength: 15,
    autoWidth: false,
    order: [[0, 'desc']], // 👈 esto obliga a mostrar DESC por la primera columna (#)
    language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
    }
});


    // Filtro personalizado por tipo de venta y mes
    $.fn.dataTable.ext.search.push(
    function(settings, data, dataIndex) {
        var tipoVentaFiltro = $('#filtroTipoVenta').val();
        var mesFiltro = $('#filtroMes').val();

        var fila = tabla.row(dataIndex).node();
        var tipoVenta = $(fila).data('tipo');
        var mesVenta = parseInt($(fila).data('mes'));       // convertir a número entero
        var mesFiltroInt = mesFiltro ? parseInt(mesFiltro) : null; // convertir filtro a entero si existe

        // Filtrar por tipo de venta
        if (tipoVentaFiltro && tipoVentaFiltro !== tipoVenta) {
            return false;
        }

        // Filtrar por mes
        if (mesFiltroInt && mesFiltroInt !== mesVenta) {
            return false;
        }

        return true;
    }
);


    // Redibujar tabla al cambiar filtros
    $('#filtroTipoVenta, #filtroMes').on('change', function() {
        tabla.draw();
    });
});
// Función para abrir los detalles de factura en una nueva pestaña tipo PDF
//function verDetallesFactura(id) {
///    const url = 'vistas/factura_ventas.php?id_factura=' + id;
 //   window.open(url, '_blank');
//}
// Función para mostrar detalles de factura con AJAX en div, sin recargar página
function verDetallesFactura(id) {
    $('#filtrosVentas').hide(); // oculta los filtros
    $('#contenedorTablaFactura').hide();

   $('#formularioDetallesFactura')
       .slideDown()
      .load('vistas/factura_ventas.php', { id_factura: id });
}

</script>
