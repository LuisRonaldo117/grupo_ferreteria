<?php
// Vista Infórmate - Estructura original con estilos del diseño guía
?>

<!-- Incluir el header -->

<!-- Portada -->
<section class="portada-informate">
    <div class="portada-imagen">
        <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Infórmate">
        <div class="portada-contenido">
            <h1><?php echo $portada['titulo']; ?></h1>
            <p><?php echo $portada['subtitulo']; ?></p>
        </div>
    </div>
</section>

<!-- Navegacion por pestañas -->
<section class="tabs-section">
    <div class="container">
        <div class="tabs-navegacion">
            <button class="tab-btn active" data-tab="articulos">
                <i class="fas fa-newspaper"></i>
                Artículos
            </button>
            <button class="tab-btn" data-tab="tutoriales">
                <i class="fas fa-video"></i>
                Tutoriales
            </button>
            <button class="tab-btn" data-tab="consejos">
                <i class="fas fa-lightbulb"></i>
                Consejos
            </button>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tabs-contenido">
            <!-- Pestaña Articulos -->
            <div id="tab-articulos" class="tab-panel active">
                <div class="panel-header">
                    <h2>Artículos Especializados</h2>
                    <p>Descubre artículos informativos sobre herramientas, técnicas y materiales</p>
                </div>
                <div class="articulos-grid">
                    <article class="articulo-card">
                        <div class="articulo-imagen">
                            <img src="https://www.mndelgolfo.com/blog/wp-content/uploads/2025/01/herramientas-electricas-que-no-deben-faltar-en-tu-hogar-1024x576.jpg" alt="Herramientas eléctricas">
                            <div class="articulo-etiqueta">Nuevo</div>
                        </div>
                        <div class="articulo-contenido">
                            <div class="articulo-meta">
                                <span class="badge-category">Herramientas</span>
                                <span class="articulo-fecha">15 Mar 2024</span>
                            </div>
                            <h3>Guía Completa de Herramientas Eléctricas Profesionales</h3>
                            <p>Aprende a elegir y utilizar las mejores herramientas eléctricas para tus proyectos de construcción y renovación.</p>
                            <a href="index.php?articulo=1" class="btn-leer-mas">
                                <i class="fas fa-book-reader me-1"></i>Leer Más
                            </a>
                        </div>
                    </article>
                    
                    <article class="articulo-card">
                        <div class="articulo-imagen">
                            <img src="https://www.construyendoseguro.com/wp-content/uploads/2025/02/materiales-para-construccion.jpg" alt="Materiales construcción">
                            <div class="articulo-etiqueta">Popular</div>
                        </div>
                        <div class="articulo-contenido">
                            <div class="articulo-meta">
                                <span class="badge-category">Materiales</span>
                                <span class="articulo-fecha">10 Mar 2024</span>
                            </div>
                            <h3>Materiales de Construcción: Calidad vs Precio</h3>
                            <p>Descubre cómo balancear calidad y presupuesto al seleccionar materiales para tu próximo proyecto.</p>
                            <a href="index.php?articulo=2" class="btn-leer-mas">
                                <i class="fas fa-book-reader me-1"></i>Leer Más
                            </a>
                        </div>
                    </article>
                    
                    <article class="articulo-card">
                        <div class="articulo-imagen">
                            <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Pinturas y acabados">
                            <div class="articulo-etiqueta">Destacado</div>
                        </div>
                        <div class="articulo-contenido">
                            <div class="articulo-meta">
                                <span class="badge-category">Acabados</span>
                                <span class="articulo-fecha">5 Mar 2024</span>
                            </div>
                            <h3>Tendencias en Pinturas y Acabados para 2024</h3>
                            <p>Explora las últimas tendencias en colores, texturas y técnicas de aplicación para darle vida a tus espacios.</p>
                            <a href="index.php?articulo=3" class="btn-leer-mas">
                                <i class="fas fa-book-reader me-1"></i>Leer Más
                            </a>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Pestaña Tutoriales -->
            <div id="tab-tutoriales" class="tab-panel">
                <div class="panel-header">
                    <h2>Tutoriales en Video</h2>
                    <p>Aprende con nuestros tutoriales en video paso a paso</p>
                </div>
                <div class="tutoriales-grid">
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://images.unsplash.com/photo-1621905252507-b35492cc74b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80" alt="Instalación cerámica">
                            <div class="play-button" onclick="abrirVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="duracion">15:30</div>
                        </div>
                        <div class="tutorial-contenido">
                            <h3>Instalación Perfecta de Cerámica</h3>
                            <p>Aprende las técnicas profesionales para instalar cerámica sin errores y con acabados perfectos.</p>
                            <button class="btn-ver-video" onclick="abrirVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')">
                                <i class="fas fa-play me-1"></i>Ver Tutorial
                            </button>
                        </div>
                    </div>
                    
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://images.unsplash.com/photo-1605106707843-e02b4f9c57bc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Electricidad básica">
                            <div class="play-button" onclick="abrirVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="duracion">22:15</div>
                        </div>
                        <div class="tutorial-contenido">
                            <h3>Instalaciones Eléctricas Seguras</h3>
                            <p>Guía completa para realizar instalaciones eléctricas residenciales con total seguridad.</p>
                            <button class="btn-ver-video" onclick="abrirVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')">
                                <i class="fas fa-play me-1"></i>Ver Tutorial
                            </button>
                        </div>
                    </div>
                    
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Carpintería básica">
                            <div class="play-button" onclick="abrirVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="duracion">18:45</div>
                        </div>
                        <div class="tutorial-contenido">
                            <h3>Carpintería para Principiantes</h3>
                            <p>Domina las técnicas básicas de carpintería y crea tus primeros proyectos con confianza.</p>
                            <button class="btn-ver-video" onclick="abrirVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ')">
                                <i class="fas fa-play me-1"></i>Ver Tutorial
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña Consejos -->
            <div id="tab-consejos" class="tab-panel">
                <div class="panel-header">
                    <h2>Consejos Prácticos</h2>
                    <p>Tips prácticos de nuestros expertos para mejores resultados</p>
                </div>
                <div class="consejos-grid">
                    <div class="consejo-categoria">
                        <div class="categoria-header">
                            <div class="categoria-icono">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3>Herramientas</h3>
                        </div>
                        <ul class="lista-consejos">
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Siempre mantén tus herramientas limpias y afiladas
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Usa herramientas adecuadas para cada tipo de material
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Almacena las herramientas en lugar seco y organizado
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Verifica el estado de las herramientas eléctricas antes de usar
                            </li>
                        </ul>
                    </div>
                    
                    <div class="consejo-categoria">
                        <div class="categoria-header">
                            <div class="categoria-icono">
                                <i class="fas fa-paint-roller"></i>
                            </div>
                            <h3>Pintura y Acabados</h3>
                        </div>
                        <ul class="lista-consejos">
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Prepara bien la superficie antes de pintar
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Usa cinta de enmascarar para bordes perfectos
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Aplica pintura en capas delgadas y uniformes
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Mantén una ventilación adecuada durante el trabajo
                            </li>
                        </ul>
                    </div>
                    
                    <div class="consejo-categoria">
                        <div class="categoria-header">
                            <div class="categoria-icono">
                                <i class="fas fa-home"></i>
                            </div>
                            <h3>Construcción</h3>
                        </div>
                        <ul class="lista-consejos">
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Siempre mide dos veces y corta una vez
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Usa equipo de protección personal en todo momento
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Planifica tu proyecto antes de comenzar
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                Consulta con expertos para proyectos complejos
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para videos -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Tutorial en Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="videoContainer" class="ratio ratio-16x9">
                    <!-- El video se cargará aquí -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reset para mantener consistencia con el header */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #ffffff;
        color: #333;
        line-height: 1.6;
        padding-top: 0 !important;
    }

    :root {
        --primary-blue: #1a237e;
        --accent-blue: #2196F3;
        --dark-blue: #1976D2;
        --light-blue: #e3f2fd;
        --success-green: #4CAF50;
        --warning-orange: #ff9800;
        --shadow-sm: 0 4px 12px rgba(33, 150, 243, 0.1);
        --shadow-md: 0 8px 25px rgba(33, 150, 243, 0.15);
        --shadow-lg: 0 12px 35px rgba(33, 150, 243, 0.2);
    }
    
    /* Portada */
    .portada-informate {
        position: relative;
        height: 400px;
        margin-bottom: 40px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-top: 20px;
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
        transition: transform 0.5s ease;
    }

    .portada-imagen:hover img {
        transform: scale(1.05);
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
        font-size: 3.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
    }

    .portada-contenido p {
        font-size: 1.3rem;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 500;
    }

    /* Navegación por pestañas */
    .tabs-section {
        padding: 40px 0 80px;
        background: #ffffff;
    }

    .tabs-navegacion {
        display: flex;
        justify-content: center;
        margin-bottom: 50px;
        background: #ffffff;
        padding: 12px;
        border-radius: 50px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        box-shadow: var(--shadow-md);
        border: 2px solid #e3f2fd;
        position: relative;
        overflow: hidden;
    }

    .tabs-navegacion:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-blue), var(--dark-blue));
    }

    .tab-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        border: none;
        background: transparent;
        border-radius: 40px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        font-weight: 600;
        color: #546e7a;
        flex: 1;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .tab-btn:hover {
        color: var(--primary-blue);
        background: rgba(33, 150, 243, 0.1);
        transform: translateY(-2px);
    }

    .tab-btn.active {
        background: linear-gradient(135deg, var(--accent-blue), var(--dark-blue));
        color: white;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.4);
        transform: translateY(-2px);
    }

    .tab-btn i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .tab-btn.active i {
        transform: scale(1.1);
    }

    /* Contenido de pestañas */
    .tabs-contenido {
        position: relative;
    }

    .tab-panel {
        display: none;
        animation: fadeInUp 0.5s ease;
    }

    .tab-panel.active {
        display: block;
    }

    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .panel-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .panel-header h2 {
        font-size: 2.8rem;
        color: var(--primary-blue);
        margin-bottom: 20px;
        font-weight: 700;
        position: relative;
        padding-bottom: 20px;
    }

    .panel-header h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-blue), var(--dark-blue));
        border-radius: 2px;
    }

    .panel-header p {
        font-size: 1.2rem;
        color: #546e7a;
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 500;
    }

    /* Artículos */
    .articulos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 30px;
    }

    .articulo-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.4s ease;
        border: 1px solid #e3f2fd;
        position: relative;
    }

    .articulo-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent-blue);
    }

    .articulo-imagen {
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .articulo-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .articulo-card:hover .articulo-imagen img {
        transform: scale(1.1);
    }

    .articulo-etiqueta {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, var(--warning-orange), #f57c00);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .articulo-contenido {
        padding: 30px;
    }

    .articulo-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .badge-category {
        background: linear-gradient(135deg, var(--accent-blue), var(--dark-blue));
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
    }

    .articulo-fecha {
        color: #78909c;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .articulo-contenido h3 {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 15px;
        line-height: 1.4;
        font-weight: 700;
    }

    .articulo-contenido p {
        color: #546e7a;
        line-height: 1.6;
        margin-bottom: 25px;
        font-size: 1rem;
    }

    .btn-leer-mas {
        background: linear-gradient(135deg, var(--accent-blue), var(--dark-blue));
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        cursor: pointer;
    }

    .btn-leer-mas:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Tutoriales */
    .tutoriales-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 30px;
    }

    .tutorial-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.4s ease;
        border: 1px solid #e3f2fd;
        position: relative;
    }

    .tutorial-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent-blue);
    }

    .tutorial-video {
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .tutorial-video img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .tutorial-card:hover .tutorial-video img {
        transform: scale(1.1);
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, rgba(33, 150, 243, 0.9), rgba(25, 118, 210, 0.9));
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        backdrop-filter: blur(5px);
    }

    .play-button:hover {
        background: linear-gradient(135deg, rgba(33, 150, 243, 1), rgba(25, 118, 210, 1));
        transform: translate(-50%, -50%) scale(1.1);
        box-shadow: 0 12px 30px rgba(0,0,0,0.4);
    }

    .play-button i {
        color: white;
        font-size: 28px;
        margin-left: 4px;
    }

    .duracion {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        backdrop-filter: blur(5px);
    }

    .tutorial-contenido {
        padding: 30px;
    }

    .tutorial-contenido h3 {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 15px;
        line-height: 1.4;
        font-weight: 700;
    }

    .tutorial-contenido p {
        color: #546e7a;
        line-height: 1.6;
        margin-bottom: 25px;
        font-size: 1rem;
    }

    .btn-ver-video {
        background: linear-gradient(135deg, var(--success-green), #2E7D32);
        color: white;
        padding: 14px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-weight: 600;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }

    .btn-ver-video:hover {
        background: linear-gradient(135deg, #43A047, #1B5E20);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    /* Consejos */
    .consejos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .consejo-categoria {
        background: white;
        padding: 35px;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid #e3f2fd;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .consejo-categoria:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent-blue);
    }

    .consejo-categoria:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-blue), var(--dark-blue));
    }

    .categoria-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e3f2fd;
    }

    .categoria-icono {
        font-size: 42px;
        margin-right: 20px;
        color: var(--accent-blue);
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
    }

    .categoria-header h3 {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin: 0;
        font-weight: 700;
    }

    .lista-consejos {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .lista-consejos li {
        padding: 15px 0;
        border-bottom: 1px solid #f5f5f5;
        color: #546e7a;
        line-height: 1.5;
        font-size: 1rem;
        display: flex;
        align-items: flex-start;
        transition: all 0.3s ease;
    }

    .lista-consejos li:hover {
        background: #f8fdff;
        padding-left: 10px;
        border-radius: 8px;
    }

    .lista-consejos li:last-child {
        border-bottom: none;
    }

    .lista-consejos li i {
        margin-top: 3px;
        color: var(--success-green);
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .portada-contenido h1 {
            font-size: 2.5rem;
        }

        .portada-contenido p {
            font-size: 1.1rem;
        }

        .tabs-navegacion {
            flex-direction: column;
            border-radius: 20px;
            gap: 8px;
            max-width: 100%;
        }

        .tab-btn {
            border-radius: 15px;
            padding: 15px 20px;
        }

        .panel-header h2 {
            font-size: 2.2rem;
        }

        .articulos-grid,
        .tutoriales-grid,
        .consejos-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .portada-informate {
            height: 300px;
            margin-bottom: 30px;
        }
        
        .categoria-header {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .categoria-icono {
            margin-right: 0;
        }
    }

    @media (max-width: 480px) {
        .portada-informate {
            height: 250px;
        }

        .portada-contenido h1 {
            font-size: 2rem;
        }

        .portada-contenido p {
            font-size: 1rem;
        }

        .articulo-meta {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }

        .panel-header h2 {
            font-size: 1.8rem;
        }

        .articulo-contenido,
        .tutorial-contenido {
            padding: 20px;
        }
        
        .consejo-categoria {
            padding: 25px;
        }
    }
</style>

<script>
    // Navegacion por pestañas
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Remover clase active de todos los botones y paneles
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanels.forEach(p => p.classList.remove('active'));
                
                // Agregar clase active al botón clickeado y panel correspondiente
                this.classList.add('active');
                document.getElementById(`tab-${targetTab}`).classList.add('active');
            });
        });
    });

    // Funciones para el modal de videos
    function abrirVideo(url) {
        const modal = new bootstrap.Modal(document.getElementById('videoModal'));
        const container = document.getElementById('videoContainer');
        
        // Extraer id del video de youtube
        const videoId = url.split('v=')[1]?.split('&')[0] || url.split('/').pop();
        
        container.innerHTML = `
            <iframe 
                src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        `;
        
        modal.show();
    }

    // Limpiar el video cuando se cierra el modal
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        const container = document.getElementById('videoContainer');
        container.innerHTML = '';
    });
</script>