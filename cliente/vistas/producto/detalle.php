<!-- Hero Section -->
<section class="product-hero rounded">
    <div class="product-hero-content">
        <h1>Detalles del Producto</h1>
        <p class="lead">Información completa sobre el producto seleccionado</p>
    </div>
</section>

<!-- Contenido principal -->
<div class="container my-4">
    <div class="product-detail-container">
        <div class="row">
            <!-- Imagen del producto -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    <div class="main-image">
                        <?php if (strpos($producto['imagen_ruta'], 'http') === 0 || strpos($producto['imagen_ruta'], 'assets/') === 0): ?>
                            <img src="<?php echo $producto['imagen_ruta']; ?>" 
                                 alt="<?php echo $producto['nombre']; ?>" 
                                 class="img-fluid rounded"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <div class="product-icon-fallback" style="display: none;">
                                <i class="fas fa-tools fa-5x"></i>
                            </div>
                        <?php else: ?>
                            <div class="product-icon-large text-center py-5">
                                <div class="icon-display" style="font-size: 5rem;"><?php echo $producto['imagen_ruta']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Información del producto -->
            <div class="col-lg-6 mb-4">
                <div class="product-info-card">
                    <div class="product-header">
                        <h2 class="product-title"><?php echo $producto['nombre']; ?></h2>
                        <div class="product-category-badge">
                            <i class="fas fa-tag me-2"></i><?php echo $producto['nombre_categoria']; ?>
                        </div>
                    </div>
                    
                    <div class="product-price-section">
                        <div class="current-price">Bs. <?php echo number_format($producto['precio_unitario'], 2); ?></div>
                    </div>
                    
                    <div class="product-stock-info">
                        <div class="stock-status <?php echo $producto['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                            <i class="fas <?php echo $producto['stock'] > 0 ? 'fa-check-circle' : 'fa-times-circle'; ?> me-2"></i>
                            <?php echo $producto['stock'] > 0 ? "Disponible ({$producto['stock']} unidades)" : "Agotado"; ?>
                        </div>
                    </div>
                    
                    <div class="product-description">
                        <h4>Descripción</h4>
                        <p><?php echo !empty($producto['descripcion']) ? nl2br(htmlspecialchars($producto['descripcion'])) : 'Descripción no disponible.'; ?></p>
                    </div>
                    
                    <div class="product-actions-detalle">
                        <div class="quantity-control">
                            <label for="cantidadProducto" class="form-label">Cantidad:</label>
                            <div class="input-group" style="max-width: 150px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity()">-</button>
                                <input type="number" class="form-control text-center" id="cantidadProducto" value="1" min="1" max="<?php echo $producto['stock']; ?>">
                                <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity()">+</button>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <?php if ($producto['stock'] > 0): ?>
                                <button class="btn-add-to-cart-detalle" 
                                        onclick="agregarAlCarritoDesdeDetalle(<?php echo $producto['id_producto']; ?>, '<?php echo addslashes($producto['nombre']); ?>', <?php echo $producto['precio_unitario']; ?>, '<?php echo $producto['imagen_ruta']; ?>')">
                                    <i class="fas fa-cart-plus me-2"></i> Agregar al Carrito
                                </button>
                            <?php else: ?>
                                <button class="btn-disabled-detalle" disabled>
                                    <i class="fas fa-ban me-2"></i> Producto Agotado
                                </button>
                            <?php endif; ?>
                            
                            <a href="index.php?c=catalogo" class="btn-back-to-catalog">
                                <i class="fas fa-arrow-left me-2"></i> Volver al Catálogo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Productos relacionados -->
    <?php if (!empty($productosRelacionados)): ?>
    <div class="row mt-5">
        <div class="col-12">
            <div class="related-products-section">
                <h3 class="section-title-related">Productos Relacionados</h3>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach($productosRelacionados as $productoRel): ?>
                    <div class="col">
                        <div class="product-card-related">
                            <div class="product-img-container-related">
                                <?php if (strpos($productoRel['imagen_ruta'], 'http') === 0 || strpos($productoRel['imagen_ruta'], 'assets/') === 0): ?>
                                    <img src="<?php echo $productoRel['imagen_ruta']; ?>" 
                                         alt="<?php echo $productoRel['nombre']; ?>" 
                                         class="product-img-related">
                                <?php else: ?>
                                    <div class="product-icono-related">
                                        <?php echo $productoRel['imagen_ruta']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="product-body-related">
                                <h5 class="product-title-related"><?php echo $productoRel['nombre']; ?></h5>
                                <div class="product-price-related">
                                    <span class="current-price-related">Bs. <?php echo number_format($productoRel['precio_unitario'], 2); ?></span>
                                </div>
                                <div class="product-actions-related">
                                    <a href="index.php?c=producto&a=detalle&id=<?php echo $productoRel['id_producto']; ?>" class="btn-detail-related">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Toast Notification Container -->
<div class="toast-notification"></div>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #d32f2f;
        --accent-color: #007bff;
        --light-bg: #f8f9fa;
        --dark-blue: #1a2a3a;
        --gold-accent: #FFD700;
        --navy-blue: #003366;
    }
    
    /* Hero Section */
    .product-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                    url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
        border-radius: 8px;
    }
    
    .product-hero-content {
        text-align: center;
    }
    
    .product-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    /* Contenedor principal */
    .product-detail-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
        padding: 30px;
        margin-bottom: 40px;
        border-top: 4px solid var(--accent-color);
    }

    /* Imagen del producto */
    .product-image-container {
        text-align: center;
        padding: 20px;
    }

    .main-image {
        max-width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .main-image img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .product-icon-fallback {
        font-size: 5rem;
        color: #6c757d;
    }

    /* Información del producto */
    .product-info-card {
        padding: 20px;
    }

    .product-header {
        margin-bottom: 25px;
    }

    .product-title {
        color: var(--dark-blue);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .product-category-badge {
        display: inline-block;
        background: rgba(0, 123, 255, 0.1);
        color: var(--accent-color);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .product-price-section {
        margin-bottom: 20px;
    }

    .current-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .product-stock-info {
        margin-bottom: 25px;
    }

    .stock-status {
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: 500;
    }

    .stock-status.in-stock {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .stock-status.out-of-stock {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .product-description {
        margin-bottom: 30px;
    }

    .product-description h4 {
        color: var(--dark-blue);
        margin-bottom: 15px;
        font-size: 1.2rem;
    }

    .product-description p {
        line-height: 1.6;
        color: #6c757d;
        font-size: 1rem;
    }

    /* Acciones del producto */
    .product-actions-detalle {
        margin-top: 30px;
    }

    .quantity-control {
        margin-bottom: 20px;
    }

    .quantity-control .form-label {
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: 10px;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .btn-add-to-cart-detalle {
        background: linear-gradient(135deg, var(--navy-blue) 0%, #002244 100%);
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add-to-cart-detalle:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
    }

    .btn-disabled-detalle {
        background: #e9ecef;
        color: #6c757d;
        border: none;
        padding: 15px 25px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: not-allowed;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-back-to-catalog {
        background: #6c757d;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.3s;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-back-to-catalog:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }

    /* Productos relacionados */
    .related-products-section {
        margin-top: 50px;
    }

    .section-title-related {
        color: var(--dark-blue);
        font-weight: 600;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title-related:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--gold-accent);
    }

    .product-card-related {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .product-card-related:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .product-img-container-related {
        height: 150px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
        padding: 15px;
    }

    .product-img-related {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .product-icono-related {
        font-size: 3rem;
        opacity: 0.7;
    }

    .product-body-related {
        padding: 20px;
    }

    .product-title-related {
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: 10px;
        font-size: 1rem;
        line-height: 1.3;
        height: 40px;
        overflow: hidden;
    }

    .product-price-related {
        margin-bottom: 15px;
    }

    .current-price-related {
        font-weight: 700;
        color: var(--secondary-color);
        font-size: 1.1rem;
    }

    .product-actions-related {
        text-align: center;
    }

    .btn-detail-related {
        background: var(--accent-color);
        color: white;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        width: 100%;
    }

    .btn-detail-related:hover {
        background: #0069d9;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* Toast notifications */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .product-hero h1 {
            font-size: 2rem;
        }
        
        .product-title {
            font-size: 1.5rem;
        }
        
        .main-image {
            height: 300px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
    // Funciones para control de cantidad
    function incrementQuantity() {
        const input = document.getElementById('cantidadProducto');
        const max = parseInt(input.getAttribute('max'));
        let value = parseInt(input.value);
        if (value < max) {
            input.value = value + 1;
        }
    }
    
    function decrementQuantity() {
        const input = document.getElementById('cantidadProducto');
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
        }
    }
    
    // Función para agregar al carrito desde la página de detalles
    function agregarAlCarritoDesdeDetalle(id, nombre, precio, imagen) {
        const cantidad = document.getElementById('cantidadProducto').value;
        
        console.log('Agregando al carrito desde detalles:', {id, nombre, precio, imagen, cantidad});
        
        // Mostrar loading
        showToast('Agregando producto...', 'info');
        
        // Hacer petición AJAX al controlador del carrito
        fetch('index.php?c=carrito&a=agregar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'id_producto=' + id + '&cantidad=' + cantidad
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('✓ ' + data.mensaje, 'success');
                // Actualizar contador del carrito en el header
                actualizarContadorCarrito();
            } else {
                showToast('✗ ' + data.mensaje, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('✗ Error al agregar al carrito', 'error');
        });
    }
    
    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('index.php?c=carrito&a=obtenerCarrito', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartBadge = document.getElementById('contadorCarrito');
                if (cartBadge) {
                    cartBadge.textContent = data.totalItems || 0;
                }
            }
        })
        .catch(error => console.error('Error al obtener carrito:', error));
    }
    
    // Función para mostrar notificación
    function showToast(message, type = 'success') {
        const toastContainer = document.querySelector('.toast-notification');
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : 
                       type === 'error' ? 'bg-danger' : 'bg-info';
        
        const toastHTML = `
            <div id="${toastId}" class="toast show align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        // Auto-remover después de 3 segundos
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                const bsToast = new bootstrap.Toast(toastElement);
                bsToast.hide();
                setTimeout(() => toastElement.remove(), 300);
            }
        }, 3000);
    }

    // Inicializar cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar contador del carrito al cargar la página
        actualizarContadorCarrito();
    });
</script>