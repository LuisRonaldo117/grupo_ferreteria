<?php
// Obtener datos del cliente desde el controlador
$cliente = $this->modelo->obtenerDatosCliente($_SESSION['id_cliente']);
$pedidos = $this->modelo->obtenerPedidosCliente($_SESSION['id_cliente']);

// Asignar variables para la vista - CORREGIDAS
$nombre = $cliente['nombres'] ?? $_SESSION['nombre_cliente'] ?? 'Usuario';
$apellido = $cliente['apellidos'] ?? $_SESSION['apellido_cliente'] ?? '';
$email = $cliente['correo'] ?? $_SESSION['email_cliente'] ?? 'correo@ejemplo.com';
$telefono = $cliente['telefono'] ?? $_SESSION['telefono_cliente'] ?? 'No especificado';
$direccion = $cliente['direccion'] ?? $_SESSION['direccion_cliente'] ?? 'No especificada';
$fecha_registro = isset($cliente['fecha_creacion']) ? date('Y-m-d', strtotime($cliente['fecha_creacion'])) : '2025-01-01';

// Debug para verificar datos
error_log("Variables de vista - Nombre: $nombre, Apellido: $apellido, Email: $email");

if (isset($_SESSION['mensaje'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['mensaje'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $_SESSION['error'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Ferretería</title>
    
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
        
        /* Estilos específicos del perfil */
        .profile-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 25px;
            border: 3px solid var(--accent-color);
        }
        
        .profile-info h2 {
            margin-bottom: 5px;
            color: var(--primary-color);
        }
        
        .profile-info p {
            margin-bottom: 0;
            color: #6c757d;
        }
        
        .nav-pills .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--accent-color);
            color: white;
        }
        
        .info-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .info-label {
            font-weight: 500;
            color: var(--primary-color);
        }
        
        .order-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.08);
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,.1);
        }
        
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-processing {
            background-color: #fff3cd;
            color: #856404;
        }
        
        /* Estilos existentes de la navbar */
        .main-nav .nav-link {
            color: white;
            font-weight: 500;
            padding: 8px 20px;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
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
    
    <!-- Contenido principal del perfil -->
    <div class="container my-5">
        <div class="row">
            <!-- Menú lateral -->
            <div class="col-lg-3 mb-4">
                <div class="profile-container">
                    <div class="profile-header">
                        <img src="<?= !empty($cliente['imagen']) ? BASE_URL . 'uploads/perfil/' . htmlspecialchars($cliente['imagen']) : 'https://ui-avatars.com/api/?name=' . urlencode($nombre . ' ' . $apellido) . '&background=007bff&color=fff' ?>" class="profile-avatar" alt="Avatar">
                        <div class="profile-info">
                            <h2><?= htmlspecialchars($nombre . ' ' . $apellido) ?></h2>
                            <p>Miembro desde <?= date('d/m/Y', strtotime($fecha_registro)) ?></p>
                        </div>
                    </div>
                    
                    <!-- Menu de navegacion de pestañas -->
                    <ul class="nav nav-pills flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active w-100 text-start" id="v-pills-usuario-tab" data-bs-toggle="pill" data-bs-target="#usuario" type="button" role="tab" aria-controls="usuario" aria-selected="true">
                                <i class="fas fa-user-circle me-2"></i>Información Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100 text-start" id="v-pills-pedidos-tab" data-bs-toggle="pill" data-bs-target="#pedidos" type="button" role="tab" aria-controls="pedidos" aria-selected="false">
                                <i class="fas fa-clipboard-list me-2"></i>Mis Pedidos
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Contenido del perfil -->
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- Pestaña de información personal -->
                    <div class="tab-pane fade show active" id="usuario" role="tabpanel" aria-labelledby="v-pills-usuario-tab">
                        <div class="profile-container">
                            <h3 class="mb-4"><i class="fas fa-user-circle me-2"></i>Información personal</h3>
                            
                            <form action="<?= BASE_URL ?>cliente/?ruta=usuario&accion=actualizar" method="post" enctype="multipart/form-data">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="info-card">
                                            <h5 class="mb-3">Datos básicos</h5>
                                            <div class="mb-3">
                                                <label class="form-label info-label">Nombre *</label>
                                                <input type="text" class="form-control" name="nombres" value="<?= htmlspecialchars($nombre) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label info-label">Apellido *</label>
                                                <input type="text" class="form-control" name="apellidos" value="<?= htmlspecialchars($apellido) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label info-label">Correo electrónico *</label>
                                                <input type="email" class="form-control" name="correo" value="<?= htmlspecialchars($email) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label info-label">Teléfono</label>
                                                <input type="tel" class="form-control" name="telefono" value="<?= htmlspecialchars($telefono) ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card">
                                            <h5 class="mb-3">Dirección</h5>
                                            <div class="mb-3">
                                                <label class="form-label info-label">Dirección principal</label>
                                                <textarea class="form-control" name="direccion" rows="3" placeholder="Ingresa tu dirección completa"><?= htmlspecialchars($direccion) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Pestaña de pedidos -->
                    <div class="tab-pane fade" id="pedidos" role="tabpanel" aria-labelledby="v-pills-pedidos-tab">
                        <div class="profile-container">
                            <h3 class="mb-4"><i class="fas fa-clipboard-list me-2"></i>Mis pedidos recientes</h3>
                            
                            <?php if (empty($pedidos)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 text-muted"></i>
                                    <h5>No hay pedidos registrados</h5>
                                    <p class="text-muted">Realiza tu primer pedido en nuestro catálogo</p>
                                    <a href="<?= BASE_URL ?>cliente/?ruta=catalogo" class="btn btn-primary">Ir al catálogo</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($pedidos as $pedido): ?>
                                <div class="order-card p-3 mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <h6 class="mb-1">Pedido #FERR-<?= str_pad($pedido['id_pedido'], 5, '0', STR_PAD_LEFT) ?></h6>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></small>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge badge-status <?= 
                                                $pedido['estado'] == 'entregado' ? 'status-completed' : 
                                                ($pedido['estado'] == 'cancelado' ? 'status-cancelled' : 'status-processing') 
                                            ?>">
                                                <?= ucfirst($pedido['estado']) ?>
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted"><?= $pedido['productos'] ?> producto(s)</small>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <h6 class="mb-0">Bs. <?= number_format($pedido['total'], 2) ?></h6>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <a href="<?= BASE_URL ?>cliente/?ruta=detalle-pedido&id=<?= $pedido['id_pedido'] ?>" class="btn btn-sm btn-outline-primary">
                                                Ver detalle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center mt-4">
                                    <a href="<?= BASE_URL ?>cliente/?ruta=historial-pedidos" class="btn btn-outline-primary">
                                        <i class="fas fa-history me-2"></i>Ver historial completo
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Script para manejar notificaciones y carrito
        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar contador del carrito
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const totalProductos = carrito.reduce((total, item) => total + item.cantidad, 0);
            const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart')?.nextElementSibling;
            if (cartBadge) {
                cartBadge.textContent = totalProductos;
            }
            
            // Validación de contraseñas
            const form = document.querySelector('form[action*="cambiar-contrasena"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const nueva = form.querySelector('input[name="nueva"]').value;
                    const confirmar = form.querySelector('input[name="confirmar"]').value;
                    
                    if (nueva !== confirmar) {
                        e.preventDefault();
                        alert('Las contraseñas no coinciden');
                    }
                });
            }
        });
    </script>
</body>
</html>