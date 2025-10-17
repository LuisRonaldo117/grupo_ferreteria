<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$subtotal = $_GET['subtotal'] ?? 0;
$envio = $_GET['envio'] ?? 0;
$total = $_GET['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f9ff;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Seleccione Método de Pago</h4>
                    </div>
                    <div class="card-body">
                        <form id="formPago" action="<?= BASE_URL ?>cliente/?ruta=procesar_pago" method="POST">
                            <!-- Campos ocultos para los totales -->
                            <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                            <input type="hidden" name="envio" value="<?= $envio ?>">
                            <input type="hidden" name="total" value="<?= $total ?>">

                            <div class="mb-4">
                                <h5>Método de Pago</h5>
                                <select class="form-select" name="metodo_pago" required>
                                    <option value="">Seleccione un método</option>
                                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="qr">Pago con QR</option>
                                </select>
                            </div>

                            <!-- Resumen del pedido -->
                            <div class="border-top pt-3">
                                <h5>Resumen del Pedido</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Bs. <?= number_format($subtotal, 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Envío:</span>
                                    <span>Bs. <?= number_format($envio, 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span>Bs. <?= number_format($total, 2) ?></span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    Confirmar Pago
                                </button>
                                <a href="<?= BASE_URL ?>cliente/?ruta=carrito" class="btn btn-outline-secondary mt-2 w-100">
                                    Volver al Carrito
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
document.getElementById('formPago').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const btnSubmit = form.querySelector('button[type="submit"]');
    
    // Deshabilitar botón y mostrar carga
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    
    try {
        const formData = new FormData(form);
        
        // Enviar datos
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        // Verificar si es una redirección
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }
        
        // Si no es redirección, procesar como JSON
        const data = await response.json();
        
        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            window.location.href = '<?= BASE_URL ?>cliente/?ruta=carrito&pago_exitoso=1';
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar el pago');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = 'Confirmar Pago';
    }
});
</script>
</html>