<!-- MIS LIBROS -->

<section id="tab-my-books" class="tab-content hidden">

    <div class="mb-6">
        <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Mi Colección</h2>
        <p class="text-[#a8a5a0] text-sm">Organiza tus lecturas actuales, terminadas y pendientes</p>
    </div>

    <!-- Filtros de estado -->
    <div class="flex gap-2 mb-6 flex-wrap">
        <button class="status-filter-btn bg-[#f4a261] text-[#0f0f1a] px-4 py-2 rounded-full text-sm font-medium transition-all" data-status="all">Todos</button>
        <button class="status-filter-btn bg-[#2a2a4a] px-4 py-2 rounded-full text-sm font-medium transition-all hover:bg-[#3a3a5a]" data-status="leyendo">Leyendo</button>
        <button class="status-filter-btn bg-[#2a2a4a] px-4 py-2 rounded-full text-sm font-medium transition-all hover:bg-[#3a3a5a]" data-status="completado">Terminados</button>
        <button class="status-filter-btn bg-[#2a2a4a] px-4 py-2 rounded-full text-sm font-medium transition-all hover:bg-[#3a3a5a]" data-status="pendiente">Pendientes</button>
    </div>

    <!-- Lista de libros -->
    <div id="my-books-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"></div>

    <!-- Estado vacío -->
    <div id="empty-my-books" class="hidden text-center py-12">
        <h3 class="font-display text-lg text-[#a8a5a0] mb-2">Tu biblioteca está vacía</h3>
        <p class="text-sm text-[#6a6a8a]">Explora libros y agrégalos a tu colección</p>
    </div>

</section>