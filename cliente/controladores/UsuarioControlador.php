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
        
        // Usar el id real del cliente desde la sesión
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
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json');
        
        // Log para debugs
        error_log("=== ACTUALIZAR PERFIL INICIADO ===");
        error_log("Datos POST recibidos: " . print_r($_POST, true));
        error_log("ID Cliente: " . $this->idCliente);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Error: Método no permitido");
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
            exit();
        }
        
        try {
            // Validar que los campos requeridos esten presentes
            $camposRequeridos = ['nombres', 'apellidos', 'correo'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                    error_log("Error: Campo requerido faltante: " . $campo);
                    echo json_encode(['success' => false, 'mensaje' => 'El campo ' . $campo . ' es requerido']);
                    exit();
                }
            }
            
            $datos = [
                'nombres' => trim($_POST['nombres']),
                'apellidos' => trim($_POST['apellidos']),
                'correo' => trim($_POST['correo']),
                'telefono' => isset($_POST['telefono']) ? trim($_POST['telefono']) : '',
                'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : ''
            ];
            
            error_log("Datos procesados: " . print_r($datos, true));
            
            // Validar formato de email
            if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
                error_log("Error: Email inválido: " . $datos['correo']);
                echo json_encode(['success' => false, 'mensaje' => 'El formato del correo electrónico no es válido']);
                exit();
            }
            
            $resultado = $this->modelo->actualizarPerfil($this->idCliente, $datos);
            
            if ($resultado) {
                // Actualizar tambien los datos en la sesión
                $_SESSION['nombre_cliente'] = $datos['nombres'];
                $_SESSION['apellido_cliente'] = $datos['apellidos'];
                
                error_log("Perfil actualizado exitosamente");
                echo json_encode([
                    'success' => true, 
                    'mensaje' => 'Perfil actualizado correctamente'
                ]);
            } else {
                error_log("Error: No se pudo actualizar el perfil en el modelo");
                echo json_encode([
                    'success' => false, 
                    'mensaje' => 'Error al actualizar el perfil. Verifique los datos e intente nuevamente.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en actualizarPerfil: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
        
        error_log("=== ACTUALIZAR PERFIL FINALIZADO ===");
        error_log("JSON enviado: " . json_last_error_msg());
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