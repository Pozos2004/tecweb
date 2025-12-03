<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

// 1) Recibir datos
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($email === '' || $password === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "Email y contraseña son obligatorios"
    ]);
    exit;
}

// 2) Conexión BD
$cnx = @mysqli_connect('localhost', 'root', '', 'marketzone');

if (!$cnx) {
    echo json_encode([
        "status"  => "error",
        "message" => "No se pudo conectar a la base de datos"
    ]);
    exit;
}

$emailEsc = mysqli_real_escape_string($cnx, $email);

// 3) Buscar usuario
$sql = "SELECT id, nombre, password_hash FROM usuarios WHERE email = '$emailEsc' LIMIT 1";
$res = mysqli_query($cnx, $sql);

$usuarioIdParaBitacora = 'NULL';
$exito = 0;
$accion = '';

if ($res && mysqli_num_rows($res) === 1) {
    $row = mysqli_fetch_assoc($res);
    $usuarioIdParaBitacora = (int) $row['id'];

    if (password_verify($password, $row['password_hash'])) {
        // Login correcto
        $_SESSION['usuario_id']   = $row['id'];
        $_SESSION['usuario_name'] = $row['nombre'];

        $exito  = 1;
        $accion = 'login_exitoso';

        $respuesta = [
            "status"  => "success",
            "message" => "Login correcto",
            "user"    => [
                "id"     => $row['id'],
                "nombre" => $row['nombre'],
                "email"  => $email
            ]
        ];
    } else {
        $exito  = 0;
        $accion = 'login_password_incorrecto';
        $respuesta = [
            "status"  => "error",
            "message" => "Credenciales incorrectas"
        ];
    }

    mysqli_free_result($res);

} else {
    $accion = 'login_usuario_no_encontrado';
    $respuesta = [
        "status"  => "error",
        "message" => "Credenciales incorrectas"
    ];
}

// 4) Registrar en bitácora_accesos
$ip  = $_SERVER['REMOTE_ADDR'] ?? '';
$ua  = $_SERVER['HTTP_USER_AGENT'] ?? '';

$ipEsc  = mysqli_real_escape_string($cnx, $ip);
$uaEsc  = mysqli_real_escape_string($cnx, $ua);
$accEsc = mysqli_real_escape_string($cnx, $accion);

if ($usuarioIdParaBitacora === 'NULL') {
    $sqlBit = "
        INSERT INTO bitacora_accesos (usuario_id, ip, user_agent, exito, accion)
        VALUES (NULL, '$ipEsc', '$uaEsc', $exito, '$accEsc')
    ";
} else {
    $sqlBit = "
        INSERT INTO bitacora_accesos (usuario_id, ip, user_agent, exito, accion)
        VALUES ($usuarioIdParaBitacora, '$ipEsc', '$uaEsc', $exito, '$accEsc')
    ";
}
mysqli_query($cnx, $sqlBit);

// 5) Responder al frontend
echo json_encode($respuesta);

mysqli_close($cnx);
