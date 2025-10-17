
<?php
class FacturaModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function crearFactura($datos) {
        $this->db->beginTransaction();
        
        try {
            // Validación de datos
            if (empty($datos['id_cliente'])) {
                throw new Exception("ID de cliente no proporcionado");
            }
            
            if (!isset($datos['total']) || $datos['total'] <= 0) {
                throw new Exception("Total inválido o no proporcionado");
            }

            // Insertar factura principal
            $query = "INSERT INTO factura 
                    (tipo_venta, id_cliente, total, id_metodo, fecha, estado) 
                    VALUES 
                    ('virtual', :id_cliente, :total, 
                    (SELECT id_metodo FROM metodo_pago WHERE nombre_metodo = :metodo LIMIT 1), 
                    NOW(), :estado)";
            
            $stmt = $this->db->prepare($query);
            $params = [
                ':id_cliente' => $datos['id_cliente'],
                ':total' => $datos['total'],
                ':metodo' => $this->convertirMetodoPago($datos['metodo_pago'] ?? 'efectivo'),
                ':estado' => $datos['estado'] ?? 'pendiente'
            ];
            
            if (!$stmt->execute($params)) {
                throw new Exception("Error al crear factura");
            }

            $id_factura = $this->db->lastInsertId();
            
            // Insertar detalles de factura
            foreach ($datos['productos'] as $producto) {
                $query = "INSERT INTO detalle_factura 
                        (id_factura, id_producto, cantidad, precio_unitario)
                        VALUES 
                        (:id_factura, :id_producto, :cantidad, :precio)";
                
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([
                    ':id_factura' => $id_factura,
                    ':id_producto' => $producto['id_producto'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio' => $producto['precio_unitario'] ?? $producto['precio']
                ]);
                
                if (!$result) {
                    throw new Exception("Error al insertar detalle");
                }

                // Actualizar stock
                $query = "UPDATE producto SET stock = stock - ? WHERE id_producto = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$producto['cantidad'], $producto['id_producto']]);
            }
            
            $this->db->commit();
            return $id_factura;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en FacturaModelo: " . $e->getMessage());
            return false;
        }
    }

    private function convertirMetodoPago($metodo) {
        $metodos = [
            'tarjeta' => 'Tarjeta',
            'efectivo' => 'Efectivo',
            'qr' => 'Pago por QR'
        ];
        return $metodos[strtolower($metodo)] ?? 'Efectivo';
    }

    public function obtenerFactura($id_factura) {
        try {
            $query = "SELECT f.*, mp.nombre_metodo as metodo_pago 
                    FROM factura f
                    LEFT JOIN metodo_pago mp ON f.id_metodo = mp.id_metodo
                    WHERE f.id_factura = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_factura]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener factura: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstadoFactura($id_factura, $estado) {
        try {
            $query = "UPDATE factura SET estado = ? WHERE id_factura = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$estado, $id_factura]);
        } catch (PDOException $e) {
            error_log("Error al actualizar estado de factura: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerDetallesFactura($id_factura) {
        try {
            $query = "SELECT df.*, p.nombre as nombre_producto 
                    FROM detalle_factura df
                    LEFT JOIN producto p ON df.id_producto = p.id_producto
                    WHERE df.id_factura = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_factura]);
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($detalles)) {
                error_log("No se encontraron detalles para factura ID: $id_factura");
            }
            
            return $detalles;
        } catch (PDOException $e) {
            error_log("Error al obtener detalles de factura: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerCliente($id_cliente) {
        try {
            $query = "SELECT p.* 
                    FROM cliente c
                    JOIN persona p ON c.id_persona = p.id_persona
                    WHERE c.id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener cliente: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerCarrito($id_cliente) {
        try {
            // Obtener productos del carrito
            $query = "SELECT c.id_carrito, c.id_producto, c.cantidad, 
                    p.nombre, p.precio_unitario as precio, p.imagen
                    FROM carrito c
                    JOIN producto p ON c.id_producto = p.id_producto
                    WHERE c.id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calcular totales
            $subtotal = 0;
            foreach ($carrito as $item) {
                $subtotal += $item['precio'] * $item['cantidad'];
            }
            
            // Envío fijo
            $envio = 15.00;
            $total = $subtotal + $envio;

            return [
                'carrito' => $carrito,
                'subtotal' => $subtotal,
                'envio' => $envio,
                'total' => $total,
                'total_productos' => count($carrito)
            ];
        } catch (PDOException $e) {
            error_log("Error al obtener carrito: " . $e->getMessage());
            return [
                'carrito' => [],
                'subtotal' => 0,
                'envio' => 0,
                'total' => 0,
                'total_productos' => 0
            ];
        }
    }

    public function registrarPedido($id_factura, $id_cliente) {
        try {
            // Obtener datos de la factura
            $factura = $this->obtenerFactura($id_factura);
            $detalles = $this->obtenerDetallesFactura($id_factura);
            
            if (!$factura || empty($detalles)) {
                throw new Exception("No se pudo obtener datos de la factura para crear pedido");
            }
            
            // Insertar pedido principal
            $query = "INSERT INTO pedido 
                    (id_cliente, id_sucursal, fecha_pedido, estado, total, tipo_pago)
                    VALUES 
                    (:id_cliente, 1, NOW(), 'pendiente', :total, 
                    (SELECT nombre_metodo FROM metodo_pago WHERE id_metodo = :id_metodo))";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':id_cliente' => $id_cliente,
                ':total' => $factura['total'],
                ':id_metodo' => $factura['id_metodo']
            ]);
            
            $id_pedido = $this->db->lastInsertId();
            
            // Insertar detalles del pedido
            foreach ($detalles as $detalle) {
                $query = "INSERT INTO detalle_pedido 
                        (id_pedido, id_producto, cantidad, precio_unitario, total)
                        VALUES 
                        (:id_pedido, :id_producto, :cantidad, :precio, :total)";
                
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':id_pedido' => $id_pedido,
                    ':id_producto' => $detalle['id_producto'],
                    ':cantidad' => $detalle['cantidad'],
                    ':precio' => $detalle['precio_unitario'],
                    ':total' => $detalle['cantidad'] * $detalle['precio_unitario']
                ]);
            }
            
            return $id_pedido;
            
        } catch (Exception $e) {
            error_log("Error al registrar pedido: " . $e->getMessage());
            return false;
        }
    }
}
?>