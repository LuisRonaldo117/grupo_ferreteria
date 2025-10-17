<?php

session_start();

// Evita cache (aunque para AJAX no siempre es necesario)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica sesión de admin o empleado según corresponda
if (!isset($_SESSION['admin_usuario']) && !isset($_SESSION['empleado_usuario'])) {
    // Devuelve un error JSON y termina
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
require_once "../controladores/dashboard.controlador.php";
require_once "../modelos/dashboard.modelo.php";
class AjaxDashboard{
    public function getDatosDashboard(){
        $datos = DashboardControlador::ctrGetDatosDashboard();
        echo json_encode($datos);
    }
}

$datos = new AjaxDashboard();
$datos -> getDatosDashboard();