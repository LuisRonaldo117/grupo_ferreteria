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
                        👤 Información Personal
                    </a>
                    <a href="index.php?c=usuario&a=pedidos" class="menu-item <?php echo $seccion_activa === 'pedidos' ? 'active' : ''; ?>">
                        📦 Mis Pedidos
                    </a>
                    <a href="index.php?c=usuario&a=cerrarSesion" class="menu-item cerrar-sesion">
                        🚪 Cerrar Sesión
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
                                <div class="icono-vacio">📦</div>
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
        background: #f8f9fa;
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
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }

    .usuario-info {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid #ecf0f1;
    }

    .usuario-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #1abc9c, #16a085);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: white;
        margin: 0 auto 15px;
    }

    .usuario-datos h3 {
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 18px;
        font-weight: 600;
    }

    .usuario-datos p {
        color: #7f8c8d;
        font-size: 14px;
    }

    .menu-navegacion {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        text-decoration: none;
        color: #7f8c8d;
        border-radius: 10px;
        transition: all 0.3s;
        font-weight: 500;
    }

    .menu-item:hover {
        background: #f8f9fa;
        color: #2c3e50;
    }

    .menu-item.active {
        background: #1abc9c;
        color: white;
    }

    .cerrar-sesion {
        margin-top: 20px;
        color: #e74c3c !important;
        border-top: 1px solid #ecf0f1;
        padding-top: 20px;
    }

    .cerrar-sesion:hover {
        background: #e74c3c !important;
        color: white !important;
    }

    /* Contenido Principal */
    .perfil-contenido {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .seccion-perfil h2,
    .seccion-pedidos h2 {
        color: #2c3e50;
        margin-bottom: 30px;
        font-size: 28px;
        font-weight: bold;
    }

    /* Formulario de Perfil */
    .formulario-perfil {
        max-width: 600px;
    }

    .form-seccion {
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid #ecf0f1;
    }

    .form-seccion:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .form-seccion h3 {
        color: #2c3e50;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
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
        color: #2c3e50;
    }

    .form-grupo input,
    .form-grupo textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
        background: white;
    }

    .form-grupo input:focus,
    .form-grupo textarea:focus {
        outline: none;
        border-color: #1abc9c;
    }

    .form-acciones {
        text-align: right;
        margin-top: 30px;
    }

    .btn-guardar {
        background: #1abc9c;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-guardar:hover {
        background: #16a085;
    }

    /* Sección de Pedidos */
    .pedidos-vacio {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .icono-vacio {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .pedidos-vacio h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .pedidos-vacio p {
        margin-bottom: 25px;
        font-size: 16px;
    }

    .btn-ir-catalogo {
        background: #1abc9c;
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.3s;
        display: inline-block;
    }

    .btn-ir-catalogo:hover {
        background: #16a085;
    }

    .lista-pedidos {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .pedido-card {
        border: 2px solid #ecf0f1;
        border-radius: 12px;
        padding: 25px;
        transition: all 0.3s;
    }

    .pedido-card:hover {
        border-color: #1abc9c;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .pedido-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .pedido-info h3 {
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 18px;
        font-weight: 600;
    }

    .pedido-fecha {
        color: #7f8c8d;
        font-size: 14px;
    }

    .pedido-estado {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .estado-pendiente { background: #fff3cd; color: #856404; }
    .estado-procesado { background: #d1ecf1; color: #0c5460; }
    .estado-enviado { background: #d4edda; color: #155724; }
    .estado-entregado { background: #d4edda; color: #155724; }
    .estado-cancelado { background: #f8d7da; color: #721c24; }

    .pedido-detalle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .pedido-monto {
        font-size: 18px;
        color: #2c3e50;
    }

    .pedido-metodo {
        color: #7f8c8d;
        font-size: 14px;
    }

    .pedido-acciones {
        text-align: right;
    }

    .btn-ver-detalle {
        background: #3498db;
        color: white;
        padding: 8px 20px;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-ver-detalle:hover {
        background: #2980b9;
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
                
                // Mostrar estado de carga
                btnTexto.style.display = 'none';
                btnCargando.style.display = 'inline';
                btnGuardar.disabled = true;
                
                // Enviar datos via AJAX
                const formData = new FormData(this);
                
                console.log('Enviando datos del formulario...');
                
                fetch('index.php?c=usuario&a=actualizarPerfil', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta recibida, status:', response.status);
                    
                    if (!response.ok) {
                        throw new Error('Error HTTP: ' + response.status);
                    }
                    
                    return response.text().then(text => {
                        console.log('Respuesta texto completo:', text);
                        
                        // Intentar parsear como JSON
                        try {
                            const data = JSON.parse(text);
                            console.log('Datos parseados:', data);
                            return data;
                        } catch (e) {
                            console.error('Error parseando JSON:', e);
                            console.error('Texto recibido:', text);
                            
                            // Si no es JSON válido, mostrar el texto completo para debug
                            mostrarNotificacion('Error en la respuesta del servidor. Ver consola para detalles.', 'error');
                            throw new Error('Respuesta no válida del servidor: ' + text.substring(0, 100));
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        mostrarNotificacion('Perfil actualizado correctamente', 'success');
                        // Opcional: recargar la página después de un tiempo
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        mostrarNotificacion('Error: ' + data.mensaje, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    mostrarNotificacion('Error de conexión: ' + error.message, 'error');
                })
                .finally(() => {
                    // Restaurar boton
                    btnTexto.style.display = 'inline';
                    btnCargando.style.display = 'none';
                    btnGuardar.disabled = false;
                });
            });
        }
    });

    // Función para mostrar notificaciones bonitas
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const notificacion = document.createElement('div');
        const backgroundColor = tipo === 'error' ? '#e74c3c' : '#1abc9c';
        
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${backgroundColor};
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 400px;
        `;
        notificacion.textContent = mensaje;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (notificacion.parentNode) {
                    document.body.removeChild(notificacion);
                }
            }, 300);
        }, 4000);
    }

    // Agregar estilos para las animaciones si no existen
    if (!document.querySelector('#notificacion-styles')) {
        const style = document.createElement('style');
        style.id = 'notificacion-styles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>