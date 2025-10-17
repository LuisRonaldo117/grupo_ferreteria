<?php

class ProductoModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para actualizar la imagen de un producto
    public function actualizarImagenProducto($id_producto, $ruta_imagen) {
        $query = "UPDATE producto SET imagen = ? WHERE id_producto = ?";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$ruta_imagen, $id_producto]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar imagen del producto: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener un producto por ID
    public function obtenerProducto($id_producto) {
        $query = "SELECT * FROM producto WHERE id_producto = ?";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_producto]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener producto: " . $e->getMessage());
            return false;
        }
    }
}
?>

