<?php

class FacturaModelo {
    
    public function crearFactura($datos) {
        $conexion = conectarBD();
        
        try {
            // Validacion de datos
            if (empty($datos['id_cliente'])) {
                throw new Exception("ID de cliente no proporcionado");
            }
            
            if (!isset($datos['total']) || $datos['total'] <= 0) {
                throw new Exception("Total inválido o no proporcionado");
            }

            // Obtener id del metodo de pago
            $metodoPago = $this->convertirMetodoPago($datos['metodo_pago'] ?? 'efectivo');
            $sqlMetodo = "SELECT id_metodo FROM metodo_pago WHERE nombre_metodo = ? LIMIT 1";
            $stmtMetodo = $conexion->prepare($sqlMetodo);
            $stmtMetodo->bind_param("s", $metodoPago);
            $stmtMetodo->execute();
            $resultMetodo = $stmtMetodo->get_result();
            $idMetodo = $resultMetodo->fetch_assoc()['id_metodo'] ?? 2; // Default: Efectivo
            $stmtMetodo->close();

            // Insertar factura principal
            $sql = "INSERT INTO factura 
                    (tipo_venta, id_cliente, total, id_metodo, fecha, estado) 
                    VALUES 
                    ('virtual', ?, ?, ?, NOW(), ?)";
            
            $stmt = $conexion->prepare($sql);
            $estado = $datos['estado'] ?? 'completado';
            $stmt->bind_param("idis", 
                $datos['id_cliente'],
                $datos['total'],
                $idMetodo,
                $estado
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Error al crear factura: " . $stmt->error);
            }

            $id_factura = $conexion->insert_id;
            
            // Insertar detalles de factura
            foreach ($datos['productos'] as $producto) {
                $sqlDetalle = "INSERT INTO detalle_factura 
                        (id_factura, id_producto, cantidad, precio_unitario)
                        VALUES 
                        (?, ?, ?, ?)";
                
                $stmtDetalle = $conexion->prepare($sqlDetalle);
                $stmtDetalle->bind_param("iiid",
                    $id_factura,
                    $producto['id'],
                    $producto['cantidad'],
                    $producto['precio']
                );
                
                if (!$stmtDetalle->execute()) {
                    throw new Exception("Error al insertar detalle: " . $stmtDetalle->error);
                }

                // Actualizar stock
                $sqlStock = "UPDATE producto SET stock = stock - ? WHERE id_producto = ?";
                $stmtStock = $conexion->prepare($sqlStock);
                $stmtStock->bind_param("ii", $producto['cantidad'], $producto['id']);
                $stmtStock->execute();
                $stmtStock->close();
                
                $stmtDetalle->close();
            }
            
            // Registrar pedido
            $this->registrarPedido($id_factura, $datos['id_cliente'], $conexion);
            
            $conexion->commit();
            return $id_factura;
            
        } catch (Exception $e) {
            $conexion->rollback();
            error_log("Error en FacturaModelo: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) $stmt->close();
            $conexion->close();
        }
    }

    private function convertirMetodoPago($metodo) {
        $metodos = [
            'tarjeta' => 'Tarjeta',
            'efectivo' => 'Efectivo',
            'qr' => 'QR'
        ];
        return $metodos[strtolower($metodo)] ?? 'Efectivo';
    }

    public function obtenerFactura($id_factura) {
        $conexion = conectarBD();
        
        try {
            $sql = "SELECT f.*, mp.nombre_metodo as metodo_pago 
                    FROM factura f
                    LEFT JOIN metodo_pago mp ON f.id_metodo = mp.id_metodo
                    WHERE f.id_factura = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id_factura);
            $stmt->execute();
            $result = $stmt->get_result();
            $factura = $result->fetch_assoc();
            
            $stmt->close();
            $conexion->close();
            
            return $factura;
        } catch (Exception $e) {
            error_log("Error al obtener factura: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerDetallesFactura($id_factura) {
        $conexion = conectarBD();
        
        try {
            $sql = "SELECT df.*, p.nombre as nombre_producto 
                    FROM detalle_factura df
                    LEFT JOIN producto p ON df.id_producto = p.id_producto
                    WHERE df.id_factura = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id_factura);
            $stmt->execute();
            $result = $stmt->get_result();
            $detalles = [];
            
            while ($row = $result->fetch_assoc()) {
                $detalles[] = $row;
            }
            
            $stmt->close();
            $conexion->close();
            
            return $detalles;
        } catch (Exception $e) {
            error_log("Error al obtener detalles de factura: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerCliente($id_cliente) {
        $conexion = conectarBD();
        
        try {
            $sql = "SELECT p.* 
                    FROM cliente c
                    JOIN persona p ON c.id_persona = p.id_persona
                    WHERE c.id_cliente = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id_cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $cliente = $result->fetch_assoc();
            
            $stmt->close();
            $conexion->close();
            
            return $cliente;
        } catch (Exception $e) {
            error_log("Error al obtener cliente: " . $e->getMessage());
            return false;
        }
    }

    private function registrarPedido($id_factura, $id_cliente, $conexion) {
        try {
            // Obtener datos de la factura
            $sqlFactura = "SELECT total, id_metodo FROM factura WHERE id_factura = ?";
            $stmtFactura = $conexion->prepare($sqlFactura);
            $stmtFactura->bind_param("i", $id_factura);
            $stmtFactura->execute();
            $resultFactura = $stmtFactura->get_result();
            $factura = $resultFactura->fetch_assoc();
            $stmtFactura->close();
            
            if (!$factura) {
                throw new Exception("No se pudo obtener datos de la factura");
            }
            
            // Obtener metodo de pago
            $sqlMetodo = "SELECT nombre_metodo FROM metodo_pago WHERE id_metodo = ?";
            $stmtMetodo = $conexion->prepare($sqlMetodo);
            $stmtMetodo->bind_param("i", $factura['id_metodo']);
            $stmtMetodo->execute();
            $resultMetodo = $stmtMetodo->get_result();
            $metodoPago = $resultMetodo->fetch_assoc()['nombre_metodo'] ?? 'efectivo';
            $stmtMetodo->close();
            
            // Insertar pedido principal
            $sqlPedido = "INSERT INTO pedido 
                    (id_cliente, id_sucursal, fecha_pedido, estado, total, tipo_pago)
                    VALUES 
                    (?, 1, NOW(), 'pendiente', ?, ?)";
            
            $stmtPedido = $conexion->prepare($sqlPedido);
            $stmtPedido->bind_param("ids", $id_cliente, $factura['total'], $metodoPago);
            $stmtPedido->execute();
            $id_pedido = $conexion->insert_id;
            $stmtPedido->close();
            
            // Obtener detalles de la factura
            $sqlDetalles = "SELECT id_producto, cantidad, precio_unitario 
                           FROM detalle_factura 
                           WHERE id_factura = ?";
            $stmtDetalles = $conexion->prepare($sqlDetalles);
            $stmtDetalles->bind_param("i", $id_factura);
            $stmtDetalles->execute();
            $resultDetalles = $stmtDetalles->get_result();
            
            // Insertar detalles del pedido
            while ($detalle = $resultDetalles->fetch_assoc()) {
                $sqlDetallePedido = "INSERT INTO detalle_pedido 
                        (id_pedido, id_producto, cantidad, precio_unitario, total)
                        VALUES 
                        (?, ?, ?, ?, ?)";
                
                $total = $detalle['cantidad'] * $detalle['precio_unitario'];
                $stmtDetallePedido = $conexion->prepare($sqlDetallePedido);
                $stmtDetallePedido->bind_param("iiidd", 
                    $id_pedido, 
                    $detalle['id_producto'], 
                    $detalle['cantidad'], 
                    $detalle['precio_unitario'],
                    $total
                );
                $stmtDetallePedido->execute();
                $stmtDetallePedido->close();
            }
            
            $stmtDetalles->close();
            return $id_pedido;
            
        } catch (Exception $e) {
            error_log("Error al registrar pedido: " . $e->getMessage());
            return false;
        }
    }
}
?>