
<?php if($_SESSION['rol'] == 'admin'){
    echo 'hola administrador';
}
else if($_SESSION['rol'] == 'alumno'){
    echo 'hola alumno';
}
else if($_SESSION['rol'] == 'tutor'){
    echo 'hola tutor';
}
?>
