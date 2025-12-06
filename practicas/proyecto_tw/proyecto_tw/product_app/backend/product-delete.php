<?php
header('Content-Type: application/json; charset=utf-8');

$cnx = mysqli_connect('localhost', 'root', '', 'marketzone');
if (!$cnx) { echo json_encode(["status"=>"error","message"=>"BD no disponible"]); exit; }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(["status"=>"error","message"=>"ID inválido"]);
    exit;
}

// (opcional) podrías borrar también el archivo físico leyendo ruta_archivo primero

$sql = "DELETE FROM archivos WHERE id=$id";

if (mysqli_query($cnx, $sql)) {
    echo json_encode(["status"=>"success","message"=>"Recurso eliminado"]);
} else {
    echo json_encode(["status"=>"error","message"=>"No se pudo eliminar"]);
}

mysqli_close($cnx);
