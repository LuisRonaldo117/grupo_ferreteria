<?php
// ============================
// FACTURA DE PRUEBA (DEMO)
// ============================

// Datos simulados para visualizar el diseño sin generar la factura real.
$nombreEmpresa = "Grupo Ferretería S.R.L.";
$direccionEmpresa = "Av. América #245, Cochabamba - Bolivia";
$telefonoEmpresa = "+591 4 4456789";
$nitEmpresa = "7894567012";

$idFactura = 12345;
$fechaEmision = date("d/m/Y H:i:s");

$nombreCliente = "Juan Pérez López";
$direccionCliente = "Calle Siempre Viva 742";
$telefonoCliente = "+591 76543210";
$nitCliente = "12345678";

$productos = [
    ["nombre" => "Taladro Bosch GSB 13 RE", "precio_unitario" => 650.00, "cantidad" => 1, "subtotal" => 650.00],
    ["nombre" => "Martillo Stanley Antivibración", "precio_unitario" => 80.50, "cantidad" => 2, "subtotal" => 161.00],
    ["nombre" => "Caja de Tornillos 100pz", "precio_unitario" => 35.00, "cantidad" => 1, "subtotal" => 35.00],
];
$total = 846.00;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Vista Previa Factura</title>
  <link rel="stylesheet" href="../css/facturaexitosa.css">
</head>
<body>

  <h1>Factura</h1>

  <div class="factura-container">

    <!-- Encabezado empresa -->
    <div class="factura-header">
      <div>
        <strong><?= $nombreEmpresa ?></strong><br>
        <?= $direccionEmpresa ?><br>
        Tel: <?= $telefonoEmpresa ?> | NIT: <?= $nitEmpresa ?>
      </div>
      <div>
        <strong>N° Factura:</strong> <?= str_pad($idFactura, 5, '0', STR_PAD_LEFT) ?><br>
        <strong>Fecha:</strong> <?= $fechaEmision ?>
      </div>
    </div>

    <!-- Datos del cliente -->
    <div class="factura-header" style="background:#f5f5f5; color:#333; border-top:1px solid #ccc;">
      <div><strong>Cliente:</strong> <?= $nombreCliente ?></div>
      <div><strong>Teléfono:</strong> <?= $telefonoCliente ?></div>
      <div><strong>NIT:</strong> <?= $nitCliente ?></div>
    </div>

    <!-- Tabla de productos -->
    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio Unitario</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['nombre']) ?></td>
            <td>Bs/. <?= number_format($item['precio_unitario'], 2) ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td>Bs/. <?= number_format($item['subtotal'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
          <td><strong>Bs/. <?= number_format($total, 2) ?></strong></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="btn-container no-print">
    <a href="#" class="btn btn-print" onclick="window.print()">🖨️ Imprimir Factura</a>
    <a href="../index.php" class="btn btn-back">← Volver al inicio</a>
  </div>

</body>
</html>
