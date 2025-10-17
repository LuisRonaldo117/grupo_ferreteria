<?php
class RegistroModelo {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerDepartamentos() {
        try {
            $query = "SELECT * FROM departamento";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener departamentos: " . $e->getMessage());
            return [];
        }
    }

    public function registrarPersona($nombres, $apellidos, $ci, $fecha_nacimiento, 
                                   $direccion, $telefono, $correo, $genero, $id_departamento) {
        try {
            $query = "INSERT INTO persona (nombres, apellidos, ci, fecha_nacimiento, 
                      direccion, telefono, correo, genero, id_departamento)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nombres, $apellidos, $ci, $fecha_nacimiento, 
                           $direccion, $telefono, $correo, $genero, $id_departamento]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al registrar persona: " . $e->getMessage());
            return false;
        }
    }

    public function registrarCliente($usuario, $contrasena, $id_persona) {
        try {
            // Verificar si el usuario ya existe
            if ($this->existeUsuario($usuario)) {
                return false;
            }

            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO cliente (usuario, contrasena, id_persona)
                      VALUES (?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$usuario, $contrasena_hash, $id_persona]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al registrar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioPorId($id_cliente) {
        try {
            $query = "SELECT c.id_cliente, c.usuario, p.nombres, p.apellidos, p.ci, 
                      p.correo, p.direccion, p.telefono
                      FROM cliente c
                      JOIN persona p ON c.id_persona = p.id_persona
                      WHERE c.id_cliente = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_cliente]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return false;
        }
    }

    private function existeUsuario($usuario) {
        try {
            $query = "SELECT COUNT(*) FROM cliente WHERE usuario = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$usuario]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar usuario: " . $e->getMessage());
            return false;
        }
    }
}
?>