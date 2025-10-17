<?php
include('../conexion.php');

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $ci = mysqli_real_escape_string($conexion, $_POST['ci']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    $id_departamento = intval($_POST['id_departamento']);

    // Verificar si la cédula ya existe
    $check_sql = "SELECT COUNT(*) AS count FROM persona WHERE ci = '$ci'";
    $check_result = mysqli_query($conexion, $check_sql);
    $row = mysqli_fetch_assoc($check_result);

    if ($row['count'] > 0) {
        $error_msg = "La cédula <strong>$ci</strong> ya está registrada. Por favor verifica.";
    } else {
        // Insertar datos si no existe
        $sql = "INSERT INTO persona (nombres, apellidos, ci, fecha_nacimiento, direccion, telefono, correo, genero, id_departamento)
                VALUES ('$nombres', '$apellidos', '$ci', '$fecha_nacimiento', '$direccion', '$telefono', '$correo', '$genero', '$id_departamento')";

        if (mysqli_query($conexion, $sql)) {
            $id_persona = mysqli_insert_id($conexion);
            header("Location: crear_cliente.php?id_persona=$id_persona");
            exit();
        } else {
            $error_msg = "Error al guardar: " . mysqli_error($conexion);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro de Persona</title>
    <link rel="stylesheet" href="../estilos/persona_cliente.css" />
</head>

<body>
    <div class="wrapper">
        <form action="registrar_persona.php" method="post">

            <h1>Iniciar Registro</h1>

            <?php if ($error_msg): ?>
                <div style="background-color:#f44336; color:white; padding:12px; border-radius:8px; margin-bottom:20px; text-align:center;">
                    <?= $error_msg ?>
                </div>
            <?php endif; ?>

            <div class="input-box">
                <input type="text" placeholder="Nombres" name="nombre" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Apellidos" name="apellido" required>
                <i class='bx bxstext-user'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Cédula" name="ci" required>
                <i class='bx bxs-id-card'></i>
            </div>

            <div class="input-box">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" required>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Dirección" name="direccion">
                <i class='bx bxs-map'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Teléfono" name="telefono">
                <i class='bx bxs-phone-call'></i>
            </div>

            <div class="input-box">
                <input type="email" placeholder="Correo" name="correo">
                <i class='bx bxs-envelope'></i>
            </div>

            <div class="input-box">
                <label for="genero">Género:</label>
                <select name="genero" id="genero" required>
                    <option value="" disabled selected>Selecciona Género</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>

            <div class="input-box">
                <label for="id_departamento">Departamento:</label>
                <select name="id_departamento" id="id_departamento" required>
                    <option value="" disabled selected>Selecciona departamento</option>
                    <?php
                    $sql = "SELECT id_departamento, nom_departamento FROM departamento ORDER BY id_departamento";
                    $result = mysqli_query($conexion, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id_departamento'] . '">' . htmlspecialchars($row['nom_departamento']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="botones">
                <input class="btn" type="submit" value="Siguiente" style="background-color: green; color: white;">
                <input class="btn" type="button" value="Volver Atrás" onclick="window.location.href='../index.php';">
 </div>
        </form>
    </div>
</body>

</html>
