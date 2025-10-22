<?php
require_once 'modelos/InicioModelo.php';

class InicioControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new InicioModelo();
    }
    
    public function index() {
        $datos = $this->modelo->obtenerDatosInicio();
        cargarVista('inicio/index', $datos);
    }
}
?>