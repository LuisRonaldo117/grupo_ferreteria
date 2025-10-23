<h1>
    <?php 
    if (isset($terminoBusqueda)) {
        echo $nombreCategoria;
        echo ' <span class="resultados-count">(' . $totalResultados . ' resultado' . ($totalResultados != 1 ? 's' : '') . ')</span>';
    } else {
        echo 'Catálogo - ' . $nombreCategoria;
    }
    ?>
</h1>

<?php if (isset($terminoBusqueda)): ?>
    <div class="busqueda-info">
        <p>Mostrando resultados para: <strong>"<?php echo htmlspecialchars($terminoBusqueda); ?>"</strong></p>
        <a href="index.php?c=catalogo" class="btn-volver-catalogo">← Volver al catálogo completo</a>
    </div>
<?php endif; ?>

<!-- Filtros por categoría -->
<div class="filtros-categoria">
    <a href="index.php?c=catalogo" class="filtro-btn <?php echo $categoriaActual === 'todos' ? 'active' : ''; ?>">
        Todos los Productos
    </a>
    <?php foreach($categorias as $id => $nombre): ?>
        <a href="index.php?c=catalogo&categoria=<?php echo $id; ?>" 
           class="filtro-btn <?php echo $categoriaActual == $id ? 'active' : ''; ?>">
            <?php echo $nombre; ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Grid de productos -->
<div class="productos-grid">
    <?php if(!empty($productos)): ?>
        <?php foreach($productos as $producto): ?>
            <div class="producto-card">
                <div class="producto-imagen">
                    <?php if (strpos($producto['imagen'], 'http') === 0): ?>
                        <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
                    <?php elseif (strpos($producto['imagen'], 'assets/') === 0): ?>
                        <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                        <div class="producto-icono" style="display: none;"><?php echo obtenerIconoAlternativo($producto['categoria']); ?></div>
                    <?php else: ?>
                        <div class="producto-icono"><?php echo $producto['imagen']; ?></div>
                    <?php endif; ?>
                </div>
                <h3><?php echo $producto['nombre']; ?></h3>
                <p class="producto-descripcion">
                    <?php 
                    if (!empty($producto['descripcion'])) {
                        echo strlen($producto['descripcion']) > 100 ? 
                             substr($producto['descripcion'], 0, 100) . '...' : 
                             $producto['descripcion'];
                    } else {
                        echo 'Descripción no disponible';
                    }
                    ?>
                </p>
                <p class="producto-precio">$<?php echo number_format($producto['precio'], 2); ?></p>
                <p class="producto-stock <?php echo $producto['stock'] < 10 ? 'stock-bajo' : ''; ?>">
                    Stock: <?php echo $producto['stock']; ?>
                </p>
                <button class="btn-agregar-carrito" 
                    onclick="agregarAlCarrito(
                        <?php echo $producto['id']; ?>,
                        '<?php echo addslashes($producto['nombre']); ?>',
                        <?php echo $producto['precio']; ?>,
                        '<?php echo $producto['imagen']; ?>'
                        )"
                        <?php echo $producto['stock'] == 0 ? 'disabled' : ''; ?>>
                    <?php echo $producto['stock'] == 0 ? 'Sin Stock' : 'Agregar al Carrito'; ?>
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="sin-resultados">
            <div class="icono">🔍</div>
            <h3>No se encontraron productos</h3>
            <p>No hay productos que coincidan con tu búsqueda.</p>
            
            <?php if (isset($terminoBusqueda)): ?>
                <div class="sugerencias-busqueda">
                    <h4>Sugerencias:</h4>
                    <ul>
                        <li>Revisa la ortografía de las palabras</li>
                        <li>Usa términos más generales</li>
                        <li>Prueba con otras palabras relacionadas</li>
                        <li>Busca por categorías específicas</li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <a href="index.php?c=catalogo" class="btn-ir-catalogo">Ver todos los productos</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .filtros-categoria {
        display: flex;
        gap: 10px;
        margin: 20px 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filtro-btn {
        padding: 10px 20px;
        background: #ecf0f1;
        color: #2c3e50;
        text-decoration: none;
        border-radius: 25px;
        transition: all 0.3s;
        border: 2px solid transparent;
        font-size: 14px;
    }

    .filtro-btn:hover,
    .filtro-btn.active {
        background: #1abc9c;
        color: white;
        border-color: #16a085;
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .producto-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #ecf0f1;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .producto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .producto-imagen {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .producto-imagen img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .producto-icono {
        font-size: 60px;
        opacity: 0.7;
    }

    .producto-card h3 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 16px;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .producto-descripcion {
        color: #7f8c8d;
        font-size: 13px;
        margin-bottom: 10px;
        flex-grow: 1;
        line-height: 1.4;
    }

    .producto-precio {
        font-size: 20px;
        font-weight: bold;
        color: #1abc9c;
        margin: 10px 0;
    }

    .producto-stock {
        color: #27ae60;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .producto-stock.stock-bajo {
        color: #e74c3c;
        font-weight: bold;
    }

    .btn-agregar-carrito {
        background: #3498db;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
        font-size: 14px;
        margin-top: auto;
    }

    .btn-agregar-carrito:hover {
        background: #2980b9;
    }

    .btn-agregar-carrito:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
    }

    .no-productos {
        text-align: center;
        color: #7f8c8d;
        font-size: 18px;
        grid-column: 1 / -1;
        padding: 40px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .btn-volver {
        display: inline-block;
        background: #1abc9c;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 15px;
        transition: background 0.3s;
    }

    .btn-volver:hover {
        background: #16a085;
    }

    /* Estilos para la búsqueda */
    .busqueda-info {
        background: #e8f5e8;
        border: 1px solid #1abc9c;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
    }

    .busqueda-info p {
        margin: 0 0 15px 0;
        color: #2c3e50;
        font-size: 16px;
    }

    .btn-volver-catalogo {
        background: #3498db;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        transition: background 0.3s;
    }

    .btn-volver-catalogo:hover {
        background: #2980b9;
    }

    .resultados-count {
        font-size: 0.8em;
        color: #7f8c8d;
        font-weight: normal;
    }

    /* Mensaje cuando no hay resultados */
    .sin-resultados {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
        grid-column: 1 / -1;
    }

    .sin-resultados .icono {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .sin-resultados h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .sin-resultados p {
        margin-bottom: 25px;
        font-size: 16px;
    }

    .sugerencias-busqueda {
        margin-top: 20px;
        text-align: left;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .sugerencias-busqueda h4 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .sugerencias-busqueda ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sugerencias-busqueda li {
        padding: 5px 0;
        color: #7f8c8d;
    }

    .sugerencias-busqueda li:before {
        content: '•';
        color: #1abc9c;
        margin-right: 8px;
    }
</style>

<script>
    // Funcion para obtener icono alternativo
    function obtenerIconoAlternativo(categoria) {
        const iconos = {
            'HERRAMIENTAS MANUALES': '🔨',
            'HERRAMIENTAS ELÉCTRICAS': '⚡',
            'MATERIALES DE CONSTRUCCIÓN': '🏗️',
            'TORNILLERÍA Y SUJECIÓN': '🔩',
            'ELECTRICIDAD': '💡',
            'FONTANERÍA': '🚰',
            'PINTURAS Y ACCESORIOS': '🎨',
            'SEGURIDAD INDUSTRIAL': '🛡️'
        };
        return iconos[categoria] || '📦';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Agregar productos al carrito
        const botonesCarrito = document.querySelectorAll('.btn-agregar-carrito:not(:disabled)');
        
        botonesCarrito.forEach(boton => {
            boton.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const precio = this.getAttribute('data-precio');
                
                agregarAlCarrito(id, nombre, precio);
            });
        });
        
        function agregarAlCarrito(id, nombre, precio) {
            // Mostrar mensaje de confirmacion
            const mensaje = `Producto agregado al carrito:\n${nombre}\nPrecio: $${precio}`;
            alert(mensaje);
            
            console.log('Agregando al carrito:', {id, nombre, precio});
        }
    });
</script>

<?php
// Funcion para obtener icono alternativo
function obtenerIconoAlternativo($categoria) {
    $iconos = [
        'HERRAMIENTAS MANUALES' => '🔨',
        'HERRAMIENTAS ELÉCTRICAS' => '⚡',
        'MATERIALES DE CONSTRUCCIÓN' => '🏗️',
        'TORNILLERÍA Y SUJECIÓN' => '🔩',
        'ELECTRICIDAD' => '💡',
        'FONTANERÍA' => '🚰',
        'PINTURAS Y ACCESORIOS' => '🎨',
        'SEGURIDAD INDUSTRIAL' => '🛡️'
    ];
    return $iconos[$categoria] ?? '📦';
}
?>