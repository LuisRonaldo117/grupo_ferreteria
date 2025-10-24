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
                                🗑️ Vaciar Carrito
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
        background: #f8f9fa;
        min-height: 70vh;
    }

    .carrito-page h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 40px;
        font-size: 36px;
        font-weight: bold;
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
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .carrito-vacio-mensaje {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .carrito-vacio-mensaje .icono {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .carrito-vacio-mensaje h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .carrito-vacio-mensaje p {
        margin-bottom: 25px;
        font-size: 16px;
    }

    .btn-ir-catalogo {
        background: #1abc9c;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.3s;
        display: inline-block;
    }

    .btn-ir-catalogo:hover {
        background: #16a085;
    }

    .carrito-item-detalle {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #ecf0f1;
        gap: 20px;
    }

    .carrito-item-detalle:last-child {
        border-bottom: none;
    }

    .item-imagen {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .item-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    /* Para el dropdown del carrito */
    .carrito-item-imagen {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .carrito-item-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-info {
        flex: 1;
    }

    .item-nombre {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .item-precio {
        font-size: 16px;
        color: #1abc9c;
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
        background: #f8f9fa;
        padding: 5px 15px;
        border-radius: 25px;
    }

    .btn-cantidad {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .btn-cantidad:hover {
        background: #1abc9c;
        color: white;
    }

    .cantidad-numero {
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }

    .btn-eliminar-item {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-eliminar-item:hover {
        background: #c0392b;
    }

    .carrito-acciones-principales {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #ecf0f1;
    }

    .btn-seguir-comprando {
        background: #3498db;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-seguir-comprando:hover {
        background: #2980b9;
    }

    .btn-vaciar-carrito-principal {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-vaciar-carrito-principal:hover {
        background: #c0392b;
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
        margin-bottom: 25px;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
    }

    .resumen-detalle {
        margin-bottom: 25px;
    }

    .resumen-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        color: #7f8c8d;
    }

    .resumen-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #ecf0f1;
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
    }

    .seguridad-info {
        text-align: center;
        margin-bottom: 25px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        color: #7f8c8d;
        font-size: 14px;
    }

    .btn-proceder-pago {
        background: #1abc9c;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }

    .btn-proceder-pago:hover {
        background: #16a085;
    }

    .btn-proceder-pago:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
    }

    /* Modal de Pago */
    .modal-pago {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 2000;
        align-items: center;
        justify-content: center;
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
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 30px;
        border-bottom: 1px solid #ecf0f1;
    }

    .modal-header h2 {
        color: #2c3e50;
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .cerrar-modal {
        font-size: 30px;
        cursor: pointer;
        color: #7f8c8d;
        transition: color 0.3s;
    }

    .cerrar-modal:hover {
        color: #e74c3c;
    }

    .modal-body {
        padding: 30px;
    }

    .resumen-modal {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin: 25px 0;
    }

    .resumen-modal h3 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
    }

    .modal-acciones {
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }

    .btn-cancelar {
        flex: 1;
        background: #95a5a6;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-cancelar:hover {
        background: #7f8c8d;
    }

    .btn-confirmar-pago {
        flex: 2;
        background: #1abc9c;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-confirmar-pago:hover {
        background: #16a085;
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
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 2px solid #28a745;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        animation: slideInDown 0.5s ease;
    }

    .notificacion-contenido {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .notificacion-icono {
        font-size: 40px;
    }

    .notificacion-texto h3 {
        color: #155724;
        margin: 0 0 5px 0;
        font-size: 20px;
    }

    .notificacion-texto p {
        color: #155724;
        margin: 0;
        opacity: 0.9;
    }

    .btn-descargar-factura {
        background: #007bff;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 500;
        transition: background 0.3s;
        margin-left: auto;
    }

    .btn-descargar-factura:hover {
        background: #0056b3;
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
