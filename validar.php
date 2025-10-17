<?php
session_start();
include('conexion.php');

// Sanitizar entradas
$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
$contraseña = mysqli_real_escape_string($conexion, $_POST['contrasena']);

// CONSULTA CLIENTE
$consulta_cliente = "
    SELECT cliente.*, persona.nombres, persona.apellidos
    FROM cliente
    JOIN persona ON cliente.id_persona = persona.id_persona
    WHERE cliente.usuario = '$usuario' AND cliente.contrasena = '$contraseña'
";
$resultado_cliente = mysqli_query($conexion, $consulta_cliente);

// CONSULTA EMPLEADO (sin filtrar estado aquí)
$consulta_emple = "
    SELECT empleado.*, empleado.id_sucursal, persona.nombres, persona.apellidos
    FROM empleado 
    JOIN persona ON empleado.id_persona = persona.id_persona
    WHERE empleado.usuario = '$usuario' AND empleado.contrasena = '$contraseña'
";
$resultado_emple = mysqli_query($conexion, $consulta_emple);

// CONSULTA ADMIN
$consulta_admin = "
    SELECT * FROM administrador 
    WHERE admin_usuario = '$usuario' AND admin_contrasena = '$contraseña'
";
$resultado_admin = mysqli_query($conexion, $consulta_admin);

// CLIENTE
if (mysqli_num_rows($resultado_cliente) > 0) {
    $row = mysqli_fetch_assoc($resultado_cliente);

    $_SESSION['rol'] = 'cliente';
    $_SESSION['cliente_usuario'] = $row['usuario'];
    $_SESSION['id_cliente'] = $row['id_cliente'];
    $_SESSION['nombre_cliente'] = $row['nombres'];
    $_SESSION['apellido_cliente'] = $row['apellidos'];
    $_SESSION['sesskey'] = bin2hex(random_bytes(16)); // Clave segura

    header("Location: cliente/index.php");
    exit();
}

// ADMIN
elseif (mysqli_num_rows($resultado_admin) > 0) {
    $row = mysqli_fetch_assoc($resultado_admin);

    $_SESSION['rol'] = 'admin';
    $_SESSION['admin_usuario'] = $row['admin_usuario'];
    $_SESSION['sesskey'] = bin2hex(random_bytes(16)); // Clave segura

    header("Location: admin/index.php");
    exit();
}

// EMPLEADO
elseif (mysqli_num_rows($resultado_emple) > 0) {
    $row = mysqli_fetch_assoc($resultado_emple);

    if ($row['estado'] == 1) {

        $_SESSION['rol'] = 'empleado';
        $_SESSION['empleado_usuario'] = $row['usuario'];
        $_SESSION['id_empleado'] = $row['id_empleado'];
        $_SESSION['nombre_empleado'] = $row['nombres'];
        $_SESSION['apellido_empleado'] = $row['apellidos'];
        $_SESSION['id_sucursal'] = $row['id_sucursal']; // ✅ Nueva línea
        $_SESSION['sesskey'] = bin2hex(random_bytes(16)); // Clave segura

        header("Location: empleado/index.php");
        exit();
    } else {
        echo "<script>alert('Empleado inactivo.  Contacta con el administrador.'); window.location='index.php';</script>";
        exit();
    }
}

// ERROR
else {
    echo "<script>alert('Usuario o contraseña incorrecta'); window.location='index.php';</script>";
}

// Liberar resultados y cerrar
mysqli_free_result($resultado_cliente);
mysqli_free_result($resultado_emple);
mysqli_free_result($resultado_admin);
mysqli_close($conexion);
