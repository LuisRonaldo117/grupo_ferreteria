<?php
require_once "conexion.php";

$reporte = $_GET['reporte'] ?? 'venta';

try {
    $db = Conexion::conectar();
    $db->exec("SET lc_time_names = 'es_ES'");

    switch ($reporte) {

        // 1️⃣ Clientes por departamento
        case 'clientes':
            $sql = "SELECT d.nom_departamento AS categoria, COUNT(*) AS valor
                    FROM persona p
                    JOIN departamento d ON p.id_departamento = d.id_departamento
                    JOIN cliente c ON c.id_persona = p.id_persona
                    GROUP BY d.nom_departamento
                    ORDER BY valor DESC";
            break;

        // 2️⃣ Reclamos por cliente
        case 'reclamos':
            $sql = "SELECT c.usuario AS categoria, COUNT(r.id_reclamo) AS valor
                    FROM reclamos r
                    JOIN cliente c ON r.id_cliente = c.id_cliente
                    GROUP BY c.usuario
                    ORDER BY valor DESC";
            break;

        // 3️⃣ Tipo de venta (virtual/presencial)
        case 'venta':
            $sql = "SELECT f.tipo_venta AS categoria, COUNT(*) AS valor
                    FROM factura f
                    GROUP BY f.tipo_venta
                    ORDER BY valor DESC";
            break;

        // 4️⃣ Cargo de empleados
        case 'cargo':
            $sql = "SELECT c.nombre_cargo AS categoria, COUNT(*) AS valor
                    FROM empleado e
                    JOIN cargo_empleado c ON e.id_cargo = c.id_cargo
                    GROUP BY c.nombre_cargo
                    ORDER BY valor DESC";
            break;

        // 5️⃣ Productos más vendidos
        case 'productos':
            $sql = "SELECT p.nombre AS categoria, SUM(df.cantidad) AS valor
                    FROM detalle_factura df
                    JOIN producto p ON df.id_producto = p.id_producto
                    JOIN factura f ON df.id_factura = f.id_factura
                    WHERE f.estado = 'completado'
                    GROUP BY p.nombre
                    ORDER BY valor DESC
                    LIMIT 10";
            break;
case 'productos_stok':
    $sql = "SELECT p.nombre AS categoria, p.stock AS valor, c.nombre_categoria AS subcategoria, p.min_stock
            FROM producto p
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
            ORDER BY p.stock ASC
            LIMIT 20";
    break;

        // 6️⃣ Ventas por empleado
        case 'ventas_empleado':
            $sql = "SELECT CONCAT(p.nombres,' ',p.apellidos) AS categoria,
                           COUNT(f.id_factura) AS valor
                    FROM empleado e
                    JOIN persona p ON e.id_persona = p.id_persona
                    LEFT JOIN factura f ON e.id_empleado = f.id_empleado AND f.estado = 'completado'
                    GROUP BY e.id_empleado
                    ORDER BY valor DESC";
            break;

        // 7️⃣ Ventas por cliente
        case 'ventas_cliente':
            $sql = "SELECT CONCAT(p.nombres,' ',p.apellidos) AS categoria,
                           COUNT(f.id_factura) AS valor
                    FROM cliente c
                    JOIN persona p ON c.id_persona = p.id_persona
                    LEFT JOIN factura f ON c.id_cliente = f.id_cliente AND f.estado = 'completado'
                    GROUP BY c.id_cliente
                    ORDER BY valor DESC
                    LIMIT 10";
            break;

        // 8️⃣ Ingresos por mes
        case 'ingresos_mes':
            $sql = "SELECT DATE_FORMAT(f.fecha, '%M %Y') AS categoria, IFNULL(SUM(f.total), 0) AS valor
                    FROM factura f
                    WHERE f.estado = 'completado'
                    GROUP BY YEAR(f.fecha), MONTH(f.fecha)
                    ORDER BY f.fecha ASC";
            break;

        // 9️⃣ Clientes por género
        case 'genero':
            $sql = "SELECT p.genero AS categoria, COUNT(*) AS valor
                    FROM persona p
                    JOIN cliente c ON c.id_persona = p.id_persona
                    GROUP BY p.genero";
            break;

        // 🔟 Productos por categoría
        case 'productos_categoria':
            $sql = "SELECT cat.nombre_categoria AS categoria, COUNT(p.id_producto) AS valor
                    FROM producto p
                    JOIN categoria cat ON p.id_categoria = cat.id_categoria
                    GROUP BY cat.nombre_categoria
                    ORDER BY valor DESC";
            break;

        // 11️⃣ Ventas por método de pago
        case 'metodo_pago':
            $sql = "SELECT mp.nombre_metodo AS categoria, COUNT(*) AS valor
                    FROM factura f
                    JOIN metodo_pago mp ON f.id_metodo = mp.id_metodo
                    WHERE f.estado = 'completado'
                    GROUP BY mp.nombre_metodo";
            break;

        // 12️⃣ Pedidos por estado
        case 'pedido_estado':
            $sql = "SELECT estado AS categoria, COUNT(*) AS valor
                    FROM pedido
                    GROUP BY estado";
            break;

        // 13️⃣ Empleados por sucursal
        case 'empleado_sucursal':
            $sql = "SELECT s.nombre AS categoria, COUNT(*) AS valor
                    FROM empleado e
                    JOIN sucursal s ON e.id_sucursal = s.id_sucursal
                    GROUP BY s.nombre";
            break;

        // 14️⃣ Reclamos por mes
        case 'reclamos_mes':
            $sql = "SELECT DATE_FORMAT(r.fecha_reclamo, '%M %Y') AS categoria, COUNT(*) AS valor
                    FROM reclamos r
                    GROUP BY YEAR(r.fecha_reclamo), MONTH(r.fecha_reclamo)
                    ORDER BY r.fecha_reclamo";
            break;


// 🔹 Proveedor con más productos comprados
case 'proveedor_mas_productos':
    $sql = "SELECT 
                pr.nombre AS categoria,
                COUNT(DISTINCT pp.id_producto) AS valor
            FROM producto_proveedor pp
            JOIN proveedores pr ON pp.id_proveedor = pr.id_proveedor
            JOIN producto p ON pp.id_producto = p.id_producto -- solo productos existentes
            GROUP BY pr.id_proveedor, pr.nombre
            ORDER BY valor DESC";
    break;


        default:
            $sql = "";
            break;
    }

    $data = [];
    if ($sql) {
        $stmt = $db->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = [
                    "category" => $row['categoria'],
                    "value" => (float)$row['valor'],
                ];
            }
        } else {
            error_log("Error SQL: " . implode(" | ", $stmt->errorInfo()));
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

} catch (PDOException $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([]);
    error_log("Error PDO: " . $e->getMessage());
}
?>
