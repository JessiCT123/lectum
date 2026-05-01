<?php
// Los datos ($libros, $generos, $tiendas, $precios, $resenias) los inyecta ExplorarController.php
include_once dirname(__DIR__) . '/header.php';

$isLoggedIn = isset($_SESSION['id']) ? 'true' : 'false';
?>

<section id="tab-explore" class="tab-content px-4 max-w-7xl mx-auto w-full py-6">

    <div class="mb-6">
        <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Explorar Libros</h2>
        <p class="text-[#a8a5a0] text-sm">Filtra por género, autor o tipo de reseña</p>
    </div>

    <!-- Filtros -->
    <div class="bg-[#1a1a2e] rounded-xl p-4 mb-6 border border-[#2a2a4a]">
        <div class="flex flex-wrap gap-3">

            <!-- Filtro por género -->
            <select id="filter-genero"
                class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
                <option value="">Todos los géneros</option>
                <?php foreach ($generos as $gen): ?>
                    <option value="<?= htmlspecialchars($gen['nombre']) ?>"><?= ucfirst(htmlspecialchars($gen['nombre'])) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Filtro por valoración -->
            <select id="filter-valoracion"
                class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
                <option value="">Todas las reseñas</option>
                <option value="positive">Reseñas positivas (4-5)</option>
                <option value="negative">Reseñas negativas (1-2)</option>
            </select>

            <!-- Filtro por autor -->
            <input type="text" id="filter-autor" placeholder="Buscar por autor..."
                class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261] flex-1 min-w-48">

        </div>
    </div>

    <!-- Resultados -->
    <div id="explore-results" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"></div>

    <div id="ui-modal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div id="ui-content"
            class="bg-[#1a1a2e] border border-[#2a2a4a] max-w-sm w-full rounded-2xl p-6 shadow-2xl transform transition-all scale-95 opacity-0">
        </div>
    </div>

    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>

    <script>
        // sin sesión
        window.LIBROS = <?= json_encode($libros, JSON_THROW_ON_ERROR) ?>;

        //con sesión
        window.SESION_ACTIVA = <?= $isLoggedIn ?>;
    </script>
    <script>
        // Renderiza los libros según los filtros activos
        function aplicarFiltros() {
            const autor = document.getElementById('filter-autor')?.value.toLowerCase().trim() ?? '';
            const genero = document.getElementById('filter-genero')?.value.toLowerCase().trim() ?? '';
            const valoracion = document.getElementById('filter-valoracion')?.value ?? '';

            let libros = window.LIBROS ?? [];

            if (autor) {
                libros = libros.filter(l => l.autor.toLowerCase().includes(autor) || l.titulo.toLowerCase().includes(autor));
            }
            if (genero) {
                libros = libros.filter(l => l.genero_nombre.toLowerCase().includes(genero));
            }
            if (valoracion == 'positive') {
                libros = libros.filter(l => l.valoracion >= 4);
            } else if (valoracion == 'negative') {
                libros = libros.filter(l => l.valoracion <= 2);
            }

            const grid = document.getElementById('explore-results');
            grid.innerHTML = '';
            // Sin resultados
            if (libros.length == 0) {
                grid.innerHTML = '<p class="col-span-full text-center text-[#a8a5a0] py-10">No se encontraron libros.</p>';
                return;
            }

            // Pintar tarjetas
            for (var i = 0; i < libros.length; i++) {
                var libro = libros[i];

                // Si hay sesión mostramos el select, si no un mensaje
                var botonColeccion = '';
                if (window.SESION_ACTIVA == true) {
                    botonColeccion =
                        '<select class="mt-2 w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-2 py-1 text-xs text-[#a8a5a0]" onchange="agregarLibro(' + libro.id + ', this.value)">' +
                        '<option value="">+ Añadir a colección</option>' +
                        '<option value="Pendiente">Pendiente</option>' +
                        '<option value="Leyendo">Leyendo</option>' +
                        '<option value="Terminado">Terminado</option>' +
                        '</select>' +
                        '<p id="msg-' + libro.id + '" class="text-[10px] text-[#2a9d8f] mt-1 hidden">Guardado</p>';
                } else {
                    botonColeccion = '<p class="text-[10px] text-[#a8a5a0] mt-2 italic">Inicia sesión para añadir</p>';
                }

                grid.innerHTML +=
                    '<div class="book-card group cursor-pointer">' +
                    '<div class="aspect-[3/4] overflow-hidden relative rounded-lg" onclick=\'verLibro(' + JSON.stringify(libro) + ')\'>' +
                    '<img src="<?= BASE_URL ?>/Vista/assets/img/' + libro.portada + '" class="w-full h-full object-cover transition-transform group-hover:scale-105">' +
                    '<span class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] px-2 py-1 rounded">' + libro.genero_nombre + '</span>' +
                    '</div>' +
                    '<div class="p-3">' +
                    '<h4 class="font-display text-sm font-semibold text-white group-hover:text-[#f4a261] transition-colors">' + libro.titulo + '</h4>' +
                    '<p class="text-xs mt-1 text-gray-400">' + libro.autor + '</p>' +
                    '<div class="flex justify-between text-xs mt-2 text-gray-500">' +
                    '<span><i class="fas fa-star text-yellow-500"></i> ' + libro.valoracion + '</span>' +
                    '<span>' + libro.anio + '</span>' +
                    '</div>' +
                    botonColeccion +
                    '</div>' +
                    '</div>';
            }
        }

        function verLibro(libro) {
            abrirUI(`
        <div class="relative text-center">
            <button onclick="cerrarUI()" class="absolute -top-4 -right-2 w-10 h-10 bg-[#f4a261] text-[#0f0f1a] rounded-full flex items-center justify-center transition-all z-[110] shadow-xl font-bold border-2 border-[#0f0f1a] hover:scale-110">
                <span class="text-xl line-height-0">✕</span>
            </button>

            <div class="relative inline-block mb-4 group">
                <img src="<?= BASE_URL ?>/Vista/assets/img/${libro.portada}"
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
                    <button onclick="agregarLibro(${libro.id}, 'pendiente')" class="flex-1 bg-gray-500/20 hover:bg-gray-500 text-gray-400 hover:text-white border border-gray-500/30 px-2 py-2 rounded-lg text-[10px] font-bold transition-all">
                        PENDIENTE
                    </button>
                    <button onclick="agregarLibro(${libro.id}, 'leyendo')" class="flex-1 bg-[#2a9d8f]/20 hover:bg-[#2a9d8f] text-[#2a9d8f] hover:text-white border border-[#2a9d8f]/30 px-2 py-2 rounded-lg text-[10px] font-bold transition-all">
                        LEYENDO
                    </button>
                    <button onclick="agregarLibro(${libro.id}, 'terminado')" class="flex-1 bg-[#f4a261]/20 hover:bg-[#f4a261] text-[#f4a261] hover:text-[#0f0f1a] border border-[#f4a261]/30 px-2 py-2 rounded-lg text-[10px] font-bold transition-all">
                        TERMINADO
                    </button>
                </div>
            </div>

        <div id="msg-${libro.id}" class="hidden text-xs mt-2 text-center text-white">
</div>

            <a href="${window.BASE_URL}/inicio/compararPrecios?id=${libro.id}" class="flex items-center justify-center gap-2 w-full bg-[#16213e] hover:bg-[#f4a261] text-[#f4a261] hover:text-[#0f0f1a] py-3 rounded-xl border border-[#f4a261]/50 font-bold text-xs transition-all uppercase tracking-widest">
                <i class="fas fa-tag text-[10px]"></i> VER PRECIOS
            </a>
           <br> 
           <a href="${window.BASE_URL}/resenias/?id=${libro.id}" class="flex items-center justify-center gap-2 w-full bg-[#16213e] hover:bg-[#f4a261] text-[#f4a261] hover:text-[#0f0f1a] py-3 rounded-xl border border-[#f4a261]/50 font-bold text-xs transition-all uppercase tracking-widest">
                <i class="fas fa-tag text-[10px]"></i>  VER RESEÑAS
            </a>
             </br>

        </div>
    `);
        }

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


        //Mostar libro desde el buscador
        var params = new URLSearchParams(window.location.search);
        var buscarUrl = params.get('buscar');
        if (buscarUrl) {
            document.getElementById('filter-autor').value = buscarUrl;
        }

        // Escuchar cambios en los filtros
        document.getElementById('filter-autor').addEventListener('input', aplicarFiltros);
        document.getElementById('filter-genero').addEventListener('change', aplicarFiltros);
        document.getElementById('filter-valoracion').addEventListener('change', aplicarFiltros);


        // Cargar todos los libros al entrar
        aplicarFiltros();
    </script>
    <?php include_once __DIR__ . '/../footer.php'; ?>