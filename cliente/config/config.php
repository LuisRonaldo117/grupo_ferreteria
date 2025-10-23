<?php

// Configuracion de la bd
define('DB_HOST', 'localhost');
define('DB_NAME', 'grupo_ferreteria');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuracion de la aplicacion
define('APP_NAME', 'FERRETERIA');
define('APP_URL', 'http://localhost/grupo_ferreteria');

// Conexion a la bd
function conectarBD() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    $conexion->set_charset("utf8");
    return $conexion;
}

// Vistas principales
function cargarVista($vista, $datos = []) {
    extract($datos);
    require_once 'vistas/header.php';
    require_once 'vistas/' . $vista . '.php';
    require_once 'vistas/footer.php';
}
?>