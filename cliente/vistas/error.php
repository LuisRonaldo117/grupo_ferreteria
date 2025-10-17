<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="display-1">404</h1>
                <p class="lead">Página no encontrada</p>
                <p>La página que estás buscando no existe o ha ocurrido un error.</p>
                <a href="<?= BASE_URL ?>cliente/?ruta=inicio" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>