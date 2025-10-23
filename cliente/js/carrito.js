// Sistema de Carrito de Compras
class CarritoManager {
    constructor() {
        this.carrito = this.obtenerCarritoLocalStorage();
        this.init();
    }

    init() {
        this.actualizarVista();
        this.agregarEventListeners();
        this.agregarDropdownListeners();
    }

    obtenerCarritoLocalStorage() {
        const carrito = localStorage.getItem('carrito_ferreteria');
        return carrito ? JSON.parse(carrito) : [];
    }

    guardarCarritoLocalStorage() {
        localStorage.setItem('carrito_ferreteria', JSON.stringify(this.carrito));
    }

    agregarProducto(producto) {
        const productoExistente = this.carrito.find(item => item.id === producto.id);
        
        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            this.carrito.push({
                ...producto,
                cantidad: 1
            });
        }
        
        this.guardarCarritoLocalStorage();
        this.actualizarVista();
        this.mostrarMensaje('Producto agregado al carrito');
        
        // Abrir el carrito automáticamente al agregar un producto
        this.abrirCarrito();
    }

    eliminarProducto(id) {
        this.carrito = this.carrito.filter(item => item.id !== id);
        this.guardarCarritoLocalStorage();
        this.actualizarVista();
        this.mostrarMensaje('Producto eliminado del carrito');
    }

    actualizarCantidad(id, nuevaCantidad) {
        if (nuevaCantidad < 1) {
            this.eliminarProducto(id);
            return;
        }

        const producto = this.carrito.find(item => item.id === id);
        if (producto) {
            producto.cantidad = nuevaCantidad;
            this.guardarCarritoLocalStorage();
            this.actualizarVista();
        }
    }

    vaciarCarrito() {
        if (this.carrito.length === 0) return;
        
        if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
            this.carrito = [];
            this.guardarCarritoLocalStorage();
            this.actualizarVista();
            this.mostrarMensaje('Carrito vaciado');
        }
    }

    calcularSubtotal() {
        return this.carrito.reduce((total, item) => total + (item.precio * item.cantidad), 0);
    }

    calcularTotal() {
        const subtotal = this.calcularSubtotal();
        const envio = subtotal > 0 ? 15 : 0;
        return subtotal + envio;
    }

    actualizarVista() {
        this.actualizarDropdown();
        this.actualizarPaginaCarrito();
        this.actualizarContadores();
    }

    actualizarDropdown() {
        const listaCarrito = document.getElementById('listaCarrito');
        const carritoTotal = document.getElementById('carritoTotal');
        
        if (!listaCarrito) return;

        if (this.carrito.length === 0) {
            listaCarrito.innerHTML = `
                <div class="carrito-vacio">
                    <div class="icono">🛒</div>
                    <p>Tu carrito está vacío</p>
                </div>
            `;
            if (carritoTotal) carritoTotal.textContent = '0.00';
        } else {
            listaCarrito.innerHTML = this.carrito.map(item => `
                <div class="carrito-item">
                    <div class="carrito-item-imagen">${item.imagen}</div>
                    <div class="carrito-item-info">
                        <div class="carrito-item-nombre">${item.nombre}</div>
                        <div class="carrito-item-precio">Bs. ${item.precio.toFixed(2)}</div>
                        <div class="carrito-item-cantidad">
                            <button onclick="carritoManager.actualizarCantidad(${item.id}, ${item.cantidad - 1})">-</button>
                            <span>${item.cantidad}</span>
                            <button onclick="carritoManager.actualizarCantidad(${item.id}, ${item.cantidad + 1})">+</button>
                        </div>
                    </div>
                    <button class="carrito-item-eliminar" onclick="carritoManager.eliminarProducto(${item.id})">
                        🗑️
                    </button>
                </div>
            `).join('');
            
            if (carritoTotal) carritoTotal.textContent = this.calcularTotal().toFixed(2);
        }
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
            carritoContenido.innerHTML = this.carrito.map(item => `
                <div class="carrito-item-detalle">
                    <div class="item-imagen">${item.imagen}</div>
                    <div class="item-info">
                        <div class="item-nombre">${item.nombre}</div>
                        <div class="item-precio">Bs. ${item.precio.toFixed(2)}</div>
                    </div>
                    <div class="item-controls">
                        <div class="cantidad-control">
                            <button class="btn-cantidad" onclick="carritoManager.actualizarCantidad(${item.id}, ${item.cantidad - 1})">-</button>
                            <span class="cantidad-numero">${item.cantidad}</span>
                            <button class="btn-cantidad" onclick="carritoManager.actualizarCantidad(${item.id}, ${item.cantidad + 1})">+</button>
                        </div>
                        <button class="btn-eliminar-item" onclick="carritoManager.eliminarProducto(${item.id})">
                            Eliminar
                        </button>
                    </div>
                </div>
            `).join('');
        }

        this.actualizarResumen();
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
            const totalItems = this.carrito.reduce((sum, item) => sum + item.cantidad, 0);
            contadorCarrito.textContent = totalItems;
            contadorCarrito.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    mostrarMensaje(mensaje) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1abc9c;
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

    agregarDropdownListeners() {
        const iconoCarrito = document.getElementById('iconoCarrito');
        const dropdownCarrito = document.getElementById('dropdownCarrito');
        const overlayNotificaciones = document.getElementById('overlayNotificaciones');

        if (!iconoCarrito || !dropdownCarrito) return;

        const abrirCarrito = () => {
            dropdownCarrito.classList.add('active');
            if (overlayNotificaciones) {
                overlayNotificaciones.style.display = 'block';
            }
        };

        const cerrarCarrito = () => {
            dropdownCarrito.classList.remove('active');
            if (overlayNotificaciones) {
                overlayNotificaciones.style.display = 'none';
            }
        };

        this.abrirCarrito = abrirCarrito;
        this.cerrarCarrito = cerrarCarrito;

        // Event listener para el icono del carrito
        iconoCarrito.addEventListener('click', (e) => {
            e.stopPropagation();
            if (dropdownCarrito.classList.contains('active')) {
                cerrarCarrito();
            } else {
                // Cerrar notificaciones si están abiertas
                const dropdownNotificaciones = document.getElementById('dropdownNotificaciones');
                if (dropdownNotificaciones && dropdownNotificaciones.classList.contains('active')) {
                    dropdownNotificaciones.classList.remove('active');
                }
                abrirCarrito();
            }
        });

        // Cerrar al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!dropdownCarrito.contains(e.target) && !iconoCarrito.contains(e.target)) {
                cerrarCarrito();
            }
        });

        // Cerrar con tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cerrarCarrito();
            }
        });

        // Usar el overlay existente para cerrar
        if (overlayNotificaciones) {
            overlayNotificaciones.addEventListener('click', cerrarCarrito);
        }
    }

    agregarEventListeners() {
        const btnVaciarCarrito = document.getElementById('btnVaciarCarrito');
        if (btnVaciarCarrito) {
            btnVaciarCarrito.addEventListener('click', () => this.vaciarCarrito());
        }

        const btnVaciarCarritoPrincipal = document.getElementById('btnVaciarCarritoPrincipal');
        if (btnVaciarCarritoPrincipal) {
            btnVaciarCarritoPrincipal.addEventListener('click', () => this.vaciarCarrito());
        }

        const btnProcederPago = document.getElementById('btnProcederPago');
        const modalPago = document.getElementById('modalPago');
        const cerrarModalPago = document.getElementById('cerrarModalPago');
        const btnCancelarPago = document.getElementById('btnCancelarPago');
        const formPago = document.getElementById('formPago');

        if (btnProcederPago) {
            btnProcederPago.addEventListener('click', () => {
                if (this.carrito.length > 0) {
                    modalPago.classList.add('active');
                }
            });
        }

        if (cerrarModalPago) {
            cerrarModalPago.addEventListener('click', () => {
                modalPago.classList.remove('active');
            });
        }

        if (btnCancelarPago) {
            btnCancelarPago.addEventListener('click', () => {
                modalPago.classList.remove('active');
            });
        }

        if (formPago) {
            formPago.addEventListener('submit', (e) => {
                e.preventDefault();
                this.procesarPago();
            });
        }

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
            // Preparar datos del carrito para enviar al servidor
            const datosPago = {
                metodo_pago: metodoPago,
                total: total,
                carrito: JSON.stringify(this.carrito)
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
        window.carritoManager.agregarProducto({
            id: parseInt(id),
            nombre: nombre,
            precio: parseFloat(precio),
            imagen: imagen
        });
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