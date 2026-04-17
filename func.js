function abrirUI(html) {
    const modal   = document.getElementById('ui-modal');
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
    const modal   = document.getElementById('ui-modal');
    const content = document.getElementById('ui-content');

    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}


// tarjeta de Libro


function renderCardLibro(libro) {
  const media = calcularMedia(libro.id);

  return `
    <div class="bg-[#1a1a2e] border border-[#2a2a4a] rounded-lg p-3">
      <h4 class="font-semibold text-sm mb-1">${libro.titulo}</h4>
      <p class="text-xs text-[#a8a5a0]">${libro.autor}</p>
      <div class="text-yellow-400 text-sm mt-2">★ ${media || "0.0"}</div>
      <div class="text-xs text-[#a8a5a0] mt-1">${libro.lecturas} lecturas</div>
    </div>
  `;
}


// UTILIDADES


function calcularMedia(libroId) {
  const res = window.RESENIAS || [];
  const filtradas = res.filter(r => Number(r.libro_id) === Number(libroId));

  if (filtradas.length === 0) return 0;

  const suma = filtradas.reduce((acc, r) => acc + Number(r.valoracion), 0);
  return (suma / filtradas.length).toFixed(1);
}

function getLibroById(id) {
  return (window.LIBROS || []).find(l => Number(l.id) === Number(id));
}

// DESTACADOS


function renderizarDestacados() {
  const libros = window.LIBROS || [];

  // Más leídos
  const masLeidos = [...libros]
    .sort((a, b) => b.lecturas - a.lecturas)
    .slice(0, 6);

  document.getElementById("most-read-books").innerHTML =
    masLeidos.map(renderCardLibro).join("");

  // Mejor valorados
  const mejores = [...libros]
    .sort((a, b) => calcularMedia(b.id) - calcularMedia(a.id))
    .slice(0, 6);

  document.getElementById("best-rated-books").innerHTML =
    mejores.map(renderCardLibro).join("");

  // Autores del momento
  const autores = {};
  libros.forEach(l => {
    autores[l.autor] = (autores[l.autor] || 0) + 1;
  });

  document.getElementById("trending-autores").innerHTML =
    Object.entries(autores)
      .sort((a, b) => b[1] - a[1])
      .slice(0, 6)
      .map(([autor]) => `
        <div class="bg-[#2a2a4a] p-3 rounded-lg text-sm">
          ${autor}
        </div>
      `).join("");
}

// EXPLORAR


function renderizarExplorar() {
  const libros = window.LIBROS || [];

  const genero    = document.getElementById("filter-genero").value;
  const autor     = document.getElementById("filter-autor").value.toLowerCase();
  const valoracion = document.getElementById("filter-valoracion").value;

  let filtrados = libros;

  if (genero) {
    filtrados = filtrados.filter(l => l.genero_nombre === genero);
  }

  if (autor) {
    filtrados = filtrados.filter(l =>
      l.autor.toLowerCase().includes(autor)
    );
  }

  if (valoracion === "positive") {
    filtrados = filtrados.filter(l => calcularMedia(l.id) >= 4);
  }

  if (valoracion === "negative") {
    filtrados = filtrados.filter(l => calcularMedia(l.id) < 3);
  }

  document.getElementById("explore-results").innerHTML =
    filtrados.map(renderCardLibro).join("");
}




// RESEÑAS


function renderizarResenias() {
  const lista = document.getElementById("reviews-list");
  if (!lista) return;

  const res = window.RESENIAS || [];

  if (res.length === 0) {
    lista.innerHTML = `<p class="text-sm text-[#a8a5a0]">Aún no hay reseñas.</p>`;
    return;
  }

  lista.innerHTML = res.map(r => `
    <div class="bg-[#2a2a4a] p-3 rounded-lg">
      <div class="text-sm font-semibold">${r.nombre_usuario}</div>
      <div class="text-xs text-[#a8a5a0]">${r.titulo_libro}</div>
      <div class="text-yellow-400 text-sm">★ ${r.valoracion}</div>
      <p class="text-sm mt-1">${r.texto}</p>
    </div>
  `).join("");
}
//colección

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

}

// nav
function verAutor(nombre) {
    window.location.href = `explorar.php?autor=${encodeURIComponent(nombre)}`;
}

document.addEventListener('DOMContentLoaded', () => {
    // Renderizar secciones principales
    renderizarDestacados();
    renderizarResenias();
    renderizarExplorar();

    // Si se llega desde verAutor(), activar filtro automáticamente
    const params    = new URLSearchParams(window.location.search);
    const autorUrl  = params.get('autor');

    if (autorUrl) {
        const seccionExplorar = document.getElementById('tab-explore');
        if (seccionExplorar) seccionExplorar.classList.remove('hidden');

        const buscador = document.getElementById('filter-autor');
        if (buscador) {
            buscador.value = autorUrl;
            renderizarExplorar(); // volver a filtrar  con el autor de la URL
        }
    }
});