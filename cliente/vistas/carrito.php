<?php
// Verificar sesión al inicio
if (!isset($_SESSION['id_cliente'])) {
    header('Location: ' . BASE_URL . 'cliente/?ruta=login');
    exit;
}

$nombre = $_SESSION['nombre_cliente'] ?? 'Cliente';
$apellido = $_SESSION['apellido_cliente'] ?? '';

require_once __DIR__ . '/../config/paths.php';
require_once CONTROLLERS_DIR . 'carrito.controlador.php';

// Conectar a la base de datos
require_once BASE_PATH . '/conexion.php';
$conexion = new Conexion();
$db = $conexion->conectar();

$carritoControlador = new CarritoControlador($db);

// Manejar acciones (actualizar, eliminar, vaciar) solo si es una solicitud AJAX
if (isset($_GET['accion']) && !empty($_GET['accion'])) {
    $carritoControlador->manejarAccion();
    exit();
}

// Obtener datos del carrito
$datosCarrito = $carritoControlador->mostrarCarrito();

// Extraer variables para la vista
$carrito = $datosCarrito['carrito'] ?? [];
$subtotal = $datosCarrito['subtotal'] ?? 0;
$envio = $datosCarrito['envio'] ?? 0;
$total = $datosCarrito['total'] ?? 0;
$total_productos = $datosCarrito['total_productos'] ?? 0;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras | Ferretería</title>

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

        /* Estilos específicos del carrito */
        .cart-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quantity-selector {
            width: 120px;
        }

        .summary-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            padding: 25px;
            position: sticky;
            top: 140px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-row.total {
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: none;
        }

        .btn-checkout {
            background-color: var(--accent-color);
            color: white;
            font-weight: 500;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            margin-top: 20px;
        }

        .btn-checkout:hover {
            background-color: #0069d9;
        }

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
</head>
<body>
    <!-- Barra superior con logo y búsqueda -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid align-items-center">
            <a class="navbar-brand me-4" href="index.php">FERRETERÍA</a>

            <!-- Barra de búsqueda -->
            <form class="d-flex flex-grow-1 mx-2" style="max-width: 600px;">
                <div class="input-group flex-nowrap">
                    <input class="form-control border-end-0" type="search" placeholder="Buscar productos..." aria-label="Search">
                    <button class="btn btn-outline-primary border-start-0 px-3" type="submit">
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
                <!-- En el menú principal -->
                <li class="nav-item">
                    <a class="nav-link px-3" href="<?= BASE_URL ?>cliente/?ruta=mis_compras">
                        <i class="fas fa-file-invoice me-1"></i> MIS COMPRAS
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal del carrito -->
    <div class="container my-5">
        <div class="row">
            <!-- Lista de productos -->
            <div class="col-lg-8">
                <div class="cart-container">
                    <h2 class="mb-4">Tu Carrito de Compras</h2>

                    <?php if (empty($carrito)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x mb-3 text-muted"></i>
                            <h4>Tu carrito está vacío</h4>
                            <p class="text-muted">Agrega productos desde nuestro catálogo</p>
                            <a href="<?= BASE_URL ?>cliente/?ruta=catalogo" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Ir al Catálogo
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($carrito as $item): ?>
                        <div class="cart-item" data-id="<?= $item['id_carrito'] ?>">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="<?= BASE_URL ?>imagenes/<?= basename($item['imagen'] ?? $item['icono'] ?? 'default.jpg') ?>" 
                                        class="cart-item-img" alt="<?= $item['nombre'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <h5><?= $item['nombre'] ?></h5>
                                    <p class="text-success mb-0">En stock: <?= $item['stock'] ?? 'N/A' ?></p> <!-- Agregado fallback N/A -->
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group quantity-selector">
                                        <button class="btn btn-outline-secondary" type="button">-</button>
                                        <input type="text" class="form-control text-center" value="<?= $item['cantidad'] ?>">
                                        <button class="btn btn-outline-secondary" type="button">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5 class="mb-0">Bs. <?= number_format($item['precio'] ?? 0, 2) ?></h5> <!-- Agregado fallback 0 -->
                                </div>
                                <div class="col-md-1 text-end">
                                    <button class="btn btn-link text-danger" onclick="eliminarProducto(<?= $item['id_carrito'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>


                <?php if (!empty($carrito)): ?>
                <div class="d-flex justify-content-between mt-3">
                    <a href="<?= BASE_URL ?>cliente/?ruta=catalogo" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Seguir comprando
                    </a>
                    <button class="btn btn-outline-danger" onclick="vaciarCarrito()">
                        <i class="fas fa-trash me-2"></i>Vaciar carrito
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Resumen del pedido -->
            <?php if (!empty($carrito)): ?>
            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="mb-4">Resumen del Pedido</h4>

                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>Bs. <?= number_format($subtotal ?? 0, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Envío:</span>
                        <span>Bs. <?= number_format($envio ?? 0, 2) ?></span> 
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>Bs. <?= number_format($total ?? 0, 2) ?></span>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>cliente/?ruta=pago&subtotal=<?= $subtotal ?>&envio=<?= $envio ?>&total=<?= $total ?>" class="btn btn-checkout">
                            <i class="fas fa-credit-card me-2"></i>Proceder al pago
                        </a>
                    </div>

                    <div class="mt-3">
                        <p class="small text-muted">
                            <i class="fas fa-lock me-2"></i>Tu información de pago está protegida
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['pago_exitoso']) && $_GET['pago_exitoso'] == '1'): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">¡Pago exitoso!</h5>
                    <p class="mb-0">Tu compra ha sido procesada correctamente.</p>
                    <?php if (isset($_SESSION['ultima_factura'])): ?>
                        <p class="mb-0 mt-2">
                            <a href="<?= BASE_URL ?>cliente/?ruta=generar_factura&id=<?= $_SESSION['ultima_factura'] ?>" 
                            class="alert-link">
                                <i class="fas fa-file-pdf me-1"></i>Descargar factura
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para manejar el aumento/disminución de cantidad
        document.addEventListener('DOMContentLoaded', function() {
        // Manejar cambios de cantidad
        document.querySelectorAll('.quantity-selector button').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                let cantidad = parseInt(input.value);
                
                if (this.textContent === '+') {
                    cantidad++;
                } else {
                    if (cantidad > 1) cantidad--;
                }
                
                input.value = cantidad;
                actualizarCantidad(this.closest('.cart-item').dataset.id, cantidad);
            });
        });
            
            // Calcular subtotal
            const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            const envio = 15.00;
            const total = subtotal + envio;
            
            // Mostrar productos
            const productosContainer = document.querySelector('.cart-container');
            productosContainer.innerHTML = `
                <h2 class="mb-4">Tu Carrito de Compras</h2>
                ${carrito.map(item => `
                    <div class="cart-item" data-id="${item.id_producto}">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="${item.imagen}" class="cart-item-img" alt="${item.nombre}">
                            </div>
                            <div class="col-md-4">
                                <h5>${item.nombre}</h5>
                                <p class="text-success mb-0">Bs. ${item.precio.toFixed(2)}</p>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group quantity-selector">
                                    <button class="btn btn-outline-secondary" type="button" onclick="actualizarCantidad(${item.id_producto}, ${item.cantidad - 1})">-</button>
                                    <input type="text" class="form-control text-center" value="${item.cantidad}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="actualizarCantidad(${item.id_producto}, ${item.cantidad + 1})">+</button>
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <h5 class="mb-0">Bs. ${(item.precio * item.cantidad).toFixed(2)}</h5>
                            </div>
                            <div class="col-md-1 text-end">
                                <button class="btn btn-link text-danger" onclick="eliminarProducto(${item.id_producto})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            `;
            
            // Mostrar resumen
            document.querySelector('.summary-row:nth-child(1) span:last-child').textContent = `Bs. ${subtotal.toFixed(2)}`;
            document.querySelector('.summary-row:nth-child(2) span:last-child').textContent = `Bs. ${envio.toFixed(2)}`;
            document.querySelector('.summary-row.total span:last-child').textContent = `Bs. ${total.toFixed(2)}`;
            
            // Actualizar contador en el ícono del carrito
            const totalProductos = carrito.reduce((total, item) => total + item.cantidad, 0);
            const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart').nextElementSibling;
            if (cartBadge) {
                cartBadge.textContent = totalProductos;
            }
        });
        
        function actualizarCantidad(idProducto, cantidad) {
            fetch(`<?= BASE_URL ?>cliente/?ruta=carrito&accion=actualizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_carrito=${idProducto}&cantidad=${cantidad}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error al actualizar la cantidad');
                }
            });
        }

        function eliminarProducto(idCarrito) {
            if(confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                fetch('<?= BASE_URL ?>cliente/?ruta=carrito&accion=eliminar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_carrito=${idCarrito}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error al eliminar producto');
                    }
                });
            }
        }
        
        function vaciarCarrito() {
            if(confirm('¿Estás seguro de vaciar todo el carrito?')) {
                fetch('<?= BASE_URL ?>cliente/?ruta=carrito&accion=vaciar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Error al vaciar el carrito');
                    }
                });
            }
        }

        async function procesarPago(event) {
            event.preventDefault();
            
            const form = event.target;
            const btnPagar = form.querySelector('button[type="submit"]');
            
            // Mostrar estado de carga
            btnPagar.disabled = true;
            btnPagar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Procesando...';

            try {
                const formData = new FormData(form);
                const response = await fetch('<?= BASE_URL ?>cliente/?ruta=procesar_pago', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Error al procesar el pago');
                }
                
                // Redirigir si hay una URL de redirección
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Redirección por defecto si no hay URL específica
                    window.location.href = '<?= BASE_URL ?>cliente/?ruta=carrito&pago_exitoso=1';
                }
                
            } catch (error) {
                alert(error.message);
                console.error('Error:', error);
            } finally {
                btnPagar.disabled = false;
                btnPagar.innerHTML = '<i class="fas fa-credit-card me-2"></i> Confirmar Pago';
            }
        }

        document.getElementById('formPago').addEventListener('submit', procesarPago);

        // Asignar el event listener al formulario
        document.getElementById('formPago').addEventListener('submit', procesarPago);

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

        document.addEventListener('DOMContentLoaded', function() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const totalProductos = carrito.reduce((total, item) => total + item.cantidad, 0);
            const cartBadge = document.querySelector('.nav-icons .fa-shopping-cart')?.nextElementSibling;
            if (cartBadge) {
                cartBadge.textContent = totalProductos;
            }
        });
    </script>
</body>
</html>