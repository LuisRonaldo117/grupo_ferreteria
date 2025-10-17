<?php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ACTUALIZAR CATEGORÍA
    $id = $_POST['id_categoria'];
    $nombre = trim($_POST['nombre_categoria']);

    try {
        $sql = "UPDATE categoria SET nombre_categoria = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $id]);
        echo "Categoría actualizada correctamente.";
    } catch (Exception $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
    exit; // 🚨 Importante: No mostrar HTML después
}

// OBTENER DATOS PARA MOSTRAR EL FORMULARIO
$id_categoria = $_GET['id'] ?? null;
$nombre_actual = "";

if ($id_categoria) {
    $stmt = $conn->prepare("SELECT nombre_categoria FROM categoria WHERE id_categoria = ?");
    $stmt->execute([$id_categoria]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombre_actual = $categoria ? $categoria['nombre_categoria'] : '';
}
?>

<!-- FORMULARIO PARA EDITAR -->
<form id="formEditarCategoria" method="POST">
    <input type="hidden" name="id_categoria" value="<?= htmlspecialchars($id_categoria) ?>">
    <h1 class="text-center fw-bold text-dark mb-4">✏️ Editar Categoría</h1>
    <div class="form-group">
        <label for="nombre_categoria">Nombre de la categoría:</label>
        <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" required
               value="<?= htmlspecialchars($nombre_actual) ?>">
    </div>

    <div class="mt-3" id="botonesFormulario">
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" onclick="cancelarEdicion()">Cancelar</button>
    </div>
</form>

<script>
    $('#formEditarCategoria').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'vistas/categorias/editar_categoria.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                CELM_CargarContenido('vistas/categorias/categoria.php', 'content-wrapper');
            },
            error: function () {
                alert('Error al actualizar la categoría.');
            }
        });
    });

    function cancelarEdicion() {
        $('#formularioNuevaCategoria').slideUp();
        $('#contenedorTablaCategoria').show();
        $('#btnNuevaCategoria').show();
    }
</script>
