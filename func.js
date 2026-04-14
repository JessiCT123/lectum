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