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
$idCliente = $datosFactura['id_cliente'] ?? null;
$nombreCliente = null;
if ($idCliente) {
    include '../conexion.php'; // si no está ya incluido
    $stmt = $conexion->prepare("SELECT CONCAT(p.nombres, ' ', p.apellidos) AS nombre 
                                FROM cliente c 
                                JOIN persona p ON c.id_persona = p.id_persona 
                                WHERE c.id_cliente = ?");
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $stmt->bind_result($nombre);
    if ($stmt->fetch()) {
        $nombreCliente = $nombre;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura Generada</title>
  <link rel="stylesheet" href="../css/facturaexitosa.css" media="print">
  <link rel="stylesheet" href="../css/facturaexitosa.css" media="screen">
</head>
<body>

  <h1>Factura Generada</h1>

  <div class="factura-container">

    <div class="factura-header">
      <span class="factura-codigo"><strong>Código de Factura:</strong> <?= str_pad($idFactura, 5, '0', STR_PAD_LEFT) ?></span>
      <span class="factura-fecha"><strong>Fecha de Emisión:</strong> <?= $fechaEmision ?></span>
    </div>
    <div class="factura-header">
      <span class="factura-fecha"><strong>Cliente:</strong> <?= $nombreCliente ?></span>
    </div>
  
  
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
    <a href="#" class="btn btn-print" onclick="window.print()">Imprimir Factura</a>
    <a href="../index.php" class="btn btn-back">Volver al inicio</a>
  </div>

</body>
</html>
