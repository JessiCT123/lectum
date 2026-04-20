<!-- EXPLORAR -->

<?php
session_start();
include 'backend/conexion.php';
include 'header.php';

//comprobamos si el usuario  a iniciado sesión 
$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$isLoggedIn = $userId ? 'true' : 'false';



// ── Cargar datos  ──
$libros = $conn->query("SELECT l.*, g.nombre AS genero_nombre FROM libros l JOIN generos g ON l.genero_id = g.id ORDER BY l.id")->fetchAll();
$tiendas = $conn->query("SELECT * FROM tienda ORDER BY id")->fetchAll();
$precios = $conn->query("SELECT * FROM precios")->fetchAll();
$resenias = $conn->query("SELECT r.*, l.titulo AS titulo_libro, u.usuario AS nombre_usuario FROM resenias r JOIN libros l ON r.libro_id = l.id JOIN usuarios u ON r.usuario_id = u.id ORDER BY r.fecha DESC LIMIT 20")->fetchAll();
$generos = $conn->query("SELECT * FROM generos ORDER BY nombre")->fetchAll();
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
            <select id="filter-genero" class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
                <?php
                //obtenemos los géneros de la BBDD
                $queryGen = $conn->query("SELECT * FROM generos");
                while ($gen = $queryGen->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$gen['nombre']}'>" . ucfirst($gen['nombre']) . "</option>";
                }
                ?>
            </select>

            <!-- Filtro por valoración -->
            <select id="filter-valoracion" class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
                <option value="">Todas las reseñas</option>
                <option value="positivas">Reseñas positivas (4-5)</option>
                <option value="negativas">Reseñas negativas (1-2)</option>
            </select>

            <!-- Filtro por autor -->
            <input type="text" id="filter-autor" placeholder="Buscar por autor..."
                class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261] flex-1 min-w-48">

        </div>
    </div>

    <!-- Resultados -->
    <div id="explore-results" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"></div>
    <script>
        // sin sesión
        window.LIBROS = <?= json_encode($libros) ?>;

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
                libros = libros.filter(l => l.autor.toLowerCase().includes(autor));
            }
            if (genero) {
                libros = libros.filter(l => l.genero_nombre.toLowerCase().includes(genero));
            }
            if (valoracion === 'positive') {
                libros = libros.filter(l => l.valoracion >= 4);
            } else if (valoracion === 'negative') {
                libros = libros.filter(l => l.valoracion <= 2);
            }

            const grid = document.getElementById('explore-results');
            grid.innerHTML= '';
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
                            '<option value="leyendo">Leyendo</option>' +
                            '<option value="terminado">Terminado</option>' +
                            '<option value="pendiente">Pendiente</option>' +
                        '</select>' +
                        '<p id="msg-' + libro.id + '" class="text-[10px] text-[#2a9d8f] mt-1 hidden">Guardado</p>';
                } else {
                    botonColeccion = '<p class="text-[10px] text-[#a8a5a0] mt-2 italic">Inicia sesión para añadir</p>';
                }

                grid.innerHTML +=
                    '<div class="book-card group cursor-pointer">' +
                        '<div class="aspect-[3/4] overflow-hidden relative rounded-lg" onclick=\'verLibro(' + JSON.stringify(libro) + ')\'>' +
                            '<img src="img/' + libro.portada + '" onerror="this.src=\'img/default.jpg\'" class="w-full h-full object-cover transition-transform group-hover:scale-105">' +
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

        // Guarda el libro en la colección
        function agregarLibro(libro_id, estado) {
            if (estado == '') {
                return;
            }

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
                    msg.classList.remove('hidden');
                    setTimeout(function() {
                        msg.classList.add('hidden');
                    }, 2000);
                } else {
                    alert('Error al guardar. Inicia sesión primero.');
                }
            });
        }

        // Escuchar cambios en los filtros
        document.getElementById('filter-autor').addEventListener('input', aplicarFiltros);
        document.getElementById('filter-genero').addEventListener('change', aplicarFiltros);
        document.getElementById('filter-valoracion').addEventListener('change', aplicarFiltros);

        // Cargar todos los libros al entrar
        aplicarFiltros();
    </script>
</section>
<?php include 'footer.php'; ?>