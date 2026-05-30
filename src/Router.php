<?php

namespace App;

/**
 * Router simple para gestionar rutas GET y POST
 * Uso:
 *   Router::get('/users', 'UserController@index');
 *   Router::post('/users', 'UserController@store');
 *   Router::dispatch();
 */
class Router
{
    private static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    private static $basePath = '';

    /**
     * Establecer la ruta base de la aplicación
     */
    public static function setBasePath($path)
    {
        self::$basePath = rtrim($path, '/');
    }

    /**
     * Registrar ruta GET
     */
    public static function get($path, $callback)
    {
        self::add('GET', $path, $callback);
    }

    /**
     * Registrar ruta POST
     */
    public static function post($path, $callback)
    {
        self::add('POST', $path, $callback);
    }

    /**
     * Registrar ruta PUT
     */
    public static function put($path, $callback)
    {
        self::add('PUT', $path, $callback);
    }

    /**
     * Registrar ruta DELETE
     */
    public static function delete($path, $callback)
    {
        self::add('DELETE', $path, $callback);
    }

    /**
     * Registrar una ruta genérica
     */
    public static function add($method, $path, $callback)
    {
        $path = self::$basePath . $path;
        self::$routes[$method][$path] = $callback;
    }

    /**
     * Ejecutar la ruta actual
     */
    public static function dispatch()
    {
        // Manejar CLI
        if (php_sapi_name() === 'cli') {
            http_response_code(404);
            echo json_encode(['error' => 'Acceso CLI no permitido']);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Remover ruta base si existe
        if (self::$basePath && strpos($uri, self::$basePath) === 0) {
            $uri = substr($uri, strlen(self::$basePath));
        }

        // Limpiar URI
        $uri = rtrim($uri, '/') ?: '/';

        // Buscar ruta exacta
        if (isset(self::$routes[$method][$uri])) {
            return self::executeCallback(self::$routes[$method][$uri]);
        }

        // Buscar ruta con parámetros FOREACH PARA VER SI FUNCIONA CON OTRO
       /* foreach (self::$routes[$method] as $path => $callback) {
            $pattern = self::pathToRegex($path);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remover el match completo
                return self::executeCallback($callback, $matches);
            }
        }*/

        // ASÍ DEBE QUEDAR (CORREGIDO):
        // DENTRO DE SRC/ROUTER.PHP -> METODO DISPATCH:
foreach (self::$routes[$method] as $path => $callback) {
    $pattern = self::pathToRegex($path);
    
    if (preg_match($pattern, $uri, $matches)) {
        // 1. Filtrar el array para quedarnos ÚNICAMENTE con las llaves numéricas
        $limpios = array_filter($matches, function($key) {
            return is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        // 2. CORRECCIÓN CRÍTICA:
        // El primer elemento de $matches siempre es la URL completa (ej: '/tutorias/1/sesiones').
        // Al usar array_values(), ese texto se quedaba en la posición 0.
        // Con array_shift quitamos la URL completa antes de mandar los parámetros reales.
        array_shift($limpios); 

        // 3. Ejecutar el callback pasando solo los ID puros (ej: [1])
        return self::executeCallback($callback, array_values($limpios));
    }
}

        // Ruta no encontrada
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada', 'uri' => $uri]);
        exit;
    }

    /**
     * Convertir ruta con parámetros a regex
     * Ej: /users/{id} => regex que capta {id}
     */
    private static function pathToRegex($path)
    {
        $path = preg_replace('/\{(\w+)\}/', '(?P<$1>\d+)', $path);
        return '#^' . $path . '$#';
    }

    /**
     * Ejecutar el callback de una ruta
     */
    private static function executeCallback($callback, $params = [])
    {
        // Si es una función anónima o callable
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        // Si es string tipo "Controller@metodo"
        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controller, $method) = explode('@', $callback);
            $controllerClass = 'App\\Controller\\' . $controller;

            if (!class_exists($controllerClass)) {
                http_response_code(500);
                echo json_encode(['error' => "Controlador $controllerClass no encontrado"]);
                exit;
            }

            $instance = new $controllerClass();
            if (!method_exists($instance, $method)) {
                http_response_code(500);
                echo json_encode(['error' => "Método $method no encontrado en $controllerClass"]);
                exit;
            }

            return call_user_func_array([$instance, $method], $params);
        }

        // Callback simple
        return $callback;
    }

    /**
     * Obtener todas las rutas registradas (útil para debugging)
     */
    public static function getRoutes()
    {
        return self::$routes;
    }
}


