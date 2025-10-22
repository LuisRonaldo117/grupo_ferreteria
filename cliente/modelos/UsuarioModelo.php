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
        
        // Primero obtenemos el id_persona del cliente
        $sql_persona = "SELECT id_persona FROM cliente WHERE id_cliente = ?";
        $stmt_persona = $conexion->prepare($sql_persona);
        $stmt_persona->bind_param("i", $idCliente);
        $stmt_persona->execute();
        $result_persona = $stmt_persona->get_result();
        
        if ($result_persona && $result_persona->num_rows > 0) {
            $cliente = $result_persona->fetch_assoc();
            $idPersona = $cliente['id_persona'];
            
            // Actualizar datos de la persona
            $sql_actualizar = "UPDATE persona SET 
                              nombres = ?, apellidos = ?, correo = ?, 
                              telefono = ?, direccion = ?, fecha_actualizacion = NOW()
                              WHERE id_persona = ?";
            
            $stmt_actualizar = $conexion->prepare($sql_actualizar);
            $stmt_actualizar->bind_param("sssssi", 
                $datos['nombres'], $datos['apellidos'], $datos['correo'],
                $datos['telefono'], $datos['direccion'], $idPersona
            );
            
            $resultado = $stmt_actualizar->execute();
            $stmt_actualizar->close();
            $stmt_persona->close();
            $conexion->close();
            
            return $resultado;
        }
        
        $stmt_persona->close();
        $conexion->close();
        return false;
    }
}
?>