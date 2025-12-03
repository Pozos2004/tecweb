<?php
    use TECWEB\MYAPI\Delete\ProductDelete;
    require_once __DIR__ . '/../vendor/autoload.php';

    $productos = new ProductDelete('marketzone');
    $productos->delete( $_POST['id'] );
    echo $productos->getData();
?>