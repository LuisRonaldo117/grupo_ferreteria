<?php
require_once 'modelos/ContactosModelo.php';

class ContactosControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new ContactosModelo();
    }
    
    public function index() {
        $datos = $this->modelo->obtenerInformacion();
        cargarVista('contactos/index', $datos);
    }
}
?>