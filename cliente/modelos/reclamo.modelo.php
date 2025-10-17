<?php

class ReclamoModelo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function guardarReclamo($id_cliente, $descripcion) {
        error_log("LLAMANDO a guardarReclamo con id_cliente = $id_cliente");
        try {
            $query = "INSERT INTO reclamos (id_cliente, descripcion)
                      VALUES (:id_cliente, :descripcion)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Fallo en execute para reclamo, id_cliente: $id_cliente, descripcion: $descripcion");
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error al guardar reclamo: " . $e->getMessage() . " | id_cliente: $id_cliente");
            return false;
        }
    }
}
?>
