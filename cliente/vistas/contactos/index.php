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
                            <div class="sucursal-icono"><i class="fas fa-store"></i></div>
                            <h3><?php echo $sucursal['nombre']; ?></h3>
                        </div>
                        <div class="sucursal-info">
                            <p><i class="fas fa-map-marker-alt text-primary me-2"></i> <strong>Dirección:</strong> <?php echo $sucursal['direccion']; ?></p>
                            <p><i class="fas fa-phone text-primary me-2"></i> <strong>Teléfono:</strong> <?php echo $sucursal['telefono']; ?></p>
                            <p><i class="fas fa-clock text-primary me-2"></i> <strong>Horario:</strong> <?php echo $sucursal['horario']; ?></p>
                            <p><i class="fas fa-boxes text-primary me-2"></i> <strong>Stock:</strong> 
                                <span class="stock-status <?php 
                                    if(strpos($sucursal['stock'], 'disponible') !== false) echo 'stock-available';
                                    elseif(strpos($sucursal['stock'], 'limitado') !== false) echo 'stock-low';
                                    else echo 'stock-out';
                                ?>">
                                    <i class="fas <?php 
                                        if(strpos($sucursal['stock'], 'disponible') !== false) echo 'fa-check-circle';
                                        elseif(strpos($sucursal['stock'], 'limitado') !== false) echo 'fa-exclamation-circle';
                                        else echo 'fa-times-circle';
                                    ?> me-1"></i> 
                                    <?php echo $sucursal['stock']; ?>
                                </span>
                            </p>
                            <button class="btn btn-sm btn-primary mt-2" onclick="focusOnMap(<?php echo $sucursal['id']; ?>)">
                                <i class="fas fa-search-location me-1"></i> Ver en mapa
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mapa -->
            <div class="mapa-container">
                <div id="map"></div>
                <div class="mapa-leyenda">
                    <p><i class="fas fa-map-marker-alt me-1"></i> Haz click en los marcadores para más información</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formulario e Informacion -->
<section class="contacto-section">
    <div class="container">
        <div class="contacto-layout">
            <!-- Formulario  -->
            <div class="formulario-container">
                <h2 class="section-title">Envíanos un Mensaje</h2>
                <form class="formulario-contacto" id="formContacto">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                value="<?php echo isset($usuario) ? htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) : ''; ?>" 
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?php echo isset($usuario) ? htmlspecialchars($usuario['correo']) : ''; ?>" 
                                readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <select class="form-select" id="asunto" name="asunto" required>
                            <option value="" selected disabled>Seleccione un asunto</option>
                            <?php foreach($formulario['asuntos'] as $key => $valor): ?>
                                <option value="<?php echo $key; ?>"><?php echo $valor; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="6" 
                                placeholder="Describa su consulta o reclamo con el mayor detalle posible..." 
                                required></textarea>
                    </div>

                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-2"></i> Enviar Mensaje
                    </button>
                </form>
            </div>

            <!-- Informacion de Contacto -->
            <div class="info-container">
                <h2 class="section-title">Información de Contacto</h2>
                
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="contact-text">
                        <h5>Atención al Cliente</h5>
                        <p>Estamos disponibles para atender tus consultas y solicitudes.</p>
                        <p><strong>Teléfono:</strong> +591 77584652</p>
                        <p><strong>Email:</strong> contacto@ferreteria.com</p>
                    </div>
                </div>
                
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="contact-text">
                        <h5>Soporte Técnico</h5>
                        <p>Asesoría técnica especializada para tus proyectos.</p>
                        <p><strong>Teléfono:</strong> +591 78632451</p>
                        <p><strong>Email:</strong> soporte@ferreteria.com</p>
                    </div>
                </div>
                
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="contact-text">
                        <h5>Oficina Central</h5>
                        <p>Av. 16 de Julio #789, El Prado, La Paz</p>
                        <p><strong>Horario:</strong> Lunes a Sábado: 8:30 - 19:30</p>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="mt-4">
                    <h5 class="mb-3"><i class="fas fa-share-alt text-primary me-2"></i> Síguenos en Redes</h5>
                    <div class="social-links">
                        <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="whatsapp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Font Awesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #d32f2f;
        --accent-color: #007bff;
        --light-bg: #f8f9fa;
        --dark-blue: #1a2a3a;
        --gold-accent: #FFD700;
    }
    
    /* Portada */
    .portada-contactos {
        position: relative;
        height: 400px;
        margin-bottom: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                    url('https://images.unsplash.com/photo-1605153864431-a2795a1b2f95?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        color: white;
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
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        font-family: 'Poppins', sans-serif;
    }

    .portada-contenido p {
        font-size: 1.2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Sucursales */
    .sucursales-section {
        padding: 80px 0;
        background: white;
    }

    .sucursales-section h2 {
        text-align: center;
        font-size: 2rem;
        color: var(--dark-blue);
        margin-bottom: 50px;
        font-weight: 700;
        position: relative;
        padding-bottom: 15px;
        font-family: 'Poppins', sans-serif;
    }
    
    .sucursales-section h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: var(--gold-accent);
        border-radius: 2px;
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
        gap: 25px;
    }

    .sucursal-card {
        background: white;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,.08);
        transition: all 0.4s ease;
        cursor: pointer;
        border: none;
        overflow: hidden;
        margin-bottom: 0;
    }

    .sucursal-card:hover, .sucursal-card.active {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,.15);
    }

    .sucursal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-blue) 100%);
        color: white;
        padding: 20px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    
    .sucursal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(45deg);
    }

    .sucursal-icono {
        font-size: 1.5rem;
        margin-right: 15px;
        color: white;
    }

    .sucursal-header h3 {
        font-size: 1.25rem;
        color: white;
        margin: 0;
        font-weight: 600;
    }

    .sucursal-info {
        padding: 25px;
        background: white;
    }

    .sucursal-info p {
        margin: 8px 0;
        color: #555;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
    }

    .sucursal-info .text-primary {
        color: var(--accent-color) !important;
        margin-right: 8px;
        width: 16px;
        text-align: center;
    }

    .stock-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 5px;
    }
    
    .stock-available {
        background-color: #d4edda;
        color: #155724;
    }

    .stock-low {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .stock-out {
        background-color: #f8d7da;
        color: #721c24;
    }

    .btn-primary {
        background: var(--accent-color);
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    
    .btn-primary:hover {
        background: #0069d9;
        transform: translateY(-2px);
    }

    /* Mapa */
    .mapa-container {
        position: sticky;
        top: 20px;
    }

    #map {
        height: 500px;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0,0,0,.1);
        border: 1px solid rgba(0,0,0,.1);
    }

    .mapa-leyenda {
        margin-top: 15px;
        text-align: center;
        color: #7f8c8d;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    /* Estilos del popup del mapa */
    .leaflet-popup-content {
        min-width: 250px;
    }

    .popup-sucursal h3 {
        color: var(--dark-blue);
        margin-bottom: 10px;
        font-size: 18px;
        font-weight: 600;
    }

    .popup-sucursal p {
        margin: 5px 0;
        color: #555;
        font-size: 14px;
    }

    /* Contacto Section */
    .contacto-section {
        padding: 80px 0;
        background: var(--light-bg);
    }

    .contacto-layout {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 40px;
        align-items: start;
    }

    /* Formulario */
    .formulario-container {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
        border-top: 4px solid var(--accent-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .formulario-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,.15);
    }

    .section-title {
        color: var(--dark-blue);
        font-weight: 700;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: var(--gold-accent);
        border-radius: 2px;
    }

    .formulario-contacto {
        background: none;
        padding: 0;
        border-radius: 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: 8px;
        font-family: 'Poppins', sans-serif;
    }

    .form-control, .form-select {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--accent-color) 0%, #0069d9 100%);
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 100%;
        margin-top: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    /* Información de Contacto */
    .info-container {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 24px rgba(26, 43, 73, 0.1);
        border-top: 4px solid var(--accent-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,.15);
    }

    /* Iconos de contacto */
    .contact-icon {
        font-size: 1.8rem;
        color: var(--accent-color);
        margin-right: 15px;
        width: 50px;
        height: 50px;
        background: rgba(0, 123, 255, 0.1);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .contact-info:hover .contact-icon {
        background: var(--accent-color);
        color: white;
        transform: scale(1.1);
    }
    
    .contact-info {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        transition: all 0.3s ease;
        padding: 10px;
        border-radius: 8px;
    }
    
    .contact-info:hover {
        background: rgba(0,0,0,0.03);
    }
    
    .contact-text h5 {
        font-weight: 600;
        color: var(--dark-blue);
        margin-bottom: 5px;
        font-family: 'Poppins', sans-serif;
    }
    
    .contact-text p {
        margin-bottom: 0;
        color: #555;
        font-size: 0.9rem;
    }

    /* Redes sociales */
    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 10px;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .social-links a:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    
    .facebook {
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
    }
    
    .twitter {
        background: linear-gradient(135deg, #1da1f2 0%, #0d8ecf 100%);
    }
    
    .instagram {
        background: linear-gradient(135deg, #405de6 0%, #5851db 20%, #833ab4 40%, #c13584 60%, #e1306c 80%, #fd1d1d 100%);
    }
    
    .whatsapp {
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .sucursales-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .contacto-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        #map {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .portada-contenido h1 {
            font-size: 2.2rem;
        }

        .portada-contenido p {
            font-size: 1rem;
        }

        .sucursales-section h2,
        .section-title {
            font-size: 1.6rem;
        }
        
        .formulario-container,
        .info-container {
            padding: 25px;
        }
    }

    @media (max-width: 480px) {
        .portada-contactos {
            height: 300px;
        }
        
        .sucursal-info p {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .sucursal-info .text-primary {
            margin-bottom: 5px;
        }
        
        .formulario-container,
        .info-container {
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

        // Agregar capa (OpenStreetMap)
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
                    <h3><i class="fas fa-store me-1"></i> ${sucursal.nombre}</h3>
                    <p><i class="fas fa-map-marker-alt text-primary me-2"></i> ${sucursal.direccion}</p>
                    <p><i class="fas fa-phone text-primary me-2"></i> ${sucursal.telefono}</p>
                    <p><i class="fas fa-clock text-primary me-2"></i> ${sucursal.horario}</p>
                    <p><i class="fas fa-boxes text-primary me-2"></i> ${sucursal.stock}</p>
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

        // Función para centrar el mapa en una sucursal específica
        window.focusOnMap = function(branchId) {
            const sucursal = sucursales.find(s => s.id == branchId);
            if (sucursal) {
                // Remover clase active de todas las tarjetas
                document.querySelectorAll('.sucursal-card').forEach(c => {
                    c.classList.remove('active');
                });
                
                // Agregar clase active a la tarjeta correspondiente
                document.querySelector(`[data-sucursal-id="${branchId}"]`).classList.add('active');
                
                // Centrar mapa en la sucursal
                map.setView([sucursal.latitud, sucursal.longitud], 15);
                
                // Encontrar y abrir el marcador correspondiente
                marcadores.forEach(marcador => {
                    const latLng = marcador.getLatLng();
                    if (latLng.lat === sucursal.latitud && latLng.lng === sucursal.longitud) {
                        marcador.openPopup();
                    }
                });
            }
            return false;
        };

        // Manejo del formulario
        const formulario = document.getElementById('formContacto');
        formulario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnEnviar = this.querySelector('.btn-submit');
            
            // Mostrar estado de carga
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enviando...';
            
            // Enviar datos via AJAX
            const formData = new FormData(this);
            
            fetch('index.php?c=contactos&a=enviarMensaje', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(data.mensaje, 'success');
                    
                    // Limpiar solo el asunto y el mensaje, mantener nombre y email
                    document.getElementById('asunto').value = '';
                    document.getElementById('mensaje').value = '';
                    
                } else {
                    mostrarNotificacion(data.mensaje, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión. Por favor, intente nuevamente.', 'error');
            })
            .finally(() => {
                // Restaurar botón
                btnEnviar.disabled = false;
                btnEnviar.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Enviar Mensaje';
            });
        });
    });

    // Función para mostrar notificaciones
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const notificacion = document.createElement('div');
        const backgroundColor = tipo === 'error' ? '#e74c3c' : '#1abc9c';
        
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${backgroundColor};
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 400px;
            font-weight: 500;
        `;
        notificacion.textContent = mensaje;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notificacion.parentNode) {
                    document.body.removeChild(notificacion);
                }
            }, 300);
        }, 4000);
    }

    // Agregar estilos para las animaciones si no existen
    if (!document.querySelector('#notificacion-styles')) {
        const style = document.createElement('style');
        style.id = 'notificacion-styles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>