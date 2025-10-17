<?php
include("../modelos/conexion.php");
$conn = Conexion::conectar();

if (isset($_POST['id_pedido'])) {
    $id = filter_var($_POST['id_pedido'], FILTER_VALIDATE_INT);
    if (!$id) {
        echo "<p>ID de pedido inválido.</p>";
        exit;
    }

    // Datos del cliente, empleado, sucursal y pedido
    $sqlPedido = "SELECT p.fecha_pedido, p.total, p.estado, p.tipo_pago,
                         CONCAT(cpers.nombres, ' ', cpers.apellidos) AS nombre_cliente,
                         cpers.ci AS ci_cliente,
                         CONCAT(epers.nombres, ' ', epers.apellidos) AS nombre_empleado,
                         s.nombre AS nombre_sucursal
                  FROM pedido p
                  LEFT JOIN cliente c ON p.id_cliente = c.id_cliente
                  LEFT JOIN persona cpers ON c.id_persona = cpers.id_persona
                  LEFT JOIN empleado e ON p.id_empleado = e.id_empleado
                  LEFT JOIN persona epers ON e.id_persona = epers.id_persona
                  JOIN sucursal s ON p.id_sucursal = s.id_sucursal
                  WHERE p.id_pedido = ?";

    $stmt = $conn->prepare($sqlPedido);
    $stmt->execute([$id]);
    $datosPedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datosPedido) {
        echo "<p>No se encontró el pedido.</p>";
        exit;
    }

    // Simulamos los detalles porque no se proporcionó una tabla detalle_pedido
    $detalles = [
        ["id" => 1, "producto" => "Martillo", "cantidad" => 2, "precio" => 25.00],
        ["id" => 2, "producto" => "Taladro", "cantidad" => 1, "precio" => 180.00],
    ];

    $costoEnvio = 15.00;
    $totalPedido = 0;

    foreach ($detalles as &$item) {
        $item['subtotal'] = $item['cantidad'] * $item['precio'];
        $totalPedido += $item['subtotal'];
    }

?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.factura-box {
    background: #ffffff;
    max-width: 900px;
    margin: 30px auto;
    padding: 25px 35px;
    border-radius: 15px;
    box-shadow: 0 0 18px rgba(0, 0, 0, 0.15);
}


.factura-titulo {
    text-align: center;
    font-size: 2.2rem;
    font-weight: bold;
    color: #0d6efd;
    margin-bottom: 25px;
}

.info-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
    font-size: 0.95rem;
    color: #333;
    background-color: #f1f3f5;
    border-radius: 8px;
    padding: 20px;
}

.info-cliente,
.info-fecha-ci {
    width: 48%;
    line-height: 1.8;
}

.fecha {
    font-weight: bold;
    color: #333;
}

.ci {
    font-size: 0.9rem;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 0.95rem;
    color: #333;
}

table th, table td {
    border: 1px solid #dee2e6;
    padding: 12px;
    text-align: center;
}

table th {
    background-color: #0d6efd;
    color: white;
    font-weight: bold;
}

tfoot tr.total th {
    background-color: #dc3545;
    color: white;
    font-weight: bold;
}

tfoot tr.envio-verde th {
    background-color: #d4edda;
    color: #155724;
    font-weight: bold;
}

.acciones {
    text-align: center;
    margin-top: 30px;
}

.acciones button {
    margin: 8px;
    padding: 10px 25px;
    border: none;
    border-radius: 8px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.btn-info {
    background-color: #0d6efd;
    color: white;
}
.btn-info:hover {
    background-color: #0b5ed7;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}
.btn-danger:hover {
    background-color: #bb2d3b;
}

.boton-rojo {
    background-color: #6c757d;
    color: white;
}
.boton-rojo:hover {
    background-color: #5a6268;
}

@media print {
    .no-print {
        display: none !important;
    }
}

table, tr, td, th {
    page-break-inside: avoid !important;
}
</style>


<div class='factura-box'>
    <div class='factura-titulo'>FACTURA DE PEDIDO Nº <?= str_pad($id, 6, '0', STR_PAD_LEFT) ?></div>
    <div class="info-container">
        <div class="info-cliente">
            <strong>FERRETERÍA CENTRAL</strong><br>
            <?= htmlspecialchars($datosPedido['nombre_sucursal']) ?><br>
            <strong>Cliente:</strong> <?= htmlspecialchars($datosPedido['nombre_cliente']) ?><br>
            <strong>CI/NIT:</strong> <?= htmlspecialchars($datosPedido['ci_cliente']) ?>
        </div>
        <div class="info-fecha-ci">
            <span class="fecha">Fecha: <?= htmlspecialchars($datosPedido['fecha_pedido']) ?></span><br>
            <span class="ci"><strong>Empleado/Responsable:</strong> <?= htmlspecialchars($datosPedido['nombre_empleado'] ?? 'N/A') ?></span><br>
            <span class="ci"><strong>Estado:</strong> <?= htmlspecialchars($datosPedido['estado']) ?></span><br>
            <span class="ci"><strong>Método:</strong> <?= htmlspecialchars($datosPedido['tipo_pago']) ?></span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['producto']) ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td><?= number_format($item['precio'], 2, '.', ',') ?></td>
                <td><?= number_format($item['subtotal'], 2, '.', ',') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="envio-verde">
                <th colspan="4" style="text-align:right">Precio de Envío:</th>
                <th><?= number_format($costoEnvio, 2, '.', ',') ?></th>
            </tr>
            <tr class="total">
                <th colspan="4" style="text-align:right">TOTAL:</th>
                <th><?= number_format($totalPedido + $costoEnvio, 2, '.', ',') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class='acciones no-print'>
        <button id='btnImprimir' class='btn btn-info'><i class='fas fa-print'></i> Imprimir</button>
        <button id='btnDescargarPDF' class='btn btn-danger'><i class='fas fa-file-pdf'></i> PDF</button>
        <button type='button' id='btnVolverAtrasForm' class='boton-rojo'><i class='fas fa-arrow-left'></i> Volver Atrás</button>
    </div>
</div>


<?php
} else {
    echo "<p>ID de pedido no proporcionado.</p>";
}
?>

<!-- html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
// Botón imprimir
document.getElementById("btnImprimir").addEventListener("click", function () {
    window.print();
});

// Botón PDF mejorado
document.getElementById("btnDescargarPDF").addEventListener("click", function () {
    const original = document.querySelector(".factura-box");
    const clone = original.cloneNode(true);

    // Elimina botones no imprimibles
    clone.querySelectorAll(".no-print").forEach(el => el.remove());

    // Contenedor temporal para evitar conflictos visuales
    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    tempContainer.appendChild(clone);
    document.body.appendChild(tempContainer);

    const opt = {
        margin: [-10, 10, 10, 10], // en mm
        filename: 'factura_<?php echo str_pad($id, 6, "0", STR_PAD_LEFT); ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
            scale: 3,
            scrollY: 0
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        },
        pagebreak: {
            mode: ['avoid-all', 'css', 'legacy']
        }
    };

    html2pdf().set(opt).from(clone).save().then(() => {
        document.body.removeChild(tempContainer);
    });
});
</script>

<script>
// Botón volver
document.getElementById('btnVolverAtrasForm').addEventListener('click', function () {
    $('#formularioDetallesPedido').slideUp().empty();
    $('#filtrosPedidos').fadeIn();
    $('#contenedorTablaPedido').fadeIn();
    $('#btnVerFacturas').fadeIn();
});
</script>
