<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conectar a la base de datos
require_once __DIR__ . '/../config/paths.php';
require_once BASE_PATH . '/conexion.php';
require_once MODELS_DIR . 'producto.modelo.php';

$conexion = new Conexion();
$db = $conexion->conectar();
$productoModelo = new ProductoModelo($db);
$productos = $productoModelo->obtenerProducto($id_producto);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos | Ferretería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h2>Subir Imagen de Producto</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="../controladores/producto.controlador.php?ruta=subirImagenProducto" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id_producto" class="form-label">Seleccionar Producto</label>
                <select class="form-select" id="id_producto" name="id_producto" required>
                    <option value="">-- Selecciona un producto --</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id_producto'] ?>">
                            <?= htmlspecialchars($producto['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Subir Imagen</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>