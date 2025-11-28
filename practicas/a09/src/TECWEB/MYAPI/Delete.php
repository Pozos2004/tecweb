<?php
namespace TECWEB\MYAPI;
require_once 'DataBase.php';

class Delete extends DataBase {
    public function __construct($db, $user = 'root', $pass = '') { parent::__construct($db, $user, $pass); }

    public function delete($id) {
        $this->data = ['status' => 'error', 'message' => 'La consulta falló'];
        if(isset($id)) {
            $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
            if ($this->conexion->query($sql)) { $this->data['status'] = "success"; $this->data['message'] = "Producto eliminado"; }
            $this->conexion->close();
        }
    }

    public function getData() { return json_encode($this->data, JSON_PRETTY_PRINT); }
}
?>