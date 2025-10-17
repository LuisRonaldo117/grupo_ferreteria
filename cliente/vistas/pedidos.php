<?php
session_start();

// Suponiendo que ya tienes el ID del cliente en sesión
$idCliente = $_SESSION['id_cliente'] ?? null;

if (!$idCliente) {
    die("Cliente no autenticado.");
}

include '../../conexion.php'; // Ruta corregida desde cliente/vistas/

$stmt = $conexion->prepare("SELECT c.id_carrito, p.imagen, p.nombre, c.precio_unitario, c.cantidad, c.subtotal
                            FROM carrito c
                            JOIN producto p ON c.id_producto = p.id_producto
                            WHERE c.id_cliente = ?");
$stmt->bind_param("i", $idCliente);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <style>
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 40px;
            background-color: #f9f9f9;
        }
        th, td {
            padding: 10px 15px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center; margin-top: 30px;">Mis Pedidos</h2>
 <h1>Id cliente Con id <?php echo $idCliente ?></h1>
 <table>
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Producto</th>
            <th>Precio Unitario</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="/grupo_ferreteria/imagenes/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen" width="60">
                </td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td>Bs. <?= number_format($row['precio_unitario'], 2) ?></td>
                <td><?= $row['cantidad'] ?></td>
                <td>Bs. <?= number_format($row['subtotal'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
