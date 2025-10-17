<?php
include("../modelos/conexion.php");
$conn = Conexion::conectar();

if (isset($_POST['id_factura'])) {
    $id = filter_var($_POST['id_factura'], FILTER_VALIDATE_INT);
    if (!$id) {
        echo "<p>ID de factura inválido.</p>";
        exit;
    }

    $sqlCliente = "SELECT f.fecha, 
                          CONCAT(IFNULL(p.nombres, 'Cliente'), ' ', IFNULL(p.apellidos, 'Presencial')) AS nombre_cliente, 
                          IFNULL(p.ci, '---') AS ci_cliente
                   FROM factura f
                   LEFT JOIN cliente c ON f.id_cliente = c.id_cliente
                   LEFT JOIN persona p ON c.id_persona = p.id_persona
                   WHERE f.id_factura = ?";
    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->execute([$id]);
    $datosCliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if (!$datosCliente) {
        echo "<p>No se encontró la factura.</p>";
        exit;
    }

    $fechaFactura = $datosCliente['fecha'];
    $nombreCliente = $datosCliente['nombre_cliente'];
    $ciCliente = $datosCliente['ci_cliente'];

    $sqlDetalle = "SELECT d.id_detalle, p.nombre AS nombre_producto, d.cantidad, d.precio_unitario, d.subtotal
                   FROM detalle_factura d
                   JOIN producto p ON d.id_producto = p.id_producto
                   WHERE d.id_factura = ?";
    $stmt = $conn->prepare($sqlDetalle);
    $stmt->execute([$id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($detalles) {
?>
<style>
    body {
        margin: 0;
        padding: 0;
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
    .info-cliente {
        width: 50%;
        line-height: 1.6;
    }
    .info-fecha-ci {
        width: 50%;
        text-align: right;
        line-height: 1.6;
    }
    .fecha {
        font-weight: bold;
        display: block;
        margin-bottom: 4px;
    }
    .ci {
        display: block;
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
    tfoot th {
        background-color: #dc3545;
        color: white;
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
    <div class="info-container">
        <div class="info-cliente">
            <strong>FERRETERÍA CENTRAL</strong><br>
            Av. 16 de Julio #789, El Prado, La Paz, Bolivia<br>
            <strong>Cliente:</strong> <?= htmlspecialchars($nombreCliente) ?>
        </div>
        <div class="info-fecha-ci">
            <span class="fecha">Fecha: <?= htmlspecialchars($fechaFactura) ?></span>
            <span class="ci">CI/NIT: <?= htmlspecialchars($ciCliente) ?></span>
        </div>
    </div>

    <div class='factura-titulo'>FACTURA DE VENTA N° <?= str_pad($id, 6, '0', STR_PAD_LEFT) ?></div>

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
            <?php
            $totalFactura = 0;
            foreach ($detalles as $d):
                $totalFactura += $d['subtotal'];
            ?>
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
            <tr>
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

<script>
    document.getElementById('btnImprimir').addEventListener('click', function() {
        window.print();
    });
    document.getElementById('btnVolverAtrasForm').addEventListener('click', function() {
        window.history.back();
    });
</script>

<?php
    } else {
        echo "<p>No hay detalles para esta factura.</p>";
    }
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
