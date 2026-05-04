<?php
// Los datos ($libros, $tienda, $precios, $resenias, $generos, $coleccionUsuario)
// los inyecta InicioController.php
include_once dirname(__DIR__) . '/Vista/header.php';
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
                    $masLeidos = $libros ?? [];
                    usort($masLeidos, function ($a, $b) {
                        return $b['lecturas'] <=> $a['lecturas'];
                    });
                    foreach (array_slice($masLeidos, 0, 5) as $libro):
                        ?>
                        <div class="book-card group cursor-pointer" >
                            <div class="aspect-[3/4] overflow-hidden relative rounded-lg">
                                <img src="<?= BASE_URL ?>/Vista/assets/img/<?= $libro['portada'] ?>"
                                     alt="<?= htmlspecialchars($libro['titulo']) ?>"
                                     class="w-full h-full object-cover transition-transform group-hover:scale-105">
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
                    $mejorValorados = $libros ?? [];
                    usort($mejorValorados, function ($a, $b) {
                        return $b['valoracion'] <=> $a['valoracion'];
                    });
                    foreach (array_slice($mejorValorados, 0, 5) as $libro):
                        ?>
                        <div class="book-card group cursor-pointer">
                            <div class="aspect-[3/4] overflow-hidden relative rounded-lg">
                                <img src="<?= BASE_URL ?>/Vista/assets/img/<?= $libro['portada'] ?>"
                                     alt="Portada <?= $libro['titulo'] ?>"
                                     class="w-full h-full object-cover transition-transform group-hover:scale-105">

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
                                <?= $totalLibros ?> libros • <i
                                        class="fas fa-star text-yellow-500 text-[10px]"></i> <?= number_format($media, 1) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<div id="ui-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div id="ui-content"
         class="bg-[#1a1a2e] border border-[#2a2a4a] max-w-sm w-full rounded-2xl p-6 shadow-2xl transform transition-all scale-95 opacity-0">
    </div>
</div>

<script>
    window.LIBROS = <?= json_encode($libros) ?>;
    window.TIENDA = <?= json_encode($tienda) ?>;
    window.RESENIAS = <?= json_encode($resenias) ?>;
    window.PRECIOS = <?= json_encode($precios) ?>;
    window.MIS_LIBROS = <?= json_encode($coleccionUsuario) ?>;

    // Guarda el libro en la colección
    async function agregarLibro(libro_id, estado) {

        const datos = new FormData();
        datos.append('libro_id', libro_id);
        datos.append('estado', estado);

        try {
            const respuesta = await fetch(`${window.BASE_URL}/perfil/guardarEstadoLibro`, {
                method: 'POST',
                body: datos
            });

            const json = await respuesta.json(); // siempre lo leemos

            if (respuesta.status === 401) {
                mostrarMensaje(libro_id, json.message, '#f5481d');
                return;
            }

            if (!respuesta.ok) {
                mostrarMensaje(libro_id, json.message || 'Error desconocido', '#f5481d');
                return;
            }

            // Éxito
            mostrarMensaje(libro_id, 'Libro guardado correctamente', '#2a9d8f', true);

        } catch (error) {
            mostrarMensaje(libro_id, 'Error de conexión', '#f5481d');
        }
    }

    function mostrarMensaje(id, texto, color, autoOcultar = false) {
        const msg = document.getElementById(`msg-${id}`);
        msg.innerText = texto;
        msg.style.color = color;
        msg.classList.remove('hidden');

        if (autoOcultar) {
            setTimeout(() => msg.classList.add('hidden'), 2000);
        }
    }

</script>
<script>
    window.BASE_URL = '<?= BASE_URL ?>';
</script>
<?php include_once dirname(__DIR__) . '/Vista/footer.php'; ?>
