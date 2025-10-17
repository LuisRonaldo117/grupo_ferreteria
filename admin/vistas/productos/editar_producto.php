<?php
include("../../modelos/conexion.php");

$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST["id_producto"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $min_stock = $_POST["min_stock"];
    $id_categoria = $_POST["id_categoria"];
    $id_proveedor = $_POST["id_proveedor"];
    $precio_compra = $_POST["precio_compra"];
    $tiempo_entrega = $_POST["tiempo_entrega"];

    // Imagen
    $nombre_imagen = $_POST["imagen_actual"];
    if (!empty($_FILES["imagen"]["name"])) {
        $nombre_imagen = basename($_FILES["imagen"]["name"]);
        $ruta_destino = "../../../imagenes/" . $nombre_imagen;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_destino);
    }

    try {
        // Actualizar producto
        $sql = "UPDATE producto SET nombre=:nombre, descripcion=:descripcion, precio_unitario=:precio, stock=:stock, min_stock=:min_stock, imagen=:imagen, id_categoria=:id_categoria WHERE id_producto=:id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);
        $stmt->bindParam(":min_stock", $min_stock);
        $stmt->bindParam(":imagen", $nombre_imagen);
        $stmt->bindParam(":id_categoria", $id_categoria);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->execute();

        // Actualizar proveedor relacionado
       // Verificar si ya existe relación producto-proveedor
$sqlCheck = "SELECT COUNT(*) FROM producto_proveedor WHERE id_producto = :id_producto";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bindParam(":id_producto", $id_producto);
$stmtCheck->execute();
$existeRelacion = $stmtCheck->fetchColumn() > 0;

if ($existeRelacion) {
    $sql2 = "UPDATE producto_proveedor SET id_proveedor = :id_proveedor, precio_compra = :precio_compra, tiempo_entrega_dias = :tiempo_entrega WHERE id_producto = :id_producto";
} else {
    $sql2 = "INSERT INTO producto_proveedor (id_producto, id_proveedor, precio_compra, tiempo_entrega_dias) VALUES (:id_producto, :id_proveedor, :precio_compra, :tiempo_entrega)";
}
$stmt2 = $conn->prepare($sql2);

        $stmt2->bindParam(":id_proveedor", $id_proveedor);
        $stmt2->bindParam(":precio_compra", $precio_compra);
        $stmt2->bindParam(":tiempo_entrega", $tiempo_entrega);
        $stmt2->bindParam(":id_producto", $id_producto);
        $stmt2->execute();

        echo json_encode(['status' => 'ok', 'message' => 'Producto actualizado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// GET: obtener info para formulario
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    // Datos del producto
    $sql = "SELECT * FROM producto WHERE id_producto = :id_producto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id_producto", $id_producto);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "<p>Producto no encontrado</p>";
        exit;
    }

    // Categorías
    $sqlCat = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria";
    $stmtCat = $conn->prepare($sqlCat);
    $stmtCat->execute();
    $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

    // Proveedores
    $sqlProv = "SELECT id_proveedor, nombre FROM proveedores ORDER BY nombre";
    $stmtProv = $conn->prepare($sqlProv);
    $stmtProv->execute();
    $proveedores = $stmtProv->fetchAll(PDO::FETCH_ASSOC);

    // Datos del proveedor del producto
    $sqlPP = "SELECT * FROM producto_proveedor WHERE id_producto = :id_producto";
    $stmtPP = $conn->prepare($sqlPP);
    $stmtPP->bindParam(":id_producto", $id_producto);
    $stmtPP->execute();
    $productoProveedor = $stmtPP->fetch(PDO::FETCH_ASSOC);
if (!$productoProveedor) {
    $productoProveedor = [
        'id_proveedor' => '',
        'precio_compra' => '',
        'tiempo_entrega_dias' => ''
    ];
}

} else {
    echo "<p>ID de producto no especificado.</p>";
    exit;
}
?>

<form id="formEditarProducto" enctype="multipart/form-data" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">✏️ Editar Producto</h1>
    <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
    <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($producto['imagen']) ?>">

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nombre del producto</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Precio unitario</label>
            <input type="number" name="precio" class="form-control" step="0.01" value="<?= $producto['precio_unitario'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Stock actual</label>
            <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Stock mínimo</label>
            <input type="number" name="min_stock" class="form-control" value="<?= $producto['min_stock'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Categoría</label>
            <select name="id_categoria" class="form-control" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : '' ?>>
                        <?= $cat['nombre_categoria'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Imagen</label><br>
            <img src="/grupo_ferreteria/imagenes/<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen actual" style="width: 70px; height: auto; margin-bottom: 10px;">
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>

        <!-- Sección producto_proveedor -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">Proveedor</label>
            <select name="id_proveedor" class="form-control" required>
                <option value="">Seleccione un proveedor</option>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov['id_proveedor'] ?>" <?= ($productoProveedor['id_proveedor'] == $prov['id_proveedor']) ? 'selected' : '' ?>>
                        <?= $prov['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Precio de compra</label>
            <input type="number" name="precio_compra" class="form-control" step="0.01" value="<?= $productoProveedor['precio_compra'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tiempo de entrega (días)</label>
            <input type="number" name="tiempo_entrega" class="form-control" value="<?= $productoProveedor['tiempo_entrega_dias'] ?>" required>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <button type="submit" class="btn btn-warning px-4 me-4" style="margin-right: 40px;">Guardar Cambios</button>
        <button type="button" id="btnCancelarEdicion" class="btn btn-danger px-4">Cancelar</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#formEditarProducto').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'vistas/productos/editar_producto.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'ok') {
                    alert('✅ ' + response.message);
                    CELM_CargarContenido('vistas/productos/productos.php', 'content-wrapper');
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('❌ Error en la actualización: ' + error);
            }
        });
    });

    $('#btnCancelarEdicion').on('click', function() {
        $('#formularioNuevoProducto').slideUp().empty();
        $('#contenedorTabla').fadeIn();
        $('#btnNuevoProducto').fadeIn();
    });
});
</script>
