<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        '',
        'marketzone'
    );

    if(!$conexion) {
        die('¡Base de datos NO conextada!');
    }
?>