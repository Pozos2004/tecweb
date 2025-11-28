<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/vendor/autoload.php';

use TECWEB\MYAPI\Create;
use TECWEB\MYAPI\Read;
use TECWEB\MYAPI\Update;
use TECWEB\MYAPI\Delete;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// ENDPOINTS DIRECTOS
if ($method == 'POST' && $path == '/tecweb/practicas/a09/backend/product') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productos = new Create('marketzone');
    $productos->add(json_decode(json_encode($input)));
    echo $productos->getData();
}
elseif ($method == 'GET' && $path == '/tecweb/practicas/a09/backend/products') {
    $productos = new Read('marketzone');
    $productos->list();
    echo $productos->getData();
}
elseif ($method == 'GET' && preg_match('/\/tecweb\/practicas\/a09\/backend\/product\/(\d+)/', $path, $matches)) {
    $productos = new Read('marketzone');
    $productos->single($matches[1]);
    echo $productos->getData();
}
elseif ($method == 'PUT' && $path == '/tecweb/practicas/a09/backend/product') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productos = new Update('marketzone');
    $productos->edit(json_decode(json_encode($input)));
    echo $productos->getData();
}
elseif ($method == 'DELETE' && $path == '/tecweb/practicas/a09/backend/product') {
    $input = json_decode(file_get_contents('php://input'), true);
    $productos = new Delete('marketzone');
    $productos->delete($input['id']);
    echo $productos->getData();
}
else {
    echo json_encode(["status" => "error", "message" => "Ruta no encontrada: $path"]);
}
?>