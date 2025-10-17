<!-- Main Sidebar Container -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['admin_usuario'] ?? 'Invitado';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link d-flex align-items-center" style="text-align: left;">
        <img src="vistas/assets/dist/img/AdminLTELogo.png" alt="Logo Ferretería"
             class="brand-image img-circle elevation-3 mr-2"
             style="opacity: .9; width: 30px; height: 30px;">
        <span class="brand-text font-weight-bold" style="font-size: 1.3rem; color: #ffffff;">
            Ferretería La Llave
        </span>
    </a>
<!-- Usuario -->
<a class="brand-link d-flex align-items-center justify-content-center" style="flex-direction: column; text-align: center; padding: 10px 0;">
    <i class="fas fa-user-circle" style="font-size: 1.5rem; color: white; margin-bottom: 4px;"></i>
    <span class="brand-text font-weight-bold" style="font-size: 0.95rem; color: #ffffff;">
        Bienvenido al Sistema
    </span>
    <span class="text-white" style="font-size: 0.85rem;">
        <?php echo htmlspecialchars($usuario); ?>
    </span>
</a>


    <!-- Sidebar -->
    <div class="sidebar">



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a style="cursor: pointer;" class="nav-link active" onclick="CELM_CargarContenido('vistas/dashboard.php','content-wrapper')" >
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Tablero principal
                            
                        </p>
                    </a>
                </li>
                
                <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Administrador
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/empleados/empleados.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Empleados</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/proveedores/proveedor.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Proveedores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/sucursal/sucursal.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Sucursales</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Inventario
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/productos/productos.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Productos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/categorias/categoria.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Categorias</p>
                            </a>
                        </li>

                        
                    </ul>
                </li>
                <li class="nav-item">
                <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/ventas.php','content-wrapper')" >
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Ventas
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/pedidos.php','content-wrapper')" >
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p>
                            Pedido de Cliente
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/compras.php','content-wrapper')" >
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p>
                            Compras
                        </p>
                    </a>
                </li>
               


                 <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>
                            Reportes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reporte_productos.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Productos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reporte_empleados.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Empleados</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reporte_proveedores.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Compras Realizadas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reporte_facturas.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Facturas</p>
                            </a>
                        </li>
                         <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reporte_clientes.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Clientes</p>
                            </a>
                        </li>
                         <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reportes_asistencia.php','content-wrapper')" >
                        <i class="far fa-circle nav-icon"></i>
                                <p>Asistencia</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                <a style="cursor: pointer;" class="nav-link" onclick="CELM_CargarContenido('vistas/reclamos.php','content-wrapper')" >
                        <i class="nav-icon fas fa-exclamation-circle"></i>
                        <p>
                            Reclamos
                        </p>
                    </a>
                </li>
              
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    $(".nav-link").on('click',function(){
          $(".nav-link").removeClass('active');
          $(this).addClass('active');
    })
</script>