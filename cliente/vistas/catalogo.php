<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/proyecto%20ferreteria/grupo_ferreteria/');
}

// Verificar si las variables están definidas
$productos = $productos ?? [];
$categorias = $categorias ?? [];
$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos | Ferretería Industrial</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
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
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-bg);
            padding-top: 120px;
            color: #333;
            line-height: 1.6;
        }
        
        /* Navbar superior (mantenido igual) */
        .top-navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .main-nav {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            position: fixed;
            top: 60px;
            width: 100%;
            z-index: 1020;
        }
        
        .main-nav .nav-link {
            color: white;
            font-weight: 500;
            padding: 8px 20px;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .main-nav .nav-link:hover {
            color: var(--gold-accent);
            text-decoration: none;
        }
        
        .main-nav .nav-link.active {
            color: var(--gold-accent);
            font-weight: 600;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
            font-family: 'Poppins', sans-serif;
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
            font-family: 'Poppins', sans-serif;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .catalog-hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        /* Filtros y categorías */
        .filter-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
            padding: 25px;
            margin-bottom: 30px;
            border-top: 4px solid var(--accent-color);
        }
        
        .filter-title {
            color: var(--dark-blue);
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            font-family: 'Poppins', sans-serif;
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
        
        .category-sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
            padding: 25px;
            height: 100%;
            border-top: 4px solid var(--accent-color);
        }
        
        .category-list {
            list-style: none;
            padding: 0;
        }
        
        .category-item {
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: 500;
            color: var(--dark-blue);
            border-left: 4px solid transparent;
        }
        
        .category-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
        }
        
        .category-item.active {
            background-color: rgba(0, 123, 255, 0.1);
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
            font-weight: 600;
        }
        
        .category-item i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 25px;
            text-align: center;
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
            font-family: 'Poppins', sans-serif;
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
        }
        
        .btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2);
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
        }
        
        .btn-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 51, 102, 0.2);
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
        }
        
        .btn-icon {
            margin-right: 5px;
        }
        
        /* Paginación */
        .pagination .page-item.active .page-link {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .pagination .page-link {
            color: var(--dark-blue);
            border-radius: 6px;
            margin: 0 5px;
            border: 1px solid #dee2e6;
            padding: 8px 15px;
            font-weight: 500;
        }
        
        .pagination .page-link:hover {
            background-color: #f1f1f1;
            color: var(--accent-color);
        }
        
        /* Iconos de navegación */
        .nav-icons .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            position: relative;
            transition: all 0.3s;
            color: var(--primary-color);
        }
        
        .nav-icons .btn-icon:hover {
            background-color: #f1f1f1;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
        
        /* Price range slider */
        .price-range {
            margin-bottom: 25px;
        }
        
        .price-range-values {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        /* Toast notification */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
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
            
            .pagination .page-link {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Barra superior -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?ruta=inicio">FERRETERÍA</a>
            
            <form class="d-flex mx-4" style="flex-grow: 1; max-width: 600px;" id="searchForm">
                <div class="input-group">
                    <input type="search" class="form-control" id="searchInput" placeholder="Buscar productos..." aria-label="Search">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <div class="nav-icons d-flex">
                <!-- Notificación - Usando el sistema de rutas -->
                <button class="btn-icon position-relative" onclick="window.location.href='<?= BASE_URL ?>cliente/?ruta=notificacion'">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger badge-notification rounded-pill" id="notificationBadge">0</span>
                </button>
                
                <!-- Carrito - Usando el sistema de rutas -->
                <button class="btn-icon position-relative" onclick="window.location.href='<?= BASE_URL ?>cliente/?ruta=carrito'">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge bg-danger badge-notification rounded-pill"><?= isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0 ?></span>
                </button>
                <div class="dropdown ms-2">
                    <button class="btn-icon dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if(isset($_SESSION['id_cliente'])): ?>
                            <i class="fas fa-user-check text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <?php if(isset($_SESSION['id_cliente'])): ?>
                            <li><h6 class="dropdown-header">Bienvenido, <?= htmlspecialchars($_SESSION['nombre_cliente'] ?? 'Usuario') ?></h6></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>cliente/?ruta=usuario"><i class="fas fa-user-circle me-2"></i>Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>cliente/?ruta=logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>"><i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menú principal (mantenido igual) -->
    <nav class="navbar navbar-expand main-nav">
        <div class="container-fluid">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=inicio">INICIO</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php?ruta=catalogo">CATÁLOGO</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=nosotros">NOSOTROS</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=informate">INFÓRMATE</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=contacto">CONTACTO</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="catalog-hero rounded">
        <div class="catalog-hero-content">
            <h1>NUESTRO CATÁLOGO DE PRODUCTOS</h1>
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
                        <li class="category-item active" data-categoria="all">
                            <i class="fas fa-th-large"></i> Todas las categorías
                        </li>
                        <li class="category-item" data-categoria="1">
                            <i class="fas fa-tools"></i> Herramientas Manuales
                        </li>
                        <li class="category-item" data-categoria="2">
                            <i class="fas fa-bolt"></i> Herramientas Eléctricas
                        </li>
                        <li class="category-item" data-categoria="3">
                            <i class="fas fa-home"></i> Materiales de Construcción
                        </li>
                        <li class="category-item" data-categoria="4">
                            <i class="fas fa-screwdriver"></i> Tornillería y Sujeción
                        </li>
                        <li class="category-item" data-categoria="5">
                            <i class="fas fa-lightbulb"></i> Electricidad
                        </li>
                        <li class="category-item" data-categoria="6">
                            <i class="fas fa-faucet"></i> Fontanería
                        </li>
                        <li class="category-item" data-categoria="7">
                            <i class="fas fa-paint-roller"></i> Pinturas y Accesorios
                        </li>
                        <li class="category-item" data-categoria="8">
                            <i class="fas fa-hard-hat"></i> Seguridad Industrial
                        </li>
                    </ul>
                    
                    <h4 class="filter-title mt-4">Filtrar por</h4>
                    <div class="mb-3">
                        <label class="form-label">Rango de precios</label>
                        <input type="range" class="form-range" min="0" max="1000" step="10" id="priceRange" value="1000">
                        <div class="price-range-values">
                            <span>Bs. <span id="minPriceValue">0</span></span>
                            <span>Bs. <span id="maxPriceValue">1000</span></span>
                        </div>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="inStockCheck">
                        <label class="form-check-label" for="inStockCheck">Mostrar solo en stock</label>
                    </div>
                    
                    <button class="btn btn-primary w-100" id="applyFilters">
                        <i class="fas fa-filter me-2"></i> Aplicar filtros
                    </button>
                </div>
            </div>
            
            <!-- Productos -->
            <div class="col-lg-9">
                <div class="filter-section mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">Mostrando <span id="productCount"><?= count($productos) ?></span> productos</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="dropdown d-inline-block me-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort me-1"></i> Ordenar por
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#" data-sort="nombre-asc">Nombre (A-Z)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="nombre-desc">Nombre (Z-A)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="precio-asc">Precio (Menor a Mayor)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="precio-desc">Precio (Mayor a Menor)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="relevancia">Relevancia</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="productContainer">
                    <?php if (empty($productos)): ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i> No se encontraron productos con los filtros seleccionados.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="col">
                                <div class="product-card">
                                    <div class="product-img-container">
                                        <?php if (rand(0, 1) === 1): ?>
                                            <span class="product-badge <?= rand(0, 1) ? 'badge-new' : (rand(0, 1) ? 'badge-sale' : 'badge-discount') ?>">
                                                <?= rand(0, 1) ? 'NUEVO' : (rand(0, 1) ? 'OFERTA' : '-20%') ?>
                                            </span>
                                        <?php endif; ?>
                                        <img src="<?= BASE_URL ?>imagenes/<?= htmlspecialchars($producto['imagen']) ?>" 
                                            class="product-img" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                    </div>
                                    <div class="product-body">
                                        <h5 class="product-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                        <p class="product-brand"><?= htmlspecialchars($producto['nombre_categoria']) ?></p>
                                        
                                        <div class="product-price">
                                            <span class="current-price">Bs. <?= number_format($producto['precio_unitario'], 2) ?></span>
                                            <?php if (rand(0, 1) === 1): ?>
                                                <span class="original-price">Bs. <?= number_format($producto['precio_unitario'] * 1.2, 2) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <p class="product-stock <?= $producto['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                                            <i class="fas <?= $producto['stock'] > 0 ? 'fa-check-circle stock-icon' : 'fa-times-circle stock-icon' ?>"></i>
                                            <?= $producto['stock'] > 0 ? "Disponible ({$producto['stock']} unidades)" : "Agotado" ?>
                                        </p>
                                        
                                        <div class="product-actions">
                                            <div class="d-flex justify-content-between w-100">
                                                <a href="index.php?ruta=detalle_producto&id=<?= $producto['id_producto'] ?>" class="btn-detail">
                                                    <i class="fas fa-eye btn-icon"></i> Detalles
                                                </a>
                                                <?php if ($producto['stock'] > 0): ?>
                                                    <button class="btn-cart" onclick="agregarAlCarrito(<?= $producto['id_producto'] ?>)">
                                                        <i class="fas fa-cart-plus btn-icon"></i> Añadir al Carrito
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
                    <?php endif; ?>
                </div>
                
                <!-- Paginación -->
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-notification"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Manejo de categorías
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                categoryItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                actualizarProductos();
            });
        });

        // Manejo de búsqueda
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        let debounceTimeout;
        const debounceSearch = () => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                actualizarProductos();
            }, 500);
        };
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            actualizarProductos();
        });
        searchInput.addEventListener('input', debounceSearch);

        // Manejo del rango de precios
        const priceRange = document.getElementById('priceRange');
        const maxPriceValue = document.getElementById('maxPriceValue');
        priceRange.addEventListener('input', () => {
            maxPriceValue.textContent = priceRange.value;
            actualizarProductos();
        });

        // Manejo del filtro de stock
        const inStockCheck = document.getElementById('inStockCheck');
        inStockCheck.addEventListener('change', () => {
            actualizarProductos();
        });

        // Manejo del botón de filtros
        const applyFiltersBtn = document.getElementById('applyFilters');
        applyFiltersBtn.addEventListener('click', () => {
            actualizarProductos();
        });

        // Manejo del ordenamiento
        const sortItems = document.querySelectorAll('#sortDropdown + .dropdown-menu .dropdown-item');
        sortItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                sortItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                actualizarProductos();
            });
        });

        // Actualizar contador del carrito
        fetch('index.php?ruta=carrito&accion=contar', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart')?.nextElementSibling;
            if (cartBadge) {
                cartBadge.textContent = data.total_productos || 0;
            }
        })
        .catch(error => console.error('Error al contar carrito:', error));

        // Actualizar contador de notificaciones
        <?php if (isset($_SESSION['id_cliente'])): ?>
        fetch('<?= BASE_URL ?>cliente/?ruta=notificacion&accion=contar', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElement

    ById('notificationBadge');
            if (badge) {
                badge.textContent = data.total_notificaciones || 0;
            }
        })
        .catch(error => console.error('Error:', error));
        <?php endif; ?>
    });

    // Función para mostrar notificación
    function showToast(message, type = 'success') {
        const toastContainer = document.querySelector('.toast-notification');
        const toastId = 'toast-' + Date.now();
        
        const toastHTML = `
            <div id="${toastId}" class="toast show align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.classList.remove('show');
                setTimeout(() => toastElement.remove(), 300);
            }
        }, 3000);
    }

    // Función para actualizar productos
    function actualizarProductos() {
        const categoria = document.querySelector('.category-item.active')?.dataset.categoria || 'all';
        const busqueda = document.getElementById('searchInput').value.trim();
        const stock = document.getElementById('inStockCheck').checked;
        const precioMin = parseFloat(document.getElementById('minPriceValue').textContent) || 0;
        const precioMax = parseFloat(document.getElementById('maxPriceValue').textContent) || 1000;
        const orden = document.querySelector('.dropdown-item.active')?.dataset.sort || 'relevancia';

        const params = new URLSearchParams({
            categoria,
            buscar: busqueda,
            stock: stock ? 1 : 0,
            precio_min: precioMin,
            precio_max: precioMax,
            orden
        });

        fetch(`index.php?ruta=catalogo&${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('productContainer');
            const countElement = document.getElementById('productCount');
            
            container.innerHTML = '';
            countElement.textContent = data.productos?.length || 0;
            
            if (!data.productos || data.productos.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> No se encontraron productos con los filtros seleccionados.
                        </div>
                    </div>
                `;
                return;
            }

            data.productos.forEach(producto => {
                const stockClass = producto.stock > 0 ? 'in-stock' : 'out-of-stock';
                const stockText = producto.stock > 0 ? `Disponible (${producto.stock} unidades)` : 'Agotado';
                const stockIcon = producto.stock > 0 ? 'fa-check-circle' : 'fa-times-circle';
                
                const randomBadge = Math.random() > 0.7 ? 
                    `<span class="product-badge ${Math.random() > 0.5 ? 'badge-new' : (Math.random() > 0.5 ? 'badge-sale' : 'badge-discount')}">
                        ${Math.random() > 0.5 ? 'NUEVO' : (Math.random() > 0.5 ? 'OFERTA' : '-20%')}
                    </span>` : '';
                
                const originalPrice = Math.random() > 0.7 ? 
                    `<span class="original-price">Bs. ${(parseFloat(producto.precio_unitario) * 1.2).toFixed(2)}</span>` : '';

                container.innerHTML += `
                    <div class="col">
                        <div class="product-card">
<div class="product-img-container">
    ${randomBadge}
    <img src="${BASE_URL}imagenes/${producto.imagen}" class="product-img" alt="${producto.nombre}">
</div>

                            <div class="product-body">
                                <h5 class="product-title">${producto.nombre}</h5>
                                <p class="product-brand">${producto.nombre_categoria}</p>
                                
                                <div class="product-price">
                                    <span class="current-price">Bs. ${parseFloat(producto.precio_unitario).toFixed(2)}</span>
                                    ${originalPrice}
                                </div>
                                
                                <p class="product-stock ${stockClass}">
                                    <i class="fas ${stockIcon} stock-icon"></i>
                                    ${stockText}
                                </p>
                                
                                <div class="product-actions">
                                    <div class="d-flex justify-content-between w-100">
                                        <a href="index.php?ruta=detalle_producto&id=${producto.id_producto}" class="btn-detail">
                                            <i class="fas fa-eye btn-icon"></i> Detalles
                                        </a>
                                        ${producto.stock > 0 ? 
                                            `<button class="btn-cart" onclick="agregarAlCarrito(${producto.id_producto})">
                                                <i class="fas fa-cart-plus btn-icon"></i> Añadir
                                            </button>` : 
                                            `<button class="btn-disabled" disabled>
                                                <i class="fas fa-ban btn-icon"></i> Agotado
                                            </button>`}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error('Error al actualizar productos:', error);
            document.getElementById('productContainer').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i> Error al cargar los productos: ${error.message}
                    </div>
                </div>
            `;
        });
    }

    function agregarAlCarrito(idProducto) {
        <?php if (!isset($_SESSION['id_cliente'])): ?>
            alert('Debes iniciar sesión para añadir productos al carrito');
            window.location.href = 'index.php?ruta=login';
            return;
        <?php endif; ?>

        fetch('index.php?ruta=carrito&accion=agregar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_producto=${idProducto}&cantidad=1`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const toast = new bootstrap.Toast(document.createElement('div'));
                toast._element.innerHTML = `
                    <div class="toast show position-fixed bottom-0 end-0 m-3">
                        <div class="toast-header bg-success text-white">
                            <strong class="me-auto">Éxito</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            Producto añadido al carrito
                        </div>
                    </div>
                `;
                document.body.appendChild(toast._element);
                toast.show();
                setTimeout(() => toast._element.remove(), 3000);

                const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart')?.nextElementSibling;
                if (cartBadge) {
                    cartBadge.textContent = data.total_productos || 0;
                }
            } else {
                alert(data.message || 'Error al agregar al carrito');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al comunicarse con el servidor');
        });
    }
    </script>
</body>
</html>