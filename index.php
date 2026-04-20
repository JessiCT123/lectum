<?php include 
'includes/conexion.php'; 


$query = $conn->query("SELECT l.*, g.nombre as genero 
                       FROM libros l 
                       JOIN generos g ON l.genero_id = g.id");
$libros = $query->fetchAll(PDO::FETCH_ASSOC);

$queryResenias = $conn->query("SELECT r.*, u.usuario, l.titulo 
                               FROM resenias r 
                               JOIN usuarios u ON r.usuario_id = u.id 
                               JOIN libros l ON r.libro_id = l.id");
$resenias = $queryResenias->fetchAll(PDO::FETCH_ASSOC);
//Comparador de precios
$queryPrecios = $conn->query("SELECT p.*, t.nombre as tienda_nombre, t.icono as tienda_icono, t.estrellas, t.envio
                               FROM precios p 
                               JOIN tienda t ON p.tienda_id = t.id");
$todosLosPrecios = $queryPrecios->fetchAll(PDO::FETCH_ASSOC);

$id_usuario_prueba = 1; 
$queryMisLibros = $conn->prepare("SELECT ul.*, l.titulo, l.portada, l.autor 
                                  FROM usuario_libros ul 
                                  JOIN libros l ON ul.libro_id = l.id 
                                  WHERE ul.usuario_id = ?");
$queryMisLibros->execute([$id_usuario_prueba]);
$misLibros = $queryMisLibros->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="es" class="h-full">

<!-- CABECERA -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Biblioteca Personal</title>

<!-- Estilos -->
<link rel="stylesheet" href="css/estilos.css">

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com/3.4.17"></script>

<!-- Font Awesome (para iconos tipo "fas fa-truck") -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
    const BDLibros = <?php echo json_encode($libros); ?>;
    const BDResenias = <?php echo json_encode($resenias); ?>;

    //misLibros.php
    const BDMisLibros = <?php echo json_encode($misLibros); ?>;
    //compararPrecios.php
    const BDPrecios = <?php echo json_encode($todosLosPrecios); ?>;
</script>

<!-- Scripts de la aplicación (orden importante) -->
<script src="js/func.js" defer></script>      
<script src="js/events.js" defer></script>   

<!-- Fuentes Google -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<!-- Estructura principal (Header, Nav, Main, Modales y Toast) -->
<body class="h-full font-body bg-[#0f0f1a] text-[#e8e6e3] overflow-auto">
<div id="app" class="min-h-full w-full">

<!-- HEADER -->
<header class="bg-gradient-to-r from-[#1a1a2e] to-[#16213e] border-b border-[#2a2a4a] sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 py-4">
<div class="flex items-center justify-between">

<div>
<h1 id="titulo-pg" class="font-display text-2xl md:text-3xl font-bold text-[#f4a261]">L E C T U M</h1>
<p id="bienvenida-msg" class="text-sm text-[#a8a5a0] mt-1">Descubre, organiza y comparte tus lecturas</p>
</div>

<!-- Buscador + botón perfil -->
<div class="flex items-center gap-3">

<!-- Buscador global - redirige a Explorar + filtra por autor -->
<div class="relative">
<input type="text" id="search-input" placeholder="Buscar libros..."
  class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-full px-4 py-2 pl-10 text-sm w-48 md:w-64 focus:outline-none focus:border-[#f4a261] transition-colors">
<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#a8a5a0]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
</div>

<!-- Botón perfil- mostrarModalPerfil()
   Icono: 👤 sin sesión / ✅ con sesión - actualizarBotonPerfil() -->
<button id="boton-perfil" onclick="mostrarModalPerfil()" class="relative w-10 h-10 rounded-full bg-[#2a2a4a] flex items-center justify-center overflow-hidden border border-transparent hover:border-[#f4a261] transition-all">
    <span id="icono-por-defecto" class="text-xl">👤</span>
    
    <img id="avatar-seleccionado-user" src="" class="hidden w-full h-full object-cover">
</button>

</div>
</div>
</div>
</header>

<!-- NAVEGACIÓN -->
<nav class="bg-[#1a1a2e] border-b border-[#2a2a4a]">
<div class="max-w-7xl mx-auto px-4">
<div class="flex gap-1 overflow-x-auto">
  <button class="nav-tab tab-active px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors hover:text-[#f4a261]" data-tab="featured">Inicio</button>
  <button class="nav-tab px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors hover:text-[#f4a261] text-[#a8a5a0]" data-tab="explore">Explorar</button>
  <button class="nav-tab px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors hover:text-[#f4a261] text-[#a8a5a0]" data-tab="my-books">Mis Libros</button>
  <button class="nav-tab px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors hover:text-[#f4a261] text-[#a8a5a0]" data-tab="reviews">Reseñas</button>
  <button class="nav-tab px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors hover:text-[#f4a261] text-[#a8a5a0]" data-tab="prices">Comparar Precios</button>
</div>
</div>
</nav>

<!-- CONTENIDO PRINCIPAL -->
<main class="max-w-7xl mx-auto px-4 py-6">

<!-- PESTAÑA: INICIO -->
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
    <h3 class="font-display text-lg font-semibold mb-4 text-[#f4a261]">Autores del Momento</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php
      $autores = [];
      foreach ($libros as $libro) { $autores[$libro['autor']][] = $libro; }
      foreach (array_slice($autores, 0, 8, true) as $nombreAutor => $librosAutor):
          $totalLibros = count($librosAutor);
          $media = array_sum(array_column($librosAutor, 'valoracion')) / ($totalLibros ?: 1);
      ?>
        <div onclick="verAutorFiltrado('<?= addslashes($nombreAutor) ?>')"
            class="bg-[#2a2a4a] p-4 rounded-xl hover:bg-[#34345a] transition-all cursor-pointer border border-[#3a3a5a] hover:border-[#f4a261]">
            <h4 class="font-semibold text-sm text-[#f4a261]"><?= htmlspecialchars($nombreAutor) ?></h4>
            <p class="text-xs text-[#a8a5a0] mt-1">
              <?= $totalLibros ?> libros • <i class="fas fa-star text-yellow-500 text-[10px]"></i> <?= number_format($media, 1) ?>
            </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>
</section>


<?php include 'explorar.php'; ?>
<?php include 'misLibros.php'; ?>

<!-- PESTAÑA RESEÑAS-->
<section id="tab-reviews" class="tab-content hidden">

<div class="mb-6">
  <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Comunidad de Reseñas</h2>
  <p class="text-[#a8a5a0] text-sm">Lee y comparte opiniones con otros lectores</p>
</div>

<div class="grid lg:grid-cols-2 gap-6">

  <!-- Formulario de reseña -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4">Escribe una Reseña</h3>
  <form id="valoracion-form" class="space-y-4">

    <!-- Selector de libro - actualizarSelectorResenas() -->
    <select id="valoracion-book-select" onchange="mostrarPreciosLibro(this.value)" class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
    <option value="">Selecciona un libro de tu colección...</option>
    </select>

    <!-- Estrellas de valoración - valoracionSeleccionada -->
    <div>
    <label class="block text-sm text-[#a8a5a0] mb-2">Tu valoración</label>
    <div id="valoracion-stars" class="flex gap-1">
      <button type="button" class="star-btn text-2xl text-[#3a3a5a] hover:text-[#f4a261]" data-valoracion="1">★</button>
      <button type="button" class="star-btn text-2xl text-[#3a3a5a] hover:text-[#f4a261]" data-valoracion="2">★</button>
      <button type="button" class="star-btn text-2xl text-[#3a3a5a] hover:text-[#f4a261]" data-valoracion="3">★</button>
      <button type="button" class="star-btn text-2xl text-[#3a3a5a] hover:text-[#f4a261]" data-valoracion="4">★</button>
      <button type="button" class="star-btn text-2xl text-[#3a3a5a] hover:text-[#f4a261]" data-valoracion="5">★</button>
    </div>
    </div>

    <!-- Texto de la reseña -->
    <textarea id="valoracion-text" rows="4" placeholder="Comparte tu opinión sobre este libro..."
    class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261] resize-none"></textarea>

    <button type="submit" id="submit-valoracion-btn" class="w-full bg-[#f4a261] text-[#0f0f1a] py-2 rounded-lg font-medium hover:bg-[#e76f51] transition-colors">
    Publicar Reseña
    </button>

  </form>
  </div>

  <!-- Listado de reseñas - renderizarResenas() -->
  <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
  <h3 class="font-display text-lg font-semibold mb-4">Reseñas Recientes</h3>
  <div id="reviews-list" class="space-y-4 max-h-96 overflow-y-auto pr-2"></div>
  </div>

</div>
</section>


<?php include 'compararPrecios.php'; ?>

</main>

<!-- MODALES -->

<!-- Modal detalle de libro -->
<div id="book-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4 overflow-y-auto">
<div class="bg-[#1a1a2e] rounded-2xl max-w-2xl w-full max-h-[90%] overflow-y-auto border border-[#2a2a4a]">
  <div id="modal-content" class="p-6"></div>
</div>
</div>

<!-- Modal perfil y autenticación -->
<div id="profile-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4 overflow-y-auto">
<div class="bg-[#1a1a2e] rounded-2xl max-w-sm w-full border border-[#2a2a4a]">
  <div id="profile-content" class="p-6"></div>
</div>
</div>

<!-- TOAST - Notificación flotante -->
<div id="toast" class="fixed bottom-4 right-4 bg-[#2a9d8f] text-white px-4 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50">
<span id="toast-message"></span>
</div>

</div>
</body>
</html>
<?php include 'footer.php'; ?>