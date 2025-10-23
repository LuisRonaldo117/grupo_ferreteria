<?php
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Opciones para Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // permite cargar imágenes desde http
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);

// URL del logo
$logoUrl = 'http://localhost/grupo_ferreteria/admin/vistas/envio_reportes/img/logo.png';

// HTML del PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; margin: 30px; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .logo { width:100px; }
    h1 { margin:0; }
</style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>Reporte de Prueba</h1>
        </div>
        <div class="header-right">
            <img src="' . $logoUrl . '" class="logo" alt="Logo">
        </div>
    </div>
    <p>Este es un PDF de prueba para verificar que el logo se muestra correctamente.</p>
</body>
</html>';

// Cargar HTML y generar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Mostrar PDF en el navegador
$dompdf->stream('prueba.pdf', ['Attachment' => false]);
