<!-- Hero Section -->
<section class="catalog-hero rounded">
    <div class="catalog-hero-content">
        <h1>
            <?php 
            if (isset($terminoBusqueda)) {
                echo $nombreCategoria;
                echo ' <span class="resultados-count">(' . $totalResultados . ' resultado' . ($totalResultados != 1 ? 's' : '') . ')</span>';
            } else {
                echo 'Catálogo - ' . $nombreCategoria;
            }
            ?>
        </h1>
        <p class="lead">Encuentra las mejores herramientas y materiales para tus proyectos</p>
    </div>
</section>

<!-- Contenido principal -->
<div class="container my-4">
    <div class="row">
        <!-- Sidebar de categorías -->
        <div class="col-lg-3 mb-4">
            <div class="category-sidebar">
                <h4 class="filter-title">Categorías</h4>
                <ul class="category-list">
                    <li class="category-item <?php echo $categoriaActual === 'todos' ? 'active' : ''; ?>">
                        <a href="index.php?c=catalogo" class="category-link">
                            <i class="fas fa-th-large"></i> Todas las categorías
                        </a>
                    </li>
                    <?php foreach($categorias as $id => $nombre): ?>
                        <li class="category-item <?php echo $categoriaActual == $id ? 'active' : ''; ?>">
                            <a href="index.php?c=catalogo&categoria=<?php echo $id; ?>" class="category-link">
                                <i class="fas fa-tools"></i> <?php echo $nombre; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <!-- Información de búsqueda -->
                <?php if (isset($terminoBusqueda)): ?>
                <div class="busqueda-info mt-4 p-3 bg-light rounded">
                    <p class="mb-2"><strong>Búsqueda:</strong> "<?php echo htmlspecialchars($terminoBusqueda); ?>"</p>
                    <a href="index.php?c=catalogo" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-arrow-left me-1"></i> Volver al catálogo
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="col-lg-9">
            <div class="filter-section mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">Mostrando <span id="productCount"><?php echo count($productos); ?></span> productos</h4>
                    </div>
                </div>
            </div>
            
            <!-- Grid de productos -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="productContainer">
                <?php if(!empty($productos)): ?>
                    <?php foreach($productos as $producto): ?>
                        <div class="col">
                            <div class="product-card">
                                <div class="product-img-container">
                                    <?php if ($producto['stock'] < 10 && $producto['stock'] > 0): ?>
                                        <span class="product-badge badge-sale">ÚLTIMAS UNIDADES</span>
                                    <?php elseif ($producto['stock'] == 0): ?>
                                        <span class="product-badge badge-discount">AGOTADO</span>
                                    <?php endif; ?>
                                    
                                    <div class="producto-imagen">
                                        <?php if (strpos($producto['imagen'], 'http') === 0): ?>
                                            <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="product-img">
                                        <?php elseif (strpos($producto['imagen'], 'assets/') === 0): ?>
                                            <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="product-img"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                            <div class="producto-icono" style="display: none;">
                                                <i class="fas fa-tools fa-3x text-muted"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="producto-icono">
                                                <i class="fas fa-tools fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="product-body">
                                    <h5 class="product-title"><?php echo $producto['nombre']; ?></h5>
                                    <p class="product-brand"><?php echo $producto['categoria'] ?? 'Categoría general'; ?></p>
                                    
                                    <div class="product-price">
                                        <span class="current-price">Bs. <?php echo number_format($producto['precio'], 2); ?></span>
                                    </div>
                                    
                                    <p class="product-stock <?php echo $producto['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                        <i class="fas <?php echo $producto['stock'] > 0 ? 'fa-check-circle stock-icon' : 'fa-times-circle stock-icon'; ?>"></i>
                                        <?php echo $producto['stock'] > 0 ? "Disponible ({$producto['stock']} unidades)" : "Agotado"; ?>
                                    </p>
                                    
                                    <div class="product-actions">
                                        <div class="d-flex justify-content-between w-100">
                                            <!-- Botón Detalles - Redirige a una página de detalles -->
                                            <a href="index.php?c=producto&a=detalle&id=<?php echo $producto['id']; ?>" class="btn-detail">
                                                <i class="fas fa-eye btn-icon"></i> Detalles
                                            </a>
                                            
                                            <!-- Botón Agregar al Carrito -->
                                            <?php if ($producto['stock'] > 0): ?>
                                                <button class="btn-cart" 
                                                    onclick="agregarAlCarrito(
                                                        <?php echo $producto['id']; ?>,
                                                        '<?php echo addslashes($producto['nombre']); ?>',
                                                        <?php echo $producto['precio']; ?>,
                                                        '<?php echo $producto['imagen']; ?>'
                                                    )">
                                                    <i class="fas fa-cart-plus btn-icon"></i> Añadir
                                                </button>
                                            <?php else: ?>
                                                <button class="btn-disabled" disabled>
                                                    <i class="fas fa-ban btn-icon"></i> Agotado
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="sin-resultados text-center py-5">
                            <div class="icono mb-3">
                                <i class="fas fa-search fa-4x text-muted"></i>
                            </div>
                            <h3 class="mb-3">No se encontraron productos</h3>
                            <p class="mb-4">No hay productos que coincidan con tu búsqueda.</p>
                            
                            <?php if (isset($terminoBusqueda)): ?>
                                <div class="sugerencias-busqueda bg-light p-4 rounded mb-4">
                                    <h4 class="mb-3">Sugerencias:</h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Revisa la ortografía de las palabras</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Usa términos más generales</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Prueba con otras palabras relacionadas</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Busca por categorías específicas</li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <a href="index.php?c=catalogo" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i> Ver todos los productos
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-bg);
        color: #333;
        line-height: 1.6;
    }
    
    /* Hero Section */
    .catalog-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                    url('https://images.unsplash.com/photo-1600585152220-90363fe7e115?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 80px 0;
        margin-bottom: 40px;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }
    
    .catalog-hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    
    .catalog-hero h1 {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    
    .catalog-hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }

    .resultados-count {
        font-size: 1rem;
        color: var(--gold-accent);
        font-weight: normal;
    }

    /* Sidebar de categorías */
    .category-sidebar {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
        padding: 25px;
        height: 100%;
        border-top: 4px solid var(--accent-color);
    }
    
    .filter-title {
        color: var(--dark-blue);
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }
    
    .filter-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--gold-accent);
    }
    
    .category-list {
        list-style: none;
        padding: 0;
    }
    
    .category-item {
        margin-bottom: 8px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .category-item:hover {
        background-color: rgba(0, 123, 255, 0.1);
        border-left: 4px solid var(--accent-color);
    }
    
    .category-item.active {
        background-color: rgba(0, 123, 255, 0.1);
        border-left: 4px solid var(--accent-color);
    }
    
    .category-link {
        padding: 12px 15px;
        display: flex;
        align-items: center;
        font-weight: 500;
        color: var(--dark-blue);
        text-decoration: none;
        width: 100%;
        height: 100%;
    }
    
    .category-item.active .category-link {
        color: var(--accent-color);
        font-weight: 600;
    }
    
    .category-item:hover .category-link {
        color: var(--accent-color);
    }
    
    .category-link i {
        margin-right: 10px;
        font-size: 1.1rem;
        width: 25px;
        text-align: center;
    }

    /* Sección de filtros */
    .filter-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
        padding: 25px;
        margin-bottom: 30px;
        border-top: 4px solid var(--accent-color);
    }
    
    /* Productos */
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.4s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        border-color: rgba(0, 123, 255, 0.2);
    }
    
    .product-img-container {
        height: 200px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
        padding: 20px;
        position: relative;
    }
    
    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .badge-new {
        background-color: #28a745;
        color: white;
    }
    
    .badge-sale {
        background-color: var(--secondary-color);
        color: white;
    }
    
    .badge-discount {
        background-color: var(--gold-accent);
        color: var(--dark-blue);
    }
    
    .product-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    
    .product-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-title {
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: 8px;
        font-size: 1.1rem;
        line-height: 1.3;
        min-height: 50px;
    }
    
    .product-brand {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 8px;
    }
    
    .product-price {
        margin: 12px 0;
    }
    
    .current-price {
        font-weight: 700;
        color: var(--secondary-color);
        font-size: 1.3rem;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.9rem;
        margin-left: 8px;
    }
    
    .product-stock {
        font-size: 0.85rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .in-stock {
        color: #28a745;
    }
    
    .out-of-stock {
        color: var(--secondary-color);
    }
    
    .stock-icon {
        margin-right: 5px;
    }
    
    .product-actions {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-detail {
        background: linear-gradient(135deg, var(--accent-color) 0%, #0069d9 100%);
        color: white;
        border-radius: 6px;
        padding: 8px 15px;
        font-size: 0.9rem;
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        flex: 1;
    }
    
    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2);
        color: white;
        text-decoration: none;
    }
    
    .btn-cart {
        background: linear-gradient(135deg, var(--navy-blue) 0%, #002244 100%);
        color: white;
        border-radius: 6px;
        padding: 8px 15px;
        font-size: 0.9rem;
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        flex: 1;
    }
    
    .btn-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 51, 102, 0.2);
        color: white;
    }
    
    .btn-disabled {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
        border-radius: 6px;
        padding: 8px 15px;
        font-size: 0.9rem;
        border: none;
        font-weight: 500;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
    }
    
    .btn-icon {
        margin-right: 5px;
    }

    /* Sin resultados */
    .sin-resultados {
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .sin-resultados .icono {
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .sin-resultados h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .sin-resultados p {
        margin-bottom: 25px;
        font-size: 16px;
    }

    .sugerencias-busqueda {
        margin-top: 20px;
        text-align: left;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .sugerencias-busqueda h4 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .catalog-hero h1 {
            font-size: 2.2rem;
        }
        
        .product-title {
            font-size: 1rem;
            min-height: auto;
        }
    }
    
    @media (max-width: 768px) {
        .catalog-hero {
            padding: 60px 0;
        }
        
        .catalog-hero h1 {
            font-size: 1.8rem;
        }
        
        .category-sidebar {
            margin-bottom: 30px;
        }
    }
    
    @media (max-width: 576px) {
        .product-actions .btn {
            font-size: 0.8rem;
            padding: 6px 10px;
        }
        
        .catalog-hero h1 {
            font-size: 1.5rem;
        }
        
        .catalog-hero p {
            font-size: 1rem;
        }
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Función para agregar al carrito con AJAX
    function agregarAlCarrito(id, nombre, precio, imagen) {   
        console.log('Agregando al carrito:', {id, nombre, precio, imagen});
        
        // Hacer petición AJAX al controlador del carrito
        fetch('index.php?c=carrito&a=agregar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'id_producto=' + id + '&cantidad=1'
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
        let toastContainer = document.querySelector('.toast-notification');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-notification position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

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
        
        // Auto remover después de 3 segundos
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
        actualizarContadorCarrito();
        
        // Manejo de categorías
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('click', function() {
                const categoria = this.getAttribute('data-categoria');
                if (categoria === 'todos') {
                    window.location.href = 'index.php?c=catalogo';
                } else {
                    window.location.href = 'index.php?c=catalogo&categoria=' + categoria;
                }
            });
        });
    });
</script>