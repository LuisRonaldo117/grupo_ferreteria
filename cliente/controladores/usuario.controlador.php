<?php

require_once MODELS_DIR . 'usuario.modelo.php';

class UsuarioControlador {
    private $db;
    private $modelo;

    public function __construct($db, $modelo = null) {
        $this->db = $db;
        $this->modelo = $modelo ?: new UsuarioModelo($db);
    }

    public function mostrarUsuario() {
        if (!isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit();
        }

        // Manejar acciones específicas
        if (isset($_GET['accion'])) {
            switch ($_GET['accion']) {
                case 'actualizar':
                    $this->actualizarUsuario();
                    break;
                default:
                    break;
            }
        }

        $id_cliente = $_SESSION['id_cliente'];
        $datosCliente = $this->modelo->obtenerDatosCliente($id_cliente);
        $pedidos = $this->modelo->obtenerPedidosCliente($id_cliente);

        // Debug para verificar datos
        error_log("Datos del cliente obtenidos: " . print_r($datosCliente, true));

        require_once VIEWS_DIR . 'usuario.php';
    }

    public function actualizarUsuario() {
        if (!isset($_SESSION['id_cliente'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
            exit();
        }

        $id_cliente = $_SESSION['id_cliente'];
        
        // Obtener datos
        $datos = [
            'nombres' => trim($_POST['nombres'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'correo' => trim($_POST['correo'] ?? '')
        ];

        // Debug para verificar datos recibidos
        error_log("Datos recibidos para actualizar: " . print_r($datos, true));
        error_log("ID Cliente: " . $id_cliente);

        // Validaciones
        if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['correo'])) {
            $_SESSION['error'] = 'Nombre, apellido y correo son obligatorios';
            header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
            exit();
        }

        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Correo electrónico no válido';
            header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
            exit();
        }

        // Intentar actualizar
        $resultado = $this->modelo->actualizarDatosCliente($id_cliente, $datos);
        
        if ($resultado) {
            // Actualizar datos en sesión
            $_SESSION['nombre_cliente'] = $datos['nombres'];
            $_SESSION['apellido_cliente'] = $datos['apellidos'];
            $_SESSION['email_cliente'] = $datos['correo'];
            $_SESSION['telefono_cliente'] = $datos['telefono'];
            $_SESSION['direccion_cliente'] = $datos['direccion'];
            
            $_SESSION['mensaje'] = 'Datos actualizados correctamente';
            error_log("Actualización exitosa para cliente ID: " . $id_cliente);
        } else {
            $_SESSION['error'] = 'Error al actualizar los datos. Por favor, inténtalo de nuevo.';
            error_log("Error al actualizar datos para cliente ID: " . $id_cliente);
        }

        header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
        exit();
    }

    public function mostrarDetallePedido() {
        if (!isset($_SESSION['id_cliente']) || !isset($_GET['id'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit;
        }
        
        $id_pedido = (int)$_GET['id'];
        $id_cliente = $_SESSION['id_cliente'];
        
        // Verificar que el pedido pertenece al cliente
        $pedido = $this->modelo->obtenerPedido($id_pedido, $id_cliente);
        
        if (!$pedido) {
            $_SESSION['error'] = 'Pedido no encontrado';
            header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
            exit;
        }
        
        $detalles = $this->modelo->obtenerDetallesPedido($id_pedido);
        
        require_once VIEWS_DIR . 'detalle_pedido.php';
    }
}
?>