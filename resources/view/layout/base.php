<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <title><?= $title ?? 'Tutorium' ?></title>

    <!-- CSS de la aplicación -->
    <link rel="stylesheet" href="<?= asset('resources/css/style.css') ?>">

    <!-- Tailwind CDN (para diseño rápido en desarrollo) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- SweetAlert2 CDN (necesario para Alerts.js) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <?php if (!empty($extra_css)) echo $extra_css; ?>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen">
<!--    --><?php //include __DIR__ . '/navbar.php'; ?>

    <main class="min-h-screen">
        <?= $content ?? '/' ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
    <script src="<?= asset('resources/javascript/Alerts.js') ?>"></script>

    <?php if (!empty($extra_js)) echo $extra_js; ?>
</body>
</html>

