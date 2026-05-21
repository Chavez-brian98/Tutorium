<?php
// Router para el servidor embebido de PHP (soporta pretty URLs)
// Uso: php -S localhost:8000 public/router.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requested = __DIR__ . $uri;

// Servir assets ubicados en /resources desde la raíz del proyecto
$projectRequested = dirname(__DIR__) . $uri;

// Servir archivos estáticos directamente si existen
if ($uri !== '/' && file_exists($requested) && is_file($requested)) {
    return false; // Dejar que el servidor embebido sirva el archivo
}

if ($uri !== '/' && strpos($uri, '/resources/') === 0 && file_exists($projectRequested) && is_file($projectRequested)) {
    $extension = strtolower(pathinfo($projectRequested, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'json' => 'application/json; charset=UTF-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
    ];

    $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

    header('Content-Type: ' . $contentType);
    readfile($projectRequested);
    exit;
}

// De lo contrario, pasar la petición al front controller
require_once __DIR__ . '/index.php';

