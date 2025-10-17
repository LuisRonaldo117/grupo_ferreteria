<!-- Content Header (Page header) -->
 <section class="content-header">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Panel de Reclamos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reclamos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
</section>
<!-- /.content-header -->
<section class="content">
  <div class="content">
    <div class="container-fluid">
      <div class="mb-3 text-center">
       <button onclick="CELM_CargarContenido('vistas/reclamos.php?filtro=recientes','content-wrapper')" class="btn btn-primary mr-2">
    Mostrar recientes (24h)
</button>

<button onclick="CELM_CargarContenido('vistas/reclamos.php?filtro=todo','content-wrapper')" class="btn btn-success">
    Mostrar todos los reclamos
</button> </div>

      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr class="text-center">
              <th>ID</th>
              <th>Cliente</th>
              <th>CI</th>
              <th>Descripción</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include("../modelos/conexion.php");
            $conn = Conexion::conectar();

            // Por defecto: mostrar recientes
            $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'recientes';

            if ($filtro === 'todo') {
              $sql = "SELECT 
                        r.id_reclamo,
                        CONCAT(p.nombres, ' ', p.apellidos) AS cliente,
                        p.ci,
                        r.descripcion,
                        r.fecha_reclamo
                      FROM reclamos r
                      JOIN cliente c ON r.id_cliente = c.id_cliente
                      JOIN persona p ON c.id_persona = p.id_persona
                      ORDER BY r.fecha_reclamo DESC";
            } else {
              // Últimas 24 horas
              $sql = "SELECT 
                        r.id_reclamo,
                        CONCAT(p.nombres, ' ', p.apellidos) AS cliente,
                        p.ci,
                        r.descripcion,
                        r.fecha_reclamo
                      FROM reclamos r
                      JOIN cliente c ON r.id_cliente = c.id_cliente
                      JOIN persona p ON c.id_persona = p.id_persona
                      WHERE r.fecha_reclamo >= NOW() - INTERVAL 1 DAY
                      ORDER BY r.fecha_reclamo DESC";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reclamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($reclamos) {
              foreach ($reclamos as $row) {
                echo "<tr>
                        <td class='text-center'>{$row['id_reclamo']}</td>
                        <td>{$row['cliente']}</td>
                        <td>{$row['ci']}</td>
                        <td>{$row['descripcion']}</td>
                        <td class='text-center'>{$row['fecha_reclamo']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>No hay reclamos registrados</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
