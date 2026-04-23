document.addEventListener("DOMContentLoaded", () => {
  // INICIALIZAR RENDERIZADO
  // Solo llama la función si el elemento existe en esta página

  if (document.getElementById("most-read-books")) {
    renderizarDestacados();
  }

  if (document.getElementById("reviews-list")) {
    renderizarResenias();
  }

  if (document.getElementById("explorar-resultados")) {
    renderizarExplorar();
  }

  // navegación entre pestañas 

  document.querySelectorAll('.nav-tab').forEach(btn => {
    btn.addEventListener('click', () => cambiarPestana(btn.dataset.tab));
  });


  // BUSCADOR GLOBAL añadimos encodeURIComponent  para que el buscador no falle por los espacios
  var buscador = document.getElementById('search-input');
  if (buscador) {
    buscador.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        var texto = this.value.trim();
        if (texto.length > 0) {
          window.location.href = '/lectum/nav/explorar.php?buscar=' + encodeURIComponent(texto);
        }
      }
    });
  }
  // Filtros de la pestaña Explorar
  let filtroGenero = document.getElementById('filter-genero');
  if (filtroGenero) {
    filtroGenero.addEventListener('change', aplicarFiltros);
  }

  let filtroAutor = document.getElementById('filter-autor');
  if (filtroAutor) {
    filtroAutor.addEventListener('input', aplicarFiltros);
  }

  let filtroValoracion = document.getElementById('filter-valoracion');
  if (filtroValoracion) {
    filtroValoracion.addEventListener('change', aplicarFiltros);
  }



  // Selector de Comparar Precios
  const priceSelect = document.getElementById('price-book-select');
  if (priceSelect) {
    priceSelect.addEventListener('change', (e) => {
      if (e.target.value) mostrarPreciosLibro(e.target.value);
    });
  }
  // Estrellas para las reseñas
  const cajaEstrellas = document.getElementById('valoracion-stars');
  if (cajaEstrellas) {

    const estrellas = cajaEstrellas.querySelectorAll('.star-btn');
    const campoOculto = document.getElementById('valoracion-hidden');

    estrellas.forEach(boton => {
      boton.addEventListener('click', () => {

        // Guardamos el número de estrellas seleccionadas 
        const elegida = parseInt(boton.dataset.val);
        campoOculto.value = elegida;

        // marcamos las estrellas seleccionadas de naranja
        estrellas.forEach((estrella, posicion) => {

          const debeEstarPintada = posicion < elegida;

          if (debeEstarPintada) {
            estrella.classList.add('text-[#f4a261]');    // naranja
            estrella.classList.remove('text-[#3a3a5a]'); // quita gris
          } else {
            estrella.classList.add('text-[#3a3a5a]');    // gris
            estrella.classList.remove('text-[#f4a261]'); // quita naranja
          }
        });

      });
    });
  }

  // VALIDACIÓN formulario reseña antes de enviar


  const formResenia = document.getElementById("valoracion-form");
  if (formResenia) {
    formResenia.addEventListener("submit", (e) => {
      const libroId = document.getElementById("valoracion-book-select").value;
      const valoracion = document.getElementById("valoracion-hidden").value;
      const texto = document.getElementById("valoracion-text").value.trim();

      if (!libroId) {
        e.preventDefault();
        alert("Selecciona un libro.");
        return;
      }
      if (!valoracion) {
        e.preventDefault();
        alert("Selecciona una valoración con las estrellas.");
        return;
      }
      if (texto.length < 10) {
        e.preventDefault();
        alert("La reseña debe tener al menos 10 caracteres.");
      }
    });
  }

});
