class NotificacionesManager {
    constructor() {
        this.contador = document.getElementById('contadorNotificaciones');
        this.dropdown = document.getElementById('dropdownNotificaciones');
        this.icono = document.getElementById('iconoNotificaciones');
        this.lista = document.getElementById('listaNotificaciones');
        this.overlay = document.getElementById('overlayNotificaciones');
        this.idCliente = window.idCliente || 0;
        
        this.init();
    }
    
    init() {
        if (this.icono) {
            this.icono.addEventListener('click', (e) => this.toggleDropdown(e));
            this.overlay.addEventListener('click', () => this.cerrarDropdown());
            this.cargarNotificaciones();
            
            // Actualizar cada 30 segundos
            setInterval(() => this.actualizarContador(), 30000);
        }
    }
    
    toggleDropdown(e) {
        e.stopPropagation();
        const estaAbierto = this.dropdown.classList.contains('active');
        
        if (estaAbierto) {
            this.cerrarDropdown();
        } else {
            this.abrirDropdown();
        }
    }
    
    abrirDropdown() {
        this.dropdown.classList.add('active');
        this.overlay.style.display = 'block';
        this.cargarNotificaciones();
    }
    
    cerrarDropdown() {
        this.dropdown.classList.remove('active');
        this.overlay.style.display = 'none';
    }
    
    async cargarNotificaciones() {
        if (!this.idCliente) return;
        
        try {
            const response = await fetch('index.php?c=notificaciones&a=obtener');
            const data = await response.json();
            
            if (data.success) {
                this.mostrarNotificaciones(data.notificaciones);
                this.actualizarContadorUI(data.totalNoLeidas);
            }
        } catch (error) {
            console.error('Error cargando notificaciones:', error);
        }
    }
    
    mostrarNotificaciones(notificaciones) {
        if (notificaciones.length === 0) {
            this.lista.innerHTML = `
                <div class="notificacion-vacia">
                    <div class="icono">🔔</div>
                    <p>No tienes notificaciones</p>
                    <small>Te notificaremos cuando tus pedidos cambien de estado</small>
                </div>
            `;
            return;
        }
        
        let html = '';
        let fechaActual = '';
        
        notificaciones.forEach(notif => {
            // Agrupar por fecha
            if (notif.fecha !== fechaActual) {
                fechaActual = notif.fecha;
                html += `<div class="notificacion-grupo-fecha">${fechaActual}</div>`;
            }
            
            html += `
                <div class="notificacion-item ${notif.leida ? 'leida' : 'no-leida'}" 
                     data-id="${notif.id}" data-tipo="${notif.tipo}">
                    <div class="notificacion-header">
                        <div class="notificacion-titulo">${notif.titulo}</div>
                        ${!notif.leida ? '<div class="notificacion-punto"></div>' : ''}
                    </div>
                    <div class="notificacion-descripcion">${notif.descripcion}</div>
                    <div class="notificacion-footer">
                        <span class="notificacion-tiempo">${notif.tiempo}</span>
                        <span class="notificacion-tipo">${notif.tipo}</span>
                    </div>
                </div>
            `;
        });
        
        this.lista.innerHTML = html;
        
        // Agregar event listeners a las notificaciones
        this.lista.querySelectorAll('.notificacion-item').forEach(item => {
            item.addEventListener('click', () => this.marcarComoLeida(item));
        });
    }
    
    async marcarComoLeida(elemento) {
        const idNotificacion = elemento.dataset.id;
        
        try {
            const formData = new FormData();
            formData.append('id_notificacion', idNotificacion);
            
            const response = await fetch('index.php?c=notificaciones&a=marcarLeida', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                elemento.classList.remove('no-leida');
                elemento.classList.add('leida');
                elemento.querySelector('.notificacion-punto')?.remove();
                this.actualizarContadorUI(data.totalNoLeidas);
            }
        } catch (error) {
            console.error('Error marcando notificación como leída:', error);
        }
    }
    
    async actualizarContador() {
        if (!this.idCliente) return;
        
        try {
            const response = await fetch('index.php?c=notificaciones&a=cantidadNoLeidas');
            const data = await response.json();
            
            if (data.success) {
                this.actualizarContadorUI(data.totalNoLeidas);
            }
        } catch (error) {
            console.error('Error actualizando contador:', error);
        }
    }
    
    actualizarContadorUI(totalNoLeidas) {
        if (this.contador) {
            if (totalNoLeidas > 0) {
                this.contador.textContent = totalNoLeidas > 99 ? '99+' : totalNoLeidas;
                this.contador.style.display = 'flex';
                
                // Agregar animacion de parpadeo para nuevas notificaciones
                this.contador.style.animation = 'pulse 2s infinite';
            } else {
                this.contador.style.display = 'none';
                this.contador.style.animation = 'none';
            }
        }
    }
}

// Inicializar cuando el dom este listo
document.addEventListener('DOMContentLoaded', () => {
    new NotificacionesManager();
});