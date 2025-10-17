<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- Content Header (Page header) -->
 <section class="content-header">

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Administrador / Sucursales</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Sucursales</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->

</section>
<section class="content">
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3 text-start">
                <button id="btnNuevaSucursal" class="btn btn-primary">Nueva Sucursal</button>
            </div>

            <div id="formularioNuevaSucursal" style="display:none; margin-bottom: 35px;"></div>

            <div id="contenedorTablaSucursal" class="table-responsive">
                <table id="tablaSucursal"
                    class="table table-hover table-bordered text-center display nowrap"
                    style="width:100%; background-color: #fff;">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../modelos/conexion.php");
                        $conn = Conexion::conectar();

                        $sql = "SELECT id_sucursal, nombre, direccion, email, telefono FROM sucursal ORDER BY id_sucursal";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($sucursales) {
                            foreach ($sucursales as $row) {
                                echo "<tr>
                                    <td>{$row["id_sucursal"]}</td>
                                    <td>{$row["nombre"]}</td>
                                    <td>{$row["direccion"]}</td>
                                    <td>{$row["email"]}</td>
                                    <td>{$row["telefono"]}</td>
                                    <td>
                                        <div >
                                            <button class='btn btn-sm btn-warning ' onclick='editarSucursal({$row["id_sucursal"]})'>Editar</button>
                                            <button class='btn btn-sm btn-danger ' onclick='eliminarSucursal({$row["id_sucursal"]})'>Eliminar</button>
                                        </div>
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

<!-- jQuery y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaSucursal').DataTable({
            responsive: false,
            scrollX: true,
            lengthChange: true,
            pageLength: 15,
            autoWidth: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        $('#btnNuevaSucursal').on('click', function(e) {
            e.preventDefault();
            $('#contenedorTablaSucursal').hide();
            $('#btnNuevaSucursal').hide();
            $('#formularioNuevaSucursal')
                .slideDown()
                .load('vistas/sucursal/agregar_sucursal.php', function() {
                    $('#botonesFormulario').show();
                });
        });
    });

    function editarSucursal(id) {
         $('#contenedorTablaSucursal').hide();
        $('#btnNuevaSucursal').hide();
        $('#formularioNuevaSucursal')
            .slideDown()
            .load('vistas/sucursal/editar_sucursal.php?id=' + id, function () {
                $('#botonesFormulario').show();
            });
    }

    function eliminarSucursal(id) {
        if (confirm('¿Estás seguro de eliminar esta sucursal?')) {
            $.ajax({
                url: 'vistas/sucursal/eliminar_sucursal.php',
                type: 'POST',
                data: { id_sucursal: id },
                success: function(response) {
                    alert(response);
                    CELM_CargarContenido('vistas/sucursal/sucursal.php', 'content-wrapper');
                },
                error: function() {
                    alert('Error al eliminar la sucursal.');
                }
            });
        }
    }
</script>
