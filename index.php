<?php
session_start();
 include 'backend/conexion.php';
 include 'header.php';

 // ── Cargar libros con nombre de género ──
$libros = $conn->query("
    SELECT l.*, g.nombre AS genero_nombre
    FROM libros l
    JOIN generos g ON l.genero_id = g.id
    ORDER BY l.id
")->fetchAll();

// ── Cargar tiendas ──
$tiendas = $conn->query("SELECT * FROM tienda ORDER BY id")->fetchAll();

// ── Cargar precios ──
$precios = $conn->query("SELECT * FROM precios")->fetchAll();

// ── Cargar reseñas de la comunidad (con nombre de usuario y libro) ──
$resenias = $conn->query("
    SELECT r.*, l.titulo AS titulo_libro, u.usuario AS nombre_usuario
    FROM resenias r
    JOIN libros  l ON r.libro_id   = l.id
    JOIN usuarios u ON r.usuario_id = u.id
    ORDER BY r.fecha DESC
    LIMIT 20
")->fetchAll();

// ── Cargar géneros para el filtro ──
$generos = $conn->query("SELECT * FROM generos ORDER BY nombre")->fetchAll();

// ── Colección del usuario logueado ──
$coleccionUsuario = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM usuario_libros WHERE usuario_id = :uid");
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $coleccionUsuario = $stmt->fetchAll();
}

 ?>


<!-- CONTENIDO PRINCIPAL -->
<main class="max-w-7xl mx-auto px-4 py-6">

<!-- PESTAÑA: DESTACADOS -->
<section id="tab-featured" class="tab-content">

<div class="mb-6">
  <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Libros Destacados</h2>
  <p class="text-[#a8a5a0] text-sm">Los más leídos, mejor valorados y más relevantes</p>
</div>

<div class="grid gap-6">

  <!-- Más leídos - renderizarDestacados() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4">Más Leídos</h3>
  <div id="most-read-books" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
  </div>

  <!-- Mejor valorados - renderizarDestacados() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4">Mejor Valorados</h3>
  <div id="best-rated-books" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
  </div>

  <!-- Autores del momento - renderizarDestacados() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4">Autores del Momento</h3>
  <div id="trending-autores" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
  </div>

</div>
</section>

<!-- EXPLORAR -->
<section id="tab-explore" class="tab-content hidden">

<div class="mb-6">
  <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Explorar</h2>
  <p class="text-[#a8a5a0] text-sm">Encuentra tu próxima lectura</p>
</div>

<div class="bg-[#1a1a2e] rounded-xl p-4 border border-[#2a2a4a] mb-6 flex flex-wrap gap-3">

<select id="filter-genero" class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm">
  <option value="">Todos los géneros</option>
  <?php foreach ($generos as $genero): ?>
    <option value="<?= htmlspecialchars($genero['nombre']) ?>">
      <?= htmlspecialchars(ucfirst($genero['nombre'])) ?>
    </option>
  <?php endforeach; ?>
</select>

<input type="text" id="filter-autor" placeholder="Buscar por autor..."
  class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm flex-1 min-w-[160px]">

<select id="filter-valoracion" class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm">
  <option value="">Todas las valoraciones</option>
  <option value="positive">Positivas (≥ 4)</option>
  <option value="negative">Negativas (< 3)</option>
</select>

<button onclick="renderizarExplorar()" class="bg-[#f4a261] text-[#0f0f1a] px-4 py-2 rounded-lg text-sm">
Filtrar
</button>

</div>

<div id="explore-results" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>

</section>

<!-- MIS LIBROS -->
<section id="tab-my-books" class="tab-content hidden">
<div class="mb-6">
  <h2 class="font-display text-xl font-semibold text-[#f4a261]">Mis Libros</h2>
</div>
<div id="my-books-grid"></div>
</section>

<!-- RESEÑAS -->
<section id="tab-reviews" class="tab-content hidden">

<div class="grid lg:grid-cols-2 gap-6">

  <!-- FORMULARIO -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">

    <h3 class="font-display text-lg font-semibold mb-4">Escribe una Reseña</h3>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <div class="text-center py-4 text-[#a8a5a0]">
        <p class="mb-3">Debes iniciar sesión para escribir una reseña</p>
        <button onclick="mostrarModalPerfil()" class="bg-[#f4a261] px-4 py-2 rounded-lg">Iniciar Sesión</button>
      </div>
    <?php else: ?>

    <form id="valoracion-form" action="backend/guardar_resenia.php" method="POST" class="space-y-4">

      <select name="libro_id" id="valoracion-book-select" class="w-full bg-[#2a2a4a] px-3 py-2 rounded-lg">
        <option value="">Selecciona un libro...</option>
        <?php foreach ($libros as $l): ?>
          <option value="<?= $l['id'] ?>">
            <?= htmlspecialchars($l['titulo']) ?> — <?= htmlspecialchars($l['autor']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <div>
        <label class="block text-sm text-[#a8a5a0] mb-2">Tu valoración</label>
        <div id="valoracion-stars" class="flex gap-1">
          <?php for ($i = 1; $i <= 5; $i++): ?>
<button type="button" class="star-btn text-2xl text-[#3a3a5a]" data-val="<?= $i ?>">★</button>          <?php endfor; ?>
        </div>
        <input type="hidden" name="valoracion" id="valoracion-hidden">
      </div>

      <textarea name="texto" id="valoracion-text" rows="4"
        class="w-full bg-[#2a2a4a] px-3 py-2 rounded-lg"></textarea>

<button type="submit" name="nuevo" class="w-full bg-[#f4a261] py-2 rounded-lg">
  Publicar Reseña
</button>

    </form>

    <?php endif; ?>

  </div>

  <!-- LISTA RESEÑAS -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
    <h3 class="font-display text-lg font-semibold mb-4">Reseñas Recientes</h3>
    <div id="reviews-list" class="space-y-4 max-h-96 overflow-y-auto"></div>
  </div>

</div>

</section>

<!-- COMPARAR PRECIOS -->
<section id="tab-prices" class="tab-content hidden">
  <?php include 'compararPrecios.php'; ?>
</section>

</main>

<!-- MODALES -->
<div id="book-modal" class="fixed inset-0 bg-black/80 z-50 hidden"></div>
<div id="profile-modal" class="fixed inset-0 bg-black/80 z-50 hidden"></div>

<!-- TOAST -->
<div id="toast" class="fixed bottom-4 right-4 bg-[#2a9d8f] text-white px-4 py-3 rounded-lg opacity-0">
  <span id="toast-message"></span>
</div>

</div>

<script>
  window.LIBROS = <?= json_encode($libros) ?>;
  window.TIENDAS = <?= json_encode($tiendas) ?>;
  window.RESENIAS = <?= json_encode($resenias) ?>;
</script>
<?php
// Incluir el pie de página
include 'footer.php';
?>
</body>
</html>
