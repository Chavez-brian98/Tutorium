<?php

/**
 * EJEMPLOS DE RUTAS - Tutorium
 *
 * Este archivo sirve como guía rápida para usar el sistema de rutas.
 *
 * Qué encontrarás aquí:
 * - Rutas GET, POST, PUT y DELETE
 * - Parámetros en la URL
 * - Query string
 * - Vistas
 * - Controladores tipo Controller@method
 * - Redirecciones
 * - Base path
 * - Helpers: url(), asset(), cdn(), route_url()
 * - Ejemplos prácticos: login, logout, perfil, CRUD
 *
 * Importante:
 * - No agrega lógica nueva.
 * - Los ejemplos están pensados para copiar en `routes/routes.php`
 *   o en tus vistas/controladores.
 */

// ============================================================================
// 1) IMPORTS BÁSICOS EN `routes/routes.php`
// ============================================================================

/*
use App\Router;
use App\Database;
*/

// ============================================================================
// 2) RUTA PRINCIPAL / HOME
// ============================================================================

/*
Router::get('/', function () {
    return view('layout/base', [
        'title' => 'Inicio - Tutorium',
        'content' => view('home'),
    ]);
});
*/

// ============================================================================
// 3) RUTAS GET BÁSICAS
// ============================================================================

/*
Router::get('/hola', function () {
    return 'Hola desde la ruta GET';
});

Router::get('/acerca-de', function () {
    return 'Página acerca de';
});

Router::get('/contacto', function () {
    return view('contacto');
});
*/

// ============================================================================
// 4) RUTAS GET CON PARÁMETROS
// ============================================================================

/*
Router::get('/users/{id}', function ($id) {
    return 'Usuario con ID: ' . $id;
});

Router::get('/posts/{postId}', function ($postId) {
    return 'Post con ID: ' . $postId;
});

Router::get('/users/{id}/orders/{orderId}', function ($id, $orderId) {
    return "Usuario $id - Orden $orderId";
});

Router::get('/productos/{id}/detalle', function ($id) {
    return json_encode([
        'product_id' => $id,
        'detalle' => 'Información del producto',
    ]);
});
*/

// ============================================================================
// 5) QUERY STRING EN LAS RUTAS
// ============================================================================

/*
// La ruta sigue siendo la misma; los filtros llegan por $_GET
Router::get('/productos', function () {
    $categoria = $_GET['categoria'] ?? 'todos';
    $orden = $_GET['orden'] ?? 'asc';

    return json_encode([
        'categoria' => $categoria,
        'orden' => $orden,
    ]);
});

// Ejemplo de URL:
// /productos?categoria=ropa&orden=desc
*/

// ============================================================================
// 6) RUTAS POST
// ============================================================================

/*
Router::post('/contacto', function () {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    return json_encode([
        'status' => 'ok',
        'nombre' => $nombre,
        'email' => $email,
        'mensaje' => $mensaje,
    ]);
});

Router::post('/api/login', function () {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    return json_encode([
        'status' => 'success',
        'email' => $data['email'] ?? null,
    ]);
});

Router::post('/usuarios', function () {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';

    return 'Crear users: ' . $nombre . ' / ' . $email;
});
*/

// ============================================================================
// 7) RUTAS PUT
// ============================================================================

/*
Router::put('/users/{id}', function ($id) {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    return json_encode([
        'status' => 'updated',
        'id' => $id,
        'data' => $data,
    ]);
});

Router::put('/perfil/{id}', function ($id) {
    return 'Actualizar perfil ' . $id;
});
*/

// ============================================================================
// 8) RUTAS DELETE
// ============================================================================

/*
Router::delete('/users/{id}', function ($id) {
    return json_encode([
        'status' => 'deleted',
        'id' => $id,
    ]);
});

Router::delete('/productos/{id}', function ($id) {
    return 'Eliminar producto ' . $id;
});
*/

// ============================================================================
// 9) VISTAS
// ============================================================================

/*
Router::get('/login', function () {
    return view('Auth/login');
});

Router::get('/perfil', function () {
    return view('layout/base', [
        'title' => 'Mi perfil',
        'content' => view('perfil', [
            'name' => 'Brian',
            'role' => 'Administrador',
        ]),
    ]);
});

Router::get('/dashboard', function () {
    return view('layout/base', [
        'title' => 'Dashboard',
        'content' => view('dashboard'),
    ]);
});
*/

// ============================================================================
// 10) CONTROLADORES TIPO Controller@method
// ============================================================================

/*
Router::get('/admin/users', 'ExampleUserController@index');
Router::get('/admin/users/{id}', 'ExampleUserController@show');
Router::post('/admin/users', 'ExampleUserController@store');
Router::put('/admin/users/{id}', 'ExampleUserController@update');
Router::delete('/admin/users/{id}', 'ExampleUserController@destroy');

Router::get('/Auth/login', 'AuthController@loginForm');
Router::post('/Auth/login', 'AuthController@login');
Router::get('/Auth/logout', 'AuthController@logout');
*/

// ============================================================================
// 11) EJEMPLOS DE LOGIN / LOGOUT / PERFIL
// ============================================================================

/*
// Mostrar formulario de login
Router::get('/login', function () {
    return view('Auth/login');
});

// Procesar login
Router::post('/login', function () {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    return 'Intento de login para: ' . $email;
});

// Cerrar sesión
Router::get('/logout', function () {
    header('Location: /login');
    exit;
});

// Perfil de users
Router::get('/perfil/{id}', function ($id) {
    return 'Perfil del users ' . $id;
});
*/

// ============================================================================
// 12) EJEMPLOS DE CRUD COMPLETO
// ============================================================================

/*
// Listar
Router::get('/users', 'ExampleUserController@index');

// Ver uno
Router::get('/users/{id}', 'ExampleUserController@show');

// Crear
Router::post('/users', 'ExampleUserController@store');

// Actualizar
Router::put('/users/{id}', 'ExampleUserController@update');

// Eliminar
Router::delete('/users/{id}', 'ExampleUserController@destroy');
*/

// ============================================================================
// 13) REDIRECCIONES
// ============================================================================

/*
Router::get('/inicio', function () {
    header('Location: /');
    exit;
});

Router::get('/old-login', function () {
    header('Location: /login');
    exit;
});
*/

// ============================================================================
// 14) BASE PATH
// ============================================================================

/*
// Si la app vive dentro de una subcarpeta, por ejemplo /tutorium
Router::setBasePath('/tutorium');

Router::get('/login', function () {
    return 'Login';
});
*/

// ============================================================================
// 15) HELPERS DE URLS Y ASSETS
// ============================================================================

/*
// En vistas:
<link rel="stylesheet" href="<?= asset('resources/css/style.css') ?>">
<script src="<?= asset('resources/javascript/Alerts.js') ?>"></script>

// URL relativa para navegación interna:
<a href="<?= url('/login') ?>">Login</a>
<a href="<?= url('/perfil/123') ?>">Perfil</a>

// CDN / recursos externos:
<link rel="stylesheet" href="<?= cdn('bootstrap/5.0.0/css/bootstrap.min.css') ?>">
<script src="<?= cdn('bootstrap/5.0.0/js/bootstrap.bundle.min.js') ?>"></script>

// URL completa basada en APP_URL:
<a href="<?= route_url('/users/123') ?>">Ver users</a>
*/

// ============================================================================
// 16) FORMULARIOS HTML + RUTAS
// ============================================================================

/*
<!-- resources/view/Auth/login.php -->
<form action="<?= url('/login') ?>" method="post">
    <input type="email" name="email" placeholder="Correo">
    <input type="password" name="password" placeholder="Contraseña">
    <button type="submit">Entrar</button>
</form>

<!-- routes/routes.php -->
Router::post('/login', function () {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    return 'Login recibido de: ' . $email;
});
*/

// ============================================================================
// 17) RUTAS + BASE DE DATOS (REFERENCIA)
// ============================================================================

/*
Router::get('/test-db', function () {
    $db = Database::getConnection();
    $stmt = $db->query('SELECT 1 as status');

    return json_encode($stmt->fetch());
});
*/

// ============================================================================
// 18) RUTAS API / JSON
// ============================================================================

/*
Router::get('/api/users', function () {
    header('Content-Type: application/json; charset=utf-8');

    return json_encode([
        'status' => true,
        'data' => [
            ['id' => 1, 'name' => 'Juan'],
            ['id' => 2, 'name' => 'María'],
        ],
    ]);
});

Router::post('/api/users', function () {
    header('Content-Type: application/json; charset=utf-8');

    return json_encode([
        'status' => 'created',
        'message' => 'Usuario creado',
    ]);
});
*/

// ============================================================================
// 19) FLUJO COMPLETO DE UNA PETICIÓN
// ============================================================================

/*
1. El navegador hace una petición a /users/123
2. `public/index.php` carga Composer y `bootstrap.php`
3. `routes/routes.php` registra las rutas
4. `Router::dispatch()` analiza la URL actual
5. Si coincide con una ruta, ejecuta el callback
6. El callback devuelve texto, HTML o JSON
7. La respuesta sale al navegador
*/

// ============================================================================
// 20) BUENAS PRÁCTICAS RÁPIDAS
// ============================================================================

/*
- Usa GET para mostrar datos.
- Usa POST para crear o enviar formularios.
- Usa PUT para actualizar.
- Usa DELETE para eliminar.
- Usa nombres claros y consistentes en las rutas.
- Mantén las rutas cortas y fáciles de leer.
- Para vistas, usa `view('nombre')` o `view('layout/base', [...])`.
- Para enlaces internos, usa `url('/ruta')`.
- Para assets, usa `asset('resources/css/style.css')`.
- Para URLs completas, usa `route_url('/ruta')`.
- Si devuelves JSON, envía `Content-Type: application/json`.
*/

// Fin del archivo de ejemplos.

