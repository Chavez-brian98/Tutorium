<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Tutorium App' ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <script src="<?= asset('javascript/Alerts.js') ?>"></script>
    <script src="<?= asset('javascript/Session.js') ?>"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($extra_css)) echo $extra_css; ?>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <?php require_once __DIR__ . '/Sidebar.php'; ?>

    <main class="flex-1 p-6 overflow-auto">
        <?= $content ?? '' ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('resources/javascript/Alerts.js') ?>"></script>

    <?php if (!empty($extra_js)) echo $extra_js; ?>
</body>
</html>