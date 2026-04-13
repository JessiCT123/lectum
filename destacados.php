<!-- PESTAÑA: DESTACADOS -->
<section id="tab-featured" class="tab-content">

<div class="mb-6">
  <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Libros Destacados</h2>
  <p class="text-[#a8a5a0] text-sm">Los más leídos, mejor valorados y más relevantes</p>
</div>

<div class="grid gap-6">

  <!-- Más leídos - renderizarDestacados() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4 flex items-center gap-2">Más Leídos</h3>
  <div id="most-read-books" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
  </div>

  <!-- Mejor valorados - renderizarDestacados() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4 flex items-center gap-2">Mejor Valorados</h3>
  <div id="best-rated-books" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
  </div>

  <!-- Autores del momento - renderizarDestacados() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4 flex items-center gap-2">Autores del Momento</h3>
  <div id="trending-autores" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
  </div>

</div>
</section>