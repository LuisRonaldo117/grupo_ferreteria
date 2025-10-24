<?php

class CatalogoModelo {
    public function obtenerProductos($categoria = 'todos') {
        $conexion = conectarBD();
        
        // Construimos la consulta segun la categoria
        if ($categoria === 'todos' || !is_numeric($categoria)) {
            $sql = "SELECT p.*, c.nombre_categoria 
                    FROM producto p 
                    LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                    WHERE p.stock > 0 
                    ORDER BY p.nombre";
            $stmt = $conexion->prepare($sql);
        } else {
            $sql = "SELECT p.*, c.nombre_categoria 
                    FROM producto p 
                    LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                    WHERE p.id_categoria = ? AND p.stock > 0 
                    ORDER BY p.nombre";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $categoria);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Determinarmos la ruta de la imagen
                $imagen = $this->obtenerRutaImagen($row['imagen'], $row['nombre_categoria']);
                
                $productos[] = [
                    'id' => $row['id_producto'],
                    'nombre' => $row['nombre'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio_unitario'],
                    'stock' => $row['stock'],
                    'imagen' => $imagen,
                    'categoria' => $row['nombre_categoria']
                ];
            }
        }
        
        // Obtener categorias para los filtros
        $categorias = $this->obtenerCategorias($conexion);
        
        // Obtener nombre de la categoria actual
        $nombreCategoria = 'Todos los Productos';
        if ($categoria !== 'todos' && is_numeric($categoria)) {
            $nombreCategoria = $categorias[$categoria] ?? 'Categoría no encontrada';
        }
        
        $stmt->close();
        $conexion->close();
        
        return [
            'productos' => $productos,
            'categoriaActual' => $categoria,
            'nombreCategoria' => $nombreCategoria,
            'categorias' => $categorias
        ];
    }
    
    private function obtenerRutaImagen($nombreImagen, $categoria) {
        if (!empty($nombreImagen)) {
            // Si hay nombre de imagen en la bd, usar nuestra ruta local
            return 'assets/images/' . $nombreImagen;
        } else {
            // Si no hay imagen, usamos un icono segun la categoria
            return $this->obtenerIconoProducto($categoria);
        }
    }
    
    private function obtenerCategorias($conexion) {
        $categorias = [];
        $sql = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria";
        $result = $conexion->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $categorias[$row['id_categoria']] = $row['nombre_categoria'];
            }
        }
        
        return $categorias;
    }
    
    private function obtenerIconoProducto($categoria) {
        $iconos = [
            'HERRAMIENTAS MANUALES' => '🔨',
            'HERRAMIENTAS ELÉCTRICAS' => '⚡',
            'MATERIALES DE CONSTRUCCIÓN' => '🏗️',
            'TORNILLERÍA Y SUJECIÓN' => '🔩',
            'ELECTRICIDAD' => '💡',
            'FONTANERÍA' => '🚰',
            'PINTURAS Y ACCESORIOS' => '🎨',
            'SEGURIDAD INDUSTRIAL' => '🛡️'
        ];
        
        return $iconos[$categoria] ?? '📦';
    }

    public function buscarProductos($termino) {
        $conexion = conectarBD();
        
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE (p.nombre LIKE ? OR p.descripcion LIKE ? OR c.nombre_categoria LIKE ?)
                AND p.stock > 0 
                ORDER BY p.nombre";
        
        $stmt = $conexion->prepare($sql);
        $terminoBusqueda = "%" . $termino . "%";
        $stmt->bind_param("sss", $terminoBusqueda, $terminoBusqueda, $terminoBusqueda);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $productos[] = [
                    'id' => $row['id_producto'],
                    'nombre' => $row['nombre'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio_unitario'],
                    'stock' => $row['stock'],
                    'imagen' => $row['imagen'] ? 'assets/images/' . $row['imagen'] : $this->obtenerIconoProducto($row['nombre_categoria']),
                    'categoria' => $row['nombre_categoria']
                ];
            }
        }
        
        // Obtener categorias para los filtros
        $categorias = $this->obtenerCategorias($conexion);
        
        $stmt->close();
        $conexion->close();
        
        return [
            'productos' => $productos,
            'categoriaActual' => 'busqueda',
            'nombreCategoria' => 'Resultados de búsqueda: "' . $termino . '"',
            'categorias' => $categorias,
            'terminoBusqueda' => $termino,
            'totalResultados' => count($productos)
        ];
    }
}
?>