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

    /** Rutas que requieren roles específicos (sin basePath) */
    private static $protected = [];

    /** Rutas que no requieren autenticación (sin basePath) */
    private static $publicPaths = ['/', '/login'];

    public static function setBasePath($path)
    {
        self::$basePath = rtrim($path, '/');
    }

    public static function get($path, $callback)
    {
        self::add('GET', $path, $callback);
    }

    public static function post($path, $callback)
    {
        self::add('POST', $path, $callback);
    }

    public static function put($path, $callback)
    {
        self::add('PUT', $path, $callback);
    }

    public static function delete($path, $callback)
    {
        self::add('DELETE', $path, $callback);
    }

    public static function add($method, $path, $callback)
    {
        $path = self::$basePath . $path;
        self::$routes[$method][$path] = $callback;
    }

    /**
     * Marcar una ruta como protegida con roles específicos.
     * $path se almacena sin basePath para comparar contra la URI normalizada.
     */
    public static function protect($path, $roles = [])
    {
        self::$protected[$path] = $roles;
    }

    /**
     * Marcar una ruta como pública (no requiere autenticación)
     */
    public static function setPublic($path)
    {
        self::$publicPaths[] = $path;
    }

    public static function dispatch()
    {
        if (php_sapi_name() === 'cli') {
            http_response_code(404);
            echo json_encode(['error' => 'Acceso CLI no permitido']);
            return;
        }

        // Cabeceras anti-caché para evitar volver atrás tras cerrar sesión
        header('Cache-Control: no-cache, no-store, must-revalidate, private');
        header('Pragma: no-cache');
        header('Expires: 0');

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Remover ruta base
        if (self::$basePath && strpos($uri, self::$basePath) === 0) {
            $uri = substr($uri, strlen(self::$basePath));
        }

        $uri = rtrim($uri, '/') ?: '/';

        // Ruta exacta
        if (isset(self::$routes[$method][$uri])) {
            self::checkAuth($uri);
            return self::executeCallback(self::$routes[$method][$uri]);
        }

        // Ruta con parámetros (la ruta almacenada incluye basePath)
        foreach (self::$routes[$method] as $storedPath => $callback) {
            $pattern = self::pathToRegex($storedPath);
            if (preg_match($pattern, $uri, $matches)) {
                $limpios = array_filter($matches, fn($key) => is_int($key), ARRAY_FILTER_USE_KEY);
                array_shift($limpios);
                self::checkAuth($uri);
                return self::executeCallback($callback, array_values($limpios));
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada', 'uri' => $uri]);
        exit;
    }

    /**
     * Verificar autenticación y roles para la URI actual
     */
    private static function checkAuth($uri)
    {
        if (self::isPublic($uri)) {
            return;
        }
        \App\Middleware\Auth::requireLogin();
        $roles = self::matchProtection($uri);
        if (!empty($roles)) {
            \App\Middleware\Auth::requireRole(...$roles);
        }
    }

    private static function isPublic($uri)
    {
        foreach (self::$publicPaths as $public) {
            if (preg_match(self::pathToRegex($public), $uri)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Busca en las rutas protegidas si la URI coincide con algún patrón
     */
    private static function matchProtection($uri)
    {
        foreach (self::$protected as $pattern => $roles) {
            if (preg_match(self::pathToRegex($pattern), $uri)) {
                return $roles;
            }
        }
        return [];
    }

    private static function pathToRegex($path)
    {
        $path = preg_replace('/\{(\w+)\}/', '(?P<$1>\d+)', $path);
        $path = str_replace('*', '.*', $path);
        return '#^' . $path . '$#';
    }

    private static function executeCallback($callback, $params = [])
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

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

        return $callback;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }
}
