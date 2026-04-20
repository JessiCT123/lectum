<!-- EXPLORAR -->

<section id="tab-explore" class="tab-content hidden">

    <div class="mb-6">
        <h2 class="font-display text-xl font-semibold text-[#f4a261] mb-2">Explorar Libros</h2>
        <p class="text-[#a8a5a0] text-sm">Filtra por género, autor o tipo de reseña</p>
    </div>

    <!-- Filtros -->
    <div class="bg-[#1a1a2e] rounded-xl p-4 mb-6 border border-[#2a2a4a]">
        <div class="flex flex-wrap gap-3">

            <!-- Filtro por género -->
            <select id="filter-genero" class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
                <option value="">Todos los géneros</option>
                <?php
                    //obtenemos los géneros de la BBDD
                    $queryGen = $conn->query("SELECT * FROM generos");
                    while($gen = $queryGen->fetch(PDO::FETCH_ASSOC)) {
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

</section>