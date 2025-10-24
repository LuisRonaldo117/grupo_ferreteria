class CarritoManager {
    constructor() {
        this.carrito = [];
        this.init();
    }

    async init() {
        await this.cargarCarritoDesdeBD();
        this.agregarEventListeners();
        this.actualizarVista();
        this.actualizarContadores();
    }

    async cargarCarritoDesdeBD() {
        try {
            const response = await fetch('index.php?c=carrito&a=obtenerCarrito');
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const resultado = await response.json();
            
            if (resultado.success) {
                this.carrito = resultado.carrito;
                this.actualizarVista();
            } else {
                this.mostrarMensaje('Error: ' + resultado.mensaje, 'error');
            }
        } catch (error) {
            console.error('Error al cargar carrito:', error);
            this.mostrarMensaje('Error de conexión al cargar carrito', 'error');
        }
    }

    async agregarProducto(idProducto, nombre, precio, imagen, cantidad = 1) {
        try {
            const formData = new FormData();
            formData.append('id_producto', idProducto);
            formData.append('cantidad', cantidad);

            const response = await fetch('index.php?c=carrito&a=agregar', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            
            if (resultado.success) {
                await this.cargarCarritoDesdeBD();
                this.mostrarMensaje('Producto agregado al carrito');
            } else {
                this.mostrarMensaje('Error: ' + resultado.mensaje, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarMensaje('Error de conexión: ' + error.message, 'error');
        }
    }

    async actualizarCantidad(idCarrito, nuevaCantidad) {
        try {
            const formData = new FormData();
            formData.append('id_carrito', idCarrito);
            formData.append('cantidad', nuevaCantidad);

            const response = await fetch('index.php?c=carrito&a=actualizarCantidad', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            
            if (resultado.success) {
                this.carrito = resultado.carrito;
                this.actualizarVista();
                this.mostrarMensaje('Cantidad actualizada');
            } else {
                this.mostrarMensaje('Error: ' + resultado.mensaje, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarMensaje('Error de conexión: ' + error.message, 'error');
        }
    }

    async eliminarProducto(idCarrito) {
        try {
            const formData = new FormData();
            formData.append('id_carrito', idCarrito);

            const response = await fetch('index.php?c=carrito&a=eliminar', {
                method: 'POST',
                body: formData
            });

            const resultado = await response.json();
            
            if (resultado.success) {
                this.carrito = resultado.carrito;
                this.actualizarVista();
                this.mostrarMensaje('Producto eliminado del carrito');
            } else {
                this.mostrarMensaje('Error: ' + resultado.mensaje, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarMensaje('Error de conexión: ' + error.message, 'error');
        }
    }

    async vaciarCarrito() {
        if (this.carrito.length === 0) return;
        
        if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
            try {
                const response = await fetch('index.php?c=carrito&a=vaciar', {
                    method: 'POST'
                });

                const resultado = await response.json();
                
                if (resultado.success) {
                    this.carrito = [];
                    this.actualizarVista();
                    this.mostrarMensaje('Carrito vaciado');
                } else {
                    this.mostrarMensaje('Error: ' + resultado.mensaje, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                this.mostrarMensaje('Error de conexión: ' + error.message, 'error');
            }
        }
    }

    calcularSubtotal() {
        return this.carrito.reduce((total, item) => total + parseFloat(item.subtotal), 0);
    }

    calcularTotal() {
        const subtotal = this.calcularSubtotal();
        const envio = subtotal > 0 ? 15 : 0;
        return subtotal + envio;
    }

    actualizarVista() {
        this.actualizarPaginaCarrito();
        this.actualizarResumen();
        this.actualizarContadores();
    }

    actualizarPaginaCarrito() {
        const carritoContenido = document.getElementById('carritoContenido');
        if (!carritoContenido) return;

        if (this.carrito.length === 0) {
            carritoContenido.innerHTML = `
                <div class="carrito-vacio-mensaje">
                    <div class="icono">🛒</div>
                    <h3>Tu carrito está vacío</h3>
                    <p>Agrega productos desde nuestro catálogo</p>
                    <a href="index.php?c=catalogo" class="btn-ir-catalogo">Ir al Catálogo</a>
                </div>
            `;
        } else {
            carritoContenido.innerHTML = this.carrito.map(item => {
                const imagenHTML = item.imagen && item.imagen !== 'null'
                    ? `<img src="${this.obtenerRutaImagen(item.imagen)}" alt="${item.nombre}" onerror="this.style.display='none'">`
                    : '📦';
                
                return `
                    <div class="carrito-item-detalle">
                        <div class="item-imagen">${imagenHTML}</div>
                        <div class="item-info">
                            <div class="item-nombre">${item.nombre}</div>
                            <div class="item-precio">Bs. ${parseFloat(item.precio_unitario).toFixed(2)}</div>
                        </div>
                        <div class="item-controls">
                            <div class="cantidad-control">
                                <button class="btn-cantidad" onclick="carritoManager.actualizarCantidad(${item.id_carrito}, ${parseInt(item.cantidad) - 1})">-</button>
                                <span class="cantidad-numero">${item.cantidad}</span>
                                <button class="btn-cantidad" onclick="carritoManager.actualizarCantidad(${item.id_carrito}, ${parseInt(item.cantidad) + 1})">+</button>
                            </div>
                            <button class="btn-eliminar-item" onclick="carritoManager.eliminarProducto(${item.id_carrito})">
                                Eliminar
                            </button>
                        </div>
                    </div>
                `;
            }).join('') + `
                <div class="carrito-acciones-principales">
                    <a href="index.php?c=catalogo" class="btn-seguir-comprando">
                        ← Seguir Comprando
                    </a>
                    <button class="btn-vaciar-carrito-principal" onclick="carritoManager.vaciarCarrito()">
                        🗑️ Vaciar Carrito
                    </button>
                </div>
            `;
        }
    }

    obtenerRutaImagen(nombreImagen) {
        if (nombreImagen.startsWith('http') || nombreImagen.startsWith('/')) {
            return nombreImagen;
        }
        
        if (nombreImagen.includes('assets/') || nombreImagen.includes('images/')) {
            return nombreImagen;
        }
        
        return `assets/images/${nombreImagen}`;
    }

    actualizarResumen() {
        const subtotal = this.calcularSubtotal();
        const total = this.calcularTotal();
        const envio = total > 0 ? 15 : 0;

        const elementos = [
            { id: 'resumenSubtotal', valor: subtotal },
            { id: 'resumenEnvio', valor: envio },
            { id: 'resumenTotal', valor: total },
            { id: 'modalSubtotal', valor: subtotal },
            { id: 'modalEnvio', valor: envio },
            { id: 'modalTotal', valor: total }
        ];

        elementos.forEach(elemento => {
            const domElement = document.getElementById(elemento.id);
            if (domElement) {
                domElement.textContent = elemento.valor.toFixed(2);
            }
        });

        const btnProcederPago = document.getElementById('btnProcederPago');
        if (btnProcederPago) {
            btnProcederPago.disabled = this.carrito.length === 0;
        }
    }

    actualizarContadores() {
        const contadorCarrito = document.getElementById('contadorCarrito');
        if (contadorCarrito) {
            const totalItems = this.carrito.reduce((sum, item) => sum + parseInt(item.cantidad), 0);
            contadorCarrito.textContent = totalItems;
            contadorCarrito.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    mostrarMensaje(mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        const backgroundColor = tipo === 'error' ? '#e74c3c' : '#1abc9c';
        
        toast.style.cssText = `
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
        `;
        toast.textContent = mensaje;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }



    agregarEventListeners() {
        // Event delegation para todos los botones del carrito
        document.addEventListener('click', (e) => {
            // Botón proceder al pago
            if (e.target.id === 'btnProcederPago' || e.target.closest('#btnProcederPago')) {
                if (this.carrito.length > 0) {
                    const modalPago = document.getElementById('modalPago');
                    if (modalPago) modalPago.classList.add('active');
                }
            }
            
            // Cerrar modal de pago
            if (e.target.id === 'cerrarModalPago' || e.target.closest('#cerrarModalPago')) {
                const modalPago = document.getElementById('modalPago');
                if (modalPago) modalPago.classList.remove('active');
            }
            
            // Cancelar pago
            if (e.target.id === 'btnCancelarPago' || e.target.closest('#btnCancelarPago')) {
                const modalPago = document.getElementById('modalPago');
                if (modalPago) modalPago.classList.remove('active');
            }
        });

        // Formulario de pago
        const formPago = document.getElementById('formPago');
        if (formPago) {
            formPago.addEventListener('submit', (e) => {
                e.preventDefault();
                this.procesarPago();
            });
        }

        // Cerrar modal al hacer click fuera
        const modalPago = document.getElementById('modalPago');
        if (modalPago) {
            modalPago.addEventListener('click', (e) => {
                if (e.target === modalPago) {
                    modalPago.classList.remove('active');
                }
            });
        }
    }

    async procesarPago() {
        const formData = new FormData(document.getElementById('formPago'));
        const metodoPago = formData.get('metodo_pago');
        const total = this.calcularTotal();

        if (!metodoPago) {
            alert('Por favor selecciona un método de pago');
            return;
        }

        try {
            const datosPago = {
                metodo_pago: metodoPago,
                total: total
            };

            const response = await fetch('index.php?c=carrito&a=procesarPago', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(datosPago)
            });

            const resultado = await response.json();
            
            if (resultado.success) {
                this.mostrarConfirmacionPago(resultado);
            } else {
                alert('Error en el pago: ' + resultado.mensaje);
            }

        } catch (error) {
            alert('Error de conexión. Por favor intenta nuevamente.');
        }
    }

    mostrarConfirmacionPago(resultado) {
        // Crear modal de confirmación
        const modalConfirmacion = document.createElement('div');
        modalConfirmacion.className = 'modal-pago-exitoso';
        modalConfirmacion.innerHTML = `
            <div class="modal-contenido">
                <div class="confirmacion-header">
                    <div class="icono-exito">✅</div>
                    <h2>¡Pago exitoso!</h2>
                </div>
                <div class="confirmacion-body">
                    <p>Tu compra ha sido procesada correctamente.</p>
                    <div class="detalles-pago">
                        <p><strong>Número de factura:</strong> FERR-${String(resultado.numero_factura).padStart(5, '0')}</p>
                        <p><strong>Total:</strong> Bs. ${parseFloat(resultado.total).toFixed(2)}</p>
                        <p><strong>Método:</strong> ${resultado.metodo_pago}</p>
                    </div>
                </div>
                <div class="confirmacion-acciones">
                    <a href="index.php?c=carrito&a=descargarFactura&id=${resultado.numero_factura}" 
                    class="btn-descargar-factura" target="_blank">
                        📄 Descargar factura
                    </a>
                    <button class="btn-continuar" onclick="carritoManager.cerrarConfirmacion()">
                        Continuar comprando
                    </button>
                </div>
            </div>
        `;

        // Estilos para el modal de confirmación
        const estilos = document.createElement('style');
        estilos.textContent = `
            .modal-pago-exitoso {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.8);
                z-index: 2000;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            }
            .modal-pago-exitoso .modal-contenido {
                background: white;
                border-radius: 15px;
                padding: 40px;
                max-width: 500px;
                width: 90%;
                text-align: center;
                animation: slideUp 0.3s ease;
            }
            .confirmacion-header {
                margin-bottom: 25px;
            }
            .icono-exito {
                font-size: 60px;
                margin-bottom: 15px;
            }
            .confirmacion-header h2 {
                color: #27ae60;
                margin: 0;
                font-size: 28px;
            }
            .confirmacion-body {
                margin-bottom: 30px;
            }
            .confirmacion-body p {
                color: #7f8c8d;
                margin-bottom: 15px;
                font-size: 16px;
            }
            .detalles-pago {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 10px;
                text-align: left;
                margin-top: 20px;
            }
            .detalles-pago p {
                margin: 8px 0;
                color: #2c3e50;
            }
            .confirmacion-acciones {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .btn-descargar-factura {
                background: #3498db;
                color: white;
                padding: 15px;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                transition: background 0.3s;
            }
            .btn-descargar-factura:hover {
                background: #2980b9;
            }
            .btn-continuar {
                background: #1abc9c;
                color: white;
                border: none;
                padding: 15px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
            }
            .btn-continuar:hover {
                background: #16a085;
            }
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(estilos);
        document.body.appendChild(modalConfirmacion);

        // Vaciar carrito después del pago exitoso
        this.vaciarCarrito();
        this.cerrarModalPago();
    }

    cerrarConfirmacion() {
        const modal = document.querySelector('.modal-pago-exitoso');
        if (modal) {
            modal.remove();
        }
        // Redirigir al catálogo
        window.location.href = 'index.php?c=catalogo';
    }

    cerrarModalPago() {
        const modalPago = document.getElementById('modalPago');
        if (modalPago) {
            modalPago.classList.remove('active');
        }
    }
}

// Inicializar carrito manager cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.carritoManager = new CarritoManager();
});

// Función global para agregar productos desde el catálogo
function agregarAlCarrito(id, nombre, precio, imagen) {
    if (window.carritoManager) {
        window.carritoManager.agregarProducto(id, nombre, precio, imagen);
    } else {
        console.error('CarritoManager no está inicializado');
    }
}

// Agregar estilos CSS para animaciones
const style = document.createElement('style');
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