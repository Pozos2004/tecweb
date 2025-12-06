<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

$cnx = mysqli_connect('localhost','root','','marketzone');
if (!$cnx) {
    echo json_encode(["status"=>"error", "message"=>"Error BD"]);
    exit;
}

/*--------------------------------
  DESCARGAS POR DÍA
--------------------------------*/
$q1 = "
SELECT DAYNAME(fecha_hora) AS dia, COUNT(*) AS total
FROM bitacora_descargas
GROUP BY DAYNAME(fecha_hora)
";
$r1 = mysqli_query($cnx, $q1);

$labels_dia = [];
$data_dia   = [];

while ($row = mysqli_fetch_assoc($r1)) {
    $labels_dia[] = $row['dia'];
    $data_dia[]   = intval($row['total']);
}

/*--------------------------------
  DESCARGAS POR HORA
--------------------------------*/
$q2 = "
SELECT HOUR(fecha_hora) AS hora, COUNT(*) AS total
FROM bitacora_descargas
GROUP BY HOUR(fecha_hora)
";
$r2 = mysqli_query($cnx, $q2);

$labels_hora = [];
$data_hora   = [];

while ($row = mysqli_fetch_assoc($r2)) {
    $labels_hora[] = $row['hora'];
    $data_hora[]   = intval($row['total']);
}

/*--------------------------------
  DESCARGAS POR TIPO (CORRECTO)
--------------------------------*/
$q3 = "
SELECT a.tipo_recurso AS tipo, COUNT(*) AS total
FROM bitacora_descargas b
JOIN archivos a ON a.id = b.archivo_id
GROUP BY a.tipo_recurso
";
$r3 = mysqli_query($cnx, $q3);

$labels_tipo = [];
$data_tipo   = [];

while ($row = mysqli_fetch_assoc($r3)) {
    $labels_tipo[] = $row['tipo'];
    $data_tipo[]   = intval($row['total']);
}

/*--------------------------------
  RESPUESTA JSON FINAL
--------------------------------*/
echo json_encode([
    "status" => "success",
    "por_dia" => [
        "labels" => $labels_dia,
        "data"   => $data_dia
    ],
    "por_hora" => [
        "labels" => $labels_hora,
        "data"   => $data_hora
    ],
    "por_tipo" => [
        "labels" => $labels_tipo,
        "data"   => $data_tipo
    ]
]);
