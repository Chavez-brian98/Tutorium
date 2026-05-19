<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Tutorium App' ?></title>

    <!-- CDN globales (usa CDN_URL si está configurado, sino enlaces públicos confiables) -->
    <?php if (getenv('CDN_URL')): ?>
        <link rel="stylesheet" href="<?= cdn('bootstrap/5.0.0/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= cdn('font-awesome/6/css/all.min.css') ?>">
    <?php else: ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php endif; ?>

    <!-- CSS de la aplicación -->
    <link rel="stylesheet" href="<?= asset('resources/css/style.css') ?>">

    <!-- Tailwind CDN (para diseño rápido en desarrollo) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <?php if (!empty($extra_css)) echo $extra_css; ?>
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="container mt-4">
        <?= $content ?? '' ?>
    </main>

    <!-- Scripts comunes -->
    <?php if (getenv('CDN_URL')): ?>
        <script src="<?= cdn('jquery/3.6.0/jquery.min.js') ?>"></script>
        <script src="<?= cdn('bootstrap/5.0.0/js/bootstrap.bundle.min.js') ?>"></script>
    <?php else: ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php endif; ?>
    <script src="<?= asset('resources/javascript/Alerts.js') ?>"></script>

    <?php if (!empty($extra_js)) echo $extra_js; ?>
</body>
</html>


