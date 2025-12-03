<?php
    use TECWEB\MYAPI\Update\ProductEdit;
    require_once __DIR__ . '/../vendor/autoload.php';

    $productos = new ProductEdit('marketzone');
    $productos->edit( json_decode( json_encode($_POST) ) );
    echo $productos->getData();
?>