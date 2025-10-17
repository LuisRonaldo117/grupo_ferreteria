<?php

$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';
$usuario = $_SESSION['cliente_usuario'] ?? 'usuario';

// Datos de ejemplo
$categorias = [
    ['id_categoria' => 1, 'nombre_categoria' => 'Herramientas Manuales'],
    ['id_categoria' => 2, 'nombre_categoria' => 'Herramientas Eléctricas'],
    ['id_categoria' => 3, 'nombre_categoria' => 'Materiales de Construcción'],
    ['id_categoria' => 4, 'nombre_categoria' => 'Tornillería y Sujeción'],
    ['id_categoria' => 5, 'nombre_categoria' => 'Electricidad'],
    ['id_categoria' => 6, 'nombre_categoria' => 'Fontanería'],
    ['id_categoria' => 7, 'nombre_categoria' => 'Pinturas y Accesorios'],
    ['id_categoria' => 8, 'nombre_categoria' => 'Seguridad Industrial']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferretería - Catálogo</title>
    
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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            padding-top: 120px;
        }
        
        /* Navbar superior */
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
        }
        
        .main-nav .nav-link:hover {
            color: #f8f9fa;
            text-decoration: underline;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
        }
        
        /* Carrusel */
        .carousel-container {
            margin-top: 110px;
            margin-bottom: 30px;
        }
        
        .carousel-item {
            height: 300px;
            background-size: cover;
            background-position: center;
        }
        
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 5px;
            bottom: 20px;
        }
        
        /* Categorías y Marcas */
        .categories-section, .brands-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
            text-transform: uppercase;
            font-size: 1.1rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
        }
        
        .category-card, .brand-card {
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        
        .category-card:hover, .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,.1);
        }
        
        .category-card i, .brand-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--accent-color);
        }
        
        /* Productos */
        .product-card {
            border: none;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,.08);
            transition: all 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,.1);
        }
        
        .product-img {
            height: 150px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        
        .product-title {
            font-weight: 600;
            font-size: 1rem;
            height: 40px;
            overflow: hidden;
        }
        
        .product-brand {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .price {
            font-weight: bold;
            color: var(--secondary-color);
            font-size: 1.2rem;
        }
        
        .btn-details {
            background-color: var(--accent-color);
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.8rem;
        }
        
        .btn-cart {
            background-color: var(--secondary-color);
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.8rem;
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
    </style>
</head>
<body>
    <!-- Barra superior con logo y búsqueda -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">FERRETERÍA</a>
            
            <!-- Barra de búsqueda -->
            <form class="d-flex mx-4" style="flex-grow: 1; max-width: 600px;">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Buscar productos..." aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Íconos de navegación -->
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
                
                <!-- Usuario - Dropdown -->
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

    <!-- Menú principal de navegación -->
    <nav class="navbar navbar-expand main-nav">
        <div class="container-fluid">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="?ruta=inicio">INICIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="?ruta=catalogo">CATÁLOGO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?ruta=nosotros">NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?ruta=informate">INFÓRMATE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?ruta=contacto">CONTACTO</a>
                </li>
            </ul>
        </div>
    </nav>

<!-- Enlaces de categorías -->
<a href="catalogo.php?categoria=<?= $categoria['id_categoria'] ?>" class="text-decoration-none">

    <!-- Carrusel de imágenes -->
    <div class="container carousel-container">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner rounded-3">
                <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1600585152220-90363fe7e115?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Las mejores herramientas</h5>
                        <p>Encuentra todo lo que necesitas para tus proyectos</p>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1605152276897-4f618f831968?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Ofertas especiales</h5>
                        <p>Aprovecha nuestros descuentos en marcas líderes</p>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Nuevos productos</h5>
                        <p>Descubre nuestras últimas incorporaciones</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Sección de Categorías -->
        <div class="categories-section">
            <h3 class="section-title">Categorías</h3>
            <div class="row">
                <?php foreach ($categorias as $categoria): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?= BASE_URL ?>cliente/?ruta=catalogo&categoria=<?= $categoria['id_categoria'] ?>">
                        <div class="category-card">
                            <i class="fas fa-tools"></i>
                            <h6><?= htmlspecialchars($categoria['nombre_categoria']) ?></h6>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch("<?= BASE_URL ?>cliente/?ruta=carrito&accion=contar", {
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
            });
        });

        // Actualizar contador de notificaciones
        <?php if (isset($_SESSION['id_cliente'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('<?= BASE_URL ?>cliente/?ruta=notificacion&accion=contar', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                if (badge) {
                    badge.textContent = data.total_notificaciones || 0;
                }
            })
            .catch(error => console.error('Error:', error));
        });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const totalProductos = carrito.reduce((total, item) => total + item.cantidad, 0);
            const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart')?.nextElementSibling;
            if (cartBadge) {
                cartBadge.textContent = totalProductos;
            }
        });
    </script>
        
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>