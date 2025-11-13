<?php
namespace TECWEB\MYAPI;

abstract class DataBase {
    protected $conexion;
    protected $data = [];

    public function __construct($db, $user = 'root', $pass = '') {
        $this->conexion = @mysqli_connect('localhost', $user, $pass, $db);
    
        if(!$this->conexion) {
            die('¡Base de datos NO conectada!');
        }
    }

    abstract public function getData();
}
?>