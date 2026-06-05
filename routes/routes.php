<?php

/**
 * Definición de Rutas de la Aplicación
 */

use App\Router;
use App\Controller\AttendanceController;
use App\Controller\EvaluationController;
use App\Controller\MaterialController;
use App\Controller\SessionController;
use App\Controller\UsuariosController;

// ==========================================
// RUTAS DE AUTENTICACIÓN (LOGIN)
// ==========================================

Router::get('/', function () {
    return view('auth/login', ['title' => 'Login Tutorium']);
});

Router::get('/login', function () {
    return view('auth/login', ['title' => 'Login Tutorium']);
});

Router::post('/login', 'Auth\\LoginController@handle');


// ==========================================
// VISTAS DEL DASHBOARD / SECCIONES
// ==========================================

// USUARIOS (Corregido: Ya no llama a layout/base manualmente)
Router::get('/users/inicio', function () {
    return view('users/inicio', ['title' => 'Mis tutorias']);
});

// ADMINISTRADOR (Corregido: Ya no llama a layout/base manualmente)
Router::get('/admin/dashboard', function () {
    return view('admin/dashboard', ['title' => 'Dashboard']);
});

Router::get('/dashboard', function () {
    return view('Dashboard/Dashboard', ['title' => 'Dashboard']);
});

Router::get('/tutorias', function () {
    $controller = new \App\Controller\TutorialController();
    return $controller->index();
});

// NUEVA RUTA DINÁMICA: Capta el ID de la tutoría de forma limpia
Router::get('/tutorias/{tutoria_id}/sesiones', function ($tutoria_id) {
    $controller = new SessionController();
    return $controller->mostrar($tutoria_id);
});

Router::get('/tutorias/admin', function () {
    return view('admin/TutorialsAdmin/Tutorials', ['title' => 'Tutorías - Admin']);
});

Router::get('/materias', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->index();
});

Router::get('/usuarios', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->index();
});

Router::get('/evaluaciones', function () {
    return view('/usersEvaluationHistory/EvaluationHistory', ['title' => 'Evaluaciones']);
});

Router::get('/perfil', function () {
    return view('Profile/Profile', ['title' => 'Perfil']);
});


// ==========================================
// CONTROLADORES Y PROCESOS (POST/GET)
// ==========================================

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

Router::post('/material/guardar', function () {
    $controller = new MaterialController();
    return $controller->guardar();
});

Router::post('/materias/guardar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->guardar();
});

Router::post('/materias/actualizar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->actualizar();
});

Router::post('/materias/eliminar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->eliminar();
});

Router::post('/usuarios/guardar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->guardar();
});

Router::post('/usuarios/actualizar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->actualizar();
});

Router::post('/usuarios/eliminar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->eliminar();
});

Router::post('/session/guardarLink', function () {
    $controller = new SessionController();
    return $controller->guardarLink();
});


// ==========================================
// HELPER PARA RENDERIZAR VISTAS
// ==========================================

function view($name, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../resources/view/' . $name . '.php';

    if (!file_exists($viewPath)) {
        http_response_code(404);
        echo "Vista $name no encontrada";
        return;
    }

    // Evita bucles: Si se pide directamente el layout base, solo lo incluye
    $sinLayout = ['layout/base'];

    if (in_array($name, $sinLayout)) {
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    $layoutPath = __DIR__ . '/../resources/view/layout/base.php';
    ob_start();
    include $layoutPath;
    return ob_get_clean();
}