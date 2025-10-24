<?php
class PedidoModelo {
    
    public function crearPedido($idCliente, $total, $metodoPago, $productos, $estado = 'pendiente') {
        $conexion = conectarBD();
        
        // Convertir metodo de pago a formato de la bd
        $tipoPago = $this->convertirMetodoPago($metodoPago);
        
        // FORZAR estado 'pendiente' - ignorar el parámetro $estado
        $sql = "INSERT INTO pedido (id_cliente, total, tipo_pago, estado, fecha_pedido) 
                VALUES (?, ?, ?, 'pendiente', NOW())";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ids", $idCliente, $total, $tipoPago);
        
        if ($stmt->execute()) {
            $idPedido = $conexion->insert_id;
            
            // Insertar detalles del pedido
            $this->insertarDetallesPedido($conexion, $idPedido, $productos);
            
            $stmt->close();
            $conexion->close();
            return $idPedido;
        }
        
        $stmt->close();
        $conexion->close();
        return false;
    }
    
    private function convertirMetodoPago($metodoPago) {
        $metodos = [
            'tarjeta' => 'tarjeta',
            'efectivo' => 'efectivo',
            'qr' => 'transferencia'
        ];
        return $metodos[$metodoPago] ?? 'otro';
    }
    
    private function insertarDetallesPedido($conexion, $idPedido, $productos) {
        $sql = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, total) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        
        foreach ($productos as $producto) {
            $subtotal = $producto['cantidad'] * $producto['precio_unitario'];
            $stmt->bind_param("iiidd", 
                $idPedido, 
                $producto['id_producto'], 
                $producto['cantidad'], 
                $producto['precio_unitario'], 
                $subtotal
            );
            $stmt->execute();
        }
        
        $stmt->close();
    }
    
    public function obtenerPedido($idPedido, $idCliente = null) {
        $conexion = conectarBD();
        
        $sql = "SELECT p.*, c.nombres, c.apellidos, c.ci, c.direccion, c.telefono
                FROM pedido p 
                INNER JOIN cliente cl ON p.id_cliente = cl.id_cliente
                INNER JOIN persona c ON cl.id_persona = c.id_persona
                WHERE p.id_pedido = ?";
        
        if ($idCliente) {
            $sql .= " AND p.id_cliente = ?";
        }
        
        $stmt = $conexion->prepare($sql);
        
        if ($idCliente) {
            $stmt->bind_param("ii", $idPedido, $idCliente);
        } else {
            $stmt->bind_param("i", $idPedido);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        
        $stmt->close();
        $conexion->close();
        return $pedido;
    }
    
    public function obtenerDetallesPedido($idPedido) {
        $conexion = conectarBD();
        
        $sql = "SELECT dp.*, pr.nombre as nombre_producto, pr.imagen
                FROM detalle_pedido dp
                INNER JOIN producto pr ON dp.id_producto = pr.id_producto
                WHERE dp.id_pedido = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $detalles = [];
        while ($row = $result->fetch_assoc()) {
            $detalles[] = $row;
        }
        
        $stmt->close();
        $conexion->close();
        return $detalles;
    }
    
    public function actualizarEstadoPedido($idPedido, $nuevoEstado) {
        $conexion = conectarBD();
        
        $sql = "UPDATE pedido SET estado = ? WHERE id_pedido = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $nuevoEstado, $idPedido);
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        return $resultado;
    }
    
    public function obtenerPedidosCliente($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT * FROM pedido WHERE id_cliente = ? ORDER BY fecha_pedido DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        
        $stmt->close();
        $conexion->close();
        return $pedidos;
    }
}
?>