<?php

class NotificacionModelo {
    
    public function obtenerNotificaciones($idUsuario) {
        // En una aplicación real, esto vendría de la base de datos
        // Por ahora usamos datos de ejemplo
        
        return [
            [
                'id' => 1,
                'titulo' => '¡Bienvenido a Ferretería!',
                'descripcion' => 'Gracias por registrarte. Disfruta de envío gratis en tu primera compra.',
                'tiempo' => 'Hace 2 horas',
                'leida' => false,
                'tipo' => 'bienvenida'
            ],
            [
                'id' => 2,
                'titulo' => 'Oferta Especial',
                'descripcion' => '20% de descuento en herramientas eléctricas esta semana.',
                'tiempo' => 'Hace 1 día',
                'leida' => false,
                'tipo' => 'promocion'
            ],
            [
                'id' => 3,
                'titulo' => 'Pedido enviado',
                'descripcion' => 'Tu pedido ha sido enviado y llegará en 2-3 días hábiles.',
                'tiempo' => 'Hace 3 días',
                'leida' => true,
                'tipo' => 'pedido'
            ]
        ];
    }
    
    public function marcarComoLeida($idNotificacion, $idUsuario) {
        // En una aplicación real, actualizaría la base de datos
        return true;
    }
    
    public function obtenerNumeroNotificacionesNoLeidas($idUsuario) {
        $notificaciones = $this->obtenerNotificaciones($idUsuario);
        $noLeidas = array_filter($notificaciones, function($notif) {
            return !$notif['leida'];
        });
        
        return count($noLeidas);
    }
}
?>