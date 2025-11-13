<?php
namespace TECWEB\MYAPI;

require_once 'DataBase.php';

class Update extends DataBase {
    public function __construct($db, $user = 'root', $pass = '') {
        parent::__construct($db, $user, $pass);
    }

    public function edit($jsonOBJ) {
        $this->data = [
            'status'  => 'error',
            'message' => 'La consulta falló'
        ];
        
        if(isset($jsonOBJ->id)) {
            $sql =  "UPDATE productos SET nombre='{$jsonOBJ->nombre}', marca='{$jsonOBJ->marca}',";
            $sql .= "modelo='{$jsonOBJ->modelo}', precio={$jsonOBJ->precio}, detalles='{$jsonOBJ->detalles}',"; 
            $sql .= "unidades={$jsonOBJ->unidades}, imagen='{$jsonOBJ->imagen}' WHERE id={$jsonOBJ->id}";
            
            $this->conexion->set_charset("utf8");
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto actualizado";
            } else {
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
            $this->conexion->close();
        }
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>