<?php
namespace TECWEB\MYAPI\Auth;

use TECWEB\MYAPI\DataBase;

class UserLogin extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = [];
        parent::__construct($db, $user, $pass);
    }

    public function login($jsonOBJ) {
        session_start();

        $email    = isset($jsonOBJ->email)    ? trim($jsonOBJ->email)    : '';
        $password = isset($jsonOBJ->password) ? $jsonOBJ->password       : '';

        if ($email === '' || $password === '') {
            $this->data = [
                "status"  => "error",
                "message" => "Email y contraseña son obligatorios"
            ];
            $this->registrarAcceso(null, 0, "login_campos_vacios");
            return;
        }

        $emailEsc = mysqli_real_escape_string($this->conexion, $email);

        $sql = "SELECT id, nombre, password_hash FROM usuarios WHERE email = '$emailEsc' LIMIT 1";
        if ($result = $this->conexion->query($sql)) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password_hash'])) {
                    // Login correcto
                    $_SESSION['usuario_id']   = $row['id'];
                    $_SESSION['usuario_name'] = $row['nombre'];

                    $this->registrarAcceso($row['id'], 1, "login_exitoso");

                    $this->data = [
                        "status" => "success",
                        "message" => "Login correcto",
                        "user" => [
                            "id"     => $row['id'],
                            "nombre" => $row['nombre'],
                            "email"  => $email
                        ]
                    ];
                } else {
                    $this->registrarAcceso($row['id'], 0, "login_password_incorrecto");
                    $this->data = [
                        "status"  => "error",
                        "message" => "Credenciales incorrectas"
                    ];
                }
            } else {
                $this->registrarAcceso(null, 0, "login_usuario_no_encontrado");
                $this->data = [
                    "status"  => "error",
                    "message" => "Credenciales incorrectas"
                ];
            }
            $result->free();
        } else {
            $this->data = [
                "status"  => "error",
                "message" => "Error en la consulta: " . mysqli_error($this->conexion)
            ];
        }
    }

    private function registrarAcceso($usuarioId, $exito, $accion) {
        $ip  = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua  = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $ipEsc  = mysqli_real_escape_string($this->conexion, $ip);
        $uaEsc  = mysqli_real_escape_string($this->conexion, $ua);
        $accEsc = mysqli_real_escape_string($this->conexion, $accion);

        $usuarioId = $usuarioId !== null ? (int)$usuarioId : 'NULL';

        $sql = "
            INSERT INTO bitacora_accesos (usuario_id, ip, user_agent, exito, accion)
            VALUES ($usuarioId, '$ipEsc', '$uaEsc', $exito, '$accEsc')
        ";
        $this->conexion->query($sql);
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
