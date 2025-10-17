<?php
// Verificar que tenemos los datos necesarios
if (!isset($factura)) {
    die("Error: No se pudo cargar la información de la factura");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmacion de Pago</title>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">¡Pago Exitoso!</h4>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="mb-3">Gracias por tu compra</h3>
                        <p class="lead">Tu pedido ha sido procesado exitosamente.</p>
                        
                        <div class="border rounded p-3 mb-4 text-start">
                            <h5 class="mb-3">Detalles de la compra</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Número de factura:</span>
                                <span class="fw-bold">#<?= str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Fecha:</span>
                                <span><?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></span>
                            </div>
                             <div class="d-flex justify-content-between mb-2">
                                <span>Método de pago:</span>
                                <span><?= htmlspecialchars($factura['metodo_pago']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span>Bs. <?= number_format($factura['total'], 2) ?></span>
                            </div>
                        </div>

                        <div class="alert alert-success">
                            <h4>¡Pago exitoso!</h4>
                            <p>Número de factura: #<?= str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT) ?></p>
                            <p>Total: Bs. <?= number_format($factura['total'], 2) ?></p>
                            <p>Fecha: <?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></p>
                            
                            <div class="mt-3">
                                <a href="<?= BASE_URL ?>cliente/?ruta=generar_factura&id=<?= $factura['id_factura'] ?>" 
                                class="btn btn-primary">
                                    <i class="fas fa-file-pdf"></i> Descargar Factura
                                </a>
                                <a href="<?= BASE_URL ?>cliente/?ruta=catalogo" class="btn btn-outline-secondary">
                                    <i class="fas fa-shopping-bag"></i> Seguir Comprando
                                </a>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-block mt-4">
                            <a href="<?= BASE_URL ?>cliente/?ruta=generar_factura&id=<?= $factura['id_factura'] ?>" 
                            class="btn btn-primary me-md-2">
                                <i class="fas fa-file-pdf me-2"></i> Descargar Factura
                            </a>
                            <a href="<?= BASE_URL ?>cliente/?ruta=carrito" class="btn btn-outline-primary me-md-2">
                                <i class="fas fa-shopping-cart me-2"></i> Volver al Carrito
                            </a>
                            <a href="<?= BASE_URL ?>cliente/?ruta=catalogo" class="btn btn-outline-secondary">
                                <i class="fas fa-shopping-bag me-2"></i> Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function procesarPago() {
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked')?.value;
            if (!metodoPago) {
                mostrarMensaje('Por favor selecciona un método de pago', 'error');
                return;
            }

            const btnPagar = document.getElementById('btn-pagar');
            btnPagar.disabled = true;
            btnPagar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

            try {
                const response = await fetch('carrito/?accion=procesar_pago', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_metodo=${metodoPago}`
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error en la solicitud');
                }

                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    
                    // Actualizar vista del carrito
                    if (data.empty_cart) {
                        document.getElementById('lista-carrito').innerHTML = `
                            <div class="alert alert-info">Tu carrito está vacío</div>
                        `;
                        document.getElementById('resumen-pago').style.display = 'none';
                        actualizarContadorCarrito(0);
                    }
                    
                    // Mostrar comprobante
                    mostrarComprobante(data.id_factura);
                } else {
                    mostrarMensaje(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje(error.message || 'Error al procesar el pago', 'error');
            } finally {
                btnPagar.disabled = false;
                btnPagar.innerHTML = 'Pagar ahora';
            }
        }

        function mostrarMensaje(mensaje, tipo = 'success') {
            const divMensaje = document.createElement('div');
            divMensaje.className = `alert alert-${tipo} fixed-top mt-3 mx-auto w-75`;
            divMensaje.style.zIndex = '1100';
            divMensaje.textContent = mensaje;
            
            document.body.appendChild(divMensaje);
            
            setTimeout(() => {
                divMensaje.remove();
            }, 5000);
        }

        function actualizarContadorCarrito(cantidad) {
            const contador = document.getElementById('contador-carrito');
            if (contador) {
                contador.textContent = cantidad;
                contador.style.display = cantidad > 0 ? 'inline-block' : 'none';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>