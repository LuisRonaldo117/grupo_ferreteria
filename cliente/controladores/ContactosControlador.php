<?php
require_once 'modelos/ContactosModelo.php';

class ContactosControlador {
    private $modelo;
    private $idCliente;
    
    public function __construct() {
        $this->modelo = new ContactosModelo();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->idCliente = isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : null;
    }
    
    public function index() {
        // Obtener datos del usuario si está logueado
        $usuario = null;
        if ($this->idCliente) {
            $usuario = $this->modelo->obtenerUsuario($this->idCliente);
        }
        
        $datos = $this->modelo->obtenerInformacion();
        $datos['usuario'] = $usuario;
        
        cargarVista('contactos/index', $datos);
    }
    
    public function enviarMensaje() {
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
            exit();
        }
        
        try {
            // Validar campos requeridos
            $camposRequeridos = ['nombre', 'email', 'asunto', 'mensaje'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                    echo json_encode(['success' => false, 'mensaje' => 'El campo ' . $campo . ' es requerido']);
                    exit();
                }
            }
            
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'asunto' => trim($_POST['asunto']),
                'mensaje' => trim($_POST['mensaje']),
                'id_cliente' => $this->idCliente
            ];
            
            // Validar formato de email
            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'mensaje' => 'El formato del correo electrónico no es válido']);
                exit();
            }
            
            $resultado = $this->modelo->guardarReclamo($datos);
            
            if ($resultado) {
                echo json_encode([
                    'success' => true, 
                    'mensaje' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'mensaje' => 'Error al enviar el mensaje. Por favor, intente nuevamente.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        
        exit();
    }
}
?>