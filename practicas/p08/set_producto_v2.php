<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Resultado Inserción</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
</head>
<body class="container mt-4">
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recuperar datos del formulario
    $nombre   = $_POST['nombre'];
    $marca    = $_POST['marca'];
    $modelo   = $_POST['modelo'];
    $precio   = $_POST['precio'];
    $unidades = $_POST['unidades'];
    $detalles = $_POST['detalles'];
    
    // Procesar la imagen
    $imagen_path = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $file_type = $_FILES['imagen']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            // Crear carpeta img si no existe
            if (!is_dir('img')) {
                mkdir('img', 0777, true);
            }
            
            // Generar nombre único para la imagen
            $file_extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nombre) . '.' . $file_extension;
            $target_path = 'img/' . $new_filename;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_path)) {
                $imagen_path = $target_path;
                echo '<div class="alert alert-success">Imagen subida correctamente: ' . $new_filename . '</div>';
            } else {
                echo '<div class="alert alert-warning">Error al subir la imagen. Usando ruta manual.</div>';
                $imagen_path = 'img/imagen.png'; // Usa tu imagen PNG directamente
            }
        } else {
            echo '<div class="alert alert-warning">Formato de imagen no válido. Usando ruta manual.</div>';
            $imagen_path = 'img/imagen.png'; // Usa tu imagen PNG directamente
        }
    } else {
        // Si no se subió imagen, usar la que ya tienes
        $imagen_path = 'img/imagen.png';
        echo '<div class="alert alert-info">Usando imagen por defecto: imagen.png</div>';
    }

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
                         VALUES ('$nombre', '$marca', '$modelo', $precio, $unidades, '$detalles', '$imagen_path', 0)";

        if ($link->query($insert_query)) {
            echo '<div class="alert alert-success"><h4>Producto insertado correctamente</h4></div>';
            echo '<ul class="list-group">
                    <li class="list-group-item"><strong>Nombre:</strong> ' . $nombre . '</li>
                    <li class="list-group-item"><strong>Marca:</strong> ' . $marca . '</li>
                    <li class="list-group-item"><strong>Modelo:</strong> ' . $modelo . '</li>
                    <li class="list-group-item"><strong>Precio:</strong> $' . number_format($precio, 2) . '</li>
                    <li class="list-group-item"><strong>Unidades:</strong> ' . $unidades . '</li>
                    <li class="list-group-item"><strong>Detalles:</strong> ' . $detalles . '</li>
                    <li class="list-group-item"><strong>Ruta de imagen:</strong> ' . $imagen_path . '</li>
                    <li class="list-group-item"><strong>Vista previa:</strong><br>';
            
            // Verificar si la imagen existe antes de mostrarla
            if (file_exists($imagen_path)) {
                echo '<img src="' . $imagen_path . '" width="100" alt="' . $nombre . '">';
            } else {
                echo '<div class="text-danger">La imagen no se encuentra en: ' . $imagen_path . '</div>';
                echo '<div class="text-info">Verifica que la carpeta img y la imagen existan</div>';
            }
            
            echo '</li></ul>';
        } else {
            echo '<div class="alert alert-danger"><h4>Error al insertar producto:</h4><p>' . $link->error . '</p></div>';
        }
    }

    $link->close();
} else {
    echo '<div class="alert alert-info">No se recibieron datos del formulario.</div>';
}
?>
</body>
</html>