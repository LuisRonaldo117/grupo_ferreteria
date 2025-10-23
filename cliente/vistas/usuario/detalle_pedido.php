<section class="detalle-pedido">
    <div class="container">
        <div class="detalle-header">
            <a href="index.php?c=usuario&a=pedidos" class="btn-volver">
                ← Volver a Mis Pedidos
            </a>
            <h1>Detalle del Pedido #<?php echo $pedido['numero_pedido']; ?></h1>
        </div>
        
        <div class="detalle-layout">
            <!-- Informacion Principal -->
            <div class="detalle-principal">
                <div class="estado-pedido">
                    <div class="estado-badge estado-<?php echo $pedido['estado']; ?>">
                        <?php 
                        $estados = [
                            'pendiente' => 'Pendiente',
                            'procesado' => 'Procesado', 
                            'enviado' => 'Enviado',
                            'entregado' => 'Entregado',
                            'cancelado' => 'Cancelado'
                        ];
                        echo $estados[$pedido['estado']] ?? $pedido['estado'];
                        ?>
                    </div>
                    <div class="pedido-info">
                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                        <p><strong>Total:</strong> Bs. <?php echo number_format($pedido['total'], 2); ?></p>
                    </div>
                </div>

                <!-- Productos -->
                <div class="productos-seccion">
                    <h2>Productos</h2>
                    <div class="lista-productos">
                        <?php foreach ($pedido['detalles'] as $detalle): ?>
                            <div class="producto-item">
                                <div class="producto-info">
                                    <h3><?php echo htmlspecialchars($detalle['producto_nombre']); ?></h3>
                                    <p><?php echo htmlspecialchars($detalle['descripcion']); ?></p>
                                </div>
                                <div class="producto-detalle">
                                    <div class="producto-cantidad">
                                        Cantidad: <?php echo $detalle['cantidad']; ?>
                                    </div>
                                    <div class="producto-precios">
                                        <span class="precio-unitario">
                                            Bs. <?php echo number_format($detalle['precio_unitario'], 2); ?> c/u
                                        </span>
                                        <span class="precio-total">
                                            Total: Bs. <?php echo number_format($detalle['total'], 2); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="resumen-pedido">
                <div class="resumen-card">
                    <h3>Método de pago</h3>
                    <div class="metodo-pago">
                        <?php echo ucfirst($pedido['tipo_pago']); ?>
                    </div>
                    
                    <div class="resumen-total">
                        <h3>Total del pedido</h3>
                        <div class="total-monto">
                            Bs. <?php echo number_format($pedido['total'], 2); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .detalle-pedido {
        padding: 40px 0 80px;
        background: #f8f9fa;
        min-height: 80vh;
    }

    .detalle-header {
        margin-bottom: 40px;
    }

    .btn-volver {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 15px;
        transition: color 0.3s;
    }

    .btn-volver:hover {
        color: #2980b9;
    }

    .detalle-header h1 {
        color: #2c3e50;
        font-size: 32px;
        font-weight: bold;
        margin: 0;
    }

    .detalle-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        align-items: start;
    }

    /* Información Principal */
    .detalle-principal {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .estado-pedido {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 25px;
        border-bottom: 2px solid #ecf0f1;
    }

    .estado-badge {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 14px;
    }

    .estado-pendiente { background: #fff3cd; color: #856404; }
    .estado-procesado { background: #d1ecf1; color: #0c5460; }
    .estado-enviado { background: #d4edda; color: #155724; }
    .estado-entregado { background: #d4edda; color: #155724; }
    .estado-cancelado { background: #f8d7da; color: #721c24; }

    .pedido-info {
        text-align: right;
    }

    .pedido-info p {
        margin: 5px 0;
        color: #7f8c8d;
    }

    /* Productos */
    .productos-seccion h2 {
        color: #2c3e50;
        margin-bottom: 25px;
        font-size: 24px;
        font-weight: 600;
    }

    .lista-productos {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .producto-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px;
        border: 2px solid #f8f9fa;
        border-radius: 10px;
        transition: border-color 0.3s;
    }

    .producto-item:hover {
        border-color: #1abc9c;
    }

    .producto-info {
        flex: 1;
    }

    .producto-info h3 {
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 18px;
        font-weight: 600;
    }

    .producto-info p {
        color: #7f8c8d;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
    }

    .producto-detalle {
        text-align: right;
        min-width: 200px;
    }

    .producto-cantidad {
        color: #7f8c8d;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .producto-precios {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .precio-unitario {
        color: #7f8c8d;
        font-size: 14px;
    }

    .precio-total {
        color: #2c3e50;
        font-weight: 600;
        font-size: 16px;
    }

    /* Resumen del Pedido */
    .resumen-pedido {
        position: sticky;
        top: 20px;
    }

    .resumen-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .resumen-card h3 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
    }

    .metodo-pago {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    .resumen-total {
        border-top: 2px solid #ecf0f1;
        padding-top: 20px;
    }

    .total-monto {
        font-size: 24px;
        font-weight: bold;
        color: #1abc9c;
        text-align: center;
        margin-top: 10px;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .detalle-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .resumen-pedido {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .detalle-header h1 {
            font-size: 24px;
        }

        .estado-pedido {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .pedido-info {
            text-align: center;
        }

        .producto-item {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .producto-detalle {
            text-align: center;
            min-width: auto;
        }
    }
</style>