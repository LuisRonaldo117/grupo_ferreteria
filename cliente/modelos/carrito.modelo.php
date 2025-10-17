<?php
class CarritoModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerCarrito($id_cliente) {
        try {
            $query = "SELECT c.id_carrito, c.id_producto, p.nombre, p.precio_unitario as precio, c.cantidad, p.stock, 
                    cat.nombre_categoria, p.imagen
                    FROM carrito c
                    JOIN producto p ON c.id_producto = p.id_producto
                    JOIN categoria cat ON p.id_categoria = cat.id_categoria
                    WHERE c.id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Resultados del carrito para cliente $id_cliente: " . print_r($resultados, true));
            
            return $resultados;
        } catch (PDOException $e) {
            error_log("Error al obtener carrito: " . $e->getMessage());
            return [];
        }
    }

    public function agregarAlCarrito($id_cliente, $id_producto, $cantidad = 1) {
        try {
            // Verificar si el producto ya está en el carrito
            $query = "SELECT id_carrito, cantidad FROM carrito 
                      WHERE id_cliente = ? AND id_producto = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente, $id_producto]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                // Actualizar cantidad si ya existe
                $nueva_cantidad = $existe['cantidad'] + $cantidad;
                $query = "UPDATE carrito SET cantidad = ? WHERE id_carrito = ?";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([$nueva_cantidad, $existe['id_carrito']]);
            } else {
                // Insertar nuevo registro
                $query = "INSERT INTO carrito (id_cliente, id_producto, cantidad, precio_unitario, fecha_agregado)
                          VALUES (?, ?, ?, (SELECT precio_unitario FROM producto WHERE id_producto = ?), NOW())";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([$id_cliente, $id_producto, $cantidad, $id_producto]);
            }
        } catch (PDOException $e) {
            error_log("Error al agregar al carrito: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarCantidad($id_carrito, $cantidad) {
        try {
            $query = "UPDATE carrito SET cantidad = ? WHERE id_carrito = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$cantidad, $id_carrito]);
        } catch (PDOException $e) {
            error_log("Error al actualizar cantidad en carrito: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarDelCarrito($id_carrito) {
        try {
            $query = "DELETE FROM carrito WHERE id_carrito = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id_carrito]);
        } catch (PDOException $e) {
            error_log("Error al eliminar del carrito: " . $e->getMessage());
            return false;
        }
    }

    public function vaciarCarrito($id_cliente) {
        try {
            $query = "DELETE FROM carrito WHERE id_cliente = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id_cliente]);
        } catch (PDOException $e) {
            error_log("Error al vaciar carrito: " . $e->getMessage());
            return false;
        }
    }

    public function contarProductos($id_cliente) {
        try {
            $query = "SELECT SUM(cantidad) as total FROM carrito WHERE id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error al contar productos en carrito: " . $e->getMessage());
            return 0;
        }
    }

    public function calcularSubtotal($id_cliente) {
        try {
            $query = "SELECT SUM(cantidad * precio_unitario) as subtotal 
                      FROM carrito WHERE id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['subtotal'] ?? 0.00;
        } catch (PDOException $e) {
            error_log("Error al calcular subtotal: " . $e->getMessage());
            return 0.00;
        }
    }

    public function procesarPago($id_cliente, $tipo_venta, $id_metodo, $id_empleado = null) {
        $this->db->beginTransaction();
        
        try {
            // Bloquear registros para evitar condiciones de carrera
            $query = "SELECT c.id_producto, c.cantidad, p.precio_unitario, p.nombre, p.stock 
                    FROM carrito c 
                    JOIN producto p ON c.id_producto = p.id_producto 
                    WHERE c.id_cliente = ? FOR UPDATE";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($items)) {
                throw new Exception('El carrito está vacío');
            }
            
            // Calcular total
            $subtotal = array_reduce($items, function($sum, $item) {
                return $sum + ($item['cantidad'] * $item['precio_unitario']);
            }, 0);
            
            $total = $subtotal + 15.00; // Costo de envío
            
            // Crear factura
            $query = "INSERT INTO factura (tipo_venta, id_cliente, id_empleado, total, id_metodo, fecha) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$tipo_venta, $id_cliente, $id_empleado, $total, $id_metodo]);
            $id_factura = $this->db->lastInsertId();
            
            // Registrar detalles y actualizar stock
            foreach ($items as $item) {
                // Insertar detalle
                $query = "INSERT INTO detalle_factura (id_factura, id_producto, cantidad, precio_unitario)
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id_factura, $item['id_producto'], $item['cantidad'], $item['precio_unitario']]);
                
                // Actualizar stock
                $query = "UPDATE producto SET stock = stock - ? WHERE id_producto = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$item['cantidad'], $item['id_producto']]);
            }
            
            // Vaciar carrito
            $query = "DELETE FROM carrito WHERE id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            
            $this->db->commit();
            return $id_factura;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en transacción de pago: " . $e->getMessage());
            throw $e;
        }
    }

    private function actualizarStock($id_producto, $cantidad) {
        $query = "UPDATE producto SET stock = stock - ? WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$cantidad, $id_producto]);
    }
}
?>