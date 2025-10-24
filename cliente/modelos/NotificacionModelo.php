<?php
require_once 'config/config.php';

class NotificacionModelo {
    
    public function obtenerNotificaciones($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT n.*, 
                       DATE_FORMAT(n.fecha_creacion, '%H:%i') as hora,
                       CASE 
                         WHEN DATE(n.fecha_creacion) = CURDATE() THEN 'Hoy'
                         WHEN DATE(n.fecha_creacion) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 'Ayer'
                         ELSE DATE_FORMAT(n.fecha_creacion, '%d/%m/%Y')
                       END as fecha_formateada
                FROM notificaciones n 
                WHERE n.id_cliente = ? 
                ORDER BY n.leida ASC, n.fecha_creacion DESC 
                LIMIT 20";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notificaciones = [];
        while ($row = $result->fetch_assoc()) {
            $notificaciones[] = $row;
        }
        
        $stmt->close();
        $conexion->close();
        
        return $notificaciones;
    }
    
    public function obtenerCantidadNoLeidas($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT COUNT(*) as total 
                FROM notificaciones 
                WHERE id_cliente = ? AND leida = 0";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        $conexion->close();
        
        return $row['total'];
    }
    
    public function marcarComoLeida($idNotificacion, $idCliente) {
        $conexion = conectarBD();
        
        $sql = "UPDATE notificaciones 
                SET leida = 1 
                WHERE id_notificacion = ? AND id_cliente = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idNotificacion, $idCliente);
        $result = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $result;
    }
    
    public function marcarTodasComoLeidas($idCliente) {
        $conexion = conectarBD();
        
        $sql = "UPDATE notificaciones 
                SET leida = 1 
                WHERE id_cliente = ? AND leida = 0";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $result = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $result;
    }
    
    public function crearNotificacion($idCliente, $titulo, $descripcion, $tipo = 'pedido', $idPedido = null) {
        $conexion = conectarBD();
        
        $sql = "INSERT INTO notificaciones (id_cliente, titulo, descripcion, tipo, id_pedido) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isssi", $idCliente, $titulo, $descripcion, $tipo, $idPedido);
        $result = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $result;
    }
    
    // Metodo para crear notificaciones automaticas cuando cambia el estado del pedido
    public function crearNotificacionEstadoPedido($idPedido, $nuevoEstado) {
        $conexion = conectarBD();
        
        // Obtener informacion del pedido
        $sql = "SELECT p.id_pedido, p.id_cliente, p.total, c.usuario 
                FROM pedido p 
                JOIN cliente c ON p.id_cliente = c.id_cliente 
                WHERE p.id_pedido = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        
        $stmt->close();
        $conexion->close();
        
        if (!$pedido) return false;
        
        $titulo = '';
        $descripcion = '';
        
        switch($nuevoEstado) {
            case 'procesado':
                $titulo = 'Pedido en Proceso';
                $descripcion = "Tu pedido #{$pedido['id_pedido']} está siendo procesado. Te notificaremos cuando sea enviado.";
                break;
                
            case 'enviado':
                $titulo = '¡Pedido Enviado!';
                $descripcion = "Tu pedido #{$pedido['id_pedido']} ha sido enviado. Llegará pronto a tu dirección.";
                break;
                
            case 'entregado':
                $titulo = 'Pedido Entregado';
                $descripcion = "¡Excelente! Tu pedido #{$pedido['id_pedido']} ha sido entregado. ¡Gracias por tu compra!";
                break;
                
            case 'cancelado':
                $titulo = 'Pedido Cancelado';
                $descripcion = "Tu pedido #{$pedido['id_pedido']} ha sido cancelado. Si tienes dudas, contáctanos.";
                break;
                
            default:
                return false;
        }
        
        return $this->crearNotificacion($pedido['id_cliente'], $titulo, $descripcion, 'pedido', $idPedido);
    }
}
?>