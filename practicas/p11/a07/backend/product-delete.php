<?php
use TECMEB\MYAPI\Products as Products;
require_once __DIR__ . '/myapi/Products.php';

if( isset($_GET['id']) ) {
    $prodObj = new Products('marketzone');
    $prodObj->delete($_GET['id']);
    echo $prodObj->getData();
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No se recibió ID'), JSON_PRETTY_PRINT);
}
?>