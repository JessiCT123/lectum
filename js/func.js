// FUNC.JS — Lógica principal de la aplicación - Separado para mejorar mantenibilidad.
// Secciones: SDK, Render, Modales, UI y Auth

// SDK - Manejador de cambios en los datos del usuario (dataSdk).
// Se dispara cada vez que datosUser cambia en la plataforma.
const gestionDatos = {
  onDataChanged(datos) {
    datosUser = datos;
    totalLibros = datos.length;
    renderizarMisLibros();
    actualizarSelectorResenas();
    renderizarResenas();
  }
};

// Inicializa los dos SDKs de la plataforma:
// - elementSdk: personalización visual (colores, fuentes, textos)
// - dataSdk: persistencia de la colección del usuario
async function iniciarSDKs() {

  if (window.elementSdk) {
    window.elementSdk.init({
      configPorDefecto,

      // Se ejecuta cada vez que el usuario cambia la configuración visual
      onConfigChange: async (configuracionSDK) => {
        const configuracionFinal = { ...configPorDefecto, ...configuracionSDK };

        // Textos editables del header
        document.getElementById('app-title').textContent = configuracionFinal.app_title;
        document.getElementById('welcome-msg').textContent = configuracionFinal.welcome_message;

        // Colores globales
        document.body.style.backgroundColor = configuracionFinal.background_color;
        document.body.style.color = configuracionFinal.text_color;
        document.body.style.fontFamily = `${configuracionFinal.font_family}, system-ui, sans-serif`;

        // Actualiza elementos con colores de la paleta
        document.querySelectorAll('.bg-\\[\\#1a1a2e\\]').forEach(el => { el.style.backgroundColor = configuracionFinal.surface_color; });
        document.querySelectorAll('.text-\\[\\#f4a261\\]').forEach(el => { el.style.color = configuracionFinal.primary_action; });
        document.querySelectorAll('.bg-\\[\\#f4a261\\]').forEach(el  => { el.style.backgroundColor = configuracionFinal.primary_action; });
        document.querySelectorAll('.bg-\\[\\#2a9d8f\\]').forEach(el  => { el.style.backgroundColor = configuracionFinal.secondary_action; });
      },

      // Expone los valores editables al panel de personalización
      mapToCapabilities: (configuracionSDK) => {
        const configuracionFinal = { ...configPorDefecto, ...configuracionSDK };
        return {
          recolorables: [
            { get: () => configuracionFinal.background_color, set: (valor) => { configuracionFinal.background_color = valor; window.elementSdk.setConfig({ background_color: valor }); } },
            { get: () => configuracionFinal.surface_color, set: (valor) => { configuracionFinal.surface_color = valor; window.elementSdk.setConfig({ surface_color: valor }); } },
            { get: () => configuracionFinal.text_color, set: (valor) => { configuracionFinal.text_color = valor; window.elementSdk.setConfig({ text_color: valor }); } },
            { get: () => configuracionFinal.primary_action, set: (valor) => { configuracionFinal.primary_action = valor; window.elementSdk.setConfig({ primary_action: valor }); } },
            { get: () => configuracionFinal.secondary_action, set: (valor) => { configuracionFinal.secondary_action = valor; window.elementSdk.setConfig({ secondary_action: valor }); } }
          ],
          borderables:  [],
          fontEditable: { get: () => configuracionFinal.font_family, set: (valor) => { configuracionFinal.font_family = valor; window.elementSdk.setConfig({ font_family: valor }); } },
          fontSizeable: { get: () => configuracionFinal.font_size, set: (valor) => { configuracionFinal.font_size = valor; window.elementSdk.setConfig({ font_size: valor }); } }
        };
      },

      // Valores que aparecen en el panel de edición de texto
      mapToEditPanelValues: (configuracionSDK) => {
        const configuracionFinal = { ...configPorDefecto, ...configuracionSDK };
        return new Map([
          ["app_title", configuracionFinal.app_title],
          ["welcome_message", configuracionFinal.welcome_message]
        ]);
      }
    });
  }

  if (window.dataSdk) {
    const resultado = await window.dataSdk.init(gestionDatos);
    if (!resultado.isOk) console.error("Failed to initialize Data SDK");
  }
}

// RENDER - Crea y devuelve una tarjeta de libro como elemento DOM.
// mostrarEstado muestra el badge de estado (leyendo/terminado/pendiente).
function crearTarjetaLibro(libro, datoLibroUsuario = null, mostrarEstado = false) {
  const tarjeta = document.createElement('div');
  tarjeta.className = 'book-card bg-[#2a2a4a] rounded-xl overflow-hidden cursor-pointer slide-up';
  tarjeta.style.animationDelay = `${Math.random() * 0.2}s`;

  const badgeEstado = datoLibroUsuario ? obtenerBadgeEstado(datoLibroUsuario.status) : '';
  const valoracionUsuario = datoLibroUsuario?.user_valoracion
    ? `<div class="text-xs text-[#f4a261]">${'★'.repeat(datoLibroUsuario.user_valoracion)}${'☆'.repeat(5 - datoLibroUsuario.user_valoracion)}</div>`
    : '';

  tarjeta.innerHTML = `
    <div class="aspect-[3/4] bg-gradient-to-br from-[#3a3a5a] to-[#2a2a4a] flex items-center justify-center text-5xl relative">
      ${libro.portada}
      ${mostrarEstado && badgeEstado ? `<div class="absolute top-2 right-2 status-badge">${badgeEstado}</div>` : ''}
    </div>
    <div class="p-3">
      <h4 class="font-medium text-sm line-clamp-2 mb-1">${libro.titulo}</h4>
      <p class="text-xs text-[#a8a5a0] mb-1">${libro.autor}</p>
      <div class="flex items-center justify-between text-xs">
        <span class="text-[#f4a261]">${'★'.repeat(Math.floor(libro.valoracion))} ${libro.valoracion}</span>
      </div>
      ${valoracionUsuario}
    </div>
  `;

  tarjeta.addEventListener('click', () => mostrarModalLibro(libro, datoLibroUsuario));
  return tarjeta;
}

// Devuelve el HTML del badge de estado de un libro
function obtenerBadgeEstado(status) {
  const badges = {
    reading:   '<span class="bg-[#2a9d8f] text-white text-xs px-2 py-1 rounded-full">📖 Leyendo</span>',
    completed: '<span class="bg-[#28a745] text-white text-xs px-2 py-1 rounded-full">✅ Terminado</span>',
    pending:   '<span class="bg-[#f4a261] text-[#0f0f1a] text-xs px-2 py-1 rounded-full">📋 Pendiente</span>'
  };
  return badges[status] || '';
}

// Devuelve el texto del estado de un libro
function obtenerEtiquetaEstado(status) {
  const etiquetas = { reading: '📖 Leyendo', completed: '✅ Terminado', pending: '📋 Pendiente' };
  return etiquetas[status] || '';
}

// Renderiza los tres bloques de la pestaña "Destacados": más leídos, mejor valorados y autores del momento
function renderizarDestacados() {

  // Más leídos
  const masLeidos = [...BDLibros].sort((a, b) => b.lecturas - a.lecturas).slice(0, 5);
  const contenedorMasLeidos = document.getElementById('most-read-books');
  contenedorMasLeidos.innerHTML = '';
  masLeidos.forEach(libro => {
    contenedorMasLeidos.appendChild(crearTarjetaLibro(libro, datosUser.find(u => u.book_id === libro.id)));
  });

  // Mejor valorados
  const mejorValorados = [...BDLibros].sort((a, b) => b.valoracion - a.valoracion).slice(0, 5);
  const contenedorMejorValorados = document.getElementById('best-rated-books');
  contenedorMejorValorados.innerHTML = '';
  mejorValorados.forEach(libro => {
    contenedorMejorValorados.appendChild(crearTarjetaLibro(libro, datosUser.find(u => u.book_id === libro.id)));
  });

  // Autores del momento
  const autores = [...new Set(BDLibros.map(b => b.autor))].slice(0, 8);
  const contenedorAutores = document.getElementById('trending-autores');
  contenedorAutores.innerHTML = '';
  autores.forEach(autor => {
    const librosAutor     = BDLibros.filter(b => b.autor === autor);
    const valoracionMedia = (librosAutor.reduce((sum, b) => sum + b.valoracion, 0) / librosAutor.length).toFixed(1);
    const tarjeta = document.createElement('div');
    tarjeta.className = 'bg-[#2a2a4a] rounded-xl p-4 cursor-pointer hover:bg-[#3a3a5a] transition-colors';
    tarjeta.innerHTML = `
      <div class="text-3xl mb-2">✍️</div>
      <h4 class="font-medium text-sm">${autor}</h4>
      <p class="text-xs text-[#a8a5a0]">${librosAutor.length} libros • ${valoracionMedia}⭐</p>
    `;
    tarjeta.addEventListener('click', () => {
      document.getElementById('filter-autor').value = autor;
      cambiarPestana('explore');
      renderizarExplorar();
    });
    contenedorAutores.appendChild(tarjeta);
  });
}

// Renderiza los resultados de la pestaña "Explorar" aplicando filtros de género, valoración y autor
function renderizarExplorar() {
  const genero = document.getElementById('filter-genero').value;
  const filtroValoracion = document.getElementById('filter-valoracion').value;
  const autor = document.getElementById('filter-autor').value.toLowerCase();

  let filtrados = [...BDLibros];
  if (genero) filtrados = filtrados.filter(b => b.genero === genero);
  if (autor) filtrados = filtrados.filter(b => b.autor.toLowerCase().includes(autor));
  if (filtroValoracion === 'positive') filtrados = filtrados.filter(b => b.valoracion >= 4);
  if (filtroValoracion === 'negative') filtrados = filtrados.filter(b => b.valoracion < 3);

  const contenedor = document.getElementById('explore-results');
  contenedor.innerHTML = '';

  if (filtrados.length === 0) {
    contenedor.innerHTML = '<div class="col-span-full text-center py-8 text-[#a8a5a0]">No se encontraron libros con estos filtros</div>';
    return;
  }

  filtrados.forEach(libro => {
    contenedor.appendChild(crearTarjetaLibro(libro, datosUser.find(u => u.book_id === libro.id)));
  });
}

// Renderiza la colección personal del usuario (en "Mis Libros").
function renderizarMisLibros() {
  const contenedor = document.getElementById('my-books-list');
  const estadoVacio = document.getElementById('empty-my-books');

  let filtrados = [...datosUser];
  if (filtroEstadoActual !== 'all') { // Filtra por filtroEstadoActual si no es 'all'.
    filtrados = filtrados.filter(u => u.status === filtroEstadoActual);
  }

  contenedor.innerHTML = '';

  if (datosUser.length === 0) {
    contenedor.classList.add('hidden');
    estadoVacio.classList.remove('hidden');
    return;
  }

  estadoVacio.classList.add('hidden');
  contenedor.classList.remove('hidden');

  if (filtrados.length === 0) {
    contenedor.innerHTML = '<div class="col-span-full text-center py-8 text-[#a8a5a0]">No hay libros en esta categoría</div>';
    return;
  }

  filtrados.forEach(libroUsuario => {
    const libro = BDLibros.find(b => b.id === libroUsuario.book_id);
    if (libro) contenedor.appendChild(crearTarjetaLibro(libro, libroUsuario, true));
  });
}

// Actualiza el selector de libros del formulario de reseñas con los libros que el usuario tiene en su colección
function actualizarSelectorResenas() {
  const selector = document.getElementById('valoracion-book-select');
  const valorActual = selector.value;
  selector.innerHTML = '<option value="">Selecciona un libro de tu colección...</option>';

  datosUser.forEach(libroUsuario => {
    const libro = BDLibros.find(b => b.id === libroUsuario.book_id);
    if (libro) {
      const opcion = document.createElement('option');
      opcion.value = libro.id;
      opcion.textContent = `${libro.titulo} - ${libro.autor}`;
      selector.appendChild(opcion);
    }
  });

  if (valorActual) selector.value = valorActual;
}

// Renderiza el listado de reseñas ("Reseñas") y combina las reseñas del usuario con las de la comunidad.
function renderizarResenas() {
  const contenedor = document.getElementById('reviews-list');
  contenedor.innerHTML = '';

  // Reseñas del usuario guardadas en dataSdk
  const resenasUsuario = datosUser
    .filter(u => u.user_review && u.user_review.trim())
    .map(u => {
      const libro = BDLibros.find(b => b.id === u.book_id);
      return {
        book_id: u.book_id,
        tituloLibro: libro?.titulo || 'Libro desconocido',
        usuario: 'Tú',
        valoracion: u.user_valoracion || 0,
        texto: u.user_review,
        fecha: u.added_at ? new Date(u.added_at).toLocaleDateString() : 'Hoy',
        esDelUsuario: true
      };
    });

  // Reseñas de la comunidad (datos estáticos en data.js)
  const todasResenas = [
    ...resenasUsuario,
    ...reseñasComunidad.map(r => {
      const libro = BDLibros.find(b => b.id === r.idLibro);
      return { ...r, tituloLibro: libro?.titulo || 'Libro desconocido', esDelUsuario: false };
    })
  ];

  if (todasResenas.length === 0) {
    contenedor.innerHTML = '<p class="text-[#a8a5a0] text-sm text-center py-4">No hay reseñas aún. ¡Sé el primero en compartir tu opinión!</p>';
    return;
  }

  todasResenas.slice(0, 10).forEach(resena => {
    const div = document.createElement('div');
    div.className = `bg-[#2a2a4a] rounded-lg p-4 ${resena.esDelUsuario ? 'border-l-4 border-[#f4a261]' : ''}`;
    div.innerHTML = `
      <div class="flex items-start justify-between mb-2">
        <div>
          <span class="font-medium text-sm ${resena.esDelUsuario ? 'text-[#f4a261]' : ''}">${resena.usuario || resena.user}</span>
          <span class="text-xs text-[#6a6a8a] ml-2">${resena.fecha}</span>
        </div>
        <span class="text-[#f4a261] text-sm">${'★'.repeat(resena.valoracion)}${'☆'.repeat(5 - resena.valoracion)}</span>
      </div>
      <p class="text-xs text-[#a8a5a0] mb-2">📖 ${resena.tituloLibro}</p>
      <p class="text-sm">${resena.texto || resena.text}</p>
    `;
    contenedor.appendChild(div);
  });
}

// Renderiza la comparación de precios de un libro entre tiendas.
// Los precios son simulados con base aleatoria.
function renderizarComparadorPrecios(idLibro) {
  const libro = BDLibros.find(b => b.id === idLibro);
  if (!libro) {
    document.getElementById('price-comparison').classList.add('hidden');
    document.getElementById('price-empty').classList.remove('hidden');
    return;
  }

  document.getElementById('price-empty').classList.add('hidden');
  document.getElementById('price-comparison').classList.remove('hidden');

  document.getElementById('selected-book-info').innerHTML = `
    <div class="flex items-center gap-4">
      <div class="text-4xl">${libro.portada}</div>
      <div>
        <h3 class="font-display font-bold text-lg">${libro.titulo}</h3>
        <p class="text-sm text-[#a8a5a0]">${libro.autor}</p>
      </div>
    </div>
  `;

  // URLs de búsqueda por tienda
  const urlsTiendas = {
    'Amazon': `https://www.amazon.es/s?k=${encodeURIComponent(libro.titulo + ' ' + libro.autor)}`,
    'Casa del Libro':  `https://www.casadellibro.com/buscar/${encodeURIComponent(libro.titulo)}`,
    'El Corte Inglés': `https://www.elcorteingles.es/libros/buscador?q=${encodeURIComponent(libro.titulo)}`,
    'Fnac': `https://www.fnac.es/SearchResult/ResultSet.aspx?Search=${encodeURIComponent(libro.titulo)}`,
    'Iberlibro': `https://www.iberlibro.com/servlet/SearchResults?kn=${encodeURIComponent(libro.titulo)}`
  };

  // Precio base aleatorio entre 15€ y 35€, con variación por tienda
  const precioBase = 15 + Math.random() * 20;
  const precios = tiendas
    .map(tienda => ({
      ...tienda,
      precio:  (precioBase + (Math.random() * 10 - 5)).toFixed(2),
      enStock: Math.random() > 0.2
    }))
    .sort((a, b) => parseFloat(a.precio) - parseFloat(b.precio));

  const contenedorPrecios = document.getElementById('store-prices');
  contenedorPrecios.innerHTML = '';

  precios.forEach((tienda, indice) => {
    const esMejor = indice === 0;
    const tarjeta = document.createElement('div');
    tarjeta.className = `bg-[#2a2a4a] rounded-xl p-4 ${esMejor ? 'ring-2 ring-[#2a9d8f]' : ''} ${!tienda.enStock ? 'opacity-60' : ''}`;
    tarjeta.innerHTML = `
      ${esMejor ? '<div class="text-xs text-[#2a9d8f] font-medium mb-2">💚 MEJOR PRECIO</div>' : ''}
      <div class="flex items-center gap-3 mb-3">
        <span class="text-2xl">${tienda.icono}</span>
        <div>
          <h4 class="font-medium">${tienda.nombre}</h4>
          <div class="flex items-center gap-1 text-xs text-[#a8a5a0]">
            ${'★'.repeat(tienda.estrellas)}${'☆'.repeat(5 - tienda.estrellas)} Confianza
          </div>
        </div>
      </div>
      <div class="text-2xl font-bold text-[#f4a261] mb-2">${tienda.precio}€</div>
      <p class="text-xs text-[#a8a5a0] mb-3">${tienda.envio}</p>
      ${tienda.enStock
        ? `<a href="${urlsTiendas[tienda.nombre]}" target="_blank" rel="noopener noreferrer" class="block w-full bg-[#f4a261] text-[#0f0f1a] py-2 rounded-lg text-sm font-medium hover:bg-[#e76f51] transition-colors text-center">Ver en ${tienda.nombre}</a>`
        : `<div class="text-center text-sm text-[#dc3545]">Sin stock</div>`
      }
    `;
    contenedorPrecios.appendChild(tarjeta);
  });
}

//  MODALES - Abre el modal de detalle de un libro.
// Muestra info, estado actual y botones de acción.
function mostrarModalLibro(libro, datoLibroUsuario = null) {
  const modal = document.getElementById('book-modal');
  const contenido = document.getElementById('modal-content');
  const estaEnColeccion = datosUser.some(u => u.book_id === libro.id);
  const estadoActual = datoLibroUsuario?.status || '';

  contenido.innerHTML = `
    <div class="flex justify-between items-start mb-4">
      <div class="text-6xl">${libro.portada}</div>
      <button onclick="cerrarModal()" class="text-[#a8a5a0] hover:text-white text-2xl">&times;</button>
    </div>
    <h2 class="font-display text-2xl font-bold text-[#f4a261] mb-2">${libro.titulo}</h2>
    <p class="text-[#a8a5a0] mb-4">${libro.autor}</p>

    <div class="flex flex-wrap gap-2 mb-4">
      <span class="bg-[#2a2a4a] px-3 py-1 rounded-full text-xs">${libro.genero}</span>
      <span class="bg-[#2a2a4a] px-3 py-1 rounded-full text-xs">${libro.año}</span>
      <span class="bg-[#2a2a4a] px-3 py-1 rounded-full text-xs">${libro.paginas} páginas</span>
      <span class="bg-[#f4a261] text-[#0f0f1a] px-3 py-1 rounded-full text-xs">${libro.valoracion}⭐</span>
    </div>

    <p class="text-sm mb-6">${libro.descripcion}</p>

    ${estaEnColeccion ? `
      <div class="mb-4 p-3 bg-[#2a2a4a] rounded-lg">
        <p class="text-sm text-[#a8a5a0] mb-2">Estado actual: ${obtenerEtiquetaEstado(estadoActual)}</p>
      </div>` : ''}

    <div class="space-y-2">
      <p class="text-sm text-[#a8a5a0] mb-2">${estaEnColeccion ? 'Cambiar estado:' : 'Añadir a mi biblioteca:'}</p>
      <div class="flex flex-wrap gap-2">
        <button onclick="añadirAColeccion('${libro.id}', 'reading')" class="flex-1 min-w-24 bg-[#2a9d8f] text-white py-2 px-4 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity ${estadoActual === 'reading'   ? 'ring-2 ring-white' : ''}">📖 Leyendo</button>
        <button onclick="añadirAColeccion('${libro.id}', 'pending')" class="flex-1 min-w-24 bg-[#f4a261] text-[#0f0f1a] py-2 px-4 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity ${estadoActual === 'pending'   ? 'ring-2 ring-white' : ''}">📋 Pendiente</button>
        <button onclick="añadirAColeccion('${libro.id}', 'completed')" class="flex-1 min-w-24 bg-[#28a745] text-white py-2 px-4 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity ${estadoActual === 'completed' ? 'ring-2 ring-white' : ''}">✅ Terminado</button>
      </div>
      ${estaEnColeccion ? `
        <button onclick="eliminarDeColeccion('${libro.id}')"class="w-full bg-[#dc3545] text-white py-2 px-4 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity mt-2">🗑️ Quitar de mi biblioteca</button>
      ` : ''}
    </div>

    <div class="mt-6 pt-4 border-t border-[#2a2a4a]">
      <button onclick="compararPrecioLibro('${libro.id}')"class="w-full bg-[#2a2a4a] text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-[#3a3a5a] transition-colors">💰 Ver precios</button>
    </div>
  `;

  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

// Cierra el modal de detalle de libro
function cerrarModal() {
  const modal = document.getElementById('book-modal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
}

// Añade/actualiza un libro en la colección del usuario via dataSdk.
// Si ya existe, actualiza el estado si no, crea un nuevo registro.
async function añadirAColeccion(idLibro, estado) {
  const existente = datosUser.find(u => u.book_id === idLibro);

  if (existente) {
    const resultado = await window.dataSdk.update({ ...existente, status: estado });
    if (resultado.isOk) { mostrarToast(`Estado actualizado a "${obtenerEtiquetaEstado(estado)}"`); cerrarModal();
    } else mostrarToast('Error al actualizar el estado');
  } else {
    if (totalLibros >= 999) { mostrarToast('Has alcanzado el límite de 999 libros'); return; }
    const resultado = await window.dataSdk.create({
      book_id: idLibro,
      status: estado,
      user_review: '',
      user_valoracion: 0,
      added_at: new Date().toISOString()
    });
    if (resultado.isOk) { mostrarToast(`Libro añadido como "${obtenerEtiquetaEstado(estado)}"`); cerrarModal();
    } else mostrarToast('Error al añadir el libro');
  }
}

// Elimina un libro de la colección del usuario
async function eliminarDeColeccion(idLibro) {
  if (!window.dataSdk) return;
  const existente = datosUser.find(u => u.book_id === idLibro);
  if (!existente) return;
  const resultado = await window.dataSdk.delete(existente);
  if (resultado.isOk) { mostrarToast('Libro eliminado de tu biblioteca'); cerrarModal(); }
  else mostrarToast('Error al eliminar el libro');
}

// Cierra el modal de libro y navega al comparador con el libro seleccionado
function compararPrecioLibro(idLibro) {
  cerrarModal();
  cambiarPestana('prices');
  document.getElementById('price-book-select').value = idLibro;
  renderizarComparadorPrecios(idLibro);
}

//  UI
// Cambia la pestaña activa y renderiza su contenido
function cambiarPestana(nombrePestana) {
  // Resetear todas las pestañas
  document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.classList.remove('tab-active');
    tab.classList.add('text-[#a8a5a0]');
  });

  // Activar la pestaña seleccionada
  document.querySelector(`[data-tab="${nombrePestana}"]`).classList.add('tab-active');
  document.querySelector(`[data-tab="${nombrePestana}"]`).classList.remove('text-[#a8a5a0]');

  // Mostrar solo la sección correspondiente
  document.querySelectorAll('.tab-content').forEach(contenido => contenido.classList.add('hidden'));
  document.getElementById(`tab-${nombrePestana}`).classList.remove('hidden');

  pestañaActual = nombrePestana;

  if (nombrePestana === 'featured') renderizarDestacados();
  if (nombrePestana === 'explore')  renderizarExplorar();
  if (nombrePestana === 'my-books') renderizarMisLibros();
  if (nombrePestana === 'reviews')  renderizarResenas();
}

// Muestra una notificación flotante durante 3 segundos
function mostrarToast(mensaje) {
  const toast        = document.getElementById('toast');
  const mensajeToast = document.getElementById('toast-message');
  mensajeToast.textContent = mensaje;
  toast.classList.remove('translate-y-20', 'opacity-0');
  setTimeout(() => { toast.classList.add('translate-y-20', 'opacity-0'); }, 3000);
}

// AUTH - Abre el modal de perfil.
// Si hay sesión activa muestra las estadísticas del usuario si no muestra el formulario de login/registro.
function mostrarModalPerfil() {
  const modal     = document.getElementById('profile-modal');
  const contenido = document.getElementById('profile-content');

  if (usuarioActual) {
    const totalLibrosColeccion = datosUser.length;
    const librosTerminados = datosUser.filter(b => b.status === 'completed').length;
    const totalResenas = datosUser.filter(b => b.user_review).length;

    contenido.innerHTML = `
      <div class="flex justify-between items-start mb-4">
        <div>
          <div class="text-4xl mb-2">👤</div>
          <h2 class="font-display text-2xl font-bold text-[#f4a261]">${usuarioActual.username}</h2>
          <p class="text-sm text-[#a8a5a0]">Miembro desde hoy</p>
        </div>
        <button onclick="cerrarModalPerfil()" class="text-[#a8a5a0] hover:text-white text-2xl">&times;</button>
      </div>
      <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-[#2a2a4a] rounded-lg p-3 text-center"><div class="text-2xl font-bold text-[#f4a261]">${totalLibrosColeccion}</div><div class="text-xs text-[#a8a5a0]">Libros</div></div>
        <div class="bg-[#2a2a4a] rounded-lg p-3 text-center"><div class="text-2xl font-bold text-[#2a9d8f]">${librosTerminados}</div><div class="text-xs text-[#a8a5a0]">Terminados</div></div>
        <div class="bg-[#2a2a4a] rounded-lg p-3 text-center"><div class="text-2xl font-bold text-[#e76f51]">${totalResenas}</div><div class="text-xs text-[#a8a5a0]">Reseñas</div></div>
      </div>
      <button onclick="cerrarSesion()" class="w-full bg-[#dc3545] text-white py-2 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">🚪 Cerrar Sesión</button>
    `;
  } else {
    contenido.innerHTML = `
      <div class="flex justify-between items-start mb-4">
        <h2 class="font-display text-2xl font-bold text-[#f4a261]">Mi Biblioteca</h2>
        <button onclick="cerrarModalPerfil()" class="text-[#a8a5a0] hover:text-white text-2xl">&times;</button>
      </div>
      <div class="mb-6 p-4 bg-[#2a2a4a] rounded-lg text-center">
        <div class="text-4xl mb-2">📚</div>
        <p class="text-sm text-[#a8a5a0] mb-4">Inicia sesión para guardar tu biblioteca y sincronizar en todos tus dispositivos</p>
      </div>
      <form id="auth-form" class="space-y-3">
        <input type="text" id="auth-username" placeholder="Usuario"    class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]" required>
        <input type="password" id="auth-password" placeholder="Contraseña" class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]" required>
        <button type="submit" class="w-full bg-[#f4a261] text-[#0f0f1a] py-2 rounded-lg text-sm font-medium hover:bg-[#e76f51] transition-colors">🔐 Iniciar Sesión</button>
        <button type="button" id="toggle-signup" class="w-full bg-[#2a2a4a] text-white py-2 rounded-lg text-sm font-medium hover:bg-[#3a3a5a] transition-colors">📝 Crear Cuenta</button>
      </form>
      <div id="auth-message" class="text-xs text-[#f4a261] mt-2 text-center hidden"></div>
    `;
    document.getElementById('auth-form').addEventListener('submit', iniciarSesion);
    document.getElementById('toggle-signup').addEventListener('click', alternarRegistro);
  }

  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

// Cierra el modal de perfil
function cerrarModalPerfil() {
  const modal = document.getElementById('profile-modal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
}

// Valida y procesa el inicio de sesión.
function iniciarSesion(e) {
  e.preventDefault();
  const nombreUsuario = document.getElementById('auth-username').value.trim();
  const contrasena = document.getElementById('auth-password').value.trim();

  if (!nombreUsuario || !contrasena) { mostrarMensajeAuth('Por favor completa todos los campos'); return; }
  if (nombreUsuario.length < 3) { mostrarMensajeAuth('El usuario debe tener al menos 3 caracteres'); return; }
  if (contrasena.length < 6) { mostrarMensajeAuth('La contraseña debe tener al menos 6 caracteres'); return; }

  usuarioActual = { username: nombreUsuario, password: contrasena };

  localStorage.setItem('libraryUser', JSON.stringify(usuarioActual)); // Guarda la sesión en localStorage.
  mostrarToast(`¡Bienvenido, ${nombreUsuario}!`);
  cerrarModalPerfil();
  actualizarBotonPerfil();
}

// Alterna el formulario entre modo login y modo registro
function alternarRegistro() {
  const formulario = document.getElementById('auth-form');
  const esRegistro = formulario.dataset.mode === 'signup';
  formulario.dataset.mode = esRegistro ? 'login' : 'signup';
  formulario.querySelector('button[type="submit"]').textContent = esRegistro ? '🔐 Iniciar Sesión' : '📝 Crear Cuenta';
  document.getElementById('toggle-signup').textContent = esRegistro ? '📝 Crear Cuenta'   : '🔐 Iniciar Sesión';
}

// Muestra un mensaje de error en el formulario de autenticación
function mostrarMensajeAuth(mensaje) {
  const mensajeEl = document.getElementById('auth-message');
  mensajeEl.textContent = mensaje;
  mensajeEl.classList.remove('hidden');
  setTimeout(() => mensajeEl.classList.add('hidden'), 3000);
}

// Cierra la sesión del usuario y limpia localStorage
function cerrarSesion() {
  usuarioActual = null;
  localStorage.removeItem('libraryUser');
  mostrarToast('Sesión cerrada');
  cerrarModalPerfil();
  actualizarBotonPerfil();
}

// Actualiza el icono del botón de perfil según el estado de sesión
function actualizarBotonPerfil() {
  const boton = document.getElementById('profile-btn');
  boton.textContent = usuarioActual ? '✅' : '👤';
  boton.title = usuarioActual ? `Sesión iniciada como ${usuarioActual.username}` : 'Iniciar sesión';
}

// Recupera la sesión guardada en localStorage al arrancar la app
function restaurarSesion() {
  const guardado = localStorage.getItem('libraryUser');
  if (guardado) {
    try {
      usuarioActual = JSON.parse(guardado);
      actualizarBotonPerfil();
    } catch (e) {
      localStorage.removeItem('libraryUser');
    }
  }
}