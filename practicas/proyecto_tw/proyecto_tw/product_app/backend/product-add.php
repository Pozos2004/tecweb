<?php
    use TECWEB\MYAPI\Create\ProductAdd;
    require_once __DIR__ . '/../vendor/autoload.php';

    $productos = new ProductAdd('marketzone');
    $productos->add( json_decode( json_encode($_POST) ) );
    echo $productos->getData();
?>