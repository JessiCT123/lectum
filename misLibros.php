<?php
session_start();
include 'backend/conexion.php';
include 'header.php';

$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$isLoggedIn = $userId ? 'true' : 'false';

// Carga de datos base y colección
$librosBase = $conn->query("SELECT l.*, g.nombre AS genero_nombre FROM libros l JOIN generos g ON l.genero_id = g.id")->fetchAll(PDO::FETCH_ASSOC);
$coleccionUsuario = [];
if ($userId) {
    $stmt = $conn->prepare("SELECT libro_id, estado FROM usuario_libros WHERE usuario_id = :uid");
    $stmt->execute([':uid' => $userId]);
    $coleccionUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<main class="flex-grow-container max-w-7xl mx-auto px-6 pt-2 pb-10 bg-transparent">

    <div class="mb-6">
        <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Mi Colección</h2>
        <p class="text-[#a8a5a0] text-sm">Organiza tus lecturas actuales, terminadas y pendientes</p>
    </div>

    <!-- Filtros de estado -->
    <div class="flex flex-wrap gap-3 mb-8 status-filters">
        <button onclick="filtrarColeccion('all', this)"
            class="status-filter-btn bg-[#f4a261] text-[#0f0f1a] px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all">
            Todos
        </button>
        <button onclick="filtrarColeccion('leyendo', this)"
            class="status-filter-btn bg-[#2a2a4a] text-[#a8a5a0] hover:bg-[#2a9d8f] hover:text-white px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all border border-[#3a3a5a]">
            Leyendo
        </button>
        <button onclick="filtrarColeccion('terminado', this)"
            class="status-filter-btn bg-[#2a2a4a] text-[#a8a5a0] hover:bg-[#f4a261] hover:text-[#0f0f1a] px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all border border-[#3a3a5a]">
            Terminados
        </button>
        <button onclick="filtrarColeccion('pendiente', this)"
            class="status-filter-btn bg-[#2a2a4a] text-[#a8a5a0] hover:bg-gray-500 hover:text-white px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all border border-[#3a3a5a]">
            Pendientes
        </button>
    </div>

    <!-- Grid de libros -->
    <div id="grid-coleccion" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 books-grid"></div>

    <!-- Mensaje vacío -->
    <div id="msg-vacio" class="hidden text-center py-20">
        <div class="text-6xl mb-4"></div>
        <p id="txt-vacio" class="text-[#a8a5a0] text-sm italic"></p>
    </div>

</main>

<script>
    window.DB_LIBROS = <?= json_encode($librosBase) ?>;
    window.USER_COL = <?= json_encode($coleccionUsuario) ?>;
    window.SESION_ACTIVA = <?= $isLoggedIn ?>;

    document.addEventListener('DOMContentLoaded', renderColeccion);
</script>

<?php include 'footer.php'; ?>