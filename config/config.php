<?php
// Detecta automáticamente el host y puerto del servidor actual , para evitar fallos 
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);
?>