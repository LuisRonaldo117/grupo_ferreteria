<?php
// agregar_proveedor.php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    try {
        $sql = "INSERT INTO proveedores (nombre, direccion, telefono, email)
                VALUES (:nombre, :direccion, :telefono, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        echo json_encode(['status' => 'ok', 'message' => 'Proveedor registrado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}
?>

<form id="formAgregarProveedor" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">🏭 Registrar Proveedor</h1>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="direccion" class="form-label fw-semibold">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <button type="submit" class="btn btn-success px-4" style="margin-right: 40px;">Registrar Proveedor</button>
         <button type="button" id="btnVolverAtrasForm" class="btn btn-danger px-4">
      Volver Atrás
    </button>
    </div>
</form>

<script>
$(document).ready(function(){
    $('#formAgregarProveedor').on('submit', function(e){
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'vistas/proveedores/agregar_proveedor.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response){
                if(response.status === 'ok'){
                    alert('✅ ' + response.message);
                    CELM_CargarContenido('vistas/proveedores/proveedor.php', 'content-wrapper');
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr, status, error){
                alert('❌ Error en el registro: ' + error);
            }
        });
    });
    $('#btnVolverAtrasForm').on('click', function() {
      $('#formularioNuevoProveedor').slideUp().empty();
      $('#contenedorTabla').fadeIn();
      $('#btnNuevoProveedor').fadeIn();
    });
});
</script>
