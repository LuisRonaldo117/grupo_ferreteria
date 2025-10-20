<?php
require_once "conexion.php";
$db = Conexion::conectar();
$db->exec("SET lc_time_names = 'es_ES'");
$tipo = $_GET['tipo'] ?? '';
if (!$tipo) {
    // Retorna JSON vacío
    echo json_encode([
        'titulo' => '',
        'meses' => [],
        'series' => [],
        'etiquetaY' => '',
        'detalle' => []
    ]);
    exit;
}
switch($tipo) {
    case 'mensual':
        $q = $db->query("
            SELECT DATE_FORMAT(fecha, '%M %Y') AS mes, COUNT(id_factura) AS total
            FROM factura
            WHERE estado='completado'
            GROUP BY YEAR(fecha), MONTH(fecha)
            ORDER BY fecha ASC
        ");
        $detalle = $q->fetchAll(PDO::FETCH_ASSOC);
        $meses = array_column($detalle, 'mes');
        $totales = array_column($detalle, 'total');
        $series = [['name'=>'Ventas', 'data'=>array_map('intval', $totales)]];
        $titulo = "Ventas Totales por Mes";
        $etiquetaY = "Cantidad de Ventas";
    break;

    case 'clientes':
        $q = $db->query("
            SELECT CONCAT(p.nombres,' ',p.apellidos) AS Cliente, COUNT(f.id_factura) AS Compras, 
                   IFNULL(SUM(f.total), 0) AS Total_Bs
            FROM cliente c
            JOIN persona p ON c.id_persona = p.id_persona
            JOIN factura f ON f.id_cliente = c.id_cliente
            WHERE f.estado='completado'
            GROUP BY c.id_cliente
            ORDER BY Compras DESC
            LIMIT 5
        ");
        $detalle = $q->fetchAll(PDO::FETCH_ASSOC);
        $meses = array_column($detalle, 'Cliente');
        $totales = array_column($detalle, 'Compras');
        $series = [['name'=>'Compras', 'data'=>array_map('intval', $totales)]];
        $titulo = "Top 5 Clientes con Más Compras";
        $etiquetaY = "Número de Compras";
    break;

    case 'ingresos':
        $q = $db->query("
            SELECT DATE_FORMAT(fecha, '%M %Y') AS mes, SUM(total) AS Ingresos
            FROM factura
            WHERE estado='completado'
            GROUP BY YEAR(fecha), MONTH(fecha)
            ORDER BY fecha ASC
        ");
        $detalle = $q->fetchAll(PDO::FETCH_ASSOC);
        $meses = array_column($detalle, 'mes');
        $totales = array_column($detalle, 'Ingresos');
        $series = [['name'=>'Ingresos (Bs)', 'data'=>array_map('floatval', $totales)]];
        $titulo = "Ingresos Totales por Mes";
        $etiquetaY = "Monto Total (Bs)";
    break;

    case 'productos': // productos
        $top = $db->query("
            SELECT p.id_producto, p.nombre AS Producto, SUM(df.cantidad) AS Total_Vendido
            FROM detalle_factura df
            JOIN factura f ON df.id_factura = f.id_factura
            JOIN producto p ON df.id_producto = p.id_producto
            WHERE f.estado='completado'
            GROUP BY p.id_producto
            ORDER BY Total_Vendido DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        $detalle = $top;
        $ids = implode(',', array_column($top, 'id_producto'));

        $stmt = $db->query("
            SELECT p.nombre AS producto, DATE_FORMAT(f.fecha, '%M %Y') AS mes, SUM(df.cantidad) AS total_vendido
            FROM detalle_factura df
            JOIN factura f ON df.id_factura = f.id_factura
            JOIN producto p ON df.id_producto = p.id_producto
            WHERE f.estado='completado' AND p.id_producto IN ($ids)
            GROUP BY p.id_producto, YEAR(f.fecha), MONTH(f.fecha)
            ORDER BY f.fecha ASC
        ");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $meses = [];
        foreach($datos as $d){ if(!in_array($d['mes'], $meses)) $meses[]=$d['mes']; }

        $series = [];
        foreach($top as $t){ $series[$t['Producto']] = array_fill(0, count($meses), 0); }
        foreach($datos as $d){
            $index = array_search($d['mes'], $meses);
            $series[$d['producto']][$index] = (int)$d['total_vendido'];
        }

        $series_final = [];
        foreach($series as $n=>$d){ $series_final[]=['name'=>$n,'data'=>$d]; }

        $titulo = "Top 5 Productos Más Vendidos por Mes";
        $etiquetaY = "Unidades Vendidas";
        $series = $series_final;
    break;

    // 🔹 TOP 5 CLIENTES POR MES
case 'clientes_mensual':
    $query = $db->prepare("
        SELECT 
            DATE_FORMAT(f.fecha, '%M %Y') AS mes,
            CONCAT(pers.nombres, ' ', pers.apellidos) AS nombre,
            COUNT(f.id_factura) AS total
        FROM factura f
        JOIN cliente c ON f.id_cliente = c.id_cliente
        JOIN persona pers ON c.id_persona = pers.id_persona
        WHERE f.estado = 'completado'
        GROUP BY c.id_cliente, YEAR(f.fecha), MONTH(f.fecha)
        ORDER BY YEAR(f.fecha), MONTH(f.fecha), total DESC
    ");
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    // Extraemos los 5 clientes con más compras
    $clientes = array_slice(array_unique(array_column($rows, 'nombre')), 0, 5);

    $meses = array_values(array_unique(array_column($rows, 'mes')));
    $series = [];
    foreach ($clientes as $cli) {
        $serie = ['name' => $cli, 'data' => []];
        foreach ($meses as $mes) {
            $valor = 0;
            foreach ($rows as $r) {
                if ($r['nombre'] == $cli && $r['mes'] == $mes) {
                    $valor = (int)$r['total'];
                    break;
                }
            }
            $serie['data'][] = $valor;
        }
        $series[] = $serie;
    }

    // Asignar variables globales para que JSON final funcione
    $titulo = 'Top 5 Clientes con Más Compras por Mes';
    $etiquetaY = 'Número de Compras';
    $detalle = $rows;
break;




case 'empleados_ingresos':
    $q = $db->query("
        SELECT CONCAT(p.nombres, ' ', p.apellidos) AS Empleado, 
               COUNT(f.id_factura) AS Ventas, 
               SUM(f.total) AS Total_Vendido
        FROM factura f
        JOIN empleado e ON f.id_empleado = e.id_empleado
        JOIN persona p ON e.id_persona = p.id_persona
        WHERE f.estado = 'completado'
        GROUP BY e.id_empleado
        ORDER BY Total_Vendido DESC
        LIMIT 5
    ");
    $detalle = $q->fetchAll(PDO::FETCH_ASSOC);
    $meses = array_column($detalle, 'Empleado');
    $totales = array_column($detalle, 'Total_Vendido');
    $series = [['name'=>'Total Vendido (Bs)', 'data'=>array_map('floatval', $totales)]];
    $titulo = "Top 5 Empleados con Más Ventas";
    $etiquetaY = "Monto Total (Bs)";
break;


}

header('Content-Type: application/json');
echo json_encode([
    'titulo'=>$titulo,
    'meses'=>$meses,
    'series'=>$series,
    'etiquetaY'=>$etiquetaY,
    'detalle'=>$detalle
]);
