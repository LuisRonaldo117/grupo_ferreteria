<?php
// Iniciar sesión
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/proyecto%20ferreteria/grupo_ferreteria/');
}

// Verificar si las variables están definidas
$producto = $producto ?? null;
$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';

if (!$producto) {
    header('Location: ?ruta=catalogo');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de <?= htmlspecialchars($producto['nombre']) ?> | Ferretería</title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome CDN -->
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
        
        /* Navbar */
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

        /* Detalles del producto */
        .product-detail-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .product-img {
            max-height: 300px;
            max-width: 100%;
            object-fit: contain;
        }
        
        .product-title {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-weight: 700;
            color: var(--secondary-color);
            font-size: 1.8rem;
            margin: 15px 0;
        }
        
        .product-stock {
            font-size: 1rem;
            margin-bottom: 15px;
        }
        
        .in-stock {
            color: #28a745;
        }
        
        .out-of-stock {
            color: var(--secondary-color);
        }
        
        .product-description {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 20px;
        }
        
        .product-category {
            font-size: 0.9rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .btn-navy {
            background-color: #003366;
            border-color: #003366;
            color: white;
            transition: all 0.3s;
            font-size: 0.85rem;
            padding: 5px 15px;
            border-radius: 4px;
        }

        .btn-navy:hover {
            background-color: #002244;
            border-color: #001a33;
            transform: translateY(-1px);
            color: white;
        }

        .btn-secondary {
            cursor: not-allowed;
            font-size: 0.85rem;
            padding: 5px 15px;
            border-radius: 4px;
            background-color: #cccccc;
            border-color: #cccccc;
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
            <form class="d-flex mx-4" style="flex-grow: 1; max-width: 600px;" id="searchForm">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Buscar productos..." aria-label="Search" id="searchInput">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- Íconos de navegación -->
            <div class="nav-icons d-flex">
                <button class="btn-icon position-relative" onclick="window.location.href='<?= BASE_URL ?>cliente/?ruta=notificacion'">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger badge-notification rounded-pill">3</span>
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
                    <a class="nav-link" href="?ruta=informate">INFORMATE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?ruta=contacto">CONTACTO</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container my-4">
        <div class="product-detail-container">
            <div class="row">
                <!-- Imagen del producto -->
                <div class="col-md-6">
                    <img src="<?= BASE_URL ?>imagenes/<?= htmlspecialchars($producto['imagen'] ?? 'assets/images/default.jpg') ?>" 
                         class="product-img img-fluid" 
                         alt="<?= htmlspecialchars($producto['nombre']) ?>">
                </div>
                <!-- Detalles del producto -->
                <div class="col-md-6">
                    <h2 class="product-title"><?= htmlspecialchars($producto['nombre']) ?></h2>
                    <p class="product-category">Categoría: <?= htmlspecialchars($producto['nombre_categoria']) ?></p>
                    <p class="product-price">Bs. <?= number_format($producto['precio_unitario'], 2) ?></p>
                    <p class="product-stock <?= $producto['stock'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                        <?= $producto['stock'] > 0 ? "Disponible ({$producto['stock']} unidades)" : "Agotado" ?>
                    </p>
                    <p class="product-description"><?= htmlspecialchars($producto['descripcion'] ?? 'Sin descripción disponible') ?></p>
                    <div class="product-actions">
                        <?php if ($producto['stock'] > 0): ?>
                        <button class="btn btn-navy" onclick="agregarAlCarrito(<?= $producto['id_producto'] ?>)">
                            <i class="fas fa-cart-plus me-1"></i> Añadir al carrito
                        </button>
                        <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-ban me-1"></i> Agotado
                        </button>
                        <?php endif; ?>
                        <a href="?ruta=catalogo" class="btn btn-outline-primary ms-2">Volver al catálogo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para añadir al carrito
        function agregarAlCarrito(idProducto) {
            <?php if(!isset($_SESSION['id_cliente'])): ?>
                alert('Debes iniciar sesión para añadir productos al carrito');
                window.location.href = '<?= BASE_URL ?>cliente/?ruta=login';
                return;
            <?php endif; ?>

            fetch('<?= BASE_URL ?>cliente/?ruta=carrito&accion=agregar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_producto=${idProducto}&cantidad=1`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 end-0 p-3';
                    toast.innerHTML = `
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header bg-success text-white">
                                <strong class="me-auto">Producto añadido</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Producto agregado al carrito
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                    
                    const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart').nextElementSibling;
                    if (cartBadge) {
                        cartBadge.textContent = data.total_productos;
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

        // Actualizar contador del carrito al cargar la página
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