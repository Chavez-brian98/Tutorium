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

//RUTAS LOGIN
Router::get('/', function () {
    return view('layout/base', [
        'title' => 'Login Tutorium',
        'content' => view('auth/login'),
    ]);
});

Router::get('/login', function () {
    return view('layout/base', [
        'title' => 'Login Tutorium',
        'content' => view('auth/login'),
    ]);
});

Router::post('/login', 'Auth\\LoginController@handle');

//USUARIOS
Router::get('/usuario/inicio', function () {
    return view('layout/base', [
        'title' => 'Mis tutorias',
        'content' => view('usuario/inicio'),
    ]);
});

//ADMINISTRADOR
Router::get('/admin/dashboard', function () {
    return view('layout/base', [
        'title' => 'Dashboard',
        'content' => view('admin/dashboard'),
    ]);
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
