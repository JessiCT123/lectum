<?php
session_start();
include 'backend/conexion.php';
include 'backend/auth.php';
include 'header.php';

// ── Cargar datos  ──
$libros = $conn->query("SELECT l.*, g.nombre AS genero_nombre FROM libros l JOIN generos g ON l.genero_id = g.id ORDER BY l.id")->fetchAll();
$tienda = $conn->query("SELECT * FROM tienda ORDER BY id")->fetchAll();
$precios = $conn->query("SELECT p.*, t.nombre as tienda_nombre, t.icono as tienda_icono, t.estrellas, t.envio FROM precios p JOIN tienda t ON p.tienda_id = t.id")->fetchAll(PDO::FETCH_ASSOC);
$resenias = $conn->query("SELECT r.*, l.titulo AS titulo_libro, u.usuario AS nombre_usuario FROM resenias r JOIN libros l ON r.libro_id = l.id JOIN usuarios u ON r.usuario_id = u.id ORDER BY r.fecha DESC LIMIT 20")->fetchAll();
$generos = $conn->query("SELECT * FROM generos ORDER BY nombre")->fetchAll();

// Vemos si el usuario está logueado para mostrar su colección personal
$coleccionUsuario = [];
if (isset($_SESSION['id'])) {
  $sql = $conn->prepare("SELECT * FROM usuario_libros WHERE usuario_id = :uid");
  $sql->execute([':uid' => $_SESSION['id']]);
  $coleccionUsuario = $sql->fetchAll();
}
?>

<main class="max-w-7xl mx-auto px-4 py-6">

  <section id="tab-featured" class="tab-content">
    <div class="mb-6">
      <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Libros Destacados</h2>
      <p class="text-[#a8a5a0] text-sm">Los más leídos, mejor valorados y más relevantes</p>
    </div>

    <div class="grid gap-6">

      <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
        <h3 class="font-display text-lg font-semibold mb-4 text-white">Más Leídos</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
          <?php
          $masLeidos = $libros;
          usort($masLeidos, function ($a, $b) {
            return $b['lecturas'] <=> $a['lecturas'];
          });
          foreach (array_slice($masLeidos, 0, 5) as $libro):
          ?>
            <div class="book-card group cursor-pointer" onclick="verLibro(<?= htmlspecialchars(json_encode($libro)) ?>)">
              <div class="aspect-[3/4] overflow-hidden relative rounded-lg">
                <img src="img/<?= $libro['portada'] ?>" alt="<?= htmlspecialchars($libro['titulo']) ?>" onerror="this.src='img/default.jpg'" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                <span class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] px-2 py-1 rounded">
                  <?= htmlspecialchars($libro['genero_nombre']) ?>
                </span>
              </div>
              <div class="p-3">
                <h4 class="font-display text-sm font-semibold text-white group-hover:text-[#f4a261] transition-colors">
                  <?= htmlspecialchars($libro['titulo']) ?>
                </h4>
                <p class="text-xs mt-1 text-gray-400"><?= htmlspecialchars($libro['autor']) ?></p>
                <div class="flex justify-between text-xs mt-2 text-gray-500">
                  <span><i class="fas fa-star text-yellow-500"></i> <?= $libro['valoracion'] ?></span>
                  <span><?= $libro['anio'] ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
        <h3 class="font-display text-lg font-semibold mb-4 text-white">Mejor Valorados</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
          <?php
          $mejorValorados = $libros;
          usort($mejorValorados, function ($a, $b) {
            return $b['valoracion'] <=> $a['valoracion'];
          });
          foreach (array_slice($mejorValorados, 0, 5) as $libro):
          ?>
            <div class="book-card group cursor-pointer" onclick="verLibro(<?= htmlspecialchars(json_encode($libro)) ?>)">
              <div class="aspect-[3/4] overflow-hidden relative rounded-lg">
                <img src="img/<?= $libro['portada'] ?>" class="w-full h-full object-cover transition-transform group-hover:scale-105">

                <span class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] px-2 py-1 rounded">
                  <?= htmlspecialchars($libro['genero_nombre']) ?>
                </span>
              </div>
              <div class="p-3">
                <h4 class="font-display text-sm font-semibold text-white group-hover:text-[#f4a261]">
                  <?= htmlspecialchars($libro['titulo']) ?>
                </h4>
                <p class="text-xs mt-1 text-gray-400"><?= htmlspecialchars($libro['autor']) ?></p>
                <div class="flex justify-between text-xs mt-2 text-gray-500">
                  <span><i class="fas fa-star text-yellow-500"></i> <?= $libro['valoracion'] ?></span>
                  <span><?= $libro['anio'] ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a]">
        <h3 class="font-display text-lg font-semibold mb-4 text-white">Autores del Momento</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

          <?php
          $autores = [];
          foreach ($libros as $libro) {
            $autores[$libro['autor']][] = $libro;
          }
          foreach (array_slice($autores, 0, 8, true) as $nombreAutor => $librosAutor):
            $totalLibros = count($librosAutor);
            $media = array_sum(array_column($librosAutor, 'valoracion')) / $totalLibros;
          ?>

            <div onclick="verAutor('<?= addslashes($nombreAutor) ?>')"
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
</main>

<div id="ui-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
  <div id="ui-content" class="bg-[#1a1a2e] border border-[#2a2a4a] max-w-sm w-full rounded-2xl p-6 shadow-2xl transform transition-all scale-95 opacity-0">
  </div>
</div>

<script>
  window.LIBROS = <?= json_encode($libros) ?>;
  window.TIENDA = <?= json_encode($tienda) ?>;
  window.RESENIAS = <?= json_encode($resenias) ?>;
  window.PRECIOS = <?= json_encode($precios) ?>;
  window.MIS_LIBROS = <?= json_encode($coleccionUsuario) ?>;

  function verLibro(libro) {
    abrirUI(`
        <div class="relative text-center">
            <button onclick="cerrarUI()" class="absolute -top-4 -right-2 w-10 h-10 bg-[#f4a261] text-[#0f0f1a] rounded-full flex items-center justify-center transition-all z-[110] shadow-xl font-bold border-2 border-[#0f0f1a] hover:scale-110">
                <span class="text-xl line-height-0">✕</span>
            </button>

            <div class="relative inline-block mb-4 group">
                <img src="img/${libro.portada}" onerror="this.src='img/default.jpg'" 
                     class="w-40 h-56 object-cover rounded-xl shadow-2xl border border-[#3a3a5a] transition-transform group-hover:scale-105">
                <div class="absolute -bottom-2 -right-2 bg-[#f4a261] text-[#1a1a2e] px-2 py-1 rounded-md font-bold text-xs shadow-lg">
                    <i class="fas fa-star"></i> ${libro.valoracion}
                </div>
            </div>

            <div class="mb-4">
                <h2 class="text-[#f4a261] text-2xl font-black uppercase leading-tight mb-1">
                    ${libro.titulo}
                </h2>
                
                <p class="text-gray-300 text-sm font-medium italic mb-2">por ${libro.autor}</p>
                
                <p class="text-gray-400 text-xs px-4 line-clamp-3 italic">
                    "${libro.descripcion || 'Sin descripción disponible.'}"
                </p>
            </div>

            <div class="grid grid-cols-3 gap-2 py-3 border-y border-[#2a2a4a] mb-5">
                <div class="text-center">
                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest">Categoría</span>
                    <span class="text-[11px] text-[#f4a261] font-bold">${libro.genero_nombre}</span>
                </div>
                <div class="text-center">
                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest">Año</span>
                    <span class="text-[11px] text-[#f4a261] font-bold">${libro.anio}</span>
                </div>
                <div class="text-center">
                    <span class="block text-[9px] text-gray-500 uppercase tracking-widest">Páginas</span>
                    <span class="text-[11px] text-[#f4a261] font-bold">${libro.paginas || '---'}</span>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-3 text-center font-bold">Añadir a mi biblioteca</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <button onclick="agregarLibro(${libro.id}, 'leyendo')" class="flex-1 bg-[#2a9d8f]/20 hover:bg-[#2a9d8f] text-[#2a9d8f] hover:text-white border border-[#2a9d8f]/30 px-2 py-2 rounded-lg text-[10px] font-bold transition-all">
                        LEYENDO
                    </button>
                    <button onclick="agregarLibro(${libro.id}, 'pendiente')" class="flex-1 bg-gray-500/20 hover:bg-gray-500 text-gray-400 hover:text-white border border-gray-500/30 px-2 py-2 rounded-lg text-[10px] font-bold transition-all">
                        PENDIENTE
                    </button>
                    <button onclick="agregarLibro(${libro.id}, 'completado')" class="flex-1 bg-[#f4a261]/20 hover:bg-[#f4a261] text-[#f4a261] hover:text-[#0f0f1a] border border-[#f4a261]/30 px-2 py-2 rounded-lg text-[10px] font-bold transition-all">
                        TERMINADO
                    </button>
                </div>
            </div>
            
        <div id="msg-${libro.id}" class="hidden text-xs mt-2 text-center text-white">
</div>

            <a href="nav/compararPrecios.php?id=${libro.id}" class="flex items-center justify-center gap-2 w-full bg-[#16213e] hover:bg-[#f4a261] text-[#f4a261] hover:text-[#0f0f1a] py-3 rounded-xl border border-[#f4a261]/50 font-bold text-xs transition-all uppercase tracking-widest">
                <i class="fas fa-tag text-[10px]"></i> VER PRECIOS
            </a>
        </div>
    `);
  }

  // Guarda el libro en la colección
  function agregarLibro(libro_id, estado) {

    var datos = new FormData();
    datos.append('libro_id', libro_id);
    datos.append('estado', estado);

    fetch('backend/guardar_estado.php', {
        method: 'POST',
        body: datos
      })
      .then(function(respuesta) {
        return respuesta.json();
      })
            .then(function(resultado) {
                if (resultado.success == true) {
                    var msg = document.getElementById('msg-' + libro_id);
                    msg.innerText = 'Libro guardado correctamente';
                    msg.style.color = '#2a9d8f';
                    msg.classList.remove('hidden');
                    setTimeout(function() {
                        msg.classList.add('hidden');
                    }, 2000);
                } else {
                    var msg = document.getElementById('msg-' + libro_id);
                    msg.innerText = 'Inicia sesión primero';
                    msg.style.color = '#f5481d';
                     msg.classList.remove('hidden');}
            });
        }

</script>
<?php include 'footer.php'; ?>