<?php
include("../../modelos/conexion.php");

if (isset($_POST['id_empleado'])) {
    $id_empleado = $_POST['id_empleado'];

    $conn = Conexion::conectar();

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Obtener el id_persona asociado
        $stmt = $conn->prepare("SELECT id_persona FROM empleado WHERE id_empleado = ?");
        $stmt->execute([$id_empleado]);
        $id_persona = $stmt->fetchColumn();

        if ($id_persona) {
            // Eliminar al empleado
            $stmt = $conn->prepare("DELETE FROM empleado WHERE id_empleado = ?");
            $stmt->execute([$id_empleado]);

            // Eliminar a la persona
            $stmt = $conn->prepare("DELETE FROM persona WHERE id_persona = ?");
            $stmt->execute([$id_persona]);

            // Confirmar transacción
            $conn->commit();
            echo "✅ Empleado y persona eliminados correctamente.";
        } else {
            $conn->rollBack();
            echo "❌ No se encontró el empleado o persona asociada.";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "❌ Error al eliminar: " . $e->getMessage();
    }
} else {
    echo "❌ ID de empleado no recibido.";
}
?>
