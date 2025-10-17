<?php
session_start();
include '../conexion.php';
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: ../logout.php'); // Ajusta la ruta a tu login
    exit;
}

if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    if (isset($_SESSION['carrito'][$idEliminar])) {
        unset($_SESSION['carrito'][$idEliminar]);
    }
    header("Location: carrito.php");
    exit();
}

// Actualización vía AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'], $_POST['cantidad'])) {
    $idProducto = intval($_POST['id_producto']);
    $cantidad = max(0, intval($_POST['cantidad']));

    if ($cantidad === 0) {
        unset($_SESSION['carrito'][$idProducto]);
    } else {
        $_SESSION['carrito'][$idProducto] = $cantidad;
    }
    echo json_encode(['success' => true]);
    exit();
}

$productosFactura = [];
$total = 0;

if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
        $stmt = $conexion->prepare("SELECT id_producto, nombre, precio_unitario, imagen FROM producto WHERE id_producto = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($producto = $resultado->fetch_assoc()) {
            $producto['cantidad'] = $cantidad;
            $producto['subtotal'] = $producto['precio_unitario'] * $cantidad;
            $total += $producto['subtotal'];
            $productosFactura[] = $producto;
        }
        $stmt->close();
    }
}

// Obtener métodos de pago
$metodos = [];
$resultado = $conexion->query("SELECT id_metodo, nombre_metodo FROM metodo_pago");
while ($fila = $resultado->fetch_assoc()) {
    $metodos[] = $fila;
}

// Obtener clientes
$clientes = [];
$resultado = $conexion->query("
    SELECT cliente.id_cliente, persona.nombres, persona.apellidos, persona.ci 
    FROM cliente 
    INNER JOIN persona ON cliente.id_persona = persona.id_persona
");
while ($fila = $resultado->fetch_assoc()) {
    $clientes[] = $fila;
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/factura.css">
  <title>Factura - Ferretería</title>
</head>
<body>
<div class="container">
<h1>Detalles de Factura</h1>

<?php if (!empty($productosFactura)): ?>
  <form id="form-compra" action="confirmar_compra.php" method="POST">
    <div class="factura-formulario">
      <div class="campo-form">
        <label for="metodo">Método de Pago:</label>
        <select name="id_metodo" id="metodo" required>
          <?php foreach ($metodos as $metodo): ?>
            <option value="<?= $metodo['id_metodo'] ?>"><?= htmlspecialchars($metodo['nombre_metodo']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="campo-form">
        <label for="cliente_visual">Cliente:</label>
        <input type="text" id="cliente_visual" placeholder="Buscar CI o nombre" list="lista-clientes" autocomplete="off">
        <datalist id="lista-clientes">
          <option>No registrado</option>
          <?php foreach ($clientes as $cli): ?>
            <option data-id="<?= $cli['id_cliente'] ?>" value="<?= htmlspecialchars($cli['ci'] . ' - ' . $cli['nombres'] . ' ' . $cli['apellidos']) ?>"></option>
          <?php endforeach; ?>
        </datalist>
        <input type="hidden" name="id_cliente" id="cliente_real" value="0">
      </div>
    </div>

    <input type="hidden" name="tipo_venta" value="presencial">
    <input type="hidden" name="id_empleado" value="<?= $_SESSION['id_empleado'] ?? 2 ?>">
    <input type="hidden" name="total" value="<?= $total ?>">

    <table>
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Producto</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productosFactura as $item): ?>
        <tr>
          <td>
            <?php if (!empty($item['imagen'])): ?>
              <img src="/grupo_ferreteria/imagenes/<?= htmlspecialchars(trim($item['imagen'])) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>" width="100">
            <?php else: ?>
              <span>Sin imagen</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($item['nombre']) ?></td>
          <td>Bs/. <?= number_format($item['precio_unitario'], 2) ?></td>
          <td>
            <div class="stepper" data-idproducto="<?= $item['id_producto'] ?>">
              <button type="button" class="btn-restar">-</button>
              <span class="cantidad-texto"><?= $item['cantidad'] ?></span>
              <button type="button" class="btn-sumar">+</button>
              <input type="hidden" class="cantidad-input" value="<?= $item['cantidad'] ?>" data-idproducto="<?= $item['id_producto'] ?>" />
            </div>
          </td>
          <td>Bs/. <?= number_format($item['subtotal'], 2) ?></td>
          <td>
            <a href="carrito.php?eliminar=<?= $item['id_producto'] ?>" class="btn btn-eliminar" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="5">Total</td>
          <td>Bs/. <?= number_format($total, 2) ?></td>
        </tr>
      </tfoot>
    </table>

    <div class="btn-container">
      <button type="submit" class="btn btn-confirmar">Confirmar Compra</button>
      <a href="vaciar_carrito.php" class="btn btn-clear">Vaciar Carrito</a>
      <a href="../index.php" class="btn btn-back">Seguir Comprando</a>
    </div>
  </form>
<?php else: ?>
  <div class="carrito-vacio">
    <h2>🛒 Tu carrito está vacío</h2>
    <p>No has agregado ningún producto aún.</p>
    <a href="../index.php" class="btn-volver">Explorar productos</a>
  </div>
<?php endif; ?>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const steppers = document.querySelectorAll('.stepper');
    steppers.forEach(stepper => {
      const span = stepper.querySelector('span');
      const input = stepper.querySelector('input');
      const idProducto = input.dataset.idproducto;

      const actualizar = (nuevaCantidad) => {
        fetch('carrito.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `id_producto=${idProducto}&cantidad=${nuevaCantidad}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) window.location.reload();
          else alert('Error al actualizar cantidad');
        });
      };

      stepper.querySelector('.btn-restar').addEventListener('click', () => {
        let cantidad = parseInt(span.textContent);
        if (cantidad > 0) {
          cantidad--;
          span.textContent = cantidad;
          input.value = cantidad;
          actualizar(cantidad);
        }
      });

      stepper.querySelector('.btn-sumar').addEventListener('click', () => {
        let cantidad = parseInt(span.textContent);
        cantidad++;
        span.textContent = cantidad;
        input.value = cantidad;
        actualizar(cantidad);
      });
    });

    const inputVisual = document.getElementById('cliente_visual');
    const inputReal = document.getElementById('cliente_real');
    const dataList = document.getElementById('lista-clientes');

    inputVisual.addEventListener('input', () => {
      const opciones = dataList.options;
      let encontrado = false;

      for (let i = 0; i < opciones.length; i++) {
        if (opciones[i].value === inputVisual.value) {
          const id = opciones[i].dataset.id;
          inputReal.value = id || 0;
          encontrado = true;
          break;
        }
      }

      if (!encontrado) inputReal.value = 0;
    });
  });
</script>
</body>
</html>