<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Administrador / Empleados</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active"> / Empleados</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="mb-3 text-left">
                <button id="btnNuevoEmpleado" class="btn btn-primary">Nuevo Empleado</button>
            </div>

            <!-- Aquí se cargará el formulario -->
            <div id="formularioNuevoEmpleado" style="display:none; margin-bottom: 15px;"></div>

           
 
            <!-- Contenedor con scroll -->
            <div id="contenedorTabla" style="overflow-x:auto;">
                <table id="listado" class="table table-bordered table-striped display nowrap" style="width:100%">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>Acciones</th>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Usuario</th>
                            <th>Teléfono</th>
                            <th>CI</th>
                            <th>Dirección</th>
                            <th>Fecha de contrato</th>
                            <th>Estado</th>
                            <th>Salario</th>
                            <th>Cargo</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../modelos/conexion.php");
                        $conn = Conexion::conectar();

                        $sql = "SELECT 
                                    e.id_empleado,
                                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
                                    e.usuario,
                                    p.telefono,
                                    p.ci,
                                    p.direccion,
                                    e.fecha_ingreso,
                                    e.estado,
                                    e.salario,
                                    c.nombre_cargo
                                FROM empleado e
                                JOIN persona p ON e.id_persona = p.id_persona
                                LEFT JOIN cargo_empleado c ON e.id_cargo = c.id_cargo
                                ORDER BY e.id_empleado, p.nombres";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $empleado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($empleado) {
                            foreach ($empleado as $row) {
                                $estado = $row["estado"] ? "Activo" : "Inactivo";
                                echo "<tr>
                                 <td>
                                            <button class='btn btn-sm btn-warning' onclick='editarEmpleado({$row["id_empleado"]})'>Editar</button>
                                            <button class='btn btn-sm btn-danger' onclick='eliminarEmpleado({$row["id_empleado"]})'>Eliminar</button>
                                        </td>
                                        <td>{$row["id_empleado"]}</td>
                                        <td>{$row["nombre_completo"]}</td>
                                        <td>{$row["usuario"]}</td>
                                        <td>{$row["telefono"]}</td>
                                        <td>{$row["ci"]}</td>
                                        <td>{$row["direccion"]}</td>
                                        <td>{$row["fecha_ingreso"]}</td>
                                        <td>{$estado}</td>
                                        <td>{$row["salario"]}</td>
                                        <td>{$row["nombre_cargo"]}</td>
                                       
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='12' class='text-center'>No existen registros</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        // Inicializar DataTable
        $('#listado').DataTable({
            responsive: false,
            scrollX: true,
            lengthChange: true,
            pageLength: 15,
            autoWidth: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        // Mostrar formulario de nuevo empleado
        $('#btnNuevoEmpleado').on('click', function (e) {
            e.preventDefault();
            $('#contenedorTabla').hide();
            $('#btnNuevoEmpleado').hide();

            $('#formularioNuevoEmpleado')
                .slideDown()
                .load('vistas/empleados/agregar_persona.php', function () {
                    $('#botonesFormulario').show(); // ✅ Mostrar botones al cargar
                });
        });
    });

    function editarEmpleado(id) {
        $('#contenedorTabla').hide();
        $('#btnNuevoEmpleado').hide();
        $('#formularioNuevoEmpleado')
            .slideDown()
            .load('vistas/empleados/editar_empleado.php?id=' + id, function () {
                $('#botonesFormulario').show(); // ✅ Mostrar botones al editar
            });
    }

    function eliminarEmpleado(id) {
        if (confirm('¿Estás seguro de eliminar este empleado?')) {
            $.ajax({
                url: 'vistas/empleados/eliminar_empleado.php',
                type: 'POST',
                data: { id_empleado: id },
                success: function (response) {
                    alert(response);
                    CELM_CargarContenido('vistas/empleados/empleados.php', 'content-wrapper');
                },
                error: function () {
                    alert('Error al eliminar el empleado.');
                }
            });
        }
    }
</script>
