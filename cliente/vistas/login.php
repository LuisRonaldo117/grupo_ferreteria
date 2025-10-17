<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Iniciar Sesión</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form action="<?php echo BASE_URL; ?>cliente/?ruta=procesar-login" method="POST">
                            <div class="form-group mb-3">
                                <label for="email">Email o Usuario:</label>
                                <input type="text" id="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="password">Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Ingresar</button>
                            </div>
                        </form>
                        
                        <div class="mt-3 text-center">
                            ¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>cliente/?ruta=registro">Regístrate aquí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>