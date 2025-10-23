<?php
session_start();
date_default_timezone_set('America/La_Paz'); // ⏰ Zona horaria Bolivia

// Verifica si hay sesión activa
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: ../logout.php');
    exit;
}

// Datos del empleado en sesión
$usuario = $_SESSION['empleado_usuario'];
$nombre = $_SESSION['nombre_empleado'];
$apellido = $_SESSION['apellido_empleado'];
$id_empleado = $_SESSION['id_empleado'];

include '../conexion.php';

// Variables base
$mensaje = "";
$fecha_actual = date('Y-m-d');
$hora_actual = date('H:i:s');
$estado = ['entrada' => false, 'salida' => false];

// Consulta si ya existe registro hoy
$consulta = "SELECT * FROM asistencia WHERE id_empleado = ? AND fecha = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("is", $id_empleado, $fecha_actual);
$stmt->execute();
$result = $stmt->get_result();
$registro = $result->fetch_assoc();

// Verifica estados
if ($registro) {
    if (!empty($registro['hora_entrada'])) $estado['entrada'] = true;
    if (!empty($registro['hora_salida'])) $estado['salida'] = true;
}

// Procesar envío del formulario
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
    <script src="https://kit.fontawesome.com/a2e0e9f4c4.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="asistencia-wrapper">
        <div class="asistencia-card">
            <header>
                <h1><i class="fas fa-clock"></i> Control de Asistencia</h1>
            </header>

            <section class="info">
                <p><strong>Empleado:</strong> <?= htmlspecialchars("$nombre $apellido") ?></p>
                <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
                <p><strong>Fecha:</strong> <?= date('d/m/Y') ?></p>
                <p><strong>Hora actual:</strong> <span id="hora-actual"><?= date('H:i:s') ?></span></p>
            </section>

            <form method="POST" class="acciones">
                <button type="submit" name="tipo" value="entrada"
                    class="<?= $estado['entrada'] ? 'bloqueado' : 'entrada' ?>"
                    <?= $estado['entrada'] ? 'disabled' : '' ?>>
                    Registrar Entrada
                </button>

                <button type="submit" name="tipo" value="salida"
                    class="<?= ($estado['salida'] || !$estado['entrada']) ? 'bloqueado' : 'salida' ?>"
                    <?= ($estado['salida'] || !$estado['entrada']) ? 'disabled' : '' ?>>
                    Registrar Salida
                </button>
            </form>

            <?php if ($mensaje): ?>
                <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <footer>
                <a href="../index.php" class="btn-volver">Volver al Inicio</a>
            </footer>
        </div>
    </div>

    <script>
    // 🕓 Reloj en tiempo real
    function actualizarHora() {
        const elementoHora = document.getElementById("hora-actual");
        const ahora = new Date();
        const hora = ahora.toLocaleTimeString('es-BO', { hour12: false });
        elementoHora.textContent = hora;
    }
    setInterval(actualizarHora, 1000);
    </script>
</body>
</html>
