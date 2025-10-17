<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a style="cursor: pointer;" class="nav-link active"
                onclick="CELM_CargarContenido('vistas/dashboard.php','content-wrapper')">
                Tablero
            </a>
        </li>

     <li class="nav-item dropdown d-none d-sm-inline-block">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownInventario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Inventario
  </a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdownInventario">
    <a class="dropdown-item" href="#" onclick="CELM_CargarContenido('vistas/productos/productos.php','content-wrapper')">Productos</a>
    <a class="dropdown-item" href="#" onclick="CELM_CargarContenido('vistas/categorias/categoria.php','content-wrapper')">Categorías</a>

  </div>
</li>


        <li class="nav-item d-none d-sm-inline-block">
            <a style="cursor: pointer;" class="nav-link"
                onclick="CELM_CargarContenido('vistas/ventas.php','content-wrapper')">
                Ventas
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a style="cursor: pointer;" class="nav-link"
                onclick="CELM_CargarContenido('vistas/compras.php','content-wrapper')">
                Compras
            </a>
        </li>
        <!-- Otros enlaces aquí -->
    </ul>

    <!-- Right navbar links: cerrar sesión -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a id="btnCerrarSesion" class="nav-link" href="vistas/modulos/logout.php" style="cursor:pointer;">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
