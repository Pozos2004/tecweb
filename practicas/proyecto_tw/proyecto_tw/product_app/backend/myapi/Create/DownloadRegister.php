<?php
namespace TECWEB\MYAPI\Create;

require_once __DIR__ . '/../../../vendor/autoload.php';
use TECWEB\MYAPI\DataBase;

class DownloadRegister extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = [];
        parent::__construct($db, $user, $pass);
    }

    public function register($jsonOBJ) {
        session_start();

        $productoId = isset($jsonOBJ->producto_id) ? (int)$jsonOBJ->producto_id : 0;
        if ($productoId <= 0) {
            $this->data = [
                "status"  => "error",
                "message" => "ID de producto inválido"
            ];
            return;
        }

        $usuarioId = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 'NULL';
        $ip  = $_SERVER['REMOTE_ADDR'] ?? '';

        $ipEsc = mysqli_real_escape_string($this->conexion, $ip);

        $sql = "
            INSERT INTO bitacora_descargas (usuario_id, producto_id, ip)
            VALUES ($usuarioId, $productoId, '$ipEsc')
        ";

        if ($this->conexion->query($sql)) {
            $this->data = [
                "status"  => "success",
                "message" => "Descarga registrada correctamente"
            ];
        } else {
            $this->data = [
                "status"  => "error",
                "message" => "Error al registrar descarga: " . mysqli_error($this->conexion)
            ];
        }
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
