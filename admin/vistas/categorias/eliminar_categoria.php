<?php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_categoria'];

    try {
        $sql = "DELETE FROM categoria WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        echo "Categoría eliminada correctamente.";
    } catch (Exception $e) {
        echo "Error al eliminar: " . $e->getMessage();
    }
}
?>
