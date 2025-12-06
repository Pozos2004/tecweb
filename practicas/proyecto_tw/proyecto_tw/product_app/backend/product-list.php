<?php
header('Content-Type: application/json; charset=utf-8');

$cnx = @mysqli_connect('localhost', 'root', '', 'marketzone');

if (!$cnx) {
    echo json_encode([]);
    exit;
}

$data = [];

$sql = "SELECT id, titulo, descripcion, lenguaje, tipo_recurso, tipo_archivo, ruta_archivo
        FROM archivos
        ORDER BY fecha_alta DESC";

if ($res = mysqli_query($cnx, $sql)) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    mysqli_free_result($res);
}

mysqli_close($cnx);

echo json_encode($data);
