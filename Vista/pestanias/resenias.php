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

                    <?php foreach ($resenias as $r): ?>

                        <div class="review-card">

                            <div class="review-header">
                                <span><?= htmlspecialchars($r['titulo_libro']) ?></span>
                                <span><?= $r['fecha'] ?></span>
                            </div>

                            <div>
                                <?= str_repeat("★", $r['valoracion']) ?>
                                <?= str_repeat("☆", 5 - $r['valoracion']) ?>
                            </div>

                            <div>
                                por <?= htmlspecialchars($r['nombre']) ?>
                            </div>

                            <p><?= htmlspecialchars($r['texto']) ?></p>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

        </div>

    </main>

    <script>

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
