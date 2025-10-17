<?php
// Ajusta la ruta según donde esté tu conexión
include '../../conexion.php';

header('Content-Type: application/json');

$sql = "SELECT COUNT(*) AS total FROM pedido WHERE estado != 'entregado'";

$result = $conexion->query($sql);
$total = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $total = (int) $row['total'];
}

// Devuelve el total en formato JSON
echo json_encode(['total' => $total]);
?>
