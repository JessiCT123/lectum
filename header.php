<?php
// Detecta qué página está activa comparando el nombre del archivo actual
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="es" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Biblioteca Personal</title>

<link rel="stylesheet" href="styles.css">

<script src="https://cdn.tailwindcss.com/3.4.17"></script>

<script src="js/func.js" defer></script>
<script src="js/events.js" defer></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body class="h-full font-body bg-[#0f0f1a] text-[#e8e6e3] overflow-auto">
<div id="app" class="min-h-full w-full">

<!-- HEADER -->
<header class="bg-gradient-to-r from-[#1a1a2e] to-[#16213e] border-b border-[#2a2a4a] sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 py-4">
<div class="flex items-center justify-between">

<div>
<h1 id="titulo-pg" class="font-display text-2xl md:text-3xl font-bold text-[#f4a261]">L E C T U M</h1>
<p id="bienvenida-msg" class="text-sm text-[#a8a5a0] mt-1">Descubre, organiza y comparte tus lecturas</p>
</div>

<!-- Buscador + botón perfil -->
<div class="flex items-center gap-3">

<!-- Buscador global - redirige a Explorar + filtra por autor -->
<div class="relative">
<input type="text" id="search-input" placeholder="Buscar libros..."
  class="bg-[#2a2a4a] border border-[#3a3a5a] rounded-full px-4 py-2 pl-10 text-sm w-48 md:w-64 focus:outline-none focus:border-[#f4a261] transition-colors">
<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#a8a5a0]" fill="none" stroke="currentColor" viewbox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
</div>

<!-- Botón perfil- mostrarModalPerfil() -->
<button id="boton-perfil" onclick="mostrarModalPerfil()" class="relative w-10 h-10 rounded-full bg-[#2a2a4a] flex items-center justify-center overflow-hidden border border-transparent hover:border-[#f4a261] transition-all">
    <span id="icono-por-defecto" class="text-xl">
      <?= isset($_SESSION['user_id']) ? '✅' : '👤' ?>
    </span>
    
    <img id="avatar-seleccionado-user" src="" class="hidden w-full h-full object-cover">
</button>

</div>
</div>
</div>
</header>

<!-- NAVEGACIÓN -->
<nav class="bg-[#1a1a2e] border-b border-[#2a2a4a]">
<div class="max-w-7xl mx-auto px-4">
<div class="flex gap-1 overflow-x-auto">
 
<a href="index.php" 
class="nav-tab px-4 py-3 text-sm font-medium <?= $paginaActual === 'index.php' ? 'tab-active' : '' ?>">
        Inicio
      </a>
<a href="destacados.php" 
class="nav-tab px-4 py-3 text-sm font-medium <?= $paginaActual === 'index.php' ? 'tab-active' : '' ?>">
        Destacados
      </a>

      <a href="explorar.php"
        class="nav-tab px-4 py-3 text-sm font-medium <?= $paginaActual === 'explorar.php' ? 'tab-active' : '' ?>">
        Explorar
      </a>

      <a href="mislibros.php"
        class="nav-tab px-4 py-3 text-sm font-medium <?= $paginaActual === 'mislibros.php' ? 'tab-active' : '' ?>">
        Mis Libros
      </a>

      <a href="resenias.php"
        class="nav-tab px-4 py-3 text-sm font-medium <?= $paginaActual === 'resenias.php' ? 'tab-active' : '' ?>">
        Reseñas
      </a>

      <a href="compararprecios.php"
        class="nav-tab px-4 py-3 text-sm font-medium <?= $paginaActual === 'compararprecios.php' ? 'tab-active' : '' ?>">
        Comparar Precios
      </a>

</div>
</div>
</nav>