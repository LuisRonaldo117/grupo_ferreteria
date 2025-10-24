<?php
include("../modelos/conexion.php");
$conn = Conexion::conectar();

if (isset($_POST['id_factura'])) {
    $id = filter_var($_POST['id_factura'], FILTER_VALIDATE_INT);
    if (!$id) {
        echo "<p>ID de factura inválido.</p>";
        exit;
    }

    // Datos principales
    $sqlFactura = "SELECT f.fecha, f.total, f.tipo_venta, f.estado,
                          CONCAT(pc.nombres, ' ', pc.apellidos) AS nombre_cliente,
                          pc.ci AS ci_cliente,
                          CONCAT(pe.nombres, ' ', pe.apellidos) AS nombre_empleado,
                          mp.nombre_metodo AS metodo_pago
                   FROM factura f
                   LEFT JOIN cliente c ON f.id_cliente = c.id_cliente
                   LEFT JOIN persona pc ON c.id_persona = pc.id_persona
                   LEFT JOIN empleado e ON f.id_empleado = e.id_empleado
                   LEFT JOIN persona pe ON e.id_persona = pe.id_persona
                   JOIN metodo_pago mp ON f.id_metodo = mp.id_metodo
                   WHERE f.id_factura = ?";
    $stmtFactura = $conn->prepare($sqlFactura);
    $stmtFactura->execute([$id]);
    $datosFactura = $stmtFactura->fetch(PDO::FETCH_ASSOC);

    if (!$datosFactura) {
        echo "<p>No se encontró la factura.</p>";
        exit;
    }

    // Detalles de productos
    $sqlDetalle = "SELECT d.id_detalle, p.nombre AS nombre_producto, d.cantidad, d.precio_unitario, d.subtotal
                   FROM detalle_factura d
                   JOIN producto p ON d.id_producto = p.id_producto
                   WHERE d.id_factura = ?";
    $stmtDetalle = $conn->prepare($sqlDetalle);
    $stmtDetalle->execute([$id]);
    $detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

    $mostrarEnvio = ($datosFactura['tipo_venta'] === 'virtual');
    $costoEnvio = $mostrarEnvio ? 15.00 : 0;
    $totalFactura = $datosFactura['total'] ;
?>
<style>
    body {
        margin: 0; padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f3f4f6;
    }
    .factura-box {
        background: #fff;
        max-width: 900px;
        margin: 30px auto;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .factura-titulo {
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        color: #0d6efd;
        margin-bottom: 20px;
    }
    .info-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
        font-size: 0.95rem;
        color: #333;
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
        border: 1px solid #ccc;
        padding: 10px;
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
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-info { background-color: #0d6efd; color: white; }
    .btn-info:hover { background-color: #0b5ed7; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-danger:hover { background-color: #bb2d3b; }
    .boton-rojo { background-color: #6c757d; color: white; }
    .boton-rojo:hover { background-color: #5a6268; }
    @media print {
        .no-print { display: none !important; }
    }
</style>

<div class="factura-box">
    <div class="factura-titulo">FACTURA DE VENTA N° <?= str_pad($id, 6, '0', STR_PAD_LEFT) ?></div>

    <div class="info-container">
        <div class="info-cliente">
            <strong>FERRETERÍA CENTRAL</strong><br>
            Av. 16 de Julio #789, El Prado, La Paz, Bolivia<br>
            <strong>Cliente:</strong> <?= htmlspecialchars($datosFactura['nombre_cliente'] ?? '---') ?><br>
            <strong>CI:</strong> <?= htmlspecialchars($datosFactura['ci_cliente'] ?? '---') ?>
        </div>
        <div class="info-fecha-ci">
            <span class="fecha">Fecha: <?= htmlspecialchars($datosFactura['fecha']) ?></span><br>
            <span class="ci"><strong>Empleado:</strong> <?= htmlspecialchars($datosFactura['nombre_empleado'] ?? '---') ?></span><br>
            <span class="ci"><strong>Estado:</strong> <?= htmlspecialchars($datosFactura['estado']) ?></span><br>
            <span class="ci"><strong>Método:</strong> <?= htmlspecialchars($datosFactura['metodo_pago']) ?></span>
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
            <?php foreach ($detalles as $d): ?>
                <tr>
                    <td><?= $d['id_detalle'] ?></td>
                    <td><?= htmlspecialchars($d['nombre_producto']) ?></td>
                    <td><?= $d['cantidad'] ?></td>
                    <td><?= number_format($d['precio_unitario'], 2, '.', ',') ?></td>
                    <td><?= number_format($d['subtotal'], 2, '.', ',') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <?php if ($mostrarEnvio): ?>
            <tr class="envio-verde">
                <th colspan="4" style="text-align:right">Precio de Envío:</th>
                <th><?= number_format($costoEnvio, 2, '.', ',') ?></th>
            </tr>
            <?php endif; ?>
            <tr class="total">
                <th colspan="4" style="text-align:right">TOTAL:</th>
                <th><?= number_format($totalFactura, 2, '.', ',') ?></th>
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
    echo "<p>ID de factura no proporcionado.</p>";
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
    $('#formularioDetallesFactura').slideUp().empty();
    $('#filtrosVentas').fadeIn();
    $('#contenedorTablaFactura').fadeIn();
    $('#btnVerFacturas').fadeIn();
});
</script>
