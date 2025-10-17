<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "grupo_ferreteria";

$conexion = new mysqli($host, $user, $pass, $db);

// Verifica conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establece codificación UTF-8
$conexion->set_charset("utf8");

class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $base_datos = "grupo_ferreteria";
    private $conn;

    public function conectar() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->base_datos", $this->usuario, $this->contrasena);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET CHARACTER SET utf8");
            return $this->conn;
        } catch (PDOException $e) {
            // Registrar el error en el log y lanzar excepción para que el llamador lo maneje
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            throw new Exception("No se pudo conectar a la base de datos. Por favor, contacte al administrador.");
        }
    }
}
?>