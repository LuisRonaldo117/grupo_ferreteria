<?php
include("../../modelos/conexion.php");

$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos para actualizar proveedor
    $id_proveedor = $_POST["id_proveedor"];
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    try {
        $sql = "UPDATE proveedores SET nombre = :nombre, direccion = :direccion, telefono = :telefono, email = :email WHERE id_proveedor = :id_proveedor";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':email' => $email,
            ':id_proveedor' => $id_proveedor
        ]);

        echo json_encode(['status' => 'ok', 'message' => 'Proveedor actualizado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Si es GET, mostrar formulario con datos existentes
if (isset($_GET['id'])) {
    $id_proveedor = $_GET['id'];

    $sql = "SELECT * FROM proveedores WHERE id_proveedor = :id_proveedor";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_proveedor' => $id_proveedor]);
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proveedor) {
        echo "<p>Proveedor no encontrado</p>";
        exit;
    }
} else {
    echo "<p>ID de proveedor no especificado.</p>";
    exit;
}
?>

<form id="formEditarProveedor" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">✏️ Editar Proveedor</h1>
    <input type="hidden" name="id_proveedor" value="<?= $proveedor['id_proveedor'] ?>">

    <div class="mb-3">
        <label for="nombre" class="form-label fw-semibold">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="direccion" class="form-label fw-semibold">Dirección</label>
        <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($proveedor['direccion']) ?>">
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($proveedor['telefono']) ?>">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($proveedor['email']) ?>">
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="submit" class="btn btn-success px-4 me-3" style="margin-right: 40px;">Guardar Cambios</button>
        <button type="button" id="btnCancelarEdicion" class="btn btn-danger px-4">Cancelar</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#formEditarProveedor').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'vistas/proveedores/editar_proveedor.php', // Ajusta esta ruta si la nombras diferente
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'ok') {
                    alert('✅ ' + response.message);
                    // Recarga o navega a la lista de proveedores
                    CELM_CargarContenido('vistas/proveedores/proveedor.php', 'content-wrapper');
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
        $('#formularioNuevoProveedor').slideUp().empty();
        $('#contenedorTabla').fadeIn();
        $('#btnNuevoProveedor').fadeIn();
    });
});
</script>
