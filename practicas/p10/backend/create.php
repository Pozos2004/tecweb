<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// create.php - Insertar nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Leer el JSON recibido
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Datos JSON inválidos']);
        exit;
    }
    
    // Validar datos requeridos
    $required = ['nombre', 'marca', 'modelo', 'precio', 'unidades'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
            exit;
        }
    }
    
    // Asignar valores
    $nombre = trim($data['nombre']);
    $marca = trim($data['marca']);
    $modelo = trim($data['modelo']);
    $precio = floatval($data['precio']);
    $unidades = intval($data['unidades']);
    $detalles = isset($data['detalles']) ? trim($data['detalles']) : '';
    $imagen = isset($data['imagen']) ? trim($data['imagen']) : 'img/default.png';
    
    /** Conexión a la base de datos **/
    @$link = new mysqli('localhost', 'root', '', 'marketzone');
    
    if ($link->connect_errno) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit;
    }
    
    // Validar si el producto ya existe (solo productos no eliminados)
    $check_query = "SELECT id FROM productos 
                    WHERE ((nombre = '$nombre' AND marca = '$marca') 
                       OR (marca = '$marca' AND modelo = '$modelo')) 
                    AND eliminado = 0";
    
    $check_result = $link->query($check_query);
    
    if ($check_result && $check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El producto ya existe en la base de datos']);
        $link->close();
        exit;
    }
    
    // Insertar nuevo producto
    $insert_query = "INSERT INTO productos (nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado)
                     VALUES ('$nombre', '$marca', '$modelo', $precio, $unidades, '$detalles', '$imagen', 0)";
    
    if ($link->query($insert_query)) {
        echo json_encode(['success' => true, 'message' => 'Producto registrado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar producto: ' . $link->error]);
    }
    
    $link->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>