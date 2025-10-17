<?php

require_once 'config/paths.php';

$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infórmate | Ferretería</title>
    
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
            --text-muted: #6c757d;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            padding-top: 120px;
            line-height: 1.6;
        }
        
        /* Navbar superior */
        .top-navbar {
            background-color: white;
            box-shadow: var(--shadow-sm);
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
            transition: color 0.3s ease;
        }
        
        .main-nav .nav-link:hover {
            color: #f8f9fa;
            text-decoration: underline;
        }
        
        .main-nav .nav-link.active {
            color: var(--accent-color);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #ffffff, #e9ecef);
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }
        
        .hero-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Contenido Infórmate */
        .info-section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
            font-size: 1.8rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent-color);
        }
        
        /* Pestañas */
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 30px;
        }
        
        .nav-tabs .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 12px 20px;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--accent-color);
            border-bottom: 3px solid var(--accent-color);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--accent-color);
            font-weight: 600;
            border-bottom: 3px solid var(--accent-color);
        }
        
        .nav-tabs .nav-link i {
            margin-right: 8px;
        }
        
        /* Tarjetas de artículos */
        .article-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            background: white;
        }
        
        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .article-img {
            height: 220px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.3s ease;
        }
        
        .article-card:hover .article-img {
            transform: scale(1.05);
        }
        
        .article-body {
            padding: 20px;
        }
        
        .article-date {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 10px;
        }
        
        .article-title {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 15px;
        }
        
        .badge-category {
            background: linear-gradient(90deg, var(--accent-color), #0056b3);
            color: white;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: inline-block;
        }
        
        .article-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .article-card .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
        }
        
        /* Tutoriales */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .tutorial-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            background: white;
        }
        
        .tutorial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .tutorial-body {
            padding: 20px;
        }
        
        .tutorial-title {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        
        /* Consejos */
        .tip-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
        }
        
        .tip-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .tip-card i {
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }
        
        .tip-card h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .tip-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
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
            transition: all 0.3s ease;
            color: var(--primary-color);
            background: transparent;
        }
        
        .nav-icons .btn-icon:hover {
            background-color: #e9ecef;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            background: var(--secondary-color);
        }
        
        /* Botón Volver Arriba */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: #0056b3;
        }
        
        /* Skeleton Loading */
        .skeleton {
            background: #e9ecef;
            border-radius: 4px;
            animation: pulse 1.5s infinite;
        }
        
        .skeleton-img {
            height: 220px;
            width: 100%;
        }
        
        .skeleton-text {
            height: 16px;
            margin-bottom: 10px;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Barra superior con logo y búsqueda -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?ruta=inicio">FERRETERÍA</a>
            
            <!-- Barra de búsqueda -->
            <form class="d-flex mx-4" style="flex-grow: 1; max-width: 600px;" action="index.php?ruta=catalogo" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="buscar" placeholder="Buscar productos..." aria-label="Search">
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
                    <button class="btn-icon dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de usuario">
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
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=inicio">INICIO</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=catalogo">CATÁLOGO</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=nosotros">NOSOTROS</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php?ruta=informate">INFÓRMATE</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?ruta=contacto">CONTACTO</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container my-5">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1 class="hero-title">Infórmate con Nosotros</h1>
            <p class="hero-subtitle">Explora artículos, tutoriales y consejos prácticos para llevar tus proyectos de construcción y bricolaje al siguiente nivel.</p>
        </div>

        <!-- Sección principal -->
        <div class="info-section">
            <h2 class="section-title">Recursos para tus Proyectos</h2>
            
            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs mb-4" id="infoTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="articles-tab" data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab" aria-controls="articles" aria-selected="true">
                        <i class="fas fa-newspaper"></i> Artículos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tutorials-tab" data-bs-toggle="tab" data-bs-target="#tutorials" type="button" role="tab" aria-controls="tutorials" aria-selected="false">
                        <i class="fas fa-video"></i> Tutoriales
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tips-tab" data-bs-toggle="tab" data-bs-target="#tips" type="button" role="tab" aria-controls="tips" aria-selected="false">
                        <i class="fas fa-lightbulb"></i> Consejos
                    </button>
                </li>
            </ul>
            
            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="infoTabsContent">
                <!-- Artículos -->
                <div class="tab-pane fade show active" id="articles" role="tabpanel" aria-labelledby="articles-tab">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="article-card">
                                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="article-img" alt="Herramientas eléctricas">
                                <div class="article-body">
                                    <span class="badge-category">Herramientas</span>
                                    <p class="article-date">15 Mayo 2025</p>
                                    <h5 class="article-title">Las 5 mejores herramientas eléctricas para principiantes</h5>
                                    <p>Descubre cuáles son las herramientas esenciales que todo aficionado al bricolaje debería tener en su taller.</p>
                                    <a href="index.php?ruta=articulo&id_articulo=1" class="btn btn-sm btn-outline-primary">Leer más</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="article-card">
                                <img src="https://images.unsplash.com/photo-1606744837616-56c9a5c6a6eb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="article-img" alt="Pintura">
                                <div class="article-body">
                                    <span class="badge-category">Pintura</span>
                                    <p class="article-date">2 Mayo 2025</p>
                                    <h5 class="article-title">Cómo elegir la pintura perfecta para cada superficie</h5>
                                    <p>Guía completa para seleccionar el tipo de pintura adecuado según el material y las condiciones del ambiente.</p>
                                    <a href="index.php?ruta=articulo&id_articulo=2" class="btn btn-sm btn-outline-primary">Leer más</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="article-card">
                                <img src="https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="article-img" alt="Fontanería">
                                <div class="article-body">
                                    <span class="badge-category">Fontanería</span>
                                    <p class="article-date">20 Abril 2025</p>
                                    <h5 class="article-title">Soluciones rápidas para problemas comunes de fontanería</h5>
                                    <p>Aprende a resolver esos pequeños problemas de tuberías que pueden convertirse en grandes dolores de cabeza.</p>
                                    <a href="index.php?ruta=articulo&id_articulo=3" class="btn btn-sm btn-outline-primary">Leer más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-primary">Ver todos los artículos</a>
                    </div>
                </div>
                
                <!-- Tutoriales -->
                <div class="tab-pane fade" id="tutorials" role="tabpanel" aria-labelledby="tutorials-tab">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="tutorial-card">
                                <div class="video-container">
                                    <iframe src="https://www.youtube.com/embed/5e290TCqf6o" title="Guía de uso de herramientas eléctricas" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="tutorial-body">
                                    <h4 class="tutorial-title">Curso de Carpintería</h4>
                                    <p>Aprende las técnicas básicas de carpintería para crear, reparar y personalizar muebles y estructuras de madera de forma segura y práctica.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="tutorial-card">
                                <div class="video-container">
                                    <iframe src="https://www.youtube.com/embed/N0CM9Oklnls" title="Instalación de estantes flotantes" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="tutorial-body">
                                    <h4 class="tutorial-title">Ideas para pintar el interior de casa</h4>
                                    <p>Descubre ideas creativas, técnicas de aplicación y combinaciones de colores que te ayudarán a renovar y pintar, logrando un ambiente lleno de estilo, armonía y personalidad.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="tutorial-card">
                                <div class="video-container">
                                    <iframe src="https://www.youtube.com/embed/Mj_QCtz7pUc" title="Cómo cambiar un enchufe eléctrico" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="tutorial-body">
                                    <h4 class="tutorial-title">Herramientas de Fontanería</h4>
                                    <p>Conoce las herramientas esenciales de fontanería y aprende cómo usarlas correctamente para realizar reparaciones e instalaciones en el hogar de forma práctica y segura.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-primary">Ver más tutoriales</a>
                    </div>
                </div>
                
                <!-- Consejos -->
                <div class="tab-pane fade" id="tips" role="tabpanel" aria-labelledby="tips-tab">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="tip-card">
                                <i class="fas fa-tools"></i>
                                <h5>Mantenimiento de herramientas</h5>
                                <p>Limpia tus herramientas después de cada uso y guárdalas en un lugar seco para evitar la oxidación.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="tip-card">
                                <i class="fas fa-paint-roller"></i>
                                <h5>Pintura más duradera</h5>
                                <p>Prepara bien la superficie limpiando y lijando antes de aplicar pintura para un acabado duradero.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="tip-card">
                                <i class="fas fa-bolt"></i>
                                <h5>Trabajo eléctrico seguro</h5>
                                <p>Corta la corriente en el cuadro eléctrico antes de realizar cualquier trabajo en instalaciones eléctricas.</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-primary">Ver más consejos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Volver Arriba -->
    <a href="#" class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('index.php?ruta=carrito&accion=contar', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cartBadge');
                if (cartBadge) {
                    cartBadge.textContent = data.total_productos || 0;
                }
            })
            .catch(error => console.error('Error al contar carrito:', error));

            // Simular skeleton loading
            const cards = document.querySelectorAll('.article-card, .tutorial-card, .tip-card');
            cards.forEach(card => {
                card.classList.add('skeleton');
                setTimeout(() => card.classList.remove('skeleton'), 1000);
            });
        });

        // Botón Volver Arriba
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
    </script>
</body>
</html>