<?php

require_once MODELS_DIR . 'producto.modelo.php';

class ProductoControlador {
    private $modelo;
    private $db;

    public function __construct($db, $modelo = null) {
        $this->db = $db;
        $this->modelo = $modelo ?? new ProductoModelo($db);
    }

    public function subirImagenProducto() {
        // Verificar si es una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_producto'])) {
            $id_producto = $_POST['id_producto'] ?? null;
            
            if (!$id_producto) {
                $_SESSION['error'] = "ID de producto no especificado";
                header("Location: " . BASE_URL . "cliente/?ruta=subir_imagen_vista");
                exit;
            }

            // Obtener información del producto
            $producto = $this->modelo->obtenerProductoPorId($id_producto);
            
            if (!$producto) {
                $_SESSION['error'] = "Producto no encontrado";
                header("Location: " . BASE_URL . "cliente/?ruta=subir_imagen_vista");
                exit;
            }

            // Configuración de la subida
            $directorio_destino = UPLOADS_DIR . 'productos/';
            
            if (!file_exists($directorio_destino)) {
                mkdir($directorio_destino, 0777, true);
            }

            $archivo_temporal = $_FILES['imagen_producto']['tmp_name'];
            $nombre_archivo = uniqid('prod_') . '_' . basename($_FILES['imagen_producto']['name']);
            $ruta_destino = $directorio_destino . $nombre_archivo;
            
            // Validar el tipo de archivo
            $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
            $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (!in_array($extension, $extensiones_permitidas)) {
                $_SESSION['error'] = "Solo se permiten archivos JPG, JPEG, PNG, GIF o WEBP";
                header("Location: " . BASE_URL . "cliente/?ruta=subir_imagen_vista");
                exit;
            }

            // Validar tamaño del archivo
            if ($_FILES['imagen_producto']['size'] > 2097152) {
                $_SESSION['error'] = "El archivo es demasiado grande (máximo 2MB)";
                header("Location: " . BASE_URL . "cliente/?ruta=subir_imagen_vista");
                exit;
            }

            // Mover el archivo subido al directorio de destino
            if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
                // Guardar la ruta relativa en la base de datos
                $ruta_relativa = 'cliente/uploads' . $nombre_archivo;
                
                if ($this->modelo->actualizarImagenProducto($id_producto, $ruta_relativa)) {
                    $_SESSION['exito'] = "Imagen subida correctamente";
                } else {
                    $_SESSION['error'] = "Error al guardar la imagen en la base de datos";
                }
            } else {
                $_SESSION['error'] = "Error al subir el archivo";
            }
            
            header("Location: " . BASE_URL . "cliente/?ruta=subir_imagen_vista");
            exit;
        }
        
        // Si no es POST, mostrar vista de subida
        $this->mostrarFormularioSubida();
    }

    private function mostrarFormularioSubida() {
        try {
            $modeloCatalogo = new CatalogoModelo($this->db);
            $productos = $modeloCatalogo->obtenerListadoProductos();
            
            if (empty($productos)) {
                $_SESSION['error'] = "No hay productos disponibles para mostrar";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cargar los productos";
            error_log("Error en mostrarFormularioSubida: " . $e->getMessage());
            $productos = [];
        }
        
        define('BASE_URL', '/grupo_ferreteria/');
        define('VIEWS_DIR', __DIR__ . '/../vistas/');
        
        require_once VIEWS_DIR . 'subir_imagen.php';
    }
}

