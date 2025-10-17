<?php
session_start();

// Verifica si hay sesión activa
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: logout.php'); // Ajusta la ruta a tu login
    exit;
}

// Ahora sí puedes usar las variables de sesión
$usuario = $_SESSION['empleado_usuario'];
$nombre = $_SESSION['nombre_empleado'];
$apellido = $_SESSION['apellido_empleado'];
$id_empleado = $_SESSION['id_empleado'];
$id_sucursal = $_SESSION['id_sucursal'];

include 'conexion.php';

// Total del carrito por AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'total_carrito') {
    $total = 0;
    if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
        $total = array_sum($_SESSION['carrito']);
    }
    echo json_encode(['total' => $total]);
    exit;
}



// Añadir producto al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'añadir') {
    $idProducto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    $mensaje = "";

    if ($cantidad > 0) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        if (isset($_SESSION['carrito'][$idProducto])) {
            $_SESSION['carrito'][$idProducto] += $cantidad;
        } else {
            $_SESSION['carrito'][$idProducto] = $cantidad;
        }
        $mensaje = 'Producto añadido al carrito.';
    }

    echo json_encode([
        'exito' => true,
        'mensaje' => $mensaje
    ]);
    exit;
}

// Obtener categorías para filtro
$sqlCategorias = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY nombre_categoria";
$resultCategorias = $conexion->query($sqlCategorias);

$categoriaFiltro = isset($_GET['categoria']) && $_GET['categoria'] !== 'all' ? intval($_GET['categoria']) : null;

// Obtener productos según filtro
$sqlProductos = $categoriaFiltro
    ? "SELECT * FROM producto WHERE id_categoria = $categoriaFiltro ORDER BY nombre"
    : "SELECT * FROM producto ORDER BY nombre";
$resultProductos = $conexion->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/index.css">
  <title>Productos - Ferretería</title>

</head>
<body>
<div id="contenedor-notificaciones" style="position: fixed; top: 20px; right: 20px; z-index: 1000; display: flex; flex-direction: column-reverse; gap: 10px;"></div>

<header class="main-header">
  <div class="header-top">
    <div class="logo">
      <h1>🛠 Productos - Ferretería la Llave</h1>
      
    </div>
    <div class="empleado-info">
      <div class="icono-usuario"><i class="fas fa-user-circle"></i></div>
      <div class="datos-usuario">
        <div><strong>Bienvenido <?= htmlspecialchars($nombre . ' ' . $apellido) ?></strong></div>
        <div>Usuario: <?= htmlspecialchars($usuario) ?></div>
      </div>
      <a href="logout.php" class="logout">Cerrar sesión</a>
    </div>
  </div>

  <nav class="navbar">
    <ul class="nav-menu">
      <li><a href="asistencia/asistencia.php">Asistencia</a></li>
      <li><a href="clientes/clientes.php">Clientes</a></li>
    </ul>

    <ul class="nav-menu-right">   
      <li>
        <a href="carrito/carrito.php" class="btn-carrito">
          🛒 <span>Carrito</span>
          <span class="badge-carrito" id="badgeCarrito">0</span>
        </a>
      </li>   
      <li>
<a href="pedidos/pedidos.php" class="btn-carrito">
  📦 <span>Pedidos</span>
  <span id="badgePedidos" class="badge-carrito" style="display:none;"></span>
</a>


</li>
 
    </ul>
    
  </nav>
</header>

<form method="GET" action="" id="filtros-form">
  <label for="categoria">Filtrar por categoría:</label>
  <select name="categoria" id="categoria" onchange="this.form.submit()">
    <option value="all" <?= $categoriaFiltro === null ? 'selected' : '' ?>>Todos los Productos</option>
    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
      <option value="<?= $categoria['id_categoria'] ?>" <?= $categoriaFiltro === intval($categoria['id_categoria']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
      </option>
    <?php endwhile; ?>
  </select>

  <label for="buscador-productos" style="margin-left:auto;">Buscar producto:</label>
  <input type="text" id="buscador-productos" placeholder="Buscar por nombre..." autocomplete="off" style="padding: 0.3rem; font-size: 1rem;">
</form>

<div id="productos">
  <?php if ($resultProductos->num_rows > 0): ?>
    <?php while ($producto = $resultProductos->fetch_assoc()): ?>
      <div class="producto" data-nombre="<?= strtolower(htmlspecialchars($producto['nombre'])) ?>">
        <img src='/grupo_ferreteria/imagenes/<?= htmlspecialchars($producto['imagen']) ?>'>
        <div class="info-producto">
          <div class="nombre-producto"><?= htmlspecialchars($producto['nombre']) ?></div>
          <div class="descripcion-producto"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></div>
         <div class="precio-stock">
  <div class="precio-producto">Bs/. <?= number_format($producto['precio_unitario'], 2) ?></div>
  <div class="stock-disponible">Stock: <?= intval($producto['stock']) ?></div>
</div>

          <form class="form-agregar" data-id="<?= intval($producto['id_producto']) ?>">
            <input type="hidden" name="id_producto" value="<?= intval($producto['id_producto']) ?>">
            <label for="cantidad_<?= intval($producto['id_producto']) ?>">Cantidad:</label>
            <input type="number" name="cantidad" min="1" max="<?= intval($producto['stock']) ?>" value="1" required style="width: 60px; margin: 0 0.5rem;">
            <button type="submit" class="btn-agregar">Añadir</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No hay productos disponibles en esta categoría.</p>
  <?php endif; ?>
</div>

<script>
  // Filtro de búsqueda
  const buscador = document.getElementById('buscador-productos');
  const productos = document.querySelectorAll('#productos .producto');
  buscador.addEventListener('input', () => {
    const filtro = buscador.value.toLowerCase();
    productos.forEach(p => {
      const nombre = p.getAttribute('data-nombre');
      p.style.display = nombre.includes(filtro) ? '' : 'none';
    });
  });

  // Añadir al carrito
  document.querySelectorAll('.form-agregar').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      formData.append('accion', 'añadir');

      fetch('index.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.exito && data.mensaje) {
          mostrarNotificacion(data.mensaje);
          actualizarBadge();

        }
      })
      .catch(err => console.error('Error al añadir producto:', err));
    });
  });

  function mostrarNotificacion(mensaje) {
    const contenedor = document.getElementById('contenedor-notificaciones');
    const noti = document.createElement('div');
    noti.className = 'notificacion-exito';
    noti.innerHTML = `
      <span>${mensaje}</span>
      <span class="cerrar" onclick="this.parentElement.remove()">×</span>
    `;
    contenedor.appendChild(noti);
    setTimeout(() => {
      noti.classList.add('ocultar');
      setTimeout(() => noti.remove(), 200);
    }, 2000);
  }

  // Actualizar badge de carrito
  function actualizarBadge() {
    fetch('index.php?accion=total_carrito')
      .then(res => res.json())
      .then(data => {
        const total = data.total || 0;
        const badge = document.getElementById('badgeCarrito');
        if (total > 0) {
          badge.textContent = total;
          badge.style.display = 'flex';
        } else {
          badge.style.display = 'none';
        }
      })
      .catch(err => console.error('Error al actualizar badge del carrito:', err));
  }


  function actualizarBadgePedidos() {
  fetch('pedidos/cantador_pedidos.php')  // ruta relativa CORRECTA desde empleado/index.php
    .then(res => res.json())
    .then(data => {
      const badge = document.getElementById('badgePedidos');
      if (data.total > 0) {
        badge.textContent = data.total;
        badge.style.display = 'flex';
      } else {
        badge.style.display = 'none';
      }
    })
    .catch(err => console.error('Error al actualizar pedidos:', err));
}



actualizarBadge();         // tu carrito
actualizarBadgePedidos();  // los pedidos pendientes
// Actualizar pedidos cada 1 segundo automáticamente
setInterval(actualizarBadgePedidos, 1000);

</script>
</body>
</html>
<?php $conexion->close(); ?>