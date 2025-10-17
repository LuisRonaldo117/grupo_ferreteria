<?php

require_once MODELS_DIR . 'catalogo.modelo.php';

class CatalogoControlador {
    private $modelo;
    private $db;

    public function __construct($db, $modelo = null) {
        $this->db = $db;
        $this->modelo = $modelo ?? new CatalogoModelo($db);
    }

    public function mostrarCatalogo() {
        $categoria_id = $_GET['categoria'] ?? null;
        $busqueda = $_GET['buscar'] ?? null;
        $orden = $_GET['orden'] ?? null;
        $soloStock = isset($_GET['stock']);
        $precio_min = $_GET['precio_min'] ?? null;
        $precio_max = $_GET['precio_max'] ?? null;

        $productos = $this->modelo->obtenerProducto($categoria_id, $busqueda, $orden, $soloStock, $precio_min, $precio_max);

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['productos' => $productos]);
            exit;
        }

        require_once VIEWS_DIR . 'catalogo.php';
    }

    public function mostrarDetalleProducto() {
        $id_producto = $_GET['id'] ?? null;

        if (!$id_producto || !is_numeric($id_producto)) {
            // Redirigir o mostrar error si no hay ID válido
            header('Location: ?ruta=catalogo');
            exit;
        }

        $producto = $this->modelo->obtenerProductoPorId($id_producto);

        if (!$producto) {
            // Redirigir o mostrar error si el producto no existe
            header('Location: ?ruta=catalogo');
            exit;
        }
        require_once VIEWS_DIR . 'detalle_producto.php';
    }
}
?>