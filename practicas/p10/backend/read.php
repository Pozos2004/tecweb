<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// read.php - Buscar producto por ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    /** Conexión a la base de datos **/
    @$link = new mysqli('localhost', 'root', '', 'marketzone');
    
    if ($link->connect_errno) {
        echo json_encode(['error' => 'Error de conexión a la base de datos']);
        exit;
    }
    
    // Consulta para buscar por ID
    $query = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";
    $result = $link->query($query);
    
    if ($result && $result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        echo json_encode($producto);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
    
    $link->close();
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}
?>