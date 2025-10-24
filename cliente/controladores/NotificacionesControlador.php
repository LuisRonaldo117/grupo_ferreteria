<?php
require_once 'modelos/NotificacionModelo.php';

class NotificacionesControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new NotificacionModelo();
    }
    
    public function obtener() {
        session_start();
        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'error' => 'No autenticado']);
            return;
        }
        
        $idCliente = $_SESSION['id_cliente'];
        $notificaciones = $this->modelo->obtenerNotificaciones($idCliente);
        
        // Formatear las notificaciones para la vista
        $notificacionesFormateadas = [];
        foreach ($notificaciones as $notif) {
            $notificacionesFormateadas[] = [
                'id' => $notif['id_notificacion'],
                'titulo' => $notif['titulo'],
                'descripcion' => $notif['descripcion'],
                'tiempo' => $this->formatearTiempo($notif['fecha_creacion']),
                'leida' => (bool)$notif['leida'],
                'tipo' => $notif['tipo'],
                'fecha' => $notif['fecha_formateada'],
                'hora' => $notif['hora']
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'notificaciones' => $notificacionesFormateadas,
            'totalNoLeidas' => $this->modelo->obtenerCantidadNoLeidas($idCliente)
        ]);
    }
    
    public function marcarLeida() {
        session_start();
        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'error' => 'No autenticado']);
            return;
        }
        
        $idNotificacion = isset($_POST['id_notificacion']) ? intval($_POST['id_notificacion']) : null;
        $idCliente = $_SESSION['id_cliente'];
        
        if ($idNotificacion) {
            $resultado = $this->modelo->marcarComoLeida($idNotificacion, $idCliente);
            
            echo json_encode([
                'success' => $resultado,
                'totalNoLeidas' => $this->modelo->obtenerCantidadNoLeidas($idCliente)
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de notificación no válido']);
        }
    }
    
    public function marcarTodasLeidas() {
        session_start();
        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'error' => 'No autenticado']);
            return;
        }
        
        $idCliente = $_SESSION['id_cliente'];
        $resultado = $this->modelo->marcarTodasComoLeidas($idCliente);
        
        echo json_encode([
            'success' => $resultado,
            'totalNoLeidas' => 0
        ]);
    }
    
    public function cantidadNoLeidas() {
        session_start();
        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'totalNoLeidas' => 0]);
            return;
        }
        
        $idCliente = $_SESSION['id_cliente'];
        $total = $this->modelo->obtenerCantidadNoLeidas($idCliente);
        
        echo json_encode([
            'success' => true,
            'totalNoLeidas' => $total
        ]);
    }
    
    private function formatearTiempo($fecha) {
        $ahora = new DateTime();
        $fechaNotif = new DateTime($fecha);
        $diferencia = $ahora->diff($fechaNotif);
        
        if ($diferencia->d > 0) {
            return "Hace {$diferencia->d} día" . ($diferencia->d > 1 ? 's' : '');
        } elseif ($diferencia->h > 0) {
            return "Hace {$diferencia->h} hora" . ($diferencia->h > 1 ? 's' : '');
        } elseif ($diferencia->i > 0) {
            return "Hace {$diferencia->i} minuto" . ($diferencia->i > 1 ? 's' : '');
        } else {
            return 'Hace unos momentos';
        }
    }
}
?>