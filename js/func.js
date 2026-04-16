function abrirUI(html) {
    const modal = document.getElementById('ui-modal');
    const content = document.getElementById('ui-content');
    
    content.innerHTML = html;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Pequeño delay para la animación de entrada
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function cerrarUI() {
    const modal = document.getElementById('ui-modal');
    const content = document.getElementById('ui-content');
    
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

function verAutor(nombre) {
    // Redirige a explorar.php pasando el autor en la URL
    // encodeURIComponent sirve para que nombres con espacios o tildes no rompan la URL
    window.location.href = `explorar.php?autor=${encodeURIComponent(nombre)}`;
}

function renderColeccion() {
    const grid = document.getElementById('grid-coleccion');
    const msg = document.getElementById('msg-vacio');
    const txt = document.getElementById('txt-vacio');

    if (!window.SESION_ACTIVA) {
        grid.innerHTML = '';
        msg.classList.remove('hidden');
        txt.innerText = "Inicia sesión para gestionar tu biblioteca personal.";
        return;
    }

    if (window.USER_COL.length === 0) {
        grid.innerHTML = '';
        msg.classList.remove('hidden');
        txt.innerText = "Tu colección está vacía. ¡Añade tu primer libro!";
        return;
    }

    filtrarColeccion('all');
}

function filtrarColeccion(filtro, btn = null) {
    const grid = document.getElementById('grid-coleccion');
    const msg = document.getElementById('msg-vacio');
    
    if (btn) {
        document.querySelectorAll('.status-filter-btn').forEach(b => {
            b.classList.remove('bg-[#f4a261]');
            b.classList.add('bg-[#2a2a4a]'); 
        });
        btn.classList.add('bg-[#f4a261]');
        btn.classList.remove('bg-[#2a2a4a]');
    }

    // Unir info base con estado del usuario
    let items = window.USER_COL.map(c => {
        let libro = window.DB_LIBROS.find(l => l.id == c.libro_id);
        return { ...libro, estado_usuario: c.estado };
    });

    if (filtro !== 'all') {
        items = items.filter(i => i.estado_usuario === filtro);
    }

    grid.innerHTML = '';
    
    if (items.length === 0) {
        msg.classList.remove('hidden');
        document.getElementById('txt-vacio').innerText = "No hay libros en esta categoría.";
    } else {
        msg.classList.add('hidden');
        items.forEach(libro => {
            grid.innerHTML += `
                <div class="book-card cursor-pointer group" onclick='verLibro(${JSON.stringify(libro)})'>
                    <div class="aspect-[3/4] overflow-hidden rounded shadow-sm relative">
                        <img src="img/${libro.portada}" onerror="this.src='img/default.jpg'" class="w-full h-full object-cover">
                        <div class="absolute bottom-2 right-2">
                             <span class="bg-[#c9933a] text-white text-[9px] px-2 py-1 rounded-sm uppercase font-black shadow-lg">
                                ${libro.estado_usuario}
                             </span>
                        </div>
                    </div>
                    <div class="mt-4 px-1">
                        <h4 class="font-display font-bold text-base leading-tight truncate group-hover:text-[#c9933a] transition-colors">${libro.titulo}</h4>
                        <p class="text-tinta-tenue text-xs italic mt-1 font-body">${libro.autor}</p>
                    </div>
                </div>
            `;
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const autorUrl = params.get('autor');

    if (autorUrl) {
        const seccionExplorar = document.getElementById('tab-explore');
        if (seccionExplorar) seccionExplorar.classList.remove('hidden');

        const buscador = document.getElementById('filter-autor'); 
        
        if (buscador) {
            buscador.value = autorUrl;

            buscador.dispatchEvent(new Event('input'));
        }
    }
});