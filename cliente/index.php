<?php

require_once 'config/config.php';

// Iniciar sesión
session_start();

// Controlador y acción por defecto
$controlador = isset($_GET['c']) ? $_GET['c'] : 'Inicio';
$accion = isset($_GET['a']) ? $_GET['a'] : 'index';

// Si el usuario no está logueado y no está en auth, redirigir al login
if (!isset($_SESSION['id_cliente']) && $controlador !== 'Auth') {
    header('Location: index.php?c=auth&a=login');
    exit();
}

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