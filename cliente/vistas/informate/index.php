<?php
// Vista Infórmate - Estructura original con estilos del diseño guía
?>

<!-- Portada -->
<section class="portada-informate">
    <div class="portada-imagen">
        <img src="<?php echo $portada['imagen']; ?>" alt="Infórmate">
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
                    <h2><?php echo $articulos['titulo']; ?></h2>
                    <p>Descubre artículos informativos sobre herramientas, técnicas y materiales</p>
                </div>
                <div class="articulos-grid">
                    <?php foreach($articulos['items'] as $articulo): ?>
                        <article class="articulo-card">
                            <div class="articulo-imagen">
                                <img src="<?php echo $articulo['imagen']; ?>" alt="<?php echo $articulo['titulo']; ?>">
                                <div class="articulo-etiqueta"><?php echo $articulo['etiqueta']; ?></div>
                            </div>
                            <div class="articulo-contenido">
                                <div class="articulo-meta">
                                    <span class="badge-category"><?php echo $articulo['categoria']; ?></span>
                                    <span class="articulo-fecha"><?php echo $articulo['fecha']; ?></span>
                                </div>
                                <h3><?php echo $articulo['titulo']; ?></h3>
                                <p><?php echo $articulo['descripcion']; ?></p>
                                <a href="index.php?articulo=<?php echo $articulo['id']; ?>" class="btn-leer-mas">
                                    <i class="fas fa-book-reader me-1"></i>Leer Más
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pestaña Tutoriales -->
            <div id="tab-tutoriales" class="tab-panel">
                <div class="panel-header">
                    <h2><?php echo $tutoriales['titulo']; ?></h2>
                    <p>Aprende con nuestros tutoriales en video paso a paso</p>
                </div>
                <div class="tutoriales-grid">
                    <?php foreach($tutoriales['items'] as $tutorial): ?>
                        <div class="tutorial-card">
                            <div class="tutorial-video">
                                <img src="<?php echo $tutorial['imagen']; ?>" alt="<?php echo $tutorial['titulo']; ?>">
                                <div class="play-button" onclick="abrirVideo('<?php echo $tutorial['video_url']; ?>')">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="duracion"><?php echo $tutorial['duracion']; ?></div>
                            </div>
                            <div class="tutorial-contenido">
                                <h3><?php echo $tutorial['titulo']; ?></h3>
                                <p><?php echo $tutorial['descripcion']; ?></p>
                                <button class="btn-ver-video" onclick="abrirVideo('<?php echo $tutorial['video_url']; ?>')">
                                    <i class="fas fa-play me-1"></i>Ver Tutorial
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pestaña Consejos -->
            <div id="tab-consejos" class="tab-panel">
                <div class="panel-header">
                    <h2><?php echo $consejos['titulo']; ?></h2>
                    <p>Tips prácticos de nuestros expertos para mejores resultados</p>
                </div>
                <div class="consejos-grid">
                    <?php foreach($consejos['items'] as $categoria): ?>
                        <div class="consejo-categoria">
                            <div class="categoria-header">
                                <div class="categoria-icono">
                                    <?php echo $categoria['icono']; ?>
                                </div>
                                <h3><?php echo $categoria['titulo']; ?></h3>
                            </div>
                            <ul class="lista-consejos">
                                <?php foreach($categoria['consejos'] as $consejo): ?>
                                    <li>
                                        <i class="fas fa-check text-success me-2"></i>
                                        <?php echo $consejo; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
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
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #d32f2f;
        --accent-color: #007bff;
        --light-bg: #f8f9fa;
        --text-muted: #6c757d;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    /* Portada */
    .portada-informate {
        position: relative;
        height: 400px;
        margin-bottom: 40px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
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
        margin-bottom: 15px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .portada-contenido p {
        font-size: 1.25rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Navegación por pestañas */
    .tabs-section {
        padding: 40px 0 80px;
        background: white;
    }

    .tabs-navegacion {
        display: flex;
        justify-content: center;
        margin-bottom: 50px;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 50px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        box-shadow: var(--shadow-sm);
        border: 1px solid #e9ecef;
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
        font-weight: 500;
        color: var(--text-muted);
        flex: 1;
        justify-content: center;
    }

    .tab-btn:hover {
        color: var(--primary-color);
        background: rgba(0, 123, 255, 0.1);
    }

    .tab-btn.active {
        background: linear-gradient(90deg, var(--accent-color), #0056b3);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .tab-btn i {
        font-size: 18px;
    }

    /* Contenido de pestañas */
    .tabs-contenido {
        position: relative;
    }

    .tab-panel {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .tab-panel.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .panel-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .panel-header h2 {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 15px;
        font-weight: 700;
        position: relative;
        padding-bottom: 15px;
    }

    .panel-header h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    .panel-header p {
        font-size: 1.1rem;
        color: var(--text-muted);
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Artículos */
    .articulos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .articulo-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .articulo-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .articulo-imagen {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .articulo-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .articulo-card:hover .articulo-imagen img {
        transform: scale(1.05);
    }

    .articulo-etiqueta {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--secondary-color);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .articulo-contenido {
        padding: 25px;
    }

    .articulo-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .badge-category {
        background: linear-gradient(90deg, var(--accent-color), #0056b3);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .articulo-fecha {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .articulo-contenido h3 {
        font-size: 1.4rem;
        color: var(--primary-color);
        margin-bottom: 15px;
        line-height: 1.4;
        font-weight: 600;
    }

    .articulo-contenido p {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 0.95rem;
    }

    .btn-leer-mas {
        background: linear-gradient(90deg, var(--accent-color), #0056b3);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 14px;
    }

    .btn-leer-mas:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        color: white;
    }

    /* Tutoriales */
    .tutoriales-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .tutorial-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .tutorial-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .tutorial-video {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .tutorial-video img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .tutorial-card:hover .tutorial-video img {
        transform: scale(1.05);
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(211, 47, 47, 0.9);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    .play-button:hover {
        background: rgba(211, 47, 47, 1);
        transform: translate(-50%, -50%) scale(1.1);
    }

    .play-button i {
        color: white;
        font-size: 24px;
        margin-left: 3px;
    }

    .duracion {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .tutorial-contenido {
        padding: 25px;
    }

    .tutorial-contenido h3 {
        font-size: 1.4rem;
        color: var(--primary-color);
        margin-bottom: 15px;
        line-height: 1.4;
        font-weight: 600;
    }

    .tutorial-contenido p {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 0.95rem;
    }

    .btn-ver-video {
        background: linear-gradient(90deg, var(--secondary-color), #b71c1c);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-ver-video:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
    }

    /* Consejos */
    .consejos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
    }

    .consejo-categoria {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .consejo-categoria:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .categoria-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }

    .categoria-icono {
        font-size: 40px;
        margin-right: 15px;
        color: var(--accent-color);
    }

    .categoria-header h3 {
        font-size: 1.4rem;
        color: var(--primary-color);
        margin: 0;
        font-weight: 600;
    }

    .lista-consejos {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .lista-consejos li {
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
        color: var(--text-muted);
        line-height: 1.5;
        font-size: 0.95rem;
        display: flex;
        align-items: flex-start;
    }

    .lista-consejos li:last-child {
        border-bottom: none;
    }

    .lista-consejos li i {
        margin-top: 3px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .portada-contenido h1 {
            font-size: 2.2rem;
        }

        .portada-contenido p {
            font-size: 1.1rem;
        }

        .tabs-navegacion {
            flex-direction: column;
            border-radius: 20px;
            gap: 5px;
            max-width: 100%;
        }

        .tab-btn {
            border-radius: 15px;
            padding: 12px 20px;
        }

        .panel-header h2 {
            font-size: 2rem;
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
    }

    @media (max-width: 480px) {
        .portada-informate {
            height: 250px;
        }

        .portada-contenido h1 {
            font-size: 1.8rem;
        }

        .portada-contenido p {
            font-size: 1rem;
        }

        .articulo-meta {
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }

        .categoria-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .categoria-icono {
            margin-right: 0;
        }

        .panel-header h2 {
            font-size: 1.6rem;
        }

        .articulo-contenido,
        .tutorial-contenido {
            padding: 20px;
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