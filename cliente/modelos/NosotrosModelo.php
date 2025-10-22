<?php

class NosotrosModelo {
    public function obtenerInformacion() {
        return [
            'titulo' => 'Sobre Nosotros',
            'portada' => [
                'titulo' => 'FERRETERÍA',
                'subtitulo' => 'Más de 20 años construyendo confianza',
                'imagen' => 'https://images.unsplash.com/photo-1581094794329-cdc53f4b629f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'
            ],
            'historia' => [
                'titulo' => 'Nuestra Historia',
                'contenido' => 'Fundada en el año 2000, Ferretería nació con la visión de ser el aliado estratégico de constructores, arquitectos y hogares bolivianos. Lo que comenzó como un pequeño local familiar se ha convertido en una de las ferreterías más confiables del país, manteniendo siempre nuestro compromiso con la calidad y el servicio personalizado.'
            ],
            'trayectoria' => [
                'titulo' => 'Nuestra Trayectoria',
                'eventos' => [
                    ['año' => '2000', 'evento' => 'Apertura de nuestra primera tienda en La Paz'],
                    ['año' => '2005', 'evento' => 'Expansión a Santa Cruz con segunda sucursal'],
                    ['año' => '2010', 'evento' => 'Implementación de sistema de ventas online'],
                    ['año' => '2015', 'evento' => 'Certificación ISO 9001 de calidad'],
                    ['año' => '2020', 'evento' => 'Lanzamiento de app móvil y entrega a domicilio'],
                    ['año' => '2024', 'evento' => 'Apertura de centro de distribución nacional']
                ]
            ],
            'estadisticas' => [
                ['numero' => '1000+', 'texto' => 'Productos'],
                ['numero' => '5000+', 'texto' => 'Clientes Satisfechos'],
                ['numero' => '20+', 'texto' => 'Expertos en Equipo'],
                ['numero' => '24/7', 'texto' => 'Soporte']
            ],
            'valores' => [
                'titulo' => 'Nuestros Valores',
                'items' => [
                    [
                        'icono' => '✅',
                        'titulo' => 'Calidad',
                        'descripcion' => 'Trabajamos solo con productos de primera calidad y marcas reconocidas.'
                    ],
                    [
                        'icono' => '🤝',
                        'titulo' => 'Confianza',
                        'descripcion' => 'Más de 20 años construyendo relaciones duraderas con nuestros clientes.'
                    ],
                    [
                        'icono' => '💡',
                        'titulo' => 'Asesoramiento',
                        'descripcion' => 'Nuestro equipo experto te guía para tomar las mejores decisiones.'
                    ],
                    [
                        'icono' => '🚀',
                        'titulo' => 'Innovación',
                        'descripcion' => 'Siempre a la vanguardia con las últimas herramientas y tecnologías.'
                    ]
                ]
            ],
            'equipo' => [
                'titulo' => 'Conoce a Nuestro Equipo',
                'miembros' => [
                    [
                        'icono' => '👨‍💼',
                        'nombre' => 'Ronaldo Mamani',
                        'cargo' => 'Gerente General',
                        'descripcion' => 'Fundador y experto en construcción con más de 20 años de experiencia en el rubro.'
                    ],
                    [
                        'icono' => '👩‍💼',
                        'nombre' => 'Pilar Gonzales',
                        'cargo' => 'Jefa de Desarrollo Comercial',
                        'descripcion' => 'Lidera el crecimiento de ventas y la atención a proyectos clave del sector residencial.'
                    ],
                    [
                        'icono' => '👨‍🔧',
                        'nombre' => 'Gabriela Barrera',
                        'cargo' => 'Asesor Técnico',
                        'descripcion' => 'Ingeniera civil especializada en herramientas eléctricas y maquinaria pesada.'
                    ],
                    [
                        'icono' => '👩‍💻',
                        'nombre' => 'Diana Carvajal',
                        'cargo' => 'Jefa de Logística',
                        'descripcion' => 'Experta en cadena de suministros y distribución de materiales a gran escala.'
                    ]
                ]
            ],
            'testimonios' => [
                'titulo' => 'Lo que Dicen Nuestros Clientes',
                'comentarios' => [
                    [
                        'texto' => '"Como contratista, he trabajado con Ferretería por más de 10 años. Su asesoramiento técnico y la calidad de sus productos han sido clave para el éxito de mis proyectos."',
                        'nombre' => 'Juan Pérez',
                        'cargo' => 'Contratista General'
                    ],
                    [
                        'texto' => '"Cuando renové mi casa, el equipo de Ferretería me guió en cada paso. Encontré todo lo que necesitaba y más, con precios competitivos y excelente servicio."',
                        'nombre' => 'María Fernández',
                        'cargo' => 'Cliente Residencial'
                    ],
                    [
                        'texto' => '"Como arquitecto, valoro mucho la variedad de productos especializados que ofrecen. Son mi primera opción para proyectos de alta gama donde la calidad es primordial."',
                        'nombre' => 'Carlos Rojas',
                        'cargo' => 'Arquitecto'
                    ]
                ]
            ]
        ];
    }
}
?>