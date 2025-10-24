<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #1a2a3a 0%, #2c3e50 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(26, 188, 156, 0.3);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(45deg, #1abc9c, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(26, 188, 156, 0.3);
            letter-spacing: 1px;
        }
        
        .logo-icon {
            font-size: 28px;
            color: #1abc9c;
            text-shadow: 0 0 8px rgba(26, 188, 156, 0.5);
        }
        
        .search-bar {
            flex-grow: 1;
            margin: 0 20px;
            max-width: 500px;
            position: relative;
        }
        
        .search-bar input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 30px;
            font-size: 16px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            color: white;
            transition: all 0.3s ease;
        }
        
        .search-bar input:focus {
            outline: none;
            background: rgba(255,255,255,0.15);
            box-shadow: 0 0 15px rgba(26, 188, 156, 0.4);
            border-color: rgba(26, 188, 156, 0.5);
        }
        
        .search-bar input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
            font-size: 18px;
        }
        
        .header-icons {
            display: flex;
            gap: 15px;
            position: relative;
        }
        
        .icon {
            cursor: pointer;
            font-size: 22px;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
        }
        
        .icon:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Contador de notificaciones */
        .notificacion-contador {
            position: absolute;
            top: 5px;
            right: 5px;
            background: linear-gradient(45deg, #e74c3c, #ff6b6b);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Dropdown de notificaciones */
        .notificaciones-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 380px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1001;
            display: none;
            margin-top: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .notificaciones-dropdown.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notificaciones-header {
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .notificaciones-header h3 {
            color: #2c3e50;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .notificaciones-lista {
            max-height: 400px;
            overflow-y: auto;
        }

        .notificacion-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: background 0.3s;
        }

        .notificacion-item:hover {
            background: #f8f9fa;
        }

        .notificacion-item.leida {
            opacity: 0.6;
        }

        .notificacion-titulo {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .notificacion-descripcion {
            color: #7f8c8d;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .notificacion-tiempo {
            color: #bdc3c7;
            font-size: 11px;
            text-align: right;
        }

        .notificacion-vacia {
            padding: 40px 20px;
            text-align: center;
            color: #7f8c8d;
        }

        .notificacion-vacia .icono {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .notificaciones-footer {
            padding: 15px 20px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            background: #f8f9fa;
        }

        .btn-ver-todas {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-ver-todas:hover {
            text-decoration: underline;
        }

        /* Overlay para cerrar al hacer click fuera */
        .overlay-notificaciones {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 999;
            display: none;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .nav-item {
            padding: 12px 25px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item:hover {
            background: rgba(26, 188, 156, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #1abc9c, #3498db);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::after {
            width: 70%;
        }
        
        .content {
            padding: 30px 0;
            min-height: 60vh;
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
        }

        /* Contador del carrito */
        .carrito-contador {
            position: absolute;
            top: 5px;
            right: 5px;
            background: linear-gradient(45deg, #e74c3c, #ff6b6b);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            animation: pulse 2s infinite;
        }

        /* Dropdown del carrito */
        .carrito-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 380px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1001;
            display: none;
            margin-top: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .carrito-dropdown.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .carrito-header {
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
            background: #f8f9fa;
        }

        .carrito-header h3 {
            color: #2c3e50;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .carrito-lista {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px 0;
        }

        .carrito-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #f8f9fa;
            gap: 15px;
        }

        .carrito-item-imagen {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            overflow: hidden;
            position: relative;
        }

        .carrito-item-imagen img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
            max-width: 100%;
            max-height: 100%;
        }

        .carrito-item-info {
            flex: 1;
        }

        .carrito-item-nombre {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .carrito-item-precio {
            color: #1abc9c;
            font-weight: bold;
            font-size: 14px;
        }

        .carrito-item-cantidad {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }

        .carrito-item-cantidad button {
            background: #ecf0f1;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.2s;
        }

        .carrito-item-cantidad button:hover {
            background: #1abc9c;
            color: white;
        }

        .carrito-item-eliminar {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .carrito-item-eliminar:hover {
            background: #e74c3c;
            color: white;
        }

        .carrito-vacio {
            padding: 40px 20px;
            text-align: center;
            color: #7f8c8d;
        }

        .carrito-vacio .icono {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .carrito-footer {
            padding: 20px;
            border-top: 1px solid #ecf0f1;
            background: #f8f9fa;
        }

        .carrito-total {
            text-align: center;
            margin-bottom: 15px;
            font-size: 16px;
            color: #2c3e50;
            font-weight: 600;
        }

        .carrito-acciones {
            display: flex;
            gap: 10px;
        }

        .btn-ver-carrito {
            flex: 1;
            background: #3498db;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-ver-carrito:hover {
            background: #2980b9;
            color: white;
            text-decoration: none;
        }

        .btn-vaciar-carrito {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-vaciar-carrito:hover {
            background: #c0392b;
        }

        /* Autocompletado de búsqueda */
        .sugerencias-autocompletado {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1001;
            margin-top: 5px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.1);
            border-top: none;
        }

        .sugerencia-item {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid #f8f9fa;
            transition: background 0.3s;
            color: #2c3e50;
        }

        .sugerencia-item:hover {
            background: #f8f9fa;
        }

        .sugerencia-item:last-child {
            border-bottom: none;
        }

        /* Efectos de neón para elementos destacados */
        .neon-effect {
            text-shadow: 0 0 5px rgba(26, 188, 156, 0.7),
                         0 0 10px rgba(26, 188, 156, 0.5),
                         0 0 15px rgba(26, 188, 156, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                text-align: center;
            }
            
            .search-bar {
                margin: 10px 0;
                order: 3;
                width: 100%;
            }
            
            .nav-menu {
                flex-wrap: wrap;
            }
            
            .nav-item {
                padding: 10px 15px;
                font-size: 14px;
            }

            .notificaciones-dropdown {
                width: 300px;
                right: -50px;
            }
            
            .carrito-dropdown {
                width: 300px;
                right: -50px;
            }
        }

        @media (max-width: 480px) {
            .notificaciones-dropdown {
                width: 280px;
                right: -80px;
            }
            
            .carrito-dropdown {
                width: 280px;
                right: -80px;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay para cerrar notificaciones -->
    <div class="overlay-notificaciones" id="overlayNotificaciones"></div>

    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo-container">
                    <i class="bi bi-tools logo-icon neon-effect"></i>
                    <div class="logo">FERRETERÍA</div>
                </div>
                <div class="search-bar">
                    <form action="index.php" method="GET" id="formBusqueda">
                        <input type="hidden" name="c" value="catalogo">
                        <input type="hidden" name="a" value="buscar">
                        <input type="text" name="q" placeholder="Buscar productos..." 
                            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                            id="inputBusqueda">
                        <button type="submit" style="display: none;">Buscar</button>
                    </form>
                    <i class="bi bi-search search-icon"></i>
                </div>
                <div class="header-icons">
                    <!-- Icono de Notificaciones -->
                    <div class="icon" id="iconoNotificaciones" title="Notificaciones">
                        <i class="bi bi-bell"></i>
                        <div class="notificacion-contador" id="contadorNotificaciones">3</div>
                    </div>
                    
                    <!-- Dropdown de Notificaciones -->
                    <div class="notificaciones-dropdown" id="dropdownNotificaciones">
                        <div class="notificaciones-header">
                            <h3>Notificaciones</h3>
                        </div>
                        <div class="notificaciones-lista" id="listaNotificaciones">
                            <!-- Las notificaciones se cargarán aquí dinámicamente -->
                        </div>
                        <div class="notificaciones-footer">
                            <a href="#" class="btn-ver-todas">Ver todas las notificaciones</a>
                        </div>
                    </div>

                    <!-- Icono del Carrito (sin dropdown) -->
                    <a href="index.php?c=carrito" class="icon" title="Carrito de Compras">
                        <i class="bi bi-cart3"></i>
                        <div class="carrito-contador" id="contadorCarrito">0</div>
                    </a>

                    <a href="index.php?c=usuario" class="icon" title="Mi perfil">
                        <i class="bi bi-person-circle"></i>
                    </a>
                </div>
            </div>
            <nav class="nav-menu">
                <a href="index.php?c=inicio" class="nav-item">INICIO</a>
                <a href="index.php?c=catalogo" class="nav-item">CATÁLOGO</a>
                <a href="index.php?c=nosotros" class="nav-item">NOSOTROS</a>
                <a href="index.php?c=informate" class="nav-item">INFÓRMATE</a>
                <a href="index.php?c=contactos" class="nav-item">CONTACTO</a>
            </nav>
        </div>
    </header>
    
    <main class="content container">

    <script src="js/notificaciones.js"></script>
    <script src="<?php echo APP_URL; ?>/js/carrito.js"></script>
    <script src="js/busqueda.js"></script>
    <script>
        // Pasar el ID del cliente al JavaScript
        window.idCliente = <?php echo $_SESSION['id_cliente'] ?? 0; ?>;
    </script>