<?php
    use TECWEB\MYAPI\Read\ProductList;
    require_once __DIR__ . '/../vendor/autoload.php';

    $productos = new ProductList('marketzone');
    $productos->list();
    echo $productos->getData();
?>