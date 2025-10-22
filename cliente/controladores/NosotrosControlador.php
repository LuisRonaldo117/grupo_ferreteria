<?php
require_once 'modelos/NosotrosModelo.php';

class NosotrosControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new NosotrosModelo();
    }
    
    public function index() {
        $datos = $this->modelo->obtenerInformacion();
        cargarVista('nosotros/index', $datos);
    }
}
?>