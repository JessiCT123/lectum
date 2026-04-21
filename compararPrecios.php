<?php
session_start();
include 'backend/conexion.php';
include 'header.php';

//Cogemos los datos de la tienda y el id 
$libros  = $conn->query("SELECT l.*, g.nombre AS genero_nombre FROM libros l JOIN generos g ON l.genero_id = g.id ORDER BY l.titulo")->fetchAll(PDO::FETCH_ASSOC);
$precios = $conn->query("SELECT p.*, t.nombre AS tienda_nombre, t.icono AS tienda_icono, t.estrellas, t.envio FROM precios p JOIN tienda t ON p.tienda_id = t.id")->fetchAll(PDO::FETCH_ASSOC);

$idLibro = isset($_GET['id']) ? $_GET['id'] : '';

?>


<!-- COMPARADOR DE PRECIOS -->
    
<section id="tab-prices" class="tab-content px-4 max-w-7xl mx-auto w-full py-6">

    <div class="mb-6">
        <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Comparador de Precios</h2>
        <p class="text-[#a8a5a0] text-sm">Encuentra la mejor opción para comprar tus libros</p>
    </div>

    <!-- Selector de libro -->
    <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a] mb-6">
        <select id="price-book-select" class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
            <option value="">Selecciona un libro para comparar precios...</option>
        </select>
    </div>

        <!-- Resultado comparación - renderizarComparadorPrecios() -->
    <div id="price-comparison" class="hidden">
        <!-- Info del libro -->
        <div id="selected-book-info" class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a] mb-6"></div>
        <!-- Tarjetas por tienda -->
        <div id="store-prices" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>

    <!-- Estado vacío -->
    <div id="price-empty" class="text-center py-12">
        <h3 class="font-display text-lg text-[#a8a5a0] mb-2">Selecciona un libro</h3>
        <p class="text-sm text-[#6a6a8a]">Compara precios entre diferentes tiendas</p>
    </div>
    <script>
    window.LIBROS  = <?= json_encode($libros) ?>;
    window.PRECIOS = <?= json_encode($precios) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        let select = document.getElementById('price-book-select');

        for (let i = 0; i < window.LIBROS.length; i++) {
            let libro  = window.LIBROS[i];
            let opcion = document.createElement('option');
            opcion.value       = libro.id;
            opcion.textContent = libro.titulo;
            select.appendChild(opcion);
        }

        //  cuando tenemos el id del libro ,lo seleccionamos automáticamente
        let idLibro = '<?= $idLibro ?>';
        if (idLibro != '') {
            select.value = idLibro;
            mostrarPreciosLibro(idLibro);
        }

        select.addEventListener('change', function() {
            if (this.value != '') {
                mostrarPreciosLibro(this.value);
            }
        });
    });
</script>
</section>
<?php include 'footer.php'; ?>