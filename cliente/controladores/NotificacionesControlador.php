<?php
require_once 'modelos/InicioModelo.php';

class NotificacionesControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new InicioModelo();
    }
    
    public function obtener() {
        // En una aplicación real, aquí obtendrías el ID del cliente de la sesión
        // Por ahora usaremos un ID fijo para pruebas
        $idCliente = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : 1;
        
        $notificaciones = $this->modelo->obtenerNotificaciones($idCliente);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'notificaciones' => $notificaciones,
            'totalNoLeidas' => $this->modelo->obtenerCantidadNoLeidas($idCliente)
        ]);
    }
    
    public function marcarLeida() {
        $idNotificacion = isset($_POST['id_notificacion']) ? $_POST['id_notificacion'] : null;
        
        // En una aplicación real, aquí obtendrías el ID del cliente de la sesión
        $idCliente = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : 1;
        
        if ($idNotificacion) {
            $resultado = $this->modelo->marcarComoLeida($idNotificacion, $idCliente);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $resultado,
                'totalNoLeidas' => $this->modelo->obtenerCantidadNoLeidas($idCliente)
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'ID de notificación no válido']);
        }
    }
    
    public function cantidadNoLeidas() {
        // En una aplicación real, aquí obtendrías el ID del cliente de la sesión
        $idCliente = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : 1;
        
        $total = $this->modelo->obtenerCantidadNoLeidas($idCliente);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'totalNoLeidas' => $total
        ]);
    }
}
?>