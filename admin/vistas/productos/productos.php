<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Inventario / Productos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active"> Productos</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-body">

            <div class="mb-3 text-left">
                <button id="btnNuevoProducto" class="btn btn-primary">Nuevo Producto</button>
            </div>

            <!-- Aquí se cargará el formulario -->
            <div id="formularioNuevoProducto" style="display:none; margin-bottom: 15px;"></div>

            <!-- Botones para formulario (inicialmente ocultos) -->
            <div id="botonesFormulario" style="display:none; margin-bottom: 20px;">
                <button id="btnRegistrarProducto" class="btn btn-success">Registrar Producto</button>
                <button id="btnVolverAtras" class="btn btn-secondary">Volver Atrás</button>
            </div>

            <!-- Contenedor de la tabla -->
            <div id="contenedorTabla" style="overflow-x:auto;">
                <table id="listado" class="table table-bordered table-striped display nowrap" style="width:100%">
                    <thead style="background-color: #343a40; color: white;">
                        <tr>
                            <th>Acciones</th>
                      
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Categoria</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../modelos/conexion.php");
                        $conn = Conexion::conectar();
                        $sql = "SELECT p.id_producto, p.imagen, 
                         p.nombre, p.descripcion, p.precio_unitario, p.stock, c.nombre_categoria
                         FROM 
                        producto p
                        JOIN 
                        categoria c ON p.id_categoria = c.id_categoria";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($productos) {
                            foreach ($productos as $row) {
                                echo "<tr>
                                            <td>
                <button class='btn btn-sm btn-warning' onclick='editarProducto(" . $row['id_producto'] . ")'>Editar</button>
                <button class='btn btn-sm btn-danger' onclick='eliminarProducto(" . $row['id_producto'] . ")'>Eliminar</button>
            </td>
 
            <td>
                <img src='/grupo_ferreteria/imagenes/{$row['imagen']}' alt='Imagen' style='width: 50px; height: auto;'>
          </td>
            <td>{$row['nombre']}</td>
            <td>{$row['descripcion']}</td>
            <td>{$row['precio_unitario']}</td>
            <td>{$row['stock']}</td>
            <td>{$row['nombre_categoria']}</td>

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
    $(document).ready(function() {
        // Inicializar DataTables
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

        // Mostrar formulario y ocultar tabla al click en "Nuevo Producto"
        $('#btnNuevoProducto').on('click', function(e) {
            e.preventDefault();

            $('#contenedorTabla').hide(); // Ocultar tabla
            $('#btnNuevoProducto').hide(); // Ocultar botón Nuevo Producto

            $('#formularioNuevoProducto').slideDown().load('vistas/productos/agregar_producto.php', function() {

            });
        });
    });

    function editarProducto(id) {
        // Ocultar tabla y botón
        $('#contenedorTabla').hide();
        $('#btnNuevoProducto').hide();

        // Mostrar y cargar el formulario de edición con el id
        $('#formularioNuevoProducto')
            .slideDown()
            .load('vistas/productos/editar_producto.php?id=' + id);
    }

    // Función para eliminar producto usando AJAX
    function eliminarProducto(id) {
        if (confirm('¿Estás seguro de eliminar este producto?')) {
            $.ajax({
                url: 'vistas/productos/eliminar_producto.php',
                type: 'POST',
                data: {
                    id_producto: id
                },
                success: function(response) {
                    alert(response);
                    // En lugar de recargar la página, llama a tu función para cargar contenido
                    CELM_CargarContenido('vistas/productos/productos.php', 'content-wrapper');
                },
                error: function() {
                    alert('Error al eliminar el producto.');
                }
            });
        }
    }
</script>