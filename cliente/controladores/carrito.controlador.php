<?php

require_once __DIR__ . '/../config/paths.php';
require_once MODELS_DIR . 'carrito.modelo.php';
require_once MODELS_DIR . 'factura.modelo.php';
require_once __DIR__ . '/../../vendor/autoload.php'; 
use Dompdf\Dompdf;

class CarritoControlador {
    private $modelo;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->modelo = new CarritoModelo($db);
    }

    public function mostrarCarrito() {
        // Verificar sesión
        if (!isset($_SESSION['id_cliente'])) {
            return ['carrito' => [], 'subtotal' => 0, 'envio' => 0, 'total' => 0, 'total_productos' => 0];
        }

        $id_cliente = $_SESSION['id_cliente'];
        $carrito = $this->modelo->obtenerCarrito($id_cliente);
        $subtotal = $this->modelo->calcularSubtotal($id_cliente);
        $total_productos = $this->modelo->contarProductos($id_cliente);
        
        // Costo de envío fijo
        $envio = 15.00;
        $total = $subtotal + $envio;

        // Obtener última factura si existe
        $ultima_factura = null;
        if (isset($_SESSION['ultima_factura'])) {
            $ultima_factura = $_SESSION['ultima_factura'];
        }

        return [
            'carrito' => $carrito,
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $total,
            'total_productos' => $total_productos,
            'ultima_factura' => $ultima_factura
        ];
    }

    public function manejarAccion() {
        // Verificar sesión primero
        if (!isset($_SESSION['id_cliente'])) {
            if ($this->esAjax()) {
                echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
                exit;
            } else {
                header('Location: ' . BASE_URL . 'cliente/?ruta=login');
                exit;
            }
        }

        $accion = $_GET['accion'] ?? '';
        $id_cliente = $_SESSION['id_cliente'];

        switch ($accion) {
            case 'agregar':
                $this->agregarProducto($id_cliente);
                break;
                
            case 'actualizar':
                $this->actualizarCantidad($id_cliente);
                break;
                
            case 'eliminar':
                $this->eliminarProducto($id_cliente);
                break;
                
            case 'vaciar':
                $this->vaciarCarrito($id_cliente);
                break;
            case 'procesar_pago':
                $this->procesarPago();
                break;
            case 'contar':
                $total_productos = $this->modelo->contarProductos($id_cliente);
                echo json_encode(['success' => true, 'total_productos' => $total_productos]);
                exit;
            default:
            if (!$this->esAjax()) {
                $datos = $this->mostrarCarrito();
                extract($datos);                   
                require_once VIEWS_DIR . 'carrito.php';
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
                exit;
            }
        }
    }

    private function esAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    private function agregarProducto($id_cliente) {
        if (!isset($_POST['id_producto'])) {
            echo json_encode(['success' => false, 'message' => 'Producto no especificado']);
            exit();
        }

        $id_producto = (int)$_POST['id_producto'];
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($this->modelo->agregarAlCarrito($id_cliente, $id_producto, $cantidad)) {
            $total_productos = $this->modelo->contarProductos($id_cliente);
            echo json_encode([
                'success' => true,
                'total_productos' => $total_productos,
                'message' => 'Producto añadido al carrito'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar al carrito']);
        }
        exit();
    }

    private function actualizarCantidad($id_cliente) {
        if (!isset($_POST['id_carrito']) || !isset($_POST['cantidad'])) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit();
        }

        $id_carrito = (int)$_POST['id_carrito'];
        $cantidad = (int)$_POST['cantidad'];

        if ($cantidad < 1) {
            echo json_encode(['success' => false, 'message' => 'Cantidad no válida']);
            exit();
        }

        if ($this->modelo->actualizarCantidad($id_carrito, $cantidad)) {
            $subtotal = $this->modelo->calcularSubtotal($id_cliente);
            $total_productos = $this->modelo->contarProductos($id_cliente);
            echo json_encode([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
                'total' => number_format($subtotal + 15.00, 2),
                'total_productos' => $total_productos
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar cantidad']);
        }
        exit();
    }

    private function eliminarProducto($id_cliente) {
        if (!isset($_POST['id_carrito'])) {
            echo json_encode(['success' => false, 'message' => 'Producto no especificado']);
            exit();
        }

        $id_carrito = (int)$_POST['id_carrito'];

        if ($this->modelo->eliminarDelCarrito($id_carrito)) {
            $subtotal = $this->modelo->calcularSubtotal($id_cliente);
            $total_productos = $this->modelo->contarProductos($id_cliente);
            echo json_encode([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
                'total' => number_format($subtotal + 15.00, 2),
                'total_productos' => $total_productos
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar producto']);
        }
        exit();
    }

    private function vaciarCarrito($id_cliente) {
        if ($this->modelo->vaciarCarrito($id_cliente)) {
            echo json_encode([
                'success' => true,
                'total_productos' => 0
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al vaciar carrito']);
        }
        exit();
    }

    private function verificarSesion() {
        if (!isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit();
        }
    }

    public function procesarPago() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
            exit;
        }

        $id_cliente = $_SESSION['id_cliente'];
        $metodo_pago = $_POST['metodo_pago'] ?? '';
        $total = floatval($_POST['total'] ?? 0);

        // Validaciones básicas
        if (!in_array($metodo_pago, ['tarjeta', 'efectivo', 'qr'])) {
            echo json_encode(['success' => false, 'message' => 'Método de pago no válido']);
            exit;
        }

        if ($total <= 0) {
            echo json_encode(['success' => false, 'message' => 'Total inválido']);
            exit;
        }

        try {
            // Obtener productos del carrito
            $carrito = $this->modelo->obtenerCarrito($id_cliente);
            if (empty($carrito)) {
                echo json_encode(['success' => false, 'message' => 'El carrito está vacío']);
                exit;
            }

            // Crear factura
            $facturaModelo = new FacturaModelo($this->db);
            $id_factura = $facturaModelo->crearFactura([
                'id_cliente' => $id_cliente,
                'total' => $total,
                'metodo_pago' => $metodo_pago,
                'productos' => $carrito,
                'estado' => 'pendiente'
            ]);

            if ($id_factura) {
                // Vaciar carrito
                $this->modelo->vaciarCarrito($id_cliente);
                
                // Guardar en sesión para mostrar en el carrito
                $_SESSION['ultima_factura'] = $id_factura;
                $_SESSION['pago_exitoso'] = true;

                // Respuesta simple con redirección
                echo json_encode([
                    'success' => true,
                    'redirect' => BASE_URL . 'cliente/?ruta=carrito&pago_exitoso=1'
                ]);
            } else {
                throw new Exception('Error al crear la factura');
            }

            // Registrar también como pedido
            if ($id_factura) {
                $facturaModelo = new FacturaModelo($this->db);
                $facturaModelo->registrarPedido($id_factura, $id_cliente);
            }

        } catch (Exception $e) {
            error_log("Error en procesarPago: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    private function generarVistaConfirmacion($id_factura) {
        $facturaModelo = new FacturaModelo($this->db);
        $factura = $facturaModelo->obtenerFactura($id_factura);
        $detalles = $facturaModelo->obtenerDetallesFactura($id_factura);
        
        ob_start();
        include VIEWS_DIR . 'partials/confirmacion_pago.php';
        return ob_get_clean();
    }

    public function mostrarFormularioPago() {
        // Verificar sesión
        if (!isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit;
        }

        // Obtener datos del carrito
        $id_cliente = $_SESSION['id_cliente'];
        $datosCarrito = $this->mostrarCarrito();
        
        // Si vienen parámetros por GET, usarlos (para mantener compatibilidad)
        $subtotal = $_GET['subtotal'] ?? $datosCarrito['subtotal'];
        $envio = $_GET['envio'] ?? $datosCarrito['envio'];
        $total = $_GET['total'] ?? $datosCarrito['total'];

        require_once VIEWS_DIR . 'pago_form.php';
    }

    public function mostrarConfirmacion() {
        if (!isset($_GET['id'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        $id_factura = $_GET['id'];
        $facturaModelo = new FacturaModelo($this->db);
        $factura = $facturaModelo->obtenerFactura($id_factura);
        
        if (!$factura) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        require_once VIEWS_DIR . 'confirmacion_pago.php';
    }

    public function generarFacturaPDF() {
        if (!isset($_GET['id'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        $id_factura = $_GET['id'];
        $facturaModelo = new FacturaModelo($this->db);
        
        $factura = $facturaModelo->obtenerFactura($id_factura);
        $detalles = $facturaModelo->obtenerDetallesFactura($id_factura);
        $cliente = $facturaModelo->obtenerCliente($factura['id_cliente']);
        
        if (!$factura || !$cliente) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        ob_start();
        include VIEWS_DIR . 'factura_template.php';
        $html = ob_get_clean();
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="factura_'.$id_factura.'.pdf"');
        echo $dompdf->output();
        exit;
    }

    public function mostrarMisCompras() {
        if (!isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit;
        }

        $id_cliente = $_SESSION['id_cliente'];
        $facturas = $this->obtenerFacturasCliente($id_cliente);

        require_once VIEWS_DIR . 'mis_compras.php';
    }

    private function obtenerFacturasCliente($id_cliente) {
        try {
            $query = "SELECT f.id_factura, f.fecha, f.total, f.estado,
                    COUNT(df.id_detalle) as cantidad_productos
                    FROM factura f
                    LEFT JOIN detalle_factura df ON f.id_factura = df.id_factura
                    WHERE f.id_cliente = ?
                    GROUP BY f.id_factura
                    ORDER BY f.fecha DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener facturas del cliente: " . $e->getMessage());
            return [];
        }
    }
}
?>