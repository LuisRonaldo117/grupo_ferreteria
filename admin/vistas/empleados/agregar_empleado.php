<?php
// agregar_empleado.php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"]; // contraseña sin encriptar
    $salario = $_POST["salario"];
    $id_persona = $_POST["id_persona"];
    $id_sucursal = $_POST["id_sucursal"];
    $id_cargo = $_POST["id_cargo"];

    // Quitar esta línea
    // $hash_password = password_hash($contrasena, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO empleado (usuario, contrasena, salario, id_persona, id_sucursal, id_cargo)
                VALUES (:usuario, :contrasena, :salario, :id_persona, :id_sucursal, :id_cargo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        // En lugar de $hash_password, pasas la contraseña tal cual
        $stmt->bindParam(":contrasena", $contrasena);
        $stmt->bindParam(":salario", $salario);
        $stmt->bindParam(":id_persona", $id_persona);
        $stmt->bindParam(":id_sucursal", $id_sucursal);
        $stmt->bindParam(":id_cargo", $id_cargo);
        $stmt->execute();

        echo json_encode(['status' => 'ok', 'message' => 'Empleado registrado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener datos para selects
$sql_sucursal = "SELECT id_sucursal, nombre FROM sucursal ORDER BY nombre";
$stmt_sucursal = $conn->prepare($sql_sucursal);
$stmt_sucursal->execute();
$sucursales = $stmt_sucursal->fetchAll(PDO::FETCH_ASSOC);

$sql_cargo = "SELECT id_cargo, nombre_cargo FROM cargo_empleado ORDER BY nombre_cargo";
$stmt_cargo = $conn->prepare($sql_cargo);
$stmt_cargo->execute();
$cargos = $stmt_cargo->fetchAll(PDO::FETCH_ASSOC);

// Obtener id_persona del GET
$id_persona = isset($_GET['id_persona']) ? intval($_GET['id_persona']) : 0;
?>

<form id="formAgregarEmpleado" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">👤 Registrar Empleado</h1>
    <input type="hidden" name="id_persona" value="<?= $id_persona ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label for="usuario" class="form-label fw-semibold">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="contrasena" class="form-label fw-semibold">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="salario" class="form-label fw-semibold">Salario</label>
            <input type="number" name="salario" id="salario" step="0.01" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="id_sucursal" class="form-label fw-semibold">Sucursal</label>
            <select name="id_sucursal" id="id_sucursal" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($sucursales as $suc): ?>
                    <option value="<?= $suc['id_sucursal'] ?>"><?= htmlspecialchars($suc['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="id_cargo" class="form-label fw-semibold">Cargo</label>
            <select name="id_cargo" id="id_cargo" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($cargos as $car): ?>
                    <option value="<?= $car['id_cargo'] ?>"><?= htmlspecialchars($car['nombre_cargo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        <button type="submit" class="btn btn-success px-4">Registrar Empleado</button>
    </div>
</form>

<script>
$(document).ready(function(){
    $('#formAgregarEmpleado').on('submit', function(e){
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'vistas/empleados/agregar_empleado.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response){
                if(response.status === 'ok'){
                    alert('✅ ' + response.message);
                    // Limpiar formulario
                    // Cargar la vista de productos en el contenedor content-wrapper
            CELM_CargarContenido('vistas/empleados/empleados.php', 'content-wrapper');
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr, status, error){
                alert('❌ Error en el registro: ' + error);
            }
        });
    });
});
</script>
