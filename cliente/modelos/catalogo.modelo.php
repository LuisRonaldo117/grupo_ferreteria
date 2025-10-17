<?php

class CatalogoModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerProducto($categoria_id = null, $busqueda = null, $orden = null, $soloStock = false, $precio_min = null, $precio_max = null) {
        $query = "SELECT p.*, c.nombre_categoria 
                FROM producto p
                JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE 1=1";
        
        $params = [];

        // Filtro por categoría
        if ($categoria_id && $categoria_id != 'all') {
            $query .= " AND p.id_categoria = ?";
            $params[] = $categoria_id;
        }

        // Filtro por búsqueda
        if ($busqueda) {
            $query .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }

        // Filtro por stock
        if ($soloStock) {
            $query .= " AND p.stock > 0";
        }

        // Filtro por rango de precios
        if ($precio_min !== null && $precio_max !== null) {
            $query .= " AND p.precio_unitario BETWEEN ? AND ?";
            $params[] = $precio_min;
            $params[] = $precio_max;
        }

        // Ordenamiento
        switch ($orden) {
            case 'precio_asc':
                $query .= " ORDER BY p.precio_unitario ASC";
                break;
            case 'precio_desc':
                $query .= " ORDER BY p.precio_unitario DESC";
                break;
            case 'nombre_asc':
                $query .= " ORDER BY p.nombre ASC";
                break;
            case 'nombre_desc':
                $query .= " ORDER BY p.nombre DESC";
                break;
            default:
                $query .= " ORDER BY p.id_producto DESC";
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            return [];
        }
    }
    
    public function obtenerProductoPorId($id_producto) {
        $query = "SELECT p.*, c.nombre_categoria 
                  FROM producto p
                  JOIN categoria c ON p.id_categoria = c.id_categoria
                  WHERE p.id_producto = ?";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_producto]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener producto por ID: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerListadoProductos() {
        $query = "SELECT id_producto, nombre FROM producto ORDER BY nombre ASC";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener listado de productos: " . $e->getMessage());
            return [];
        }
    }
}