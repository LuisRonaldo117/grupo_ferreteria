<?php
include("../../modelos/conexion.php");

if (isset($_POST['id_proveedor'])) {
    $id = $_POST['id_proveedor'];

    $conn = Conexion::conectar();

    $sql = "DELETE FROM proveedores WHERE id_proveedor = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$id])) {
        echo "Proveedor eliminado correctamente.";
    } else {
        echo "Error al eliminar el Proveedor.";
    }
} else {
    echo "ID de proveedor no recibido.";
}
?>
