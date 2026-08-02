
<?php if($_SESSION['rol'] == 'admin'){
    echo 'hola administrador';
}
else if($_SESSION['rol'] == 'alumno'){
    ?>
    <div class="flex min-h-screen">
    <?php include __DIR__ . '/../layout/sidebar.php'; ?>
    <div class="flex-1 p-6 md:p-8">hola alumno</div>
    </div>
    <?php
}
else if($_SESSION['rol'] == 'tutor'){
    ?>
    <div class="flex min-h-screen">
    <?php include __DIR__ . '/../layout/sidebar.php'; ?>
    <div class="flex-1 p-6 md:p-8">hola tutor</div>
    </div>
    <?php
}
?>
