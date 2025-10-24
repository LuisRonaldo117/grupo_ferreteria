<?php

class UsuarioModelo {
    
    public function obtenerUsuario($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT c.id_cliente, p.nombres, p.apellidos, p.ci, p.correo, p.telefono, 
                       p.direccion, p.fecha_nacimiento, p.genero, d.nom_departamento,
                       c.fecha_creacion
                FROM cliente c
                INNER JOIN persona p ON c.id_persona = p.id_persona
                INNER JOIN departamento d ON p.id_departamento = d.id_departamento
                WHERE c.id_cliente = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $stmt->close();
            $conexion->close();
            return $usuario;
        }
        
        $stmt->close();
        $conexion->close();
        return null;
    }
    
    public function obtenerPedidos($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT p.id_pedido, p.fecha_pedido, p.estado, p.total, p.tipo_pago,
                       CONCAT('FERR-', LPAD(p.id_pedido, 5, '0')) as numero_pedido
                FROM pedido p
                WHERE p.id_cliente = ?
                ORDER BY p.fecha_pedido DESC";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pedidos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
        }
        
        $stmt->close();
        $conexion->close();
        return $pedidos;
    }
    
    public function obtenerDetallePedido($idPedido, $idCliente) {
        $conexion = conectarBD();
        
        // Verificar que el pedido pertenece al cliente
        $sql = "SELECT p.*, CONCAT('FERR-', LPAD(p.id_pedido, 5, '0')) as numero_pedido
                FROM pedido p
                WHERE p.id_pedido = ? AND p.id_cliente = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idPedido, $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $pedido = $result->fetch_assoc();
            
            // Obtener detalles del pedido
            $sql_detalle = "SELECT dp.cantidad, dp.precio_unitario, dp.total,
                                   pr.nombre as producto_nombre, pr.descripcion
                            FROM detalle_pedido dp
                            INNER JOIN producto pr ON dp.id_producto = pr.id_producto
                            WHERE dp.id_pedido = ?";
            
            $stmt_detalle = $conexion->prepare($sql_detalle);
            $stmt_detalle->bind_param("i", $idPedido);
            $stmt_detalle->execute();
            $result_detalle = $stmt_detalle->get_result();
            
            $detalles = [];
            if ($result_detalle && $result_detalle->num_rows > 0) {
                while($row = $result_detalle->fetch_assoc()) {
                    $detalles[] = $row;
                }
            }
            
            $pedido['detalles'] = $detalles;
            $stmt_detalle->close();
            $stmt->close();
            $conexion->close();
            return $pedido;
        }
        
        $stmt->close();
        $conexion->close();
        return null;
    }
    
    public function actualizarPerfil($idCliente, $datos) {
        $conexion = conectarBD();
        
        if (!$conexion) {
            error_log("Error: No se pudo conectar a la base de datos");
            return false;
        }
        
        // Inicializar variables
        $stmt_persona = null;
        $stmt_verificar = null;
        $stmt_actualizar = null;
        
        try {
            // Obtenemos el id_persona del cliente
            $sql_persona = "SELECT id_persona FROM cliente WHERE id_cliente = ?";
            $stmt_persona = $conexion->prepare($sql_persona);
            $stmt_persona->bind_param("i", $idCliente);
            
            if (!$stmt_persona->execute()) {
                throw new Exception("Error al obtener id_persona: " . $stmt_persona->error);
            }
            
            $result_persona = $stmt_persona->get_result();
            
            if ($result_persona && $result_persona->num_rows > 0) {
                $cliente = $result_persona->fetch_assoc();
                $idPersona = $cliente['id_persona'];
                
                
                // Verificar si el correo ya existe en otro usuario
                $sql_verificar_correo = "SELECT id_persona FROM persona WHERE correo = ? AND id_persona != ?";
                $stmt_verificar = $conexion->prepare($sql_verificar_correo);
                $stmt_verificar->bind_param("si", $datos['correo'], $idPersona);
                
                if (!$stmt_verificar->execute()) {
                    throw new Exception("Error al verificar correo: " . $stmt_verificar->error);
                }
                
                $result_verificar = $stmt_verificar->get_result();
                
                if ($result_verificar && $result_verificar->num_rows > 0) {
                    throw new Exception("El correo electrónico ya está en uso por otro usuario");
                }
                
                // Cerrar la verificacion
                $stmt_verificar->close();
                $stmt_verificar = null;
                
                // Actualizar datos del usuario
                $sql_actualizar = "UPDATE persona SET 
                                nombres = ?, apellidos = ?, correo = ?, 
                                telefono = ?, direccion = ?
                                WHERE id_persona = ?";
                
                $stmt_actualizar = $conexion->prepare($sql_actualizar);
                $stmt_actualizar->bind_param("sssssi", 
                    $datos['nombres'], 
                    $datos['apellidos'], 
                    $datos['correo'],
                    $datos['telefono'], 
                    $datos['direccion'], 
                    $idPersona
                );
                
                $resultado = $stmt_actualizar->execute();
                
                if (!$resultado) {
                    throw new Exception("Error al actualizar perfil: " . $stmt_actualizar->error);
                }
                
                // Verificar cuantas filas fueron afectadas
                $filasAfectadas = $stmt_actualizar->affected_rows;
                error_log("Filas afectadas en la actualización: " . $filasAfectadas);
                
                // Cerrar la actualización
                $stmt_actualizar->close();
                $stmt_actualizar = null;
                
                // Cerrar statement de persona
                $stmt_persona->close();
                $stmt_persona = null;
                
                // Cerrar conexion
                $conexion->close();
                
                return $resultado && $filasAfectadas > 0;
            } else {
                throw new Exception("Cliente no encontrado");
            }
            
        } catch (Exception $e) {
            error_log("Error en actualizarPerfil modelo: " . $e->getMessage());
            
            // Cerrar statements si es que estan abiertos
            if ($stmt_persona) $stmt_persona->close();
            if ($stmt_verificar) $stmt_verificar->close();
            if ($stmt_actualizar) $stmt_actualizar->close();
            
            // Cerrar conexion
            $conexion->close();
            
            return false;
        }
    }
}
?>