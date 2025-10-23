<?php
require_once 'modelos/UsuarioModelo.php';

class UsuarioControlador {
    private $modelo;
    private $idCliente;
    
    public function __construct() {
        $this->modelo = new UsuarioModelo();
        // En una aplicación real, aquí verificaríamos la sesión del usuario
        // Por ahora simulamos que el usuario con ID 1 está logueado
        $this->idCliente = 1; // Esto vendría de la sesión
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'correo' => $_POST['correo'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion']
            ];
            
            $resultado = $this->modelo->actualizarPerfil($this->idCliente, $datos);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'mensaje' => 'Perfil actualizado correctamente']);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar el perfil']);
            }
        }
    }
    
    public function cerrarSesion() {
        header('Location: index.php?c=inicio');
        exit();
    }
}
?>