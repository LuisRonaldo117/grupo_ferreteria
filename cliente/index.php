<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    // Cargar configuración
    require_once __DIR__ . '/config/paths.php';

    // Conexión a base de datos
    try {
        require_once ROOT_DIR . '/conexion.php';
        $conexion = new Conexion();
        $db = $conexion->conectar();
    } catch (Exception $e) {
        die("Error de conexión: " . $e->getMessage());
    }

    // Definimos rutas válidas
    $rutasValidas = [
        'catalogo' => [
            'controlador' => 'catalogo.controlador.php',
            'modelo' => 'catalogo.modelo.php',
            'metodo' => 'mostrarCatalogo',
            'nombreClaseControlador' => 'CatalogoControlador',
            'nombreClaseModelo' => 'CatalogoModelo'
        ],
        'detalle_producto' => [
            'controlador' => 'catalogo.controlador.php',
            'modelo' => 'catalogo.modelo.php',
            'metodo' => 'mostrarDetalleProducto',
            'nombreClaseControlador' => 'CatalogoControlador',
            'nombreClaseModelo' => 'CatalogoModelo'
        ],
        'inicio' => ['vista' => 'inicio.php'],
        'nosotros' => ['vista' => 'nosotros.php'],
        'informate' => ['vista' => 'informate.php'],
        'carrito' => [
            'controlador' => 'carrito.controlador.php',
            'modelo' => 'carrito.modelo.php',
            'metodo' => 'manejarAccion',
            'nombreClaseControlador' => 'CarritoControlador',
            'nombreClaseModelo' => 'CarritoModelo'
        ],
        'usuario' => [
            'controlador' => 'usuario.controlador.php',
            'modelo' => 'usuario.modelo.php',
            'metodo' => 'mostrarUsuario',
            'nombreClaseControlador' => 'UsuarioControlador',
            'nombreClaseModelo' => 'UsuarioModelo',
            'acciones' => [
                'actualizar' => 'actualizarUsuario',
                'cambiar-contrasena' => 'cambiarContrasena'
            ]
        ],
        'logout'=> ['vista' => 'logout.php'],
        'pago' => [
            'controlador' => 'carrito.controlador.php',
            'modelo' => 'carrito.modelo.php',
            'metodo' => 'mostrarFormularioPago',
            'nombreClaseControlador' => 'CarritoControlador',
            'nombreClaseModelo' => 'CarritoModelo'
        ],
        'procesar_pago' => [
            'controlador' => 'carrito.controlador.php',
            'modelo' => 'carrito.modelo.php',
            'metodo' => 'procesarPago',
            'nombreClaseControlador' => 'CarritoControlador',
            'nombreClaseModelo' => 'CarritoModelo'
        ],
        'confirmacion_pago' => [
            'controlador' => 'factura.controlador.php',
            'modelo' => 'factura.modelo.php',
            'metodo' => 'mostrarConfirmacion',
            'nombreClaseControlador' => 'CarritoControlador',
            'nombreClaseModelo' => 'FacturaModelo'
        ],
        'verificar_pago' => [
            'controlador' => 'pago.controlador.php',
            'modelo' => 'factura.modelo.php',
            'metodo' => 'verificarPago',
            'nombreClaseControlador' => 'FacturaControlador',
            'nombreClaseModelo' => 'FacturaModelo'
        ],
        'generar_factura' => [
            'controlador' => 'carrito.controlador.php',
            'modelo' => 'factura.modelo.php',
            'metodo' => 'generarFacturaPDF',
            'nombreClaseControlador' => 'CarritoControlador',
            'nombreClaseModelo' => 'FacturaModelo'
        ],
        'mis_compras' => [
            'controlador' => 'carrito.controlador.php',
            'modelo' => 'carrito.modelo.php',
            'metodo' => 'mostrarMisCompras',
            'nombreClaseControlador' => 'CarritoControlador',
            'nombreClaseModelo' => 'CarritoModelo'
        ],
        'subirImagen' => [
            'controlador' => 'producto.controlador.php',
            'modelo' => 'producto.modelo.php',
            'metodo' => 'subirImagenProducto',
            'nombreClaseControlador' => 'ProductoControlador',
            'nombreClaseModelo' => 'ProductoModelo'
        ],
        'subir_imagen_vista' => [
            'vista' => 'subir_imagen.php'
        ],
        'notificacion' => [
            'controlador' => 'notificacion.controlador.php',
            'modelo' => 'notificacion.modelo.php',
            'metodo' => 'mostrarNotificacion',
            'nombreClaseControlador' => 'NotificacionControlador',
            'nombreClaseModelo' => 'NotificacionModelo'
        ],
        'contacto' => [
            'vista' => 'contacto.php',
            'controlador' => 'reclamo.controlador.php',
            'modelo' => 'reclamo.modelo.php',
            'metodo' => 'guardarReclamo',
            'nombreClaseControlador' => 'ReclamoControlador',
            'nombreClaseModelo' => 'ReclamoModelo'
        ],
        'articulo' => [
            'vista' => 'articulo.php'
        ],
        'detalle-pedido' => [
            'controlador' => 'usuario.controlador.php',
            'modelo' => 'usuario.modelo.php',
            'metodo' => 'mostrarDetallePedido',
            'nombreClaseControlador' => 'UsuarioControlador',
            'nombreClaseModelo' => 'UsuarioModelo'
        ],
    ];

    // Obtener ruta solicitada
    $ruta = $_GET['ruta'] ?? 'inicio';

    // Verificar si la ruta existe
    if (!array_key_exists($ruta, $rutasValidas)) {
        header("HTTP/1.0 404 Not Found");
        require_once VIEWS_DIR . 'error.php';
        exit;
    }

    // Manejar la ruta
    $configRuta = $rutasValidas[$ruta];

    // Si hay vista, la incluimos
    if (isset($configRuta['vista'])) {
        // Ejecutamos el controlador solo si viene un formulario POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($configRuta['controlador'])) {
            require_once MODELS_DIR . $configRuta['modelo'];
            require_once CONTROLLERS_DIR . $configRuta['controlador'];

            $modelo = new $configRuta['nombreClaseModelo']($db);
            $controlador = new $configRuta['nombreClaseControlador']($db, $modelo);
            $resultado = $controlador->{$configRuta['metodo']}();

            // Puedes manejar el mensaje o redireccionar
            if (isset($resultado['message'])) {
                echo "<script>alert('{$resultado['message']}');</script>";
            }
        }

        // Finalmente, mostrar la vista
        require_once VIEWS_DIR . $configRuta['vista'];
    } else {
        // Si no hay vista, ejecutamos el controlador como siempre
        require_once MODELS_DIR . $configRuta['modelo'];
        require_once CONTROLLERS_DIR . $configRuta['controlador'];

        $modelo = new $configRuta['nombreClaseModelo']($db);
        $controlador = new $configRuta['nombreClaseControlador']($db, $modelo);
        $controlador->{$configRuta['metodo']}();
    }
?>

