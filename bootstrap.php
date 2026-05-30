<?php

/**
 * Bootstrap - Carga configuración y variables de entorno
 */
session_start();

// Agrega esto al inicio del bootstrap.php
date_default_timezone_set('America/El_Salvador');

// Ruta base del proyecto
$basePath = __DIR__;

// Cargar archivo .env
$envFile = $basePath . '/.env';
if (file_exists($envFile)) {
    loadEnvFile($envFile);
}

/**
 * Cargar variables desde archivo .env
 * Formato: KEY=value
 */
function loadEnvFile($filePath): void
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parsear línea KEY=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Establecer variable de entorno
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}


