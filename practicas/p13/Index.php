<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath("/tecweb/practicas/p13");

// 1. MÉTODO GET en / - YA FUNCIONA
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hola Mundo desde Slim PHP!");
    return $response;
});

// 2. MÉTODO GET en /hola - MODIFICADO (sin {nombre})
$app->get('/hola', function (Request $request, Response $response, $args) {
    $nombre = $request->getQueryParams()['nombre'] ?? 'Invitado';
    $response->getBody()->write("Hola, $nombre!");
    return $response;
});

// 3. MÉTODO POST en /pruebapost - IGUAL
$app->post('/pruebapost', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $nombre = $data['nombre'] ?? 'Invitado';
    $response->getBody()->write("Hola $nombre (POST)");
    return $response;
});

// 4. MÉTODO POST en /testjson - IGUAL
$app->post('/testjson', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    
    $responseData = [
        'status' => 'success',
        'message' => 'Datos recibidos',
        'data' => $data
    ];
    
    $response->getBody()->write(json_encode($responseData, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
?>