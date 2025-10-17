<?php
// eliminar_sucursal.php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_sucursal"])) {
    $id = $_POST["id_sucursal"];

    try {
        $stmt = $conn->prepare("DELETE FROM sucursal WHERE id_sucursal = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        echo json_encode('Sucursal eliminada correctamente.');
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
?>
