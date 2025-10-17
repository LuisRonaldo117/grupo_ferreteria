<?php
session_start();

// Evita cache del navegador
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica que la sesión del admin exista
if (!isset($_SESSION['admin_usuario'])) {
    header('Location: logout.php'); // Redirige al login del admin
    exit;
}

require_once "controladores/plantilla.controlador.php";

// Opcional: variables de sesión
$usuario = $_SESSION['admin_usuario'];

$plantilla = new PlantillaControlador();
$plantilla->CargarPlantilla();
