<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<section class="content">
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3 text-start">
                <button id="btnNuevaCategoria" class="btn btn-primary">Nueva Categoría</button>
            </div>

            <div id="formularioNuevaCategoria" style="display:none; margin-bottom: 35px;"></div>

            <div id="contenedorTablaCategoria" class="table-responsive">
                <table id="tablaCategoria"
                    class="table table-hover table-bordered text-center display nowrap"
                    style="width:100%; background-color: #fff;">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../modelos/conexion.php");
                        $conn = Conexion::conectar();

                        $sql = "SELECT id_categoria, nombre_categoria FROM categoria ORDER BY id_categoria";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($categorias) {
                            foreach ($categorias as $row) {
                                echo "<tr>
                                    <td>{$row["id_categoria"]}</td>
                                    <td>{$row["nombre_categoria"]}</td>
                                   <td>
    <div class='d-flex justify-content-center' style='gap: 40px;'>
        <button class='btn btn-warning px-4'
            onclick='editarCategoria({$row["id_categoria"]})'>
            Editar
        </button>
        <button class='btn btn-danger px-4'
            onclick='eliminarCategoria({$row["id_categoria"]})'>
            Eliminar
        </button>
    </div>
</td>                       </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No existen registros</td></tr>";
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
        $('#tablaCategoria').DataTable({
            responsive: false,
            scrollX: true,
            lengthChange: true,
            pageLength: 15,
            autoWidth: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        $('#btnNuevaCategoria').on('click', function(e) {
            e.preventDefault();
            $('#contenedorTablaCategoria').hide();
            $('#btnNuevaCategoria').hide();
            $('#formularioNuevaCategoria')
                .slideDown()
                .load('vistas/categorias/agregar_categoria.php', function() {
                    $('#botonesFormulario').show();
                });
        });
    });

    function editarCategoria(id) {
        $('#contenedorTablaCategoria').hide();
        $('#btnNuevaCategoria').hide();
        $('#formularioNuevaCategoria')
            .slideDown()
            .load('vistas/categorias/editar_categoria.php?id=' + id, function() {
                $('#botonesFormulario').show();
            });
    }

    function eliminarCategoria(id) {
        if (confirm('¿Estás seguro de eliminar esta categoría?')) {
            $.ajax({
                url: 'vistas/categorias/eliminar_categoria.php',
                type: 'POST',
                data: {
                    id_categoria: id
                },
                success: function(response) {
                    alert(response);
                    CELM_CargarContenido('vistas/categorias/categoria.php', 'content-wrapper');
                },
                error: function() {
                    alert('Error al eliminar la categoría.');
                }
            });
        }
    }
</script>