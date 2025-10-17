<?php
include("../../modelos/conexion.php");

$conn = Conexion::conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos para actualizar empleado y persona
    $id_empleado = $_POST["id_empleado"];
    $id_persona = $_POST["id_persona"];
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $ci = $_POST["ci"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $genero = $_POST["genero"];
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $fecha_ingreso = $_POST["fecha_ingreso"];
    $salario = $_POST["salario"];
    $estado = $_POST["estado"];

    try {
        // Actualizar datos en persona
        $sqlPersona = "UPDATE persona SET nombres=:nombres, apellidos=:apellidos, ci=:ci, fecha_nacimiento=:fecha_nacimiento,
                       direccion=:direccion, telefono=:telefono, correo=:correo, genero=:genero WHERE id_persona=:id_persona";
        $stmtPersona = $conn->prepare($sqlPersona);
        $stmtPersona->execute([
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':ci' => $ci,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':genero' => $genero,
            ':id_persona' => $id_persona
        ]);

        // Actualizar datos en empleado
        $sqlEmpleado = "UPDATE empleado SET usuario=:usuario, contrasena=:contrasena, fecha_ingreso=:fecha_ingreso,
                        salario=:salario, estado=:estado WHERE id_empleado=:id_empleado";
        $stmtEmpleado = $conn->prepare($sqlEmpleado);
        $stmtEmpleado->execute([
            ':usuario' => $usuario,
            ':contrasena' => $contrasena,
            ':fecha_ingreso' => $fecha_ingreso,
            ':salario' => $salario,
            ':estado' => $estado,
            ':id_empleado' => $id_empleado
        ]);

        echo json_encode(['status' => 'ok', 'message' => 'Empleado actualizado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Si es GET, mostrar formulario con datos existentes
if (isset($_GET['id'])) {
    $id_empleado = $_GET['id'];

    $sql = "SELECT 
                e.id_empleado, e.usuario, e.contrasena, e.fecha_ingreso, e.estado, e.salario,
                p.id_persona, p.nombres, p.apellidos, p.ci, p.fecha_nacimiento, p.direccion,
                p.telefono, p.correo, p.genero
            FROM empleado e
            JOIN persona p ON e.id_persona = p.id_persona
            WHERE e.id_empleado = :id_empleado";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_empleado' => $id_empleado]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empleado) {
        echo "<p>Empleado no encontrado</p>";
        exit;
    }
} else {
    echo "<p>ID de empleado no especificado.</p>";
    exit;
}
?>
<form id="formEditarEmpleado" class="p-4 bg-light rounded shadow-sm">
    <h1 class="text-center fw-bold text-dark mb-4">✏️ Editar Empleado</h1>
    <input type="hidden" name="id_empleado" value="<?= $empleado['id_empleado'] ?>">
    <input type="hidden" name="id_persona" value="<?= $empleado['id_persona'] ?>">

    <h4 class="mb-3">Datos Personales</h4>

    <div class="row g-3 mb-4">
        <div class="form-group col-md-6">
            <label for="nombres" class="form-label fw-semibold">Nombres</label>
            <input type="text" name="nombres" id="nombres" class="form-control" value="<?= htmlspecialchars($empleado['nombres']) ?>" required>
        </div>
        <div class="form-group col-md-6">
            <label for="apellidos" class="form-label fw-semibold">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" class="form-control" value="<?= htmlspecialchars($empleado['apellidos']) ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label for="ci" class="form-label fw-semibold">CI</label>
            <input type="text" name="ci" id="ci" class="form-control" value="<?= htmlspecialchars($empleado['ci']) ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="<?= $empleado['fecha_nacimiento'] ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="genero" class="form-label fw-semibold">Género</label>
            <select name="genero" id="genero" class="form-control" required>
                <option value="Masculino" <?= $empleado['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="Femenino" <?= $empleado['genero'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="direccion" class="form-label fw-semibold">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($empleado['direccion']) ?>">
        </div>
        <div class="form-group col-md-6">
            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($empleado['telefono']) ?>">
        </div>
        <div class="form-group col-12">
            <label for="correo" class="form-label fw-semibold">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($empleado['correo']) ?>">
        </div>
    </div>

    <h4 class="mb-3">Datos Laborales</h4>

    <div class="row g-3">
        <div class="form-group col-md-6">
            <label for="usuario" class="form-label fw-semibold">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" value="<?= htmlspecialchars($empleado['usuario']) ?>" required>
        </div>
       <div class="form-group col-md-6 position-relative">
    <label for="contrasena" class="form-label fw-semibold">Contraseña</label>
    <input type="password" name="contrasena" id="contrasena" class="form-control" value="<?= htmlspecialchars($empleado['contrasena']) ?>" required>
    
    <div class="form-check mt-1">
        <input class="form-check-input" type="checkbox" id="mostrarContrasena" onclick="togglePassword()">
        <label class="form-check-label" for="mostrarContrasena">
            Mostrar contraseña
        </label>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('contrasena');
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
        <div class="form-group col-md-6">
            <label for="fecha_ingreso" class="form-label fw-semibold">Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="<?= $empleado['fecha_ingreso'] ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="salario" class="form-label fw-semibold">Salario</label>
            <input type="number" step="0.01" name="salario" id="salario" class="form-control" value="<?= $empleado['salario'] ?>" required>
        </div>
        <div class="form-group col-md-3">
            <label for="estado" class="form-label fw-semibold">Estado</label>
            <select name="estado" id="estado" class="form-control" required>
                <option value="1" <?= $empleado['estado'] ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= !$empleado['estado'] ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
    </div>

        <div class="mt-4 d-flex justify-content-center">
        <button type="submit" class="btn btn-warning px-4" style="margin-right: 40px;">Guardar Cambios</button>
        <button type="button" id="btnCancelarEdicion" class="btn btn-danger px-4">Cancelar</button>
    </div>
</form>


<script>
$(document).ready(function() {
    $('#formEditarEmpleado').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'vistas/empleados/editar_empleado.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'ok') {
                    alert('✅ ' + response.message);
                    CELM_CargarContenido('vistas/empleados/empleados.php', 'content-wrapper');
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
        $('#formularioNuevoEmpleado').slideUp().empty();
        $('#contenedorTabla').fadeIn();
        $('#btnNuevoEmpleado').fadeIn();
    });
});
</script>
