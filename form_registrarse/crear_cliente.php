<?php
include('../conexion.php');

if (!isset($_GET['id_persona'])) {
    die("No se ha especificado persona");
}

$id_persona = intval($_GET['id_persona']);
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $contrasena = mysqli_real_escape_string($conexion, $_POST['contrasena']);

    // Verificar si usuario ya existe
    $check_sql = "SELECT COUNT(*) AS count FROM cliente WHERE usuario = '$usuario'";
    $check_result = mysqli_query($conexion, $check_sql);
    $row = mysqli_fetch_assoc($check_result);

    if ($row['count'] > 0) {
        $error_msg = "El usuario <strong>$usuario</strong> ya está en uso. Por favor elige otro nombre de usuario.";
    } else {
        // Insertar nuevo usuario
        $sql = "INSERT INTO cliente (usuario, contrasena, id_persona) 
                VALUES ('$usuario', '$contrasena', $id_persona)";

        if (mysqli_query($conexion, $sql)) {
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro Exitoso</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                height: 100vh;
                background-image: url("../img/inicio.jpg");
                background-size: cover;
                background-position: center;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            /* Fondo oscuro semi-transparente */
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .message-box {
                background: rgba(255,255,255,0.95);
                padding: 30px 50px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                text-align: center;
                font-size: 20px;
                color: #28a745;
                font-weight: 600;
                animation: fadeIn 0.5s ease;
            }

            .message-box span {
                display: block;
                margin-top: 10px;
                font-size: 16px;
                color: #333;
                font-weight: 400;
            }

            @keyframes fadeIn {
                from {opacity: 0; transform: scale(0.9);}
                to {opacity: 1; transform: scale(1);}
            }
        </style>
        <script>
            setTimeout(() => {
                window.location.href = "../index.php";
            }, 3000);
        </script>
    </head>
    <body>
        <div class="overlay">
            <div class="message-box">
                ¡Guardado exitosamente!
                <span>Serás redirigido al inicio...</span>
            </div>
        </div>
    </body>
    </html>
    ';
    exit();
}
 else {
            $error_msg = "Error al guardar: " . mysqli_error($conexion);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Cliente</title>
    <link rel="stylesheet" href="../estilos/crear_cliente.css">
</head>

<body>
    <div class="wrapper">
        <form action="crear_cliente.php?id_persona=<?= htmlspecialchars($_GET['id_persona']) ?>" method="post">
            <h1>Crear Accesos</h1>

            <?php if ($error_msg): ?>
                <div style="background-color:#f44336; color:white; padding:12px; border-radius:8px; margin-bottom:20px; text-align:center;">
                    <?= $error_msg ?>
                </div>
            <?php endif; ?>

            <div class="input-box">
                <label for="usuario">Crear Usuario:</label><br>
                <input type="text" placeholder="Usuario" name="usuario" required>
            </div>

            <div class="input-box">
                <label for="contrasena">Crear Contraseña:</label><br>
                <input type="password" placeholder="Contraseña" name="contrasena" required>
            </div>

            <div class="botones">
                <input class="btn" type="submit" value="Crear Cuenta" style="background-color: green; color: white;">
            </div>
        </form>
    </div>
</body>

</html>
