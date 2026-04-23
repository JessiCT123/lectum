<?php
session_start();
include __DIR__ . '/../backend/conexion.php';
include __DIR__ . '/../header.php';
require_once __DIR__ . '/config/config.php';

if ($_POST) {

    $libro_id   = $_POST['libro_id'];
    $usuario_id = $_SESSION['id'];
    $valoracion = $_POST['valoracion'];
    $texto      = $_POST['texto'];

    $nuevo     = isset($_POST['nuevo']);
    $eliminar  = isset($_POST['eliminar']);

    try {

        if ($nuevo) {

            $sql = $conn->prepare("INSERT INTO resenias (libro_id, usuario_id, valoracion, texto, fecha)
                                   VALUES (:libro_id, :usuario_id, :valoracion, :texto, NOW())");

            $sql->bindParam(":libro_id", $libro_id);
            $sql->bindParam(":usuario_id", $usuario_id);
            $sql->bindParam(":valoracion", $valoracion);
            $sql->bindParam(":texto", $texto);
            $sql->execute();

            header("Location: resenias.php");
            exit();
        }

        if ($eliminar) {

            $sql = $conn->prepare("DELETE FROM resenias 
                                   WHERE libro_id = :libro_id 
                                   AND usuario_id = :usuario_id");

            $sql->bindParam(":libro_id", $libro_id);
            $sql->bindParam(":usuario_id", $usuario_id);
            $sql->execute();

            header("Location: resenias.php");
            exit();
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

/* LIBROS BD */
$sql = $conn->prepare("SELECT id, titulo, autor FROM libros");
$sql->execute();
$libros = $sql->fetchAll(PDO::FETCH_ASSOC);

/* RESEÑAS BD */
$sql = $conn->prepare("
    SELECT r.*, l.titulo, u.nombre
    FROM resenias r
    INNER JOIN libros l ON r.libro_id = l.id
    INNER JOIN usuarios u ON r.usuario_id = u.id
    ORDER BY r.fecha DESC
");
$sql->execute();
$reseñas = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reseñas</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<main class="page-main">

<div class="titulo">
<h1>Comunidad de <span>Reseñas</span></h1>
<p>Comparte tu experiencia con los libros</p>
</div>

<div class="resenias-grid">
    
<?php if (isset($_SESSION['id'])): ?>

<aside>
<div class="panel">
<h2>Escribe tu reseña</h2>

<form id="review-form" method="POST" action="resenias.php">
<input type="hidden" name="valoracion" id="valoracion" value="0">

<div class="form-group">
<label>Libro</label>
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
<label>Valoración</label>
<div id="stars-input">
<button type="button" class="star-btn" data-val="1">★</button>
<button type="button" class="star-btn" data-val="2">★</button>
<button type="button" class="star-btn" data-val="3">★</button>
<button type="button" class="star-btn" data-val="4">★</button>
<button type="button" class="star-btn" data-val="5">★</button>
</div>
</div>

<div class="form-group">
<label>Reseña</label>
<textarea name="texto" id="review-text" rows="5"></textarea>
</div>

<button type="submit" name="nuevo">Publicar Reseña</button>
</form>
</div>
</aside>

<?php else: ?>

<aside>
<div class="panel">
<h2>Escribe tu reseña</h2>
<p style="color:var(--ink-soft); margin-bottom:15px;">Inicia sesión para poder escribir una reseña.</p>
<a href="backend/sesion.php" style="display:block; text-align:center; background:var(--gold); color:white; padding:12px; border-radius:var(--radius-sm); font-weight:600;">Iniciar sesión</a>
</div>
</aside>

<?php endif; ?>

<section>

<div class="panel">

<h2>Reseñas recientes</h2>

<?php foreach ($reseñas as $r): ?>

<div class="review-card">

<div class="review-header">
<span><?= htmlspecialchars($r['titulo']) ?></span>
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

<?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>