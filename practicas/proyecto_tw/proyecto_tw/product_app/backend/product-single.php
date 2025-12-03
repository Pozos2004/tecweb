<?php
    use TECWEB\MYAPI\Read\ProductSingle;
    require_once __DIR__ . '/../vendor/autoload.php';

    $productos = new ProductSingle('marketzone');
    $productos->single( $_POST['id'] );
    echo $productos->getData();
?>