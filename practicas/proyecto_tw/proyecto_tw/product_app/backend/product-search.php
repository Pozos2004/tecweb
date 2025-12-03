<?php
use TECWEB\MYAPI\Read\ProductSearch;
require_once __DIR__ . '/../vendor/autoload.php';

$search = $_GET['name'] ?? '';

$productos = new ProductSearch('marketzone');
$productos->search($search);
echo $productos->getData();
?>
