<?php
namespace TECWEB\MYAPI\Delete;
require_once __DIR__ . '/../../../vendor/autoload.php';
use TECWEB\MYAPI\DataBase;

class ProductDelete extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

        public function delete($id) {
        // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        // SE VERIFICA HABER RECIBIDO EL ID
        if( isset($id) ) {
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
            if ( $this->conexion->query($sql) ) {
                $this->data['status'] =  "success";
                $this->data['message'] =  "Producto eliminado";
            } else {
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }        
        }
    }

    public function getData() { 
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON 
    return json_encode($this->data, JSON_PRETTY_PRINT);     
    } 
}
