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
                <span class="tab-icon">📚</span>
                Artículos
            </button>
            <button class="tab-btn" data-tab="tutoriales">
                <span class="tab-icon">🎥</span>
                Tutoriales
            </button>
            <button class="tab-btn" data-tab="consejos">
                <span class="tab-icon">💡</span>
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
                                    <span class="articulo-categoria"><?php echo $articulo['categoria']; ?></span>
                                    <span class="articulo-fecha"><?php echo $articulo['fecha']; ?></span>
                                </div>
                                <h3><?php echo $articulo['titulo']; ?></h3>
                                <p><?php echo $articulo['descripcion']; ?></p>
                                <a href="index.php?articulo=<?php echo $articulo['id']; ?>" class="btn-leer-mas">Leer Más</a>
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
                                    <span>▶</span>
                                </div>
                                <div class="duracion"><?php echo $tutorial['duracion']; ?></div>
                            </div>
                            <div class="tutorial-contenido">
                                <h3><?php echo $tutorial['titulo']; ?></h3>
                                <p><?php echo $tutorial['descripcion']; ?></p>
                                <button class="btn-ver-video" onclick="abrirVideo('<?php echo $tutorial['video_url']; ?>')">
                                    Ver Tutorial
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
                                <div class="categoria-icono"><?php echo $categoria['icono']; ?></div>
                                <h3><?php echo $categoria['titulo']; ?></h3>
                            </div>
                            <ul class="lista-consejos">
                                <?php foreach($categoria['consejos'] as $consejo): ?>
                                    <li><?php echo $consejo; ?></li>
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
<div id="videoModal" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarVideo()">&times;</span>
        <div id="videoContainer"></div>
    </div>
</div>

<style>
    /* Portada */
    .portada-informate {
        position: relative;
        height: 400px;
        margin-bottom: 40px;
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
        color: #7f8c8d;
        flex: 1;
        justify-content: center;
    }

    .tab-btn:hover {
        color: #2c3e50;
        background: rgba(26, 188, 156, 0.1);
    }

    .tab-btn.active {
        background: #1abc9c;
        color: white;
        box-shadow: 0 4px 15px rgba(26, 188, 156, 0.3);
    }

    .tab-icon {
        font-size: 20px;
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
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .panel-header p {
        font-size: 18px;
        color: #7f8c8d;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Artículos */
    .articulos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .articulo-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        border: 1px solid #f1f2f6;
    }

    .articulo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .articulo-imagen {
        position: relative;
        height: 200px;
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
        background: #1abc9c;
        color: white;
        padding: 5px 15px;
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
        margin-bottom: 15px;
        font-size: 14px;
    }

    .articulo-categoria {
        color: #1abc9c;
        font-weight: bold;
    }

    .articulo-fecha {
        color: #7f8c8d;
    }

    .articulo-contenido h3 {
        font-size: 20px;
        color: #2c3e50;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .articulo-contenido p {
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .btn-leer-mas {
        background: #3498db;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s;
        font-weight: 500;
    }

    .btn-leer-mas:hover {
        background: #2980b9;
    }

    /* Tutoriales */
    .tutoriales-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .tutorial-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        border: 1px solid #f1f2f6;
    }

    .tutorial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .tutorial-video {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .tutorial-video img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(26, 188, 156, 0.9);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .play-button:hover {
        background: rgba(26, 188, 156, 1);
        transform: translate(-50%, -50%) scale(1.1);
    }

    .play-button span {
        color: white;
        font-size: 20px;
        margin-left: 3px;
    }

    .duracion {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
    }

    .tutorial-contenido {
        padding: 25px;
    }

    .tutorial-contenido h3 {
        font-size: 20px;
        color: #2c3e50;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .tutorial-contenido p {
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .btn-ver-video {
        background: #e74c3c;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
        font-weight: 500;
    }

    .btn-ver-video:hover {
        background: #c0392b;
    }

    /* Consejos */
    .consejos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .consejo-categoria {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border: 1px solid #f1f2f6;
        transition: transform 0.3s ease;
    }

    .consejo-categoria:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .categoria-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }

    .categoria-icono {
        font-size: 40px;
        margin-right: 15px;
    }

    .categoria-header h3 {
        font-size: 20px;
        color: #2c3e50;
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
        position: relative;
        padding-left: 30px;
        color: #7f8c8d;
        line-height: 1.5;
    }

    .lista-consejos li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #1abc9c;
        font-weight: bold;
        font-size: 16px;
    }

    .lista-consejos li:last-child {
        border-bottom: none;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
    }

    .modal-contenido {
        position: relative;
        margin: 5% auto;
        width: 80%;
        max-width: 800px;
    }

    .cerrar {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 35px;
        font-weight: bold;
        cursor: pointer;
    }

    .cerrar:hover {
        color: #1abc9c;
    }

    #videoContainer {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        height: 0;
    }

    #videoContainer iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .portada-contenido h1 {
            font-size: 36px;
        }

        .portada-contenido p {
            font-size: 18px;
        }

        .tabs-navegacion {
            flex-direction: column;
            border-radius: 20px;
            gap: 5px;
        }

        .tab-btn {
            border-radius: 15px;
        }

        .panel-header h2 {
            font-size: 28px;
        }

        .articulos-grid,
        .tutoriales-grid,
        .consejos-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .modal-contenido {
            width: 95%;
            margin: 10% auto;
        }
    }

    @media (max-width: 480px) {
        .portada-informate {
            height: 300px;
        }

        .articulo-meta {
            flex-direction: column;
            gap: 5px;
        }

        .categoria-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .categoria-icono {
            margin-right: 0;
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
        const modal = document.getElementById('videoModal');
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
        
        modal.style.display = 'block';
    }

    function cerrarVideo() {
        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
        
        modal.style.display = 'none';
        container.innerHTML = '';
    }

    // Cerrar modal al hacer click fuera
    window.onclick = function(event) {
        const modal = document.getElementById('videoModal');
        if (event.target == modal) {
            cerrarVideo();
        }
    }

    // Cerrar con tecla
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarVideo();
        }
    });
</script>