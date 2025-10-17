<?php
include("../../modelos/conexion.php");

if (isset($_POST['id_producto'])) {
    $id = $_POST['id_producto'];

    $conn = Conexion::conectar();

    try {
        $conn->beginTransaction();

        // Eliminar de detalle_factura (si estás seguro de borrar registros históricos)
        $sql0 = "DELETE FROM detalle_factura WHERE id_producto = ?";
        $conn->prepare($sql0)->execute([$id]);

        // Eliminar de producto_proveedor
        $sql1 = "DELETE FROM producto_proveedor WHERE id_producto = ?";
        $conn->prepare($sql1)->execute([$id]);

        // Eliminar de producto
        $sql2 = "DELETE FROM producto WHERE id_producto = ?";
        $conn->prepare($sql2)->execute([$id]);

        $conn->commit();
        echo "Producto y relaciones eliminadas correctamente.";
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error al eliminar: " . $e->getMessage();
    }
} else {
    echo "ID de producto no recibido.";
}
