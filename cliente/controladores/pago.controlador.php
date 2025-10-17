<?php

require_once __DIR__ . '/../config/paths.php';
require_once MODELS_DIR . 'carrito.modelo.php';
require_once MODELS_DIR . 'factura.modelo.php';
require_once __DIR__ . '/../../vendor/autoload.php'; 
use Dompdf\Dompdf;

class PagoControlador {
    private $db;
    private $modelo;
    private $modeloCarrito;
    private $modeloFactura;

    public function __construct($db, $modelo) {
        $this->db = $db;
        $this->modelo = $modelo;
        $this->modeloCarrito = new CarritoModelo($db);
        $this->modeloFactura = new FacturaModelo($db);
    }

    // Método para mostrar el formulario de pago
    public function mostrarFormularioPago() {
        // Verificar sesión
        if (!isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=login');
            exit;
        }

        $subtotal = $_GET['subtotal'] ?? 0;
        $envio = $_GET['envio'] ?? 0;
        $total = $_GET['total'] ?? 0;
        
        // Si no vienen por GET, obtener del carrito
        if ($total == 0) {
            $id_cliente = $_SESSION['id_cliente'];
            $datosCarrito = $this->modeloCarrito->obtenerCarrito($id_cliente);

            $subtotal = $datosCarrito['subtotal'] ?? 0;
            $envio = $datosCarrito['envio'] ?? 0;
            $total = $datosCarrito['total'] ?? 0;
        }

        require_once VIEWS_DIR . 'pago_form.php';
    }

    // Método para mostrar la confirmación de pago
    public function mostrarConfirmacion() {
        if (!isset($_SESSION['id_factura']) || !isset($_SESSION['pago_exitoso'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        $factura = $this->modeloFactura->obtenerFactura($_SESSION['id_factura']);
        require_once VIEWS_DIR . 'confirmacion_pago.php';
    }

    // Método para verificar el pago
    public function verificarPago() {
        if (!isset($_GET['id_factura'])) {
            echo json_encode(['success' => false]);
            exit;
        }

        $id_factura = $_GET['id_factura'];
        sleep(2);
        
        // Actualizar estado de la factura
        $success = $this->modeloFactura->actualizarEstadoFactura($id_factura, 'pendiente');
        
        echo json_encode(['success' => $success]);
        exit;
    }

    public function generarFacturaPDF() {
        if (!isset($_SESSION['id_factura'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        // Obtener datos de la factura
        $id_factura = $_SESSION['id_factura'];
        $factura = $this->modeloFactura->obtenerFactura($id_factura);
        $detalles = $this->modeloFactura->obtenerDetallesFactura($id_factura);
        $cliente = $this->modeloFactura->obtenerCliente($factura['id_cliente']);

        if (!$factura || !$cliente) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
            exit;
        }

        ob_start();
        require_once VIEWS_DIR . 'factura_template.php';
        $html = ob_get_clean();
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        // Renderizar el PDF
        $dompdf->render();
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="factura_'.$id_factura.'.pdf"');

        echo $dompdf->output();
        exit;
    }
}
?>