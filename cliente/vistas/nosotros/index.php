<!-- Portada -->
<section class="portada-nosotros">
    <div class="portada-imagen">
        <img src="<?php echo $portada['imagen']; ?>" alt="Ferretería">
        <div class="portada-contenido">
            <h1><?php echo $portada['titulo']; ?></h1>
            <p><?php echo $portada['subtitulo']; ?></p>
        </div>
    </div>
</section>

<!-- Nuestra Historia -->
<section class="historia-section">
    <div class="container">
        <div class="historia-contenido">
            <h2><?php echo $historia['titulo']; ?></h2>
            <p><?php echo $historia['contenido']; ?></p>
        </div>
    </div>
</section>

<!-- Nuestra Trayectoria -->
<section class="trayectoria-section">
    <div class="container">
        <h2><?php echo $trayectoria['titulo']; ?></h2>
        <div class="linea-tiempo">
            <?php foreach($trayectoria['eventos'] as $index => $evento): ?>
                <div class="evento <?php echo $index % 2 == 0 ? 'izquierda' : 'derecha'; ?>">
                    <div class="punto"></div>
                    <div class="contenido-evento">
                        <div class="año"><?php echo $evento['año']; ?></div>
                        <div class="descripcion"><?php echo $evento['evento']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Estadisticas -->
<section class="estadisticas-section">
    <div class="container">
        <div class="estadisticas-grid">
            <?php foreach($estadisticas as $estadistica): ?>
                <div class="estadistica-card">
                    <div class="numero"><?php echo $estadistica['numero']; ?></div>
                    <div class="texto"><?php echo $estadistica['texto']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Nuestros Valores -->
<section class="valores-section">
    <div class="container">
        <h2><?php echo $valores['titulo']; ?></h2>
        <div class="valores-grid">
            <?php foreach($valores['items'] as $valor): ?>
                <div class="valor-card">
                    <div class="valor-icono"><?php echo $valor['icono']; ?></div>
                    <h3><?php echo $valor['titulo']; ?></h3>
                    <p><?php echo $valor['descripcion']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Nuestro Equipo -->
<section class="equipo-section">
    <div class="container">
        <h2><?php echo $equipo['titulo']; ?></h2>
        <div class="equipo-grid">
            <?php foreach($equipo['miembros'] as $miembro): ?>
                <div class="miembro-card">
                    <div class="miembro-icono"><?php echo $miembro['icono']; ?></div>
                    <h3><?php echo $miembro['nombre']; ?></h3>
                    <div class="miembro-cargo"><?php echo $miembro['cargo']; ?></div>
                    <p><?php echo $miembro['descripcion']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonios -->
<section class="testimonios-section">
    <div class="container">
        <h2><?php echo $testimonios['titulo']; ?></h2>
        <div class="testimonios-grid">
            <?php foreach($testimonios['comentarios'] as $testimonio): ?>
                <div class="testimonio-card">
                    <div class="comillas">"</div>
                    <p class="testimonio-texto"><?php echo $testimonio['texto']; ?></p>
                    <div class="testimonio-autor">
                        <strong><?php echo $testimonio['nombre']; ?></strong>
                        <span><?php echo $testimonio['cargo']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    /* Portada */
    .portada-nosotros {
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
        font-size: 24px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    /* Historia */
    .historia-section {
        padding: 80px 0;
        background: white;
    }

    .historia-contenido {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }

    .historia-contenido h2 {
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .historia-contenido p {
        font-size: 18px;
        line-height: 1.8;
        color: #7f8c8d;
    }

    /* Trayectoria */
    .trayectoria-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .trayectoria-section h2 {
        text-align: center;
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 60px;
        font-weight: bold;
    }

    .linea-tiempo {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }

    .linea-tiempo::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #1abc9c;
        transform: translateX(-50%);
    }

    .evento {
        position: relative;
        margin-bottom: 60px;
        width: 45%;
    }

    .evento.izquierda {
        left: 0;
        text-align: right;
        padding-right: 40px;
    }

    .evento.derecha {
        left: 55%;
        padding-left: 40px;
    }

    .punto {
        position: absolute;
        width: 20px;
        height: 20px;
        background: #1abc9c;
        border-radius: 50%;
        top: 0;
    }

    .evento.izquierda .punto {
        right: -10px;
    }

    .evento.derecha .punto {
        left: -10px;
    }

    .año {
        font-size: 24px;
        font-weight: bold;
        color: #1abc9c;
        margin-bottom: 10px;
    }

    .descripcion {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        font-size: 16px;
        line-height: 1.6;
    }

    /* Estadísticas */
    .estadisticas-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }

    .estadisticas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
        text-align: center;
    }

    .estadistica-card {
        padding: 30px 20px;
    }

    .numero {
        font-size: 48px;
        font-weight: bold;
        color: #1abc9c;
        margin-bottom: 15px;
    }

    .texto {
        font-size: 18px;
        opacity: 0.9;
    }

    /* Valores */
    .valores-section {
        padding: 80px 0;
        background: white;
    }

    .valores-section h2 {
        text-align: center;
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 60px;
        font-weight: bold;
    }

    .valores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
    }

    .valor-card {
        text-align: center;
        padding: 40px 30px;
        background: #f8f9fa;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    .valor-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .valor-icono {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .valor-card h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .valor-card p {
        color: #7f8c8d;
        line-height: 1.6;
    }

    /* Equipo */
    .equipo-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .equipo-section h2 {
        text-align: center;
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 60px;
        font-weight: bold;
    }

    .equipo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 40px;
    }

    .miembro-card {
        background: white;
        padding: 40px 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .miembro-card:hover {
        transform: translateY(-5px);
    }

    .miembro-icono {
        font-size: 80px;
        margin-bottom: 20px;
    }

    .miembro-card h3 {
        font-size: 22px;
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .miembro-cargo {
        color: #1abc9c;
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .miembro-card p {
        color: #7f8c8d;
        line-height: 1.6;
    }

    /* Testimonios */
    .testimonios-section {
        padding: 80px 0;
        background: white;
    }

    .testimonios-section h2 {
        text-align: center;
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 60px;
        font-weight: bold;
    }

    .testimonios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .testimonio-card {
        background: #f8f9fa;
        padding: 40px 30px;
        border-radius: 15px;
        position: relative;
        transition: transform 0.3s ease;
    }

    .testimonio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .comillas {
        font-size: 60px;
        color: #1abc9c;
        position: absolute;
        top: 10px;
        left: 20px;
        opacity: 0.3;
    }

    .testimonio-texto {
        font-size: 16px;
        line-height: 1.7;
        color: #2c3e50;
        margin-bottom: 20px;
        font-style: italic;
    }

    .testimonio-autor {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
    }

    .testimonio-autor strong {
        display: block;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .testimonio-autor span {
        color: #7f8c8d;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .portada-contenido h1 {
            font-size: 36px;
        }

        .portada-contenido p {
            font-size: 18px;
        }

        .evento {
            width: 100%;
            left: 0 !important;
            text-align: left !important;
            padding: 0 0 0 40px !important;
            margin-bottom: 40px;
        }

        .linea-tiempo::before {
            left: 20px;
        }

        .evento.izquierda .punto,
        .evento.derecha .punto {
            left: 10px !important;
            right: auto !important;
        }

        .estadisticas-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .valores-grid,
        .equipo-grid,
        .testimonios-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }

    @media (max-width: 480px) {
        .estadisticas-grid {
            grid-template-columns: 1fr;
        }

        .numero {
            font-size: 36px;
        }

        .portada-nosotros {
            height: 300px;
        }
    }
</style>