<section class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white py-16 rounded-lg shadow-lg">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-extrabold mb-4">¡Bienvenido a Tutorium!</h1>
        <p class="text-lg opacity-90">Sistema simple de rutas, CDN y conexión a base de datos. Rápido de usar y fácil de entender.</p>
        <div class="mt-6 flex justify-center gap-4">
            <a href="<?= url('/') ?>" class="bg-white text-indigo-600 font-semibold px-4 py-2 rounded-md shadow hover:opacity-90">Inicio</a>
            <a href="<?= url('/test-assets') ?>" class="bg-white text-indigo-600 font-semibold px-4 py-2 rounded-md shadow hover:opacity-90">Test Assets</a>
            <a href="<?= url('/test-db') ?>" class="bg-white text-indigo-600 font-semibold px-4 py-2 rounded-md shadow hover:opacity-90">Test DB</a>
        </div>
    </div>
</section>

<div class="max-w-6xl mx-auto mt-10 px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-5">
        <h3 class="font-bold text-xl mb-2">Rutas rápidas</h3>
        <ul class="text-sm text-gray-700 space-y-2">
            <li><a class="text-indigo-600 hover:underline" href="<?= url('/') ?>">/ (inicio)</a></li>
            <li><a class="text-indigo-600 hover:underline" href="<?= url('/test-assets') ?>">/test-assets</a></li>
            <li><a class="text-indigo-600 hover:underline" href="<?= url('/test-db') ?>">/test-db</a></li>
            <li><a class="text-indigo-600 hover:underline" href="<?= url('/users/123') ?>">/users/123</a></li>
        </ul>
    </div>

    <div class="bg-white rounded-lg shadow p-5 md:col-span-2">
        <h3 class="font-bold text-xl mb-2">Ejemplos de uso</h3>
        <pre class="bg-gray-100 rounded p-4 text-sm text-gray-800 overflow-auto">
&lt;link rel="stylesheet" href="&lt;?= asset('resources/css/style.css') ?&gt;"&gt;

&lt;script src="&lt;?= cdn('bootstrap/5.0.0/js/bootstrap.min.js') ?&gt;"&gt;&lt;/script&gt;

Router::get('/users/{id}', function($id) {
    return json_encode(['user_id' => $id]);
});

$db = Database::getConnection();
$result = $db->query("SELECT * FROM users");
        </pre>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <h3 class="font-bold text-xl mb-2">Variables de entorno</h3>
        <div class="text-sm text-gray-700 space-y-2">
            <div><span class="font-semibold">APP_URL:</span> <?= getenv('APP_URL') ?: 'No configurado' ?></div>
            <div><span class="font-semibold">CDN_URL:</span> <?= getenv('CDN_URL') ?: 'No configurado' ?></div>
            <div><span class="font-semibold">DB_HOST:</span> <?= getenv('DB_HOST') ?: 'localhost' ?></div>
            <div><span class="font-semibold">DB_NAME:</span> <?= getenv('DB_NAME') ?: 'No configurado' ?></div>
            <div><span class="font-semibold">APP_ENV:</span> <?= getenv('APP_ENV') ?: 'No configurado' ?></div>
        </div>
    </div>
</div>



