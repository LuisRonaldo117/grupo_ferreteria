<?php
include("../../modelos/conexion.php");

$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sucursal = $_POST["id_sucursal"];
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    try {
        $sql = "UPDATE sucursal SET nombre = :nombre, direccion = :direccion, telefono = :telefono, email = :email WHERE id_sucursal = :id_sucursal";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':email' => $email,
            ':id_sucursal' => $id_sucursal
        ]);

        echo json_encode(['status' => 'ok', 'message' => 'Sucursal actualizada correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

if (isset($_GET['id'])) {
    $id_sucursal = $_GET['id'];

    $sql = "SELECT * FROM sucursal WHERE id_sucursal = :id_sucursal";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_sucursal' => $id_sucursal]);
    $sucursal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sucursal) {
        echo "<p>Sucursal no encontrada.</p>";
        exit;
    }
} else {
    echo "<p>ID de sucursal no especificado.</p>";
    exit;
}
?>

<form id="formEditarSucursal" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">✏️ Editar Sucursal</h1>
    <input type="hidden" name="id_sucursal" value="<?= $sucursal['id_sucursal'] ?>">

    <div class="mb-3">
        <label for="nombre" class="form-label fw-semibold">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($sucursal['nombre']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="direccion" class="form-label fw-semibold">Dirección</label>
        <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($sucursal['direccion']) ?>">
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($sucursal['telefono']) ?>">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($sucursal['email']) ?>">
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="submit" class="btn btn-success px-4 me-3" style="margin-right: 40px;">Guardar Cambios</button>
        <button type="button" id="btnCancelarEdicion" class="btn btn-danger px-4">Cancelar</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#formEditarSucursal').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'vistas/sucursal/editar_sucursal.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'ok') {
                    alert('✅ ' + response.message);
                    CELM_CargarContenido('vistas/sucursal/sucursal.php', 'content-wrapper');
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
        $('#formularioNuevaSucursal').slideUp().empty();
        $('#contenedorTablaSucursal').fadeIn();
        $('#btnNuevaSucursal').fadeIn();
    });
});
</script>
