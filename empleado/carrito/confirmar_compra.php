<?php
session_start();
$id_empleado = $_SESSION['id_empleado'];
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        die("No hay productos en el carrito.");
    }

    $tipoVenta  = $_POST['tipo_venta'] ?? 'presencial';
    $idCliente  = null;
    $estado     = 'completado';
    $idMetodo   = intval($_POST['id_metodo'] ?? 1);
    $total      = floatval($_POST['total'] ?? 0);

    if ($total <= 0) {
        die("El total no puede ser cero.");
    }

    $conexion->begin_transaction();

    try {
        // ✅ CORREGIDO: sin doble $
        $stmt = $conexion->prepare("INSERT INTO factura (tipo_venta, id_cliente, id_empleado, total, id_metodo, estado) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) throw new Exception("Error preparando la inserción de factura: " . $conexion->error);

        // ✅ CORREGIDO: se usa 's' para el campo estado que es string
        $stmt->bind_param("siidss", $tipoVenta, $idCliente, $id_empleado, $total, $idMetodo, $estado);
        if (!$stmt->execute()) throw new Exception("Error al ejecutar inserción de factura: " . $stmt->error);

        $idFactura = $stmt->insert_id;
        $stmt->close();

        $stmtDetalle = $conexion->prepare("INSERT INTO detalle_factura (id_factura, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        if (!$stmtDetalle) throw new Exception("Error preparando detalle_factura: " . $conexion->error);

        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
            $stmtProducto = $conexion->prepare("SELECT precio_unitario, stock FROM producto WHERE id_producto = ?");
            if (!$stmtProducto) throw new Exception("Error consultando producto: " . $conexion->error);

            $stmtProducto->bind_param("i", $idProducto);
            $stmtProducto->execute();
            $result = $stmtProducto->get_result();

            if ($row = $result->fetch_assoc()) {
                $precio = floatval($row['precio_unitario']);
                $stockActual = intval($row['stock']);

                if ($stockActual < $cantidad) {
                    throw new Exception("No hay suficiente stock para el producto ID $idProducto");
                }

                $stmtDetalle->bind_param("iiid", $idFactura, $idProducto, $cantidad, $precio);
                if (!$stmtDetalle->execute()) throw new Exception("Error insertando detalle_factura: " . $stmtDetalle->error);

                $nuevoStock = $stockActual - $cantidad;
                $stmtUpdate = $conexion->prepare("UPDATE producto SET stock = ? WHERE id_producto = ?");
                if (!$stmtUpdate) throw new Exception("Error preparando actualización de stock: " . $conexion->error);

                $stmtUpdate->bind_param("ii", $nuevoStock, $idProducto);
                if (!$stmtUpdate->execute()) throw new Exception("Error actualizando stock: " . $stmtUpdate->error);

                $stmtUpdate->close();
            }

            $stmtProducto->close();
        }

        $stmtDetalle->close();
        $conexion->commit();

        // Obtener los detalles de productos...
        $productosFactura = [];
        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
            $stmtProducto = $conexion->prepare("SELECT nombre, precio_unitario FROM producto WHERE id_producto = ?");
            $stmtProducto->bind_param("i", $idProducto);
            $stmtProducto->execute();
            $result = $stmtProducto->get_result();
            if ($row = $result->fetch_assoc()) {
                $nombre = $row['nombre'];
                $precio = floatval($row['precio_unitario']);
                $subtotal = $precio * $cantidad;

                $productosFactura[] = [
                    'nombre' => $nombre,
                    'precio_unitario' => $precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal
                ];
            }
            $stmtProducto->close();
        }

        // Guardar en sesión para mostrar en factura_exitosa.php
        $_SESSION['factura_generada'] = [
            'productos' => $productosFactura,
            'total' => $total
        ];

        // 🔻 Limpiar el carrito
        unset($_SESSION['carrito']);

        header("Location: factura_exitosa.php?id_factura=$idFactura");
        exit();

    } catch (Exception $e) {
        $conexion->rollback();
        die("Ocurrió un error al procesar la compra: " . $e->getMessage());
    }
}
?>
