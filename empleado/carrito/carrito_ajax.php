<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = intval($_POST['id_producto'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 0);

    if ($idProducto > 0 && $cantidad > 0) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$idProducto])) {
            $_SESSION['carrito'][$idProducto] += $cantidad;
        } else {
            $_SESSION['carrito'][$idProducto] = $cantidad;
        }

        echo json_encode(['success' => true, 'mensaje' => '✅ Producto añadido al carrito.']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => '❌ Datos inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => '❌ Método no permitido.']);
}
?>
