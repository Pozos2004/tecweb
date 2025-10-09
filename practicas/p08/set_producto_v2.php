<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recuperar datos del formulario
    $nombre   = $_POST['nombre'];
    $marca    = $_POST['marca'];
    $modelo   = $_POST['modelo'];
    $precio   = $_POST['precio'];
    $unidades = $_POST['unidades'];
    $detalles = $_POST['detalles'];
    $imagen   = $_POST['imagen'];

    /** Conexión a la base de datos **/
    @$link = new mysqli('localhost', 'root', '12345678a', 'marketzone');
    if ($link->connect_errno) {
        die('<p>Falló la conexión: ' . $link->connect_error . '</p>');
    }

    // Validar que no exista ya un producto con el mismo nombre, marca y modelo
    $check_query = "SELECT * FROM productos WHERE nombre='$nombre' AND marca='$marca' AND modelo='$modelo'";
    $check = $link->query($check_query);

    if ($check->num_rows > 0) {
        echo "<h3>El producto ya existe (nombre, marca y modelo repetidos).</h3>";
    } else {
        // Insertar producto con eliminado = 0
        $insert_query = "INSERT INTO productos (nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado)
                         VALUES ('$nombre', '$marca', '$modelo', $precio, $unidades, '$detalles', '$imagen', 0)";

        if ($link->query($insert_query)) {
            echo "<h3>Producto insertado correctamente:</h3>";
            echo "<ul>
                    <li><strong>Nombre:</strong> $nombre</li>
                    <li><strong>Marca:</strong> $marca</li>
                    <li><strong>Modelo:</strong> $modelo</li>
                    <li><strong>Precio:</strong> $precio</li>
                    <li><strong>Unidades:</strong> $unidades</li>
                    <li><strong>Detalles:</strong> $detalles</li>
                    <li><strong>Imagen:</strong> $imagen</li>
                  </ul>";
        } else {
            echo "<h3>Error al insertar producto: " . $link->error . "</h3>";
        }
    }

    $link->close();
} else {
    echo "<p>No se recibieron datos del formulario.</p>";
}
?>
</html>
