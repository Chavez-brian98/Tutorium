
<?php if($_SESSION['rol'] == 'admin'){
    echo 'hola administrador';
}
else if($_SESSION['rol'] == 'alumno'){
    include __DIR__ . '/../layout/sidebar.php';
    echo 'hola alumno';
}
else if($_SESSION['rol'] == 'tutor'){
    include __DIR__ . '/../layout/sidebar.php';
    echo 'hola tutor';
}
?>
