<?php

require_once 'config/paths.php';

$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';

$articulos = [
    1 => [
        'titulo' => 'Las 5 mejores herramientas eléctricas para principiantes',
        'categoria' => 'Herramientas',
        'fecha' => '15 Mayo 2025',
        'imagen' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
        'resumen' => 'Descubre cuáles son las herramientas esenciales que todo aficionado al bricolaje debería tener en su taller.',
        'contenido' => '
            <p>Si estás comenzando en el mundo del bricolaje, elegir las herramientas adecuadas puede marcar una gran diferencia en la calidad y facilidad de tus proyectos. Aquí te presentamos las cinco herramientas eléctricas esenciales para principiantes:</p>
            <h3>1. Taladro eléctrico</h3>
            <p>Un taladro eléctrico es imprescindible para perforar agujeros y atornillar. Opta por un modelo con velocidad variable y función de percusión para mayor versatilidad.</p>
            <h3>2. Sierra circular</h3>
            <p>Ideal para cortes rectos en madera y otros materiales. Busca una sierra con guía láser para precisión.</p>
            <h3>3. Lijadora orbital</h3>
            <p>Perfecta para alisar superficies antes de pintar o barnizar. Las lijadoras orbitales son fáciles de manejar y ofrecen un acabado uniforme.</p>
            <h3>4. Amoladora angular</h3>
            <p>Útil para cortar, pulir y desbastar metal, piedra o cerámica. Asegúrate de usar discos adecuados para cada tarea.</p>
            <h3>5. Multímetro</h3>
            <p>Esencial para diagnosticar problemas eléctricos. Un multímetro digital sencillo es suficiente para principiantes.</p>
            <p>Con estas herramientas, estarás bien equipado para abordar una amplia variedad de proyectos de bricolaje. ¡Empieza a construir con confianza!</p>
        ',
        'keywords' => 'herramientas eléctricas, bricolaje, taladro, sierra circular, lijadora'
    ],
    2 => [
        'titulo' => 'Cómo elegir la pintura perfecta para cada superficie',
        'categoria' => 'Pintura',
        'fecha' => '2 Mayo 2025',
        'imagen' => 'https://images.unsplash.com/photo-1606744837616-56c9a5c6a6eb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
        'resumen' => 'Guía completa para seleccionar el tipo de pintura adecuado según el material y las condiciones del ambiente.',
        'contenido' => '
            <p>Elegir la pintura adecuada es clave para obtener un acabado duradero y estético. Aquí te explicamos cómo seleccionar la pintura perfecta según la superficie y las condiciones:</p>
            <h3>Paredes interiores</h3>
            <p>Para paredes de yeso o drywall, usa pintura acrílica mate o satinado. Son fáciles de limpiar y resisten bien la humedad en baños y cocinas.</p>
            <img src="https://images.unsplash.com/photo-1618221710640-c0eaaa2adb49?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Pintura paredes" class="content-img">
            <h3>Madera</h3>
            <p>La pintura esmalte al agua o al aceite es ideal para muebles y molduras de madera. Aplica una imprimación primero para mejor adherencia.</p>
            <h3>Metal</h3>
            <p>Usa pintura anticorrosiva para superficies metálicas expuestas al exterior. Las pinturas en spray son prácticas para objetos pequeños.</p>
            <h3>Condiciones ambientales</h3>
            <p>En áreas húmedas, elige pinturas con protección antimoho. Para exteriores, opta por pinturas resistentes a los rayos UV y la intemperie.</p>
            <p>Recuerda preparar bien la superficie: limpia, lija y aplica imprimación si es necesario. ¡Con la pintura correcta, tus proyectos lucirán profesionales!</p>
        ',
        'keywords' => 'pintura, superficies, acrílica, esmalte, anticorrosiva'
    ],
    3 => [
        'titulo' => 'Soluciones rápidas para problemas comunes de fontanería',
        'categoria' => 'Fontanería',
        'fecha' => '20 Abril 2025',
        'imagen' => 'https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
        'resumen' => 'Aprende a resolver esos pequeños problemas de tuberías que pueden convertirse en grandes dolores de cabeza.',
        'contenido' => '
            <p>Los problemas de fontanería pueden ser frustrantes, pero muchos se pueden solucionar sin llamar a un profesional. Aquí tienes soluciones rápidas para problemas comunes:</p>
            <h3>Fugas en grifos</h3>
            <p>La mayoría de las fugas se deben a juntas desgastadas. Cierra el agua, desmonta el grifo y reemplaza la junta de goma.</p>
            <h3>Tuberías obstruidas</h3>
            <p>Usa un desatascador manual o un producto desatascador químico. Para obstrucciones persistentes, prueba con un alambre de fontanería.</p>
            <h3>Baja presión de agua</h3>
            <p>Limpia el cabezal de la ducha o el filtro del grifo, que suelen acumular sedimentos. Si el problema persiste, revisa las válvulas de cierre.</p>
            <h3>Inodoros que gotean</h3>
            <p>Ajusta o reemplaza la válvula de llenado o la aleta en el tanque del inodoro. Usa un kit de reparación universal para facilitar el proceso.</p>
            <p>Con estas soluciones, puedes ahorrar tiempo y dinero. Siempre corta el suministro de agua antes de trabajar y usa herramientas adecuadas.</p>
        ',
        'keywords' => 'fontanería, fugas, tuberías, presión de agua, inodoros'
    ]
];

$id_articulo = isset($_GET['id_articulo']) ? (int)$_GET['id_articulo'] : 0;

// Validar si el artículo existe
$articulo = isset($articulos[$id_articulo]) ? $articulos[$id_articulo] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $articulo ? htmlspecialchars($articulo['titulo']) : 'Artículo no encontrado' ?> | Ferretería</title>
    <meta name="description" content="<?= $articulo ? htmlspecialchars($articulo['resumen']) : 'Explora recursos y consejos para tus proyectos de bricolaje.' ?>">
    <meta name="keywords" content="<?= $articulo ? htmlspecialchars($articulo['keywords']) : 'ferretería, bricolaje, herramientas' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
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
        .hero-article {
            position: relative;
            background: url('<?= $articulo ? htmlspecialchars($articulo['imagen']) : '' ?>') no-repeat center center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 40px;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .hero-article::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 20px;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .badge-category {
            background: var(--gradient-accent);
            color: white;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            text-transform: uppercase;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        
        .badge-category:hover {
            transform: scale(1.05);
        }
        
        /* Contenido del artículo */
        .article-section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: var(--shadow-md);
            margin-bottom: 40px;
            border: 1px solid #e9ecef;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }
        
        .content-img {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: var(--shadow-sm);
        }
        
        .article-content {
            font-size: 1.1rem;
            color: #333;
        }
        
        .article-content h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 15px;
            font-family: 'Roboto', sans-serif;
        }
        
        .article-content p {
            margin-bottom: 20px;
            color: var(--text-muted);
        }
        
        /* Sidebar */
        .sidebar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }
        
        .related-article {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        
        .related-article:hover {
            transform: translateX(5px);
        }
        
        .related-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        
        .related-title {
            font-size: 0.95rem;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Share Buttons */
        .share-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: transform 0.3s ease;
        }
        
        .share-btn:hover {
            transform: scale(1.1);
        }
        
        .share-btn.facebook { background: #3b5998; }
        .share-btn.twitter { background: #1da1f2; }
        .share-btn.whatsapp { background: #25d366; }
        
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
        
        /* Botón Volver Arriba */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--gradient-accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
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
            transform: scale(1.1);
        }
        
        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsividad */
        @media (max-width: 768px) {
            .hero-title { font-size: 2rem; }
            .hero-article { height: 300px; }
            .article-section { padding: 20px; }
            .content-img { max-width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Barra superior con logo y búsqueda -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?ruta=inicio">FERRETERÍA</a>
            
            <form class="d-flex mx-4" style="flex-grow: 1; max-width: 600px;" action="index.php?ruta=catalogo" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="buscar" placeholder="Buscar productos..." aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">
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
                    <button class="btn-icon dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de usuario">
                        <?php if(isset($_SESSION['id_cliente'])): ?>
                            <i class="fas fa-user-check text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <?php if(isset($_SESSION['id_cliente'])): ?>
                            <li><h6 class="dropdown-header">Bienvenido, <?= htmlspecialchars($nombre) ?></h6></li>
                            <li><a class="dropdown-item" href="index.php?ruta=usuario"><i class="fas fa-user-circle me-2"></i>Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?ruta=logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="index.php?ruta=login"><i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="index.php?ruta=registro"><i class="fas fa-user-plus me-2"></i>Registrarse</a></li>
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
        <?php if ($articulo): ?>
            <!-- Hero Section -->
            <div class="hero-article">
                <div class="hero-content">
                    <span class="badge-category"><?= htmlspecialchars($articulo['categoria']) ?></span>
                    <h1 class="hero-title"><?= htmlspecialchars($articulo['titulo']) ?></h1>
                    <div class="article-meta">
                        <span><i class="fas fa-calendar-alt me-1"></i><?= htmlspecialchars($articulo['fecha']) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Contenido y Sidebar -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="article-section">
                        <div class="article-content" itemscope itemtype="https://schema.org/Article">
                            <?= $articulo['contenido'] ?>
                            <meta itemprop="datePublished" content="<?= date('Y-m-d', strtotime($articulo['fecha'])) ?>">
                            <meta itemprop="headline" content="<?= htmlspecialchars($articulo['titulo']) ?>">
                            <meta itemprop="image" content="<?= htmlspecialchars($articulo['imagen']) ?>">
                        </div>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" class="share-btn facebook" target="_blank" aria-label="Compartir en Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($articulo['titulo']) ?>" class="share-btn twitter" target="_blank" aria-label="Compartir en Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode($articulo['titulo'] . ' ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" class="share-btn whatsapp" target="_blank" aria-label="Compartir en WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                        <div class="text-center mt-4">
                            <a href="index.php?ruta=informate" class="btn btn-primary btn-lg" style="background: var(--gradient-accent); border: none;">Volver a Infórmate</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <h4 class="mb-4" style="color: var(--primary-color); font-family: 'Roboto', sans-serif;">Artículos relacionados</h4>
                        <?php foreach ($articulos as $id => $rel) {
                            if ($id != $id_articulo) {
                                echo '
                                <a href="index.php?ruta=articulo&id_articulo=' . $id . '" class="related-article">
                                    <img src="' . htmlspecialchars($rel['imagen']) . '" alt="' . htmlspecialchars($rel['titulo']) . '" class="related-img">
                                    <span class="related-title">' . htmlspecialchars($rel['titulo']) . '</span>
                                </a>';
                            }
                        } ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="article-section text-center">
                <h1 class="article-title">Artículo no encontrado</h1>
                <p class="text-muted">Lo sentimos, el artículo que buscas no está disponible.</p>
                <a href="index.php?ruta=informate" class="btn btn-primary btn-lg" style="background: var(--gradient-accent); border: none;">Volver a Infórmate</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Botón Volver Arriba -->
    <a href="#" class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Actualizar badge del carrito
        document.addEventListener('DOMContentLoaded', function() {
            fetch('index.php?ruta=carrito&accion=contar', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cartBadge');
                if (cartBadge) cartBadge.textContent = data.total_productos || 0;
            })
            .catch(error => console.error('Error al contar carrito:', error));
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