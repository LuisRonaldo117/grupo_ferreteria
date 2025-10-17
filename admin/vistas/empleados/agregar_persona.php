<?php
// agregar_persona.php
include("../../modelos/conexion.php");
$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $ci = $_POST["ci"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $genero = $_POST["genero"];
    $id_departamento = $_POST["id_departamento"];

    try {
        $sql = "INSERT INTO persona (nombres, apellidos, ci, fecha_nacimiento, direccion, telefono, correo, genero, id_departamento)
                VALUES (:nombres, :apellidos, :ci, :fecha_nacimiento, :direccion, :telefono, :correo, :genero, :id_departamento)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nombres", $nombres);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":ci", $ci);
        $stmt->bindParam(":fecha_nacimiento", $fecha_nacimiento);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":genero", $genero);
        $stmt->bindParam(":id_departamento", $id_departamento);
        $stmt->execute();

        $id_persona = $conn->lastInsertId();
        echo json_encode(['status' => 'ok', 'message' => 'Persona registrada correctamente.', 'id_persona' => $id_persona]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener departamentos para select
$sql_dep = "SELECT id_departamento, nom_departamento FROM departamento ORDER BY nom_departamento";
$stmt_dep = $conn->prepare($sql_dep);
$stmt_dep->execute();
$departamentos = $stmt_dep->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="formAgregarPersona" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">🧍 Registrar Persona</h1>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="nombres" class="form-label fw-semibold">Nombres</label>
            <input type="text" name="nombres" id="nombres" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="apellidos" class="form-label fw-semibold">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="ci" class="form-label fw-semibold">CI</label>
            <input type="text" name="ci" id="ci" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
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
            <label for="correo" class="form-label fw-semibold">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="genero" class="form-label fw-semibold">Género</label>
            <select name="genero" id="genero" class="form-control" required>
                <option value="">Seleccione...</option>
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="id_departamento" class="form-label fw-semibold">Departamento</label>
            <select name="id_departamento" id="id_departamento" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($departamentos as $dep): ?>
                    <option value="<?= $dep['id_departamento'] ?>"><?= $dep['nom_departamento'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center" id="botonesFormulario" style="margin-bottom: 40px;">
    <button class="btn btn-success" style="margin-right: 40px;">Registrar Empleado</button>
    <button id="btnVolverAtras" class="btn btn-danger">Volver Atrás</button>
</div>
</form>
<script>
$(document).ready(function(){
    $('#formAgregarPersona').on('submit', function(e){
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'vistas/empleados/agregar_persona.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response){
                if(response.status === 'ok'){
                    alert('✅ ' + response.message);
                    // Cargar el formulario de empleado con id_persona por GET
                    CELM_CargarContenido('vistas/empleados/agregar_empleado.php?id_persona=' + response.id_persona, 'content-wrapper');
                } else {
                    alert('❌ ' + response.message);
                }
            },
            error: function(xhr, status, error){
                alert('❌ Error en el registro: ' + error + '\n' + xhr.responseText);
            }
        });
    });

    // Botón para volver atrás
    $('#btnVolverAtras').on('click', function() {
        
      $('#formularioNuevoEmpleado').slideUp().empty();
      $('#contenedorTabla').fadeIn();
      $('#btnNuevoEmpleado').fadeIn();
    
    });
});
</script>
