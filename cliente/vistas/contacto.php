<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';

// Obtener el correo del cliente si está autenticado
if (isset($_SESSION['id_cliente'])) {
    try {
        $db = new PDO("mysql:host=127.0.0.1;dbname=grupo_ferreteria;charset=utf8mb4", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT p.correo FROM persona p JOIN cliente c ON p.id_persona = c.id_persona WHERE c.id_cliente = :id_cliente";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_cliente', $_SESSION['id_cliente'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['correo_cliente'] = $result['correo'] ?? '';
    } catch (PDOException $e) {
        error_log("Error al obtener correo: " . $e->getMessage());
        $_SESSION['correo_cliente'] = '';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto | Ferretería Industrial</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS para el mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    
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
            margin-top: 10px;
            background-color: var(--primary-color);
            color: white;
            padding: 8px 0;
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
        .contact-hero {
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
        
        .contact-hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .contact-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .contact-hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        /* Secciones de contacto */
        .contact-section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
            margin-bottom: 40px;
            border-top: 4px solid var(--accent-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,.15);
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
        
        /* Mapa */
        #map {
            height: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,.1);
            z-index: 1;
            border: 1px solid rgba(0,0,0,.1);
        }
        
        /* Tarjetas de sucursal */
        .branch-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,.08);
            transition: all 0.4s ease;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .branch-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,.15);
        }
        
        .branch-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-blue) 100%);
            color: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .branch-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .branch-body {
            padding: 25px;
            background: white;
        }
        
        .stock-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 5px;
        }
        
        .stock-available {
            background-color: #d4edda;
            color: #155724;
        }
        
        .stock-low {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .stock-out {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Formulario */
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-blue);
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--accent-color) 0%, #0069d9 100%);
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }
        
        /* Iconos de contacto */
        .contact-icon {
            font-size: 1.8rem;
            color: var(--accent-color);
            margin-right: 15px;
            width: 50px;
            height: 50px;
            background: rgba(0, 123, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .contact-info:hover .contact-icon {
            background: var(--accent-color);
            color: white;
            transform: scale(1.1);
        }
        
        .contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 8px;
        }
        
        .contact-info:hover {
            background: rgba(0,0,0,0.03);
        }
        
        .contact-text h5 {
            font-weight: 600;
            color: var(--dark-blue);
            margin-bottom: 5px;
            font-family: 'Poppins', sans-serif;
        }
        
        .contact-text p {
            margin-bottom: 0;
            color: #555;
        }
        
        /* Social Links */
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 10px;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .social-links a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .facebook {
            background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        }
        
        .twitter {
            background: linear-gradient(135deg, #1da1f2 0%, #0d8ecf 100%);
        }
        
        .instagram {
            background: linear-gradient(135deg, #405de6 0%, #5851db 20%, #833ab4 40%, #c13584 60%, #e1306c 80%, #fd1d1d 100%);
        }
        
        .whatsapp {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        }
        
        /* Alertas */
        .alert {
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 30px;
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
        @media (max-width: 992px) {
            .contact-hero h1 {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 1.6rem;
            }
        }
        
        @media (max-width: 768px) {
            .contact-hero {
                padding: 70px 0;
            }
            
            .contact-hero h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.4rem;
            }
            
            #map {
                height: 350px;
            }
        }
        /* Estilos específicos para el navbar superior */
        .top-navbar {
            height: 60px;
            padding: 0.5rem 1rem;
        }

        .top-navbar .form-control {
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
        }

        .top-navbar .btn-outline-primary {
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-color: #ced4da;
            border-width: 1px !important;
            border-left: none !important;
        }

        .top-navbar .input-group {
            height: 38px;
            border-radius: 4px;
            overflow: hidden;
        }

        .navbar-brand {
            font-size: 1.8rem;
            margin-right: 1rem;
        }

        .nav-icons .btn-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Barra superior con logo y búsqueda -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">FERRETERÍA</a>
            
            <!-- Barra de búsqueda - Versión corregida -->
            <form class="d-flex mx-4 flex-grow-1" style="max-width: 600px;">
                <div class="input-group">
                    <input class="form-control py-2 border-end-0" type="search" placeholder="Buscar productos..." aria-label="Search">
                    <button class="btn btn-outline-primary py-2 border-start-0" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- Íconos de navegación -->
            <div class="nav-icons d-flex">
                <button class="btn-icon position-relative" onclick="window.location.href='<?= BASE_URL ?>cliente/?ruta=notificacion'">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger badge-notification rounded-pill" id="notificationBadge">0</span>
                </button>
                
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

    <!-- Menú principal de navegación (mantenido igual) -->
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
                    <a class="nav-link" href="?ruta=nosotros">NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?ruta=informate">INFÓRMATE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="?ruta=contacto">CONTACTO</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero rounded">
        <div class="contact-hero-content">
            <h1>CONTÁCTANOS</h1>
            <p class="lead">Estamos aquí para ayudarte con cualquier consulta, reclamo o solicitud que tengas</p>
        </div>
    </section>

    <!-- Contenido principal -->
    <main class="container my-5">
        <!-- Mensaje de retroalimentación -->
        <?php if (isset($_SESSION['reclamo_message'])): ?>
            <div class="alert <?= $_SESSION['reclamo_success'] ? 'alert-success' : 'alert-danger' ?>">
                <i class="fas <?= $_SESSION['reclamo_success'] ? 'fa-check-circle' : 'fa-exclamation-circle' ?> me-2"></i>
                <?= htmlspecialchars($_SESSION['reclamo_message']) ?>
            </div>
            <?php unset($_SESSION['reclamo_message'], $_SESSION['reclamo_success']); ?>
        <?php endif; ?>

        <!-- Sección de mapa y sucursales -->
        <div class="contact-section">
            <h2 class="section-title">Nuestras Sucursales</h2>
            
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div id="map"></div>
                </div>
                
                <div class="col-lg-4">
                    <div class="branch-card">
                        <div class="branch-header">
                            <h4 class="mb-0"><i class="fas fa-store me-2"></i>Sucursal El Prado</h4>
                        </div>
                        <div class="branch-body">
                            <p><i class="fas fa-map-marker-alt text-primary me-2"></i> Av. 16 de Julio #789, El Prado</p>
                            <p><i class="fas fa-phone text-primary me-2"></i> (591) 77683912</p>
                            <p><i class="fas fa-clock text-primary me-2"></i> Lunes a Sábado: 8:30 - 19:30</p>
                            <p><i class="fas fa-boxes text-primary me-2"></i> 
                                <span class="stock-status stock-available"><i class="fas fa-check-circle me-1"></i> Stock disponible</span>
                            </p>
                            <button class="btn btn-sm btn-primary mt-2" onclick="focusOnMap(1)">
                                <i class="fas fa-search-location me-1"></i> Ver en mapa
                            </button>
                        </div>
                    </div>
                    
                    <div class="branch-card">
                        <div class="branch-header">
                            <h4 class="mb-0"><i class="fas fa-store me-2"></i>Sucursal Sopocachi</h4>
                        </div>
                        <div class="branch-body">
                            <p><i class="fas fa-map-marker-alt text-primary me-2"></i> Calle Aspiazu #456, Sopocachi</p>
                            <p><i class="fas fa-phone text-primary me-2"></i> (591) 78965423</p>
                            <p><i class="fas fa-clock text-primary me-2"></i> Lunes a Viernes: 9:00 - 19:00</p>
                            <p><i class="fas fa-boxes text-primary me-2"></i> 
                                <span class="stock-status stock-low"><i class="fas fa-exclamation-circle me-1"></i> Stock limitado</span>
                            </p>
                            <button class="btn btn-sm btn-primary mt-2" onclick="focusOnMap(2)">
                                <i class="fas fa-search-location me-1"></i> Ver en mapa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección de contacto y formulario -->
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-section h-100">
                    <h2 class="section-title">Envíanos un Mensaje</h2>
                    <form id="contactForm" method="POST" action="?ruta=contacto">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($nombre . ' ' . $apellido) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_SESSION['correo_cliente'] ?? '') ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <option value="" selected disabled>Seleccione un asunto</option>
                                <option value="Consulta general">Consulta general</option>
                                <option value="Reclamo">Reclamo</option>
                                <option value="Soporte técnico">Soporte técnico</option>
                                <option value="Sugerencia">Sugerencia</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Describa su consulta o reclamo con el mayor detalle posible..."></textarea>
                        </div>
                        <button type="submit" name="submit_reclamo" class="btn btn-submit">
                            <i class="fas fa-paper-plane me-2"></i> Enviar mensaje
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="contact-section h-100">
                    <h2 class="section-title">Información de Contacto</h2>
                    
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Atención al Cliente</h5>
                            <p>Estamos disponibles para atender tus consultas y solicitudes.</p>
                            <p><strong>Teléfono:</strong> +591 77584652</p>
                            <p><strong>Email:</strong> contacto@ferreteria.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Soporte Técnico</h5>
                            <p>Asesoría técnica especializada para tus proyectos.</p>
                            <p><strong>Teléfono:</strong> +591 78632451</p>
                            <p><strong>Email:</strong> soporte@ferreteria.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Oficina Central</h5>
                            <p>Av. 16 de Julio #789, El Prado, La Paz</p>
                            <p><strong>Horario:</strong> Lunes a Sábado: 8:30 - 19:30</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="mb-3"><i class="fas fa-share-alt text-primary me-2"></i> Síguenos en Redes</h5>
                        <div class="social-links">
                            <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="whatsapp"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS para el mapa -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Inicializar mapa centrado en La Paz
        var map = L.map('map').setView([-16.5000, -68.1500], 13);

        // Añadir capa de mapa base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Datos de las sucursales
        var branches = [
            {
                id: 1,
                name: "Sucursal El Prado",
                latlng: [-16.4965, -68.1342],
                address: "Av. 16 de Julio #789, El Prado",
                phone: "(591) 77683912",
                hours: "Lunes a Sábado: 8:30 - 19:30",
                stock: "available"
            },
            {
                id: 2,
                name: "Sucursal Sopocachi",
                latlng: [-16.5043, -68.1307],
                address: "Calle Aspiazu #456, Sopocachi",
                phone: "(591) 78965423",
                hours: "Lunes a Viernes: 9:00 - 19:00",
                stock: "low"
            },
        ];

        // Iconos personalizados para cada estado de stock
        var availableIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var lowIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var outIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Añadir marcadores al mapa
        branches.forEach(function(branch) {
            var icon;
            var stockStatus;
            
            switch(branch.stock) {
                case "available":
                    icon = availableIcon;
                    stockStatus = "<span style='color: #155724; background-color: #d4edda; padding: 3px 10px; border-radius: 10px; font-size: 0.8rem;'><i class='fas fa-check-circle me-1'></i> Stock disponible</span>";
                    break;
                case "low":
                    icon = lowIcon;
                    stockStatus = "<span style='color: #856404; background-color: #fff3cd; padding: 3px 10px; border-radius: 10px; font-size: 0.8rem;'><i class='fas fa-exclamation-circle me-1'></i> Stock limitado</span>";
                    break;
                case "out":
                    icon = outIcon;
                    stockStatus = "<span style='color: #721c24; background-color: #f8d7da; padding: 3px 10px; border-radius: 10px; font-size: 0.8rem;'><i class='fas fa-times-circle me-1'></i> Sin stock</span>";
                    break;
            }
            
            var marker = L.marker(branch.latlng, {icon: icon}).addTo(map)
                .bindPopup(`
                    <div style="min-width: 250px;">
                        <h5 style="font-weight: 600; color: var(--primary-color); margin-bottom: 10px;">
                            <i class="fas fa-store me-1"></i> ${branch.name}
                        </h5>
                        <p style="margin-bottom: 5px;"><i class="fas fa-map-marker-alt text-primary me-2"></i> ${branch.address}</p>
                        <p style="margin-bottom: 5px;"><i class="fas fa-phone text-primary me-2"></i> ${branch.phone}</p>
                        <p style="margin-bottom: 10px;"><i class="fas fa-clock text-primary me-2"></i> ${branch.hours}</p>
                        <div style="margin-bottom: 10px;">${stockStatus}</div>
                        <button onclick="focusOnMap(${branch.id})" 
                                style="width: 100%; background: var(--accent-color); color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-search-location me-1"></i> Ver detalles
                        </button>
                    </div>
                `);
            
            branch.marker = marker;
        });

        // Función para centrar el mapa en una sucursal específica
        function focusOnMap(branchId) {
            var branch = branches.find(b => b.id === branchId);
            if (branch) {
                map.flyTo(branch.latlng, 16, {
                    duration: 1,
                    easeLinearity: 0.25
                });
                branch.marker.openPopup();
            }
            return false;
        }

        // Cargar notificaciones del carrito
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
    </script>
</body>
</html>