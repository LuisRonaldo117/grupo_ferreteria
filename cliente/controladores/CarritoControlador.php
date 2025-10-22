<?php
require_once 'modelos/FacturaModelo.php';

class CarritoControlador {
    private $facturaModelo;
    private $idCliente;
    
    public function __construct() {
        $this->facturaModelo = new FacturaModelo();
        $this->idCliente = 1;
    }
    
    public function index() {
        $datos = [
            'titulo' => 'Tu Carrito de Compras',
            'metodos_pago' => [
                'tarjeta' => 'Tarjeta de Crédito/Débito',
                'efectivo' => 'Efectivo',
                'qr' => 'Pago por QR'
            ]
        ];
        cargarVista('carrito/index', $datos);
    }
    
    public function procesarPago() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $metodo_pago = $_POST['metodo_pago'] ?? '';
            $total = $_POST['total'] ?? 0;
            
            // Obtener productos del carrito
            $carrito = json_decode($_POST['carrito'] ?? '[]', true);
            
            if (empty($carrito)) {
                echo json_encode([
                    'success' => false, 
                    'mensaje' => 'El carrito está vacío'
                ]);
                return;
            }
            
            // Preparar datos para la factura
            $datosFactura = [
                'id_cliente' => $this->idCliente,
                'total' => $total,
                'metodo_pago' => $metodo_pago,
                'estado' => 'completado',
                'productos' => $carrito
            ];
            
            // Crear factura en la bd
            $idFactura = $this->facturaModelo->crearFactura($datosFactura);
            
            if ($idFactura) {
                echo json_encode([
                    'success' => true, 
                    'mensaje' => '¡Pago procesado exitosamente!',
                    'numero_factura' => $idFactura,
                    'total' => $total,
                    'metodo_pago' => $metodo_pago
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'mensaje' => 'Error al procesar el pago'
                ]);
            }
        }
    }
    
    public function descargarFactura() {
        $idFactura = $_GET['id'] ?? null;
        
        if (!$idFactura) {
            die('ID de factura no proporcionado');
        }
        
        // Obtener datos de la factura
        $factura = $this->facturaModelo->obtenerFactura($idFactura);
        $detalles = $this->facturaModelo->obtenerDetallesFactura($idFactura);
        $cliente = $this->facturaModelo->obtenerCliente($factura['id_cliente']);
        
        if (!$factura || !$detalles || !$cliente) {
            die('Error al generar la factura');
        }
        
        // Generar pdf de la factura
        $this->generarPDFFactura($factura, $detalles, $cliente);
    }
    
    private function generarPDFFactura($factura, $detalles, $cliente) {
        // Usamos dompdf para generar el pdf
        require_once 'vendor/autoload.php';
        
        // Creamos el html de la factura
        $html = $this->generarHTMLFactura($factura, $detalles, $cliente);
        
        // Configuramos dompdf
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Descargar el pdf
        $dompdf->stream("factura-{$factura['id_factura']}.pdf", [
            "Attachment" => true
        ]);
    }
    
    private function generarHTMLFactura($factura, $detalles, $cliente) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Factura #<?= $factura['id_factura'] ?></title>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    margin: 0; 
                    padding: 0; 
                    color: #333; 
                    background-color: #f9f9f9;
                }
                .page {
                    max-width: 800px;
                    margin: 20px auto;
                    padding: 30px;
                    background: white;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                    border-radius: 5px;
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 30px; 
                    padding-bottom: 20px; 
                    border-bottom: 2px solid #0a2463;
                }
                .header h1 {
                    color: #0a2463;
                    margin: 0;
                    font-size: 28px;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                }
                .header h2 {
                    color: #3e5c76;
                    margin: 10px 0 0;
                    font-size: 20px;
                }
                .header p {
                    color: #5a6a7a;
                    margin: 5px 0;
                    font-size: 14px;
                }
                .info {
                    margin-bottom: 25px;
                    padding: 15px;
                    background: #f0f4f8;
                    border-radius: 5px;
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-between;
                }
                .info p {
                    margin: 8px 0;
                    flex-basis: 48%;
                    font-size: 14px;
                }
                .info strong {
                    color: #0a2463;
                }
                table {
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 25px;
                    font-size: 14px;
                }
                th {
                    background-color: #0a2463;
                    color: white;
                    padding: 12px 8px;
                    text-align: left;
                    font-weight: 500;
                }
                td { 
                    border-bottom: 1px solid #e0e0e0;
                    padding: 10px 8px; 
                    text-align: left; 
                }
                tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                .total {
                    text-align: right; 
                    font-weight: bold; 
                    margin-top: 20px;
                    padding: 15px;
                    background: #0a2463;
                    color: white;
                    border-radius: 5px;
                    font-size: 18px;
                }
                .footer {
                    margin-top: 40px; 
                    text-align: center; 
                    font-style: italic;
                    color: #5a6a7a;
                    font-size: 13px;
                    padding-top: 15px;
                    border-top: 1px solid #e0e0e0;
                }
                .invoice-number {
                    display: inline-block;
                    margin-top: 15px;
                    padding: 8px 20px;
                    background: #0a2463;
                    color: white;
                    border-radius: 30px;
                    font-size: 18px;
                    letter-spacing: 1px;
                }
                .envio-row td {
                    border-bottom: none;
                    background-color: #f8f9fa;
                    font-weight: bold;
                    color: #0a2463;
                }
                .empty-cell {
                    border: none;
                    background: transparent;
                }
            </style>
        </head>
        <body>
            <div class="page">
                <div class="header">
                    <h1>FERRETERÍA</h1>
                    <p>Dirección: Av. 16 de Julio #789, El Prado, La Paz, Bolivia</p>
                    <p>Teléfono: +591 78421686 | NIT: 123478466</p>
                    <div class="invoice-number">
                        FACTURA #<?= str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT) ?>
                    </div>
                </div>

                <div class="info">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></p>
                    <p><strong>Cliente:</strong> <?= $cliente['nombres'] ?> <?= $cliente['apellidos'] ?></p>
                    <p><strong>CI:</strong> <?= $cliente['ci'] ?></p>
                    <p><strong>Dirección:</strong> <?= $cliente['direccion'] ?></p>
                    <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
                    <p><strong>Método de pago:</strong> <?= $factura['metodo_pago'] ?></p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $detalle): ?>
                        <tr>
                            <td><?= $detalle['nombre_producto'] ?></td>
                            <td><?= $detalle['cantidad'] ?></td>
                            <td>Bs. <?= number_format($detalle['precio_unitario'], 2) ?></td>
                            <td>Bs. <?= number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="envio-row">
                            <td class="empty-cell"></td>
                            <td class="empty-cell"></td>
                            <td><strong>Costo de envío:</strong></td>
                            <td>Bs. <?= number_format(15, 2) ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="total">
                    <p><strong>TOTAL: Bs. <?= number_format($factura['total'], 2) ?></strong></p>
                </div>

                <div class="footer">
                    <p>¡Gracias por su compra!</p>
                    <p>Factura generada electrónicamente el <?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></p>
                    <p>Esta factura es válida como comprobante de venta</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
?>