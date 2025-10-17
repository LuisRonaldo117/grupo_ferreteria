<?php
session_start();
// Verifica si hay sesión activa
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: ../logout.php'); // Ajusta la ruta a tu login
    exit;
}
$usuario = $_SESSION['empleado_usuario'];
$nombre = $_SESSION['nombre_empleado'];
$apellido = $_SESSION['apellido_empleado'];
$id_empleado = $_SESSION['id_empleado'];
include '../conexion.php';

$mensaje = "";
$fecha_actual = date('Y-m-d');
$hora_actual = date('H:i:s');
$estado = ['entrada' => false, 'salida' => false];

$consulta = "SELECT * FROM asistencia WHERE id_empleado = ? AND fecha = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("is", $id_empleado, $fecha_actual);
$stmt->execute();
$result = $stmt->get_result();
$registro = $result->fetch_assoc();

if ($registro) {
    if ($registro['hora_entrada']) $estado['entrada'] = true;
    if ($registro['hora_salida']) $estado['salida'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];

    if ($tipo === 'entrada') {
        if ($estado['entrada']) {
            $mensaje = "⚠️ Ya has registrado tu entrada hoy.";
        } else {
            if ($registro) {
                $update = "UPDATE asistencia SET hora_entrada = ? WHERE id_asistencia = ?";
                $stmt = $conexion->prepare($update);
                $stmt->bind_param("si", $hora_actual, $registro['id_asistencia']);
            } else {
                $insert = "INSERT INTO asistencia (id_empleado, fecha, hora_entrada) VALUES (?, ?, ?)";
                $stmt = $conexion->prepare($insert);
                $stmt->bind_param("iss", $id_empleado, $fecha_actual, $hora_actual);
            }
            $stmt->execute();
            $mensaje = "✅ Entrada registrada correctamente. ¡Buen día!";
            $estado['entrada'] = true;
        }
    }

    if ($tipo === 'salida') {
        if (!$estado['entrada']) {
            $mensaje = "⚠️ Debes registrar primero tu entrada.";
        } elseif ($estado['salida']) {
            $mensaje = "⚠️ Ya has registrado tu salida hoy.";
        } else {
            $update = "UPDATE asistencia SET hora_salida = ? WHERE id_asistencia = ?";
            $stmt = $conexion->prepare($update);
            $stmt->bind_param("si", $hora_actual, $registro['id_asistencia']);
            $stmt->execute();
            $mensaje = "✅ Salida registrada correctamente. ¡Hasta pronto!";
            $estado['salida'] = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Asistencia</title>
    <link rel="stylesheet" href="../css/asistencia.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Registro de Asistencia</h1>
        </header>

        <section class="info">
            <p><strong>Empleado:</strong> <?php echo "$nombre $apellido"; ?></p>
            <p><strong>Usuario:</strong> <?php echo $usuario; ?></p>
        </section>

        <section class="acciones">
            <form method="POST">
                <button type="submit" name="tipo" value="entrada" <?php echo $estado['entrada'] ? 'disabled class="bloqueado"' : ''; ?>>Registrar Entrada</button>
                <button type="submit" name="tipo" value="salida" <?php echo $estado['salida'] || !$estado['entrada'] ? 'disabled class="bloqueado"' : ''; ?>>Registrar Salida</button>
            </form>

            <?php if ($mensaje): ?>
                <div class="mensaje">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
        </section>

        <footer>
            <a href="../index.php" class="btn-volver">Volver al Inicio</a>
        </footer>
    </div>
</body>
</html>
