<?php

class ContactosModelo {
    public function obtenerInformacion() {
        return [
            'titulo' => 'Contactos',
            'portada' => [
                'titulo' => 'CONTÁCTANOS',
                'subtitulo' => 'Estamos aquí para ayudarte con cualquier consulta, reclamo o solicitud que tengas',
                'imagen' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'
            ],
            'sucursales' => [
                'titulo' => 'Nuestras Sucursales',
                'items' => [
                    [
                        'id' => 1,
                        'nombre' => 'Sucursal El Prado',
                        'direccion' => 'Av. 16 de Julio #789, El Prado',
                        'telefono' => '(591) 77683912',
                        'horario' => 'Lunes a Sábado: 8:30 - 19:30',
                        'stock' => 'Stock disponible',
                        'latitud' => -16.4950,
                        'longitud' => -68.1334,
                        'icono' => '📍'
                    ],
                    [
                        'id' => 2,
                        'nombre' => 'Sucursal Sopocachi',
                        'direccion' => 'Calle Aspiazu #456, Sopocachi',
                        'telefono' => '(591) 78965423',
                        'horario' => 'Lunes a Viernes: 9:00 - 19:00',
                        'stock' => 'Stock limitado',
                        'latitud' => -16.5080,
                        'longitud' => -68.1290,
                        'icono' => '📍'
                    ]
                ]
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
}
?>