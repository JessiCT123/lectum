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


  // BUSCADOR GLOBAL
  // Rellena el filtro de autor y cambia a explorar.php
 

  const searchInput = document.getElementById("search-input");
  if (searchInput) {
    searchInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        const valor = searchInput.value.trim();
        if (valor !== "") {
          window.location.href = "explorar.php?autor=" + encodeURIComponent(valor);
        }
      }
    });
  }

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

});