<?php
include("conexion.php");
$conn = Conexion::conectar();

$empleados = $conn->query("
    SELECT e.id_empleado, p.nombres, p.apellidos, p.correo
    FROM empleado e
    JOIN persona p ON e.id_persona = p.id_persona
    WHERE e.estado = 1
")->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="/grupo_ferreteria/admin/vistas/envio_reportes/css/index.css">

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
 
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" id="envioReportesSection">
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">📄 Enviar Reportes Mensuales a Empleados</h3>
            </div>
            <div class="card-body">
                <form id="formReportes" method="POST" enctype="multipart/form-data">
                    <!-- Selección de empleados -->
                    <div class="form-group empleados-box">
                        <label>Seleccionar Empleados:</label>
                        <div class="checkbox-group">
                            <label class="checkbox select-all">
                                <input type="checkbox" id="selectAll"> <span>Seleccionar todos</span>
                            </label>
                            <?php foreach($empleados as $e): ?>
                                <label class="checkbox">
                                    <input type="checkbox" name="empleados[]" value="<?= $e['id_empleado'] ?>"> 
                                    <span><?= $e['nombres'].' '.$e['apellidos'] ?><br><small><?= $e['correo'] ?></small></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Asunto -->
                    <div class="form-group">
                        <label for="asunto">Asunto:</label>
                        <input type="text" name="asunto" id="asunto" class="form-control" required value="Reporte Mensual">
                    </div>

                    <!-- Mensaje -->
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea name="mensaje" id="mensaje" rows="5" class="form-control" required>Adjunto tu reporte mensual.</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-send">Enviar Reportes</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {

    // Seleccionar/Deseleccionar todos
    $('#selectAll').on('change', function() {
        $('input[name="empleados[]"]').prop('checked', this.checked);
    });

    $('input[name="empleados[]"]').on('change', function() {
        const all = $('input[name="empleados[]"]').length;
        const checked = $('input[name="empleados[]"]:checked').length;
        $('#selectAll').prop('checked', all === checked);
    });

    // Toast personalizado (con desplazamiento hacia arriba)
    function mostrarToast(mensaje, tipo='success') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        const toast = $(`
            <div class="toast-message toast-${tipo}">
                <span>${mensaje}</span>
                <button class="toast-close">&times;</button>
            </div>
        `);
        $('body').append(toast);
        toast.find('.toast-close').on('click', () => toast.remove());
        setTimeout(() => toast.fadeOut(300, () => toast.remove()), 4000);
    }

    // Envío del formulario
    $('#formReportes').on('submit', function(e) {
        e.preventDefault();

        const seleccionados = $('input[name="empleados[]"]:checked');
        if (seleccionados.length === 0) {
            mostrarToast("❌ Debes seleccionar al menos un empleado.", 'error');
            return;
        }

        Swal.fire({
            title: '¿Deseas enviar los reportes por correo?',
            text: `Se enviarán los correos a ${seleccionados.length} empleado(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $('.btn-send');
                const textoOriginal = btn.text();
                btn.text('Enviando...').prop('disabled', true);

                const formData = new FormData(this);

                fetch('vistas/envio_reportes/enviar_reporte.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    mostrarToast(data.mensaje, data.exito ? 'success' : 'error');
                    if (data.exito) {
                        $('#formReportes')[0].reset();
                        $('#selectAll').prop('checked', false);
                    }
                })
                .catch(() => mostrarToast('❌ Ocurrió un error al enviar los reportes.', 'error'))
                .finally(() => {
                    btn.text(textoOriginal).prop('disabled', false);
                });
            }
        });
    });

});
</script>
