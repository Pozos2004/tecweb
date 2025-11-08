<?php
namespace TECMEB\MYAPI;

use TECMEB\MYAPI\DataBase as DataBase;

require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {
    private $response = array();
    
    public function __construct($db, $user = 'root', $pass = '') {
        $this->response = array();
        parent::__construct($user, $pass, $db);
    }
    
    public function list() {
        $this->response = array();
        
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if(!is_null($rows)) {
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $this->response[$num][$key] = utf8_encode($value);
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
    }
    
    public function single($id) {
        $this->response = array();
        
        $sql = "SELECT * FROM productos WHERE id = {$id} AND eliminado = 0";
        if ($result = $this->conexion->query($sql)) {
            $row = $result->fetch_assoc();
            if(!is_null($row)) {
                foreach($row as $key => $value) {
                    $this->response[$key] = utf8_encode($value);
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
    }
    
    public function singleByName($name) {
        $this->response = array();
        
        $sql = "SELECT * FROM productos WHERE nombre = '{$name}' AND eliminado = 0";
        if ($result = $this->conexion->query($sql)) {
            $row = $result->fetch_assoc();
            if(!is_null($row)) {
                foreach($row as $key => $value) {
                    $this->response[$key] = utf8_encode($value);
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
    }
    
    public function search($search) {
        $this->response = array();
        
        $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if(!is_null($rows)) {
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $this->response[$num][$key] = utf8_encode($value);
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
    }
    
    public function add($producto) {
        $this->response = array('status' => 'error', 'message' => 'Ya existe un producto con ese nombre');
        
        $sql = "SELECT * FROM productos WHERE nombre = '{$producto->nombre}' AND eliminado = 0";
        $result = $this->conexion->query($sql);
        
        if ($result->num_rows == 0) {
            $this->conexion->set_charset("utf8");
            $sql = "INSERT INTO productos VALUES (null, '{$producto->nombre}', '{$producto->marca}', '{$producto->modelo}', {$producto->precio}, '{$producto->detalles}', {$producto->unidades}, '{$producto->imagen}', 0)";
            
            if($this->conexion->query($sql)){
                $this->response['status'] = "success";
                $this->response['message'] = "Producto agregado";
            } else {
                $this->response['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
        } else {
            $this->response['message'] = "Ya existe un producto con el nombre: '{$producto->nombre}'";
        }
        $result->free();
    }
    
    public function update($producto) {
        $this->response = array('status' => 'error', 'message' => 'Error al actualizar el producto');
        
        $sqlCheck = "SELECT * FROM productos WHERE nombre = '{$producto->nombre}' AND id != {$producto->id} AND eliminado = 0";
        $resultCheck = $this->conexion->query($sqlCheck);
        
        if ($resultCheck->num_rows == 0) {
            $this->conexion->set_charset("utf8");
            $sql = "UPDATE productos SET 
                    nombre = '{$producto->nombre}',
                    marca = '{$producto->marca}',
                    modelo = '{$producto->modelo}',
                    precio = {$producto->precio},
                    detalles = '{$producto->detalles}',
                    unidades = {$producto->unidades},
                    imagen = '{$producto->imagen}'
                    WHERE id = {$producto->id} AND eliminado = 0";
                    
            if($this->conexion->query($sql)){
                $this->response['status'] = "success";
                $this->response['message'] = "Producto actualizado correctamente";
            } else {
                $this->response['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
        } else {
            $this->response['message'] = "Ya existe otro producto con ese nombre";
        }
        $resultCheck->free();
    }
    
    public function delete($id) {
        $this->response = array('status' => 'error', 'message' => 'La consulta falló');
        
        $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
        if ($this->conexion->query($sql)) {
            $this->response['status'] = "success";
            $this->response['message'] = "Producto eliminado";
        } else {
            $this->response['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
        }
    }
    
    public function getData() {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
    
    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>