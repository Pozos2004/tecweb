<?php
use TECMEB\MYAPI\Products as Products;
require_once __DIR__ . '/myapi/Products.php';

$producto = file_get_contents('php://input');

if(!empty($producto)) {
    $jsonOBJ = json_decode($producto);
    $prodObj = new Products('marketzone');
    $prodObj->add($jsonOBJ);
    echo $prodObj->getData();
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No se recibieron datos del producto'), JSON_PRETTY_PRINT);
}
?>