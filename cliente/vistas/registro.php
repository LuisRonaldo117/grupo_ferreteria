<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --border-radius: 0.25rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 15px;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            background: white;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: 600;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        h4 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
            font-weight: 600;
        }
        
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        label {
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: var(--border-radius);
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }
        
        .btn-primary {
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .d-grid {
            display: grid;
        }
        
        .gap-2 {
            gap: 1rem;
        }
        
        .alert {
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: var(--border-radius);
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-3 {
            margin-top: 1rem;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        .mt-5 {
            margin-top: 3rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        
        .col-md-6, .col-md-8, .col-md-12 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        
        @media (min-width: 768px) {
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
            
            .col-md-8 {
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }
        }
        
        a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .justify-content-center {
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Registro de Nuevo Cliente</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form action="<?php echo BASE_URL; ?>cliente/?ruta=procesar-registro" method="POST">
                            <h4 class="mb-3">Datos Personales</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Nombres:</label>
                                    <input type="text" name="nombres" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Apellidos:</label>
                                    <input type="text" name="apellidos" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>CI:</label>
                                    <input type="text" name="ci" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Fecha de Nacimiento:</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label>Dirección:</label>
                                <input type="text" name="direccion" class="form-control">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Teléfono:</label>
                                    <input type="text" name="telefono" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Correo Electrónico:</label>
                                    <input type="email" name="correo" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Género:</label>
                                    <select name="genero" class="form-control">
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Departamento:</label>
                                    <select name="id_departamento" class="form-control">
                                        <?php foreach ($departamentos as $dep): ?>
                                            <option value="<?= $dep['id_departamento'] ?>">
                                                <?= $dep['nom_departamento'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <h4 class="mb-3 mt-4">Datos de Cuenta</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Usuario:</label>
                                    <input type="text" name="usuario" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Contraseña:</label>
                                    <input type="password" name="contrasena" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label>Confirmar Contraseña:</label>
                                <input type="password" name="confirmar_contrasena" class="form-control" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </div>
                        </form>
                        
                        <div class="mt-3 text-center">
                            ¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>cliente/?ruta=login">Inicia sesión aquí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>