<?php

/**
 * Helpers para generar URLs de assets y CDN
 */

/**
 * Generar URL de asset (CSS, JS, imágenes, etc.)
 * @param string $path Ruta relativa del asset (ej: 'css/app.css')
 * @return string URL completa del asset
 */
function asset($path)
{
    // Detectar si es HTTP o HTTPS
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Obtener la subcarpeta real del proyecto (ej: /Tutorium/public)
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $subDir = rtrim(str_replace('/index.php', '', $scriptName), '/');

    // Construir la URL base dinámica
    $appUrl = $protocol . $host . $subDir;

    // Si la ruta comienza con /, no añadimos barras extra
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }

    return $appUrl . $path;
}

/**
 * Generar URL de CDN
 * @param string $path Ruta relativa del recurso en CDN (ej: 'lib/bootstrap.css')
 * @return string URL completa del CDN o asset local si no está configurado
 */
function cdn($path)
{
    $cdnUrl = getenv('CDN_URL');

    // Si no hay CDN_URL configurado, devolver asset local
    if (!$cdnUrl) {
        return asset($path);
    }

    $cdnUrl = rtrim($cdnUrl, '/');

    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }

    return $cdnUrl . $path;
}

/**
 * Generar URL para una ruta de la aplicación
 * @param string $path Ruta relativa (ej: '/users', '/posts/123')
 * @return string URL completa de la ruta
 */
function route_url($path = '/')
{
    return asset($path);
}

/**
 * Generar URL completamente relativa (para links internos)
 * @param string $path Ruta relativa
 * @return string Ruta relativa (útil para href)
 */
function url($path = '/')
{
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }
    return $path;
}


