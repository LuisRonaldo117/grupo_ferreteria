<?php
$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros | Ferretería Industrial</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #d32f2f;
            --accent-color: #007bff;
            --light-bg: #f8f9fa;
            --dark-blue: #1a2a3a;
            --gold-accent: #FFD700;
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
        
        /* Contenido Nosotros - Estilos mejorados */
        .about-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1605153864431-a2795a1b2f95?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .about-hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .about-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .about-hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .about-section {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
            margin-bottom: 40px;
            border-top: 4px solid var(--accent-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .about-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,.1);
        }
        
        .section-title {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--gold-accent);
            border-radius: 2px;
        }
        
        .about-img {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,.1);
            transition: transform 0.3s ease;
            height: 100%;
            object-fit: cover;
        }
        
        .about-img:hover {
            transform: scale(1.02);
        }
        
        .img-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            height: 400px;
        }
        
        .feature-box {
            padding: 30px 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
            margin-bottom: 30px;
            transition: all 0.4s ease;
            text-align: center;
            border-top: 3px solid transparent;
            height: 100%;
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,.1);
            border-top: 3px solid var(--accent-color);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .feature-box:hover .feature-icon {
            transform: scale(1.1);
            color: var(--gold-accent);
        }
        
        .feature-box h4 {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-blue);
            font-family: 'Poppins', sans-serif;
        }
        
        /* Equipo - Estilos mejorados */
        .team-card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,.08);
            transition: all 0.4s ease;
            margin-bottom: 30px;
            background: white;
            text-align: center;
            padding-bottom: 20px;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,.15);
        }
        
        .team-img-container {
            height: 250px;
            overflow: hidden;
            position: relative;
        }
        
        .team-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .team-card:hover .team-img {
            transform: scale(1.1);
        }
        
        .team-card h4 {
            margin-top: 20px;
            font-weight: 600;
            color: var(--dark-blue);
            font-family: 'Poppins', sans-serif;
        }
        
        .team-card .position {
            color: var(--accent-color);
            font-weight: 500;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .social-links {
            margin-top: 15px;
        }
        
        .social-links a {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: #f5f5f5;
            color: var(--dark-blue);
            border-radius: 50%;
            margin: 0 3px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Timeline de historia */
        .timeline {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 0;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: var(--accent-color);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
            border-radius: 2px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 4px solid var(--accent-color);
            border-radius: 50%;
            top: 15px;
            z-index: 1;
        }
        
        .left {
            left: 0;
            text-align: right;
        }
        
        .right {
            left: 50%;
            text-align: left;
        }
        
        .left::after {
            right: -12px;
        }
        
        .right::after {
            left: -12px;
        }
        
        .timeline-content {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.08);
        }
        
        .timeline-year {
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }
        
        /* Estadísticas */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-blue) 100%);
            color: white;
            padding: 60px 0;
            margin: 60px 0;
            border-radius: 8px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--gold-accent);
            font-family: 'Poppins', sans-serif;
        }
        
        .stat-label {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        /* Testimonios */
        .testimonial-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,.05);
            margin-bottom: 30px;
            position: relative;
            border-left: 4px solid var(--accent-color);
        }
        
        .testimonial-card::before {
            content: '\201C';
            font-family: Georgia, serif;
            font-size: 4rem;
            color: rgba(0,0,0,0.1);
            position: absolute;
            top: 20px;
            left: 20px;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .author-info h5 {
            margin-bottom: 0;
            font-weight: 600;
            color: var(--dark-blue);
        }
        
        .author-info p {
            margin-bottom: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Iconos de navegación (mantenidos igual) */
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item::after {
                left: 21px;
            }
            
            .left, .right {
                left: 0;
                text-align: left;
            }
            
            .about-hero h1 {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Barra superior con logo y búsqueda (mantenido igual) -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">FERRETERÍA</a>
            
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
                <!-- Notificación -->
                <button class="btn-icon position-relative" onclick="window.location.href='<?= BASE_URL ?>cliente/?ruta=notificacion'">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger badge-notification rounded-pill" id="notificationBadge">0</span>
                </button>
                
                <!-- Carrito -->
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
                    <a class="nav-link" href="?ruta=catalogo">CATÁLOGO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="?ruta=nosotros">NOSOTROS</a>
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

    <!-- Contenido principal mejorado -->
    <main class="container my-5">
        <!-- Hero Section -->
        <section class="about-hero rounded">
            <div class="about-hero-content">
                <h1>MÁS DE 20 AÑOS CONSTRUYENDO CONFIANZA</h1>
                <p class="lead">Somos la ferretería líder en La Paz, comprometidos con la calidad y el servicio excepcional</p>
            </div>
        </section>

        <!-- Sección principal -->
        <section class="about-section">
            <h2 class="section-title">Nuestra Historia</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="img-container">
                        <img src="https://images.unsplash.com/photo-1600585152220-90363fe7e115?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="about-img" alt="Nuestra ferretería">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-4">Construyendo el futuro desde 2003</h3>
                    <p class="mb-4">Nuestra Ferretería nació en 2003 como un pequeño proyecto en La Paz. Lo que comenzó como un modesto local de herramientas hoy es una de las ferreterías más importantes y reconocidas de la ciudad, gracias a nuestro compromiso con la calidad y el servicio personalizado.</p>
                    <p>Nuestro fundador Matthew Cuthbert, un maestro constructor con más de 40 años de experiencia, identificó la necesidad de un proveedor de materiales de construcción que combinara productos de primera calidad con asesoramiento técnico especializado. Hoy, ese legado continúa con un equipo de expertos en construcción, fontanería, electricidad y más, siempre dispuestos a ayudarte.</p>
                </div>
            </div>
        </section>
        
        <!-- Timeline -->
        <section class="about-section">
            <h2 class="section-title">Nuestra Trayectoria</h2>
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <div class="timeline-year">2003</div>
                        <h4>Fundación</h4>
                        <p>Apertura de nuestro primer local en la Av. 16 de Julio, El Prado, con solo 3 empleados y un inventario básico de herramientas.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <div class="timeline-year">2010</div>
                        <h4>Primera Expansión</h4>
                        <p>Abrimos otra tienda más amplia en la Calle Aspiazu, Sopocachi y ampliamos nuestro catálogo.</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <div class="timeline-year">2015</div>
                        <h4>Reconocimiento Nacional</h4>
                        <p>Ganamos el premio al "Mejor Proveedor de Materiales de Construcción" otorgado por la Cámara de Construcción de La Paz.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <div class="timeline-year">2023</div>
                        <h4>Actualidad</h4>
                        <p>Contamos con 3 sucursales en la ciudad, más de 20 empleados y un catálogo de productos especializados.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Estadísticas -->
        <section class="stats-section rounded">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="20">0</div>
                            <div class="stat-label">Años de Experiencia</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="1000">0</div>
                            <div class="stat-label">Productos</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="5000">0</div>
                            <div class="stat-label">Clientes Satisfechos</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-count="20">0</div>
                            <div class="stat-label">Expertos en Equipo</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Nuestros valores -->
        <section class="about-section">
            <h2 class="section-title">Nuestros Valores</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h4>Calidad Garantizada</h4>
                        <p>Trabajamos exclusivamente con marcas líderes y proveedores certificados para ofrecer productos duraderos, seguros y confiables para tus proyectos.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Servicio Personalizado</h4>
                        <p>Nuestro equipo de expertos te asesora desde la planificación hasta la ejecución de tu proyecto, encontrando las mejores soluciones para tus necesidades.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Garantía y Soporte</h4>
                        <p>Todos nuestros productos cuentan con garantía extendida y respaldo tanto del fabricante como de nuestra empresa. Tu satisfacción es nuestra prioridad.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Nuestro equipo -->
        <section class="about-section">
            <h2 class="section-title">Conoce a Nuestro Equipo</h2>
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-icon">
                            <i class="fas fa-user-tie fa-5x text-primary mb-3"></i>
                        </div>
                        <h4>Ronaldo Mamani</h4>
                        <p class="position">Gerente General</p>
                        <p>Fundador y experto en construcción con más de 20 años de experiencia en el rubro.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-icon">
                            <i class="fas fa-user-check fa-5x text-primary mb-3"></i>
                        </div>
                        <h4>Pilar Gonzales</h4>
                        <p class="position">Jefa de Desarrollo Comercial</p>
                        <p>Lidera el crecimiento de ventas y la atención a proyectos clave del sector residencial.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-icon">
                            <i class="fas fa-user-cog fa-5x text-primary mb-3"></i>
                        </div>
                        <h4>Gabriela Barrera</h4>
                        <p class="position">Asesor Técnico</p>
                        <p>Ingeniera civil especializada en herramientas eléctricas y maquinaria pesada.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="team-card">
                        <div class="team-icon">
                            <i class="fas fa-user-friends fa-5x text-primary mb-3"></i>
                        </div>
                        <h4>Diana Carvajal</h4>
                        <p class="position">Jefa de Logística</p>
                        <p>Experta en cadena de suministros y distribución de materiales a gran escala.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Testimonios -->
        <section class="about-section">
            <h2 class="section-title">Lo que Dicen Nuestros Clientes</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"Como contratista, he trabajado con Ferretería por más de 10 años. Su asesoramiento técnico y la calidad de sus productos han sido clave para el éxito de mis proyectos."</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Juan Pérez" class="author-img">
                            <div class="author-info">
                                <h5>Juan Pérez</h5>
                                <p>Contratista General</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"Cuando renové mi casa, el equipo de Ferretería me guió en cada paso. Encontré todo lo que necesitaba y más, con precios competitivos y excelente servicio."</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="María Fernández" class="author-img">
                            <div class="author-info">
                                <h5>María Fernández</h5>
                                <p>Cliente Residencial</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"Como arquitecto, valoro mucho la variedad de productos especializados que ofrecen. Son mi primera opción para proyectos de alta gama donde la calidad es primordial."</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Carlos Rojas" class="author-img">
                            <div class="author-info">
                                <h5>Carlos Rojas</h5>
                                <p>Arquitecto</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Contador animado para las estadísticas
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
                
                function updateCount() {
                    const count = +counter.innerText;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                }
            });
            
            // Carrito
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
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>