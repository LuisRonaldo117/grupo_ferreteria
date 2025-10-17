<?php
// agregar_producto.php
include("../../modelos/conexion.php");

$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = $_POST["nombre"];
  $descripcion = $_POST["descripcion"];
  $precio = $_POST["precio"];
  $stock = $_POST["stock"];
  $min_stock = $_POST["min_stock"];
  $id_categoria = $_POST["id_categoria"];
  $id_proveedor = $_POST["id_proveedor"];
  $precio_compra = $_POST["precio_compra"];
  $tiempo_entrega = $_POST["tiempo_entrega"];

  // Manejo de imagen
  $nombre_imagen = null;
  if (!empty($_FILES["imagen"]["name"])) {
    $nombre_imagen = basename($_FILES["imagen"]["name"]);
    $ruta_destino = "../../../imagenes/" . $nombre_imagen;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_destino);
  }

  try {
    // Insertar en producto
    $sql = "INSERT INTO producto (nombre, descripcion, precio_unitario, stock, min_stock, imagen, id_categoria) 
            VALUES (:nombre, :descripcion, :precio, :stock, :min_stock, :imagen, :id_categoria)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":stock", $stock);
    $stmt->bindParam(":min_stock", $min_stock);
    $stmt->bindParam(":imagen", $nombre_imagen);
    $stmt->bindParam(":id_categoria", $id_categoria);
    $stmt->execute();

    // Obtener el id del producto insertado
    $id_producto = $conn->lastInsertId();

    // Insertar en producto_proveedor
    $sql2 = "INSERT INTO producto_proveedor (id_producto, id_proveedor, precio_compra, tiempo_entrega_dias) 
             VALUES (:id_producto, :id_proveedor, :precio_compra, :tiempo_entrega)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindParam(":id_producto", $id_producto);
    $stmt2->bindParam(":id_proveedor", $id_proveedor);
    $stmt2->bindParam(":precio_compra", $precio_compra);
    $stmt2->bindParam(":tiempo_entrega", $tiempo_entrega);
    $stmt2->execute();

    echo json_encode(['status' => 'ok', 'message' => 'Producto y proveedor registrados correctamente.']);
  } catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
  }
  exit;
}

// Obtener categorías
$sql = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener proveedores
$sqlProv = "SELECT id_proveedor, nombre FROM proveedores ORDER BY nombre";
$stmtProv = $conn->prepare($sqlProv);
$stmtProv->execute();
$proveedores = $stmtProv->fetchAll(PDO::FETCH_ASSOC);
?>



<form id="formNuevoProducto" enctype="multipart/form-data" class="p-4 bg-light rounded shadow-sm">
  <h1 class="text-center fw-bold text-dark mb-4">🛒 Registrar Producto</h1>
  <div class="row g-3">
    <div class="col-md-6">
      <label for="nombre" class="form-label fw-semibold">Nombre del producto</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label for="precio" class="form-label fw-semibold">Precio unitario</label>
      <input type="number" name="precio" class="form-control" step="0.01" required>
    </div>
    <div class="col-md-6">
      <label for="stock" class="form-label fw-semibold">Stock actual</label>
      <input type="number" name="stock" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label for="min_stock" class="form-label fw-semibold">Stock mínimo</label>
      <input type="number" name="min_stock" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label for="id_categoria" class="form-label fw-semibold">Categoría</label>
      <select name="id_categoria" class="form-control" required>
        <option value="">Seleccione una categoría</option>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre_categoria'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label for="imagen" class="form-label fw-semibold">Imagen</label>
      <input type="file" name="imagen" class="form-control" accept="image/*">
    </div>
    <div class="col-md-12">
      <label for="descripcion" class="form-label fw-semibold">Descripción</label>
      <textarea name="descripcion" class="form-control" rows="4" required></textarea>
    </div>

    <!-- Campos producto_proveedor -->
    <div class="col-md-6">
      <label for="id_proveedor" class="form-label fw-semibold">Proveedor</label>
      <select name="id_proveedor" class="form-control" required>
        <option value="">Seleccione un proveedor</option>
        <?php foreach ($proveedores as $prov): ?>
          <option value="<?= $prov['id_proveedor'] ?>"><?= $prov['nombre'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6">
      <label for="precio_compra" class="form-label fw-semibold">Precio de compra</label>
      <input type="number" name="precio_compra" class="form-control" step="0.01" required>
    </div>
    <div class="col-md-6">
      <label for="tiempo_entrega" class="form-label fw-semibold">Tiempo de entrega (días)</label>
      <input type="number" name="tiempo_entrega" class="form-control" value="7" required>
    </div>
  </div>

  <div class="mt-4 d-flex justify-content-center">
    <button type="submit" class="btn btn-success px-4 me-4" style="margin-right: 40px;">Registrar Producto</button>
    <button type="button" id="btnVolverAtrasForm" class="btn btn-danger px-4">Volver Atrás</button>
  </div>
</form>


<script>
  $(document).ready(function() {
    $('#formNuevoProducto').on('submit', function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: 'vistas/productos/agregar_producto.php',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json', // Esperamos JSON
        success: function(response) {
          if (response.status === 'ok') {
            alert('✅ ' + response.message);
            // Cargar la vista de productos en el contenedor content-wrapper
            CELM_CargarContenido('vistas/productos/productos.php', 'content-wrapper');
          } else {
            alert('❌ ' + response.message);
          }
        },
        error: function(xhr, status, error) {
          alert("❌ Error en el registro: " + error);
        }
      });
    });

    $('#btnVolverAtrasForm').on('click', function() {
      $('#formularioNuevoProducto').slideUp().empty();
      $('#contenedorTabla').fadeIn();
      $('#btnNuevoProducto').fadeIn();
    });
  });
</script>