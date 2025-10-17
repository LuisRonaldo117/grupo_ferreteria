<?php

require_once MODELS_DIR . '/../modelos/notificacion.modelo.php';

class NotificacionControlador {
    private $modelo;
    private $db;

    public function __construct($db, $modelo = null) {
        $this->db = $db;
        $this->modelo = $modelo ?? new NotificacionModelo($db);
    }

    public function mostrarNotificacion() {
        if (!isset($_SESSION['id_cliente'])) {
            header('Location: ?ruta=login');
            exit;
        }

        $id_cliente = $_SESSION['id_cliente'];
        $notificacion = $this->modelo->obtenerNotificacion($id_cliente);

        $this->modelo->marcarComoLeidas($id_cliente);

        require_once VIEWS_DIR . 'notificacion.php';
    }

    public function contarNotificacion() {
        $id_cliente = $_SESSION['id_cliente'] ?? null;
        $total = $id_cliente ? $this->modelo->contarNotificacionNoLeidas($id_cliente) : 0;

        header('Content-Type: application/json');
        echo json_encode(['total_notificacion' => $total]);
        exit;
    }
}
?>