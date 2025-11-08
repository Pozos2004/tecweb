<?php
use TECMEB\MYAPI\Products as Products;
require_once __DIR__ . '/myapi/Products.php';

if( isset($_GET['search']) ) {
    $prodObj = new Products('marketzone');
    $prodObj->search($_GET['search']);
    echo $prodObj->getData();
} else {
    echo json_encode(array(), JSON_PRETTY_PRINT);
}
?>