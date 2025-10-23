<?php
require_once 'modelos/CatalogoModelo.php';

class CatalogoControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new CatalogoModelo();
    }
    
    public function index() {
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';
        $datos = $this->modelo->obtenerProductos($categoria);
        cargarVista('catalogo/index', $datos);
    }
    
    public function buscar() {
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (!empty($termino)) {
            $datos = $this->modelo->buscarProductos($termino);
        } else {
            // Si no hay termino de busqueda, redirigimos al catalogo completo
            header('Location: index.php?c=catalogo');
            exit();
        }
        
        cargarVista('catalogo/index', $datos);
    }
}
?>