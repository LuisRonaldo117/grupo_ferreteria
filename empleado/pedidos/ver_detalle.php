<?php
session_start();
include '../conexion.php';
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: ../logout.php'); // Ajusta la ruta a tu login
    exit;
}
if (!isset($_GET['id'])) {
    echo "<p>ID de pedido no especificado.</p>";
    exit;
}

$id = intval($_GET['id']);

// Consulta principal del pedido y cliente
$stmt = $conexion->prepare("
    SELECT p.*, 
        CONCAT(pe.nombres, ' ', pe.apellidos) AS cliente,
        pe.direccion,
        pe.telefono,
        p.estado, p.tipo_pago, p.total,
        p.id_empleado
    FROM pedido p
    JOIN cliente c ON p.id_cliente = c.id_cliente
    JOIN persona pe ON c.id_persona = pe.id_persona
    WHERE p.id_pedido = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultPedido = $stmt->get_result();

if ($resultPedido->num_rows === 0) {
    echo "<p>Pedido no encontrado.</p>";
    exit;
}

$pedido = $resultPedido->fetch_assoc();

// Obtener lista empleados
$empleados = [];
$resEmps = $conexion->query("
    SELECT e.id_empleado, CONCAT(p.nombres, ' ', p.apellidos) AS nombre, e.id_cargo
    FROM empleado e
    JOIN persona p ON e.id_persona = p.id_persona
");
while ($row = $resEmps->fetch_assoc()) {
    $empleados[] = $row;
}

// Obtener detalles productos
$stmtDet = $conexion->prepare("
    SELECT dp.*, pr.nombre, pr.imagen 
    FROM detalle_pedido dp
    JOIN producto pr ON dp.id_producto = pr.id_producto
    WHERE dp.id_pedido = ?");
$stmtDet->bind_param("i", $id);
$stmtDet->execute();
$resDetalle = $stmtDet->get_result();
?>
<link rel="stylesheet" href="../css/detallepedido.css" />
<div class="pedido-container">

  <!-- Info cliente -->
  <div class="pedido-info">
    <p><strong>Cliente:</strong> <span><?= htmlspecialchars($pedido['cliente']) ?></span></p>
    <p><strong>Dirección:</strong> <span><?= htmlspecialchars($pedido['direccion']) ?></span></p>
    <p><strong>Teléfono:</strong> <span><?= htmlspecialchars($pedido['telefono']) ?></span></p>
    <p><strong>Fecha:</strong> <span><?= $pedido['fecha_pedido'] ?></span></p>
    <p><strong>Tipo de pago:</strong> <span><?= ucfirst($pedido['tipo_pago']) ?></span></p>
  </div>

  <h3 class="productos-titulo">Productos</h3>

  <?php if ($resDetalle->num_rows > 0): ?>
    <table class="productos-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Imagen</th>
          <th>Cantidad</th>
          <th>Precio Unitario</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $resDetalle->fetch_assoc()): ?>
          <tr>
            <td class="nombre-producto"><?= htmlspecialchars($row['nombre']) ?></td>
            <td class="imagen-producto">
              <img src="../../imagenes/<?= basename($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>" />
            </td>
            <td><?= $row['cantidad'] ?></td>
            <td>Bs. <?= number_format($row['precio_unitario'], 2) ?></td>
            <td>Bs. <?= number_format($row['total'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="total-factura">
  <p class="envio-costo"><strong>Costo Adicional por Envío:</strong> <span>Bs. 15.00</span></p>
  <p><strong>Total del Pedido:</strong> <span>Bs. <?= number_format($pedido['total'], 2) ?></span></p>
</div>
  <?php else: ?>
    <p class="no-productos">No hay productos en este pedido.</p>
  <?php endif; ?>

  <h3 class="actualizar-titulo">Actualizar Estado y Responsable</h3>

  <form method="POST" action="pedidos.php" class="form-actualizar">
    <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">

    <label for="estado">Estado:</label>
    <select name="estado" id="estado" class="select-estilo">
      <?php
        $estados = ['pendiente', 'procesado', 'enviado', 'entregado', 'cancelado'];
        foreach ($estados as $estado) {
            $sel = ($estado === $pedido['estado']) ? 'selected' : '';
            echo "<option value='$estado' $sel>" . ucfirst($estado) . "</option>";
        }
      ?>
    </select>

    <label for="id_empleado">Responsable:</label>
    <select name="id_empleado" id="id_empleado" class="select-estilo">
      <option value="">Sin asignar</option>
      <?php foreach ($empleados as $emp):
        if ($emp['id_cargo'] != 6) continue;
        $sel = ($emp['id_empleado'] == $pedido['id_empleado']) ? 'selected' : '';
      ?>
        <option value="<?= $emp['id_empleado'] ?>" <?= $sel ?>>
          <?= htmlspecialchars($emp['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" class="btn-guardar">Guardar Cambios</button>
  </form>

</div>
