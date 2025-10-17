<?php
// Verificar que los datos necesarios estén presentes
if (!isset($pedido) || !isset($detalles)) {
    header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido #<?= $pedido['id_pedido'] ?> | Ferretería</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        .order-detail-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        .order-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .order-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-processing {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="order-detail-container">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>Detalle del Pedido #FERR-<?= str_pad($pedido['id_pedido'], 5, '0', STR_PAD_LEFT) ?></h2>
                            <span class="status-badge <?= 
                                $pedido['estado'] == 'entregado' ? 'status-completed' : 
                                ($pedido['estado'] == 'cancelado' ? 'status-cancelled' : 'status-processing') 
                            ?>">
                                <?= ucfirst($pedido['estado']) ?>
                            </span>
                        </div>
                        <p class="text-muted mb-1">Fecha: <?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></p>
                        <p class="text-muted">Total: Bs. <?= number_format($pedido['total'], 2) ?></p>
                    </div>

                    <h5 class="mb-3">Productos</h5>
                    
                    <?php foreach ($detalles as $detalle): ?>
                    <div class="order-item">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="<?= BASE_URL ?>imagenes/<?= basename($detalle['imagen'] ?? 'default.jpg') ?>" 
                                     class="product-img" alt="<?= $detalle['nombre_producto'] ?>">
                            </div>
                            <div class="col-md-5">
                                <h6 class="mb-1"><?= $detalle['nombre_producto'] ?></h6>
                            </div>
                            <div class="col-md-2">
                                <p class="mb-0">Cantidad: <?= $detalle['cantidad'] ?></p>
                            </div>
                            <div class="col-md-3 text-end">
                                <p class="mb-0">Bs. <?= number_format($detalle['precio_unitario'], 2) ?> c/u</p>
                                <p class="mb-0 fw-bold">Total: Bs. <?= number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="mt-4 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Método de pago</h5>
                                <p><?= ucfirst($pedido['tipo_pago']) ?></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5>Total del pedido</h5>
                                <p class="fw-bold fs-4">Bs. <?= number_format($pedido['total'], 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>cliente/?ruta=usuario" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a mis pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>