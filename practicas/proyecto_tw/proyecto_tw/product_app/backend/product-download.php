<?php
use TECWEB\MYAPI\Create\DownloadRegister;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

$api = new DownloadRegister('marketzone');
$api->register( json_decode(json_encode($_POST)) );
echo $api->getData();
