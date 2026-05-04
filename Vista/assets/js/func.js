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
    const res = globalThis.RESENIAS || [];
    const filtradas = res.filter(r => Number(r.libro_id) == Number(libroId));

    if (filtradas.length == 0) return 0;

    const suma = filtradas.reduce((acc, r) => acc + Number(r.valoracion), 0);
    return (suma / filtradas.length).toFixed(1);
}

function getLibroById(id) {
    return (globalThis.LIBROS || []).find(l => Number(l.id) == Number(id));
}

// DESTACADOS


function renderizarDestacados() {
    const libros = globalThis.LIBROS || [];

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

    const genero = document.getElementById("filter-genero").value;
    const autor = document.getElementById("filter-autor").value.toLowerCase();
    const valoracion = document.getElementById("filter-valoracion").value;

    let filtrados = libros;

    if (genero) {
        filtrados = filtrados.filter(l => l.genero_nombre == genero);
    }

    if (autor) {
        filtrados = filtrados.filter(l =>
            l.autor.toLowerCase().includes(autor)
        );
    }

    if (valoracion == "positive") {
        filtrados = filtrados.filter(l => calcularMedia(l.id) >= 4);
    }

    if (valoracion == "negative") {
        filtrados = filtrados.filter(l => calcularMedia(l.id) < 3);
    }

    document.getElementById("explore-results").innerHTML =
        filtrados.map(renderCardLibro).join("");
}


// RESEÑAS

function renderizarResenias() {
    const lista = document.getElementById("reviews-list");
    if (!lista) return;

    const res = globalThis.RESENIAS || [];

    if (res.length == 0) {
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

// compararmos las reseñas 
function mostrarResenia(idLibro) {
    const contenedor = document.getElementById('resenias-box');

    // buscamos las reseñas del libro
    let todas = globalThis.RESENIAS || [];
    let resenias = idLibro ? todas.filter(r => r.libro_id == idLibro) : todas;

    if (!resenias) return;

    let reseniaCard = '';

    for (let resenia of resenias) {
        let estrellasLlenas = "★".repeat(resenia.valoracion);
        let estrellasVacias = "☆".repeat(5 - resenia.valoracion);

        reseniaCard += `<div class="review-card">
                <div class="review-header">
                    <span>${resenia.titulo_libro}</span>
                    <span>${resenia.fecha}</span>
                </div>
                <div>
                    ${estrellasLlenas}${estrellasVacias}
                </div>
                <div>
                    por ${resenia.nombre}
                </div>
                <p>${resenia.texto}</p>
            </div>
        `;
    }
    contenedor.innerHTML = reseniaCard;
}

// comparar Precios
function mostrarPreciosLibro(idLibro) {
    const contenedor = document.getElementById('store-prices');
    const infoLibro = document.getElementById('selected-book-info');

    // buscamos el libro y sus precios
    const libro = (globalThis.LIBROS || []).find(l => l.id == idLibro);
    const precios = (globalThis.PRECIOS || []).filter(p => p.libro_id == idLibro);

    if (!libro) return;

    //Buscamos el precio más bajo de todas las tiendas
    let listaPrecios = []; // creamos el array para los precios
    for (const item of precios) {
        let precio = Number.parseFloat(item.precio);
        listaPrecios.push(precio);
    }

    let precioMinimo = listaPrecios[0];
    for (const element of listaPrecios) {
        if (element < precioMinimo) {
            precioMinimo = element;
        }
    }
    document.getElementById('price-empty').classList.add('hidden');
    document.getElementById('price-comparison').classList.remove('hidden');

    let imagen = '<img src="' + window.BASE_URL + '/Vista/assets/img/' + libro.portada + '" class="w-16 h-auto object-cover rounded shadow">';
    let titulo = '<h3 class="font-bold text-[#f4a261]">' + libro.titulo + '</h3>';
    let autor = '<p class="text-sm text-[#a8a5a0]">' + libro.autor + '</p>';
    infoLibro.innerHTML = '<div class="flex items-center gap-4">' + imagen + '<div>' + titulo + autor + '</div></div>';

    // Pintamos una tarjeta por cada tienda
    contenedor.innerHTML = '';
    for (const element of precios) {
        let p = element;
        let esMasBarato = Number.parseFloat(p.precio) == precioMinimo;

        let div = document.createElement('div');
        div.className = 'bg-white p-5 rounded-xl border-2 flex justify-between items-center relative shadow-sm transition-all hover:shadow-md ' + (esMasBarato ? 'border-emerald-500' : 'border-gray-100');

        let badgeMejorPrecio = '';
        if (esMasBarato) {
            badgeMejorPrecio = '<span class="absolute -top-3 left-4 bg-emerald-500 text-[10px] font-bold px-2 py-1 rounded-full text-white shadow-lg">MEJOR PRECIO</span>';
        }

        let iconoTienda = '<img src="' + window.BASE_URL + '/Vista/assets/imagIconos/' + p.tienda_icono + '" class="w-10 h-10 object-contain">';
        let nombreTienda = '<p class="font-bold text-gray-700 leading-none">' + p.tienda_nombre + '</p>';
        let estrellas = '<div class="text-yellow-500 text-xs mt-1">' + '★'.repeat(p.estrellas) + '☆'.repeat(5 - p.estrellas) + '</div>';
        let envio = '<p class="text-[11px] text-gray-500 italic mt-1"><i class="fas fa-truck mr-1"></i>' + (p.envio || 'Consultar envío') + '</p>';
        let colorPrecio = esMasBarato ? 'text-emerald-500' : 'text-[#f4a261]';
        let precio = '<p class="text-2xl font-black ' + colorPrecio + '">' + p.precio + '€</p>';
        let boton = '<a href="' + p.url + '" target="_blank" class="bg-[#f4a261] text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-[#e76f51] transition-all shadow-md inline-block">IR A TIENDA</a>';

        div.innerHTML = badgeMejorPrecio +
            '<div class="flex flex-col gap-1"><div class="flex items-center gap-2">' + iconoTienda + '<div>' + nombreTienda + estrellas + '</div></div>' + envio + '</div>' +
            '<div class="flex flex-col items-center">' + precio + '</div>' +
            '<div>' + boton + '</div>';

        contenedor.appendChild(div);
    }
}

// pestanias
function verAutor(nombre) {
    globalThis.location.href = globalThis.BASE_URL + '/inicio/explorar?autor=' + encodeURIComponent(nombre);
}

document.addEventListener('DOMContentLoaded', () => {
    // Renderizar secciones principales, comprobamos si estamos en Index o fuera

    if (document.getElementById('most-read-books')) {
        renderizarDestacados();
    }

    if (document.getElementById('reviews-list')) {
        renderizarResenias();
    }

    if (document.getElementById('explore-results')) {
        aplicarFiltros();
    }


    // Si se llega desde verAutor(), activar filtro automáticamente
    const params = new URLSearchParams(globalThis.location.search);
    const autorUrl = params.get('autor');

    if (autorUrl) {
        const seccionExplorar = document.getElementById('tab-explore');
        if (seccionExplorar) seccionExplorar.classList.remove('hidden');

        const buscador = document.getElementById('filter-autor');
        if (buscador) {
            buscador.value = autorUrl;
            aplicarFiltros();
        }
    }

    let selectPrecios = document.getElementById('price-book-select');
    if (selectPrecios) {
        selectPrecios.addEventListener('change', function () {
            if (this.value !== '') {
                mostrarPreciosLibro(this.value);
            }
        });
    }
});

//Funciones para registros y login
const usuarioInput = document.getElementById("usuario");
const mayus = document.getElementById("mayus");
const numero = document.getElementById("numero");
const passwordInput = document.getElementById("regPassword");
const length = document.getElementById("length");

usuarioInput.addEventListener("input", function () {
    const valor = usuarioInput.value;

    // Mayúscula
    if (/[A-Z]/.test(valor)) {
        mayus.style.color = "green";
    } else {
        mayus.style.color = "red";
    }

    // Número o símbolo
    if (/[0-9!@#$%^&*()\-_:]/.test(valor)) {
        numero.style.color = "green";
    } else {
        numero.style.color = "red";
    }
});

passwordInput.addEventListener("input", function () {
    const valor = passwordInput.value;

    if (valor.length >= 8) {
        length.style.color = "green";
    } else {
        length.style.color = "red";
    }
});

function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}