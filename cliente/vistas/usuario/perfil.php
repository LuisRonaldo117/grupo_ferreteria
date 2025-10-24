<section class="perfil-usuario">
    <div class="container">
        <div class="perfil-layout">
            <!-- Menu Lateral -->
            <div class="perfil-menu">
                <div class="usuario-info">
                    <div class="usuario-avatar">
                        <?php 
                        $iniciales = substr($usuario['nombres'], 0, 1) . substr($usuario['apellidos'], 0, 1);
                        echo strtoupper($iniciales);
                        ?>
                    </div>
                    <div class="usuario-datos">
                        <h3><?php echo $usuario['nombres'] . ' ' . $usuario['apellidos']; ?></h3>
                        <p>Miembro desde <?php echo date('M Y', strtotime($usuario['fecha_creacion'])); ?></p>
                    </div>
                </div>
                
                <nav class="menu-navegacion">
                    <a href="index.php?c=usuario" class="menu-item <?php echo $seccion_activa === 'perfil' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i> Información Personal
                    </a>
                    <a href="index.php?c=usuario&a=pedidos" class="menu-item <?php echo $seccion_activa === 'pedidos' ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-bag"></i> Mis Pedidos
                    </a>
                    <a href="index.php?c=usuario&a=cerrarSesion" class="menu-item cerrar-sesion">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </nav>
            </div>

            <!-- Contenido Principal -->
            <div class="perfil-contenido">
                <?php if ($seccion_activa === 'perfil'): ?>
                    <!-- Seccion de Informacion Personal -->
                    <div class="seccion-perfil">
                        <h2>Información personal</h2>
                        
                        <form class="formulario-perfil" id="formPerfil">
                            <div class="form-seccion">
                                <h3>Datos básicos</h3>
                                
                                <div class="form-fila">
                                    <div class="form-grupo">
                                        <label for="nombres">Nombre *</label>
                                        <input type="text" id="nombres" name="nombres" 
                                               value="<?php echo htmlspecialchars($usuario['nombres']); ?>" required>
                                    </div>
                                    
                                    <div class="form-grupo">
                                        <label for="apellidos">Apellido *</label>
                                        <input type="text" id="apellidos" name="apellidos" 
                                               value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-fila">
                                    <div class="form-grupo">
                                        <label for="correo">Correo electrónico *</label>
                                        <input type="email" id="correo" name="correo" 
                                               value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                                    </div>
                                    
                                    <div class="form-grupo">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" id="telefono" name="telefono" 
                                               value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-seccion">
                                <h3>Dirección</h3>
                                
                                <div class="form-grupo">
                                    <label for="direccion">Dirección principal</label>
                                    <textarea id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-acciones">
                                <button type="submit" class="btn-guardar">
                                    <span class="btn-texto">Guardar Cambios</span>
                                    <span class="btn-cargando" style="display: none;">Guardando...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                <?php else: ?>
                    <!-- Seccion de Mis Pedidos -->
                    <div class="seccion-pedidos">
                        <h2>Mis Pedidos</h2>
                        
                        <?php if (empty($pedidos)): ?>
                            <div class="pedidos-vacio">
                                <div class="icono-vacio">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h3>No tienes pedidos realizados</h3>
                                <p>Cuando realices tu primer pedido, aparecerá aquí.</p>
                                <a href="index.php?c=catalogo" class="btn-ir-catalogo">Ir al Catálogo</a>
                            </div>
                        <?php else: ?>
                            <div class="lista-pedidos">
                                <?php foreach ($pedidos as $pedido): ?>
                                    <div class="pedido-card">
                                        <div class="pedido-header">
                                            <div class="pedido-info">
                                                <h3>Pedido #<?php echo $pedido['numero_pedido']; ?></h3>
                                                <span class="pedido-fecha">
                                                    <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?>
                                                </span>
                                            </div>
                                            <div class="pedido-estado estado-<?php echo $pedido['estado']; ?>">
                                                <?php 
                                                $estados = [
                                                    'pendiente' => 'Pendiente',
                                                    'procesado' => 'Procesado',
                                                    'enviado' => 'Enviado',
                                                    'entregado' => 'Entregado',
                                                    'cancelado' => 'Cancelado'
                                                ];
                                                echo $estados[$pedido['estado']] ?? $pedido['estado'];
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <div class="pedido-detalle">
                                            <div class="pedido-monto">
                                                <strong>Total: Bs. <?php echo number_format($pedido['total'], 2); ?></strong>
                                            </div>
                                            <div class="pedido-metodo">
                                                Método: <?php echo ucfirst($pedido['tipo_pago']); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="pedido-acciones">
                                            <a href="index.php?c=usuario&a=detallePedido&id=<?php echo $pedido['id_pedido']; ?>" 
                                               class="btn-ver-detalle">
                                                Ver Detalles
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
    .perfil-usuario {
        padding: 40px 0 80px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
        min-height: 80vh;
    }

    .perfil-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 40px;
        align-items: start;
    }

    /* Menú Lateral */
    .perfil-menu {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 32px rgba(33, 150, 243, 0.1);
        position: sticky;
        top: 20px;
        border: 1px solid #e3f2fd;
        position: relative;
        overflow: hidden;
    }

    .perfil-menu:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
    }

    .usuario-info {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e3f2fd;
    }

    .usuario-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: white;
        margin: 0 auto 15px;
        box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3);
        border: 3px solid #e3f2fd;
    }

    .usuario-datos h3 {
        color: #1a237e;
        margin-bottom: 5px;
        font-size: 18px;
        font-weight: 600;
    }

    .usuario-datos p {
        color: #546e7a;
        font-size: 14px;
    }

    .menu-navegacion {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        text-decoration: none;
        color: #546e7a;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-weight: 500;
        border: 1px solid transparent;
    }

    .menu-item:hover {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #1976D2;
        border-color: #bbdefb;
        transform: translateX(5px);
    }

    .menu-item.active {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        border-color: #1976D2;
    }

    .menu-item i {
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .cerrar-sesion {
        margin-top: 20px;
        color: #f44336 !important;
        border-top: 1px solid #e3f2fd;
        padding-top: 20px;
    }

    .cerrar-sesion:hover {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
        color: white !important;
        border-color: #d32f2f;
    }

    /* Contenido Principal */
    .perfil-contenido {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(33, 150, 243, 0.1);
        border: 1px solid #e3f2fd;
        position: relative;
        overflow: hidden;
    }

    .perfil-contenido:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
    }

    .seccion-perfil h2,
    .seccion-pedidos h2 {
        color: #1a237e;
        margin-bottom: 30px;
        font-size: 28px;
        font-weight: 700;
        position: relative;
        padding-bottom: 15px;
    }

    .seccion-perfil h2:after,
    .seccion-pedidos h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #2196F3, #1976D2);
        border-radius: 2px;
    }

    /* Formulario de Perfil */
    .formulario-perfil {
        max-width: 600px;
    }

    .form-seccion {
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid #e3f2fd;
    }

    .form-seccion:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .form-seccion h3 {
        color: #1a237e;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
        position: relative;
        padding-left: 15px;
    }

    .form-seccion h3:before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background: linear-gradient(135deg, #2196F3, #1976D2);
        border-radius: 2px;
    }

    .form-fila {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-grupo {
        margin-bottom: 20px;
    }

    .form-grupo label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1a237e;
    }

    .form-grupo input,
    .form-grupo textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e3f2fd;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
        color: #1a237e;
    }

    .form-grupo input:focus,
    .form-grupo textarea:focus {
        outline: none;
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
        transform: translateY(-1px);
    }

    .form-acciones {
        text-align: right;
        margin-top: 30px;
    }

    .btn-guardar {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-guardar:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
    }

    .btn-guardar:active {
        transform: translateY(0);
    }

    /* Sección de Pedidos */
    .pedidos-vacio {
        text-align: center;
        padding: 60px 20px;
        color: #546e7a;
    }

    .icono-vacio {
        font-size: 80px;
        margin-bottom: 20px;
        color: #2196F3;
        opacity: 0.7;
    }

    .pedidos-vacio h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #1a237e;
        font-weight: 600;
    }

    .pedidos-vacio p {
        margin-bottom: 25px;
        font-size: 16px;
    }

    .btn-ir-catalogo {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .btn-ir-catalogo:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        color: white;
        text-decoration: none;
    }

    .lista-pedidos {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .pedido-card {
        border: 2px solid #e3f2fd;
        border-radius: 12px;
        padding: 25px;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .pedido-card:hover {
        border-color: #2196F3;
        box-shadow: 0 8px 25px rgba(33, 150, 243, 0.15);
        transform: translateY(-2px);
    }

    .pedido-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #2196F3, #1976D2);
    }

    .pedido-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .pedido-info h3 {
        color: #1a237e;
        margin-bottom: 5px;
        font-size: 18px;
        font-weight: 600;
    }

    .pedido-fecha {
        color: #546e7a;
        font-size: 14px;
    }

    .pedido-estado {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .estado-pendiente { 
        background: linear-gradient(135deg, #fff3cd, #ffeaa7); 
        color: #856404; 
        border: 1px solid #ffeaa7;
    }
    .estado-procesado { 
        background: linear-gradient(135deg, #d1ecf1, #b3e5fc); 
        color: #0c5460; 
        border: 1px solid #b3e5fc;
    }
    .estado-enviado { 
        background: linear-gradient(135deg, #d4edda, #c8e6c9); 
        color: #155724; 
        border: 1px solid #c8e6c9;
    }
    .estado-entregado { 
        background: linear-gradient(135deg, #d4edda, #c8e6c9); 
        color: #155724; 
        border: 1px solid #c8e6c9;
    }
    .estado-cancelado { 
        background: linear-gradient(135deg, #f8d7da, #ffcdd2); 
        color: #721c24; 
        border: 1px solid #ffcdd2;
    }

    .pedido-detalle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .pedido-monto {
        font-size: 18px;
        color: #1a237e;
        font-weight: 600;
    }

    .pedido-metodo {
        color: #546e7a;
        font-size: 14px;
        background: #e3f2fd;
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: 500;
    }

    .pedido-acciones {
        text-align: right;
    }

    .btn-ver-detalle {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
    }

    .btn-ver-detalle:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .perfil-layout {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .perfil-menu {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .perfil-contenido {
            padding: 25px;
        }

        .form-fila {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .pedido-header {
            flex-direction: column;
            gap: 10px;
        }

        .pedido-detalle {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .perfil-menu {
            padding: 20px;
        }

        .perfil-contenido {
            padding: 20px;
        }
        
        .seccion-perfil h2,
        .seccion-pedidos h2 {
            font-size: 24px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formPerfil = document.getElementById('formPerfil');
        
        if (formPerfil) {
            formPerfil.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btnGuardar = this.querySelector('.btn-guardar');
                const btnTexto = this.querySelector('.btn-texto');
                const btnCargando = this.querySelector('.btn-cargando');
                
                btnTexto.style.display = 'none';
                btnCargando.style.display = 'inline';
                btnGuardar.disabled = true;
                
                const formData = new FormData(this);
                
                fetch('/grupo_ferreteria/cliente/index.php?c=usuario&a=actualizarPerfil', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            mostrarNotificacion('Perfil actualizado correctamente', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            mostrarNotificacion('Error: ' + data.mensaje, 'error');
                        }
                    } catch (e) {
                        mostrarNotificacion('Error en la respuesta del servidor', 'error');
                    }
                })
                .catch(error => {
                    mostrarNotificacion('Error de conexión', 'error');
                })
                .finally(() => {
                    btnTexto.style.display = 'inline';
                    btnCargando.style.display = 'none';
                    btnGuardar.disabled = false;
                });
            });
        }
    });

    function mostrarNotificacion(mensaje, tipo = 'success') {
        const notificacion = document.createElement('div');
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${tipo === 'error' ? '#f44336' : '#4CAF50'};
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 10000;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        `;
        notificacion.textContent = mensaje;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            document.body.removeChild(notificacion);
        }, 4000);
    }
</script>