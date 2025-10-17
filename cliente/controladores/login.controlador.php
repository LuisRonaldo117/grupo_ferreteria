<?php
class LoginControlador {
    private $modelo;

    public function __construct($db) {
        require_once MODELS_DIR . 'login.modelo.php';
        $this->modelo = new LoginModelo($db);
    }

    public function mostrarLogin() {
        // Si ya está logueado, redirigir al perfil
        if (isset($_SESSION['id_cliente'])) {
            header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
            exit();
        }
        require_once VIEWS_DIR . 'login.php';
    }

    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = $this->modelo->verificarCredenciales($email, $password);

            if ($usuario) {
                // Establecemos TODAS las variables de sesión
                $_SESSION['id_cliente'] = $usuario['id_cliente'];
                $_SESSION['nombre_cliente'] = $usuario['nombres']; 
                $_SESSION['apellido_cliente'] = $usuario['apellidos']; 
                $_SESSION['email_cliente'] = $usuario['correo'];
                $_SESSION['usuario'] = $usuario['usuario'];
                $_SESSION['ci_cliente'] = $usuario['ci'];
                $_SESSION['direccion_cliente'] = $usuario['direccion'];
                $_SESSION['telefono_cliente'] = $usuario['telefono'];
                
                // Redirigir al perfil del usuario
                header('Location: ' . BASE_URL . 'cliente/?ruta=usuario');
                exit();
            } else {
                $error = "Credenciales incorrectas";
                require_once VIEWS_DIR . 'login.php';
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        session_write_close();
        header('Location: ' . BASE_URL . 'index.php'); // 🔁 redirige al index general
        //header('Location: ' . BASE_URL . 'cliente/?ruta=login');
        exit();
    }
}
?>