<?php
session_start();
if (!isset($_GET['id_factura']) || !isset($_SESSION['factura_generada'])) {
    header("Location: ../index.php");
    exit();
}

$idFactura = intval($_GET['id_factura']);
date_default_timezone_set('America/La_Paz');
$fechaEmision = date("d/m/Y H:i:s");
$datosFactura = $_SESSION['factura_generada'];
$productos = $datosFactura['productos'];
$total = $datosFactura['total'];

// Datos de la empresa (opcional, para encabezado)
$nombreEmpresa = "Grupo Ferretería S.R.L.";
$direccionEmpresa = "Av. 16 de Julio #789, El Prado";
$telefonoEmpresa = "+591 63171544";
$nitEmpresa = "7894567012";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura Generada</title>
  <link rel="stylesheet" href="../css/facturaexitosa.css">
</head>
<body>
  <div class="factura-container">

    <!-- Encabezado -->
    <div class="factura-header">
      <div>
        <strong>Dirrección:</strong> <?= $direccionEmpresa ?><br>
        <strong>NIT:</strong>  <?= $nitEmpresa ?>
      </div>
      <div>
        <strong>N° Factura:</strong> <?= str_pad($idFactura, 5, '0', STR_PAD_LEFT) ?><br>
        <strong>Fecha:</strong> <?= $fechaEmision ?>
      </div>
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
    <a href="../index.php" class="btn btn-back">Volver al inicio</a>
  </div>

</body>
</html>
