<!-- Portada -->
<section class="portada-contactos">
    <div class="portada-imagen">
        <img src="<?php echo $portada['imagen']; ?>" alt="Contactos">
        <div class="portada-contenido">
            <h1><?php echo $portada['titulo']; ?></h1>
            <p><?php echo $portada['subtitulo']; ?></p>
        </div>
    </div>
</section>

<!-- Sucursales y Mapa -->
<section class="sucursales-section">
    <div class="container">
        <h2><?php echo $sucursales['titulo']; ?></h2>
        
        <div class="sucursales-layout">
            <!-- Lista de Sucursales -->
            <div class="sucursales-lista">
                <?php foreach($sucursales['items'] as $sucursal): ?>
                    <div class="sucursal-card" data-sucursal-id="<?php echo $sucursal['id']; ?>">
                        <div class="sucursal-header">
                            <div class="sucursal-icono"><?php echo $sucursal['icono']; ?></div>
                            <h3><?php echo $sucursal['nombre']; ?></h3>
                        </div>
                        <div class="sucursal-info">
                            <p><strong>📍 Dirección:</strong> <?php echo $sucursal['direccion']; ?></p>
                            <p><strong>📞 Teléfono:</strong> <?php echo $sucursal['telefono']; ?></p>
                            <p><strong>🕒 Horario:</strong> <?php echo $sucursal['horario']; ?></p>
                            <p><strong>📦 Stock:</strong> <span class="stock-status"><?php echo $sucursal['stock']; ?></span></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mapa -->
            <div class="mapa-container">
                <div id="map"></div>
                <div class="mapa-leyenda">
                    <p><strong>📍</strong> Haz click en los marcadores para más información</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formulario e Informacion -->
<section class="contacto-section">
    <div class="container">
        <div class="contacto-layout">
            <!-- Formulario -->
            <div class="formulario-container">
                <h2><?php echo $formulario['titulo']; ?></h2>
                <form class="formulario-contacto" id="formContacto">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
                    </div>

                    <div class="form-group">
                        <label for="asunto">Asunto</label>
                        <select id="asunto" name="asunto" required>
                            <option value="">Seleccione un asunto</option>
                            <?php foreach($formulario['asuntos'] as $key => $valor): ?>
                                <option value="<?php echo $key; ?>"><?php echo $valor; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="6" placeholder="Describa su consulta o reclamo con el mayor detalle posible..." required></textarea>
                    </div>

                    <button type="submit" class="btn-enviar">
                        <span class="btn-texto">Enviar Mensaje</span>
                        <span class="btn-cargando" style="display: none;">Enviando...</span>
                    </button>
                </form>
            </div>

            <!-- Informacion de Contacto -->
            <div class="info-container">
                <h2><?php echo $informacion['titulo']; ?></h2>
                
                <?php foreach($informacion['items'] as $item): ?>
                    <div class="info-card">
                        <div class="info-icono"><?php echo $item['icono']; ?></div>
                        <div class="info-contenido">
                            <h3><?php echo $item['titulo']; ?></h3>
                            <p><?php echo $item['descripcion']; ?></p>
                            <?php if(isset($item['telefono'])): ?>
                                <p><strong>Teléfono:</strong> <?php echo $item['telefono']; ?></p>
                            <?php endif; ?>
                            <?php if(isset($item['email'])): ?>
                                <p><strong>Email:</strong> <?php echo $item['email']; ?></p>
                            <?php endif; ?>
                            <?php if(isset($item['horario'])): ?>
                                <p><strong>Horario:</strong> <?php echo $item['horario']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Redes Sociales -->
                <div class="redes-sociales">
                    <h3><?php echo $redes['titulo']; ?></h3>
                    <div class="redes-lista">
                        <?php foreach($redes['items'] as $red): ?>
                            <a href="<?php echo $red['url']; ?>" class="red-social" target="_blank">
                                <span class="red-icono"><?php echo $red['icono']; ?></span>
                                <span class="red-nombre"><?php echo $red['nombre']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Incluimos Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Portada */
    .portada-contactos {
        position: relative;
        height: 400px;
        margin-bottom: 60px;
    }

    .portada-imagen {
        position: relative;
        height: 100%;
        overflow: hidden;
    }

    .portada-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.7);
    }

    .portada-contenido {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
        width: 100%;
        padding: 0 20px;
    }

    .portada-contenido h1 {
        font-size: 48px;
        margin-bottom: 15px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .portada-contenido p {
        font-size: 20px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Sucursales */
    .sucursales-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .sucursales-section h2 {
        text-align: center;
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 50px;
        font-weight: bold;
    }

    .sucursales-layout {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
        align-items: start;
    }

    .sucursales-lista {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sucursal-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .sucursal-card:hover, .sucursal-card.active {
        border-color: #1abc9c;
        transform: translateY(-5px);
    }

    .sucursal-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .sucursal-icono {
        font-size: 24px;
        margin-right: 15px;
    }

    .sucursal-header h3 {
        font-size: 20px;
        color: #2c3e50;
        margin: 0;
        font-weight: 600;
    }

    .sucursal-info p {
        margin: 8px 0;
        color: #7f8c8d;
        line-height: 1.5;
    }

    .stock-status {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }

    .stock-status:contains("disponible") {
        background: #d4edda;
        color: #155724;
    }

    .stock-status:contains("limitado") {
        background: #fff3cd;
        color: #856404;
    }

    /* Mapa */
    .mapa-container {
        position: sticky;
        top: 20px;
    }

    #map {
        height: 500px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .mapa-leyenda {
        margin-top: 15px;
        text-align: center;
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Estilos del popup del mapa */
    .leaflet-popup-content {
        min-width: 250px;
    }

    .popup-sucursal h3 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .popup-sucursal p {
        margin: 5px 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Contacto Section */
    .contacto-section {
        padding: 80px 0;
        background: white;
    }

    .contacto-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 60px;
        align-items: start;
    }

    /* Formulario */
    .formulario-container h2 {
        font-size: 32px;
        color: #2c3e50;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .formulario-contacto {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 15px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #1abc9c;
    }

    .btn-enviar {
        background: #1abc9c;
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }

    .btn-enviar:hover {
        background: #16a085;
    }

    .btn-enviar:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
    }

    /* Información de Contacto */
    .info-container h2 {
        font-size: 32px;
        color: #2c3e50;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .info-card {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .info-icono {
        font-size: 32px;
        flex-shrink: 0;
    }

    .info-contenido h3 {
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .info-contenido p {
        color: #7f8c8d;
        margin: 5px 0;
        line-height: 1.5;
    }

    /* Redes Sociales */
    .redes-sociales {
        margin-top: 40px;
    }

    .redes-sociales h3 {
        font-size: 20px;
        color: #2c3e50;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .redes-lista {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .red-social {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s;
    }

    .red-social:hover {
        background: #1abc9c;
        color: white;
        transform: translateY(-2px);
    }

    .red-icono {
        font-size: 18px;
    }

    .red-nombre {
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .sucursales-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .contacto-layout {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        #map {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .portada-contenido h1 {
            font-size: 36px;
        }

        .portada-contenido p {
            font-size: 18px;
        }

        .sucursales-section h2,
        .formulario-container h2,
        .info-container h2 {
            font-size: 28px;
        }

        .redes-lista {
            grid-template-columns: 1fr;
        }

        .info-card {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .portada-contactos {
            height: 300px;
        }

        .formulario-contacto {
            padding: 20px;
        }
    }
</style>

<script>
    // Inicializar mapa
    document.addEventListener('DOMContentLoaded', function() {
        // Coordenadas de La Paz
        const centroMapa = [-16.5000, -68.1500];
        
        // Inicializar mapa
        const map = L.map('map').setView(centroMapa, 13);

        // Agregar capa de tiles (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Datos de sucursales
        const sucursales = <?php echo json_encode($sucursales['items']); ?>;
        const marcadores = [];

        // Agregar marcadores para cada sucursal
        sucursales.forEach(sucursal => {
            const popupContent = `
                <div class="popup-sucursal">
                    <h3>${sucursal.nombre}</h3>
                    <p><strong>📍</strong> ${sucursal.direccion}</p>
                    <p><strong>📞</strong> ${sucursal.telefono}</p>
                    <p><strong>🕒</strong> ${sucursal.horario}</p>
                    <p><strong>📦</strong> ${sucursal.stock}</p>
                </div>
            `;

            const marcador = L.marker([sucursal.latitud, sucursal.longitud])
                .addTo(map)
                .bindPopup(popupContent)
                .on('click', function() {
                    // Resaltar la tarjeta correspondiente
                    document.querySelectorAll('.sucursal-card').forEach(card => {
                        card.classList.remove('active');
                    });
                    document.querySelector(`[data-sucursal-id="${sucursal.id}"]`).classList.add('active');
                });

            marcadores.push(marcador);
        });

        // Agrupar marcadores
        const group = new L.featureGroup(marcadores);
        map.fitBounds(group.getBounds().pad(0.1));

        // Interaccion con las tarjetas de sucursales
        document.querySelectorAll('.sucursal-card').forEach(card => {
            card.addEventListener('click', function() {
                const sucursalId = this.getAttribute('data-sucursal-id');
                const sucursal = sucursales.find(s => s.id == sucursalId);
                
                if (sucursal) {
                    // Remover clase active de todas las tarjetas
                    document.querySelectorAll('.sucursal-card').forEach(c => {
                        c.classList.remove('active');
                    });
                    
                    // Agregar clase active a la tarjeta clickeada
                    this.classList.add('active');
                    
                    // Centrar mapa en la sucursal y abrir popup
                    map.setView([sucursal.latitud, sucursal.longitud], 15);
                    
                    // Encontrar y abrir el marcador correspondiente
                    marcadores.forEach(marcador => {
                        const latLng = marcador.getLatLng();
                        if (latLng.lat === sucursal.latitud && latLng.lng === sucursal.longitud) {
                            marcador.openPopup();
                        }
                    });
                }
            });
        });

        // Manejo del formulario
        const formulario = document.getElementById('formContacto');
        formulario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnEnviar = this.querySelector('.btn-enviar');
            const btnTexto = this.querySelector('.btn-texto');
            const btnCargando = this.querySelector('.btn-cargando');
            
            // Mostrar estado de carga
            btnTexto.style.display = 'none';
            btnCargando.style.display = 'inline';
            btnEnviar.disabled = true;
            
            // Simular envío (en una aplicación real, aquí harías una petición AJAX)
            setTimeout(() => {
                alert('¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.');
                
                // Restaurar botón
                btnTexto.style.display = 'inline';
                btnCargando.style.display = 'none';
                btnEnviar.disabled = false;
                
                // Limpiar formulario
                formulario.reset();
            }, 2000);
        });

        // Estilos para el estado del stock
        document.querySelectorAll('.stock-status').forEach(span => {
            if (span.textContent.includes('disponible')) {
                span.style.background = '#d4edda';
                span.style.color = '#155724';
                span.style.padding = '2px 8px';
                span.style.borderRadius = '12px';
                span.style.fontSize = '12px';
                span.style.fontWeight = 'bold';
            } else if (span.textContent.includes('limitado')) {
                span.style.background = '#fff3cd';
                span.style.color = '#856404';
                span.style.padding = '2px 8px';
                span.style.borderRadius = '12px';
                span.style.fontSize = '12px';
                span.style.fontWeight = 'bold';
            }
        });
    });
</script>