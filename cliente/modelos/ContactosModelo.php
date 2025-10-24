<?php

class ContactosModelo {
    
    public function obtenerInformacion() {
        // Obtener sucursales de la bd
        $sucursales = $this->obtenerSucursales();
        
        return [
            'titulo' => 'Contactos',
            'portada' => [
                'titulo' => 'CONTÁCTANOS',
                'subtitulo' => 'Estamos aquí para ayudarte con cualquier consulta, reclamo o solicitud que tengas',
                'imagen' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'
            ],
            'sucursales' => [
                'titulo' => 'Nuestras Sucursales',
                'items' => $sucursales
            ],
            'formulario' => [
                'titulo' => 'Envíanos un Mensaje',
                'asuntos' => [
                    'consulta' => 'Consulta General',
                    'reclamo' => 'Reclamo',
                    'soporte' => 'Soporte Técnico',
                    'sugerencia' => 'Sugerencia',
                    'otro' => 'Otro'
                ]
            ],
            'informacion' => [
                'titulo' => 'Información de Contacto',
                'items' => [
                    [
                        'titulo' => 'Atención al Cliente',
                        'descripcion' => 'Estamos disponibles para atender tus consultas y solicitudes.',
                        'telefono' => '+591 77584652',
                        'email' => 'contacto@ferreteria.com',
                        'icono' => '📞'
                    ],
                    [
                        'titulo' => 'Soporte Técnico',
                        'descripcion' => 'Asesoría técnica especializada para tus proyectos.',
                        'telefono' => '+591 78632451',
                        'email' => 'soporte@ferreteria.com',
                        'icono' => '🔧'
                    ],
                    [
                        'titulo' => 'Oficina Central',
                        'descripcion' => 'Av. 16 de Julio #789, El Prado, La Paz',
                        'horario' => 'Lunes a Sábado: 8:30 - 19:30',
                        'icono' => '🏢'
                    ]
                ]
            ],
            'redes' => [
                'titulo' => 'Síguenos en Redes',
                'items' => [
                    ['icono' => '📘', 'nombre' => 'Facebook', 'url' => '#'],
                    ['icono' => '📷', 'nombre' => 'Instagram', 'url' => '#'],
                    ['icono' => '🐦', 'nombre' => 'Twitter', 'url' => '#'],
                    ['icono' => '💼', 'nombre' => 'LinkedIn', 'url' => '#']
                ]
            ]
        ];
    }
    
    public function obtenerSucursales() {
        $conexion = conectarBD();
        
        $sql = "SELECT id_sucursal, nombre, direccion, telefono, email 
                FROM sucursal 
                WHERE id_sucursal IN (1, 2)";
        
        $result = $conexion->query($sql);
        $sucursales = [];
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sucursales[] = [
                    'id' => $row['id_sucursal'],
                    'nombre' => $row['nombre'],
                    'direccion' => $row['direccion'],
                    'telefono' => $row['telefono'],
                    'horario' => 'Lunes a Sábado: 8:30 - 19:30',
                    'stock' => 'Stock disponible',
                    'latitud' => $row['id_sucursal'] == 1 ? -16.4950 : -16.5080,
                    'longitud' => $row['id_sucursal'] == 1 ? -68.1334 : -68.1290,
                    'icono' => '📍'
                ];
            }
        }
        
        $conexion->close();
        return $sucursales;
    }
    
    public function obtenerUsuario($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT p.nombres, p.apellidos, p.correo, p.telefono
                FROM cliente c
                INNER JOIN persona p ON c.id_persona = p.id_persona
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
    
    public function guardarReclamo($datos) {
        $conexion = conectarBD();
        
        $sql = "INSERT INTO reclamos (descripcion, id_cliente, fecha_reclamo) 
                VALUES (?, ?, NOW())";
        
        $descripcion = $datos['mensaje'];
        
        $stmt = $conexion->prepare($sql);
        $idCliente = $datos['id_cliente'] ?: null;
        $stmt->bind_param("si", $descripcion, $idCliente);
        
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $resultado;
    }
}
?>