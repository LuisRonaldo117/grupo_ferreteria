<?php

class InicioModelo {
    public function obtenerDatosInicio() {
        $conexion = conectarBD();
        
        // Carrusel
        $imagenesCarrusel = [
            [
                'imagen' => 'https://www.fierros.com.co/uploads/news-pictures/pphoto-1315.png',
                'titulo' => 'Herramientas Profesionales',
                'descripcion' => 'La mejor calidad para tus proyectos'
            ],
            [
                'imagen' => 'https://grupotorices.com/wp-content/uploads/2022/10/mejores-materiales-para-construccion-2022.jpg',
                'titulo' => 'Materiales de Construcción',
                'descripcion' => 'Todo lo que necesitas para construir'
            ],
            [
                'imagen' => 'https://www.axalta.com/content/axalta_blog_mx/es_ES/pintura_decorativa/accesorios-esenciales-para-pintar-una-pared-como-un-profesional/_jcr_content/parMainContent/layoutcontainer_copy/parMainContentLayout/col12/parMainContent/mediateaser_1120113379.axFitIn.mediaTeaser_desktop_x1.png/1697043272284/ACCESORIOS+ESENCIALES+PARA+PINTAR+UNA+PARED+COMO+UN+PROFESIONAL-2.png',
                'titulo' => 'Pinturas y Accesorios',
                'descripcion' => 'Colorea y protege tus espacios'
            ]
        ];

        // Obtener categorias de la bd
        $categoriasCatalogo = [];
        $sql = "SELECT id_categoria, nombre_categoria FROM categoria";
        $result = $conexion->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $categoriasCatalogo[] = [
                    'id' => $row['id_categoria'],
                    'nombre' => $row['nombre_categoria'],
                    'icono' => $this->obtenerIconoCategoria($row['nombre_categoria']),
                    'descripcion' => $this->obtenerDescripcionCategoria($row['nombre_categoria']),
                    'url' => 'index.php?c=catalogo&categoria=' . $row['id_categoria']
                ];
            }
        }
        
        $conexion->close();

        return [
            'titulo' => 'Bienvenido a nuestra Ferretería',
            'descripcion' => 'Encuentra todo lo que necesitas para tus proyectos',
            'imagenesCarrusel' => $imagenesCarrusel,
            'categoriasCatalogo' => $categoriasCatalogo
        ];
    }
    
    private function obtenerIconoCategoria($nombreCategoria) {
        $iconos = [
            'HERRAMIENTAS MANUALES' => '🔨',
            'HERRAMIENTAS ELÉCTRICAS' => '⚡',
            'MATERIALES DE CONSTRUCCIÓN' => '🏗️',
            'TORNILLERÍA Y SUJECIÓN' => '🔩',
            'ELECTRICIDAD' => '💡',
            'FONTANERÍA' => '🚰',
            'PINTURAS Y ACCESORIOS' => '🎨',
            'SEGURIDAD INDUSTRIAL' => '🛡️'
        ];
        
        return $iconos[$nombreCategoria] ?? '📦';
    }
    
    private function obtenerDescripcionCategoria($nombreCategoria) {
        $descripciones = [
            'HERRAMIENTAS MANUALES' => 'Martillos, destornilladores, alicates',
            'HERRAMIENTAS ELÉCTRICAS' => 'Taladros, amoladoras, sierras',
            'MATERIALES DE CONSTRUCCIÓN' => 'Cemento, arena, ladrillos',
            'TORNILLERÍA Y SUJECIÓN' => 'Tornillos, tuercas, clavos',
            'ELECTRICIDAD' => 'Cables, interruptores, focos',
            'FONTANERÍA' => 'Tuberías, grifos, conexiones',
            'PINTURAS Y ACCESORIOS' => 'Pinturas, brochas, rodillos',
            'SEGURIDAD INDUSTRIAL' => 'Cascos, guantes, botas'
        ];
        
        return $descripciones[$nombreCategoria] ?? 'Productos de calidad';
    }

    public function obtenerNotificaciones($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT * FROM notificaciones 
                WHERE id_cliente = ? 
                ORDER BY fecha_creacion DESC 
                LIMIT 10";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notificaciones = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $notificaciones[] = [
                    'id' => $row['id_notificacion'],
                    'titulo' => $row['titulo'],
                    'mensaje' => $row['mensaje'],
                    'tipo' => $row['tipo'],
                    'leida' => $row['leida'],
                    'fecha' => $this->formatearFecha($row['fecha_creacion'])
                ];
            }
        }
        
        $stmt->close();
        $conexion->close();
        
        return $notificaciones;
    }

    public function marcarComoLeida($idNotificacion, $idCliente) {
        $conexion = conectarBD();
        
        $sql = "UPDATE notificaciones SET leida = 1 
                WHERE id_notificacion = ? AND id_cliente = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idNotificacion, $idCliente);
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $resultado;
    }

    public function obtenerCantidadNoLeidas($idCliente) {
        $conexion = conectarBD();
        
        $sql = "SELECT COUNT(*) as total FROM notificaciones 
                WHERE id_cliente = ? AND leida = 0";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $total = 0;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total = $row['total'];
        }
        
        $stmt->close();
        $conexion->close();
        
        return $total;
    }

    private function formatearFecha($fecha) {
        $fechaObj = new DateTime($fecha);
        $ahora = new DateTime();
        $diferencia = $ahora->diff($fechaObj);
        
        if ($diferencia->d > 0) {
            return $fechaObj->format('d/m/Y H:i');
        } elseif ($diferencia->h > 0) {
            return "Hace " . $diferencia->h . " hora" . ($diferencia->h > 1 ? 's' : '');
        } elseif ($diferencia->i > 0) {
            return "Hace " . $diferencia->i . " minuto" . ($diferencia->i > 1 ? 's' : '');
        } else {
            return "Hace unos segundos";
        }
    }
}
?>