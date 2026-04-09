// EVENTS.JS — Arranque de la app y registro de event listeners
// Se ejecuta tras cargar el DOM (defer en el HTML).
// Depende de: data.js (datos y estado) + func.js (funciones)

document.addEventListener('DOMContentLoaded', async () => {

  // Arranque - Restaura la sesión guardada en localStorage, inicializa
  //  los SDKs y renderiza la pestaña inicial "Destacados".
  restaurarSesion();
  await iniciarSDKs();
  renderizarDestacados();

  // Cierra el recuadro pequeño donde aparece la sesión pero al clicar fuera
  document.getElementById('profile-modal').addEventListener('click', (e) => {
    if (e.target.id === 'profile-modal') cerrarModalPerfil();
  });

  // Navegación por pestañas - Cada botón .nav-tab llama a cambiarPestana() con su data-tab.
  document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', () => cambiarPestana(tab.dataset.tab));
  });

  // Buscador global - A partir de 3 caracteres redirige a la pestaña "Explorar"
  //  y filtra los resultados por autor automáticamente.
  document.getElementById('search-input').addEventListener('input', (e) => {
    const busqueda = e.target.value.toLowerCase();
    if (busqueda.length > 2) { cambiarPestana('explore');
      document.getElementById('filter-autor').value = busqueda;
      renderizarExplorar();
    }
  });

  // Filtros de exploración - Los tres (género, valoración, autor) actualizan
  //  los resultados en tiempo real.
  ['filter-genero', 'filter-valoracion', 'filter-autor'].forEach(id => {
    document.getElementById(id).addEventListener('change', renderizarExplorar);
    document.getElementById(id).addEventListener('input',  renderizarExplorar);
  });

  // Filtros de estado "Mis Libros" - Al pulsar un filtro se resalta el botón activo y se
  //  actualiza filtroEstadoActual antes de rerenderizar.
  document.querySelectorAll('.status-filter-btn').forEach(boton => {
    boton.addEventListener('click', () => {

      // Resetear todos los botones al estilo inactivo
      document.querySelectorAll('.status-filter-btn').forEach(b => {
        b.classList.remove('bg-[#f4a261]', 'text-[#0f0f1a]');
        b.classList.add('bg-[#2a2a4a]');
      });

      // Marcar el botón pulsado como activo
      boton.classList.add('bg-[#f4a261]', 'text-[#0f0f1a]');
      boton.classList.remove('bg-[#2a2a4a]');

      filtroEstadoActual = boton.dataset.status;
      renderizarMisLibros();
    });
  });

  // Estrellas de valoración - Al pulsar una estrella, se actualiza valoracionSeleccionada y se
  //  colorean las estrellas hasta la seleccionada.
  document.querySelectorAll('.star-btn').forEach(boton => {
    boton.addEventListener('click', () => {
      valoracionSeleccionada = parseInt(boton.dataset.valoracion);
      document.querySelectorAll('.star-btn').forEach((s, i) => {
        s.classList.toggle('text-[#f4a261]', i < valoracionSeleccionada);
        s.classList.toggle('text-[#3a3a5a]', i >= valoracionSeleccionada);
      });
    });
  });

  // Formulario de reseña - Valida que haya libro seleccionado, texto y valoración.
  // Actualiza el registro existente en dataSdk con la reseña.
  // Después de publicar, limpia el formulario y resetea las estrellas.
  document.getElementById('valoracion-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const idLibro = document.getElementById('valoracion-book-select').value;
    const textoResena = document.getElementById('valoracion-text').value.trim();

    if (!idLibro || !textoResena || valoracionSeleccionada === 0) { mostrarToast('Completa todos los campos'); return; }
    if (!window.dataSdk) return;

    const existente = datosUser.find(u => u.book_id === idLibro);
    if (!existente) { mostrarToast('Primero añade el libro a tu colección'); return; }

    const resultado = await window.dataSdk.update({ ...existente, user_review: textoResena, user_valoracion: valoracionSeleccionada });

    if (resultado.isOk) {
      mostrarToast('¡Reseña publicada!');
      document.getElementById('valoracion-text').value = '';
      valoracionSeleccionada = 0;
      document.querySelectorAll('.star-btn').forEach(s => {
        s.classList.remove('text-[#f4a261]');
        s.classList.add('text-[#3a3a5a]');
      });
    } else {
      mostrarToast('Error al publicar la reseña');
    }
  });

  // Comparador de precios - Al cambiar el selector se lanza renderizarComparadorPrecios() con el id del libro elegido.
  // El selector se rellena con todos los libros del catálogo.
  document.getElementById('price-book-select').addEventListener('change', (e) => {
    if (e.target.value) renderizarComparadorPrecios(e.target.value);
  });

  BDLibros.forEach(libro => {
    const opcion = document.createElement('option');
    opcion.value = libro.id;
    opcion.textContent = `${libro.titulo} - ${libro.autor}`;
    document.getElementById('price-book-select').appendChild(opcion);
  });

  // Cierre de modal de libro - El modal de detalle se cierra al pulsar fuera del contenido.
  document.getElementById('book-modal').addEventListener('click', (e) => {
    if (e.target.id === 'book-modal') cerrarModal();
  });

});