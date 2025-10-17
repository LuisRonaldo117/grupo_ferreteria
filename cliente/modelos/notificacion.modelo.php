<?php

class NotificacionModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerNotificacion($id_cliente) {
        $notificacion = [];

        // Obtener pedidos como notificacion
        $query = "SELECT p.id_pedido, p.fecha_pedido, p.estado, p.total, 
                         s.nombre as sucursal, s.direccion as direccion_sucursal
                  FROM pedido p
                  JOIN sucursal s ON p.id_sucursal = s.id_sucursal
                  WHERE p.id_cliente = ?
                  ORDER BY p.fecha_pedido DESC";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pedidos as $pedido) {
                $notificacion[] = [
                    'id' => $pedido['id_pedido'],
                    'fecha' => $pedido['fecha_pedido'],
                    'estado' => $pedido['estado'],
                    'total' => $pedido['total'],
                    'sucursal' => $pedido['sucursal'],
                    'direccion_sucursal' => $pedido['direccion_sucursal'],
                    'tipo' => 'pedido',
                    'leido' => $pedido['estado'] === 'entregado' ? 1 : 0 // Entregado = leído
                ];
            }
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos: " . $e->getMessage());
        }

        $notificacion = array_merge($notificacion);

        // Ordenar por fecha descendente
        usort($notificacion, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        return $notificacion;
    }

    public function contarNotificacionNoLeidas($id_cliente) {
        $query = "SELECT COUNT(*) as total
                  FROM pedido p
                  WHERE p.id_cliente = ? AND p.estado IN ('pendiente', 'procesado', 'enviado')";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $total;
        } catch (PDOException $e) {
            error_log("Error al contar notificacion no leídas: " . $e->getMessage());
            return 0;
        }
    }

    public function marcarComoLeidas($id_cliente) {
        return true;
    }
}
?>