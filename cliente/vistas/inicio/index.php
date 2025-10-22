<!-- Carrusel -->
<div class="carrusel-container">
    <div class="carrusel" id="carrusel">
        <?php foreach($imagenesCarrusel as $index => $imagen): ?>
            <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $imagen['imagen']; ?>" alt="<?php echo $imagen['titulo']; ?>">
                <div class="slide-content">
                    <h2><?php echo $imagen['titulo']; ?></h2>
                    <p><?php echo $imagen['descripcion']; ?></p>
                    <a href="index.php?c=catalogo" class="btn-primary">Ver Catálogo</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Controles del carrusel -->
    <button class="carrusel-control prev" onclick="cambiarSlide(-1)">&#10094;</button>
    <button class="carrusel-control next" onclick="cambiarSlide(1)">&#10095;</button>
    
    <!-- Indicadores -->
    <div class="carrusel-indicadores">
        <?php foreach($imagenesCarrusel as $index => $imagen): ?>
            <span class="indicador <?php echo $index === 0 ? 'active' : ''; ?>" onclick="irASlide(<?php echo $index; ?>)"></span>
        <?php endforeach; ?>
    </div>
</div>

<!-- Botones de categorias -->
<div class="categorias-section">
    <h2>Nuestros Catálogos</h2>
    <p>Explora nuestras categorías principales</p>
    
    <div class="categorias-grid">
        <?php foreach($categoriasCatalogo as $categoria): ?>
            <a href="<?php echo $categoria['url']; ?>" class="categoria-card">
                <div class="categoria-icono"><?php echo $categoria['icono']; ?></div>
                <h3><?php echo $categoria['nombre']; ?></h3>
                <p><?php echo $categoria['descripcion']; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Seccion de informacion adicional -->
<div class="info-section">
    <div class="info-card">
        <h3>🚚 Envío Gratis</h3>
        <p>En compras mayores a $100</p>
    </div>
    <div class="info-card">
        <h3>✅ Calidad Garantizada</h3>
        <p>Productos de primera calidad</p>
    </div>
    <div class="info-card">
        <h3>📞 Soporte 24/7</h3>
        <p>Asesoramiento profesional</p>
    </div>
    <div class="info-card">
        <h3>💳 Múltiples Pagos</h3>
        <p>Tarjeta, efectivo y QR</p>
    </div>
</div>

<style>
    /* Estilos del carrusel */
    .carrusel-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto 40px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .carrusel {
        position: relative;
        height: 500px;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    .slide.active {
        opacity: 1;
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .slide-content {
        position: absolute;
        bottom: 50px;
        left: 50px;
        background: rgba(44, 62, 80, 0.9);
        color: white;
        padding: 25px;
        border-radius: 8px;
        max-width: 400px;
    }

    .slide-content h2 {
        margin-bottom: 10px;
        font-size: 28px;
        color: white;
    }

    .slide-content p {
        margin-bottom: 15px;
        font-size: 16px;
        color: #ecf0f1;
    }

    .btn-primary {
        background: #1abc9c;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        transition: background 0.3s;
        font-weight: bold;
    }

    .btn-primary:hover {
        background: #16a085;
        transform: translateY(-2px);
    }

    .carrusel-control {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        padding: 15px 20px;
        cursor: pointer;
        font-size: 18px;
        transition: background 0.3s;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .carrusel-control:hover {
        background: rgba(0,0,0,0.8);
    }

    .prev {
        left: 20px;
    }

    .next {
        right: 20px;
    }

    .carrusel-indicadores {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
    }

    .indicador {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: background 0.3s;
    }

    .indicador.active {
        background: white;
    }

    /* Estilos de las categorías */
    .categorias-section {
        text-align: center;
        padding: 60px 20px;
        background: white;
        margin: 40px 0;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }

    .categorias-section h2 {
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 36px;
        font-weight: bold;
    }

    .categorias-section p {
        color: #7f8c8d;
        margin-bottom: 40px;
        font-size: 18px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .categorias-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .categoria-card {
        background: white;
        padding: 30px 25px;
        border-radius: 15px;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s ease;
        border: 2px solid #ecf0f1;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .categoria-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #1abc9c;
        background: #f8f9fa;
    }

    .categoria-icono {
        font-size: 64px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .categoria-card:hover .categoria-icono {
        transform: scale(1.1);
    }

    .categoria-card h3 {
        margin-bottom: 15px;
        font-size: 22px;
        font-weight: 600;
        text-align: center;
    }

    .categoria-card p {
        color: #7f8c8d;
        font-size: 15px;
        margin: 0;
        text-align: center;
        line-height: 1.5;
    }

    /* Sección de información */
    .info-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin: 50px 0;
        padding: 40px;
        background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        border-radius: 15px;
        color: white;
    }

    .info-card {
        text-align: center;
        padding: 25px 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        transition: transform 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .info-card:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.15);
    }

    .info-card h3 {
        margin-bottom: 12px;
        font-size: 20px;
        font-weight: 600;
    }

    .info-card p {
        color: #bdc3c7;
        margin: 0;
        font-size: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .carrusel {
            height: 400px;
        }
        
        .slide-content {
            left: 20px;
            bottom: 20px;
            padding: 20px;
            max-width: 300px;
        }
        
        .slide-content h2 {
            font-size: 22px;
        }
        
        .carrusel-control {
            width: 40px;
            height: 40px;
            padding: 10px 15px;
        }
        
        .categorias-section {
            padding: 40px 15px;
        }
        
        .categorias-section h2 {
            font-size: 28px;
        }
        
        .categorias-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .info-section {
            grid-template-columns: 1fr;
            padding: 30px 20px;
            gap: 20px;
        }
    }

    @media (max-width: 480px) {
        .carrusel {
            height: 300px;
        }
        
        .slide-content {
            left: 15px;
            bottom: 15px;
            padding: 15px;
            max-width: 250px;
        }
        
        .slide-content h2 {
            font-size: 18px;
        }
        
        .slide-content p {
            font-size: 14px;
        }
        
        .categorias-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const indicadores = document.querySelectorAll('.indicador');

    function mostrarSlide(n) {
        // Ocultar todos los slides
        slides.forEach(slide => slide.classList.remove('active'));
        indicadores.forEach(ind => ind.classList.remove('active'));
        
        // Calcular el nuevo índice
        slideIndex = n;
        if (slideIndex >= slides.length) slideIndex = 0;
        if (slideIndex < 0) slideIndex = slides.length - 1;
        
        // Mostrar el slide actual
        slides[slideIndex].classList.add('active');
        indicadores[slideIndex].classList.add('active');
    }

    function cambiarSlide(n) {
        mostrarSlide(slideIndex + n);
    }

    function irASlide(n) {
        mostrarSlide(n);
    }

    // Cambio automatico cada 5 segundos
    let carruselInterval;

    function iniciarCarruselAutomatico() {
        carruselInterval = setInterval(() => {
            cambiarSlide(1);
        }, 5000);
    }

    function pausarCarruselAutomatico() {
        clearInterval(carruselInterval);
    }

    // Pausar el carrusel cuando el mouse esta sobre él
    const carrusel = document.getElementById('carrusel');
    if (carrusel) {
        carrusel.addEventListener('mouseenter', pausarCarruselAutomatico);
        carrusel.addEventListener('mouseleave', iniciarCarruselAutomatico);
    }

    // Iniciar el carrusel automatico
    document.addEventListener('DOMContentLoaded', function() {
        iniciarCarruselAutomatico();
        
        // Asegurarse de que el primer slide este activo
        mostrarSlide(0);
    });

    // Manejar el evento de visibilidad de la pagina
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            pausarCarruselAutomatico();
        } else {
            iniciarCarruselAutomatico();
        }
    });
</script>