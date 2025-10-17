<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Administrador / Proveedores</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Proveedores</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="mb-3 text-left">
                <button id="btnNuevoProveedor" class="btn btn-primary">Nuevo Proveedor</button>
            </div>

            <div id="formularioNuevoProveedor" style="display:none; margin-bottom: 15px;"></div>

            <!-- Tabla de proveedores -->
            <div id="contenedorTabla" style="overflow-x:auto;">
                <table id="listado" class="table table-bordered table-striped display nowrap" style="width:100%">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                           
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                             <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../modelos/conexion.php");
                        $conn = Conexion::conectar();

                        $sql = "SELECT id_proveedor, nombre, direccion, telefono, email FROM proveedores ORDER BY id_proveedor";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($proveedores) {
                            foreach ($proveedores as $row) {
                                echo "<tr>
                                   
                                    <td>{$row["id_proveedor"]}</td>
                                    <td>{$row["nombre"]}</td>
                                    <td>{$row["direccion"]}</td>
                                    <td>{$row["telefono"]}</td>
                                    <td>{$row["email"]}</td>
                                     <td>
                                        <button class='btn btn-sm btn-warning' onclick='editarProveedor({$row["id_proveedor"]})'>Editar</button>
                                        <button class='btn btn-sm btn-danger' onclick='eliminarProveedor({$row["id_proveedor"]})'>Eliminar</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No existen registros</td></tr>";
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

        $('#btnNuevoProveedor').on('click', function (e) {
            e.preventDefault();
            $('#contenedorTabla').hide();
            $('#btnNuevoProveedor').hide();
            $('#formularioNuevoProveedor')
                .slideDown()
                .load('vistas/proveedores/agregar_proveedor.php', function () {
                    $('#botonesFormulario').show();
                });
        });
    });

    function editarProveedor(id) {
        $('#contenedorTabla').hide();
        $('#btnNuevoProveedor').hide();
        $('#formularioNuevoProveedor')
            .slideDown()
            .load('vistas/proveedores/editar_proveedor.php?id=' + id, function () {
                $('#botonesFormulario').show();
            });
    }

    function eliminarProveedor(id) {
        if (confirm('¿Estás seguro de eliminar este proveedor?')) {
            $.ajax({
                url: 'vistas/proveedores/eliminar_proveedor.php',
                type: 'POST',
                data: { id_proveedor: id },
                success: function (response) {
                    alert(response);
                    CELM_CargarContenido('vistas/proveedores/proveedor.php', 'content-wrapper');
                },
                error: function () {
                    alert('Error al eliminar el proveedor.');
                }
            });
        }
    }
</script>
