<!-- Boton volver -->
<div class="nav-articulo">
    <div class="container">
        <a href="index.php?c=informate" class="btn-volver">
            ← Volver a Artículos
        </a>
    </div>
</div>

<!-- Cabecera del articulo -->
<section class="cabecera-articulo">
    <div class="container">
        <div class="articulo-meta">
            <span class="articulo-categoria"><?php echo $articulo['categoria']; ?></span>
            <span class="articulo-fecha"><?php echo $articulo['fecha']; ?></span>
            <span class="tiempo-lectura"><?php echo $articulo['tiempo_lectura']; ?> de lectura</span>
        </div>
        <h1><?php echo $articulo['titulo']; ?></h1>
        <p class="articulo-descripcion"><?php echo $articulo['descripcion']; ?></p>
        <div class="articulo-autor">
            <strong>Por:</strong> <?php echo $articulo['autor']; ?>
        </div>
    </div>
</section>

<!-- Imagen destacada -->
<section class="imagen-destacada">
    <div class="container">
        <img src="<?php echo $articulo['imagen']; ?>" alt="<?php echo $articulo['titulo']; ?>">
    </div>
</section>

<!-- Contenido del articulo -->
<section class="contenido-articulo">
    <div class="container">
        <div class="contenido-texto">
            <?php echo $articulo['contenido']; ?>
        </div>
    </div>
</section>

<!-- Articulos relacionados -->
<section class="articulos-relacionados">
    <div class="container">
        <h2>Artículos Relacionados</h2>
        <div class="relacionados-grid">
            <div class="relacionado-card">
                <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Herramientas">
                <h3>Guía de Mantenimiento para Herramientas Eléctricas</h3>
                <a href="index.php?articulo=1">Leer más</a>
            </div>
            <div class="relacionado-card">
                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Pintura">
                <h3>Técnicas Avanzadas de Pintura</h3>
                <a href="index.php?articulo=2">Leer más</a>
            </div>
            <div class="relacionado-card">
                <img src="https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Fontanería">
                <h3>Instalación Básica de Tuberías</h3>
                <a href="index.php?articulo=3">Leer más</a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Navegación */
    .nav-articulo {
        background: #f8f9fa;
        padding: 20px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .btn-volver {
        color: #1abc9c;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .btn-volver:hover {
        color: #16a085;
    }

    /* Cabecera del artículo */
    .cabecera-articulo {
        padding: 60px 0 40px;
        background: white;
    }

    .articulo-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .articulo-categoria {
        background: #1abc9c;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
    }

    .articulo-fecha, .tiempo-lectura {
        color: #7f8c8d;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .cabecera-articulo h1 {
        font-size: 42px;
        color: #2c3e50;
        margin-bottom: 20px;
        line-height: 1.3;
        font-weight: bold;
    }

    .articulo-descripcion {
        font-size: 20px;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 20px;
        max-width: 800px;
    }

    .articulo-autor {
        color: #7f8c8d;
        font-size: 16px;
    }

    /* Imagen destacada */
    .imagen-destacada {
        padding: 0 0 40px;
    }

    .imagen-destacada img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    /* Contenido del artículo */
    .contenido-articulo {
        padding: 40px 0 60px;
        background: white;
    }

    .contenido-texto {
        max-width: 800px;
        margin: 0 auto;
        font-size: 18px;
        line-height: 1.8;
        color: #2c3e50;
    }

    .contenido-texto h3 {
        color: #2c3e50;
        margin: 30px 0 15px;
        font-size: 24px;
        font-weight: 600;
    }

    .contenido-texto h4 {
        color: #1abc9c;
        margin: 25px 0 10px;
        font-size: 20px;
        font-weight: 600;
    }

    .contenido-texto p {
        margin-bottom: 20px;
    }

    .contenido-texto ul {
        margin-bottom: 20px;
        padding-left: 20px;
    }

    .contenido-texto li {
        margin-bottom: 8px;
        position: relative;
    }

    .contenido-texto li:before {
        content: '•';
        color: #1abc9c;
        font-weight: bold;
        position: absolute;
        left: -15px;
    }

    /* Artículos relacionados */
    .articulos-relacionados {
        padding: 60px 0;
        background: #f8f9fa;
    }

    .articulos-relacionados h2 {
        text-align: center;
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .relacionados-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .relacionado-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .relacionado-card:hover {
        transform: translateY(-5px);
    }

    .relacionado-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .relacionado-card h3 {
        padding: 20px;
        font-size: 18px;
        color: #2c3e50;
        margin: 0;
        line-height: 1.4;
    }

    .relacionado-card a {
        display: block;
        padding: 15px 20px;
        background: #3498db;
        color: white;
        text-decoration: none;
        text-align: center;
        transition: background 0.3s;
    }

    .relacionado-card a:hover {
        background: #2980b9;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cabecera-articulo h1 {
            font-size: 32px;
        }

        .articulo-descripcion {
            font-size: 18px;
        }

        .contenido-texto {
            font-size: 16px;
        }

        .articulo-meta {
            flex-direction: column;
            gap: 10px;
        }

        .relacionados-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .cabecera-articulo h1 {
            font-size: 28px;
        }

        .articulos-relacionados h2 {
            font-size: 28px;
        }
    }
</style>