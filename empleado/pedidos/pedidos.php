<?php
session_start();
include '../conexion.php';
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: ../logout.php'); // Ajusta la ruta a tu login
    exit;
}
// Obtener todos los pedidos con nombre del cliente y empleado asignado
$sql = "SELECT p.*, 
        CONCAT(pe.nombres, ' ', pe.apellidos) AS cliente,
        CONCAT(ee.nombres, ' ', ee.apellidos) AS responsable
        FROM pedido p
        JOIN cliente c ON p.id_cliente = c.id_cliente
        JOIN persona pe ON c.id_persona = pe.id_persona
        LEFT JOIN empleado e ON p.id_empleado = e.id_empleado
        LEFT JOIN persona ee ON e.id_persona = ee.id_persona
        WHERE p.estado != 'entregado'";

$pedidos = $conexion->query($sql);

// Obtener empleados para asignar
$empleados = [];
$resultEmp = $conexion->query("
    SELECT e.id_empleado, CONCAT(p.nombres, ' ', p.apellidos) AS nombre
    FROM empleado e
    JOIN persona p ON e.id_persona = p.id_persona
");
while ($row = $resultEmp->fetch_assoc()) {
    $empleados[] = $row;
}

// Procesar cambios de estado o empleado (desde modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_pedido'])) {
        $id_pedido = intval($_POST['id_pedido']);

        // 1. ACTUALIZAR ESTADO DEL PEDIDO
        if (isset($_POST['estado'])) {
            $estado = $_POST['estado'];
            $stmt = $conexion->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
            $stmt->bind_param("si", $estado, $id_pedido);
            $stmt->execute();

            // 2. OBTENER FECHA Y CLIENTE DEL PEDIDO
            $stmt = $conexion->prepare("SELECT fecha_pedido, id_cliente FROM pedido WHERE id_pedido = ?");
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $stmt->bind_result($fecha_pedido, $id_cliente);
            $stmt->fetch();
            $stmt->close();

            if (!empty($id_cliente) && !empty($fecha_pedido)) {
                // 3. BUSCAR FACTURA MÁS CERCANA (en ±60 segundos) AL PEDIDO DE ESE CLIENTE
                $stmt = $conexion->prepare("
                    SELECT id_factura 
                    FROM factura 
                    WHERE id_cliente = ? 
                    AND ABS(TIMESTAMPDIFF(SECOND, fecha, ?)) < 2
                    ORDER BY ABS(TIMESTAMPDIFF(SECOND, fecha, ?)) ASC 
                    LIMIT 1
                ");
                $stmt->bind_param("iss", $id_cliente, $fecha_pedido, $fecha_pedido);
                $stmt->execute();
                $stmt->bind_result($id_factura);
                $stmt->fetch();
                $stmt->close();

                // 4. ACTUALIZAR ESTADO DE LA FACTURA SI COINCIDE
                if (!empty($id_factura)) {
                    $estado_factura = null;
                    if (in_array($estado, ['entregado'])) {
                        $estado_factura = 'completado';
                    } elseif ($estado === 'cancelado') {
                        $estado_factura = 'cancelado';
                    }

                    if ($estado_factura !== null) {
                        $stmt = $conexion->prepare("UPDATE factura SET estado = ? WHERE id_factura = ?");
                        $stmt->bind_param("si", $estado_factura, $id_factura);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }

        // 5. ACTUALIZAR EMPLEADO RESPONSABLE (si se envió)
        // 5. ACTUALIZAR EMPLEADO RESPONSABLE (si se envió)
if (isset($_POST['id_empleado'])) {
    $id_empleado = $_POST['id_empleado'] === '' ? null : intval($_POST['id_empleado']);
    $stmt = $conexion->prepare("UPDATE pedido SET id_empleado = ? WHERE id_pedido = ?");
    $stmt->bind_param("ii", $id_empleado, $id_pedido);
    $stmt->execute();

    // 6. TAMBIÉN ACTUALIZAR EN LA TABLA FACTURA (si se encontró la factura)
    if (!empty($id_factura)) {
        $stmt = $conexion->prepare("UPDATE factura SET id_empleado = ? WHERE id_factura = ?");
        $stmt->bind_param("ii", $id_empleado, $id_factura);
        $stmt->execute();
        $stmt->close();
    }
}


        // Recargar para ver los cambios
        header("Location: pedidos.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Gestión de Pedidos</title>
<link rel="stylesheet" href="../css/pedidos.css" />
</head>
<body>

<div class="container">
  <h1 class="title">Lista de Pedidos</h1>
<a href="../index.php" class="btn-volver">Volver al Inicio</a>
  <table class="pedidos-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Total (Bs)</th>
        <th>Pago</th>
        <th>Estado</th>
        <th>Responsable</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($pedido = $pedidos->fetch_assoc()): ?>
        <tr>
          <td><?= $pedido['id_pedido'] ?></td>
          <td><?= htmlspecialchars($pedido['cliente']) ?></td>
          <td><?= $pedido['fecha_pedido'] ?></td>
          <td><?= number_format($pedido['total'], 2) ?></td>
          <td><?= ucfirst($pedido['tipo_pago']) ?></td>
          <?php
  $estado = $pedido['estado'];
  // Excluir 'entregado' ya que no lo muestras, pero ya lo filtraste en la consulta
  $claseEstado = "";
  switch ($estado) {
    case 'pendiente': $claseEstado = "estado-pendiente"; break;
    case 'procesado': $claseEstado = "estado-procesado"; break;
    case 'enviado':   $claseEstado = "estado-enviado"; break;
    case 'cancelado': $claseEstado = "estado-cancelado"; break;
  }
?>
<td><span class="estado-badge <?= $claseEstado ?>"><?= ucfirst($estado) ?></span></td>

          <td><?= htmlspecialchars($pedido['responsable'] ?? 'Sin asignar') ?></td>
          <td>
            <button class="btn btn-primary" onclick="abrirModal(<?= $pedido['id_pedido'] ?>)">Ver Detalles</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div id="modalPedido" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Detalles del Pedido</h2>
      <button class="close-btn" onclick="cerrarModal()">&times;</button>
    </div>
    <div class="modal-body" id="modalBody">
      <p>Cargando...</p>
    </div>
  </div>
</div>

<script>
function abrirModal(id) {
  const modal = document.getElementById('modalPedido');
  const body = document.getElementById('modalBody');
  modal.style.display = 'block';
  body.innerHTML = '<p>Cargando...</p>';

  fetch('ver_detalle.php?id=' + id)
    .then(res => res.text())
    .then(html => {
      body.innerHTML = html;
    });
}

function cerrarModal() {
  document.getElementById('modalPedido').style.display = 'none';
}

window.onclick = function(event) {
  const modal = document.getElementById('modalPedido');
  if (event.target === modal) {
    cerrarModal();
  }
}
</script>

</body>
</html>
