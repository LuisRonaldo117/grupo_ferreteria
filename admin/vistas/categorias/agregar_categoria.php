<?php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Esta parte solo se ejecuta cuando se envía el formulario por AJAX
    $nombre = trim($_POST['nombre_categoria']);

    try {
        $sql = "INSERT INTO categoria (nombre_categoria) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre]);
        echo "Categoría registrada correctamente.";
    } catch (Exception $e) {
        echo "Error al registrar: " . $e->getMessage();
    }
    exit; // 🚨 Muy importante para que no devuelva el HTML debajo
}
?>

<!-- Esta parte solo se mostrará cuando el archivo se cargue en la interfaz (por ejemplo con CELM_CargarContenido) -->
<form id="formAgregarCategoria" method="POST">
    <h1 class="text-center fw-bold text-dark mb-4">📦 Registrar Categoría</h1>
    <div class="form-group">
        <label for="nombre_categoria">Nombre de la categoría:</label>
        <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" required>
    </div>

    <div id="botonesFormulario" class="mt-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" onclick="cancelarFormulario()">Cancelar</button>
    </div>
</form>

<script>
    $('#formAgregarCategoria').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'vistas/categorias/agregar_categoria.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                CELM_CargarContenido('vistas/categorias/categoria.php', 'content-wrapper');
            },
            error: function () {
                alert('Error al guardar la categoría.');
            }
        });
    });

    function cancelarFormulario() {
        $('#formularioNuevaCategoria').slideUp();
        $('#contenedorTablaCategoria').show();
        $('#btnNuevaCategoria').show();
    }
</script>
