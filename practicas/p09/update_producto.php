<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Actualización de Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
</head>
<body class="container mt-4">
<?php
// update_producto.php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recuperar datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $precio = $_POST['precio'];
    $unidades = $_POST['unidades'];
    $detalles = $_POST['detalles'];
    $imagen = $_POST['imagen'];

    /** Conexión a la base de datos **/
    @$link = new mysqli('localhost', 'root', '', 'marketzone');
    if ($link->connect_errno) {
        die('<div class="alert alert-danger"><h4>Error de conexión:</h4><p>'. $link->connect_error .'</p></div>');
    }

    // Actualizar el producto
    $sql = "UPDATE productos SET 
            nombre = '$nombre', 
            marca = '$marca', 
            modelo = '$modelo', 
            precio = $precio, 
            unidades = $unidades, 
            detalles = '$detalles', 
            imagen = '$imagen' 
            WHERE id = $id";

    if ($link->query($sql)) {
        echo '<div class="alert alert-success"><h4>Producto actualizado correctamente</h4></div>';
        echo '<ul class="list-group">
                <li class="list-group-item"><strong>Nombre:</strong> ' . $nombre . '</li>
                <li class="list-group-item"><strong>Marca:</strong> ' . $marca . '</li>
                <li class="list-group-item"><strong>Modelo:</strong> ' . $modelo . '</li>
                <li class="list-group-item"><strong>Precio:</strong> $' . number_format($precio, 2) . '</li>
                <li class="list-group-item"><strong>Unidades:</strong> ' . $unidades . '</li>
                <li class="list-group-item"><strong>Detalles:</strong> ' . $detalles . '</li>
                <li class="list-group-item"><strong>Imagen:</strong> ' . $imagen . '</li>
              </ul>';
    } else {
        echo '<div class="alert alert-danger"><h4>Error al actualizar producto:</h4><p>' . $link->error . '</p></div>';
    }

    // Cierra la conexion
    $link->close();
    
    // Hipervínculos requeridos
    echo '<br><div class="mt-3">';
    echo '<a href="get_productos_xhtml_v2.php" class="btn btn-primary mr-2">Ver Tabla XHTML</a>';
    echo '<a href="get_productos_vigentes_v2.php?tope=100" class="btn btn-secondary">Ver Productos Vigentes</a>';
    echo '</div>';
} else {
    echo '<div class="alert alert-info">No se recibieron datos para actualizar.</div>';
}
?>
</body>
</html>