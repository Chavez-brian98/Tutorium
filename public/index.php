<?php

/**
 * Front Controller - Punto de entrada de la aplicación
 * Todas las peticiones se redirigen aquí a través de .htaccess
 */

// Cargar autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar archivo .env
require_once __DIR__ . '/../bootstrap.php';

// Usar la clase Router
use App\Router;

// Ajustar la base de rutas si la app está servida desde una subcarpeta
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$basePath = rtrim(str_replace('/index.php', '', dirname($scriptName)), '/');
if ($basePath && $basePath !== '.') {
	Router::setBasePath($basePath);
}

// Registrar rutas desde el archivo de rutas
require_once __DIR__ . '/../routes/routes.php';

try {
	// Ejecutar el dispatcher y mostrar la respuesta
	$response = Router::dispatch();

	// Si el dispatcher devolvió algo, imprimirlo
	if (is_string($response) || is_numeric($response)) {
		echo $response;
	} elseif (is_array($response) || is_object($response)) {
		// Enviar JSON si la respuesta es array/objeto
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($response);
	}
} catch (Throwable $e) {
	http_response_code(500);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'error' => $e->getMessage(),
		'file'  => $e->getFile(),
		'line'  => $e->getLine(),
	], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
