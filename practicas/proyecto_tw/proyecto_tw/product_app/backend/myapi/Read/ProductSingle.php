<?php
namespace TECWEB\MYAPI\Read;
require_once __DIR__ . '/../../../vendor/autoload.php';
use TECWEB\MYAPI\DataBase;

class ProductSingle extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function single($id) {
        if( isset($id) ) {
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            if ( $result = $this->conexion->query("SELECT * FROM productos WHERE id = {$id}") ) {
                // SE OBTIENEN LOS RESULTADOS
                $row = $result->fetch_assoc();
    
                if(!is_null($row)) {
                    // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                    foreach($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
        }
    }

    public function getData() { 
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON 
    return json_encode($this->data, JSON_PRETTY_PRINT);     
    } 
}
