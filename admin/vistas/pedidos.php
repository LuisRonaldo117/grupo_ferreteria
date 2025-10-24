<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pedidos de Clientes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Pedidos de Clientes</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Filtros -->
<section id="filtrosPedidos" class="mb-4">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filtroEstado">Filtrar por Estado de Pedido:</label>
                <select id="filtroEstado" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="procesado">Procesado</option>
                    <option value="enviado">Enviado</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- Tabla de pedidos -->
<section class="content">
    <div class="card shadow">
        <div class="card-body">
            <div id="formularioDetallesPedido" style="display:none; margin-bottom: 35px;">
    <!-- Aquí se carga la factura de pedido -->
</div>


             <div id="contenedorTablaPedido" class="table-responsive">
                <table id="tablaPedidos"
                    class="table table-hover table-bordered text-center display nowrap"
                    style="width:100%; background-color: #fff;">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Responsable</th>
                            
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../modelos/conexion.php");
                        $conn = Conexion::conectar();

                        $sql = "SELECT p.id_pedido, p.total, p.fecha_pedido, p.estado, p.tipo_pago,
                                       CONCAT(pc.nombres, ' ', pc.apellidos) AS cliente,
                                       CONCAT(pe.nombres, ' ', pe.apellidos) AS empleado,
                                       s.nombre AS sucursal
                                FROM pedido p
                                LEFT JOIN cliente c ON p.id_cliente = c.id_cliente
                                LEFT JOIN persona pc ON c.id_persona = pc.id_persona
                                LEFT JOIN empleado e ON p.id_empleado = e.id_empleado
                                LEFT JOIN persona pe ON e.id_persona = pe.id_persona
                                JOIN sucursal s ON p.id_sucursal = s.id_sucursal
                                ORDER BY p.id_pedido DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($pedidos) {
                            foreach ($pedidos as $row) {
                                $mes = date('m', strtotime($row['fecha_pedido']));
                                echo "<tr data-estado='{$row['estado']}' data-mes='{$mes}'>
                                    <td>{$row['id_pedido']}</td>
                                    <td>" . ($row['cliente'] ?? 'No registrado') . "</td>
                                    <td>" . ($row['empleado'] ?? 'No asignado') . "</td>
                                    
                                    <td>" . number_format($row['total'], 2, '.', ',') . "</td>
                                    <td>{$row['tipo_pago']}</td>
                                    <td>{$row['estado']}</td>
                                    <td>{$row['fecha_pedido']}</td>
                                     <td>
  <button class='btn btn-sm btn-success' onclick='verDetallesPedido({$row["id_pedido"]})'>Ver Detalles</button>
</td>

                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay pedidos registrados</td></tr>";
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
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var tabla = $('#tablaPedidos').DataTable({
        responsive: false,
        scrollX: true,
        lengthChange: true,
        pageLength: 15,
        autoWidth: false,
        order: [[0, 'desc']],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    // Filtro por estado y mes
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var estadoFiltro = $('#filtroEstado').val();
        var mesFiltro = $('#filtroMes').val();

        var fila = tabla.row(dataIndex).node();
        var estado = $(fila).data('estado');
        var mes = $(fila).data('mes').toString().padStart(2, '0'); // aseguramos formato "01"

        if (estadoFiltro && estadoFiltro !== estado) return false;
        if (mesFiltro && mesFiltro !== mes) return false;

        return true;
    });

    $('#filtroEstado, #filtroMes').on('change', function() {
        tabla.draw();
    });
});
// Función para ver detalles del pedido como factura profesional
function verDetallesPedido(id) {
    $('#filtrosPedidos').hide();
    $('#contenedorTablaPedido').hide();

    $('#formularioDetallesPedido')
        .slideDown()
        .load('vistas/factura_pedido.php', { id_pedido: id });
}
</script>
