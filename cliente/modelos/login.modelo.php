<?php
class LoginModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function verificarCredenciales($email, $password) {
        try {
            $query = "SELECT c.id_cliente, c.usuario, p.nombres, p.apellidos, p.ci, 
                      p.correo, p.direccion, p.telefono, c.contrasena 
                      FROM cliente c
                      JOIN persona p ON c.id_persona = p.id_persona
                      WHERE p.correo = ? OR c.usuario = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email, $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

           // if ($usuario && password_verify($password, $usuario['contrasena'])) {
            if ($usuario && $password === $usuario['contrasena']) {
    unset($usuario['contrasena']);
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }
}
?>