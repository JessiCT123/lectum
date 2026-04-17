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


  // BUSCADOR GLOBAL
document.getElementById('search-input').addEventListener('input', (e) => {
      const valor = e.target.value;
      if (valor.length > 2) {
          cambiarPestana('explore');
          renderizarExplorarConBusqueda(valor);
      } else if (valor.length === 0) {
          renderizarExplorar();
      }
  });

    // Filtros de la pestaña Explorar
    document.getElementById('filter-genero').addEventListener('change', renderizarExplorar);
    document.getElementById('filter-autor').addEventListener('input', renderizarExplorar);
    document.getElementById('filter-valoracion').addEventListener('change', renderizarExplorar);
  // ESTRELLAS — formulario de reseña


  const contenedorEstrellas = document.getElementById("valoracion-stars");
  if (contenedorEstrellas) {
    const estrellas = contenedorEstrellas.querySelectorAll(".star-btn");
    const hiddenInput = document.getElementById("valoracion-hidden");

    estrellas.forEach((btn) => {
      btn.addEventListener("click", () => {
        const val = parseInt(btn.dataset.val);

        // Guardar valor en el input hidden para que lo reciba el formulario
        hiddenInput.value = val;

        // Colorear las estrellas según la selección
        estrellas.forEach((s, i) => {
          if (i < val) {
            s.classList.remove("text-[#3a3a5a]");
            s.classList.add("text-[#f4a261]");
          } else {
            s.classList.remove("text-[#f4a261]");
            s.classList.add("text-[#3a3a5a]");
          }
        });
      });
    });
  }


// Selector de Comparar Precios
    const priceSelect = document.getElementById('price-book-select');
    if (priceSelect) {
        priceSelect.addEventListener('change', (e) => {
            if (e.target.value) mostrarPreciosLibro(e.target.value);
        });

      // Estrellas para las reseñas
      const cajaEstrellas = document.getElementById('valoracion-stars');
if (!cajaEstrellas) return;

const estrellas   = cajaEstrellas.querySelectorAll('.star-btn');
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

  // VALIDACIÓN formulario reseña antes de enviar
 

  const formResenia = document.getElementById("valoracion-form");
  if (formResenia) {
    formResenia.addEventListener("submit", (e) => {
      const libroId    = document.getElementById("valoracion-book-select").value;
      const valoracion = document.getElementById("valoracion-hidden").value;
      const texto      = document.getElementById("valoracion-text").value.trim();

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
}

});
