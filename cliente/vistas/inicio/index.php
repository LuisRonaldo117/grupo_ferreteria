<!-- Carrusel -->
<div class="carousel-container">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach($imagenesCarrusel as $index => $imagen): ?>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                    class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner rounded-3">
            <?php foreach($imagenesCarrusel as $index => $imagen): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                     style="background-image: url('<?php echo $imagen['imagen']; ?>')">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo $imagen['titulo']; ?></h5>
                        <p><?php echo $imagen['descripcion']; ?></p>
                        <a href="index.php?c=catalogo" class="btn btn-primary mt-2">Ver Catálogo</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<!-- Sección de Categorías -->
<div class="categories-section">
    <h3 class="section-title">Nuestros Catálogos</h3>
    <p class="section-subtitle">Explora nuestras categorías principales</p>
    
    <div class="row">
        <?php foreach($categoriasCatalogo as $categoria): ?>
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <a href="<?php echo $categoria['url']; ?>" class="text-decoration-none">
                <div class="category-card">
                    <i class="fas fa-tools"></i>
                    <h6><?php echo $categoria['nombre']; ?></h6>
                    <small class="text-muted"><?php echo $categoria['descripcion']; ?></small>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Sección de Información -->
<div class="info-section">
    <div class="row">
        <div class="col-md-3 col-6 mb-3">
            <div class="info-card text-center">
                <i class="fas fa-shipping-fast fa-2x mb-2"></i>
                <h6>Envío Gratis</h6>
                <small>En compras mayores a $100</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="info-card text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h6>Calidad Garantizada</h6>
                <small>Productos de primera calidad</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="info-card text-center">
                <i class="fas fa-headset fa-2x mb-2"></i>
                <h6>Soporte 24/7</h6>
                <small>Asesoramiento profesional</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="info-card text-center">
                <i class="fas fa-credit-card fa-2x mb-2"></i>
                <h6>Múltiples Pagos</h6>
                <small>Tarjeta, efectivo y QR</small>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #d32f2f;
        --accent-color: #007bff;
        --light-bg: #f8f9fa;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-bg);
    }
    
    /* Carrusel */
    .carousel-container {
        margin-top: 20px;
        margin-bottom: 30px;
    }
    
    .carousel-item {
        height: 300px;
        background-size: cover;
        background-position: center;
    }
    
    .carousel-caption {
        background-color: rgba(0, 0, 0, 0.6);
        padding: 15px;
        border-radius: 5px;
        bottom: 20px;
        right: 20px;
        left: 20px;
        width: auto;
    }
    
    .carousel-caption h5 {
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    /* Categorías */
    .categories-section {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 3px 10px rgba(0,0,0,.05);
    }
    
    .section-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        padding-bottom: 10px;
        text-transform: uppercase;
        font-size: 1.1rem;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--accent-color);
    }
    
    .section-subtitle {
        color: #6c757d;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }
    
    .category-card {
        text-align: center;
        padding: 20px 15px;
        border-radius: 8px;
        transition: all 0.3s;
        margin-bottom: 15px;
        background: white;
        border: 1px solid #e9ecef;
        height: 100%;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,.1);
        border-color: var(--accent-color);
    }
    
    .category-card i {
        font-size: 2rem;
        margin-bottom: 15px;
        color: var(--accent-color);
    }
    
    .category-card h6 {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary-color);
    }
    
    .category-card small {
        font-size: 0.8rem;
        line-height: 1.4;
    }
    
    /* Sección de Información */
    .info-section {
        background: white;
        padding: 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 3px 10px rgba(0,0,0,.05);
    }
    
    .info-card {
        padding: 20px 15px;
        border-radius: 8px;
        transition: all 0.3s;
        background: white;
        border: 1px solid #e9ecef;
        height: 100%;
    }
    
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 3px 10px rgba(0,0,0,.1);
        border-color: var(--accent-color);
    }
    
    .info-card i {
        color: var(--accent-color);
        margin-bottom: 10px;
    }
    
    .info-card h6 {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary-color);
    }
    
    .info-card small {
        font-size: 0.8rem;
        color: #6c757d;
        line-height: 1.4;
    }
    
    /* Botones */
    .btn-primary {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: translateY(-1px);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .carousel-item {
            height: 250px;
        }
        
        .categories-section,
        .info-section {
            padding: 20px 15px;
        }
        
        .category-card,
        .info-card {
            padding: 15px 10px;
        }
        
        .category-card i,
        .info-card i {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .carousel-item {
            height: 200px;
        }
        
        .carousel-caption {
            padding: 10px;
            bottom: 10px;
            right: 10px;
            left: 10px;
        }
        
        .carousel-caption h5 {
            font-size: 1rem;
        }
        
        .carousel-caption p {
            font-size: 0.8rem;
            margin-bottom: 5px;
        }
        
        .btn-primary {
            padding: 5px 15px;
            font-size: 0.8rem;
        }
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Inicializar carrusel de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.querySelector('#mainCarousel');
        if (myCarousel) {
            var carousel = new bootstrap.Carousel(myCarousel, {
                interval: 5000,
                wrap: true
            });
        }
    });
</script>