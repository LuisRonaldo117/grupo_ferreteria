<?php

class CarritoModelo {
    
    public function obtenerCarrito($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT c.id_carrito, c.id_producto, c.cantidad, c.precio_unitario, c.subtotal,
                       p.nombre, p.descripcion, p.imagen, p.stock
                FROM carrito c
                INNER JOIN producto p ON c.id_producto = p.id_producto
                WHERE c.id_cliente = ?
                ORDER BY c.fecha_agregado DESC";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $carrito = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $carrito[] = $row;
            }
        }
        
        $stmt->close();
        $conexion->close();
        return $carrito;
    }
    
    public function agregarProducto($idCliente, $idProducto, $cantidad, $precioUnitario) {
        $conexion = conectarBD();
        
        // Verificar si el producto ya esta en el carrito
        $sql_verificar = "SELECT id_carrito, cantidad FROM carrito 
                         WHERE id_cliente = ? AND id_producto = ?";
        $stmt_verificar = $conexion->prepare($sql_verificar);
        $stmt_verificar->bind_param("ii", $idCliente, $idProducto);
        $stmt_verificar->execute();
        $result_verificar = $stmt_verificar->get_result();
        
        if ($result_verificar && $result_verificar->num_rows > 0) {
            // Actualizar cantidad si ya existe
            $item = $result_verificar->fetch_assoc();
            $nuevaCantidad = $item['cantidad'] + $cantidad;
            
            $sql_actualizar = "UPDATE carrito SET cantidad = ? WHERE id_carrito = ?";
            $stmt_actualizar = $conexion->prepare($sql_actualizar);
            $stmt_actualizar->bind_param("ii", $nuevaCantidad, $item['id_carrito']);
            $resultado = $stmt_actualizar->execute();
            
            $stmt_actualizar->close();
            $stmt_verificar->close();
            $conexion->close();
            
            return $resultado;
        } else {
            // Insertar nuevo producto
            $sql_insertar = "INSERT INTO carrito (id_cliente, id_producto, cantidad, precio_unitario) 
                            VALUES (?, ?, ?, ?)";
            $stmt_insertar = $conexion->prepare($sql_insertar);
            $stmt_insertar->bind_param("iiid", $idCliente, $idProducto, $cantidad, $precioUnitario);
            $resultado = $stmt_insertar->execute();
            
            $idInsertado = $conexion->insert_id;
            
            $stmt_insertar->close();
            $stmt_verificar->close();
            $conexion->close();
            
            return $resultado ? $idInsertado : false;
        }
    }
    
    public function actualizarCantidad($idCarrito, $idCliente, $nuevaCantidad) {
        $conexion = conectarBD();
        
        if ($nuevaCantidad <= 0) {
            // Eliminar producto si la cantidad es 0 o menor
            return $this->eliminarProducto($idCarrito, $idCliente);
        }
        
        $sql = "UPDATE carrito SET cantidad = ? 
                WHERE id_carrito = ? AND id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $nuevaCantidad, $idCarrito, $idCliente);
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $resultado;
    }
    
    public function eliminarProducto($idCarrito, $idCliente) {
        $conexion = conectarBD();
        
        $sql = "DELETE FROM carrito WHERE id_carrito = ? AND id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idCarrito, $idCliente);
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $resultado;
    }
    
    public function vaciarCarrito($idCliente) {
        $conexion = conectarBD();
        
        $sql = "DELETE FROM carrito WHERE id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $resultado;
    }
    
    public function obtenerTotalCarrito($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT SUM(subtotal) as total FROM carrito WHERE id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $total = 0;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total = $row['total'] ?? 0;
        }
        
        $stmt->close();
        $conexion->close();
        return $total;
    }
    
    public function obtenerCantidadTotalProductos($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT SUM(cantidad) as total_items FROM carrito WHERE id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $totalItems = 0;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalItems = $row['total_items'] ?? 0;
        }
        
        $stmt->close();
        $conexion->close();
        return $totalItems;
    }
}
?>