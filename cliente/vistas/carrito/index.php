<section class="carrito-page">
    <div class="container">
        <h1>Tu Carrito de Compras</h1>
        
        <div class="carrito-layout">
            <!-- Lista de Productos -->
            <div class="carrito-productos">
                <div id="carritoContenido">
                    <?php if (!empty($carrito)): ?>
                        <?php foreach($carrito as $item): ?>
                        <!-- En la sección del carrito -->
                        <div class="carrito-item-detalle">
                            <div class="item-imagen">
                                <?php 
                                // Verificar si hay imagen y construir la ruta correcta
                                if (!empty($item['imagen'])) {
                                    $rutaImagen = 'assets/images/' . $item['imagen'];
                                    echo '<img src="' . $rutaImagen . '" alt="' . $item['nombre'] . '" onerror="this.style.display=\'none\'">';
                                } else {
                                    echo '📦';
                                }
                                ?>
                            </div>
                            <div class="item-info">
                                <div class="item-nombre"><?php echo $item['nombre']; ?></div>
                                <div class="item-precio">Bs. <?php echo number_format($item['precio_unitario'], 2); ?></div>
                            </div>
                            <div class="item-controls">
                                <div class="cantidad-control">
                                    <button class="btn-cantidad" onclick="carritoManager.actualizarCantidad(<?php echo $item['id_carrito']; ?>, <?php echo $item['cantidad'] - 1; ?>)">-</button>
                                    <span class="cantidad-numero"><?php echo $item['cantidad']; ?></span>
                                    <button class="btn-cantidad" onclick="carritoManager.actualizarCantidad(<?php echo $item['id_carrito']; ?>, <?php echo $item['cantidad'] + 1; ?>)">+</button>
                                </div>
                                <button class="btn-eliminar-item" onclick="carritoManager.eliminarProducto(<?php echo $item['id_carrito']; ?>)">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- Botones de acción -->
                        <div class="carrito-acciones-principales">
                            <a href="index.php?c=catalogo" class="btn-seguir-comprando">
                                ← Seguir Comprando
                            </a>
                            <button class="btn-vaciar-carrito-principal" 
                                    onclick="carritoManager.vaciarCarrito()">
                                    Vaciar Carrito
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="carrito-vacio-mensaje">
                            <div class="icono">🛒</div>
                            <h3>Tu carrito está vacío</h3>
                            <p>Agrega productos desde nuestro catálogo</p>
                            <a href="index.php?c=catalogo" class="btn-ir-catalogo">Ir al Catálogo</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="resumen-pedido">
                <div class="resumen-card">
                    <h3>Resumen del Pedido</h3>
                    
                    <div class="resumen-detalle">
                        <div class="resumen-item">
                            <span>Subtotal:</span>
                            <span>Bs. <span id="resumenSubtotal"><?php echo number_format($totalCarrito ?? 0, 2); ?></span></span>
                        </div>
                        <div class="resumen-item">
                            <span>Envío:</span>
                            <span>Bs. <span id="resumenEnvio"><?php echo ($totalCarrito ?? 0) > 0 ? '15.00' : '0.00'; ?></span></span>
                        </div>
                        <div class="resumen-total">
                            <span>Total:</span>
                            <span>Bs. <span id="resumenTotal"><?php echo number_format(($totalCarrito ?? 0) + (($totalCarrito ?? 0) > 0 ? 15 : 0), 2); ?></span></span>
                        </div>
                    </div>
                    
                    <div class="seguridad-info">
                        <p>🔒 Tu información de pago está protegida</p>
                    </div>
                    
                    <button class="btn-proceder-pago" id="btnProcederPago" <?php echo empty($carrito) ? 'disabled' : ''; ?>>
                        Proceder al Pago
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Pago -->
<div class="modal-pago" id="modalPago">
    <div class="modal-contenido">
        <div class="modal-header">
            <h2>Seleccione Método de Pago</h2>
            <span class="cerrar-modal" id="cerrarModalPago">&times;</span>
        </div>
        
        <div class="modal-body">
            <form id="formPago">
                <div class="form-group">
                    <label for="metodoPago">Método de Pago</label>
                    <select id="metodoPago" name="metodo_pago" required>
                        <option value="">Seleccione un método</option>
                        <?php foreach($metodos_pago as $key => $valor): ?>
                            <option value="<?php echo $key; ?>"><?php echo $valor; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="resumen-modal">
                    <h3>Resumen del Pedido</h3>
                    <div class="resumen-item">
                        <span>Subtotal:</span>
                        <span>Bs. <span id="modalSubtotal">0.00</span></span>
                    </div>
                    <div class="resumen-item">
                        <span>Envío:</span>
                        <span>Bs. <span id="modalEnvio">15.00</span></span>
                    </div>
                    <div class="resumen-total">
                        <span>Total:</span>
                        <span>Bs. <span id="modalTotal">15.00</span></span>
                    </div>
                </div>
                
                <div class="modal-acciones">
                    <button type="button" class="btn-cancelar" id="btnCancelarPago">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-confirmar-pago">
                        Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notificación de pago exitoso -->
<div id="notificacionPago" class="notificacion-pago" style="display: none;">
    <div class="notificacion-contenido">
        <div class="notificacion-icono">✅</div>
        <div class="notificacion-texto">
            <h3>¡Pago exitoso!</h3>
            <p>Tu compra ha sido procesada correctamente.</p>
        </div>
        <a href="#" class="btn-descargar-factura" id="btnDescargarFacturaNotif">
            📄 Descargar factura
        </a>
    </div>
</div>

<style>
    .carrito-page {
        padding: 40px 0 80px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
        min-height: 70vh;
    }

    .carrito-page h1 {
        text-align: center;
        color: #1a237e;
        margin-bottom: 40px;
        font-size: 36px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
        padding-bottom: 15px;
    }

    .carrito-page h1:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
        border-radius: 2px;
    }

    .carrito-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        align-items: start;
    }

    /* Lista de Productos */
    .carrito-productos {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(33, 150, 243, 0.1);
        border: 1px solid #e3f2fd;
        position: relative;
        overflow: hidden;
    }

    .carrito-productos:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
    }

    .carrito-vacio-mensaje {
        text-align: center;
        padding: 60px 20px;
        color: #546e7a;
    }

    .carrito-vacio-mensaje .icono {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.7;
        color: #2196F3;
    }

    .carrito-vacio-mensaje h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #1a237e;
        font-weight: 600;
    }

    .carrito-vacio-mensaje p {
        margin-bottom: 25px;
        font-size: 16px;
        color: #546e7a;
    }

    .btn-ir-catalogo {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .btn-ir-catalogo:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        color: white;
        text-decoration: none;
    }

    .carrito-item-detalle {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e3f2fd;
        gap: 20px;
        transition: all 0.3s ease;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .carrito-item-detalle:hover {
        background: #f8fdff;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.1);
    }

    .carrito-item-detalle:last-child {
        border-bottom: none;
    }

    .item-imagen {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid #e3f2fd;
    }

    .item-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-info {
        flex: 1;
    }

    .item-nombre {
        font-size: 18px;
        font-weight: 600;
        color: #1a237e;
        margin-bottom: 5px;
    }

    .item-precio {
        font-size: 16px;
        color: #1976D2;
        font-weight: bold;
    }

    .item-controls {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .cantidad-control {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #e3f2fd;
        padding: 5px 15px;
        border-radius: 25px;
        border: 1px solid #bbdefb;
    }

    .btn-cantidad {
        background: #2196F3;
        color: white;
        border: none;
        font-size: 16px;
        cursor: pointer;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-weight: bold;
    }

    .btn-cantidad:hover {
        background: #1976D2;
        transform: scale(1.1);
    }

    .cantidad-numero {
        font-weight: 600;
        min-width: 30px;
        text-align: center;
        color: #1a237e;
    }

    .btn-eliminar-item {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.2);
    }

    .btn-eliminar-item:hover {
        background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
    }

    .carrito-acciones-principales {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e3f2fd;
    }

    .btn-seguir-comprando {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .btn-seguir-comprando:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-vaciar-carrito-principal {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    }

    .btn-vaciar-carrito-principal:hover {
        background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
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
        box-shadow: 0 8px 32px rgba(33, 150, 243, 0.1);
        border: 1px solid #e3f2fd;
        position: relative;
        overflow: hidden;
    }

    .resumen-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
    }

    .resumen-card h3 {
        color: #1a237e;
        margin-bottom: 25px;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        position: relative;
        padding-bottom: 10px;
    }

    .resumen-card h3:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 2px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
    }

    .resumen-detalle {
        margin-bottom: 25px;
    }

    .resumen-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        color: #546e7a;
        font-weight: 500;
    }

    .resumen-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #e3f2fd;
        font-size: 18px;
        font-weight: bold;
        color: #1a237e;
    }

    .seguridad-info {
        text-align: center;
        margin-bottom: 25px;
        padding: 15px;
        background: #e8f5e9;
        border-radius: 8px;
        color: #2e7d32;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid #c8e6c9;
    }

    .btn-proceder-pago {
        background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-proceder-pago:hover {
        background: linear-gradient(135deg, #43A047 0%, #1B5E20 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .btn-proceder-pago:disabled {
        background: #bdbdbd;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Modal de Pago */
    .modal-pago {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(26, 35, 126, 0.9);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .modal-pago.active {
        display: flex;
    }

    .modal-contenido {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        animation: modalSlideIn 0.3s ease;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: 1px solid #e3f2fd;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 30px;
        border-bottom: 1px solid #e3f2fd;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .cerrar-modal {
        font-size: 30px;
        cursor: pointer;
        color: white;
        transition: color 0.3s;
        opacity: 0.8;
    }

    .cerrar-modal:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1a237e;
    }

    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e3f2fd;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
        background: white;
    }

    .form-group select:focus {
        outline: none;
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
    }

    .resumen-modal {
        background: #f8fdff;
        padding: 20px;
        border-radius: 10px;
        margin: 25px 0;
        border: 1px solid #e3f2fd;
    }

    .resumen-modal h3 {
        color: #1a237e;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
        text-align: center;
    }

    .modal-acciones {
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }

    .btn-cancelar {
        flex: 1;
        background: linear-gradient(135deg, #78909c 0%, #546e7a 100%);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-cancelar:hover {
        background: linear-gradient(135deg, #546e7a 0%, #455a64 100%);
        transform: translateY(-1px);
    }

    .btn-confirmar-pago {
        flex: 2;
        background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-confirmar-pago:hover {
        background: linear-gradient(135deg, #43A047 0%, #1B5E20 100%);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .carrito-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .resumen-pedido {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .carrito-page h1 {
            font-size: 28px;
        }

        .carrito-productos {
            padding: 20px;
        }

        .carrito-item-detalle {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .item-controls {
            justify-content: center;
        }

        .carrito-acciones-principales {
            flex-direction: column;
            gap: 15px;
        }

        .modal-acciones {
            flex-direction: column;
        }
    }

    .notificacion-pago {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border: 2px solid #4CAF50;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        animation: slideInDown 0.5s ease;
        box-shadow: 0 8px 25px rgba(76, 175, 80, 0.2);
    }

    .notificacion-contenido {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .notificacion-icono {
        font-size: 40px;
        color: #4CAF50;
    }

    .notificacion-texto h3 {
        color: #2e7d32;
        margin: 0 0 5px 0;
        font-size: 20px;
        font-weight: 600;
    }

    .notificacion-texto p {
        color: #2e7d32;
        margin: 0;
        opacity: 0.9;
    }

    .btn-descargar-factura {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-left: auto;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
    }

    .btn-descargar-factura:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        color: white;
        text-decoration: none;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>