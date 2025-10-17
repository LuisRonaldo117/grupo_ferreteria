<?php
class UsuarioModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerDatosCliente($id_cliente) {
        try {
            $query = "SELECT c.*, p.nombres, p.apellidos, p.ci, p.fecha_nacimiento, 
                            p.direccion, p.telefono, p.correo, p.genero, p.id_departamento,
                            d.nom_departamento
                    FROM cliente c
                    JOIN persona p ON c.id_persona = p.id_persona
                    JOIN departamento d ON p.id_departamento = d.id_departamento
                    WHERE c.id_cliente = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("No se encontraron datos para el cliente con ID: $id_cliente");
                return false;
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Error al obtener datos del cliente: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarDatosCliente($id_cliente, $datos, $imagen = null) {
        try {
            // Obtener id_persona asociada al cliente
            $queryCheck = "SELECT id_persona FROM cliente WHERE id_cliente = ?";
            $stmtCheck = $this->db->prepare($queryCheck);
            $stmtCheck->execute([$id_cliente]);
            $id_persona = $stmtCheck->fetchColumn();
            
            if (!$id_persona) {
                throw new Exception("No se encontró el cliente");
            }

            // Preparar consulta de actualización
            $query = "UPDATE persona SET 
                     nombres = ?, 
                     apellidos = ?, 
                     telefono = ?, 
                     direccion = ?, 
                     correo = ?";
                     
            $params = [
                $datos['nombres'],
                $datos['apellidos'],
                $datos['telefono'],
                $datos['direccion'],
                $datos['correo']
            ];

            // Agregar imagen si existe
            if ($imagen) {
                $query .= ", imagen = ?";
                $params[] = $imagen;
            }

            $query .= " WHERE id_persona = ?";
            $params[] = $id_persona;

            // Ejecutar actualización
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute($params);

            // Debug para verificar
            error_log("Query ejecutada: " . $query);
            error_log("Parámetros: " . print_r($params, true));
            error_log("Filas afectadas: " . $stmt->rowCount());

            return $result && $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log("Error en actualizarDatosCliente: " . $e->getMessage());
            return false;
        } catch (PDOException $e) {
            error_log("Error PDO en actualizarDatosCliente: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPedidosCliente($id_cliente) {
        try {
            $query = "SELECT p.id_pedido, p.fecha_pedido, p.estado, p.total,
                    COUNT(dp.id_detalle) as productos
                    FROM pedido p
                    LEFT JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                    WHERE p.id_cliente = ?
                    GROUP BY p.id_pedido
                    ORDER BY p.fecha_pedido DESC
                    LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos del cliente: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerHistorialPedidos($id_cliente) {
        try {
            $query = "SELECT p.id_pedido, p.fecha_pedido, p.estado, p.total,
                    COUNT(dp.id_detalle) as productos
                    FROM pedido p
                    LEFT JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                    WHERE p.id_cliente = ?
                    GROUP BY p.id_pedido
                    ORDER BY p.fecha_pedido DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener historial de pedidos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPedido($id_pedido, $id_cliente) {
        try {
            $query = "SELECT * FROM pedido 
                    WHERE id_pedido = ? AND id_cliente = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_pedido, $id_cliente]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedido: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerDetallesPedido($id_pedido) {
        try {
            $query = "SELECT dp.*, p.nombre as nombre_producto, p.imagen
                    FROM detalle_pedido dp
                    JOIN producto p ON dp.id_producto = p.id_producto
                    WHERE dp.id_pedido = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_pedido]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles de pedido: " . $e->getMessage());
            return [];
        }
    }
}
?>