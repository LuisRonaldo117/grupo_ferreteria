document.addEventListener('DOMContentLoaded', function() {
    const iconoNotificaciones = document.getElementById('iconoNotificaciones');
    const dropdownNotificaciones = document.getElementById('dropdownNotificaciones');
    const overlayNotificaciones = document.getElementById('overlayNotificaciones');
    const listaNotificaciones = document.getElementById('listaNotificaciones');
    const contadorNotificaciones = document.getElementById('contadorNotificaciones');

    // Datos de ejemplo para notificaciones
    const notificaciones = [
        {
            id: 1,
            titulo: '¡Bienvenido a Ferretería!',
            descripcion: 'Gracias por registrarte. Disfruta de envío gratis en tu primera compra.',
            tiempo: 'Hace 2 horas',
            leida: false,
            tipo: 'bienvenida'
        },
        {
            id: 2,
            titulo: 'Oferta Especial',
            descripcion: '20% de descuento en herramientas eléctricas esta semana.',
            tiempo: 'Hace 1 día',
            leida: false,
            tipo: 'promocion'
        },
        {
            id: 3,
            titulo: 'Pedido #00123 enviado',
            descripcion: 'Tu pedido ha sido enviado y llegará en 2-3 días hábiles.',
            tiempo: 'Hace 3 días',
            leida: true,
            tipo: 'pedido'
        }
    ];

    // Función para cargar notificaciones
    function cargarNotificaciones() {
        listaNotificaciones.innerHTML = '';

        const notificacionesNoLeidas = notificaciones.filter(notif => !notif.leida);
                
        // Actualizar contador
        contadorNotificaciones.textContent = notificacionesNoLeidas.length;
                
        if (notificacionesNoLeidas.length === 0) {
            // Mostrar mensaje cuando no hay notificaciones
            listaNotificaciones.innerHTML = `
                <div class="notificacion-vacia">
                    <div class="icono">🔔</div>
                    <p>No tienes notificaciones nuevas</p>
                </div>
            `;
        } else {
            // Mostrar notificaciones no leídas
            notificacionesNoLeidas.forEach(notificacion => {
                const notificacionElement = document.createElement('div');
                notificacionElement.className = 'notificacion-item';
                notificacionElement.innerHTML = `
                    <div class="notificacion-titulo">${notificacion.titulo}</div>
                    <div class="notificacion-descripcion">${notificacion.descripcion}</div>
                    <div class="notificacion-tiempo">${notificacion.tiempo}</div>
                `;
                        
                notificacionElement.addEventListener('click', function() {
                    marcarComoLeida(notificacion.id);
                });
                        
                listaNotificaciones.appendChild(notificacionElement);
            });
        }
    }

    // Función para marcar notificación como leída
    function marcarComoLeida(id) {
        const notificacion = notificaciones.find(notif => notif.id === id);
        if (notificacion && !notificacion.leida) {
            notificacion.leida = true;
            cargarNotificaciones();
                    
            // Cerrar dropdown después de un momento
            setTimeout(() => {
                cerrarNotificaciones();
            }, 1000);
        }
    }

    // Función para abrir notificaciones
    function abrirNotificaciones() {
        dropdownNotificaciones.classList.add('active');
        overlayNotificaciones.style.display = 'block';
        cargarNotificaciones();
    }

    // Función para cerrar notificaciones
    function cerrarNotificaciones() {
        dropdownNotificaciones.classList.remove('active');
        overlayNotificaciones.style.display = 'none';
    }

    // Event listeners
    iconoNotificaciones.addEventListener('click', function(e) {
        e.stopPropagation();
        if (dropdownNotificaciones.classList.contains('active')) {
            cerrarNotificaciones();
        } else {
            abrirNotificaciones();
        }
    });

    overlayNotificaciones.addEventListener('click', cerrarNotificaciones);

    // Cerrar notificaciones al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!dropdownNotificaciones.contains(e.target) && !iconoNotificaciones.contains(e.target)) {
            cerrarNotificaciones();
        }
    });

    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarNotificaciones();
        }
    });

    // Cargar notificaciones al iniciar
    cargarNotificaciones();

    // Simular nueva notificación (para demostración)
    setTimeout(() => {
        // Esta es solo una simulación - en una app real vendría del servidor
        console.log('Nueva notificación simulada');
    }, 10000);
});