<?php
require_once 'modelos/UsuarioModelo.php';

class UsuarioControlador {
    private $modelo;
    private $idCliente;
    
    public function __construct() {
        $this->modelo = new UsuarioModelo();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['id_cliente']) || $_SESSION['rol'] !== 'cliente') {
            header('Location: index.php');
            exit();
        }
        
        $this->idCliente = $_SESSION['id_cliente'];
    }
    
    public function index() {
        $usuario = $this->modelo->obtenerUsuario($this->idCliente);
        $pedidos = $this->modelo->obtenerPedidos($this->idCliente);
        
        $datos = [
            'usuario' => $usuario,
            'pedidos' => $pedidos,
            'seccion_activa' => 'perfil'
        ];
        
        cargarVista('usuario/perfil', $datos);
    }
    
    public function pedidos() {
        $usuario = $this->modelo->obtenerUsuario($this->idCliente);
        $pedidos = $this->modelo->obtenerPedidos($this->idCliente);
        
        $datos = [
            'usuario' => $usuario,
            'pedidos' => $pedidos,
            'seccion_activa' => 'pedidos'
        ];
        
        cargarVista('usuario/perfil', $datos);
    }
    
    public function detallePedido() {
        $idPedido = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($idPedido) {
            $pedido = $this->modelo->obtenerDetallePedido($idPedido, $this->idCliente);
            
            if ($pedido) {
                $datos = [
                    'pedido' => $pedido
                ];
                cargarVista('usuario/detalle_pedido', $datos);
                return;
            }
        }
        
        header('Location: index.php?c=usuario&a=pedidos');
    }
    
    public function actualizarPerfil() {
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [];
        
        try {
            if (empty($_POST['nombres']) || empty($_POST['apellidos']) || empty($_POST['correo'])) {
                throw new Exception('Faltan campos requeridos');
            }
            
            $datos = [
                'nombres' => trim($_POST['nombres']),
                'apellidos' => trim($_POST['apellidos']),
                'correo' => trim($_POST['correo']),
                'telefono' => isset($_POST['telefono']) ? trim($_POST['telefono']) : '',
                'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : ''
            ];
            
            if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El formato del correo electrónico no es válido');
            }
            
            $resultado = $this->modelo->actualizarPerfil($this->idCliente, $datos);
            
            if ($resultado) {
                $_SESSION['nombre_cliente'] = $datos['nombres'];
                $_SESSION['apellido_cliente'] = $datos['apellidos'];
                
                $response = [
                    'success' => true, 
                    'mensaje' => 'Perfil actualizado correctamente'
                ];
            } else {
                $response = [
                    'success' => false, 
                    'mensaje' => 'No se pudo actualizar el perfil. Verifique los datos e intente nuevamente.'
                ];
            }
            
        } catch (Exception $e) {
            $response = [
                'success' => false, 
                'mensaje' => $e->getMessage()
            ];
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    public function cerrarSesion() {
        echo "<script>
            localStorage.removeItem('carrito_ferreteria');
        </script>";
        
        session_destroy();
        header('Location: http://localhost/grupo_ferreteria/');
        exit();
    }
}
?>