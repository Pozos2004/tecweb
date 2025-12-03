<?php
header('Content-Type: application/json; charset=utf-8');

// 1) Recibir datos
$nombre   = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($nombre === '' || $email === '' || $password === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Todos los campos son obligatorios"
    ]);
    exit;
}

// 2) Conexión a la BD (igual que DataBase.php)
$cnx = @mysqli_connect('localhost', 'root', '', 'marketzone');

if (!$cnx) {
    echo json_encode([
        "status"  => "error",
        "message" => "No se pudo conectar a la base de datos"
    ]);
    exit;
}

// 3) Escapar datos
$nombreEsc = mysqli_real_escape_string($cnx, $nombre);
$emailEsc  = mysqli_real_escape_string($cnx, $email);

// 4) ¿Ya existe el correo?
$sqlCheck = "SELECT id FROM usuarios WHERE email = '$emailEsc' LIMIT 1";
$resCheck = mysqli_query($cnx, $sqlCheck);

if ($resCheck && mysqli_num_rows($resCheck) > 0) {
    echo json_encode([
        "status"  => "error",
        "message" => "El correo ya está registrado"
    ]);
    mysqli_free_result($resCheck);
    mysqli_close($cnx);
    exit;
}
if ($resCheck) {
    mysqli_free_result($resCheck);
}

// 5) Insertar usuario
$hash = password_hash($password, PASSWORD_DEFAULT);

$sqlInsert = "
    INSERT INTO usuarios (nombre, email, password_hash)
    VALUES ('$nombreEsc', '$emailEsc', '$hash')
";

if (mysqli_query($cnx, $sqlInsert)) {
    echo json_encode([
        "status"  => "success",
        "message" => "Usuario registrado correctamente"
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Error al registrar usuario: " . mysqli_error($cnx)
    ]);
}

mysqli_close($cnx);
