<?php

class InformateModelo {
    public function obtenerInformacion() {
        return [
            'titulo' => 'Infórmate',
            'portada' => [
                'titulo' => 'Infórmate con Nosotros',
                'subtitulo' => 'Explora artículos, tutoriales y consejos prácticos para llevar tus proyectos de construcción y bricolaje al siguiente nivel.',
                'imagen' => 'https://images.unsplash.com/photo-1581094794329-cdc53f4b629f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'
            ],
            'recursos' => [
                'titulo' => 'Recursos para tus Proyectos',
                'categorias' => [
                    [
                        'icono' => '📚',
                        'nombre' => 'Artículos',
                        'descripcion' => 'Información detallada sobre materiales, técnicas y tendencias'
                    ],
                    [
                        'icono' => '🎥',
                        'nombre' => 'Tutoriales',
                        'descripcion' => 'Guías paso a paso en video para aprender haciendo'
                    ],
                    [
                        'icono' => '💡',
                        'nombre' => 'Consejos',
                        'descripcion' => 'Tips prácticos de expertos para mejores resultados'
                    ]
                ]
            ],
            'articulos' => [
                'titulo' => 'Artículos Destacados',
                'items' => [
                    [
                        'id' => 1,
                        'categoria' => 'Herramientas eléctricas',
                        'etiqueta' => 'Herramientas',
                        'fecha' => '15 Mayo 2025',
                        'titulo' => 'Las 5 mejores herramientas eléctricas para principiantes',
                        'descripcion' => 'Descubre cuáles son las herramientas esenciales que todo aficionado al bricolaje debería tener en su taller.',
                        'imagen' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'slug' => 'mejores-herramientas-electricas-principiantes'
                    ],
                    [
                        'id' => 2,
                        'categoria' => 'Pintura',
                        'etiqueta' => 'Pintura',
                        'fecha' => '2 Mayo 2025',
                        'titulo' => 'Cómo elegir la pintura perfecta para cada superficie',
                        'descripcion' => 'Guía completa para seleccionar el tipo de pintura adecuado según el material y las condiciones del ambiente.',
                        'imagen' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'slug' => 'elegir-pintura-perfecta-superficie'
                    ],
                    [
                        'id' => 3,
                        'categoria' => 'Fontanería',
                        'etiqueta' => 'Fontanería',
                        'fecha' => '20 Abril 2025',
                        'titulo' => 'Soluciones rápidas para problemas comunes de fontanería',
                        'descripcion' => 'Aprende a resolver esos pequeños problemas de tuberías que pueden convertirse en grandes dolores de cabeza.',
                        'imagen' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'slug' => 'soluciones-rapidas-problemas-fontaneria'
                    ],
                    [
                        'id' => 4,
                        'categoria' => 'Seguridad',
                        'etiqueta' => 'Seguridad',
                        'fecha' => '10 Abril 2025',
                        'titulo' => 'Equipo de protección personal esencial en construcción',
                        'descripcion' => 'Conoce los elementos de seguridad indispensables para protegerte durante tus proyectos.',
                        'imagen' => 'https://images.unsplash.com/photo-1581094794329-cdc53f4b629f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'slug' => 'equipo-proteccion-personal-construccion'
                    ]
                ]
            ],
            'tutoriales' => [
                'titulo' => 'Tutoriales en Video',
                'items' => [
                    [
                        'titulo' => 'Curso de Carpintería',
                        'descripcion' => 'Aprende las técnicas básicas de carpintería para crear, reparar y personalizar muebles y estructuras de madera de forma segura y práctica.',
                        'video_url' => 'https://youtu.be/5e290TCqf6o',
                        'imagen' => 'https://images.unsplash.com/photo-1572981779307-38f8b0448d51?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'duracion' => '15:30'
                    ],
                    [
                        'titulo' => 'Ideas para pintar el interior de casa',
                        'descripcion' => 'Descubre ideas creativas, técnicas de aplicación y combinaciones de colores que te ayudarán a renovar y pintar, logrando un ambiente lleno de estilo, armonía y personalidad.',
                        'video_url' => 'https://youtu.be/N0CM9Oklnls',
                        'imagen' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'duracion' => '12:45'
                    ],
                    [
                        'titulo' => 'Herramientas de Fontanería',
                        'descripcion' => 'Conoce las herramientas esenciales de fontanería y aprende cómo usarlas correctamente para realizar reparaciones e instalaciones en el hogar de forma práctica y segura.',
                        'video_url' => 'https://youtu.be/Mj_QCtz7pUc',
                        'imagen' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                        'duracion' => '18:20'
                    ]
                ]
            ],
            'consejos' => [
                'titulo' => 'Consejos Prácticos',
                'items' => [
                    [
                        'icono' => '🔨',
                        'titulo' => 'Mantenimiento de Herramientas',
                        'consejos' => [
                            'Limpia tus herramientas después de cada uso',
                            'Almacénalas en lugar seco y organizado',
                            'Afilas las cuchillas regularmente',
                            'Revisa los cables de herramientas eléctricas'
                        ]
                    ],
                    [
                        'icono' => '🎨',
                        'titulo' => 'Técnicas de Pintura',
                        'consejos' => [
                            'Prepara la superficie antes de pintar',
                            'Usa cinta de enmascarar para bordes limpios',
                            'Aplica varias capas finas en lugar de una gruesa',
                            'Limpia los rodillos inmediatamente después de usar'
                        ]
                    ],
                    [
                        'icono' => '🚰',
                        'titulo' => 'Fontanería Básica',
                        'consejos' => [
                            'Cierra la llave de paso antes de hacer reparaciones',
                            'Usa teflón en las roscas para evitar fugas',
                            'No uses productos químicos agresivos en tuberías',
                            'Instala filtros en los desagües para evitar obstrucciones'
                        ]
                    ],
                    [
                        'icono' => '⚡',
                        'titulo' => 'Seguridad Eléctrica',
                        'consejos' => [
                            'Desconecta la corriente antes de trabajar',
                            'Usa herramientas con aislamiento adecuado',
                            'No sobrecargues los enchufes',
                            'Instala protectores en tomas con niños'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function obtenerArticulo($id) {
        $articulos = $this->obtenerInformacion()['articulos']['items'];
        
        foreach ($articulos as $articulo) {
            if ($articulo['id'] == $id) {
                return array_merge($articulo, $this->obtenerContenidoArticulo($id));
            }
        }
        
        return null;
    }

    private function obtenerContenidoArticulo($id) {
        $contenidos = [
            1 => [
                'contenido' => '
                    <p>Si estás comenzando en el mundo del bricolaje o la construcción, tener las herramientas eléctricas adecuadas puede marcar la diferencia entre un proyecto exitoso y uno frustrante. Estas son las 5 herramientas esenciales que todo principiante debería considerar:</p>

                    <h3>1. Taladro Percutor</h3>
                    <p>El taladro es probablemente la herramienta eléctrica más versátil. Un buen taladro percutor te permitirá:</p>
                    <ul>
                        <li>Perforar madera, metal y concreto</li>
                        <li>Atornillar y desatornillar con diferentes cabezales</li>
                        <li>Trabajar en proyectos de montaje y reparación</li>
                    </ul>

                    <h3>2. Sierra Caladora</h3>
                    <p>Perfecta para cortes curvos y diseños complejos en madera. Ideal para:</p>
                    <ul>
                        <li>Cortar formas irregulares</li>
                        <li>Trabajar con tableros de madera</li>
                        <li>Proyectos de carpintería básica</li>
                    </ul>

                    <h3>3. Lijadora Orbital</h3>
                    <p>Para obtener acabados profesionales en superficies de madera. Sus ventajas incluyen:</p>
                    <ul>
                        <li>Acabado uniforme sin marcas circulares</li>
                        <li>Fácil cambio de papel de lija</li>
                        <li>Ideal para preparar superficies antes de pintar</li>
                    </ul>

                    <h3>4. Pistola de Calor</h3>
                    <p>Una herramienta multifuncional que sirve para:</p>
                    <ul>
                        <li>Quitar pintura vieja</li>
                        <li>Descongelar tuberías</li>
                        <li>Doblar plástico y soldar</li>
                    </ul>

                    <h3>5. Atornillador Eléctrico</h3>
                    <p>Para proyectos que requieren muchos tornillos, esta herramienta te ahorrará tiempo y esfuerzo.</p>

                    <h3>Consejos de Seguridad</h3>
                    <p>Recuerda siempre usar equipo de protección personal: gafas de seguridad, guantes y mascarilla cuando sea necesario.</p>
                ',
                'autor' => 'Ing. Carlos Mendoza',
                'tiempo_lectura' => '5 min'
            ],
            2 => [
                'contenido' => '
                    <p>Elegir la pintura correcta es crucial para el éxito de cualquier proyecto de pintura. La pintura adecuada no solo mejora la apariencia, sino que también protege las superficies y dura más tiempo.</p>

                    <h3>Tipos de Pintura Según la Superficie</h3>

                    <h4>Para Paredes Interiores</h4>
                    <p><strong>Pintura Látex (Acrílica):</strong> Ideal para la mayoría de paredes interiores. Es lavable, de secado rápido y baja emisión de olores.</p>

                    <h4>Para Madera</h4>
                    <p><strong>Esmaltes Sintéticos:</strong> Perfectos para muebles, puertas y ventanas. Ofrecen acabado duradero y resistente.</p>

                    <h4>Para Metal</h4>
                    <p><strong>Pinturas Anticorrosivas:</strong> Contienen inhibidores de óxido que protegen el metal de la corrosión.</p>

                    <h3>Consideraciones por Ambiente</h3>

                    <h4>Cocinas y Baños</h4>
                    <p>Usa pinturas resistentes a la humedad y al moho. Las pinturas semibrillantes o satinadas son ideales porque son lavables y resisten mejor la humedad.</p>

                    <h4>Dormitorios y Salas</h4>
                    <p>Pinturas mate o satinadas crean ambientes acogedores y disimulan mejor las imperfecciones de las paredes.</p>

                    <h3>Preparación de la Superficie</h3>
                    <ul>
                        <li>Limpia y lava la superficie</li>
                        <li>Lija para eliminar imperfecciones</li>
                        <li>Aplica sellador o imprimante</li>
                        <li>Deja secar completamente entre capas</li>
                    </ul>
                ',
                'autor' => 'Arq. Laura Fernández',
                'tiempo_lectura' => '7 min'
            ],
            3 => [
                'contenido' => '
                    <p>Los problemas de fontanería pueden aparecer en cualquier momento, pero muchos de ellos tienen soluciones simples que puedes realizar tú mismo sin necesidad de llamar a un profesional.</p>

                    <h3>1. Destapar Desagües Lentos</h3>
                    <p><strong>Solución:</strong> Mezcla media taza de bicarbonato con media taza de vinagre blanco. Vierte por el desagüe, deja actuar 30 minutos y luego enjuaga con agua caliente.</p>

                    <h3>2. Grifos que Gotean</h3>
                    <p><strong>Solución:</strong> Cierra la llave de paso, desmonta el grifo y reemplaza las arandelas desgastadas. Las arandelas de goma son económicas y fáciles de instalar.</p>

                    <h3>3. Inodoros que no Dejan de Correr</h3>
                    <p><strong>Solución:</strong> Revisa y ajusta la cadena del flotador. Si el problema persiste, probablemente necesites reemplazar la válvula de llenado.</p>

                    <h3>4. Tuberías Congeladas</h3>
                    <p><strong>Solución:</strong> Usa una pistola de calor o compresas calientes para descongelar lentamente. Nunca uses llama abierta.</p>

                    <h3>Herramientas Básicas que Necesitarás</h3>
                    <ul>
                        <li>Llave ajustable</li>
                        <li>Destornilladores</li>
                        <li>Cinta de teflón</li>
                        <li>Desatascador (ventosa)</li>
                        <li>Arandelas de repuesto</li>
                    </ul>

                    <h3>Cuándo Llamar a un Profesional</h3>
                    <p>Si el problema involucra la línea principal de agua, fugas importantes o trabajos que requieren soldadura, es mejor contactar a un fontanero certificado.</p>
                ',
                'autor' => 'Téc. Miguel Torres',
                'tiempo_lectura' => '6 min'
            ],
            4 => [
                'contenido' => '
                    <p>La seguridad debe ser siempre la prioridad número uno en cualquier proyecto de construcción o bricolaje. El equipo de protección personal (EPP) adecuado puede prevenir lesiones graves.</p>

                    <h3>Equipo de Protección Esencial</h3>

                    <h4>1. Casco de Seguridad</h4>
                    <p>Protege contra impactos en la cabeza por objetos que caen. Es obligatorio en obras de construcción y recomendable en cualquier proyecto donde haya riesgo de golpes.</p>

                    <h4>2. Gafas de Protección</h4>
                    <p>Protegen los ojos de partículas, polvo, virutas y salpicaduras químicas. Deben ser resistentes a impactos y ajustarse cómodamente.</p>

                    <h4>3. Guantes de Trabajo</h4>
                    <p>Existen diferentes tipos según el trabajo:</p>
                    <ul>
                        <li><strong>Guantes de cuero:</strong> Para manejo de materiales</li>
                        <li><strong>Guantes anticorte:</strong> Para usar con herramientas afiladas</li>
                        <li><strong>Guantes dieléctricos:</strong> Para trabajos eléctricos</li>
                    </ul>

                    <h4>4. Calzado de Seguridad</h4>
                    <p>Botas con punta de acero que protegen los pies de objetos pesados que puedan caer. Deben ser antideslizantes y resistentes a productos químicos.</p>

                    <h4>5. Protección Auditiva</h4>
                    <p>Orejeras o tapones para proteger contra el ruido de herramientas eléctricas como sierras y taladros.</p>

                    <h4>6. Mascarilla Respiratoria</h4>
                    <p>Esencial cuando se trabaja con polvo, pinturas en aerosol o productos químicos.</p>

                    <h3>Mantenimiento del EPP</h3>
                    <ul>
                        <li>Inspecciona regularmente el equipo</li>
                        <li>Limpia después de cada uso</li>
                        <li>Reemplaza equipo dañado inmediatamente</li>
                        <li>Almacena en lugar limpio y seco</li>
                    </ul>
                ',
                'autor' => 'Ing. Ana Rodríguez',
                'tiempo_lectura' => '8 min'
            ]
        ];

        return $contenidos[$id] ?? ['contenido' => '<p>Contenido no disponible.</p>', 'autor' => 'Autor no disponible', 'tiempo_lectura' => 'N/A'];
    }
}
?>