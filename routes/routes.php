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
require_once __DIR__ . '/../app/controller/SessionController.php';
require_once __DIR__ . '/../app/controller/EvaluationController.php';
require_once __DIR__ . '/../app/controller/AttendanceController.php';
require_once __DIR__ . '/../app/controller/SessionController.php';
require_once __DIR__ . '/../app/controller/MaterialController.php';




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

Router::post('/attendance/marcar', function () {
    $controller = new AttendanceController();
    return $controller->marcar();
});

Router::get('/session', function () {
    $controller = new SessionController();
    return $controller->mostrar(1); // tutoria_id hardcodeado por ahora
});

Router::post('/material/guardar', function () {
    $controller = new MaterialController();
    return $controller->guardar();
});

Router::post('/session/guardarLink', function () {
    $controller = new SessionController();
    return $controller->guardarLink();
});


//rutas para el sidebar 
// Dashboard
Router::get('/dashboard', function () {
    return view('Dashboard/Dashboard', ['title' => 'Dashboard']);
});

// Tutorías para tutor/alumno
Router::get('/tutorias', function () {
    return view('TutorialsUser/Tutorial', ['title' => 'Tutorías']);
});

// Tutorías para admin
Router::get('/tutorias/admin', function () {
    return view('TutorialsAdmin/Tutorials', ['title' => 'Tutorías - Admin']);
});

// Evaluaciones
Router::get('/evaluaciones', function () {
    return view('EvaluationHistory/EvaluationHistory', ['title' => 'Evaluaciones']);
});

// Materias
//Router::get('/materias', function () {
    //return view('Materias/Materias', ['title' => 'Materias']);
//});

// Perfil
Router::get('/perfil', function () {
    return view('Profile/Profile', ['title' => 'Perfil']);
});
router::get('/auth/login', function () {
    return view('auth/login', ['title' => 'Login']);
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
        echo "Vista $name no encontrada";
        return;
    }

    // Vistas que NO usan base.php (login, errores, etc.)
    $sinLayout = ['auth/login', 'auth/register', 'layout/base'];

    if (in_array($name, $sinLayout)) {
        ob_start();
        include $viewPath;
        echo ob_get_clean();
        return;
    }

    // El resto de vistas se envuelven en base.php
    ob_start();
    include $viewPath;

    return ob_get_clean();
  
  // $content = ob_get_clean();

   // $layoutPath = __DIR__ . '/../resources/view/layout/base.php';
   // include $layoutPath;
}

   

