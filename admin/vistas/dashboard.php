<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tablero Principal</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Tablero Principal</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <!-- Fila 1: 3 contenedores -->
        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h4 id="totalProductos"></h4>
                        <p>Productos registrados</p>
                    </div>
                    <div class="icon"><i class="ion ion-clipboard"></i></div>
                    <a class="small-box-footer "> Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h4 id="totalCompras"></h4>
                        <p>Total Compras</p>
                    </div>
                    <div class="icon"><i class="ion ion-cash"></i></div>
                    <a class="small-box-footer"> Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h4 id="totalVentas"></h4>
                        <p>Total Ventas</p>
                    </div>
                    <div class="icon"><i class="ion ion-clipboard"></i></div>
                    <a class="small-box-footer"> Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Fila 2: 3 contenedores -->
        <div class="row">
            <div class="col-lg-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h4 id="totalGanancias"></h4>
                        <p>Total Ganancias</p>
                    </div>
                    <div class="icon"><i class="ion ion-clipboard"></i></div>
                    <a class="small-box-footer"> Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h4 id="totalProductosMinStock"></h4>
                        <p>Productos Poco Stock</p>
                    </div>
                    <div class="icon"><i class="ion ion-clipboard"></i></div>
                    <a class="small-box-footer"> Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h4 id="totalVentasHoy"></h4>
                        <p>Ventas del Día</p>
                    </div>
                    <div class="icon"><i class="ion ion-clipboard"></i></div>
                    <a class="small-box-footer"> Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->


<style>
/* Reducir tamaño del texto dentro de los h4 de los cuadros */
.small-box .inner h4 {
    font-size: 2.5rem; /* más pequeño que antes, ajusta si quieres */
    font-weight: 500;
    text-align: left;
}
</style>

<script>
    $(document).ready(function(){
        $.ajax({
            url: "ajax/dashboard.ajax.php",
            method: 'POST',
            dataType: 'json',
            success:function(respuesta){
                console.log("respuesta", respuesta);

                function formatoMoneda(valor) {
                    return Number(valor).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                function formatoEntero(valor) {
                    return Number(valor).toLocaleString('en-US', { maximumFractionDigits: 0 });
                }

                $("#totalProductos").html(formatoEntero(respuesta[0]['totalProductos']));
                $("#totalCompras").html('Bs./ ' + formatoMoneda(respuesta[0]['totalCompras']));
                $("#totalVentas").html('Bs./ ' + formatoMoneda(respuesta[0]['totalVentas']));
                $("#totalGanancias").html('Bs./ ' + formatoMoneda(respuesta[0]['ganancias']));
                $("#totalProductosMinStock").html(formatoEntero(respuesta[0]['productosPocoStock']));
                $("#totalVentasHoy").html('Bs./ ' + formatoMoneda(respuesta[0]['ventasHoy']));
            }
        });  
    })
</script>
