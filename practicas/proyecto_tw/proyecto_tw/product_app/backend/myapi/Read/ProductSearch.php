<?php
namespace TECWEB\MYAPI\Read;

require_once __DIR__ . '/../../../vendor/autoload.php';
use TECWEB\MYAPI\DataBase;

class ProductSearch extends DataBase {

    private $data = [];

    public function __construct($db, $user='root', $pass='') {
        parent::__construct($db, $user, $pass);
    }

    public function search($search) {

        if (!isset($search) || $search === '') {
            return;
        }

        // PREVENCIÓN CONTRA INYECCIONES SQL
        $search = $this->conexion->real_escape_string($search);

        $sql = "
            SELECT * 
            FROM productos 
            WHERE eliminado = 0 
            AND (
                id = '$search'
                OR nombre LIKE '%$search%'
                OR marca LIKE '%$search%'
                OR detalles LIKE '%$search%'
            )
        ";

        $result = $this->conexion->query($sql);

        if ($result === false) {
            die('Query Error: ' . $this->conexion->error);
        }

        $this->data = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
