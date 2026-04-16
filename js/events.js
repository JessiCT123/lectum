// EVENTS.JS
document.addEventListener('DOMContentLoaded', () => {

    // Carga inicial
    renderizarDestacados();

    // Navegación de pestañas
    document.querySelectorAll('.nav-tab').forEach(btn => {
        btn.addEventListener('click', () => cambiarPestana(btn.dataset.tab));
    });

    // Buscador en tiempo real
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
    
    // Selector de Comparar Precios
    const priceSelect = document.getElementById('price-book-select');
    if (priceSelect) {
        priceSelect.addEventListener('change', (e) => {
            if (e.target.value) mostrarPreciosLibro(e.target.value);
        });
    }
});