<?php 
// Verificar que todos los datos necesarios estén presentes
if (!isset($factura) || !isset($detalles) || !isset($cliente)) {
    echo '<div class="error"><p>Error: Datos de factura incompletos</p></div>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura #<?= htmlspecialchars($factura['id_factura']) ?></title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            color: #333; 
            background-color: #f9f9f9;
        }
        .page {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            padding-bottom: 20px; 
            border-bottom: 2px solid #0a2463;
        }
        .header h1 {
            color: #0a2463;
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 {
            color: #3e5c76;
            margin: 10px 0 0;
            font-size: 20px;
        }
        .header p {
            color: #5a6a7a;
            margin: 5px 0;
            font-size: 14px;
        }
        .info {
            margin-bottom: 25px;
            padding: 15px;
            background: #f0f4f8;
            border-radius: 5px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .info p {
            margin: 8px 0;
            flex-basis: 48%;
            font-size: 14px;
        }
        .info strong {
            color: #0a2463;
        }
        table {
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px;
            font-size: 14px;
        }
        th {
            background-color: #0a2463;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 500;
        }
        td { 
            border-bottom: 1px solid #e0e0e0;
            padding: 10px 8px; 
            text-align: left; 
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f0f4f8;
        }
        .total {
            text-align: right; 
            font-weight: bold; 
            margin-top: 20px;
            padding: 2px;
            background: #0a2463;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }
        .error { 
            color: #d9534f; 
            font-weight: bold; 
            text-align: center; 
            margin: 20px 0; 
        }
        .footer {
            margin-top: 40px; 
            text-align: center; 
            font-style: italic;
            color: #5a6a7a;
            font-size: 13px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }
        .invoice-number {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            background: #0a2463;
            color: white;
            border-radius: 30px;
            font-size: 18px;
            letter-spacing: 1px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #0a2463;
        }
        
        .envio-row td {
            border-bottom: none;
            background-color: #f8f9fa;
            font-weight: bold;
            color: #0a2463;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FERRETERÍA</h1>
        <p>Dirección:  Av. 16 de Julio #789, El Prado, La Paz, Bolivia</p>
        <p>Teléfono: +591 78421686 | NIT: 123478466</p>
        <h2>FACTURA #<?= str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT) ?></h2>
    </div>

    <div class="info">
        <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></p>
        <p><strong>Cliente:</strong> <?= $cliente['nombres'] ?> <?= $cliente['apellidos'] ?></p>
        <p><strong>CI:</strong> <?= $cliente['ci'] ?></p>
        <p><strong>Dirección:</strong> <?= $cliente['direccion'] ?></p>
        <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
        <p><strong>Método de pago:</strong> <?= $factura['metodo_pago'] ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $detalle): ?>
            <tr>
                <td><?= $detalle['nombre_producto'] ?></td>
                <td><?= $detalle['cantidad'] ?></td>
                <td>Bs. <?= number_format($detalle['precio_unitario'], 2) ?></td>
                <td>Bs. <?= number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="envio-row">
                <td class="empty-cell"></td>
                <td class="empty-cell"></td>
                <td><strong>Costo de envío:</strong></td>
                <td>Bs. <?= number_format(15, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p><strong>TOTAL: Bs. <?= number_format($factura['total'], 2) ?></strong></p>
    </div>


    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Factura generada electrónicamente el <?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></p>
        <p>Esta factura es válida como comprobante de venta</p>
    </div>
</body>
</html>