<?php
class RegistroControlador {
    private $modelo;

    public function __construct($db) {
        require_once MODELS_DIR . 'registro.modelo.php';
        $this->modelo = new RegistroModelo($db);
    }

    public function mostrarRegistro() {
        // Si ya está logueado, redirigir al perfil
        if (isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
            exit();
        }
        
        // Obtener departamentos para el select
        $departamentos = $this->modelo->obtenerDepartamentos();
        require_once VIEWS_DIR . 'registro.php';
    }

    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['contrasena'] !== $_POST['confirmar_contrasena']) {
                $error = "Las contraseñas no coinciden";
                $departamentos = $this->modelo->obtenerDepartamentos();
                require_once VIEWS_DIR . 'registro.php';
                return;
            }

            // Registrar primero la persona
            $id_persona = $this->modelo->registrarPersona(
                $_POST['nombres'], 
                $_POST['apellidos'], 
                $_POST['ci'], 
                $_POST['fecha_nacimiento'], 
                $_POST['direccion'], 
                $_POST['telefono'], 
                $_POST['correo'], 
                $_POST['genero'], 
                $_POST['id_departamento']
            );

            if (!$id_persona) {
                $error = "Error al registrar los datos personales";
                error_log("Error: Falló registrarPersona");
                $departamentos = $this->modelo->obtenerDepartamentos();
                require_once VIEWS_DIR . 'registro.php';
                return;
            }

            if ($id_persona) {
                // Luego registrar el cliente
                $id_cliente = $this->modelo->registrarCliente(
                    $_POST['usuario'], 
                    $_POST['contrasena'], 
                    $id_persona
                );

                if ($id_cliente) {
                    // Obtener datos completos del nuevo usuario
                    $usuario = $this->modelo->obtenerUsuarioPorId($id_cliente);
                    
                    // Autenticar al usuario
                    $_SESSION['id_cliente'] = $usuario['id_cliente'];
                    $_SESSION['nombre_cliente'] = $usuario['nombres'];
                    $_SESSION['apellido_cliente'] = $usuario['apellidos'];
                    $_SESSION['email_cliente'] = $usuario['correo'];
                    $_SESSION['usuario'] = $usuario['usuario'];
                    $_SESSION['ci_cliente'] = $usuario['ci'];
                    $_SESSION['direccion_cliente'] = $usuario['direccion'];
                    $_SESSION['telefono_cliente'] = $usuario['telefono'];
                    
                    header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
                    exit();
                } else {
                    $error = "No se pudo registrar el cliente (posible usuario duplicado)";
                    error_log("Error: Falló registrarCliente para persona $id_persona");
                    $departamentos = $this->modelo->obtenerDepartamentos();
                    require_once VIEWS_DIR . 'registro.php';
                    return;
                }
            }
            
            $error = "Error al registrar el usuario. Por favor, intente nuevamente.";
            $departamentos = $this->modelo->obtenerDepartamentos();
            require_once VIEWS_DIR . 'registro.php';
        }
    }
}
?>