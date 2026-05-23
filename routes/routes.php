<?php

/**
 * Definición de Rutas de la Aplicación
 *
 * Ejemplos:
 *   Router::get('/', function() { ... });
 *   Router::post('/users', 'UserController@store');
 *   Router::get('/users/{id}', 'UserController@show');
 */

use App\Router;
use App\Database;

// Ruta principal (usa layout base para incluir CDNs y assets globales)
Router::get('/', function () {
    return view('layout/base', [
        'title' => 'Login Tutorium',
        'content' => view('auth/login'),
    ]);
});


Router::get('/session', function () {
    return view('Session/Session');
});

require_once __DIR__ . '/../app/controller/SessionController.php';
require_once __DIR__ . '/../app/controller/EvaluationController.php';

Router::get('/evaluation/crear', function () {
    $controller = new EvaluationController();
    return $controller->mostrarFormulario();
});

Router::post('/evaluation/guardar', function () {
    $controller = new EvaluationController();
    return $controller->guardar();
});

Router::post('/evaluation/importar-xml', function () {
    $controller = new EvaluationController();
    return $controller->importarXML();
});

// Ejemplo con Controlador (descomenta para usar)
// Router::get('/users', 'ExampleUserController@index');
// Router::get('/users/{id}', 'ExampleUserController@show');
// Router::post('/users', 'ExampleUserController@store');

/**
 * Helper para renderizar vistas
 */
function view($name, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../resources/view/' . $name . '.php';

    if (!file_exists($viewPath)) {
        http_response_code(404);
        return json_encode(['error' => "Vista $name no encontrada"]);
    }

    ob_start();
    include $viewPath;
    return ob_get_clean();
}


