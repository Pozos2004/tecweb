<?php
// set_producto_v2.php - Para INSERTAR nuevos productos
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
    @$link = new mysqli('localhost', 'root', '', 'marketzone');
    if ($link->connect_errno) {
        die('<div class="alert alert-danger"><h4>Error de conexión:</h4><p>'. $link->connect_error .'</p></div>');
    }

    /** Validar si ya existe un producto con el mismo nombre, marca y modelo **/
    $check_query = "SELECT * FROM productos WHERE nombre='$nombre' AND marca='$marca' AND modelo='$modelo'";
    $check = $link->query($check_query);

    if ($check->num_rows > 0) {
        echo '<div class="alert alert-warning"><h4>El producto ya existe</h4>
              <p>Ya hay un registro con el mismo nombre, marca y modelo.</p></div>';
    } else {
        /** INSERT en la base de datos **/
        $insert_query = "INSERT INTO productos (nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado)
                         VALUES ('$nombre', '$marca', '$modelo', $precio, $unidades, '$detalles', '$imagen', 0)";

        if ($link->query($insert_query)) {
            echo '<div class="alert alert-success"><h4> Producto registrado correctamente</h4></div>';
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
            echo '<div class="alert alert-danger"><h4> Error al registrar producto:</h4><p>' . $link->error . '</p></div>';
        }
    }

    $link->close();
} else {
    echo '<div class="alert alert-info">No se recibieron datos del formulario.</div>';
}
?>