<?php
use TECMEB\MYAPI\Products as Products;
require_once __DIR__ . '/myapi/Products.php';

if( isset($_GET['id']) ) {
    $prodObj = new Products('marketzone');
    $prodObj->single($_GET['id']);
    echo $prodObj->getData();
} else {
    echo json_encode(array(), JSON_PRETTY_PRINT);
}
?>