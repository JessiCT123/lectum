<?php

use Servicios\SesionServicio;

session_start();

// 1. Cargar autoload (solo este es obligatorio)
require_once __DIR__ . '/autoload.php';

// 2. Cargar configuración (esto NO es una clase, así que sí requiere require_once)
require_once __DIR__ . '/Config/config.php';

$session = new SesionServicio();
$session->restaurarSesionActiva();

// Parsear la URL
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si la URI empieza por basePath, eliminarlo
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// Normalizamos
$uri = '/' . ltrim($uri, '/');

$partes = array_values(array_filter(explode('/', trim($uri, '/'))));

$nombreControlador = !empty($partes[0]) ? ucfirst($partes[0]) . 'Controller' : 'InicioController';
$nombreMetodo = !empty($partes[1]) ? $partes[1] : 'index';

if (!class_exists("Controlador\\$nombreControlador")) {
    http_response_code(404);
    die("404 — Controlador $nombreControlador no encontrado");
}

$clase = "Controlador\\$nombreControlador";

$controlador = new $clase();

if (!method_exists($controlador, $nombreMetodo)) {
    http_response_code(404);
    die("404 — Método $nombreMetodo no encontrado");
}

$controlador->$nombreMetodo();
