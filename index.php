<?php
// Evita cache
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="estilos/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php if (!empty($mensajeLogout)): ?>
    <div style="margin: 20px auto; max-width: 400px;" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensajeLogout) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php endif; ?>

    <div class="wrapper">
        <form action="validar.php" method="post" autocomplete="off">
            <h1>Iniciar sesión</h1>
            <div class="input-box">
                <input type="text" placeholder="Usuario" name="usuario" required autocomplete="off">
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Contraseña" name="contrasena" id="password" required autocomplete="off">
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="remember-forgot">
                <label>
                    <input type="checkbox" onclick="var p = document.getElementById('password'); p.type = this.checked ? 'text' : 'password'">
                    Mostrar contraseña
                </label>
                <a href="#">Olvido su contraseña?</a>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
            <div class="register-link">
                <p>No tienes cuenta? <a href="form_registrarse/registrar_persona.php">Regístrate</a></p>
            </div>
        </form>
    </div>
    
</body>

</html>
