<!-- MIS LIBROS -->

<?php
session_start();
 include 'backend/conexion.php';
 include 'header.php';
?>

<section id="tab-my-books" class="tab-content hidden">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Mis Libros</h2>
            <p class="text-[#a8a5a0] text-sm">Gestiona tu biblioteca personal</p>
        </div>
        <div class="text-right">
            <span id="count-total" class="text-2xl font-bold text-white">0</span>
            <p class="text-[10px] text-gray-500 uppercase">Total</p>
        </div>
    </div>

    <div class="flex gap-2 mb-8 flex-wrap">
        <button class="filter-btn active-status bg-[#f4a261] text-[#0f0f1a] px-5 py-2 rounded-xl text-sm font-bold shadow-lg" onclick="filtrarMisLibros('all', this)">Todos</button>
        <button class="filter-btn bg-[#2a2a4a] text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-[#34345a]" onclick="filtrarMisLibros('leyendo', this)">Leyendo</button>
        <button class="filter-btn bg-[#2a2a4a] text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-[#34345a]" onclick="filtrarMisLibros('completado', this)">Terminados</button>
        <button class="filter-btn bg-[#2a2a4a] text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-[#34345a]" onclick="filtrarMisLibros('pendiente', this)">Pendientes</button>
    </div>

    <div id="my-books-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6"></div>

    <div id="empty-my-books" class="hidden text-center py-20 bg-[#1a1a2e] rounded-3xl border border-[#2a2a4a]">
        <i class="fas fa-layer-group text-4xl text-gray-600 mb-4"></i>
        <h3 class="text-white font-semibold">Tu biblioteca está vacía</h3>
    </div>
</section>

<script>
    window.LIBROS = <?= json_encode($libros) ?>;
    window.MI_COLECCION = <?= json_encode($coleccionUsuario) ?>;
</script>
<?php include 'footer.php'; ?>