<!-- COMPARADOR DE PRECIOS -->

<section id="tab-prices" class="tab-content hidden">

    <div class="mb-6">
        <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Comparador de Precios</h2>
        <p class="text-[#a8a5a0] text-sm">Encuentra la mejor opción para comprar tus libros</p>
    </div>

    <!-- Selector de libro -->
    <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a] mb-6">
        <select id="price-book-select" class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
            option value="">Selecciona un libro para comparar precios...</option>
        </select>
    </div>

        <!-- Resultado comparación - renderizarComparadorPrecios() -->
    <div id="price-comparison" class="hidden">
        <!-- Info del libro -->
        <div id="selected-book-info" class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a] mb-6"></div>
        <!-- Tarjetas por tienda -->
        <div id="store-prices" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>

    <!-- Estado vacío -->
    <div id="price-empty" class="text-center py-12">
        <h3 class="font-display text-lg text-[#a8a5a0] mb-2">Selecciona un libro</h3>
        <p class="text-sm text-[#6a6a8a]">Compara precios entre diferentes tiendas</p>
    </div>

</section>