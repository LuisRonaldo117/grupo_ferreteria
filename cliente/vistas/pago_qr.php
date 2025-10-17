<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago QR</title>
</head>
<body>
    <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pago con QR</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div id="qrCode" class="mx-auto" style="width: 200px; height: 200px;"></div>
                    </div>
                    
                    <h5 class="mb-3">Escanea este código QR para completar tu pago</h5>
                    <p class="text-muted mb-4">Usa la aplicación de tu banco para escanear el código</p>
                    
                    <div class="border rounded p-3 mb-4 text-start">
                        <h5 class="mb-3">Detalles del pago</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total a pagar:</span>
                            <span class="fw-bold">Bs. <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Referencia:</span>
                            <span><?= substr($qr_data, 0, 15) ?>...</span>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> 
                        Tu pedido se completará una vez que recibamos la confirmación de pago.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button id="btnPagoVerificado" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i> Ya he pagado
                        </button>
                        <a href="<?= BASE_URL ?>cliente/?ruta=carrito" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Volver al carrito
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generar QR
    new QRCode(document.getElementById("qrCode"), {
        text: "<?= $qr_data ?>",
        width: 200,
        height: 200,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    
    // Verificar pago
    document.getElementById('btnPagoVerificado').addEventListener('click', function() {
        fetch('<?= BASE_URL ?>cliente/?ruta=verificar_pago&id_factura=<?= $id_factura ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>cliente/?ruta=confirmacion_pago';
                } else {
                    alert('Aún no hemos confirmado tu pago. Por favor intenta nuevamente más tarde.');
                }
            });
    });
});
</script>
</body>
</html>