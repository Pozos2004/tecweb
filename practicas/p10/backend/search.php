<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// search.php - Búsqueda por nombre, marca o detalles usando LIKE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : '';
    
    if (empty($searchTerm)) {
        echo json_encode(['error' => 'Término de búsqueda vacío']);
        exit;
    }
    
    /** Conexión a la base de datos **/
    @$link = new mysqli('localhost', 'root', '', 'marketzone');
    
    if ($link->connect_errno) {
        echo json_encode(['error' => 'Error de conexión a la base de datos']);
        exit;
    }
    
    // Escapar el término de búsqueda para seguridad
    $searchTerm = $link->real_escape_string($searchTerm);
    
    // Consulta con LIKE para búsqueda flexible
    $query = "SELECT * FROM productos 
              WHERE (nombre LIKE '%$searchTerm%' 
                 OR marca LIKE '%$searchTerm%' 
                 OR detalles LIKE '%$searchTerm%') 
              AND eliminado = 0 
              ORDER BY nombre";
    
    $result = $link->query($query);
    
    if ($result) {
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        echo json_encode($productos);
    } else {
        echo json_encode(['error' => 'Error en la consulta']);
    }
    
    $link->close();
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>