<?php
session_start();
include __DIR__ . '/../backend/conexion.php';
include __DIR__ . '/../header.php';
require_once __DIR__ . '/../config/config.php';

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

    //la función se ejecuta al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        renderColeccion('all');
    });


    //mapa de libro por ID 
    function buildColeccionMap() {
        var map = {};
        for (var i = 0; i < window.USER_COL.length; i++) {
            var entrada = window.USER_COL[i];
            map[entrada.libro_id] = entrada.estado;
        }
        return map;
    }

    // función principal filtra los libros ( todos, leyendo,terminado..)
    function renderColeccion(filtro = 'all') {
        const grid = document.getElementById('grid-coleccion');
        const msgBox = document.getElementById('msg-vacio');
        const txtMsg = document.getElementById('txt-vacio');

        //comprobamos si el usuario ha iniciado sesión 

        if (window.SESION_ACTIVA != true) {
            msgBox.classList.remove('hidden');
            txtMsg.textContent = 'Hay que iniciar sesión para ver tus libros.';
            return;
        }

        var mapa = buildColeccionMap();

        //muestra los libros que están en la colección del usuario

        var libros = [];
        for (var i = 0; i < window.DB_LIBROS.length; i++) {
            var libro = window.DB_LIBROS[i];
            if (mapa[libro.id] != undefined) {
                libros.push(libro);
            }
        }

        // mostrar los libros con filtros. 

        if (filtro != 'all') {
            var librosFiltrados = [];
            for (var i = 0; i < libros.length; i++) {
                if (mapa[libros[i].id] == filtro) {
                    librosFiltrados.push(libros[i]);
                }
            }
            libros = librosFiltrados;
        }

        grid.innerHTML = ''; //limpiamos


        // Si no hay libros mostramos un mensaje
        if (libros.length == 0) {
            msgBox.classList.remove('hidden');
            if (filtro == 'all') {
                txtMsg.textContent = 'Aún no tienes libros en tu colección.';
            } else if (filtro == 'leyendo') {
                txtMsg.textContent = 'No tienes libros en curso.';
            } else if (filtro == 'terminado') {
                txtMsg.textContent = 'No has terminado ningún libro todavía.';
            } else if (filtro == 'pendiente') {
                txtMsg.textContent = 'No tienes libros pendientes.';
            }
            return;
        }

        // si hay libros en la colección ocultamos los mensajes 
        msgBox.classList.add('hidden');
        for (var i = 0; i < libros.length; i++) {
            grid.insertAdjacentHTML('beforeend', tarjetaHTML(libros[i], mapa[libros[i].id]));
        }
    }


    // botes para filtrar los estados 
    function filtrarColeccion(estado, btn) {
        document.querySelectorAll('.status-filter-btn').forEach(b => {
            b.classList.remove('bg-[#f4a261]', 'text-[#0f0f1a]');
            b.classList.add('bg-[#2a2a4a]', 'text-[#a8a5a0]');
        });
        btn.classList.add('bg-[#f4a261]', 'text-[#0f0f1a]');
        btn.classList.remove('bg-[#2a2a4a]', 'text-[#a8a5a0]');
        renderColeccion(estado);
    }

    // datos de los libros
    function tarjetaHTML(libro, estado) {

        const etiquetas = {
            leyendo: {
                label: 'Leyendo',
                color: 'bg-[#2a9d8f] text-white'
            },
            terminado: {
                label: 'Terminado',
                color: 'bg-[#f4a261] text-[#0f0f1a]'
            },
            pendiente: {
                label: 'Pendiente',
                color: 'bg-gray-500 text-white'
            },
        };

        // Elegir la etiqueta correcta
        let etiqueta;

        if (etiquetas[estado]) {
            etiqueta = etiquetas[estado];
        } else {
            etiqueta = {
                label: estado,
                color: 'bg-gray-600 text-white'
            };
        }
        // 


        //portada 
        let portada;

        if (libro.portada != null && libro.portada != '') {
            portada = '<img src="' + window.BASE_URL + '/img/' + libro.portada + '" alt="' + libro.titulo + '" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">';
        } else {
            portada = '<i class="fa-solid fa-book text-4xl text-[#a8a5a0]"></i>';
        }

        // Autor del libro
        let autor;

        if (libro.autor != null) {
            autor = libro.autor;
        } else {
            autor = '';
        }
        // Devolver el HTML de la tarjeta
        return '<div class="book-card flex flex-col gap-2" data-estado="' + estado + '">' +
            '<div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-[#1a1a2e] flex items-center justify-center group shadow-lg">' +
            portada +
            '<span class="absolute top-2 left-2 text-[10px] font-bold uppercase px-2 py-0.5 rounded-full ' + etiqueta.color + '">' +
            etiqueta.label +
            '</span>' +
            '</div>' +
            '<p class="text-white text-[13px] font-semibold leading-tight line-clamp-2">' + libro.titulo + '</p>' +
            '<p class="text-[#a8a5a0] text-[11px]">' + autor + '</p>' +
            '</div>';
    }
</script>
<script>window.BASE_URL = '<?= BASE_URL ?>';</script>

<?php include __DIR__ . '/../footer.php'; ?>