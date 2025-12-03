<?php
namespace TECWEB\MYAPI\Create;
require_once __DIR__ . '/../../../vendor/autoload.php';
use TECWEB\MYAPI\DataBase;

class ProductAdd extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function add($jsonOBJ) {
        // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );
        if(isset($jsonOBJ->nombre)) {
            // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
            $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
            
            if ($result->num_rows == 0) {
                $this->conexion->set_charset("utf8");
                $sql = "INSERT INTO productos 
                (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado)
                VALUES (
                    '{$jsonOBJ->nombre}',
                    '{$jsonOBJ->marca}',
                    '{$jsonOBJ->modelo}',
                    {$jsonOBJ->precio},
                    '{$jsonOBJ->detalles}',
                    {$jsonOBJ->unidades},
                    '{$jsonOBJ->imagen}',
                    0
                )";
                if($this->conexion->query($sql)){
                    $this->data['status'] =  "success";
                    $this->data['message'] =  "Producto agregado";
                } else {
                    $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
                }
            }

            $result->free();
            // Cierra la conexion
                     
        }
    }

    public function getData() { 
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON 
    return json_encode($this->data, JSON_PRETTY_PRINT);     
    } 
}