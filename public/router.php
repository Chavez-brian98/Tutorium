<?php
// Router para el servidor embebido de PHP (soporta pretty URLs)
// Uso: php -S localhost:8000 public/router.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requested = __DIR__ . $uri;

// Servir archivos estáticos directamente si existen
if ($uri !== '/' && file_exists($requested) && is_file($requested)) {
    return false; // Dejar que el servidor embebido sirva el archivo
}

// De lo contrario, pasar la petición al front controller
require_once __DIR__ . '/index.php';

