<?php
session_start();

// Evita caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica sesión
if (!isset($_SESSION['empleado_usuario'])) {
    header('Location: ../logout.php');
    exit;
}

include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Clientes</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/clientes.css">
</head>
<body>
    <div class="clientes-wrapper">
        <div class="clientes-card">
            <header>
                <h1>📋 Listado de Clientes</h1>
            </header>

            <div class="acciones">
                <a href="../index.php" class="btn-volver">Volver al Inicio</a>
            </div>

            <!-- 🔍 Buscador fuera del contenedor de la tabla -->
            <div class="buscador-clientes">
                <input type="text" id="filtroCliente" placeholder="🔍 Buscar cliente...">
            </div>

            <div class="tabla-contenedor">
                <table id="tablaClientes" class="display nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Usuario</th>
                            <th>Fecha de creación</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Departamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                    c.id_cliente,
                                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
                                    c.usuario,
                                    c.fecha_creacion,
                                    p.direccion,
                                    p.telefono,
                                    d.nom_departamento
                                FROM cliente c
                                INNER JOIN persona p ON c.id_persona = p.id_persona
                                INNER JOIN departamento d ON p.id_departamento = d.id_departamento
                                ORDER BY c.id_cliente";
                        $stmt = $conexion->prepare($sql);
                        $stmt->execute();
                        $clientes = $stmt->get_result();

                        while ($row = $clientes->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id_cliente']}</td>
                                    <td>{$row['nombre_completo']}</td>
                                    <td>{$row['usuario']}</td>
                                    <td>{$row['fecha_creacion']}</td>
                                    <td>{$row['direccion']}</td>
                                    <td>{$row['telefono']}</td>
                                    <td>{$row['nom_departamento']}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            const tabla = $('#tablaClientes').DataTable({
                responsive: true,
                dom: 't', // Ocultamos el buscador por defecto de DataTables
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                pageLength: 8,
                lengthMenu: [5, 8, 10, 25]
            });

            // 🔍 Filtrado personalizado
            $('#filtroCliente').on('keyup', function () {
                tabla.search(this.value).draw();
            });
        });
    </script>
</body>
</html>
