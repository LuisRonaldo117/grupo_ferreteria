<?php
require_once 'modelos/CatalogoModelo.php';

class ProductoControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new CatalogoModelo();
    }
    
    public function detalle() {
        $idProducto = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$idProducto) {
            header('Location: index.php?c=catalogo');
            exit();
        }
        
        $producto = $this->obtenerProductoPorId($idProducto);
        
        if (!$producto) {
            header('Location: index.php?c=catalogo');
            exit();
        }
        
        // Obtener productos relacionados
        $productosRelacionados = $this->obtenerProductosRelacionados($producto['id_categoria'], $idProducto);
        
        $datos = [
            'producto' => $producto,
            'productosRelacionados' => $productosRelacionados,
            'titulo' => $producto['nombre'] . ' | Ferretería'
        ];
        
        cargarVista('producto/detalle', $datos);
    }
    
    private function obtenerProductoPorId($idProducto) {
        $conexion = conectarBD();
        
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE p.id_producto = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $producto = null;
        if ($result && $result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            // Determinar la ruta de la imagen
            $producto['imagen_ruta'] = $this->obtenerRutaImagen($producto['imagen'], $producto['nombre_categoria']);
        }
        
        $stmt->close();
        $conexion->close();
        
        return $producto;
    }
    
    private function obtenerProductosRelacionados($idCategoria, $idProductoExcluir, $limite = 4) {
        $conexion = conectarBD();
        
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                WHERE p.id_categoria = ? AND p.id_producto != ? AND p.stock > 0 
                ORDER BY RAND() 
                LIMIT ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $idCategoria, $idProductoExcluir, $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['imagen_ruta'] = $this->obtenerRutaImagen($row['imagen'], $row['nombre_categoria']);
                $productos[] = $row;
            }
        }
        
        $stmt->close();
        $conexion->close();
        
        return $productos;
    }
    
    private function obtenerRutaImagen($nombreImagen, $categoria) {
        if (!empty($nombreImagen)) {
            return 'assets/images/' . $nombreImagen;
        } else {
            $iconos = [
                'HERRAMIENTAS MANUALES' => '🔨',
                'HERRAMIENTAS ELÉCTRICAS' => '⚡',
                'MATERIALES DE CONSTRUCCIÓN' => '🏗️',
                'TORNILLERÍA Y SUJECIÓN' => '🔩',
                'ELECTRICIDAD' => '💡',
                'FONTANERÍA' => '🚰',
                'PINTURAS Y ACCESORIOS' => '🎨',
                'SEGURIDAD INDUSTRIAL' => '🛡️'
            ];
            return $iconos[$categoria] ?? '📦';
        }
    }
}
?>