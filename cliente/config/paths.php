<?php
define('ROOT_DIR', dirname(__DIR__, 2));
define('BASE_PATH', dirname(__DIR__, 2));

define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/grupo_ferreteria/');

// Rutas de la aplicación
define('CONTROLLERS_DIR', BASE_PATH . '/cliente/controladores/');
define('MODELS_DIR', BASE_PATH . '/cliente/modelos/');
define('VIEWS_DIR', BASE_PATH . '/cliente/vistas/');
define('CONFIG_DIR', BASE_PATH . '/cliente/config/');
define('UPLOADS_DIR', BASE_PATH . '/cliente/uploads/');


// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'grupo_ferreteria');
define('DB_USER', 'root');
define('DB_PASS', '');

// Función para cargar archivos de manera segura
function require_safe($path) {
    if (file_exists($path)) {
        return require_once $path;
    }
    throw new Exception("Archivo no encontrado: $path");
}
?>