class BusquedaManager {
    constructor() {
        this.init();
    }

    init() {
        this.agregarEventListeners();
        this.inicializarAutocompletado();
    }

    agregarEventListeners() {
        const formBusqueda = document.getElementById('formBusqueda');
        const inputBusqueda = document.getElementById('inputBusqueda');

        if (formBusqueda && inputBusqueda) {
            // Buscar al presionar enter
            inputBusqueda.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.realizarBusqueda();
                }
            });

            // Buscar al hacer click en el icono de lupa
            const iconoLupa = document.querySelector('.icono-lupa');
            if (iconoLupa) {
                iconoLupa.addEventListener('click', () => {
                    this.realizarBusqueda();
                });
            }
        }
    }

    realizarBusqueda() {
        const inputBusqueda = document.getElementById('inputBusqueda');
        const termino = inputBusqueda.value.trim();

        if (termino.length > 0) {
            // Redirigir a la pagina de resultados de busqueda
            window.location.href = `index.php?c=catalogo&a=buscar&q=${encodeURIComponent(termino)}`;
        }
    }

    inicializarAutocompletado() {
        const inputBusqueda = document.getElementById('inputBusqueda');
        
        if (!inputBusqueda) return;

        // Sugerencias de busqueda
        const sugerencias = [
            'martillo', 'taladro', 'pintura', 'cemento', 'tubería',
            'destornillador', 'llave inglesa', 'brocha', 'foco led',
            'cable eléctrico', 'interruptor', 'grifo', 'ducha'
        ];

        inputBusqueda.addEventListener('input', (e) => {
            const valor = e.target.value.toLowerCase();
            
            // Limpiar sugerencias anteriores
            this.limpiarAutocompletado();

            if (valor.length > 2) {
                const coincidencias = sugerencias.filter(sugerencia => 
                    sugerencia.toLowerCase().includes(valor)
                ).slice(0, 5);

                if (coincidencias.length > 0) {
                    this.mostrarSugerencias(coincidencias, valor);
                }
            }
        });

        // Cerrar sugerencias al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-bar')) {
                this.limpiarAutocompletado();
            }
        });
    }

    mostrarSugerencias(sugerencias, termino) {
        const inputBusqueda = document.getElementById('inputBusqueda');
        const searchBar = inputBusqueda.closest('.search-bar');

        // Crear contenedor de sugerencias
        const contenedorSugerencias = document.createElement('div');
        contenedorSugerencias.className = 'sugerencias-autocompletado';

        sugerencias.forEach(sugerencia => {
            const elemento = document.createElement('div');
            elemento.className = 'sugerencia-item';
            elemento.textContent = sugerencia;
            elemento.addEventListener('click', () => {
                inputBusqueda.value = sugerencia;
                this.realizarBusqueda();
            });
            contenedorSugerencias.appendChild(elemento);
        });

        // Remover sugerencias anteriores y agregar nuevas
        this.limpiarAutocompletado();
        searchBar.appendChild(contenedorSugerencias);
    }

    limpiarAutocompletado() {
        const sugerencias = document.querySelector('.sugerencias-autocompletado');
        if (sugerencias) {
            sugerencias.remove();
        }
    }
}

// Inicializar cuando el dom este listo
document.addEventListener('DOMContentLoaded', () => {
    window.busquedaManager = new BusquedaManager();
});