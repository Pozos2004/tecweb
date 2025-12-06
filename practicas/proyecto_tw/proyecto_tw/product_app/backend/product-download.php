<?php
// ESTE ARCHIVO YA NO DEVUELVE JSON
// AQUÍ SE ENVÍA EL ARCHIVO DIRECTAMENTE AL NAVEGADOR

mysqli_report(MYSQLI_REPORT_OFF);
$cnx = @mysqli_connect('localhost', 'root', '', 'marketzone');

if (!$cnx) {
    http_response_code(500);
    die("Error de conexión a BD");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    die("ID inválido");
}

// OBTENER ARCHIVO
$sql = "SELECT id, titulo, ruta_archivo, tipo_archivo FROM archivos WHERE id = $id LIMIT 1";
$res = mysqli_query($cnx, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    http_response_code(404);
    die("Archivo no encontrado");
}

$file = mysqli_fetch_assoc($res);
$ruta = __DIR__ . '/../' . $file['ruta_archivo'];  // RUTA COMPLETA

if (!file_exists($ruta)) {
    http_response_code(404);
    die("El archivo no existe en el servidor");
}

// REGISTRO EN BITÁCORA
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$ip = mysqli_real_escape_string($cnx, $ip);

$log = "INSERT INTO bitacora_descargas (usuario_id, archivo_id, ip, fecha_hora)
        VALUES (NULL, {$file['id']}, '$ip', NOW())";
mysqli_query($cnx, $log);

// ENVIAR ARCHIVO AL NAVEGADOR
$nombreDescarga = basename($file['ruta_archivo']);
$extension = strtolower($file['tipo_archivo']);

header("Content-Disposition: attachment; filename=\"$nombreDescarga\"");
header("Content-Type: application/octet-stream");
header("Content-Length: " . filesize($ruta));

readfile($ruta);
exit;
