// FUNC.JS

let pestañaActual = 'featured';
let filtroEstadoActual = 'all';

// RENDER - Crea la tarjeta visual de un libro
function crearTarjetaLibro(libro) {
    const tarjeta = document.createElement('div');
    tarjeta.className = 'book-card bg-[#2a2a4a] rounded-xl overflow-hidden cursor-pointer hover:scale-105 transition-all p-3 border border-[#3a3a5a]';
    
    // Si el libro tiene un campo 'portada' con el nombre de la imagen
    const rutaImagen = `imagenes/${libro.portada}`; 

    tarjeta.innerHTML = `
        <div class="aspect-[3/4] mb-3 overflow-hidden rounded-lg bg-[#1a1a2e]">
            <img src="${rutaImagen}" class="w-full h-full object-cover" alt="${libro.titulo}">
        </div>
        <h4 class="font-medium text-sm line-clamp-1">${libro.titulo}</h4>
        <p class="text-xs text-[#a8a5a0] mb-2">${libro.autor}</p>
        <div class="flex items-center justify-between text-xs text-[#f4a261]">
            <span>★ ${libro.valoracion || 'S/V'}</span>
            <span class="text-[#a8a5a0]">${libro.paginas} pág.</span>
        </div>
    `;

    tarjeta.addEventListener('click', () => mostrarModalLibro(libro));
    return tarjeta;
}

// PESTAÑA INICIO
function renderizarDestacados() {
    const contenedorMasLeidos = document.getElementById('most-read-books');
    const contenedorMejorValorados = document.getElementById('best-rated-books');
    
    if (!contenedorMasLeidos) return;

    contenedorMasLeidos.innerHTML = '';
    contenedorMejorValorados.innerHTML = '';

    // Lógica simple: Los 5 primeros son más leídos, los 5 con más nota mejor valorados
    const masLeidos = [...BDLibros].slice(0, 5);
    const mejorValorados = [...BDLibros].sort((a, b) => b.valoracion - a.valoracion).slice(0, 5);

    masLeidos.forEach(l => contenedorMasLeidos.appendChild(crearTarjetaLibro(l)));
    mejorValorados.forEach(l => contenedorMejorValorados.appendChild(crearTarjetaLibro(l)));
}

// PESTAÑA EXPLORAR
function renderizarExplorar() {
    const contenedor = document.getElementById('explore-results');
    const filtroGen = document.getElementById('filter-genero').value.toLowerCase();
    const filtroAutor = document.getElementById('filter-autor').value.toLowerCase();
    const filtroVal = document.getElementById('filter-valoracion').value;

    let filtrados = BDLibros.filter(l => {
        const coincideGen = !filtroGen || l.genero.toLowerCase() === filtroGen;
        const coincideAutor = !filtroAutor || l.autor.toLowerCase().includes(filtroAutor);
        const coincideVal = !filtroVal || 
            (filtroVal === 'positivas' && parseFloat(l.valoracion) >= 4) || 
            (filtroVal === 'negativas' && parseFloat(l.valoracion) <= 2);
        return coincideGen && coincideAutor && coincideVal;
    });

    contenedor.innerHTML = '';
    filtrados.forEach(l => contenedor.appendChild(crearTarjetaLibro(l)));
}

function renderizarMisLibros() {
    const contenedor = document.getElementById('my-books-list');
    const empty = document.getElementById('empty-my-books');
    if (!contenedor) return;

    contenedor.innerHTML = '';

    if (BDMisLibros.length === 0) {
        empty.classList.remove('hidden');
        return;
    }
    empty.classList.add('hidden');
    BDMisLibros.forEach(l => contenedor.appendChild(crearTarjetaLibro(l)));
}

// PESTAÑA COMPARAR PRECIOS
function renderizarComparadorPrecios() {
    const select = document.getElementById('price-book-select');
    if (!select || select.options.length > 1) return;
    BDLibros.forEach(l => {
        const opt = document.createElement('option');
        opt.value = l.id;
        opt.textContent = l.titulo;
        select.appendChild(opt);
    });
}

function mostrarPreciosLibro(idLibro) {
    const contenedor = document.getElementById('store-prices');
    const infoLibro = document.getElementById('selected-book-info');
    
    // 1. CORRECCIÓN: Usar BDLibros (que viene de tu index.php)
    const libro = (BDLibros || []).find(l => l.id == idLibro);
    
    // 2. CORRECCIÓN: Usar BDPrecios (que es donde guardaste $todosLosPrecios)
    const precios = (BDPrecios || []).filter(p => p.libro_id == idLibro);

    if (!libro) return;

    // 3. Lógica para el precio más bajo (aseguramos que sea número)
    const precioMinimo = precios.length > 0 
    ? Math.min(...precios.map(p => parseFloat(p.precio.toString().replace(',', '.'))))
    : 0;

    document.getElementById('price-empty').classList.add('hidden');
    document.getElementById('price-comparison').classList.remove('hidden');

    infoLibro.innerHTML = `
        <div class="flex items-center gap-4">
            <img src="imagenes/${libro.portada}" class="w-16 h-auto object-cover rounded shadow">
            <div>
                <h3 class="font-bold text-[#f4a261]">${libro.titulo}</h3>
                <p class="text-sm text-[#a8a5a0]">${libro.autor}</p>
            </div>
        </div>`;

    contenedor.innerHTML = '';
    precios.forEach(p => {
    const esElMasBarato = parseFloat(p.precio.toString().replace(',', '.')) === precioMinimo;
    
    const div = document.createElement('div');
    // Ajustamos el grid para que el nombre de la tienda y el precio tengan su lugar fijo
    div.className = `bg-white p-5 rounded-xl border-2 flex justify-between items-center relative shadow-sm transition-all hover:shadow-md ${esElMasBarato ? 'border-emerald-500' : 'border-gray-100'}`;
    
    div.innerHTML = `
        ${esElMasBarato ? '<span class="absolute -top-3 left-4 bg-emerald-500 text-[10px] font-bold px-2 py-1 rounded-full text-white shadow-lg flex items-center gap-1">MEJOR PRECIO</span>' : ''}
        
        <div class="flex flex-col gap-1">
    <div class="flex items-center gap-2">
        <img src="imagIconos/${p.tienda_icono}" class="w-10 h-10 object-contain" onerror="this.style.display='none'">
        <div>
            <p class="font-bold text-gray-700 leading-none">${p.tienda_nombre}</p>
            <div class="text-yellow-500 text-xs mt-1">
                ${'★'.repeat(p.estrellas)}${'☆'.repeat(5 - p.estrellas)}
            </div>
        </div>
    </div>
    <p class="text-[11px] text-gray-500 italic mt-1">
        <i class="fas fa-truck mr-1"></i>${p.envio || 'Consultar envío'}
    </p>
</div>

        <div class="flex flex-col items-center">
            <p class="text-2xl font-black ${esElMasBarato ? 'text-emerald-500' : 'text-[#f4a261]'}">${p.precio}€</p>
        </div>

        <div>
            <a href="${p.url}" target="_blank" class="bg-[#f4a261] text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-[#e76f51] transition-all shadow-md inline-block">
                IR A TIENDA
            </a>
        </div>
    `;
    contenedor.appendChild(div);
});
}

// NAVEGACIÓN
function cambiarPestana(id, libroExterno = null) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('tab-active', 'text-[#f4a261]'));
    
    const targetTab = document.getElementById(`tab-${id}`);
    if (targetTab) targetTab.classList.remove('hidden');

    const tabBtn = document.querySelector(`[data-tab="${id}"]`);
    if (tabBtn) tabBtn.classList.add('tab-active', 'text-[#f4a261]');

    // Renderizados normales
    if (id === 'featured') renderizarDestacados();
    if (id === 'explore') renderizarExplorar();
    if (id === 'my-books') renderizarMisLibros();
    if (id === 'prices') renderizarComparadorPrecios();

    // Lógica para cuando vienes desde el botón "Ver precios" del Modal explorar
    if (id === 'prices' && libroExterno) {
        // 1. Forzamos que el select tenga el ID del libro
        const select = document.getElementById('price-book-select');
        if (select) {
            select.value = libroExterno.id;
        }
        // 2. Ejecutamos la función que busca en la BD de precios y renderiza
        mostrarPreciosLibro(libroExterno.id);
    }
}

// BUSCADOR GENERAL DE LA PG WEB, BUSCA POR AUTOR/TÍTULO LIBRO
function renderizarExplorarConBusqueda(busqueda) {
    const contenedor = document.getElementById('explore-results');
    const filtroGen = document.getElementById('filter-genero').value.toLowerCase();
    const filtroVal = document.getElementById('filter-valoracion').value;

    let filtrados = BDLibros.filter(l => {
        const coincideAutor = l.autor.toLowerCase().includes(busqueda.toLowerCase()) ||
                              l.titulo.toLowerCase().includes(busqueda.toLowerCase());
        const coincideGen = !filtroGen || l.genero.toLowerCase() === filtroGen;
        const coincideVal = !filtroVal ||
            (filtroVal === 'positivas' && parseFloat(l.valoracion) >= 4) ||
            (filtroVal === 'negativas' && parseFloat(l.valoracion) <= 2);
        return coincideAutor && coincideGen && coincideVal;
    });

    contenedor.innerHTML = '';
    filtrados.forEach(l => contenedor.appendChild(crearTarjetaLibro(l)));
}
//Llena la ventana de cuando clicas sobre un libro en explorar
function mostrarModalLibro(libro) {
    const modal = document.getElementById('book-modal');
    const contenido = document.getElementById('modal-content');
    
    contenido.innerHTML = `
        <div class="flex justify-between items-start mb-4">
            <div class="flex gap-8">
                <img src="imagenes/${libro.portada}" class="portada-lateral-mini">
                
                <div class="info-lateral flex-1">
                    <h2 class="text-3xl font-bold text-[#f4a261] leading-tight">${libro.titulo}</h2>
                    <p class="text-lg text-[#a8a5a0] mb-3">${libro.autor}</p>
                    
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold uppercase mb-4">
                        <span class="border border-[#f4a261] text-[#f4a261] px-2 py-0.5 rounded">${libro.genero}</span>
                        <span class="border border-[#f4a261] text-[#f4a261] px-2 py-0.5 rounded">AÑO: ${libro.anio || '2024'}</span>
                        <span class="border border-[#f4a261] text-[#f4a261] px-2 py-0.5 rounded">${libro.paginas} PÁG.</span>
                        <span class="bg-[#f4a261] text-[#0f0f1a] px-2 py-0.5 rounded shadow-sm">${libro.valoracion || 'S/V'}</span>
                    </div>

                    <div class="caja-descripcion-derecha mb-6">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-[#a8a5a0] font-black mb-3">
                            Descripción:
                        </p>
                        ${libro.descripcion || 'Sin descripción disponible para este título.'}
                    </div>

                    <div class="mt-4">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-[#a8a5a0] font-black mb-3">
                            Añadir a mi biblioteca:
                        </p>
                        <div class="flex gap-3">
                            <button class="bg-[#7dd3fc] text-[#0f0f1a] px-4 py-2 rounded-lg font-bold hover:opacity-80 transition-all text-[10px] uppercase">
                                Leyendo
                            </button>
                            <button class="bg-[#f4a261] text-[#0f0f1a] px-4 py-2 rounded-lg font-bold hover:opacity-80 transition-all text-[10px] uppercase">
                                Pendiente
                            </button>
                            <button class="bg-[#86efac] text-[#0f0f1a] px-4 py-2 rounded-lg font-bold hover:opacity-80 transition-all text-[10px] uppercase">
                                Terminado
                            </button>
                        </div>
                    </div>
                </div>
            </div>

    <span class="text-[#a8a5a0] hover:text-[#f4a261] cursor-pointer text-base leading-none p-2 rounded-full transition-all duration-300 hover:bg-[rgba(244,162,97,0.15)] hover:shadow-[0_0_20px_rgba(244,162,97,0.5)]" onclick="cerrarModal()">✕</span>       </div>

        <div class="mt-4 pt-4 border-t border-[#f4a261]/60 flex justify-center">
            <button onclick="cerrarModal(); cambiarPestana('prices', ${JSON.stringify(libro).replace(/"/g, '&quot;')});" 
                    class="bg-transparent border border-[#f4a261] text-[#f4a261] px-8 py-2 rounded-full text-[10px] font-bold tracking-[0.2em] uppercase transition-all duration-300 hover:bg-[#f4a261] hover:text-[#0f0f1a] hover:shadow-[0_0_15px_rgba(244,162,97,0.4)]">
                Ver precios en tiendas
            </button>
        </div>
    `;

    modal.classList.replace('hidden', 'flex');
}
//Cierra la venta de cuando clicas sobre un libro
function cerrarModal() {
    const modal = document.getElementById('book-modal');
    // Cambiamos 'flex' por 'hidden' para que desaparezca
    modal.classList.replace('flex', 'hidden');
}
//Cierrra la ventana cuando clicas fuera del recuadro
window.onclick = function(event) {
    const modal = document.getElementById('book-modal');
    // Si el clic fue exactamente en el modal (el fondo) y no en su contenido
    if (event.target === modal) {
        cerrarModal();
    }
}
