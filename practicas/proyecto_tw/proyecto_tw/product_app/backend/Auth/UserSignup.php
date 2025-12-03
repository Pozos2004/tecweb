<?php
namespace TECWEB\MYAPI\Auth;

use TECWEB\MYAPI\DataBase;

class UserSignup extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = [];
        parent::__construct($db, $user, $pass);
    }

    public function signup($jsonOBJ) {
        $nombre   = isset($jsonOBJ->nombre)   ? trim($jsonOBJ->nombre)   : '';
        $email    = isset($jsonOBJ->email)    ? trim($jsonOBJ->email)    : '';
        $password = isset($jsonOBJ->password) ? $jsonOBJ->password       : '';

        if ($nombre === '' || $email === '' || $password === '') {
            $this->data = [
                "status"  => "error",
                "message" => "Todos los campos son obligatorios"
            ];
            return;
        }

        $nombreEsc = mysqli_real_escape_string($this->conexion, $nombre);
        $emailEsc  = mysqli_real_escape_string($this->conexion, $email);

        // ¿Ya existe el correo?
        $sqlCheck = "SELECT id FROM usuarios WHERE email = '$emailEsc' LIMIT 1";
        if ($result = $this->conexion->query($sqlCheck)) {
            if ($result->num_rows > 0) {
                $this->data = [
                    "status"  => "error",
                    "message" => "El correo ya está registrado"
                ];
                $result->free();
                return;
            }
            $result->free();
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sqlInsert = "
            INSERT INTO usuarios (nombre, email, password_hash)
            VALUES ('$nombreEsc', '$emailEsc', '$hash')
        ";

        if ($this->conexion->query($sqlInsert)) {
            $this->data = [
                "status"  => "success",
                "message" => "Usuario registrado correctamente"
            ];
        } else {
            $this->data = [
                "status"  => "error",
                "message" => "Error al registrar usuario: " . mysqli_error($this->conexion)
            ];
        }
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
