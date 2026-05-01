<?php
include_once dirname(__DIR__) . '/header.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reseñas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Vista/assets/css/styles.css">
</head>

<body>

    <main class="page-main">

        <div class="titulo">
            <h1>Comunidad de <span>Reseñas</span></h1>
            <p>Comparte tu experiencia con los libros</p>
        </div>

        <div class="resenias-grid">

            <?php if (isset($_SESSION['id'])): ?>

                <aside aria-label="Escribe tu reseña">
                    <div class="panel">
                        <h2>Escribe tu reseña</h2>

                        <form id="review-form" method="POST" action="<?= BASE_URL ?>/resenias/publicar">
                            <input type="hidden" name="valoracion" id="valoracion" value="0">

                            <div class="form-group">
                                <label for="sel-libro">Libro</label>
                                <select id="sel-libro" name="libro_id">
                                    <option value="">Selecciona un libro...</option>
                                    <?php foreach ($libros as $l): ?>
                                        <option value="<?= $l['id'] ?>">
                                            <?= htmlspecialchars($l['titulo']) ?> — <?= htmlspecialchars($l['autor']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="stars-input">Valoración</label>
                                <div id="stars-input">
                                    <button type="button" class="star-btn" data-val="1">★</button>
                                    <button type="button" class="star-btn" data-val="2">★</button>
                                    <button type="button" class="star-btn" data-val="3">★</button>
                                    <button type="button" class="star-btn" data-val="4">★</button>
                                    <button type="button" class="star-btn" data-val="5">★</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="review-text">Reseña</label>
                                <textarea name="texto" id="review-text" rows="5"></textarea>
                            </div>

                            <button type="submit" name="nuevo">Publicar Reseña</button>
                        </form>
                    </div>
                </aside>

            <?php else: ?>

                <aside aria-label="Formulario Reseña">
                    <div class="panel">
                        <h2>Escribe tu reseña</h2>
                        <p style="color:var(--ink-soft); margin-bottom:15px;">Inicia sesión para poder escribir una
                            reseña.</p>
                        <a href="<?= BASE_URL ?>/auth"
                            style="display:block; text-align:center; background:var(--gold); color:white; padding:12px; border-radius:var(--radius-sm); font-weight:600;">Iniciar
                            sesión</a>
                    </div>
                </aside>

            <?php endif; ?>

            <section>

                <div class="panel">

                    <h2>Reseñas recientes</h2>

                    <!-- Selector de libro -->

                    <div class="bg-[#1a1a2e] rounded-xl p-5 border border-[#2a2a4a] mb-6">
                        <select id="book-select"
                            class="w-full bg-[#2a2a4a] border border-[#3a3a5a] rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#f4a261]">
                            <option value="">Selecciona un libro para ver la reseña </option>
                        </select>
                    </div>

                    <!-- Resultado comparación -  -->
                    <div id="resenias-box" class="hidden">
        
                    </div>
                </div>

            </section>

        </div>

    </main>

    <script>
        window.LIBROS = <?= json_encode($libros, JSON_THROW_ON_ERROR) ?>;
         window.RESENIAS = <?= json_encode($resenias, JSON_THROW_ON_ERROR) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            let select = document.getElementById('book-select');

            for (let i = 0; i < window.LIBROS.length; i++) {
                let libro = window.LIBROS[i];
                let opcion = document.createElement('option');
                opcion.value = libro.id;
                opcion.textContent = libro.titulo;
                select.appendChild(opcion);
            }

            //  cuando tenemos el id del libro ,lo seleccionamos automáticamente
            let idLibro = '<?= $idLibro ?>';
            if (idLibro != '') {
                select.value = idLibro;
                mostrarResenia(idLibro);
            }

            select.addEventListener('change', function() {
                if (this.value != '') {
                    mostrarResenia(this.value);
                }
            });
        });

        let valoracion = parseInt(document.getElementById("valoracion").value) || 0;

        document.querySelectorAll(".star-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                valoracion = parseInt(btn.dataset.val);

                document.querySelectorAll(".star-btn").forEach((b, i) => {
                    b.classList.toggle("active", i < valoracion);
                });
            });
        });

        document.getElementById("review-form").addEventListener("submit", () => {
            document.getElementById("valoracion").value = valoracion;
        });
    </script>

    <?php include_once __DIR__ . '/../footer.php'; ?>

</body>

</html>