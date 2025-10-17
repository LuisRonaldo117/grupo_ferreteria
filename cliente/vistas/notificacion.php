<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/proyecto%20ferreteria/grupo_ferreteria/');
}

$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones | Ferretería</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
        .notifications-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .notification-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
            transition: all 0.3s;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item:hover {
            background-color: #f9f9f9;
        }
        
        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .notification-pedido {
            background-color: rgba(0, 123, 255, 0.1);
            color: var(--accent-color);
        }
        
        .notification-promocion {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .notification-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .badge-estado {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-procesado {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .badge-enviado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-entregado {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .badge-cancelado {
            background-color: #f8d7da;
            color: #721c24;
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
        
        .empty-state {
            text-align: center;
            padding: 40px 0;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid align-items-center">
            <a class="navbar-brand me-4" href="index.php">FERRETERÍA</a>
            <form class="d-flex flex-grow-1 mx-2" style="max-width: 600px;">
                <div class="input-group flex-nowrap">
                    <input class="form-control border-end-0" type="search" placeholder="Buscar productos..." aria-label="Search">
                    <button class="btn btn-outline-primary border-start-0 px-3" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
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

    <nav class="navbar navbar-expand main-nav">
        <div class="container-fluid">
            <ul class="navbar-nav mx-auto gap-2">
                <li class="nav-item">
                    <a class="nav-link px-3" href="?ruta=inicio">INICIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?ruta=catalogo">CATÁLOGO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?ruta=nosotros">NOSOTROS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?ruta=informate">INFÓRMATE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="?ruta=contacto">CONTACTO</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="notifications-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Tus Notificaciones</h2>
                        <button class="btn btn-sm btn-outline-secondary" onclick="marcarTodasLeidas()">
                            <i class="fas fa-check-circle me-1"></i> Marcar todas como leídas
                        </button>
                    </div>
                    
                    <?php if (empty($notificaciones)): ?>
                        <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <h4>No tienes notificaciones</h4>
                            <p class="text-muted">Aquí aparecerán las actualizaciones de tus pedidos y promociones</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notificaciones as $notif): ?>
                        <div class="notification-item <?= $notif['leido'] ? 'notification-read' : '' ?>">
                            <div class="d-flex">
                                <div class="notification-icon 
                                    <?= $notif['tipo'] == 'pedido' ? 'notification-pedido' : 'notification-promocion' ?>">
                                    <i class="fas <?= $notif['tipo'] == 'pedido' ? 'fa-shopping-bag' : 'fa-percentage' ?>"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <?php if ($notif['tipo'] == 'pedido'): ?>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-1">Actualización de tu pedido #<?= $notif['id'] ?></h5>
                                            <span class="badge-estado badge-<?= $notif['estado'] ?>">
                                                <?= strtoupper($notif['estado']) ?>
                                            </span>
                                        </div>
                                        
                                        <p class="mb-1">
                                            <strong>Sucursal:</strong> <?= htmlspecialchars($notif['sucursal']) ?><br>
                                            <strong>Total:</strong> Bs. <?= number_format($notif['total'], 2) ?>
                                        </p>
                                        
                                        <?php if ($notif['estado'] == 'enviado'): ?>
                                            <div class="alert alert-info p-2 mt-2 mb-1">
                                                <i class="fas fa-truck me-2"></i>
                                                Tu pedido está en camino a: <?= htmlspecialchars($notif['direccion_sucursal']) ?>
                                            </div>
                                        <?php elseif ($notif['estado'] == 'entregado'): ?>
                                            <div class="alert alert-success p-2 mt-2 mb-1">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Pedido entregado con éxito. ¡Gracias por tu compra!
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="notification-date">
                                                <i class="far fa-clock me-1"></i>
                                                <?= date('d/m/Y H:i', strtotime($notif['fecha'])) ?>
                                            </span>
                                            <a href="detalle_pedido.php?id=<?= $notif['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                Ver detalles
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <h5 class="mb-1">¡Nueva promoción disponible!</h5>
                                        <p class="mb-2"><?= htmlspecialchars($notif['mensaje']) ?></p>
                                        <span class="notification-date">
                                            <i class="far fa-clock me-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($notif['fecha'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Actualizar contador del carrito
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
            }).catch(error => console.error('Error:', error));
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

        // Función para marcar todas como leídas
        function marcarTodasLeidas() {
            fetch('<?= BASE_URL ?>cliente/?ruta=notificacion&accion=marcar_leidas', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.add('notification-read');
                    });
                    document.getElementById('notificationBadge').textContent = '0';
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
?>