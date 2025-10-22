<?php
require_once 'modelos/InformateModelo.php';

class InformateControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new InformateModelo();
    }
    
    public function index() {
        $datos = $this->modelo->obtenerInformacion();
        cargarVista('informate/index', $datos);
    }
    
    public function articulo() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($id) {
            $articulo = $this->modelo->obtenerArticulo($id);
            
            if ($articulo) {
                cargarVista('informate/articulo', ['articulo' => $articulo]);
            } else {
                echo "Artículo no encontrado";
            }
        } else {
            echo "ID de artículo no especificado";
        }
    }
}
?>