<?php
if (!isset($facturas)) {
    header('Location: ' . BASE_URL . 'cliente/?ruta=carrito');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Compras | Ferretería</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --azul-oscuro: #0a1f3d;
            --azul-medio: #1a3b6a;
            --azul-claro: #2a5a8a;
            --azul-hielo: #e6f2fa;
            --dorado: #d4af37;
            --gris-claro: #f8f9fa;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Contenedor principal */
        .contenedor-compras {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(10, 31, 61, 0.1);
            padding: 2.5rem;
            margin-top: 2rem;
            margin-bottom: 3rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        /* Encabezado */
        .encabezado-compras {
            border-bottom: 2px solid var(--azul-hielo);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .encabezado-compras h2 {
            color: var(--azul-oscuro);
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        
        .encabezado-compras h2 i {
            color: var(--dorado);
            margin-right: 12px;
            font-size: 1.8rem;
        }
        
        /* Tabla personalizada */
        .tabla-compras {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: var(--azul-hielo);
            --bs-table-hover-bg: rgba(26, 59, 106, 0.05);
            margin-bottom: 2rem;
        }
        
        .tabla-compras thead th {
            background-color: var(--azul-medio);
            color: white;
            font-weight: 600;
            padding: 1rem;
            border: none;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .tabla-compras tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            border-color: var(--azul-hielo);
        }
        
        /* Badges de estado */
        .badge-estado {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            border-radius: 50px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid transparent;
        }
        
        .badge-completado {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-color: #28a745;
        }
        
        .badge-pendiente {
            background-color: rgba(255, 193, 7, 0.1);
            color: #d4a017;
            border-color: #ffc107;
        }
        
        .badge-cancelado {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-color: #dc3545;
        }
        
        /* Botones */
        .btn-descargar {
            background-color: transparent;
            border: 1px solid var(--azul-claro);
            color: var(--azul-claro);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-descargar:hover {
            background-color: var(--azul-claro);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(26, 59, 106, 0.15);
        }
        
        .btn-descargar i {
            margin-right: 6px;
        }
        
        /* Mensaje sin compras */
        .sin-compras {
            background-color: var(--azul-hielo);
            border: 2px dashed var(--azul-claro);
            border-radius: 10px;
            padding: 3rem 2rem;
            text-align: center;
            margin: 2rem 0;
        }
        
        .sin-compras i {
            font-size: 3rem;
            color: var(--azul-claro);
            opacity: 0.7;
            margin-bottom: 1.5rem;
        }
        
        .sin-compras h4 {
            color: var(--azul-oscuro);
            font-weight: 600;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .contenedor-compras {
                padding: 1.5rem;
            }
            
            .tabla-compras thead {
                display: none;
            }
            
            .tabla-compras tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border: 1px solid var(--azul-hielo);
                border-radius: 8px;
                padding: 1rem;
                box-shadow: 0 2px 8px rgba(10, 31, 61, 0.05);
            }
            
            .tabla-compras tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border: none;
                border-bottom: 1px solid var(--azul-hielo);
            }
            
            .tabla-compras tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--azul-oscuro);
                margin-right: 1rem;
            }
            
            .tabla-compras tbody td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="contenedor-compras">
            <div class="encabezado-compras">
                <h2><i class="fas fa-file-invoice"></i> Mis Compras</h2>
            </div>
            
            <?php if (empty($facturas)): ?>
                <div class="sin-compras">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <h4>No tienes compras registradas</h4>
                    <p class="text-muted">Aún no has realizado ninguna compra en nuestra tienda</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table tabla-compras">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Fecha</th>
                                <th>Productos</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturas as $factura): ?>
                                <tr>
                                    <td data-label="Factura">
                                        <strong>#<?= str_pad($factura['id_factura'], 6, '0', STR_PAD_LEFT) ?></strong>
                                    </td>
                                    <td data-label="Fecha"><?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?></td>
                                    <td data-label="Productos"><?= $factura['cantidad_productos'] ?></td>
                                    <td data-label="Total">
                                        <strong>Bs. <?= number_format($factura['total'], 2) ?></strong>
                                    </td>
                                    <td data-label="Estado">
                                        <span class="badge badge-estado badge-<?= $factura['estado'] ?>">
                                            <?= ucfirst($factura['estado']) ?>
                                        </span>
                                    </td>
                                    <td data-label="Acciones">
                                        <a href="<?= BASE_URL ?>cliente/?ruta=generar_factura&id=<?= $factura['id_factura'] ?>" 
                                           class="btn btn-descargar">
                                            <i class="fas fa-file-pdf"></i> Descargar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>cliente/?ruta=carrito" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Volver al Carrito
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Aplicar clases de estado a los badges
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.badge-estado').forEach(badge => {
                const estado = badge.textContent.trim().toLowerCase();
                badge.classList.add('badge-' + estado);
            });
        });
    </script>
</body>
</html>