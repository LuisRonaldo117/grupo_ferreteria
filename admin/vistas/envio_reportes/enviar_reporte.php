<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

require __DIR__ . '/../../vendor/autoload.php';
include(__DIR__ . '/conexion.php');
$conn = Conexion::conectar();

// Validar selección
$seleccionados = $_POST['empleados'] ?? [];
if (empty($seleccionados)) {
    echo json_encode([
        'exito' => false,
        'mensaje' => '❌ Debes seleccionar al menos un empleado.'
    ]);
    exit;
}

$asunto = $_POST['asunto'] ?? 'Reporte Mensual';
$mes = date('m');
$anio = date('Y');
$meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
$mes_nombre = $meses[intval($mes)];

// Logo de empresa
$logoPath = __DIR__ . '/img/logo.png';
$logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';

// Cargar CSS externo
$cssPath = __DIR__ . '/css/reporte_pdf.css';
$css = file_exists($cssPath) ? file_get_contents($cssPath) : '';

try {
    // Obtener empleados
    if (in_array('all', $seleccionados)) {
        $sql = "SELECT e.id_empleado, e.salario, e.fecha_ingreso, p.nombres, p.apellidos, p.correo, d.nom_departamento, c.nombre_cargo
                FROM empleado e
                JOIN persona p ON e.id_persona = p.id_persona
                LEFT JOIN cargo_empleado c ON e.id_cargo = c.id_cargo
                LEFT JOIN departamento d ON p.id_departamento = d.id_departamento
                WHERE e.estado = 1";
    } else {
        $ids = implode(',', array_map('intval', $seleccionados));
        $sql = "SELECT e.id_empleado, e.salario, e.fecha_ingreso, p.nombres, p.apellidos, p.correo, d.nom_departamento, c.nombre_cargo
                FROM empleado e
                JOIN persona p ON e.id_persona = p.id_persona
                LEFT JOIN cargo_empleado c ON e.id_cargo = c.id_cargo
                LEFT JOIN departamento d ON p.id_departamento = d.id_departamento
                WHERE e.id_empleado IN ($ids)";
    }

    $empleados = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    if (!$empleados) throw new Exception("No se encontraron empleados seleccionados.");

    $exitos = 0;

    foreach ($empleados as $emp) {
        // Asistencias
        $stmt = $conn->prepare("SELECT fecha, hora_entrada, hora_salida 
                                FROM asistencia 
                                WHERE id_empleado = :id AND MONTH(fecha)=:mes AND YEAR(fecha)=:anio
                                ORDER BY fecha ASC");
        $stmt->execute([':id'=>$emp['id_empleado'], ':mes'=>$mes, ':anio'=>$anio]);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ventas
        $ventas = $conn->prepare("SELECT SUM(total) as total_ventas, COUNT(id_factura) as total_facturas
                                  FROM factura
                                  WHERE id_empleado=:id AND MONTH(fecha)=:mes AND YEAR(fecha)=:anio AND estado='completado'");
        $ventas->execute([':id'=>$emp['id_empleado'], ':mes'=>$mes, ':anio'=>$anio]);
        $ventas_mes = $ventas->fetch(PDO::FETCH_ASSOC);
// Fecha y hora actual (zona horaria Bolivia)
date_default_timezone_set('America/La_Paz');
$fecha_envio = date('d/m/Y');
$hora_envio = date('H:i:s');
        // HTML con CSS cargado desde archivo
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Reporte Mensual</title>
            <style>' . $css . '</style>
        </head>
        <body>
    <div class="header">
        <div class="header-inner">
            <div class="header-info">
                <h1>Reporte Mensual de Empleado</h1>
                <div class="fecha">' . $mes_nombre . ' ' . $anio . '</div>
                <div class="envio">Enviado el ' . $fecha_envio . ' a las ' . $hora_envio . '</div>
            </div>
        <!-- <img src="' . $logoSrc . '" class="logo" alt="Logo Empresa"> -->    
        </div>
    </div>

            <div class="profile">
    <div class="profile-info">
        <p><strong>Nombre:</strong> ' . htmlspecialchars($emp['nombres'].' '.$emp['apellidos']) . '</p>
        <p><strong>Cargo:</strong> ' . htmlspecialchars($emp['nombre_cargo']) . '</p>
        <p><strong>Departamento:</strong> ' . htmlspecialchars($emp['nom_departamento']) . '</p>
        <p><strong>Salario mensual:</strong> Bs.- ' . number_format($emp['salario'],2) . '</p>
        <p><strong>Fecha de ingreso:</strong> ' . htmlspecialchars($emp['fecha_ingreso']) . '</p>
    </div>

            </div>

            <div class="section-title">Asistencias</div>
            <table>
                <tr><th>Fecha</th><th>Entrada</th><th>Salida</th></tr>';
        $contador = 0;
        foreach ($asistencias as $a) {
            $class = ($contador++ % 2 == 0) ? 'table-row-even' : '';
            $html .= "<tr class='$class'><td>{$a['fecha']}</td><td>{$a['hora_entrada']}</td><td>{$a['hora_salida']}</td></tr>";
        }
        $html .= '</table>';

        $html .= '<div class="section-title">Ventas del Mes</div>
            <table>
                <tr><th>Total Facturas</th><th>Total Ventas (Bs)</th></tr>
                <tr><td>'.($ventas_mes['total_facturas'] ?? 0).'</td><td>'.number_format($ventas_mes['total_ventas'] ?? 0,2).'</td></tr>
            </table>';

        $html .= '<div class="footer">© '.$anio.' Grupo Ferretería - Profesional y confiable</div>
        </body>
        </html>';

        // Generar PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfFile = __DIR__.'/reporte_'.$emp['id_empleado'].'.pdf';
        file_put_contents($pdfFile, $dompdf->output());

        // Enviar correo
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->SMTPAuth=true;
            $mail->Username='luisronaldomamanimayta01@gmail.com';
            $mail->Password='bcmhxdeezpzcujjy';
            $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port=587;
            $mail->CharSet='UTF-8';

            $mail->setFrom('luisronaldomamanimayta01@gmail.com','Sistema de Empleados');
            $mail->addAddress($emp['correo'],$emp['nombres'].' '.$emp['apellidos']);
            $mail->isHTML(true);
            $mail->Subject=$asunto;
            $mail->Body='<p>Hola <strong>'.$emp['nombres'].'</strong>,</p><p>Adjunto tu reporte mensual.</p>';
            $mail->addAttachment($pdfFile);
            $mail->send();
            $exitos++;
        } catch (Exception $e) {
            // Ignorar errores individuales
        } finally {
            if (file_exists($pdfFile)) unlink($pdfFile);
        }
    }

    echo json_encode([
        'exito' => true,
        'mensaje' => "✅ Reportes enviados correctamente a $exitos empleados."
    ]);

} catch (Exception $e) {
    echo json_encode([
        'exito' => false,
        'mensaje' => "❌ " . $e->getMessage()
    ]);
}
exit;
?>
