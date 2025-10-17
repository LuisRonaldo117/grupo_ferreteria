<?php
    $mensaje_error = $_SESSION['error'] ?? null;
    $mensaje_exito = $_SESSION['exito'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen de Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Subir Imagen de Producto</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($mensaje_error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($mensaje_error) ?></div>
                        <?php endif; ?>
                        
                        <?php if ($mensaje_exito): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($mensaje_exito) ?></div>
                        <?php endif; ?>
                        
                        <form action="<?= BASE_URL ?>cliente/?ruta=subirImagen" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="id_producto" class="form-label">Seleccionar Producto</label>
                                <select class="form-select" id="id_producto" name="id_producto" required>
                                    <option value="">-- Seleccione un producto --</option>
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?= $producto['id_producto'] ?>">
                                            <?= htmlspecialchars($producto['nombre']) ?> (ID: <?= $producto['id_producto'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="imagen_producto" class="form-label">Imagen del Producto</label>
                                <input type="file" class="form-control" id="imagen_producto" name="imagen_producto" accept="image/*" required>
                                <div class="form-text">Formatos permitidos: JPG, PNG, GIF, WEBP (Máximo 2MB)</div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Subir Imagen</button>
                                <a href="<?= BASE_URL ?>cliente/?ruta=catalogo" class="btn btn-secondary">Volver al Catálogo</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

