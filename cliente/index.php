<?php

require_once 'config/config.php';

// Iniciar sesión (temporal para pruebas)
session_start();
if (!isset($_SESSION['id_cliente'])) {
    $_SESSION['id_cliente'] = 1; // ID temporal para pruebas
}

// Obtener el controlador y la acción desde la URL
$controlador = isset($_GET['c']) ? $_GET['c'] : 'Inicio';
$accion = isset($_GET['a']) ? $_GET['a'] : 'index';

// Si hay un parámetro 'articulo', redirigir a la acción articulo del controlador Informate
if (isset($_GET['articulo'])) {
    $controlador = 'Informate';
    $accion = 'articulo';
    $_GET['id'] = $_GET['articulo'];
}
// Rutas
if (isset($_GET['c']) && $_GET['c'] === 'carrito') {
    $controlador = 'Carrito';
    $accion = isset($_GET['a']) ? $_GET['a'] : 'index';
}

if (isset($_GET['c']) && $_GET['c'] === 'usuario') {
    $controlador = 'Usuario';
    $accion = isset($_GET['a']) ? $_GET['a'] : 'index';
}

if (isset($_GET['c']) && $_GET['c'] === 'catalogo' && isset($_GET['a']) && $_GET['a'] === 'buscar') {
    $controlador = 'Catalogo';
    $accion = 'buscar';
}

if (isset($_GET['c']) && $_GET['c'] === 'carrito') {
    $controlador = 'Carrito';
    $accion = isset($_GET['a']) ? $_GET['a'] : 'index';
}

// Construir el nombre del controlador
$nombreControlador = ucfirst($controlador) . 'Controlador';
$archivoControlador = 'controladores/' . $nombreControlador . '.php';

// Verificar si el controlador existe
if(file_exists($archivoControlador)) {
    require_once $archivoControlador;
    $controladorObj = new $nombreControlador();
    
    // Verificar si la accion existe
    if(method_exists($controladorObj, $accion)) {
        $controladorObj->$accion();
    } else {
        echo "La acción no existe";
    }
} else {
    echo "El controlador no existe";
}
?>